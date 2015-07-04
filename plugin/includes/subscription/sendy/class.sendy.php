<?php 

class OPanda_SendySubscriptionService extends OPanda_Subscription {

    public function init( $options ) {
        parent::init( $options );
    }

    /**
     * Returns available Opt-In modes.
     * 
     * @since 1.0.0
     * @return mixed[]
     */
    public function getOptInModes() {
        return array( 'double-optin', 'quick-double-optin', 'quick' );
    }
        
    /**
     * Returns a MyMail Lists Manager
     * 
     * @since 1.0.7
     * @return mymail_lists
     */
    public function getListsManager() {
        
        if ( !defined('MYMAIL_VERSION') ) {
            throw new OPanda_SubscriptionException( __( 'The MyMail plugin is not found on your website.', 'optinpanda' ) ); 
        }
        
        $path = MYMAIL_DIR . '/classes/lists.class.php';
        if ( !file_exists( $path ) ) {
            throw new OPanda_SubscriptionException( __( 'Unable to connect with the MyMail Lists Manager. Your version of MyMail plugin is not supported. Please contact OnePress support.', 'optinpanda' ) ); 
        }
        
        require_once $path;
        
        if ( !class_exists( 'mymail_lists' ) ) {
            throw new OPanda_SubscriptionException( __( 'Unable to connect with the MyMail Lists Manager. Your version of MyMail plugin is not supported. Please contact OnePress support.', 'optinpanda' ) ); 
        }  
        
        return mymail('lists');
    }
    
    /**
     * Returns a MyMail Subscribers Manager
     * 
     * @since 1.0.7
     * @return mymail_lists
     */
    public function getSubscribersManager() {
        
        if ( !defined('MYMAIL_VERSION') ) {
            throw new OPanda_SubscriptionException( __( 'The MyMail plugin is not found on your website.', 'optinpanda' ) ); 
        }
        
        $path = MYMAIL_DIR . '/classes/subscribers.class.php';
        if ( !file_exists( $path ) ) {
            throw new OPanda_SubscriptionException( __( 'Unable to connect with the MyMail Subscribers Manager. Your version of MyMail plugin is not supported. Please contact OnePress support.', 'optinpanda' ) ); 
        }
        
        require_once $path;
        
        if ( !class_exists( 'mymail_subscribers' ) ) {
            throw new OPanda_SubscriptionException( __( 'Unable to connect with the MyMail Subscribers Manager. Your version of MyMail plugin is not supported. Please contact OnePress support.', 'optinpanda' ) ); 
        }  
        
        return mymail('subscribers');
    }    
    
    /**
     * Makes a request to Acumbamail.
     * 
     * @since 1.0.9 
     */
    public function request( $method, $args = array(), $requestMethod = 'GET' ) {

        $apiKey = get_option('opanda_sendy_apikey', false);
        $sendyUrl = trim( get_option('opanda_sendy_url', false) );

        if ( empty( $apiKey ) )
            throw new OPanda_SubscriptionException( __( 'The Sendy API Key is not specified.', 'optinpanda' ) );
        
        if ( empty( $sendyUrl ) )
            throw new OPanda_SubscriptionException( __( 'The Sendy Installation is not specified.', 'optinpanda' ) );
        
        $sendyUrl = trim($sendyUrl, '/');
        if ( false === strpos($sendyUrl, 'http://') ) $sendyUrl = 'http://' . $sendyUrl;
        
        $url = $sendyUrl . $method;
        $args['api_key'] = $apiKey;       

        $result = wp_remote_post( $url, array(
            'timeout' => 30,
            'body' => $args
        ));

        if (is_wp_error( $result )) {
            throw new OPanda_SubscriptionException( sprintf( __( 'Unexpected error occurred during connection to Sendy: %s', 'optinpanda' ), $result->get_error_message() ) );
        }

        $code = isset( $result['response']['code'] ) ? intval ( $result['response']['code'] ) : 0;
        if ( 200 !== $code ) {
            throw new OPanda_SubscriptionException( sprintf( __( 'Unexpected error occurred during connection to Sendy: %s', 'optinpanda' ), $result['response']['message'] ) );   
        }
        
        if ( empty( $result['body'] ) ) return false;
        return $result['body'];
    }
    
    /**
     * Returns lists available to subscribe.
     * 
     * @since 1.0.0
     * @return mixed[]
     */
    public function getLists() {
        return array();
    }

    /**
     * Subscribes the person.
     */
    public function subscribe( $identityData, $listId, $doubleOptin, $contextData ) {

        $result = $this->request('/api/subscribers/subscription-status.php', array(
            'email' => $identityData['email'],
            'list_id' => $listId
        ));

        // if not subscribed yet
        
        if ( strpos($result, 'does not exist') > 0 ) {
            
            $data = array(
                'email' => $identityData['email'],
                'list' => $listId ,
                'boolean' => true
            );
            
            if ( !empty( $identityData['name'] ) ) {
                $userData['name'] = $identityData['name']; 
                $userData['firstname'] = $identityData['name'];
            }

            if ( !empty( $identityData['family'] ) ) {
                $userData['family'] = $identityData['family'];
                $userData['lastname'] = $identityData['family'];
                $userData['surname'] = $identityData['family'];
            }

            if ( empty( $identityData['name'] ) && empty( $identityData['family'] ) && !empty( $identityData['displayName'] ) ) {
                $userData['name'] = $identityData['displayName'];
                $userData['firstname'] = $identityData['displayName']; 
            }
            
            $result = $this->request('/subscribe', $data);

            if ( 'true' === $result || strpos( $result, 'subscribed' ) || strpos( $result, 'confirmation email' ) ) {
                return array('status' => $doubleOptin ? 'pending' : 'subscribed');
            } else {
                throw new OPanda_SubscriptionException( $result );   
            }
        }
        
        // if already subscribed
        
        $success = array( 'subscribed', 'unsubscribed', 'bounced', 'soft bounced', 'unconfirmed', 'complained' );
        if ( !in_array( strtolower( $result ), $success )) {
            throw new OPanda_SubscriptionException( $result );   
        }
        
        if ( 'subscribed' === strtolower( $result ) ) {
            return array('status' => 'subscribed');
        } else {
            return array('status' => 'pending');
        }
    }
 
    /**
     * Checks if the user subscribed.
     */  
    public function check( $identityData, $listId, $contextData ) { 
        
        $result = $this->request('/api/subscribers/subscription-status.php', array(
            'email' => $identityData['email'],
            'list_id' => $listId
        ));
        
        $success = array( 'subscribed', 'unsubscribed', 'bounced', 'soft bounced', 'unconfirmed', 'complained' );
        if ( !in_array( strtolower( $result ), $success )) {
            throw new OPanda_SubscriptionException( $success );   
        }
        
        if ( 'subscribed' === strtolower( $result ) ) {
            return array('status' => 'subscribed');
        } else {
            return array('status' => 'pending');
        }
    }
    
    /**
     * Returns custom fields.
     */
    public function getCustomFields( $listId ) {

        return array(
            'error' => sprintf( __('Sorry, the plugin doesn\'t custom fields for Sendy. Please <a href="%s" target="_blank">contact us</a> if you need this feature.', 'bizpanda'), "http://support.onepress-media.com/create-ticket/" )
        );
    }
}
