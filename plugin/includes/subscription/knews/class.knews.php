<?php 

class OPanda_KnewsSubscriptionService extends OPanda_Subscription {
    
    const KNEWS_PLUGIN_LISTS = 'knewslists';
    const KNEWS_PLUGIN_USERS = 'knewsusers';
    const KNEWS_PLUGIN_USERSLISTS = 'knewsuserslists';
    const KNEWS_PLUGIN_USERS_EXTRA = 'knewsusersextra'; 
    const KNEWS_PLUGIN_EXTRA_FIELDS = 'knewsextrafields'; 
    
    /**
     * Returns lists available to subscribe.
     * 
     * @since 1.0.8
     * @return mixed[]
     */
    public function getLists() {
        global $wpdb;
        
        $data = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . self::KNEWS_PLUGIN_LISTS );

        $lists = array();
        foreach( $data as $item ) {
            $lists[] = array(
                'title' => $item->name,
                'value' => $item->id
            );
        }

        return array(
            'items' => $lists
        ); 
    }
    
    /**
     * Adds a new subscriber.
     * 
     * @since 1.0.8
     * @return void
     */
    protected function addSubscriber( $email, $customFields, $listId ) {
        global $wpdb;
        global $Knews_plugin;
        
        require_once OPANDA_BIZPANDA_DIR . '/admin/includes/leads.php';
        
        // adding info about a new subscriber
        
        $date = $Knews_plugin->get_mysql_date();
        $lang = 'en';       
        $state = 1;
        $confkey = $Knews_plugin->get_unique_id();
        $ip = OPanda_Leads::getIP();
        
        $sql = "INSERT INTO " . $wpdb->prefix . self::KNEWS_PLUGIN_USERS . " (email, lang, state, joined, confkey, ip) VALUES (%s, %s, %d, %s, %s, %s)";
        $sql = $wpdb->prepare( $sql, $email, $lang, $state, $date, $confkey, $ip);
        $result = $wpdb->query( $sql );
        
        if ( empty( $result ) ) {
            throw new OPanda_SubscriptionException( __( 'Unable to add a subscriber to the plugin K-news due to a SQL error. Please contact the support service.', 'optinpanda' ) ); 
        }
        
        $subscriberId = $Knews_plugin->real_insert_id();
        
        // adding custom fields
        
        $customFields = $this->mapExtraFields( $customFields );
        
        foreach( $customFields as $fieldId => $fieldValue ) {
            
            $sql = "INSERT INTO " . $wpdb->prefix  . self::KNEWS_PLUGIN_USERS_EXTRA . " (value, user_id, field_id) VALUES (%s, %d, %d)";
            $sql = $wpdb->prepare( $sql, $fieldValue, $subscriberId, $fieldId );
            $result=$wpdb->query( $sql );
        }
        
        // adding the subscriber to the list
        
        $this->addSubscriberToList( $subscriberId, $listId );
    }
    
    /**
     * Links fields names to fields IDs.
     * 
     * @since 1.0.8
     * @return mixed[]
     */
    protected function mapExtraFields( $customFields ) {
        global $wpdb;
        
        $sql = "SELECT * FROM " . $wpdb->prefix . self::KNEWS_PLUGIN_EXTRA_FIELDS;
	$rows = $wpdb->get_results( $sql );
        
        $result = array();
        foreach( $rows as $row ) { 
            if ( !isset( $customFields[$row->name] ) ) continue;
            $result[$row->id] = $customFields[$row->name];
        }
        
        return $result;
    }
    
    /**
     * Returns a subscriber by email.
     * 
     * @since 1.0.8
     * @return mixed[]
     */
    protected function getSubscriberByEmail( $email ) {
        global $wpdb;
        
        $sql = "SELECT * FROM " . $wpdb->prefix . self::KNEWS_PLUGIN_USERS . " WHERE email=%s LIMIT 1";
	return $wpdb->get_row( $wpdb->prepare( $sql, $email ) );
    }

    /**
     * Returns IDs of lists of a given subscriber.
     * 
     * @since 1.0.8
     * @param int $id A subscriber ID.
     * @return int[]
     */
    protected function getSubscriberLists( $id ) {
        global $wpdb;
        return $wpdb->get_col( "SELECT id_list FROM " . $wpdb->prefix . self::KNEWS_PLUGIN_USERSLISTS . " WHERE id_user=$id " );
    }
    
    /**
     * Adds a given subscriber to a specified list.
     * 
     * @since 1.0.8
     * @return void
     */
    protected function addSubscriberToList( $subscriberId, $listId ) {
        global $wpdb;
        
        $result = $wpdb->query( "INSERT INTO " . $wpdb->prefix . self::KNEWS_PLUGIN_USERSLISTS . " (id_user,id_list) VALUES ($subscriberId,$listId)" );
        if ( empty( $result ) ) {
            throw new OPanda_SubscriptionException( __( 'Unable to add a subscriber to the specified list due to a SQL error. Please contact the support service.', 'optinpanda' ) ); 
        }
    }

    /**
     * Subscribes the person.
     */
    public function subscribe( $identityData, $listId, $doubleOptin, $contextData ) {

        // if already subscribed
        
        $subscriber = $this->getSubscriberByEmail( $identityData['email'] );
        if ( !empty( $subscriber ) ) {
            
            $lists = $this->getSubscriberLists( $subscriber->id );
            if ( !in_array( $listId, $lists ) ) {
                $this->addSubscriberToList( $subscriber->id, $listId );
            }
            
            if ( !$doubleOptin ) return array('status' =>  'subscribed');
            
            $status = intval($subscriber->state );
            if ( 1 === $status ) $this->sendConfirmation( $subscriber, $contextData );
            
            return array('status' => 2 === $status ? 'subscribed' : 'pending');
        }
        
        // if it's a new subscriber
        
        $customeFileds = array();
        
        if ( !empty( $identityData['name'] ) )
            $customeFileds['name'] = $identityData['name'];
        
        if ( !empty( $identityData['family'] ) )
            $customeFileds['surname'] = $identityData['family'];

        $this->addSubscriber($identityData['email'], $customeFileds, $listId);

        $subscriber = $this->getSubscriberByEmail( $identityData['email'] );
        if ( empty( $subscriber ) ) {
            throw new OPanda_SubscriptionException( __( 'Unable to add a new subscriber via the plugin K-news. Please contact the support service.', 'optinpanda' ) ); 
        }
        
        if ( $doubleOptin ) $this->sendConfirmation( $subscriber, $contextData );
        return array('status' => $doubleOptin ? 'pending' : 'subscribed');
    }
    
    /**
     * Send the confirmation email replacing the original confirmation link generated via MyMail.
     * 
     * @since 1.0.7
     * @return void
     */
    private function sendConfirmation( $subscriber, $contextData ) {

        global $Knews_plugin;
        $Knews_plugin->basic_init();
        
        $this->_beforeSendingConfirmation( $contextData );
        $result = apply_filters('knews_submit_confirmation', $subscriber->email, $subscriber->confkey );
        $this->_afterSendingConfirmation();
        
        if ( !$result ) {
            throw new OPanda_SubscriptionException( __( 'Unable to send the confirmation email via the plugin K-news. Please make sure that the plugin K-news is able to send emails.', 'optinpanda' ) ); 
        }
    }
    /**
     * A helper method to prepare hooks before sending the confirmation email.
     * 
     * @since 1.0.9
     * @return void
     */
    private function _beforeSendingConfirmation( $contextData ) {
        
        if ( isset( $contextData['postUrl'] ) && !empty( $contextData['postUrl'] ) ) {
            $this->_returnPage = $contextData['postUrl'];
        } else {
            $this->_returnPage = get_home_url();
        }
        
        if ( !isset( $contextData['itemId'] ) ) {
            throw new OPanda_SubscriptionException( __( 'Invalid request. Item ID is not set.', 'optinpanda' ) ); 
        }

        $itemId = intval( $contextData['itemId'] );
        $GLOBALS['post'] = get_post( $itemId );
        
        add_filter('home_url', array( $this, '_fixConfirmationLink'), 99, 1 );        
    }

    /**
     * A helper method to prepare hooks after sending the confirmation email.
     * 
     * @since 1.0.9
     * @return void
     */
    private function _afterSendingConfirmation() {
        remove_filter('home_url', array( $this, '_fixConfirmationLink'), 99, 1 );     
    }
    
    /**
     * Replaces the confirmation link generated by K-news.
     * 
     * @since 1.0.9
     * @return string
     */
    public function _fixConfirmationLink( $url ) {
        if ( get_option('opanda_knews_redirect', false ) ) return $this->_returnPage;
        return $url;
    }
    
    /**
     * Checks if the user subscribed.
     */  
    public function check( $identityData, $listId, $contextData ) { 
        
        $subscriber = $this->getSubscriberByEmail( $identityData['email'] );
        if ( empty($subscriber ) ) {
            throw new OPanda_SubscriptionException( __( 'The operation is canceled because the website administrator deleted your email from the list.', 'optinpanda' ) ); 
        }
        
        $lists = $this->getSubscriberLists( $subscriber->id );
        if ( !in_array( $listId, $lists ) ) {
            $this->addSubscriberToList( $subscriber->id, $listId );
        }
        
        $status = intval( $subscriber->state );
        if ( 2 === $status ) return array('status' => 'subscribed');
        return array('status' => 'pending');
    }
    
    /**
     * Returns custom fields.
     */
    public function getCustomFields( $listId ) {
        
        return array(
            'error' => sprintf( __('Sorry, the plugin doesn\'t custom fields for K-News. Please <a href="%s" target="_blank">contact us</a> if you need this feature.', 'bizpanda'), "http://support.onepress-media.com/create-ticket/" )
        );
    }
}
