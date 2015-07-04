<?php

define('BIZPANDA_EMAIL_LOCKER_DIR', dirname(__FILE__));
define('BIZPANDA_EMAIL_LOCKER_URL', plugins_url( null, __FILE__ ));

if ( is_admin() ) require BIZPANDA_EMAIL_LOCKER_DIR . '/admin/boot.php';
global $bizpanda;

/**
 * Registers the Email Locker item.
 * 
 * @since 1.0.0
 */
function opanda_register_email_locker( $items ) {
    global $optinpanda;

        $items['email-locker'] = array(
            'name' => 'email-locker',
            'title' => __('Email Locker', 'emaillocker'),
            'help' => opanda_get_help_url('what-is-email-locker'),
            'description' => __('<p>Asks visitors to subscribe to unlock some value content.</p><p>Perfect way to build your mailing list without any extra costs or efforts.</p>', 'optinpanda'),
            'type' => 'free',
            'shortcode' => 'emaillocker',
            'plugin' => $optinpanda
        );

    


    return $items;
}
add_filter('opanda_items', 'opanda_register_email_locker', 1);

/**
 * Adds options to print at the frontend.
 * 
 * @since 1.0.0
 */
function opanda_email_locker_options( $options, $id ) {
    
    $options['terms'] = opanda_terms_url();
    $options['privacyPolicy'] = opanda_privacy_policy_url(); 

    if ( !get_option('opanda_terms_use_pages', false) ) {
        $options['termsPopup'] = array(
            'width' => 570,
            'height' => 400
        );
    }
        
    $connectButtons = opanda_get_item_option($id, 'subscribe_social_buttons', true);
    $hasConnectButtons = opanda_get_item_option($id, 'subscribe_allow_social') && !empty( $connectButtons );

    $formType = opanda_get_item_option($id, 'form_type', false);
        
    $options['groups'] = ( $hasConnectButtons && ( 'custom-form' !== $formType ) )
        ? array('subscription', 'connect-buttons')
        : array('subscription');
    
    $options['subscription'] = array(
        'order' => array('form'),
        'form' => array(
            'buttonText' => opanda_get_item_option($id, 'button_text', false),
            'noSpamText' => opanda_get_item_option($id, 'after_button', false)      
        )
    );
        $options['theme'] = 'great-attractor';
    


    $options['subscription']['form']['type'] = $formType;
    
    if ( 'custom-form' === $formType ) {
        
        $meta = opanda_get_item_options( $id );
        $strFieldsJson = isset( $meta['opanda_fields'] ) ? $meta['opanda_fields'] : '';

        if ( $strFieldsJson ) {

            $fieldsData = json_decode( $strFieldsJson, true );
            $fields = array();

            foreach( $fieldsData as $field ) {
                $fields[] = $field['fieldOptions'];
            }

            $options['subscription']['form']['fields'] = $fields;
        }  
        
    } elseif ( $hasConnectButtons ) {
        
        $options['connectButtons'] = array();
        $options['connectButtons']['order'] = explode( ',', $connectButtons );
        
        $options['connectButtons']['text'] = opanda_get_item_option($id, 'subscribe_social_text', false);
        
        $options['connectButtons']['facebook'] = array(
            'appId' => opanda_get_option('facebook_appid'),
            'actions'=> array('subscribe')
        );
        
        $options['connectButtons']['google'] = array(
            'clientId' => opanda_get_option('google_client_id'),
            'actions'=> array('subscribe')
        );
        
        $options['connectButtons']['linkedin'] = array(
            'apiKey' => opanda_get_option('linkedin_api_key'),
            'actions'=> array('subscribe')
        );
        
    }

    $optinMode = opanda_get_item_option($id, 'subscribe_mode');
    
    $service = opanda_get_option('subscription_service', 'database');
    $listId = ( 'database' === $service ) ? 'default' : opanda_get_item_option($id, 'subscribe_list', false);
    
    $options['subscribeActionOptions'] = array(
        'listId' => $listId,
        'service' => $service,
        'doubleOptin' => in_array( $optinMode, array('quick-double-optin', 'double-optin') ),
        'confirm' => in_array( $optinMode, array('double-optin') )
    );

    return $options;
}

