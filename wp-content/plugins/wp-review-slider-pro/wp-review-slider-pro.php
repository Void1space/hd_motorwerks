<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://ljapps.com
 * @since             1.0
 * @package           WP_Review_Slider_Pro
 *
 * @wordpress-plugin
 * Plugin Name: WP Review Slider Pro (Premium)
 * Plugin URI:        http://ljapps.com/wp-review-slider-pro/
 * Description:       Pro Version - Allows you to easily display your Facebook Page, Google, and Yelp reviews in your Posts, Pages, and Widget areas.
 * Version:           11.0.9.8
 * Author:            LJ Apps
 * Author URI:        http://ljapps.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-review-slider-pro
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
//--------------------
//constants for plugin version and token
define( 'WPREVPRO_PLUGIN_VERSION', '11.0.9.8' );
define( 'WPREVPRO_PLUGIN_TOKEN', 'wp-review-slider-pro' );
//define plugin location constant ex: /home/94285.cloudwaysapps.com/fzamfatyjq/public_html/wp-content/plugins/wp-review-slider-pro-premium/
define( 'WPREV_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPREV_PLUGIN_URL', plugins_url( '', __FILE__ ) );
//$wprevpro_badge_slidepop = array();
//freemius integration
function wrsp_fs()
{
    global  $wrsp_fs ;
    
    if ( !isset( $wrsp_fs ) ) {
        // Include Freemius SDK.
        require_once dirname( __FILE__ ) . '/freemius/start.php';
        $wrsp_fs = fs_dynamic_init( array(
            'id'               => '646',
            'slug'             => 'wp-review-slider',
            'premium_slug'     => 'wp-review-slider-pro',
            'slug'             => 'wp-review-slider-pro',
            'type'             => 'plugin',
            'public_key'       => 'pk_118102a96ccea6cd5fab38e72dc0f',
            'is_premium'       => true,
            'premium_suffix'   => '',
            'has_addons'       => false,
            'has_paid_plans'   => true,
            'is_org_compliant' => false,
            'trial'            => array(
            'days'               => 7,
            'is_require_payment' => true,
        ),
            'has_affiliation'  => 'selected',
            'menu'             => array(
            'slug'        => 'wp_pro-getrevs',
            'support'     => false,
            'affiliation' => true,
            'contact'     => true,
        ),
            'is_live'          => true,
        ) );
    }
    
    return $wrsp_fs;
}

// Init Freemius.
//wrsp_fs();
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-review-slider-pro-activator.php
 */
function activate_WP_Review_Pro( $networkwide )
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-review-slider-pro-activator.php';
    WP_Review_Pro_Activator::activate_all( $networkwide );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-review-slider-pro-deactivator.php
 */
function deactivate_WP_Review_Pro()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-review-slider-pro-deactivator.php';
    WP_Review_Pro_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_WP_Review_Pro' );
