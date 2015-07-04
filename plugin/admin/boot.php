<?php

#comp merge
require(OPTINPANDA_DIR . '/plugin/admin/activation.php');
    require(OPTINPANDA_DIR . '/plugin/admin/notices.php');



require(OPTINPANDA_DIR . '/plugin/admin/pages/license-manager.php');
    require(OPTINPANDA_DIR . '/plugin/admin/pages/premium.php');


#endcomp

// ---
// Subscription Services
//

/**
 * Registers available subscription services.
 * 
 * @see the 'opanda_subscription_services' action
 * 
 * @since 1.0.8
 * @return mixed[]
 */
function optinpanda_subscription_services( $services ) {
    
    $items = array(

        'database' => array(
            'title' => __('None', 'optinpanda'),
            'description' => __('Emails of subscribers will be saved in the WP database.', 'optinpanda'),
            
            'class' => 'OPanda_DatabaseSubscriptionService',
            'path' => OPTINPANDA_DIR . '/plugin/includes/subscription/database/class.database.php',
            'modes' => array('quick')
        ),
        'mailchimp' => array(
            'title' => __('MailChimp', 'optinpanda'),
            'description' => __('Adds subscribers to your MailChimp account.', 'optinpanda'),
            
            'image' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/gray/mailchimp.png',
            'hover' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/gray/mailchimp.png', 
            
            'class' => 'OPanda_MailChimpSubscriptionService',
            'path' => OPTINPANDA_DIR . '/plugin/includes/subscription/mailchimp/class.mailchimp.php',
            'modes' => array('double-optin', 'quick-double-optin', 'quick'),
            
            'notes' => array(
                'customFields' => sprintf( __('Select one of merge tags in your MailChimp account to save the value from this field. <a href="%s" target="_blank">Learn more about Merge Tags</a>.'), 'http://kb.mailchimp.com/merge-tags/using/getting-started-with-merge-tags' )
            )
        ),
        'aweber' => array(
            'title' => __('Aweber', 'optinpanda'),
            'description' => __('Adds subscribers to your Aweber account.', 'optinpanda'),
            
            'image' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/gray/aweber.png',
            'hover' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/colored/aweber.png',       
            
            'class' => 'OPanda_AweberSubscriptionService',
            'path' => OPTINPANDA_DIR . '/plugin/includes/subscription/aweber/class.aweber.php',
            'modes' => array('double-optin', 'quick-double-optin')
        ),
        'getresponse' => array(
            'title' => __('GetResponse', 'optinpanda'),
            'description' => __('Adds subscribers to your GetResponse account.', 'optinpanda'),
            
            'image' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/gray/getresponse.png',
            'hover' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/colored/getresponse.png',  
            
            'class' => 'OPanda_GetResponseSubscriptionService',
            'path' => OPTINPANDA_DIR . '/plugin/includes/subscription/getresponse/class.getresponse.php',
            'modes' => array('double-optin', 'quick-double-optin')
        ),
        'mailpoet' => array(
            'title' => __('MailPoet', 'optinpanda'),
            'description' => __('Adds subscribers to the plugin MailPoet.', 'optinpanda'),
            
            'image' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/gray/mailpoet.png',
            'hover' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/colored/mailpoet.png',
            
            'class' => 'OPanda_MailPoetSubscriptionService',
            'path' => OPTINPANDA_DIR . '/plugin/includes/subscription/mailpoet/class.mailpoet.php',
            'modes' => array('double-optin', 'quick-double-optin', 'quick')
        ),
        'mymail' => array(
            'title' => __('MyMail', 'optinpanda'),
            'description' => __('Adds subscribers to the plugin MyMail.', 'optinpanda'),
            
            'image' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/gray/mymail.png',
            'hover' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/colored/mymail.png',
            
            'class' => 'OPanda_MyMailSubscriptionService',
            'path' => OPTINPANDA_DIR . '/plugin/includes/subscription/mymail/class.mymail.php',
            'modes' => array('double-optin', 'quick-double-optin', 'quick')
        ),
        'acumbamail' => array(
            'title' => __('Acumbamail', 'optinpanda'),
            'description' => __('Adds subscribers to your Acumbamail account.', 'optinpanda'),
            
            'image' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/gray/acumbamail.png',
            'hover' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/colored/acumbamail.png',
            
            'class' => 'OPanda_AcumbamailSubscriptionService',
            'path' => OPTINPANDA_DIR . '/plugin/includes/subscription/acumbamail/class.acumbamail.php',
            'modes' => array('quick')
        ),
        'knews' => array(
            'title' => __('K-news', 'optinpanda'),
            'description' => __('Adds subscribers to the plugin K-news.', 'optinpanda'),
            
            'image' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/gray/knews.png',
            'hover' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/gray/knews.png',
            
            'class' => 'OPanda_KNewsSubscriptionService',
            'path' => OPTINPANDA_DIR . '/plugin/includes/subscription/knews/class.knews.php',
            'modes' => array('double-optin', 'quick-double-optin', 'quick')
        ),
        'freshmail' => array(
            'title' => __('FreshMail', 'optinpanda'),
            'description' => __('Adds subscribers to your FreshMail account.', 'optinpanda'),
            
            'image' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/gray/freshmail.png',
            'hover' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/colored/freshmail.png',
            
            'class' => 'OPanda_FreshmailSubscriptionService',
            'path' => OPTINPANDA_DIR . '/plugin/includes/subscription/freshmail/class.freshmail.php',
            'modes' => array('double-optin', 'quick-double-optin', 'quick')
        ),
        'sendy' => array(
            'title' => __('Sendy', 'optinpanda'),
            'description' => __('Adds subscribers to your Sendy application.', 'optinpanda'),
            
            'image' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/gray/sendy.png',
            'hover' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/colored/sendy.png',
            
            'class' => 'OPanda_SendySubscriptionService',
            'path' => OPTINPANDA_DIR . '/plugin/includes/subscription/sendy/class.sendy.php',
            'modes' => array('double-optin', 'quick'),
            
            'manualList' => true
        ),
        'smartemailing' => array(
            'title' => __('SmartEmailing', 'optinpanda'),
            'description' => __('Adds subscribers to your SmartEmailing account.', 'optinpanda'),
            
            'image' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/gray/smartemailing.png',
            'hover' => 'https://cconp.s3.amazonaws.com/optinpanda/mailing-services/colored/smartemailing.png',
            
            'class' => 'OPanda_SmartemailingSubscriptionService',
            'path' => OPTINPANDA_DIR . '/plugin/includes/subscription/smartemailing/class.smartemailing.php',
            'modes' => array('quick')
        )
    );
    
    return array_merge( $services, $items );
}

