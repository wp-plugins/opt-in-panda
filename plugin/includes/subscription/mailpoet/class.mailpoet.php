<?php 

class OPanda_MailPoetSubscriptionService extends OPanda_Subscription {

    /**
     * Returns lists available to subscribe.
     * 
     * @since 1.0.0
     * @return mixed[]
     */
    public function getLists() {
        
        if ( !defined('WYSIJA') ) {
            throw new OPanda_SubscriptionException( __( 'The MailPoet plugin is not found on your website.', 'optinpanda' ) ); 
        }
        
        $model_list = WYSIJA::get('list','model');
        
        $lists = array();
        foreach( $model_list->getLists() as $item ) {
            $lists[] = array(
                'title' => $item['name'],
                'value' => $item['list_id']
            );
        }

        return array(
            'items' => $lists
        ); 
    }

    /**
     * Subscribes the person.
     */
    public function subscribe( $identityData, $listId, $doubleOptin, $contextData ) {

        if ( !defined('WYSIJA') ) {
            throw new OPanda_SubscriptionException( __( 'The MailPoet plugin is not found on your website.', 'optinpanda' ) ); 
        }
        
        $userModel = WYSIJA::get('user','model');
        $userListModel = WYSIJA::get('user_list','model');
        $manager = WYSIJA::get('user','helper');   
            
        $subscriber = $userModel->getOne(false, array('email' => $identityData['email'] ));
        $customs = $this->refine( $identityData );

        if ( empty( $customs['firstname'] ) && !empty( $identityData['name'] ) ) $customs['firstname'] = $identityData['name'];
        if ( empty( $customs['lastname'] ) && !empty( $identityData['family'] ) ) $customs['lastname'] = $identityData['family'];
        if ( empty( $customs['firstname'] ) && !empty( $identityData['displayName'] ) ) $customs['firstname'] = $identityData['displayName'];

        // ---
        // if already subscribed
        
        if ( !empty( $subscriber ) ) {
            
            $subscriberId = intval( $subscriber['user_id'] );

            // adding the user to the specified list if the user has not been yet added
            
            $lists = $userListModel->get_lists( array( $subscriberId ) );

            if ( !isset( $lists[$subscriberId] ) || !in_array( $listId, $lists[$subscriberId] ) ) {
                $manager->addToList( $listId, array( $subscriberId ) );
            }
            
            if ( isset( $customs['firstname'] ) || isset( $customs['lastname'] ) ) {
                
                $modelUser = WYSIJA::get('user', 'model');
                $data = array();
                
                if ( isset( $customs['firstname'] ) ) $data['firstname'] = $customs['firstname'];
                if ( isset( $customs['lastname'] ) ) $data['lastname'] = $customs['lastname'];

                $modelUser->update($data, array( 'user_id' => $subscriberId ));
            } 
            
            // adds custom fields
            WJ_FieldHandler::handle_all( $customs, $subscriberId );
            
            if ( !$doubleOptin ) return array('status' =>  'subscribed');
            
            // sends the confirmation email
            
            $status = intval($subscriber['status'] );
            if ( 0 === $status ) $manager->sendConfirmationEmail( $subscriberId, true, array( $listId ) );
            
            return array('status' => 1 === $status ? 'subscribed' : 'pending');
        }
        
        // ---
        // if it's a new subscriber
        
        $ip = $manager->getIP();
        
        $userData = array(
            'email' => $identityData['email'],
            'status' => !$doubleOptin ? 1 : 0,
            'ip' => $ip,
            'created_at' => time()
        );
        
        if ( !empty( $identityData['name'] ) )
            $userData['firstname'] = $identityData['name'];

        if ( !empty( $identityData['family'] ) )
            $userData['lastname'] = $identityData['family'];

        if ( empty( $identityData['name'] ) && empty( $identityData['family'] ) && !empty( $identityData['displayName'] ) )
            $userData['firstname'] = $identityData['displayName'];
        
        $subscriberId = $userModel->insert( $userData );
        
        // adds custom fields
        WJ_FieldHandler::handle_all( $customs, $subscriberId );
            
        if ( !$subscriberId ) {
            throw new OPanda_SubscriptionException ( '[subscribe]: Unable to add a subscriber.' ); 
        }
        
        // adds the user the the specified list
        
        $manager->addToList( $listId, array( $subscriberId ) );
        
        // sends the confirmation email

        if ( $doubleOptin ) $manager->sendConfirmationEmail( $subscriberId, true, array( $listId ) );
        return array('status' => $doubleOptin ? 'pending' : 'subscribed');
    }
    
    /**
     * Checks if the user subscribed.
     */  
    public function check( $identityData, $listId, $contextData ) { 
        
        $userModel = WYSIJA::get('user','model');
        $userListModel = WYSIJA::get('user_list','model');
        $manager = WYSIJA::get('user','helper');   
        
        $subscriber = $userModel->getOne(false, array('email' => $identityData['email'] ));
        if ( empty( $subscriber ) ) {
            throw new OPanda_SubscriptionException( __( 'The operation is canceled because the website administrator deleted your email from the list.', 'optinpanda' ) ); 
        }
        
        $subscriberId = intval( $subscriber['user_id'] );
        
        // adding the user to the specified list if the user has not been yet added

        $lists = $userListModel->get_lists( array( $subscriberId ) );
        if ( !isset( $lists[$subscriberId] ) || !in_array( $listId, $lists[$subscriberId] ) ) {
            $manager->addToList( $listId, array( $subscriberId ) );
        }
        
        $status = intval( $subscriber['status'] );
        
        if ( 1 === $status ) return array('status' => 'subscribed');
        return array('status' => 'pending');
    }
    