register_deactivation_hook( __FILE__, 'deactivate_WP_Review_Pro' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-review-slider-pro.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_WP_Review_Pro()
{
    //define type array that we can loop and display through plugin
    $typearray = array(
        "Manual",
        "Submitted",
        "Airbnb",
        "AngiesList",
        "Facebook",
        "Freemius",
        "GetYourGuide",
        "Google",
        "HousecallPro",
        "iTunes",
        "Nextdoor",
        "Qualitelis",
        "Realtor",
        "TripAdvisor",
        "Twitter",
        "VRBO",
        "WooCommerce",
        "Yelp"
    );
    $typearrayserial = serialize( $typearray );
    define( 'WPREV_TYPE_ARRAY', $typearrayserial );
    //review funnel type array
    $typearrayrf = array(
        "Agoda",
        "Airbnb",
        "AliExpress",
        "AlternativeTo",
        "Amazon",
        "Apartmentratings",
        "Apartments",
        "AppleAppstore",
        "Avvo",
        "BBB",
        "Booking",
        "Bookatable",
        "Capterra",
        "CarGurus",
        "Cars",
        "Citysearch",
        "ClassPass",
        "ConsumerAffairs",
        "CreditKarma",
        "CustomerLobby",
        "DealerRater",
        "Ebay",
        "Edmunds",
        "Etsy",
        "Expedia",
        "Facebook",
        "FindLaw",
        "Foursquare",
        "G2Crowd",
        "Gartner",
        "Glassdoor",
        "Google",
        "GooglePlay",
        "GoogleShopping",
        "GreatSchools",
        "Healthgrades",
        "HomeAdvisor",
        "HomeAway",
        "Homestars",
        "Hotels",
        "Houzz",
        "HungerStation",
        "Indeed",
        "Influenster",
        "InsiderPages",
        "ITCentralStation",
        "Jet",
        "Lawyers",
        "LendingTree",
        "Martindale",
        "Newegg",
        "Niche",
        "OpenRice",
        "Opentable",
        "ProductHunt",
        "ProductReview",
        "RateMDs",
        "ReserveOut",
        "Siftery",
        "Sitejabber",
        "SoftwareAdvice",
        "Talabat",
        "TheKnot",
        "Thumbtack",
        "TripAdvisor",
        "Trulia",
        "TrustedShops",
        "Trustpilot",
        "TrustRadius",
        "Vitals",
        "Walmart",
        "WeddingWire",
        "Yell",
        "YellowPages",
        "Yelp",
        "Zillow",
        "ZocDoc",
        "Zomato"
    );
    $typearrayserialrf = serialize( $typearrayrf );
    define( 'WPREV_TYPE_ARRAY_RF', $typearrayserialrf );
    // Init Freemius.
    $wrspfs = wrsp_fs();
    //echo $wrspfs->get_ajax_action( 'activate_license' );
    //$license = $wrspfs->_get_license();
    $user = $wrspfs->get_user();
    $site = $wrspfs->get_site();
    $license = $wrspfs->_get_license();
    
    if ( is_admin() ) {
        //for passing to funnel.ljapps.com to check license and number of calls
        //define( 'WPREV_FR_SITEID', $site->license_id );
        //define( 'WPREV_FR_URL', $site->url );
        if ( isset( $site->license_id ) ) {
            update_option( 'wprev_fr_siteid', $site->license_id );
        }
        if ( isset( $site->url ) ) {
            update_option( 'wprev_fr_url', $site->url );
        }
    }
    
    // Signal that SDK was initiated.
    do_action( 'wrsp_fs_loaded' );
    //register unistall hook for freemius
    // Not like register_uninstall_hook(), you do NOT have to use a static function.
    wrsp_fs()->add_action( 'after_uninstall', 'wrsp_uninstall_cleanup' );
    //custom icon
    wrsp_fs()->add_filter( 'plugin_icon', 'wrsp_fs_custom_icon' );
    $plugin = new WP_Review_Pro();
    $plugin->run();
}

//add link to change log on plugins menu
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wprevpro_action_links' );
function wprevpro_action_links( $links )
{
    $links[] = '<a href="https://wpreviewslider.userecho.com/knowledge-bases/2/articles/88-change-log" target="_blank">Change Log</a>';
    return $links;
}

function wrsp_fs_custom_icon()
{
    return dirname( __FILE__ ) . '/admin/partials/logo_star.png';
}

//used to remove directories on uninstall
function wpprorev_rmrf( $dir )
{
    foreach ( glob( $dir ) as $file ) {
        
        if ( is_dir( $file ) ) {
            wpprorev_rmrf( "{$file}/*" );
            rmdir( $file );
        } else {
            unlink( $file );
        }
    
    }
}

function wrsp_uninstall_cleanup()
{
    // Leave no trail
    $option1 = 'widget_wprevpro_widget';
    $option2 = 'wp-review-slider-pro_version';
    $option3 = 'wprevpro_options';
    $option4 = 'wprevpro_fb_app_id';
    $option5 = 'wprevpro_hidden_reviews';
    $option6 = 'wprevpro_cookieval';
    $option7 = 'wprevpro_zillowid';
    
    if ( !is_multisite() ) {
        delete_option( $option1 );
        delete_option( $option2 );
        delete_option( $option3 );
        delete_option( $option4 );
        delete_option( $option5 );
        delete_option( $option6 );
        delete_option( $option7 );
        //delete review table in database
        global  $wpdb ;
        $table_name = $wpdb->prefix . 'wpfb_reviews';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review template table
        $table_name = $wpdb->prefix . 'wpfb_post_templates';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review wpfb_badges table
        $table_name = $wpdb->prefix . 'wpfb_badges';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review wpfb_forms table
        $table_name = $wpdb->prefix . 'wpfb_forms';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review wpfb_floats table
        $table_name = $wpdb->prefix . 'wpfb_floats';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review wpfb_reviewfunnel table
        $table_name = $wpdb->prefix . 'wpfb_reviewfunnel';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review wpfb_getapps_forms table
        $table_name = $wpdb->prefix . 'wpfb_getapps_forms';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review wpfb_nofitifcation_forms table
        $table_name = $wpdb->prefix . 'wpfb_nofitifcation_forms';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review wpfb_gettwitter_forms table
        $table_name = $wpdb->prefix . 'wpfb_gettwitter_forms';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review wpfb_total_averages table
        $table_name = $wpdb->prefix . 'wpfb_total_averages';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
    } else {
        global  $wpdb ;
        $blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );
        $original_blog_id = get_current_blog_id();
        foreach ( $blog_ids as $blog_id ) {
            switch_to_blog( $blog_id );
            delete_option( $option1 );
            delete_option( $option2 );
            delete_option( $option3 );
            delete_option( $option4 );
            delete_option( $option5 );
            delete_option( $option6 );
            delete_option( $option7 );
            $table_name = $wpdb->prefix . 'wpfb_reviews';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review template table
            $table_name = $wpdb->prefix . 'wpfb_post_templates';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review wpfb_badges table
            $table_name = $wpdb->prefix . 'wpfb_badges';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review wpfb_forms table
            $table_name = $wpdb->prefix . 'wpfb_forms';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review wpfb_floats table
            $table_name = $wpdb->prefix . 'wpfb_floats';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review wpfb_reviewfunnel table
            $table_name = $wpdb->prefix . 'wpfb_reviewfunnel';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review wpfb_getapps_forms table
            $table_name = $wpdb->prefix . 'wpfb_getapps_forms';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review wpfb_nofitifcation_forms table
            $table_name = $wpdb->prefix . 'wpfb_nofitifcation_forms';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review wpfb_gettwitter_forms table
            $table_name = $wpdb->prefix . 'wpfb_gettwitter_forms';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review wpfb_total_averages table
            $table_name = $wpdb->prefix . 'wpfb_total_averages';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        }
        switch_to_blog( $original_blog_id );
    }
    
    //delete avatar and cache directories
    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir_wprev = $upload_dir . '/wprevslider/';
    wpprorev_rmrf( $upload_dir_wprev );
}

