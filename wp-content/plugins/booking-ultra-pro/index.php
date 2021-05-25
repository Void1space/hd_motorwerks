<?php
/*
Plugin Name: Booking Ultra Pro CORE
Plugin URI: http://bookingultrapro.com
Description: Booking Plugin for every service provider: dentists, medical services, hair & beauty salons, repair services, event planners, rental agencies, educational services, government agencies, school counsellors and more. This plugin allows you to manage your appointments easily.
Version: 1.0.90
Author: Booking Ultra Pro
Domain Path: /languages
Text Domain: booking-ultra-pro
Author URI: https://bookingultrapro.com/
*/
define('bookingup_url',plugin_dir_url(__FILE__ ));
define('bookingup_path',plugin_dir_path(__FILE__ ));
define('MY_PLUGIN_SETTINGS_URL',"?page=bookingultra&tab=pro");

$plugin = plugin_basename(__FILE__);

/* Loading Function */
require_once (bookingup_path . 'functions/functions.php');

/* Init */
define('bup_pro_url','https://bookingultrapro.com/');

function bup_load_textdomain() 
{     	   
	   $locale = apply_filters( 'plugin_locale', get_locale(), 'booking-ultra-pro' );	   
       $mofile = bookingup_path . "languages/booking-ultra-pro-$locale.mo";
			
		// Global + Frontend Locale
		load_textdomain( 'booking-ultra-pro', $mofile );
		load_plugin_textdomain( 'booking-ultra-pro', false, dirname(plugin_basename(__FILE__)).'/languages/' );
}

/* Load plugin text domain (localization) */
add_action('init', 'bup_load_textdomain');	
		
add_action('init', 'bup_output_buffer');
function bup_output_buffer() {
		ob_start();
}



/* Master Class  */
require_once (bookingup_path . 'classes/bookingultra.class.php');

// Helper to activate a plugin on another site without causing a fatal error by
register_activation_hook( __FILE__, 'bupro_activation');
 
function  bupro_activation( $network_wide ) 
{
	$plugin_path = '';
	$plugin = "booking-ultra-pro/index.php";	
	
	if ( is_multisite() && $network_wide ) // See if being activated on the entire network or one blog
	{ 
		activate_plugin($plugin_path,NULL,true);
			
		
	} else { // Running on a single blog		   	
			
		activate_plugin($plugin_path,NULL,false);		
		
	}
}

$bookingultrapro = new BookingUltraPro();
$bookingultrapro->plugin_init();

register_activation_hook(__FILE__, 'bup_my_plugin_activate');
add_action('admin_init', 'bup_my_plugin_redirect');

function bup_my_plugin_activate() 
{
    add_option('bup_plugin_do_activation_redirect', true);
}

function bup_my_plugin_redirect() 
{
    if (get_option('bup_plugin_do_activation_redirect', false)) {
        delete_option('bup_plugin_do_activation_redirect');
        wp_redirect(MY_PLUGIN_SETTINGS_URL);
    }
}

require_once bookingup_path . 'addons/maintenance/index.php';