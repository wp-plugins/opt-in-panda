<?php 

class OPanda_SmartemailingSubscriptionService extends OPanda_Subscription {
    
    /**
     * Make a call to the Freshmail API.
     */
    public function request( $requestType, $requestMethod, $details = array() ) {
        
        $username = get_option('opanda_smartemailing_username', false);
        $token = get_option('opanda_smartemailing_apikey', false);
 
        if( empty( $username ) || empty( $token ) )
            throw new OPanda_SubscriptionException ( __('The Username or API Key Secret not set.', 'optinpanda' ));
        
        $url = 'https://app.smartemailing.cz/api/v2';
        
        $xml = '
        <xmlrequest>
            <username>'.$username.'</username>
            <usertoken>'.$token.'</usertoken>
            <requesttype>' . $requestType . '</requesttype>
            <requestmethod>' . $requestMethod . '</requestmethod>
            <details>' . $this->convertToXml($details) . '</details>
        </xmlrequest>
        ';

        $arrHeaders = array();
        $arrHeaders['Content-Type'] = 'application/xml';

        $result = wp_remote_post( $url, array(
            'headers' => $arrHeaders,
            'timeout' => 30,
            'body' => $xml
        ));

        if (is_wp_error( $result )) {
            throw new OPanda_SubscriptionException( sprintf( __( 'Unexpected error occurred during connection to SmartEmailing. %s.', 'optinpanda' ), $result->get_error_message() ) );
        }
        
        // processing errors

        $body = $this->convertToArray( $result['body'] );
        if ( !isset( $body['RESPONSE']['STATUS'] ) ) {
            
            throw new OPanda_SubscriptionException( __('Unexpected error occurred during connection to SmartEmailing. Invalid response.', 'optinpanda'), 'optinpanda'  ); 
        }

        if ( 'FAILED' === strtoupper( $body['RESPONSE']['STATUS'] ) ) {
            return array('error' => $body['RESPONSE']['ERRORMESSAGE'] );
        }
        
        $data = $body['RESPONSE']['DATA'];
        return $data;
    }
    
 
    /**
     * Convert XML to an Array
     */
    protected function convertToArray($XML)
    {
        if ( empty( $XML ) ) return array();

        $xml_parser = xml_parser_create();
        xml_parse_into_struct($xml_parser, $XML, $vals);
        xml_parser_free($xml_parser);

        $_tmp='';
        foreach ($vals as $xml_elem) {
            $x_tag=$xml_elem['tag'];
            $x_level=$xml_elem['level'];
            $x_type=$xml_elem['type'];
            if ($x_level!=1 && $x_type == 'close') {
                if (isset($multi_key[$x_tag][$x_level]))
                    $multi_key[$x_tag][$x_level]=1;
                else
                    $multi_key[$x_tag][$x_level]=0;
            }
            if ($x_level!=1 && $x_type == 'complete') {
                if ($_tmp==$x_tag)
                    $multi_key[$x_tag][$x_level]=1;
                $_tmp=$x_tag;
            }
        }

        foreach ($vals as $xml_elem) {
            $x_tag=$xml_elem['tag'];
            $x_level=$xml_elem['level'];
            $x_type=$xml_elem['type'];
            if ($x_type == 'open')
                $level[$x_level] = $x_tag;
            $start_level = 1;
            $php_stmt = '$xml_array';
            if ($x_type=='close' && $x_level!=1)
                $multi_key[$x_tag][$x_level]++;
            while ($start_level < $x_level) {
                $php_stmt .= '[$level['.$start_level.']]';
                if (isset($multi_key[$level[$start_level]][$start_level]) && $multi_key[$level[$start_level]][$start_level])
                    $php_stmt .= '['.($multi_key[$level[$start_level]][$start_level]-1).']';
                $start_level++;
            }
            $add='';
            if (isset($multi_key[$x_tag][$x_level]) && $multi_key[$x_tag][$x_level] && ($x_type=='open' || $x_type=='complete')) {
                if (!isset($multi_key2[$x_tag][$x_level]))
                    $multi_key2[$x_tag][$x_level]=0;
                else
                    $multi_key2[$x_tag][$x_level]++;
                $add='['.$multi_key2[$x_tag][$x_level].']';
            }
            if (isset($xml_elem['value']) && trim($xml_elem['value'])!='' && !array_key_exists('attributes', $xml_elem)) {
                if ($x_type == 'open')
                    $php_stmt_main=$php_stmt.'[$x_type]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
                else
                    $php_stmt_main=$php_stmt.'[$x_tag]'.$add.' = $xml_elem[\'value\'];';
                eval($php_stmt_main);
            }
            if (array_key_exists('attributes', $xml_elem)) {
                if (isset($xml_elem['value'])) {
                    $php_stmt_main=$php_stmt.'[$x_tag]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
                    eval($php_stmt_main);
                }
                foreach ($xml_elem['attributes'] as $key=>$value) {
                    $php_stmt_att=$php_stmt.'[$x_tag]'.$add.'[$key] = $value;';
                    eval($php_stmt_att);
                }
            }
        }
        return $xml_array;
    }
    
