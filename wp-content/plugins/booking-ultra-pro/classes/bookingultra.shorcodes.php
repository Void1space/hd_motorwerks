<?php
class BookingUltraProShortCode {

	function __construct() 
	{
	
		add_action( 'init',   array(&$this,'bup_shortcodes'));	
		add_action( 'init', array(&$this,'respo_base_unautop') );	

	}
	
	/**
	* Add the shortcodes
	*/
	function bup_shortcodes() 
	{	
	
	    add_filter( 'the_content', 'shortcode_unautop');			
		add_shortcode( 'bupro_appointment', array(&$this,'make_appointment') );		
		
		
	}
	
	/**
	* Don't auto-p wrap shortcodes that stand alone
	*/
	function respo_base_unautop() {
		add_filter( 'the_content',  'shortcode_unautop');
	}
	
	public function  make_appointment ($atts)
	{
		global $bookingultrapro;		
		return $bookingultrapro->appointment->get_public_booking_form($atts);
		
		
	}
	
	
	

}
$key = "shortcode";
$this->{$key} = new BookingUltraProShortCode();