//run single event cron to cache avatars on update-----------------
if ( is_admin() ) {
    add_action( 'admin_init', 'wprevpro_check_cache_avatars_cron' );
}
add_action( 'wprevpro_capic_event', 'wprevpro_check_cache_avatars' );
//used to set a one time event
function wprevpro_check_cache_avatars_cron()
{
    //make sure this is an admin
    
    if ( current_user_can( 'manage_options' ) ) {
        //setup one time cron up if needed
        $current_version = get_option( WPREVPRO_PLUGIN_TOKEN . '_current_ca_pic_version', 0 );
        if ( $current_version != WPREVPRO_PLUGIN_VERSION ) {
            wp_schedule_single_event( time() + 10, 'wprevpro_capic_event' );
        }
    }

}

function wprevpro_check_cache_avatars()
{
    //see if this is a update or new install, only run this once
    $current_version = get_option( WPREVPRO_PLUGIN_TOKEN . '_current_ca_pic_version', 0 );
    update_option( WPREVPRO_PLUGIN_TOKEN . '_current_ca_pic_version', WPREVPRO_PLUGIN_VERSION );
    //cache if we made this far
    
    if ( $current_version != WPREVPRO_PLUGIN_VERSION ) {
        //try to update avatars
        require_once plugin_dir_path( __FILE__ ) . 'admin/class-wp-review-slider-pro-admin_hooks.php';
        $plugin_admin = new WP_Review_Pro_Admin_Hooks( 'wp-review-slider-pro', WPREVPRO_PLUGIN_VERSION );
        $plugin_admin->wprevpro_download_img_tolocal();
    }

}