add_filter('opanda_email-locker_item_options', 'opanda_email_locker_options', 10, 2);


/**
 * Requests assets for email locker.
 */
function opanda_email_locker_assets( $lockerId, $options, $fromBody, $fromHeader ) {
    OPanda_AssetsManager::requestLockerAssets( $fromBody, $fromHeader );
    
    // The screen "Please Confirm Your Email"
    OPanda_AssetsManager::requestTextRes(array(
        'confirm_screen_title',
        'confirm_screen_instructiont',
        'confirm_screen_note1',
        'confirm_screen_note2',
        'confirm_screen_cancel',
        'confirm_screen_open',
    ));
    
    // Miscellaneous
    OPanda_AssetsManager::requestTextRes(array(
        'misc_data_processing',
        'misc_enter_your_email',
        'misc_enter_your_name',
        'misc_your_agree_with',
        'misc_terms_of_use',
        'misc_privacy_policy',
        'misc_or_wait',
        'misc_close',
        'misc_or'
    ));
    
    // Errors & Notices
    OPanda_AssetsManager::requestTextRes(array(
        'errors_empty_email',
        'errors_inorrect_email',
        'errors_empty_name',
        'errors_subscription_canceled',
        'misc_close',
        'misc_or'
    ));
    
    if ( !empty( $options['opanda_has_mask']) ) {
        
        wp_enqueue_script( 
            'jquery-maskedinput', 
            OPANDA_BIZPANDA_URL . '/assets/js/jquery.maskedinput.min.js', 
            array('jquery', 'opanda-lockers'), false, true
        );
    }
    
    if ( !empty( $options['opanda_has_date']) ) {
        
        wp_enqueue_script( 
            'jquery-moment', 
            OPANDA_BIZPANDA_URL . '/assets/js/moment.js', 
            array('jquery', 'opanda-lockers'), false, true
        );
        
        wp_enqueue_script( 
            'jquery-pikaday', 
            OPANDA_BIZPANDA_URL . '/assets/js/pikaday.js', 
            array('jquery', 'jquery-moment', 'opanda-lockers'), false, true
        );
    }
    
    if ( !empty( $options['opanda_has_fontawesome']) ) {

        wp_enqueue_style( 
            'factory-fontawesome', 
            OPANDA_BIZPANDA_URL . '/assets/css/font-awesome/css/font-awesome.css'
        );

    }

    if ( !isset( $options['opanda_subscribe_social_buttons'] ) || !$options['opanda_subscribe_social_buttons'] ) return;
    
    // The screen "One Step To Complete" | Errors & Notices
    OPanda_AssetsManager::requestTextRes(array(
        'onestep_screen_title',
        'onestep_screen_instructiont',
        'onestep_screen_button',
        'errors_not_signed_in',
        'errors_not_granted'
    ));
    
    // Sign-In Buttons
    OPanda_AssetsManager::requestTextRes(array(
        'signin_long',
        'signin_short',
        'signin_facebook_name',
        'signin_twitter_name',
        'signin_google_name',
        'signin_linkedin_name'
    ));
    
    if ( isset( $options['opanda_subscribe_social_buttons'] ) && strpos( $options['opanda_subscribe_social_buttons'], 'facebook' ) !== false ) {
        OPanda_AssetsManager::requestFacebookSDK();  
    }
}

add_action('opanda_request_assets_for_email-locker', 'opanda_email_locker_assets', 10, 4);

/**
 * A shortcode for the Email Locker
 * 
 * @since 1.0.0
 */
class OPanda_EmailLockerShortcode extends OPanda_LockerShortcode {
    
    /**
     * Shortcode name
     * @var string
     */
    public $shortcodeName = array( 
        'emaillocker', 'emaillocker-1', 'emaillocker-2', 'emaillocker-3', 'emaillocker-4', 'emaillocker-bulk'
    );
    
    protected function getDefaultId() {
        return get_option('opanda_default_email_locker_id');
    }
}

FactoryShortcodes320::register( 'OPanda_EmailLockerShortcode', $optinpanda );