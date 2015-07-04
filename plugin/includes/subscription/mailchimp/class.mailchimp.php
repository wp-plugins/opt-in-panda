<?php 

class OPanda_MailchimpSubscriptionService extends OPanda_Subscription {
    
    public function initMailChimpLibs() {
        
        $this->apiKey = get_option('opanda_mailchimp_apikey');

        require_once 'libs/mailchimp.php';    
        return new OPanda_MailChimp( $this->apiKey );
    }

    /**
     * Returns lists available to subscribe.
     * 
     * @since 1.0.0
     * @return mixed[]
     */
    public function getLists() {
        
        $MailChimp = $this->initMailChimpLibs();
        $response = $MailChimp->call('lists/list');
        
        if ( !$response ) {
            throw new OPanda_SubscriptionException( __( 'The API Key is incorrect.', 'optinpanda' ) );   
        }
            
        $lists = array();
        foreach( $response['data'] as $value ) {
            $lists[] = array(
                'title' => $value['name'],
                'value' => $value['id']
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

        $vars = $this->refine( $identityData );
        $email = $identityData['email'];

        if ( empty( $vars['FNAME'] ) && !empty( $identityData['name'] ) ) $vars['FNAME'] = $identityData['name'];
        if ( empty( $vars['LNAME'] ) && !empty( $identityData['family'] ) ) $vars['LNAME'] = $identityData['family'];
        if ( empty( $vars['FNAME'] ) && !empty( $identityData['displayName'] ) ) $vars['FNAME'] = $identityData['displayName'];
        
        $MailChimp = $this->initMailChimpLibs();
        $response = $MailChimp->call('lists/subscribe', array(
            'id'                => $listId,
            'email'             => array('email' => $email),
            'merge_vars'        => $vars,
            'double_optin'      => $doubleOptin,
            'update_existing'   => false,
            'replace_interests' => false,
            'send_welcome'      => !$doubleOptin ? true : false,
        ));

        if( isset($response['error']) && $response['code'] != 214 ) {
            throw new OPanda_SubscriptionException ( '[subscribe]: ' . $response['error'] );
            
        // already exits
            
        }  else if ( isset($response['error']) && $response['code'] == 214 ) {
            
            $response = $MailChimp->call('lists/update-member', array(
                'id'                => $listId,
                'email'             => array('email' => $email),
                'merge_vars'        => $vars
            ));
            
            if( isset($response['error'] ))
                throw new OPanda_SubscriptionException ( '[subscribe]: ' . $response['error'] );
        }

        return array('status' => $doubleOptin ? 'pending' : 'subscribed');
    }
    
    /**
     * Checks if the user subscribed.
     */  
    public function check( $identityData, $listId, $contextData ) { 
        
        $MailChimp = $this->initMailChimpLibs();
        $response = $MailChimp->call('/lists/member-info', array( 
                       'id' => $listId,
                       'emails' => array( 
                           array('email' => $identityData['email'])           
                       )
                    ));
         
        if( !sizeof($response) && !isset($response['data'][0]['status']) )
            throw new OPanda_SubscriptionException('[check]: Unexpected error occurred.');
         
        return array('status' => $response['data'][0]['status']);
    }
    
    /**
     * Prepares values enters by the user to save.
     */
    public function prepareFieldValueToSave( $mapOptions, $value ) {
        if ( empty( $value ) ) return $value;
        
        $fieldType = $mapOptions['service']['field_type'];

        if ( $fieldType == 'birthday' ) {
            
            $dateformat = strtolower( $mapOptions['service']['dateformat'] );
            $parts = explode('/', $value);
            
            if ( $dateformat === 'dd/mm' ) {
                return $parts[1] . '/' . $parts[0];
            } else {
                return $parts[0] . '/' . $parts[1];
            }
            
        } elseif ( $fieldType == 'phone' ) {

            $phoneformat = strtolower( $mapOptions['service']['phoneformat'] );
            if ( $phoneformat === 'us' ) {
                
                if ( preg_match('/\((\d\d\d)\)\s(\d\d\d)\-(\d\d\d\d)/', $value, $matches ) ) {
                    return $matches[1] . '-' . $matches[2] . '-' . $matches[3];
                } else {
                    return $value;
                }
                
            } else {
                return $value;
            }
            
        }

        return $value;
    }
     
    /**
     * Returns custom fields.
     */
    public function getCustomFields( $listId ) {
        
        $MailChimp = $this->initMailChimpLibs();
        $response = $MailChimp->call('lists/merge-vars', array(
            "id" => array( $listId )
        ));

        if( isset($response['error_count']) && $response['error_count'] > 0 )
            throw new OPanda_SubscriptionException ( sprintf( __( 'Error: %s. Please try to refresh this page or update your <a href="%s" target="_blank">subscription settings</a>.' ), $response['errors'][0]['error'], opanda_get_settings_url('social') ) );  
        
        if ( !isset($response['data'][0]['merge_vars']) ) return array();
        
        $customFields = array();
        $mappingRules = array(
            'radio' => 'dropdown',
            'text' => array('text', 'checkbox'),
            'number' => array('integer', 'checkbox')
        );

        foreach( $response['data'][0]['merge_vars'] as $mergeVars ) {
            $fieldType = $mergeVars['field_type'];
                    
            $pluginFieldType = isset( $mappingRules[$fieldType] ) 
                    ? $mappingRules[$fieldType] 
                    : strtolower( $fieldType );
            
            if ( in_array($pluginFieldType, array('email'))) continue;            
            
            $can = array(
                'changeType' => true,
                'changeReq' => false,
                'changeDropdown' => false,
                'changeMask' => true
            );
            
            $fieldOptions = array();
            if ( 'dropdown' === $pluginFieldType ) {
                
                foreach ( $mergeVars['choices'] as $choice ) {
                    $fieldOptions['choices'][] = $choice;
                }
                
            } else if ( 'birthday' === $pluginFieldType ) {
                
                $fieldOptions['mask'] = '99/99';
                $fieldOptions['maskPlaceholder'] = strtolower( $mergeVars['dateformat'] );
                $can['changeMask'] = false;
                
            } else if ( 'phone' === $pluginFieldType ) {
                
                if ( 'US' === $mergeVars['phoneformat'] ) {

                    $fieldOptions['mask'] = '(999) 999-9999';
                    $fieldOptions['maskPlaceholder'] = '(___) ___-____';
                    $can['changeMask'] = false;
                }
            }
            
            $fieldOptions['req'] = $mergeVars['req'];

            $customFields[] = array(
                
                'fieldOptions' => $fieldOptions,
                
                'mapOptions' => array(
                    'req' => $mergeVars['req'],
                    'id' => $mergeVars['tag'],
                    'name' => $mergeVars['tag'],
                    'title' => sprintf('%s [%s]', $mergeVars['name'], $mergeVars['tag'] ),
                    'labelTitle' => $mergeVars['name'],
                    'mapTo' => is_array($pluginFieldType) ? $pluginFieldType : array( $pluginFieldType ),
                    'service' => $mergeVars
                ),
                
                'premissions' => array(
                    
                    'can' => $can,
                    'notices' => array(
                        'changeReq' => __('You can change this checkbox in your MailChimp account.', 'bizpanda'),
                        'changeDropdown' => sprintf( __('Please visit your MailChimp account to modify the choices. <a href="%s" target="_blank">Learn more</a>.', 'bizpanda'), "http://kb.mailchimp.com/merge-tags/using/getting-started-with-merge-tags#List-merge-tags" )
                    ), 
                )
            );
        }

        return $customFields;
    }
    
    public function getNameFieldIds() {
        return array( 'FNAME' => 'name', 'LNAME' => 'family' );
    }
}