//-----------------------------------------------------//
//for running lang_code cron job. Not normally going to be used.
add_action( 'wprevpro_daily_event_lang', 'wprevpro_do_this_daily_lang' );
function wprevpro_do_this_daily_lang()
{
    $options = get_option( 'wprevpro_notifications_settings' );
    
    if ( isset( $options['auto_lang_code'] ) && $options['auto_lang_code'] == 1 && $options['api_key'] != '' ) {
        $apikey = $options['api_key'];
        //auto add is turned on we need to run it.
        //echo "running";
        require_once plugin_dir_path( __FILE__ ) . 'admin/class-wp-review-slider-pro-admin_hooks.php';
        $plugin_admin = new WP_Review_Pro_Admin_Hooks( 'wp-review-slider-pro', WPREVPRO_PLUGIN_VERSION );
        $plugin_admin->wprevpro_run_language_detect_ajax_go( $apikey, $page = '0', 30 );
    }
    
    //print_r($options);
}

//for running the cron job
//if($_GET['page']=='wp_pro-getrevs'){
//wpprorev_rf_getapps_cron();
//wprevpro_do_this_daily();
//}
add_action( 'wprevpro_daily_event', 'wprevpro_do_this_daily' );
function wprevpro_do_this_daily()
{
    require_once plugin_dir_path( __FILE__ ) . 'admin/class-wp-review-slider-pro-admin_hooks.php';
    require_once plugin_dir_path( __FILE__ ) . 'admin/wppro_simple_html_dom.php';
    $plugin_admin = new WP_Review_Pro_Admin_Hooks( 'wp-review-slider-pro', WPREVPRO_PLUGIN_VERSION );
    $atleastone = false;
    //cron job for review funnels-------------
    wpprorev_rf_funnel_cron();
    //cron job for itunes-------------
    wpprorev_rf_getapps_cron();
    //download yelp reviews if option settings
    $yelpoptions = get_option( 'wprevpro_yelp_settings' );
    
    if ( isset( $yelpoptions['yelp_radio'] ) && $yelpoptions['yelp_radio'] == 'yes' ) {
        $atleastone = true;
        $plugin_admin->wprevpro_download_yelp_master( 'all', 1 );
    }
    
    //download trip reviews if option settings
    $tripoptions = get_option( 'wprevpro_tripadvisor_settings' );
    
    if ( isset( $tripoptions['tripadvisor_radio_cron'] ) && $tripoptions['tripadvisor_radio_cron'] == 'yes' ) {
        $atleastone = true;
        $plugin_admin->wprevpro_download_tripadvisor_master();
    }
    
    //download airbnb reviews if option settings
    $airbnboptions = get_option( 'wprevpro_airbnb_settings' );
    
    if ( isset( $airbnboptions['airbnb_radio_cron'] ) && $airbnboptions['airbnb_radio_cron'] == 'yes' ) {
        $atleastone = true;
        $plugin_admin->wprevpro_download_airbnb_master();
    }
    
    //download vrbo reviews if option settings
    $vrbooptions = get_option( 'wprevpro_vrbo_settings' );
    
    if ( isset( $vrbooptions['vrbo_radio_cron'] ) && $vrbooptions['vrbo_radio_cron'] == 'yes' ) {
        $atleastone = true;
        $plugin_admin->wprevpro_download_vrbo_master();
    }
    
    //download google reviews if option settings
    $googleoptions = get_option( 'wpfbr_google_options' );
    if ( isset( $googleoptions['google_review_cron'] ) ) {
        
        if ( $googleoptions['google_review_cron'] == 1 ) {
            $atleastone = true;
            $plugin_admin->get_google_reviews( $googleoptions, true );
        }
    
    }
    //download facebook reviews if cron check box selected
    $fbcronpagesarray = get_option( 'wpfb_cron_pages' );
    
    if ( isset( $fbcronpagesarray ) ) {
        $fbcronpagesarray = json_decode( $fbcronpagesarray, true );
        
        if ( count( (array) $fbcronpagesarray ) > 0 ) {
            $atleastone = true;
            $plugin_admin->wprevpro_get_fb_reviews_cron();
        }
    
    }
    
    //------------------------------------------
    if ( $atleastone ) {
        $plugin_admin->wprevpro_download_img_tolocal();
    }
}

