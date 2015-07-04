<?php

class OPanda_DatabaseSubscriptionService extends OPanda_Subscription {

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
        return array('status' => 'subscribed');
    }
    
    /**
     * Checks if the user subscribed.
     */  
    public function check( $identityData, $listId, $contextData ) { 
        return array('status' => 'subscribed');
    }

    /**
     * Returns custom fields.
     */
    public function getCustomFields( $listId ) {
        
        $can = array(
            'changeType' => true,
            'changeReq' => true,
            'changeDropdown' => true,
            'changeMask' => true
        );
        
        $customFields = array();
        
        $customFields[] = array(
                    
            'fieldOptions' => array(),
            
            'mapOptions' => array(
                'req' => false,
                'id' => 'void',
                'name' => 'void',
                'title' => __('Custom Field', 'bizpanda'),
                'labelTitle' => __('Custom Field', 'bizpanda'),
                'mapTo' => 'any',
                'service' => array()
            ),
            
            'premissions' => array(
                'can' => $can,
                'notices' => array()
            )
        );

        return $customFields;
    }
}