add_action('opanda_subscription_services', 'optinpanda_subscription_services');

/**
 * Registers options for the subscription services.
 * 
 * @see the 'opanda_subscription_services_options' action
 * 
 * @since 1.1.3
 * @return mixed[]
 */
function optinpanda_subscription_services_options( $options ) {
    
    // mailchimp
        
    $options[] = array(
        'type'      => 'div',
        'id'        => 'opanda-mailchimp-options',
        'class'     => 'opanda-mail-service-options opanda-hidden',
        'items'     => array(

            array(
                'type' => 'separator'
            ),
            array(
                'type'      => 'textbox',
                'name'      => 'mailchimp_apikey',
                'after'     => sprintf( __( '<a href="%s" class="btn btn-default" target="_blank">Get API Key</a>', 'optinpanda' ), 'http://kb.mailchimp.com/accounts/management/about-api-keys#Finding-or-generating-your-API-key' ),
                'title'     => __( 'API Key', 'optinpanda' ),
                'hint'      => __( 'The API key of your MailChimp account.', 'optinpanda' ),
            )
        )
    );

    // aweber

    if( !get_option('opanda_aweber_consumer_key', false) ) {

        $options[] = array(
            'type'      => 'div',
            'id'        => 'opanda-aweber-options',
            'class'     => 'opanda-mail-service-options opanda-hidden',
            'items'     => array(

                array(
                    'type' => 'separator'
                ),
                array(
                    'type'      => 'html',
                    'html'      => 'opanda_aweber_html'
                ),
                array(
                    'type'      => 'textarea',
                    'name'      => 'aweber_auth_code',
                    'title'     => __( 'Authorization Code', 'optinpanda' ),
                    'hint'      => __( 'The authorization code you will see after log in to your Aweber account.', 'optinpanda' )
                )
            )
        );    

    } else {

        $options[] = array(
            'type'      => 'div',
            'id'        => 'opanda-aweber-options',
            'class'     => 'opanda-mail-service-options opanda-hidden',
            'items'     => array(
                array(
                    'type' => 'separator'
                ),
                array(
                    'type'      => 'html',
                    'html'      => 'opanda_aweber_html'
                )                    
            )
        );
    }

    // getresponse

    $options[] = array(
        'type'      => 'div',
        'id'        => 'opanda-getresponse-options',
        'class'     => 'opanda-mail-service-options opanda-hidden',
        'items'     => array(

            array(
                'type' => 'separator'
            ),
            array(
                'type'      => 'textbox',
                'name'      => 'getresponse_apikey',
                'title'     => __( 'API Key', 'optinpanda' ),
                'after'     => sprintf( __( '<a href="%s" class="btn btn-default" target="_blank">Get API Key</a>', 'optinpanda' ), 'http://support.getresponse.com/faq/where-i-find-api-key' ),
                'hint'      => __( 'The API key of your GetResponse account.', 'optinpanda' ),
            )
        )
    );

    // mymail

    $options[] = array(
        'type'      => 'div',
        'id'        => 'opanda-mymail-options',
        'class'     => 'opanda-mail-service-options opanda-hidden',
        'items'     => array(


            array(
                'type' => 'html',
                'html' => 'opanda_show_mymail_html'
            ),
            array(
                'type' => 'separator'
            ),
            array(
                'type'      => 'checkbox',
                'way'       => 'buttons',
                'name'      => 'mymail_redirect',
                'title'     => __( 'Redirect To Locker', 'optinpanda' ),
                'hint'      => sprintf( __( 'Set On to redirect the user after the email confirmation to the page where the locker located.<br />If Off, the MyMail will redirect the user to the page specified in the option <a href="%s" target="_blank">Newsletter Homepage</a>.', 'optinpanda' ), admin_url('options-general.php?page=newsletter-settings&settings-updated=true#frontend') )
            )
        )
    );

    // mailpoet

    $options[] = array(
        'type'      => 'div',
        'id'        => 'opanda-mailpoet-options',
        'class'     => 'opanda-mail-service-options opanda-hidden',
        'items'     => array(

            array(
                'type' => 'html',
                'html' => 'opnada_show_mailpoet_html'
            )   
        )
    );

    // acumbamail

    $options[] = array(
        'type'      => 'div',
        'id'        => 'opanda-acumbamail-options',
        'class'     => 'opanda-mail-service-options opanda-hidden',
        'items'     => array(

            array(
                'type' => 'separator'
            ),
            array(
                'type'      => 'textbox',
                'name'      => 'acumbamail_customer_id',
                'title'     => __( 'Customer ID', 'optinpanda' ),
                'after'     => sprintf( __( '<a href="%s" class="btn btn-default" target="_blank">Get ID & Token</a>', 'optinpanda' ), 'https://acumbamail.com/apidoc/' ),
                'hint'      => __( 'The customer ID of your Acumbamail account.', 'optinpanda' )
            ),
            array(
                'type'      => 'textbox',
                'name'      => 'acumbamail_api_token',
                'title'     => __( 'API Token', 'optinpanda' ),
                'hint'      => __( 'The API token of your Acumbamail account.', 'optinpanda' )
            )
        )
    );

    // knews

    $options[] = array(
        'type'      => 'div',
        'id'        => 'opanda-knews-options',
        'class'     => 'opanda-mail-service-options opanda-hidden',
        'items'     => array(

            array(
                'type' => 'html',
                'html' => 'opanda_show_knews_html'
            ),
            array(
                'type' => 'separator'
            ),
            array(
                'type'      => 'checkbox',
                'way'       => 'buttons',
                'name'      => 'knews_redirect',
                'title'     => __( 'Redirect To Locker', 'optinpanda' ),
                'hint'      => __( 'Set On to redirect the user after the email confirmation to the page where the locker located.<br />If Off, the K-news will redirect the user to the home page.', 'optinpanda' )
            )   
        )
    );   

    // freshmail

    $options[] = array(
        'type'      => 'div',
        'id'        => 'opanda-freshmail-options',
        'class'     => 'opanda-mail-service-options opanda-hidden',
        'items'     => array(

            array(
                'type' => 'separator'
            ),
            array(
                'type'      => 'textbox',
                'name'      => 'freshmail_apikey',
                'title'     => __( 'API Key', 'optinpanda' ),
                'after'     => sprintf( __( '<a href="%s" class="btn btn-default" target="_blank">Get API Keys</a>', 'optinpanda' ), 'https://app.freshmail.com/en/settings/integration/' ),
                'hint'      => __( 'The API Key of your FreshMail account.', 'optinpanda' )
            ),
            array(
                'type'      => 'textbox',
                'name'      => 'freshmail_apisecret',
                'title'     => __( 'API Secret', 'optinpanda' ),
                'hint'      => __( 'The API Sercret of your FreshMail account.', 'optinpanda' )
            )
        )
    );

    // sendy

    $options[] = array(
        'type'      => 'div',
        'id'        => 'opanda-sendy-options',
        'class'     => 'opanda-mail-service-options opanda-hidden',
        'items'     => array(

            array(
                'type' => 'separator'
            ),
            array(
                'type'      => 'textbox',
                'name'      => 'sendy_apikey',
                'title'     => __( 'API Key', 'optinpanda' ),
                'hint'      => __( 'The API key of your Sendy application, available in Settings.', 'optinpanda' ),
            ),
            array(
                'type'      => 'textbox',
                'name'      => 'sendy_url',
                'title'     => __( 'Installation', 'optinpanda' ),
                'hint'      => __( 'An URL for your Sendy installation, <strong>http://your_sendy_installation</strong>', 'optinpanda' ),
            )
        )
    );
    
    // smartemailing
    
    $options[] = array(
        'type'      => 'div',
        'id'        => 'opanda-smartemailing-options',
        'class'     => 'opanda-mail-service-options opanda-hidden',
        'items'     => array(

            array(
                'type' => 'separator'
            ),
            array(
                'type'      => 'textbox',
                'name'      => 'smartemailing_username',
                'title'     => __( 'Username', 'optinpanda' ),
                'hint'      => __( 'Enter your username on SmartEmailing. Usually it is a email.', 'optinpanda' ),
            ),          
            array(
                'type'      => 'textbox',
                'name'      => 'smartemailing_apikey',
                'after'     => sprintf( __( '<a href="%s" class="btn btn-default" target="_blank">Get API Key</a>', 'optinpanda' ), 'https://app.smartemailing.cz/userinfo/show/api' ),
                'title'     => __( 'API Key', 'optinpanda' ),
                'hint'      => __( 'The API key of your SmartEmailing account.', 'optinpanda' ),
            )
        )
    );
    
    return $options;
}