//if($_GET['page']=='wp_pro-getrevs'){
//wpprorev_rf_getapps_cron();
//wprevpro_do_this_daily();
//}
function wpprorev_rf_getapps_cron()
{
    //for get apps aka itunes-------------
    global  $wpdb ;
    $table_name = $wpdb->prefix . 'wpfb_getapps_forms';
    $currentappforms = $wpdb->get_results( "SELECT * FROM {$table_name} where cron!=''" );
    require_once plugin_dir_path( __FILE__ ) . 'admin/class-wp-review-slider-pro-admin_hooks.php';
    $plugin_admin = new WP_Review_Pro_Admin_Hooks( 'wp-review-slider-pro', WPREVPRO_PLUGIN_VERSION );
    //print_r($currentappforms);
    foreach ( $currentappforms as $currentform ) {
        $newappformjob = false;
        
        if ( $currentform->cron == '24' ) {
            //echo "run this job once a day";
            $newappformjob = true;
        } else {
            
            if ( $currentform->cron == '48' ) {
                //echo "run this job every other a day";
                $today = date( "j" );
                //number 1 - 31
                //if $today is even then run this cron
                if ( $today % 2 == 0 ) {
                    $newappformjob = true;
                }
            } else {
                
                if ( $currentform->cron != '' ) {
                    //echo "run this job once a week";
                    //run this if difference in last run time and today is greater than 7 days.
                    $difftime = time() - $currentform->last_ran;
                    $timetocheck = $currentform->cron * 60 * 60;
                    if ( $difftime > $timetocheck ) {
                        $newappformjob = true;
                    }
                }
            
            }
        
        }
        
        if ( $newappformjob == true ) {
            //echo "--request ne job fid:".$currentform->id;
            //request a new scrape job
            //$newjobresults = $plugin_admin->wprp_revfunnel_addprofile_ajax_go( $currentform->id, 'usediff' );
            $newjobresults = $plugin_admin->wprp_getapps_getrevs_ajax_go(
                $currentform->id,
                1,
                100,
                0
            );
        }
    }
}

