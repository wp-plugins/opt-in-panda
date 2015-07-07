<?php 
/**
Plugin Name: Opt-In Panda | BizPanda
Plugin URI: http://api.byonepress.com/public/1.0/get/?product=optinpanda
Description: Opt-In Panda is a lead-generation that motivates visitors to opt-in in return to access your premium content (e.g. downloads, discounts, videos and so on).
Author: OnePress
Version: 1.2.1
Author URI: http://byonepress.com
*/

if (defined('OPTINPANDA_PLUGIN_ACTIVE')) return;
define('OPTINPANDA_PLUGIN_ACTIVE', true);



define('OPTINPANDA_DIR', dirname(__FILE__));
define('OPTINPANDA_URL', plugins_url( null, __FILE__ ));



// ---
// BizPanda Framework
//

// inits bizpanda and its items
require( OPTINPANDA_DIR . '/bizpanda/connect.php');
define('OPTINPANDA_BIZPANDA_VERSION', 116);

/**
 * Fires when the BizPanda connected.
 */
function onp_op_init_bizpanda( $activationHook = false ) {
    
    /**
     * Displays a note about that it's requited to update other plugins.
     */
    if ( !$activationHook && !bizpanda_validate( OPTINPANDA_BIZPANDA_VERSION, 'Opt-In Panda' ) ) return;

    // enabling features the plugin requires
    
    BizPanda::enableFeature('lockers');
    BizPanda::enableFeature('subscription');
    BizPanda::enableFeature('terms');
    
    // creating the plugin object

    global $optinpanda;
    $optinpanda = new Factory325_Plugin(__FILE__, array(
        'name'          => 'optinpanda',
        'title'         => 'Opt-In Panda',
        'version'       => '1.2.1',
        'assembly'      => 'free',
        'lang'          => 'en_US',
        'api'           => 'http://api.byonepress.com/1.1/',
        'premium'       => 'http://api.byonepress.com/public/1.0/get/?product=optinpanda',
        'styleroller'   => 'http://api.byonepress.com/public/1.0/get/?product=optinpanda',
        'account'       => 'http://accounts.byonepress.com/',
        'updates'       => OPTINPANDA_DIR . '/plugin/updates/',
        'tracker'       => /*@var:tracker*/'0ec2f14c9e007ba464c230b3ddd98384'/*@*/,
        'childPlugins'  => array( 'bizpanda' )
    ));
        BizPanda::registerPlugin($optinpanda, 'optinpanda', 'free');
    


    // requires factory modules
    $optinpanda->load(array(
        array( 'bizpanda/libs/factory/bootstrap', 'factory_bootstrap_329', 'admin' ),
        array( 'bizpanda/libs/factory/notices', 'factory_notices_323', 'admin' ),
        array( 'bizpanda/libs/onepress/api', 'onp_api_320' ),
        array( 'bizpanda/libs/onepress/licensing', 'onp_licensing_325' ),
        array( 'bizpanda/libs/onepress/updates', 'onp_updates_324' )
    ));
        require(OPTINPANDA_DIR . '/panda-items/email-locker/boot.php');
    


    require(OPTINPANDA_DIR . '/plugin/boot.php');
}

add_action('bizpanda_init', 'onp_op_init_bizpanda');

/**
 * Activates the plugin.
 * 
 * TThe activation hook has to be registered before loading the plugin.
 * The deactivateion hook can be registered in any place (currently in the file plugin.class.php).
 */
function onp_op_activation() {
    
    // if the old version of the bizpanda which doesn't contain the function bizpanda_connect has been loaded,
    // ignores activation, the message suggesting to upgrade the plugin will be appear instead
    if ( !function_exists( 'bizpanda_connect') ) return;
    
    // if the bizpanda has been already connected, inits the plugin manually
    if ( defined('OPANDA_ACTIVE') ) onp_op_init_bizpanda( true );
    else bizpanda_connect();
        
    global $optinpanda;
    $optinpanda->activate();
}

register_activation_hook( __FILE__, 'onp_op_activation' );

/**
 * Displays a note about that it's requited to update other plugins.
 */
if ( is_admin() && defined('OPANDA_ACTIVE') ) {
    bizpanda_validate( OPTINPANDA_BIZPANDA_VERSION, 'Opt-In Panda' );
}