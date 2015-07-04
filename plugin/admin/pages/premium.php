<?php
/**
 * The file contains a short help info.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2014, OnePress Ltd
 * 
 * @package core 
 * @since 1.0.0
 */

/**
 * Common Settings
 */
class OPanda_PremiumPage extends FactoryPages321_AdminPage  {
 
    public $menuPostType = OPANDA_POST_TYPE;
    public $id = "premium";
    
    public function __construct(Factory325_Plugin $plugin) {   
        parent::__construct($plugin);

        add_filter( 'factory_menu_title_premium-optinpanda' , array( $this, 'fixMenuTitle') ) ;
    }
    
    public function fixMenuTitle() {
        if ( BizPanda::isSinglePlugin() ) return __('Go Premium', 'optinpanda');
        return __('Opt-In Panda Pro', 'optinpanda');
    }
  
    public function assets($scripts, $styles) {
        $this->scripts->request('jquery');
        $this->styles->add(OPANDA_BIZPANDA_URL . '/assets/admin/css/premium.030100.css');   
        $this->styles->request('bootstrap.core', 'bootstrap');
    }

    /**
     * Shows 'Get more features!'
     * 
     * @sinve 1.0.0
     * @return void
     * 
     */
    public function indexAction() {
        global $optinpanda;
        
        $alreadyActivated = get_option('onp_trial_activated_' . $optinpanda->pluginName, false);
        
        $skipTrial = get_option('onp_sl_skip_trial', false);
        if ( $skipTrial ) {
            wp_redirect( onp_op_get_url_to_purchase('go-premium') );
            exit;
        }
        
        ?>

        <style>
            .onp-how-comparation .onp-how-group {
                background-color: transparent;
            }
            .onp-how-comparation .onp-how-group td {
                font-weight: bold;
            }

            .onp-how-comparation .onp-how-group .onp-how-premium {
                background-color: #ffe581;
                border-bottom: 1px solid #eeca70 !important;
                color: #9c7e42;
                font-weight: bold;
            }
            .onp-how-comparation .onp-how-premium {
                background-color: #fff7d7;
                border-bottom: 1px solid #fff4c7 !important;
                padding-left: 12px !important;
            }
            .onp-how-comparation .onp-how-premium,
            .onp-how-comparation .onp-how-premium strong {
                color: #9c7e42;
            } 

            #activate-trial-btn, #onp-sl-purchase-btn {
                background: #ffe16c;
                border: 0px;
                border-bottom: 3px solid #e0b854;
                -webkit-box-shadow: none;
                -moz-box-shadow: none;
                box-shadow: none;
                padding: 20px 0 15px 0 !important;
                color: #9c7e42;
                font-weight: bold;
                font-size: 18px;
                border-radius: 5px;
            }
            #activate-trial-btn:hover, #onp-sl-purchase-btn:hover {
                background: #ffdc55;
                border-color: #e0b854;
            }
            
        </style>

        <div class="wrap factory-bootstrap-329 factory-fontawesome-320">
            <div class="onp-page-content">
                <div class="onp-inner-wrap">
                    
        <div class="onp-page-section">
            
            <?php if ( !$alreadyActivated ) { ?>
                <h1><?php _e('Try Premium Version For 7 Days For Free!', 'optinpanda'); ?></h1>
            <?php } else { ?>
                <h1><?php _e('Upgrade Opt-In Panda To Premium!', 'optinpanda'); ?></h1>     
            <?php } ?>

            <?php if ( !$alreadyActivated ) { ?>  
            <p>
                <?php printf( __('The plugin you are using is a free version of the popular <a target="_blank" href="%s"> Opt-In Panda</a> plugin. 
                We offer you to try the premium version for 7 days absolutely for free. We are sure you will enjoy it!', 'optinpanda'), onp_op_get_url_to_purchase('go-premium') ) ?>
            </p>
            <p>
                <?php _e('Check out the table below to know about the premium features.', 'optinpanda'); ?>
            </p>
            <?php } else { ?>
            <p>
                <?php _e('The plugin you are using is a free version of the popular <a target="_blank" href="%s"> Opt-In Panda plugin</a> sold on CodeCanyon.', 'optinpanda') ?>
                <?php _e('Check out the table below to know about all the premium features.', 'optinpanda'); ?>
            </p>   
            <?php } ?>

        </div>

        <div class="onp-page-section">
            <h2><i class="fa fa-star-o"></i> Comparison of Free & Premium Versions</h2>
            <p>Click on the dotted title to learn more about a given feature.</p>
            <table class="table table-bordered onp-how-comparation">
                <tbody>
                    
                    <tr class="onp-how-group">
                        <td class="onp-how-group-title"><i class="fa fa-cogs"></i> Common Features</td>
                        <td class="onp-how-yes">Free</td>
                        <td class="onp-how-yes onp-how-premium"><i class="fa fa-star-o"></i> Premium</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">Unlimited Lockers</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">Single/Double Opt-In</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>         
                    <tr>
                        <td class="onp-how-title"><a href="#extra-options">Visibility Options</a> <i class="fa fa-star-o"></i></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>    
                        <td class="onp-how-title"><a href="#extra-options">Advanced Options</a> <i class="fa fa-star-o"></i></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>   
                    <tr>
                        <td class="onp-how-title"><a href="#custom-fields">Custom Fields</a> <i class="fa fa-star-o"></i></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title"><a href="#social">Social Subscription</a> <i class="fa fa-star-o"></i></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr>           
                    <tr>
                        <td class="onp-how-title">Export Emails in CSV <i class="fa fa-star-o"></i></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>   
                    </tr> 
                    
                    <tr class="onp-how-group-separator">
                      <td colspan="3"></td>   
                    </tr>
                    <tr class="onp-how-group">
                        <td class="onp-how-group-title"><i class="fa fa-adjust"></i> Overlap Modes</td>
                        <td class="onp-how-yes">Free</td>
                        <td class="onp-how-yes onp-how-premium"><i class="fa fa-star-o"></i> Premium</td>   
                    </tr>

                    <tr>
                        <td class="onp-how-title">Full</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title">Transparency</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title"><a href="#blurring">Blurring</a> <i class="fa fa-star-o"></i></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>     
                    </tr>   
                    
                    <tr class="onp-how-group-separator">
                      <td colspan="3"></td>   
                    </tr>
                    <tr class="onp-how-group">
                        <td class="onp-how-group-title"><i class="fa fa-picture-o"></i> Themes</td>
                        <td class="onp-how-yes">Free</td>
                        <td class="onp-how-yes onp-how-premium"><i class="fa fa-star-o"></i> Premium</td>   
                    </tr>

                    <tr>
                        <td class="onp-how-title onp-how-group-in-group">Theme 'Great Attractor'</td>
                        <td class="onp-how-yes">yes</td>
                        <td class="onp-how-yes onp-how-premium">yes</td>   
                    </tr>
                    <tr>
                        <td class="onp-how-title onp-how-group-in-group">Theme 'Friendly Giant' <i class="fa fa-star-o"></i></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>     
                    </tr>          
                    <tr>
                        <td class="onp-how-title onp-how-group-in-group">Theme 'Dark Force' <i class="fa fa-star-o"></i></td>
                        <td class="onp-how-no">-</td>
                        <td class="onp-how-yes onp-how-premium"><strong>yes</strong></td>    
                    </tr>
                    
                    <tr class="onp-how-group-separator">
                      <td colspan="3"></td>   
                    </tr>
                    <tr class="onp-how-group">
                        <td class="onp-how-group-title"><i class="fa fa-picture-o"></i> Services</td>
                        <td class="onp-how-yes">Free</td>
                        <td class="onp-how-yes onp-how-premium"><i class="fa fa-star-o"></i> Premium</td>   
                    </tr>

                    <tr>
                        <td class="onp-how-title onp-how-group-in-group"><a href="#updates">Updates</a></td>
                        <td class="onp-how-no">not guaranteed</td>
                        <td class="onp-how-yes onp-how-premium">primary updates</td>   
                    </tr>      
                    <tr>
                        <td class="onp-how-title"><a href="#support">Support</a></td>
                        <td class="onp-how-no">not guaranteed</td>
                        <td class="onp-how-yes onp-how-premium">dedicated support</td>   
                    </tr>  
                </tbody>
            </table>
            
            <?php if ( !$alreadyActivated ) { ?>
            
            <div>
                <a class="button button-primary" id="activate-trial-btn" href="<?php echo onp_licensing_325_manager_link($this->plugin->pluginName, 'activateTrial', false ) ?>">
                    <i class="fa fa-star-o"></i>
                    Click Here To Activate Your Free Trial For 7 Days
                    <i class="fa fa-star-o"></i>
                    <br />
                    <small>(instant activation by one click)</small>
                </a>
            </div>
            
            <?php } else { ?>
            
            <div class='factory-bootstrap-329'>
                <a class="btn btn-gold" id="onp-sl-purchase-btn" href="<?php echo onp_op_get_url_to_purchase( 'go-premium' ) ?>">
                    <i class="fa fa-star"></i>
                    Purchase Opt-In Panda Premium For $24 Only
                    <i class="fa fa-star"></i>
                </a>
            </div>
            
            <?php } ?>

            <?php if ( !$alreadyActivated ) { ?>

            <p style="text-align: center; margin-top: 20px;">
                <a href="<?php echo onp_op_get_url_to_purchase( 'go-premium' ) ?>" style="color: #111;"><strong>Or Buy The Opt-In Panda Right Now For $22 Only</strong></a>
            </p>

            <?php } ?>
            
        </div>

        <div class="onp-page-section" id="extra-options">
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Set How, When and For Whom Your Lockers Appear', 'optinpanda'); ?>
            </h1>
            
            <p>Each website has its own unique audience. We know that a good business is an agile business. The premium version of Opt-In Panda provides 8 additional options that allow you to configure the lockers flexibly to meet your needs.</p>

            <p class='onp-img'>
                <img src='http://cconp.s3.amazonaws.com/bizpanda/advanced-options.png' />
            </p>
            <div class="clearfix"></div>
        </div> 

        <div class="onp-page-section" id='blurring'>
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Create Interest to Your Content via Overlay Effects', 'optinpanda'); ?>
            </h1>
            <p>It attracts tremendous attention and create huge interest to your locked content. If people see and understand what they will get after unlocking, they are more likely to leave their emails.</p>
            <p class='onp-img'>
                <img src='http://cconp.s3.amazonaws.com/bizpanda/email-blurring.png' />
            </p>
        </div> 
         
        <div class="onp-page-section" id='custom-fields'>
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Unlimited Custom Fields To Fit Your Business Needs', 'optinpanda'); ?>
            </h1>
            <p>
                <p>Need to ask your visitors for a phone number or address? No problem! You can create custom textboxes (with/without input mask), dropdown lists, checkboxes and more.</p>
            </p>
            <p class='onp-img'>
                <img src='http://cconp.s3.amazonaws.com/bizpanda/custom-fields.png' />
            </p>
        </div>       
                        
        <div class="onp-page-section" id='social'>
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Subscribe Visitors through Facebook, Twitter, Google or LinkedIn', 'optinpanda'); ?>
            </h1>
            <p>
                <p>Except subscription via the classic opt-in form, the premium plugin also provides visitors an option to subscribe through social networks.</p>
            </p>
            <p class='onp-img'>
                <img src='http://cconp.s3.amazonaws.com/bizpanda/signin-locker.png' />
            </p>
            <p>
                For each social button, you can configure extra actions which should be performed when the user clicks on the social button. For example, you can set up the locker to create an account for the user on your website.
            </p>
            <p class='onp-img'>
                <img src='http://cconp.s3.amazonaws.com/bizpanda/social-actions.png' />
            </p>
        </div>                       

        <div class="onp-page-section" id='updates'>
            <h1>
                <i class="fa fa-star-o"></i> <?php _e('Updates Almost Every Week & Guaranteed Support Within 24h', 'optinpanda'); ?>
            </h1>
            <p>We release about 3-4 updates each month, adding new features and fixing bugs. The Free version does not guarantee that you will get all the major updates. If you upgrade to the Premium version, your copy of the plugin will be always up-to-date.</p>
            <p>
                All of our plugins come with free support. We care about your plugin after purchase just as much as you do. We want to make your life easier and make you happy about choosing our plugins.
            </p>
            <p>
                Unfortunately we receive plenty of support requests every day and we cannot answer to all the users quickly. But for the users of the premium version (and the trial version), we guarantee to respond to every inquiry within 1 business day (typical response time is 3 hours).
            </p>
        </div> 

        <div class="onp-page-section">
            
            <?php if ( !$alreadyActivated ) { ?>
            
            <div>
                <a class="button button-primary" id="activate-trial-btn" href="<?php echo onp_licensing_325_manager_link($this->plugin->pluginName, 'activateTrial', false ) ?>">
                    <i class="fa fa-star-o"></i>
                    Click Here To Activate Your Free Trial For 7 Days
                    <i class="fa fa-star-o"></i>
                    <br />
                    <small>(instant activation by one click)</small>
                </a>
            </div>
            
            <?php } else { ?>
            
            <div class='factory-bootstrap-329'>
                <a class="btn btn-gold" id="onp-sl-purchase-btn" href="<?php echo onp_op_get_url_to_purchase( 'go-premium' ) ?>">
                    <i class="fa fa-star"></i>
                    Purchase Opt-In Panda Premium For $24 Only
                    <i class="fa fa-star"></i>
                </a>
            </div>
            
            <?php } ?>

            <?php if ( !$alreadyActivated ) { ?>

            <p style="text-align: center; margin-top: 20px;">
                <a href="<?php echo onp_op_get_url_to_purchase( 'go-premium' ) ?>" style="color: #111;"><strong>Or Buy The Opt-In Panda Right Now For $22 Only</strong></a>
            </p>

            <?php } ?>
            
        </div> 

        <div class="onp-page-section">
            <div class="onp-remark">
                <div class="onp-inner-wrap">
                    <p>
                        <?php _e('You can purchase the premium version at any time within your trial period or right now. After purchasing you will get a license key to unlock all the plugin features.', 'optinpanda'); ?>
                        <?php printf(__('<strong>To purchase the Opt-In Panda</strong>, <a target="_blank" href="%s">click here</a> to visit the plugin page on CodeCanyon. Then click the "Purchase" button on the right sidebar.', 'optinpanda'), onp_op_get_url_to_purchase( 'go-premium' )); ?>
                    </p>
                </div>
            </div>
        </div> 

                
                </div>
            </div>    
        </div> 
        <?php
    }
}

FactoryPages321::register($optinpanda, 'OPanda_PremiumPage');