    /**
     * Returns custom fields.
     */
    public function getCustomFields( $listId ) {
        
        $mappingRules = array(
            'input' => 'text',
            'textarea' => 'text',
            'radio' => 'dropdown',
            'select' => 'dropdown',            
            
        );
        
        $manager = WYSIJA::get('form_engine','helper');
        $result = $manager->get_custom_fields();

        $customFields = array();
        foreach ($result as $field ) {
            $fieldType = $field['column_type'];
            
            $pluginFieldType = isset( $mappingRules[$fieldType] ) 
                    ? $mappingRules[$fieldType] 
                    : strtolower( $fieldType );
            
            $fieldOptions = array(
                'req' => isset( $field['params']['required'] ) ? ( ( $field['params']['required'] ) ? true : false ) : false
            );
            
            $changeMask = true;
            
            if ( 'text' === $pluginFieldType ) {
                
                if ( isset( $field['params']['validate'] ) && !empty( $field['params']['validate'] ) ) {
                    
                    if ( 'onlyNumberSp' == $field['params']['validate'] ) {
                        $pluginFieldType = 'integer';
                    } elseif ( 'onlyLetterSp' == $field['params']['validate'] ) {
                        $fieldOptions['validation'] = '/^[a-z]+$/i';
                    } elseif ( 'onlyLetterNumber' == $field['params']['validate'] ) {
                        $fieldOptions['validation'] = '/^[0-9a-z]+$/i';
                    } elseif ( 'phone' == $field['params']['validate'] ) {
                        $pluginFieldType = 'phone';
                        $fieldOptions['validation'] = '/^[\s\+\-\#\(\)\d]+$/';
                    }
                }

            } elseif ( 'date' === $pluginFieldType  ) {
                
                if ( isset( $field['params']['date_type'] ) && !empty( $field['params']['date_type'] ) ) {
                    
                    if ( 'year_month' == $field['params']['date_type'] ) {
                        
                        $pluginFieldType = 'text';
                        $fieldOptions['mask'] = '99/9999';
                        $fieldOptions['validation'] = 'month/year';
                        $changeMask = false;
                        
                    } elseif ( 'month' == $field['params']['date_type'] ) {
                        
                        $pluginFieldType = 'text';
                        $fieldOptions['mask'] = '99';
                        $fieldOptions['validation'] = 'month';
                        $changeMask = false;
                        
                    } elseif ( 'year' == $field['params']['date_type'] ) {
                        
                        $pluginFieldType = 'text';
                        $fieldOptions['mask'] = '9999';
                        $fieldOptions['validation'] = 'year';
                        $changeMask = false;
                        
                    }
                }
                
            } elseif ( 'dropdown' === $pluginFieldType  ) {
                
                if ( isset( $field['params']['values'] ) && !empty( $field['params']['values'] ) ) {
                    
                    foreach ( $field['params']['values'] as $choice ) {
                        $fieldOptions['choices'][] = $choice['value'];
                    }
                }
                
            } elseif ( 'checkbox' === $pluginFieldType  ) {
                
                if ( isset( $field['params']['values'] ) && !empty( $field['params']['values'] ) ) {
                    
                    if ( !empty( $field['params']['values'][0]['value'] ) ) {
                        $fieldOptions['onValue'] = $field['params']['values'][0]['value'];
                        $fieldOptions['offValue'] = '';
                    }
 
                    if ( $field['params']['values'][0]['is_checked'] ) {
                        $fieldOptions['markedByDefault'] = $field['params']['values'][0]['is_checked'];
                    }
                }
                
            }
            
            if ( in_array($pluginFieldType, array('html', 'list', 'divider'))) continue;     
            
            $can = array(
                'changeType' => true,
                'changeReq' => false,
                'changeDropdown' => false,
                'changeMask' => $changeMask
            );
            
            $customFields[] = array(
                
                'fieldOptions' => $fieldOptions,
                
                'mapOptions' => array(
                    'req' => isset( $field['params']['required'] ) ? ( ( $field['params']['required'] ) ? true : false ) : false,
                    'id' => $field['column_name'],
                    'name' => $field['column_name'],
                    'title' => $field['name'],
                    'labelTitle' => $field['name'],
                    'mapTo' => is_array($pluginFieldType) ? $pluginFieldType : array( $pluginFieldType ),
                    'service' => $field
                ),
                
                'premissions' => array(
                    
                    'can' => $can,
                    'notices' => array(
                        'changeReq' => __('You can change this checkbox in the settings of your MailPoet forms.', 'bizpanda'),
                        'changeDropdown' => __('Please visit the form editor in MailPoet to modify the choices.' )
                    ), 
                )
            );
        }

        return $customFields;
    }
    
    public function prepareFieldValueToSave( $mapOptions, $value ) {
        if ( empty( $value ) ) return $value;
        $fieldType = $mapOptions['service']['column_type'];

        if ( $fieldType == 'checkbox' ) {
            
            return ( !empty( $value ) ) ? 1 : 0;
            
        } else if ( $fieldType == 'date' ) {
            
            if ( 'year_month' == $mapOptions['service']['params']['date_type'] ) {
                
                $parts = explode('/', $value);
                return array(
                    'year' => $parts[1],
                    'month' => $parts[0],
                    'day' => 1
                );

            } elseif ( 'month' == $mapOptions['service']['params']['date_type'] ) {

                return array(
                    'year' => 1,
                    'month' => $value,
                    'day' => 1
                );

            } elseif ( 'year' == $mapOptions['service']['params']['date_type'] ) {

                return array(
                    'year' => $value,
                    'month' => 1,
                    'day' => 1
                );

            } else {
                
                $parts = explode('-', $value); 
                return array(
                    'year' => $parts[0],
                    'month' => $parts[1],
                    'day' => $parts[2]
                );
                
            }
        }
        
        return $value;
    }
}
