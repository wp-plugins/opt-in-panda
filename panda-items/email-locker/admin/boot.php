<?php
/**
 * Boots the code for the admin part of the Email Locker
 * 
 * @since 1.0.0
 * @package core
 */

/**
 * Registers metaboxes for Email Locker.
 * 
 * @see opanda_item_type_metaboxes
 * @since 1.0.0
 */
function opanda_email_locker_metaboxes( $metaboxes ) {
   
    $metaboxes[] = array(
        'class' => 'OPanda_SubscriptionOptionsMetaBox',
        'path' => BIZPANDA_EMAIL_LOCKER_DIR . '/admin/metaboxes/subscription-options.php'
    );

    $metaboxes[] = array(
        'class' => 'OPanda_TermsPrivacyMetaBox',
        'path' => OPANDA_BIZPANDA_DIR . '/includes/metaboxes/terms-privacy.php'
    );
      
        $metaboxes[] = array(
            'class' => 'OPanda_EmailLockerMoreFeaturesMetaBox',
            'path' => BIZPANDA_EMAIL_LOCKER_DIR . '/admin/metaboxes/more-features.php'
        );  
    

    
    return $metaboxes;
}

add_filter('opanda_email-locker_type_metaboxes', 'opanda_email_locker_metaboxes', 10, 1);

/**
 * Prepares the Email Locker to use while activation.
 * 
 * @since 1.0.0
 */
function opanda_email_locker_activation( $plugin, $helper ) {
    
    // default email locker

    $helper->addPost(
        'opanda_default_email_locker_id',
        array(
            'post_type' => OPANDA_POST_TYPE,
            'post_title' => __('Email Locker (default)', 'optinpanda'),
            'post_name' => 'opanda_default_email_locker'
        ),
        array(
            'opanda_item' => 'email-locker',
            'opanda_header' => __('This Content Is Only For Subscribers', 'optinpanda'),       
            'opanda_message' => __('Please subscribe to unlock this content. Enter your email to get access.', 'optinpanda'),
            'opanda_style' => 'great-attractor',
            'opanda_mobile' => 1,       
            'opanda_catch_leads' => 1,
            'opanda_highlight' => 1,                   
            'opanda_is_system' => 1,
            'opanda_is_default' => 1
        )
    );
}

add_action('after_bizpanda_activation', 'opanda_email_locker_activation', 10, 2);


/**
 * Registers default themes.
 * 
 * We don't need to include the file containing the file OPanda_ThemeManager because this function will
 * be called from the hook defined inside the class OPanda_ThemeManager.
 * 
 * @see onp_sl_register_themes
 * @see OPanda_ThemeManager
 * 
 * @since 1.0.0
 * @return void 
 */
function opanda_register_email_locker_themes( $item  ) {
     
        OPanda_ThemeManager::registerTheme(array(
            'name' => 'great-attractor',
            'title' => 'Great Attractor',
            'path' => OPANDA_BIZPANDA_DIR . '/themes/great-attractor',
            'items' => array('signin-locker', 'email-locker') 
        ));

        OPanda_ThemeManager::registerTheme(array(
            'name' => 'friendly-giant',
            'title' => 'Friendly Giant',
            'preview' => 'https://cconp.s3.amazonaws.com/optinpanda/themes-preview/friendly-giant-email.png',
            'previewHeight' => 450,
            'hint' => sprintf( __( 'This theme is available only in the <a href="%s" target="_blank">premium version</a> of the plugin', 'emaillocker' ), opanda_get_premium_url( null, 'themes') ),
            'items' => array('signin-locker', 'email-locker') 
        ));

        OPanda_ThemeManager::registerTheme(array(
            'name' => 'dark-force',
            'title' => 'Dark Force',
            'preview' => 'https://cconp.s3.amazonaws.com/optinpanda/themes-preview/dark-force-email.png',
            'previewHeight' => 450,
            'hint' => sprintf( __( 'This theme is available only in the <a href="%s" target="_blank">premium version</a> of the plugin', 'emaillocker' ), opanda_get_premium_url( null, 'themes') ),
            'items' => array('signin-locker', 'email-locker') 
        ));
    
    
 
}

add_action('onp_sl_register_themes', 'opanda_register_email_locker_themes');


/**
 * Shows the help page 'What it it?' for the Email Locker.
 * 
 * @since 1.0.0
 */
function opanda_help_page_what_is_email_locker( $manager ) {
    require BIZPANDA_EMAIL_LOCKER_DIR . '/admin/help/what-is-it.php';
}

add_action('opanda_help_page_what-is-email-locker', 'opanda_help_page_what_is_email_locker');