function wpprorev_rf_funnel_cron()
{
    //for review funnels-------------
    global  $wpdb ;
    $table_name = $wpdb->prefix . 'wpfb_reviewfunnel';
    $currentfunnels = $wpdb->get_results( "SELECT * FROM {$table_name} where cron!=''" );
    require_once plugin_dir_path( __FILE__ ) . 'admin/class-wp-review-slider-pro-admin_hooks.php';
    $plugin_admin = new WP_Review_Pro_Admin_Hooks( 'wp-review-slider-pro', WPREVPRO_PLUGIN_VERSION );
    //print_r($currentfunnels);
    foreach ( $currentfunnels as $currentfunnel ) {
        $newfunneljob = false;
        
        if ( $currentfunnel->cron == '24' ) {
            //echo "run this job once a day";
            $newfunneljob = true;
        } else {
            
            if ( $currentfunnel->cron == '48' ) {
                //echo "run this job every other a day";
                $today = date( "j" );
                //number 1 - 31
                //if $today is even then run this cron
                if ( $today % 2 == 0 ) {
                    $newfunneljob = true;
                }
            } else {
                
                if ( $currentfunnel->cron != '' ) {
                    //echo "run this job once a week";
                    //run this if difference in last run time and today is greater than 7 days.
                    $difftime = time() - $currentfunnel->cron_last_ran;
                    $timetocheck = $currentfunnel->cron * 60 * 60;
                    if ( $difftime > $timetocheck ) {
                        $newfunneljob = true;
                    }
                }
            
            }
        
        }
        
        
        if ( $newfunneljob == true && wrsp_fs()->can_use_premium_code() ) {
            //echo "--request ne job fid:".$currentfunnel->id;
            //request a new scrape job
            $newjobresults = $plugin_admin->wprp_revfunnel_addprofile_ajax_go( $currentfunnel->id, 'usediff' );
            
            if ( isset( $newjobresults['job_id'] ) && $newjobresults['job_id'] != '' ) {
                //update db with cron jobid and cron ran on
                $lji = $newjobresults['job_id'];
                $clr = time();
                $cfid = $currentfunnel->id;
                $data = array(
                    'cron_last_job_id' => "{$lji}",
                    'cron_last_ran'    => "{$clr}",
                );
                $format = array( '%s', '%s' );
                $updatetempquery = $wpdb->update(
                    $table_name,
                    $data,
                    array(
                    'id' => $cfid,
                ),
                    $format,
                    array( '%d' )
                );
                //setup a one time event to download reviews from this scrape job in 6 hours, gives it time to complete
                wp_schedule_single_event( time() + 21600, 'wprevpro_rf_cron_after', array( $lji, $cfid ) );
            }
        
        }
    
    }
    //-------------
}

//----
//testing date
//This will run 30 minutes after the review funnel cron, fired by one time event.
function wpopro_rf_cron_in_thirty( $job_id, $fid )
{
    require_once plugin_dir_path( __FILE__ ) . 'admin/class-wp-review-slider-pro-admin_hooks.php';
    $plugin_admin = new WP_Review_Pro_Admin_Hooks( 'wp-review-slider-pro', WPREVPRO_PLUGIN_VERSION );
    //now we are getting the reviews from the previously requested cron scrape
    $scrapethejob = $plugin_admin->wprp_revfunnel_getrevs_ajax_go( $job_id, $fid );
    //update cache
    $plugin_admin->wprevpro_download_img_tolocal();
}

add_action(
    'wprevpro_rf_cron_after',
    'wpopro_rf_cron_in_thirty',
    10,
    2
);
//print_r(wprevpro_gettotalavgs('ChIJC8DB3J5sYogRV8b_lTk20U4'));
//===public admin function so developers can get an array of values from avgtotal table
function wprevpro_gettotalavgs( $pageid )
{
    global  $wpdb ;
    $table_name = $wpdb->prefix . 'wpfb_total_averages';
    $totalavgdata = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_name} where btp_id = %s ", $pageid ), ARRAY_A );
    return $totalavgdata;
}

//====================
//check for whitelable settings
// Get the contents of the JSON file
/*
$wlsettingsfilename = WPREV_PLUGIN_DIR . 'admin/wlsettings.txt';
if (file_exists($wlsettingsfilename)) {
	$wlsettingsContents = file_get_contents($wlsettingsfilename);
	$wlsettingsarray = json_decode($wlsettingsContents, true);
	print_r($wlsettingsarray); // print array
}
*/
//start the plugin-------------
run_WP_Review_Pro();
//======================
//check if any free versions are active, if so then add message to admin-----

