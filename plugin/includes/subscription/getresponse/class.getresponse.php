<?php

class OPanda_GetresponseSubscriptionService extends OPanda_Subscription {
    
    protected $apiUrl = 'http://api2.getresponse.com';

    public function initGetResponseLibs( ) {
        
        $this->apiKey = get_option('opanda_getresponse_apikey');

        require_once 'libs/getresponse.php';
        return new jsonRPCClient($this->apiUrl);
    }

    /**
     * Returns lists available to subscribe.
     * 
     * @since 1.0.0
     * @return mixed[]
     */
    public function getLists() {
        
        $getResponse = $this->initGetResponseLibs();
        
        try {
            $campaigns = $getResponse->get_campaigns( $this->apiKey, array (            
                'name' => array ( 'CONTAINS' => '%' )
            )); 
        } catch (Exception $ex) {
            
            $message = $ex->getMessage();
            
            // The API Key may be passed incorrectly
            // "Request have return error: Invalid params"
            if (strpos( $message, 'Invalid params') ) {
                throw new OPanda_SubscriptionException( __( 'The API Key is incorrect.', 'optinpanda' ) ); 
            }
            
            throw $ex;
        }

        $lists = array();
        foreach( $campaigns as $key => $value ) {
            $lists[] = array(
                'title' => $value['name'],
                'value' => $key
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

        if ( !$doubleOptin )
            throw new OPanda_SubscriptionException ('GetResponse requires the double opt-in. But the option "doubleOptin" set to false.');
        
        $getResponse = null;
        
        $customs = array();
        foreach( $identityData as $customName => $customValue ) {
            if ( in_array( $customName, array('email', 'name', 'family', 'fullname', 'displayName') ) ) continue;
            
            $customs[] = array(
                'name' => $customName,
                'content' => $customValue
            );
        }

        try {  
            $getResponse = $this->initGetResponseLibs();
            
            $dataToPass = array (                
                'campaign'  => $listId,
                'email'     => $identityData['email']
            );
            
            if ( isset( $identityData['name'] ) && isset( $identityData['family'] ) ) {
                $dataToPass['name'] = $identityData['name'] . ' ' . $identityData['family']; 
            } elseif ( isset( $identityData['name'] ) ) {
                $dataToPass['name'] = $identityData['name'];
            }
            
            $dataToPass['customs'] = $customs;            
            $getResponse->add_contact( $this->apiKey, $dataToPass );
            
            return array('status' => 'pending');
            
        } catch(Exception $ext) {
            
            $status = null;
            
            // already waiting confirmation:
            // "Request have return error: Contact already queued for target campaign"
            if ( strpos( $ext->getMessage(), 'queued for target campaign' ) ) {
                $status = 'pending';
            }
            
            // already waiting confirmation:
            // "Request have return error: Contact already added to target campaign"
            if ( strpos( $ext->getMessage(), 'already added' ) ) {
                $status = 'subscribed';
            } 
            
            if ( $status !== null ) {
 
                try {  
    
                    $response = $getResponse->get_contacts( $this->apiKey, array ( 
                        'campaigns'  => array($listId),
                        'email' => array('EQUALS' => $identityData['email'])
                    ));

                    foreach( $response as $contactId => $contactData ) {
                                            
                        $dataToPass = array();
                        $dataToPass['contact'] = $contactId;
                        $dataToPass['customs'] = $customs;
                        
                        $getResponse->set_contact_customs( $this->apiKey, $dataToPass );
                    }

                } catch(Exception $ext) {
                    throw new OPanda_SubscriptionException ('[update]: ' . $ext->getMessage());
                }
                
                return array('status' => $status);
            }

            /**
            if( !in_array(md5($ext->getMessage()), array('ad9f84f2ed3f3352d179ee2d5a17a1a4','92a2ebe1277e1bff0d8ee02b523c28b5')) )
                throw new OPanda_SubscriptionException ('addContact: ' . $ext->getMessage());  */   
            
            throw new OPanda_SubscriptionException ('[subscribe]: ' . $ext->getMessage());
        }   
    }
    
    /**
     * Checks if the user subscribed.
     */  
    public function check( $identityData, $listId, $contextData ) { 
       
        $getResponse = $this->initGetResponseLibs();

        try { 

            $response = $getResponse->get_contacts( $this->apiKey, array ( 
                'campaigns'  => array($listId),
                'email' => array('EQUALS' => $identityData['email'])
            ));
            
        } catch(Exception $ext) {
            throw new OPanda_SubscriptionException ('[check]: ' . $ext->getMessage());
        }
        
        if( isset($response['error']) ) return array('status' => 'false');
        return array('status' => sizeof($response) ? 'subscribed' : 'pending');
    }
    
    /**
     * Returns custom fields.
     */
    public function getCustomFields( $listId ) {

        try {
            
            $getResponse = $this->initGetResponseLibs();
            $response = $getResponse->get_account_customs( $this->apiKey);

            $customFields = array();
            $mappingRules = array(
                'textarea' => 'text',
                'single_select' => 'dropdown',
                'radio' => 'dropdown',
            );

            foreach( $response as $id => $field ) {

                $pluginFieldType = isset( $mappingRules[$field['input_type']] ) 
                        ? $mappingRules[$field['input_type']] 
                        : strtolower( $field['input_type'] );
            
                $fieldOptions = array();
                
                if ( 'date' === $field['content_type'] ) {
                    $pluginFieldType = 'date';
                } elseif ( 'number' === $field['content_type'] ) {
                    $pluginFieldType = 'integer';
                } elseif ( 'phone' === $field['content_type'] ) {
                    $pluginFieldType = 'phone';
                    $fieldOptions['validation'] = '/\+\d+/';
                    $fieldOptions['validationError'] = __('Incorrect value. Please enter a valid phone number preceded by "+" and a country code.', 'bizpanda');
                }
                
                if ( in_array($pluginFieldType, array('multi_select'))) continue;            
            
                $can = array(
                    'changeType' => true,
                    'changeReq' => true,
                    'changeDropdown' => false,
                    'changeMask' => true
                );

                if ( 'dropdown' === $pluginFieldType ) {
                
                    foreach ( $field['contents'] as $choice ) {
                        $fieldOptions['choices'][] = $choice;
                    }
                } elseif ( 'checkbox' === $pluginFieldType ) {
                    
                    if ( isset( $field['contents'] ) && count( $field['contents'] ) > 0 ) {
                        $fieldOptions['onValue'] = $field['contents'][0];
                        $fieldOptions['offValue'] = '';            
                    }
                }

                $customFields[] = array(

                    'fieldOptions' => $fieldOptions,
                    
                    'mapOptions' => array(
                        'id' => $field['name'],
                        'name' => $field['name'],
                        'title' => $field['name'],
                        'labelTitle' => $field['name'],
                        'mapTo' => is_array($pluginFieldType) ? $pluginFieldType : array( $pluginFieldType ),
                        'service' => $field
                    ),
                    
                    'premissions' => array(

                        'can' => $can,
                        'notices' => array(
                            'changeDropdown' => __('Please visit your GetResponse account to modify the choices.')
                        )
                    )
                );
            }
            
        } catch(Exception $ext) {
            throw new OPanda_SubscriptionException ('[custom-fields]: ' . $ext->getMessage());   
        }

        return $customFields;
    }
}