add_action('opanda_subscription_services_options', 'optinpanda_subscription_services_options');

/**
 * Shows HTML for Aweber.
 * 
 * @since 1.1.3
 * @return void
 */
function opanda_aweber_html() {
    
    if( !get_option('opanda_aweber_consumer_key', false) ) {
    ?>

    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="control-group controls col-sm-10 opanda-aweber-steps">
            <p><?php _e( 'To connect your Aweber account:', 'optinpanda' ) ?></p>
            <ul>
                <li><?php _e( '<span>1.</span> <a href="https://auth.aweber.com/1.0/oauth/authorize_app/92c68137" class="button" target="_blank">Click here</a> <span>to open the authorization page and log in.</span>', 'optinpanda' ) ?></li>
                <li><?php _e( '<span>2.</span> Copy and paste the authorization code in the field below.', 'optinpanda' ) ?></li>
            </ul>
        </div>
    </div>

    <?php } else { ?>

    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="control-group controls col-sm-10 opanda-aweber-steps">
            <p><strong><?php _e( 'Your Aweber Account is connected.', 'optinpanda' ) ?></strong></p>
            <ul>
                <li><?php _e( '<a href="' . admin_url('admin.php?page=settings-bizpanda&opanda_screen=subscription&opanda_action=disconnectAweber') . '" class="button onp-sl-aweber-oauth-logout">Click here</a> <span>to disconnect.</span>', 'optinpanda' ) ?></li>                    
            </ul>
        </div>
    </div>

    <?php  
    }
}