if ( in_array( 'wp-google-places-review-slider/wp-google-reviews.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    //plugin is activated
    function wp_rev_freeversion_admin_notice_google()
    {
        $class = 'notice notice-error is-dismissible';
        $message = __( 'Warning! Please de-activate the free version (WP Google Review Slider) of the WP Review Slider Pro plugin. You can not use the Free version and the Pro version at the same time. If you would like to completely delete the free version, then first de-activate the Pro version.', 'wp-review-slider-pro' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }
    
    add_action( 'admin_notices', 'wp_rev_freeversion_admin_notice_google' );
}


if ( in_array( 'wp-airbnb-review-slider/wp-airbnb-review-slider.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    //plugin is activated
    function wp_rev_freeversion_admin_notice_airbnb()
    {
        $class = 'notice notice-error is-dismissible';
        $message = __( 'Warning! Please de-activate the free version (WP Airbnb Review Slider) of the WP Review Slider Pro plugin. You can not use the Free version and the Pro version at the same time. If you would like to completely delete the free version, then first de-activate the Pro version.', 'wp-review-slider-pro' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }
    
    add_action( 'admin_notices', 'wp_rev_freeversion_admin_notice_airbnb' );
}


if ( in_array( 'wp-facebook-reviews/wp-fb-reviews.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    //plugin is activated
    function wp_rev_freeversion_admin_notice_fb()
    {
        $class = 'notice notice-error is-dismissible';
        $message = __( 'Warning! Please de-activate the free version (WP Review Slider) of the WP Review Slider Pro plugin. You can not use the Free version and the Pro version at the same time. If you would like to completely delete the free version, then first de-activate the Pro version.', 'wp-review-slider-pro' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }
    
    add_action( 'admin_notices', 'wp_rev_freeversion_admin_notice_fb' );
}


if ( in_array( 'wp-tripadvisor-review-slider/wp-tripadvisor-review-slider.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    //plugin is activated
    function wp_rev_freeversion_admin_notice_trip()
    {
        $class = 'notice notice-error is-dismissible';
        $message = __( 'Warning! Please de-activate the free version (WP TripAdvisor Review Slider) of the WP Review Slider Pro plugin. You can not use the Free version and the Pro version at the same time. If you would like to completely delete the free version, then first de-activate the Pro version.', 'wp-review-slider-pro' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }
    
    add_action( 'admin_notices', 'wp_rev_freeversion_admin_notice_trip' );
}


if ( in_array( 'wp-yelp-review-slider/wp-yelp-review-slider.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    //plugin is activated
    function wp_rev_freeversion_admin_notice_yelp()
    {
        $class = 'notice notice-error is-dismissible';
        $message = __( 'Warning! Please de-activate the free version (WP Yelp Review Slider) of the WP Review Slider Pro plugin. You can not use the Free version and the Pro version at the same time. If you would like to completely delete the free version, then first de-activate the Pro version.', 'wp-review-slider-pro' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }
    
    add_action( 'admin_notices', 'wp_rev_freeversion_admin_notice_yelp' );
}


if ( in_array( 'wp-zillow-review-slider/wp-zillow-review-slider.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    //plugin is activated
    function wp_rev_freeversion_admin_notice_zillow()
    {
        $class = 'notice notice-error is-dismissible';
        $message = __( 'Warning! Please de-activate the free version (WP Zillow Review Slider) of the WP Review Slider Pro plugin. You can not use the Free version and the Pro version at the same time. If you would like to completely delete the free version, then first de-activate the Pro version.', 'wp-review-slider-pro' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }
    
    add_action( 'admin_notices', 'wp_rev_freeversion_admin_notice_zillow' );
}


if ( in_array( 'wp-thumbtack-review-slider/wp-thumbtack-review-slider.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    //plugin is activated
    function wp_rev_freeversion_admin_notice_thumbtack()
    {
        $class = 'notice notice-error is-dismissible';
        $message = __( 'Warning! Please de-activate the free version (WP Thumbtack Review Slider) of the WP Review Slider Pro plugin. You can not use the Free version and the Pro version at the same time. If you would like to completely delete the free version, then first de-activate the Pro version.', 'wp-review-slider-pro' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }
    
    add_action( 'admin_notices', 'wp_rev_freeversion_admin_notice_thumbtack' );
}