/**
 * Shows the help page 'Usage Example' for the Email Locker.
 * 
 * @since 1.0.0
 */
function opanda_help_page_usage_example_email_locker( $manager ) {
    require BIZPANDA_EMAIL_LOCKER_DIR . '/admin/help/usage-example.php';
}

add_action('opanda_help_page_usage-example-email-locker', 'opanda_help_page_usage_example_email_locker');

/**
 * Adds additional libs for email locker in the preview.
 */
function opanda_preview_head_for_email_locker() {
    ?>
    <script type="text/javascript" src="<?php echo OPANDA_BIZPANDA_URL ?>/assets/js/jquery.maskedinput.min.js"></script>
    <script type="text/javascript" src="<?php echo OPANDA_BIZPANDA_URL ?>/assets/js/pikaday.js"></script>
    <link rel="stylesheet" href="<?php echo OPANDA_BIZPANDA_URL ?>/assets/css/font-awesome/css/font-awesome.css">
    <?php
}
add_action('onp_sl_preview_head', 'opanda_preview_head_for_email_locker');

/**
 * Registers the quick tags for the wp editors.
 * 
 * @see admin_print_footer_scripts
 * @since 1.0.0
 */
function opanda_quicktags_for_email_locker()
{ ?>
    <script type="text/javascript">
        (function(){
            if (!window.QTags) return;
            window.QTags.addButton( 'emaillocker', 'emaillocker', '[emaillocker]', '[/emaillocker]' );
        }());
    </script>
<?php 
}

add_action('admin_print_footer_scripts',  'opanda_quicktags_for_email_locker');

/**
 * Registers stats screens for Email Locker.
 * 
 * @since 1.0.0
 */
function opanda_email_locker_stats_screens( $screens ) { 

    $screens = array(
        
        // The Summary Screen
        
        'summary' => array (
            'title' => __('<i class="fa fa-search"></i> Summary', 'optinpanda'),
            'description' => __('The page shows the total number of unlocks for the current locker.', 'emaillocker'),

            'chartClass' => 'OPanda_EmailLocker_Summary_StatsChart',
            'tableClass' => 'OPanda_EmailLocker_Summary_StatsTable',
            'path' => BIZPANDA_EMAIL_LOCKER_DIR . '/admin/stats/summary.php'
        ),
        
        // The Profits Screen
        
        'profits' => array(
            'title' => __('<i class="fa fa-usd"></i> Benefits', 'emaillocker'), 
            'description' => __('The page shows benefits the locker brought for your website.', 'emaillocker'),

            'chartClass' => 'OPanda_EmailLocker_Profits_StatsChart',
            'tableClass' => 'OPanda_EmailLocker_Profits_StatsTable',
            'path' => BIZPANDA_EMAIL_LOCKER_DIR . '/admin/stats/profits.php'
        ),  
         
        // The Channels Screen        
  
        'channels' => array(
            'title' => __('<i class="fa fa-search-plus"></i> Channels', 'emaillocker'), 
            'description' => __('The page shows which ways visitors used to unlock the content.', 'optinpanda'),
            
            'chartClass' => 'OPanda_EmailLocker_Channels_StatsChart',
            'tableClass' => 'OPanda_EmailLocker_Channels_StatsTable',
            'path' => BIZPANDA_EMAIL_LOCKER_DIR . '/admin/stats/channels.php' 
        ),
        
        // The Bounces Screen        
  
        'bounces' => array(
            'title' => __('<i class="fa fa-sign-out"></i> Bounces', 'emaillocker'), 
            'description' => __('The page shows major weaknesses of the locker which lead to bounces. Hover your mouse pointer on [?] in the table, to know more about a particular metric.', 'emaillocker'),
            
            'chartClass' => 'OPanda_EmailLocker_Bounces_StatsChart',
            'tableClass' => 'OPanda_EmailLocker_Bounces_StatsTable',
            'path' => BIZPANDA_EMAIL_LOCKER_DIR . '/admin/stats/bounces.php' 
        ),
  
        // The Skips Screen 
        
        'skips' => array (
            'title' => __('<i class="fa fa-tint"></i> Skips', 'optinpanda'),
            'description' => __('The chart shows how many users skipped the locker by using the Timer or Close Icon, comparing to the users who unlocked the content.', 'optinpanda'),
            
            'chartClass' => 'OPanda_EmailLocker_Skips_StatsChart',
            'tableClass' => 'OPanda_EmailLocker_Skips_StatsTable',
            'path' => BIZPANDA_EMAIL_LOCKER_DIR . '/admin/stats/skips.php'
         )
    );
    
    return $screens;
}

add_filter('opanda_email-locker_stats_screens', 'opanda_email_locker_stats_screens', 10, 1);