/**
 * Shows HTML for MyMail.
 * 
 * @since 1.1.3
 * @return void
 */
function opanda_show_mymail_html() {
    
    if ( !defined('MYMAIL_VERSION') ) {
        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="control-group controls col-sm-10">
                <p><strong><?php _e('The MyMail plugin is not found on your website. Emails will not be saved.', 'opanda') ?></strong></p>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="control-group controls col-sm-10">
                <p><?php _e('You can set a list where the subscribers should be added in the settings of a particular locker.', 'opanda') ?></p>
            </div>
        </div>
    <?php
    }
}
    
/**
 * Shows HTML for MailPoet.
 * 
 * @since 1.1.3
 * @return void
 */
function opnada_show_mailpoet_html() {

    if ( !defined('WYSIJA') ) {
        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="control-group controls col-sm-10">
                <p><strong><?php _e('The MailPoet plugin is not found on your website. Emails will not be saved.', 'opanda') ?></strong></p>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="control-group controls col-sm-10">
                <p><?php _e('You can set a list where the subscribers should be added in the settings of a particular locker.', 'opanda') ?></p>
            </div>
        </div>
    <?php
    }
}
    
/**
 * Shows HTML for Knews.
 * 
 * @since 1.1.3
 * @return void
 */
function opanda_show_knews_html() {

    if ( !class_exists("KnewsPlugin") ) {
        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="control-group controls col-sm-10">
                <p><strong><?php _e('The K-news plugin is not found on your website. Emails will not be saved.', 'opanda') ?></strong></p>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="control-group controls col-sm-10">
                <p><?php _e('You can set a list where the subscribers should be added in the settings of a particular locker.', 'opanda') ?></p>
            </div>
        </div>
    <?php
    }
}