    protected function convertToXml( $data ) {
        
        $xml = '';
        foreach( $data as $itemKey => $itemValue ) {
            $inner = is_array( $itemValue ) ? $this->convertToXml($itemValue) : $itemValue;
            $xml .= "<$itemKey>" . $inner . "</$itemKey>";
        }
        
        return $xml;
    }
    
    /**
     * Returns lists available to subscribe.
     * 
     * @since 1.0.0
     * @return mixed[]
     */
    public function getLists() {
        
        $result = $this->request('ContactLists', 'getAll');

        if ( isset($result['error']) ) {
            throw new OPanda_SubscriptionException( sprintf( __( 'Unexpected error occurred during connection to SmartEmailing. %s.', 'optinpanda' ), $result['error'] ) );
        }
        
        if ( isset( $result['ITEM'] )) $result = $result['ITEM'];
        
        foreach( $result as $item ) {
            $lists[] = array(
                'title' => $item['NAME'],
                'value' => $item['ID']
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
        $result = $this->request('Contacts', 'getOne', array(
            'emailaddress' => $email
        ));

        if ( isset($result['error']) ) {
  
            // the subscriber not found
            if ( false === strpos($result['error'], 'does not exist') ) {
                throw new OPanda_SubscriptionException( sprintf( __( 'Unexpected error occurred during connection to SmartEmailing. %s.', 'optinpanda' ), $result['error'] ) );
            }
            
        } else {
   
            if ( isset( $result['CONTACTLISTSTATUSES'] ) ) {
                foreach( $result['CONTACTLISTSTATUSES'] as $item ) {
                    if ( $item['ID'] != $listId ) continue;
                    return array('status' => $item['STATUS'] === 'confirmed' ? 'subscribed' : 'pending');
                }
            }
        }
    
        $data = array(
            'emailaddress' => $identityData['email'],
            'contactliststatuses' => array(
                'item' => array(
                    'id' => $listId,
                    'status' => $doubleOptin ? 'unconfirmed' : 'confirmed'
                )
            )
        );

        if ( !empty( $identityData['name'] ) ) {
            $data['name'] = $identityData['name'];     
        }
        
        if ( !empty( $identityData['family'] ) ) {
            $data['surname'] = $identityData['family'];     
        }

        if ( empty( $identityData['name'] ) && !empty( $identityData['displayName'] ) ) {
            $data['name'] = $identityData['displayName'];   
        }

        $result = $this->request('Contacts', 'createupdate', $data);

        if ( is_array($result) && isset( $result['error'] ) ) {

            // the subscriber already exists
             if ( false === strpos($result['error'], 'already exists') ) {
                throw new OPanda_SubscriptionException( sprintf( __( 'Unexpected error occurred during connection to SmartEmailing. %s.', 'optinpanda' ), $result['error'] ) );
            } else {
                return array('status' => 'subscribed'); 
            }
        }  

        return array('status' => $doubleOptin ? 'pending' : 'subscribed');
    }
    
    /**
     * Checks if the user subscribed.
     */  
    public function check( $identityData, $listId, $contextData ) { 
        
        $email = $identityData['email'];
        $result = $this->request('Contacts', 'getOne', array(
            'emailaddress' => $email
        ));

        if ( isset($result['error']) ) {
  
            // the subscriber not found
            if ( false === strpos($result['error'], 'does not exist') ) {
                throw new OPanda_SubscriptionException( sprintf( __( 'Unexpected error occurred during connection to SmartEmailing. %s.', 'optinpanda' ), $result['error'] ) );
            }
            
        } else {
   
            if ( isset( $result['CONTACTLISTSTATUSES'] ) ) {
                foreach( $result['CONTACTLISTSTATUSES'] as $item ) {
                    if ( $item['ID'] != $listId ) continue;
                    return array('status' => $item['STATUS'] === 'confirmed' ? 'subscribed' : 'pending');
                }
            }
        }
        
        return array('status' => 'pending');
    }
    
    /**
     * Returns custom fields.
     */
    public function getCustomFields( $listId ) {
        
        return array(
            'error' => sprintf( __('Sorry, the plugin doesn\'t custom fields for SmartEmailing. Please <a href="%s" target="_blank">contact us</a> if you need this feature.', 'bizpanda'), "http://support.onepress-media.com/create-ticket/" )
        );
        
        /**
        $result = $this->request('CustomFields', 'getAll');
        
        if ( isset($result['error']) ) {
            throw new OPanda_SubscriptionException( sprintf( __( 'Unexpected error occurred during connection to SmartEmailing. %s.', 'bizpanda' ), $result['error'] ) ); 
        }
        */
    }
}
