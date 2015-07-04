<?php 

class OPanda_FreshmailSubscriptionService extends OPanda_Subscription {
    
    /**
     * Make a call to the Freshmail API.
     */
    public function request( $strUrl, $arrParams = array() ) {
        
        $apiKey = get_option('opanda_freshmail_apikey', false);
        $apiSecret = get_option('opanda_freshmail_apisecret', false);
        
        if( empty( $apiKey ) || empty( $apiSecret ) )
            throw new OPanda_SubscriptionException ('The API Key or API Secret not set.');
        
        $isPost = empty( $arrParams ) ? false : true;
        $url = 'https://api.freshmail.com/rest/' . $strUrl;
        
        if ( $isPost ) {
            $jsonData = json_encode( $arrParams );
            $strSign = sha1( $apiKey . '/rest/' . $strUrl . $jsonData . $apiSecret );
        } else {
            $strSign = sha1( $apiKey . '/rest/' . $strUrl . $apiSecret );
        }
        
        $arrHeaders = array();
        $arrHeaders['X-Rest-ApiKey'] = $apiKey;
        $arrHeaders['X-Rest-ApiSign'] = $strSign;
        $arrHeaders['Content-Type'] = $isPost ? 'application/json' : 'text/json';
        
        if ( $isPost ) {

            $result = wp_remote_post( $url, array(
                'headers' => $arrHeaders,
                'timeout' => 30,
                'body' => $jsonData
            ));
            
        } else {

            $result = wp_remote_get( $url, array(
                'headers' => $arrHeaders,
                'timeout' => 30
            ));
        }

        if (is_wp_error( $result )) {
            throw new OPanda_SubscriptionException( sprintf( __( 'Unexpected error occurred during connection to FreshMail. %s', 'optinpanda' ), $result->get_error_message() ) );
        }

        if ( empty( $result['body'] ) ) return array();
        return json_decode( $result['body'] );
    }
    
    /**
     * Returns lists available to subscribe.
     * 
     * @since 1.0.0
     * @return mixed[]
     */
    public function getLists() {
        
        $result = $this->request('subscribers_list/lists' );
        
        if ( isset( $result->errors ) ) {
            throw new OPanda_SubscriptionException( $result->errors[0]->message ); 
        }


        foreach( $result->lists as $value ) {
            $lists[] = array(
                'title' => $value->name,
                'value' => $value->subscriberListHash
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
        
        $email = $identityData['email'];
        $result = $this->request("subscriber/get/$listId/$email" );

        if ( isset( $result->errors ) ) {
            
            // 1311: the subscriber not found
            if ( 1311 !== $result->errors[0]->code  ) {
                throw new OPanda_SubscriptionException( $result->errors[0]->message ); 
            }
            
        } else {
            
            $state = intval( $result->data->state );
            if ( $doubleOptin ) return array('status' => $state === 1 ? 'subscribed' : 'pending');
            return array('status' => 'subscribed');                  
        }  
        
        $data = array(
            'email' => $identityData['email'],
            'list'  => $listId,
            'confirm' => $doubleOptin ? 1 : 0,
            'state' => $doubleOptin ? 2 : 1
        );

        $fields = $identityData;

        unset( $fields['email'] );        
        unset( $fields['name'] );
        unset( $fields['family'] );
        unset( $fields['displayName'] );
            
        $data['custom_fields'] = $fields;
        $result = $this->request('subscriber/add', $data, true );
        
        if ( isset( $result->errors ) ) {
            
            // 1304: the subscriber already exists
            if ( 1304 === $result->errors[0]->code  ) {
                return array('status' => 'subscribed');
            } else {
                throw new OPanda_SubscriptionException( $result->errors[0]->message ); 
            }
        }  

        return array('status' => $doubleOptin ? 'pending' : 'subscribed');
    }
    
    /**
     * Checks if the user subscribed.
     */  
    public function check( $identityData, $listId, $contextData ) { 
        
        $email = $identityData['email'];
        $result = $this->request("subscriber/get/$listId/$email" );
        
        if ( isset( $result->errors ) ) {
            throw new OPanda_SubscriptionException( $result->errors[0]->message ); 
        } else {
            $state = intval( $result->data->state );
            return array('status' => $state === 1 ? 'subscribed' : 'pending');             
        }  
    }
    
    /**
     * Returns custom fields.
     */
    public function getCustomFields( $listId ) {

        try {
            
            $mappingRules = array(
                'text' => 'any',
                'number' => array('integer', 'checkbox')
            );
            
            $result = $this->request("subscribers_list/getFields", array('hash' => $listId));
            
            if ( isset( $result->errors ) ) {
                throw new OPanda_SubscriptionException( $result->errors[0]->message ); 
            }
        
            $customFields = array();
            foreach( $result->fields as $field ) {
                
                $pluginFieldType = isset( $mappingRules[$field->type] ) 
                        ? $mappingRules[$field->type] 
                        : strtolower( $field->type );

                $can = array(
                    'changeType' => true,
                    'changeReq' => false,
                    'changeDropdown' => false,
                    'changeMask' => true
                );
            
                $customFields[] = array(
   
                    'fieldOptions' => array(),
                    
                    'mapOptions' => array(
                        'id' => $field->tag,
                        'name' => $field->tag,
                        'title' => $field->name,
                        'labelTitle' => $field->name,
                        'type' => $field->type,
                        'mapTo' => ( is_array($pluginFieldType) || $pluginFieldType == 'any') ? $pluginFieldType : array( $pluginFieldType )
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
            
        } catch(Exception $ext) {
            throw new OPanda_SubscriptionException ('[custom-fields]: ' . $ext->getMessage());   
       }
    }
}