function opanda_on_saving_aweber_options( $caller ) {

    $service = isset( $_POST['opanda_subscription_service'] ) 
            ? $_POST['opanda_subscription_service']:
            null;

    $authCode = isset( $_POST['opanda_aweber_auth_code'] )
            ? trim( $_POST['opanda_aweber_auth_code'] ):
            null;

    unset( $_POST['opanda_aweber_auth_code'] );

    if ( 'aweber' !== $service || get_option('opanda_aweber_consumer_key', false) ) return;

    // if the auth code is empty, show the error

    if ( empty( $authCode ) ) {
        return $caller->showError( __('Unable to connect to Aweber. The Authorization Code is empty.', 'optinpanda' ) );    
    }

    // try to get credential via api, shows the error if the exception occurs

    require_once OPANDA_BIZPANDA_DIR.'/admin/includes/subscriptions.php';
    $aweber = OPanda_SubscriptionServices::getService('aweber');

    try {
        $credential = $aweber->getCredentialUsingAuthorizeKey( $authCode ); 
    } catch (Exception $ex) {
        return $caller->showError( $ex->getMessage() );
    }

    // saves the credential

    if ( $credential && sizeof($credential) ) {
        foreach( $credential as $key => $value ) {
            update_option('opanda_aweber_'.$key, $value);
        }
    }
}
add_action('opanda_on_saving_subscription_settings', 'opanda_on_saving_aweber_options');
    
/**
 * Adds scripts and styles in the admin area.
 * 
 * @see the 'admin_enqueue_scripts' action
 * 
 * @since 1.0.0
 * @return void
 */
function optinpanda_icon_admin_assets( $hook ) { 
        ?>
        <style>
            #menu-posts-opanda-item a[href*="premium-optinpanda"] {
                color: #edd578 !important;
            }
            .admin-color-light #menu-posts-opanda-item a[href*="premium-optinpanda"] {
                color: #b47e42 !important;
            }
        </style>
        <?php
        
        return;
    

}

add_action('admin_enqueue_scripts', 'optinpanda_icon_admin_assets');


// ---
// Help
//

/**
 * Registers a help section for the Connect Locker.
 * 
 * @since 1.0.0
 */
function optinpanda_register_help( $pages ) {
    global $opanda_help_cats;
    if ( !$opanda_help_cats ) $opanda_help_cats = array();
    
    $items = array(
        array(
            'name' => 'email-locker',
            'title' => __('Email Locker', 'optinpanda'),
            'hollow' => true,

            'items' => array(
                array(
                    'name' => 'what-is-email-locker',
                    'title' => __('What is it?', 'optinpanda')
                ),
                array(
                    'name' => 'usage-example-email-locker',
                    'title' => __('Quick Start Guide', 'optinpanda')
                ),
            )
        )
    );
    
    array_unshift($pages, array(
        'name' => 'optinpanda',
        'title' => __('Plugin: Opt-In Panda', 'optinpanda'),
        'items' => $items
    ));
        unset( $pages[1]);
    

    
    return $pages;
}

add_filter('opanda_help_pages', 'optinpanda_register_help');

function opanda_help_page_optinpanda( $manager ) {
    require OPTINPANDA_DIR . '/plugin/admin/pages/help/optinpanda.php';
}

add_action('opanda_help_page_optinpanda', 'opanda_help_page_optinpanda');


/**
 * Makes internal page "License Manager" for the Opt-in Panda
 * 
 * @since 1.0.0
 * @return bool true
 */
function optinpanda_make_internal_license_manager( $internal ) {

    if ( BizPanda::isSinglePlugin() ) return $internal;
    return true;
}

add_filter('factory_page_is_internal_license-manager-optinpanda', 'optinpanda_make_internal_license_manager');


// ---
// Menu
//

/**
 * Changes the menu title if the Opt-In Panda is onle the plugin installed from the BizPanda Collection.
 * 
 * @since 1.0.0
 * @return string
 */
function opanda_change_menu_title( $title ) {
    if ( !BizPanda::isSinglePlugin() ) return $title;
    return __('Opt-In Panda', 'opanda');
}

add_filter('opanda_menu_title', 'opanda_change_menu_title');

/**
 * Returns an URL of page "Go Premium".
 */
function onp_op_get_premium_page_url( $url, $name, $campaign = 'na' ) {
    if ( !empty( $name ) && !in_array( $name, array('email-locker') )) return $url;
    
    if ( get_option('onp_op_skip_trial', false) ) {
        return onp_sl_get_premium_url( $campaign );
    } else {
        return admin_url('edit.php?post_type=opanda-item&page=premium-optinpanda');
    }
}

add_filter('opanda_premium_url', 'onp_op_get_premium_page_url', 10, 3);

/**
 * Returns an URL where the user can purchaes the plugin.
 */
function onp_op_get_url_to_purchase( $campaign = 'na' ) {
    global $optinpanda; 
    return onp_licensing_325_get_purchase_url( $optinpanda, $campaign );
}

/**
 * 
 */
function onp_op_plugins_suggestions( $suggestions ) {
    if ( empty( $suggestions ) ) return $suggestions;

    
    foreach( $suggestions as $index => $item ) {
        
        if ( 'optinpanda' === $item['name'] ) {
            $suggestions[$index]['description'] = '<p>Get more email subscribers the most organic way without tiresome popups.</p><p>Custom fields, export in CSV, content blurring, advanced options and more.</p>';
            continue;
        }
        
        if ( 'optinpanda' === $item['name'] ) {
            $suggestions[$index]['description'] = '<p>Helps to attract social traffic and improve spreading your content in social networks.</p><p>Also extends the Sign-In Locker by adding social actions you can set up to be performed.</p>';
        }
    }
    
    return $suggestions;
}

add_filter('opanda_plugins_suggestions', 'onp_op_plugins_suggestions');