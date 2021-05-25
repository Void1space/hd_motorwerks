<?php
class BookingUltraPro
{
	public $classes_array = array();
	public $registration_fields;
	public $login_fields;
	public $fields;
	public $allowed_inputs;
	public $use_captcha = "no";
	
	
		
	public function __construct()
	{		
		
		$this->logged_in_user = 0;
		$this->login_code_count = 0;
		$this->current_page = $_SERVER['REQUEST_URI'];
		
				
	
    }
	
	public function plugin_init() 
	{	
		
		/*Load Amin Classes*/		
		if (is_admin()) 
		{
			$this->set_admin_classes();
			$this->load_classes();					
		
		}else{
			
			/*Load Main classes*/
			$this->set_main_classes();
			$this->load_classes();
			
		
		}
		
		//ini settings
		$this->intial_settings();
		
		
	}
	
 
	public function set_main_classes()
	{
		 $this->classes_array = array( "commmonmethods" =>"bookingultra.common",
		 
		 "shortcode" =>"bookingultra.shorcodes",
		 "appointment" =>"bookingultra.appointment",
		 "breaks" =>"bookingultra.break",
		 "paypal" =>"bookingultra.paypal",
		 "register" =>"bookingultra.register",
		 "order" =>"bookingultra.order",
		 "service" =>"bookingultra.service",
		 "userpanel" =>"bookingultra.user",
		 "captchamodule" =>"bookingultra.captchamodules",
		 "imagecrop" =>"bookingultra.cropimage",
		 "messaging" =>"bookingultra.messaging"	
		 
		   ); 	
	
	}
	
	public function set_admin_classes()
	{
				 
		 $this->classes_array = array( "commmonmethods" =>"bookingultra.common" , 
			
		 "shortcode" =>"bookingultra.shorcodes",
		 "appointment" =>"bookingultra.appointment",
		  "breaks" =>"bookingultra.break",
		 "paypal" =>"bookingultra.paypal",
		 "register" =>"bookingultra.register",
		 "order" =>"bookingultra.order",
		 "buupadmin" =>"bookingultra.admin"	,				
		 "service" =>"bookingultra.service",
		 "userpanel" =>"bookingultra.user",
		 "imagecrop" =>"bookingultra.cropimage",
		 "captchamodule" =>"bookingultra.captchamodules",
		 "adminshortcode" =>"bookingultra.adminshortcodes",
		 "messaging" =>"bookingultra.messaging"
		 
		  
		   ); 	
		 
		
	}
	
	
	public  function get_date_picker_format( )
    {
		global  $bookingultrapro;
		
		$date_format = $bookingultrapro->get_option('bup_date_picker_format');
		
		if($date_format=='d/m/Y'){			
			
			$date_format = 'dd/mm/yy';
			
		}elseif($date_format=='m/d/Y'){
			
			$date_format = 'mm/dd/yy';			
			
		}else{
			
			$date_format = 'mm/dd/yy';
			
		}
        return $date_format;
		
	
	}
	
	public  function get_date_picker_date( )
    {
		global  $bookingultrapro;
		
		$date_format = $bookingultrapro->get_option('bup_date_picker_format');
		
		if($date_format==''){			
			
			$date_format = 'm/d/Y';					
		}
        return $date_format;
		
	
	}
	
	public function get_my_account_page()
	{
		global $bookingultrapro, $wp_rewrite, $blog_id ; 
		
		$wp_rewrite = new WP_Rewrite();
		
		$account_page_id = get_option('bup_my_account_page');				
		$my_account_url = get_page_link($account_page_id);				
				
		if($my_account_url=="")
		{
			$url = $_SERVER['REQUEST_URI'];
						
		}else{
							
			$url = $my_account_url;				
						
		}
					
		 return $url ;
		
	
	}
	
	public  function get_int_date_format( )
    {
		global  $bookingultrapro;
		
		$date_format = $bookingultrapro->get_option('bup_date_admin_format');
		
		if($date_format==''){			
			
			$date_format = 'm/d/Y';					
		}
        return $date_format;
		
	
	}
	
	
	
	public function pluginname_ajaxurl() 
	{
		echo '<script type="text/javascript">var ajaxurl = "'. admin_url("admin-ajax.php") .'";
</script>';
	}
	
	
	public function intial_settings()
	{
		add_action( 'admin_notices', array(&$this, 'bup_display_custom_message'));
		
		
			
		add_action( 'wp_ajax_create_default_pages_auto', array( $this, 'create_default_pages_auto' ));	
		add_action( 'wp_ajax_bup_hide_proversion_message', array( $this, 'hide_proversion_message' ));				
			 			 
		$this->include_for_validation = array('text','fileupload','textarea','select','radio','checkbox','password');
			
		add_action('wp_enqueue_scripts', array(&$this, 'add_front_end_styles'), 9); 
		add_action('admin_enqueue_scripts', array(&$this, 'add_styles_scripts'), 9);
		
		/*Create a generic profile page*/
		add_action( 'init', array(&$this, 'activate_profile_module'), 9);
		
		/* Remove bar except for admins */
		//add_action('init', array(&$this, 'bup_remove_admin_bar'), 9);	
		
		/* Create Standar Fields */		
		add_action('init', array(&$this, 'upp_create_standard_fields'));
		add_action('admin_init', array(&$this, 'upp_create_standard_fields'));	
		
		add_action('init', array(&$this, 'create_default_business_hours'));
		add_action('admin_init', array(&$this, 'create_default_business_hours'));			
				
		/*Setup redirection*/
		add_action( 'wp_head', array(&$this, 'pluginname_ajaxurl'));
		//add_action( 'mce_css', array(&$this, 'bup_my_theme_add_editor_styles'));	
		
		
		
		
	}
	
	
	
	
	
	
	
	
	public function activate_profile_module ()
	{
		$this->create_initial_pages();
		
	}
	
	public function create_initial_pages ()
	{
		global $bookingultrapro;
		
		$fresh_page_creation  = get_option( 'bup_auto_page_creation' );			
		$profile_page_id = $this->get_option('profile_page_id');
		
		//if($profile_page_id!='' && is_page($profile_page_id))	
		
		if($profile_page_id!='' )	
		{
			$profile_page = get_post($profile_page_id);
			$slug =  $profile_page->post_name;
			
			if($fresh_page_creation==1) //user wants to recreate pages
			{						
				 //pages created
				 update_option('bup_auto_page_creation',0);			 
				
				add_rewrite_rule("$slug/([^/]+)/?",'index.php?page_id='.$profile_page_id.'&bup_username=$matches[1]', 'top');		
				//this rules is for displaying the user's profiles
				add_rewrite_rule("([^/]+)/$slug/([^/]+)/?",'index.php?page_id='.$profile_page_id.'&bup_username=$matches[2]', 'top');
				
				flush_rewrite_rules(false);
			
			 }else{			
					
				add_rewrite_rule("$slug/([^/]+)/?",'index.php?page_id='.$profile_page_id.'&bup_username=$matches[1]', 'top');		
				//this rules is for displaying the user's profiles
				add_rewrite_rule("([^/]+)/$slug/([^/]+)/?",'index.php?page_id='.$profile_page_id.'&bup_username=$matches[2]', 'top');
			
			}
		
		}
			
		/* Setup query variables */
		 add_filter( 'query_vars',   array(&$this, 'bup_uid_query_var') );				
			
	}
	
	public function bup_uid_query_var( $query_vars )
	{
		$query_vars[] = 'bup_username';
		//$query_vars[] = 'searchuser';
		return $query_vars;
	}
	
	public function create_rewrite_rules() 
	{
		global  $bookingultrapro;
		
		//$slug = $bookingultrapro->get_option("bup_slug"); // Profile Slug
		$profile_page_id = $this->get_option('profile_page_id');
		$profile_page = get_post($profile_page_id);
		$slug =  $profile_page->post_name;
		
		add_rewrite_rule("$slug/([^/]+)/?",'index.php?page_id='.$profile_page_id.'&bup_username=$matches[1]', 'top');		
			//this rules is for displaying the user's profiles
		add_rewrite_rule("([^/]+)/$slug/([^/]+)/?",'index.php?page_id='.$profile_page_id.'&bup_username=$matches[2]', 'top');
		
		flush_rewrite_rules(false);
	
	
	}
	
	public function hide_proversion_message () 
	{
		$message= $_POST['message_id'];		
		update_option('bup_pro_improvement_'.$message,1);
		die();
		
	}
	
	public function display_ultimate_validate_copy () 
	{	
			
		$res_message  = get_option( 'bup_pro_improvement_13' );		
		if($res_message=="" )
		{
		
			$message = '<div id="message" class="updated buppro-message wc-connect">
	<a class="buppro-message-close notice-dismiss" href="" message-id="13"> '.__('Dismiss','booking-ultra-pro').'</a>

	<p><strong>Booking Ultra Pro Updates:</strong> – We highly recommend you creating a serial number for your domain which will allow you to update your plugin automatically.</p>
	
	<p class="submit">
		
		<a href="?page=bookingultra&tab=licence" class="button-secondary" > '.__('Validate your Copy','booking-ultra-pro').'</a>
	</p>
</div>';
			
			
		echo $message;	
		
		}
		
		
		
		
	}
	
	
	public function bup_display_custom_message () 
	{
				
				
		//Pro major 1.1.13 message		
		$bup_pro_message  = get_option( 'bup_pro_improvement_12' );		
		if($bup_pro_message=="" )
		{
		
			$message = '<div id="message" class="updated buppro-message wc-connect">
	<a class="buppro-message-close notice-dismiss" href="#" message-id="12">'.__('Dismiss','booking-ultra-pro').'</a>

	<p><strong>Booking Ultra Pro has new features:</strong> – We have implemented the Shopping Cart option. This feature allows clients to purchase several services on the same session and pay for them in bulk.</p>
	
	<p class="submit">
		<a href="https://bookingultrapro.com/demo/booking-form-with-shopping-cart/" class="button-primary" target="_blank">Check Example</a>
		<a href="http://doc.bookingultrapro.com/how-to-activate-the-shopping-cart-feature/" class="button-secondary" target="_blank">Shopping Cart Integration Guide</a>
	</p>
</div>';		
			
		$this->bup_fresh_install_message($message);	
				
		}
		
		$res_message  = get_option( 'bup_pro_improvement_14' );	
		
		if($res_message=="" )
		{
		
			$message = '<div id="message" class="updated buppro-message wc-connect">
	<a class="buppro-message-close notice-dismiss" href="#" message-id="14">'.__('Dismiss','booking-ultra-pro').'</a>

	<p><strong>Booking Ultra Pro Staff & Client Backend:</strong> – Your Staff Members will have a Backend on the front-end that allows them to manage their incoming appointment requests. Also, the clients have a panel that allows them to manage their own appointments.</p>
	
	<p class="submit">
		<a href="https://bookingultrapro.com/booking-ultra-screenshots-staff-backend.html" class="button-primary" target="_blank">'.__('See Screenshots','booking-ultra-pro').'</a>
		<a href="http://doc.bookingultrapro.com/getting-started-with-the-staff-client-account-module/" class="button-secondary" target="_blank">'.__('Getting Started','booking-ultra-pro').'</a>
	</p>
</div>';		
			
		$this->bup_fresh_install_message($message);	
				
		}
		
		$res_message  = get_option( 'bup_pro_improvement_15' );	
		
		if($res_message=="" )
		{
		
			$message = '<div id="message" class="updated buppro-message wc-connect">
	<a class="buppro-message-close notice-dismiss" href="#" message-id="15">'.__('Dismiss','booking-ultra-pro').'</a>

	<p><strong>Booking Ultra Pro SMS Reminders:</strong> – '.__('We are very happy to introduce the SMS Reminders module. Reduce no-shows by sending text message reminders a few hours before each appointment. And get instantly notified when someone makes a new booking.','booking-ultra-pro').'</p>
	
	<p class="submit">
		<a href="http://doc.bookingultrapro.com/how-to-set-up-sms-on-your-account/" class="button-secondary" target="_blank">'.__('Getting Started','booking-ultra-pro').'</a>
	</p>
</div>';		
			
		$this->bup_fresh_install_message($message);	
				
		}
		
		
		
		
	}
	
	
	//display message
	public function bup_fresh_install_message ($message) 
	{
	
		echo $message;
	
	}
	
	
	/******************************************
	Check if user exists by ID
	******************************************/
	function user_exists( $user_id ) 
	{
		$aux = get_userdata( $user_id );
		if($aux==false){
			return false;
		}
		return true;
	}
	
	
	
	
	
	public function create_default_pages_auto () 
	{
		update_option('bup_auto_page_creation',1);
		
	}
	
	
	//display message
	public function uultra_fresh_install_message ($message) 
	{
		if ($errormsg) 
		{
			echo '<div id="message" class="error">';
			
		}else{
			
			echo '<div id="message" class="updated fade">';
		}
	
		echo "<p><strong>$message</strong></p></div>";
	
	}
	

	function bup_remove_admin_bar() 
	{
		if (!current_user_can('manage_options') && !is_admin())
		{
			
			//if ($this->get_option('hide_admin_bar')==1) 
			//{
				
				//show_admin_bar(false);
			//}
		}
	}
	
	function userultra_convert_date($date) 
	{
		
		$custom_date_format = $this->get_option('bup_date_format');
			
		if ($custom_date_format) 
		{
			$date = date($custom_date_format, strtotime($date));
		}
		
		
		return $date;
	}
	
	public function get_currency_symbol() 
	{
		
		$currency_symbol = $this->get_option('currency_symbol');
			
		if ($currency_symbol=='') 
		{
			$currency_symbol = '$';
		}
		
		
		return $currency_symbol;
	}
	
	
	
	public function get_logout_url ()
	{
		
		/*$defaults = array(
		            'redirect_to' => $this->current_page
		    );
		$args = wp_parse_args( $args, $defaults );
		
		extract( $args, EXTR_SKIP );*/
		
		$redirect_to = $this->current_page;
			
		return wp_logout_url($redirect_to);
	}
	
	
	public function custom_logout_page ($atts)
	{
		global $xoouserultra, $wp_rewrite ;
		
		$wp_rewrite = new WP_Rewrite();		
		require_once(ABSPATH . 'wp-includes/link-template.php');		
		
		extract( shortcode_atts( array(	
			
			'redirect_to' => '', 		
							
			
		), $atts ) );
		
		
		
		//check redir		
		$account_page_id = get_option('bup_my_account_page');
		$my_account_url = get_permalink($account_page_id);
		
		if($redirect_to=="")
		{
				$redirect_to =$my_account_url;
		
		}
		$logout_url = wp_logout_url($redirect_to);
		
		//quick patch =
		
		$logout_url = str_replace("amp;","",$logout_url);
	
		wp_redirect($logout_url);
		exit;
		
	}
	
	public function get_redirection_link ($module)
	{
		$url ="";
		
		if($module=="profile")
		{
			//get profile url
			$url = $this->get_option('profile_page_id');			
		
		}
		
		return $url;
		
	}
	
		
		
	
	/*Setup redirection*/
	public function bup_pro_redirect() 
	{
		global $pagenow;

		/* Not admin */
		if (!current_user_can('administrator')) {
			
		    $option_name = '';
			// Check if current page is profile page
			if('profile.php' == $pagenow)
			{
				// If user have selected to redirect backend profile page            
				if($this->get_option('redirect_backend_profile') == '1')
				{
					$option_name = 'profile_page_id';
				}
			}  
            

        // Check if current page is login or not
        if('wp-login.php' == $pagenow && !isset($_REQUEST['action']))
        {
            if($this->get_option('redirect_backend_login') == '1')
            {
                $option_name = 'login_page_id';
            }
        }

        if('wp-login.php' == $pagenow && isset($_REQUEST['action']) && $_REQUEST['action'] == 'register')
        {
            if($this->get_option('redirect_backend_registration') == '1')
            {
                $option_name = 'registration_page_id';
            }
        }
		
        
        if($option_name != '')
        {
            if($this->get_option($option_name) > 0)
            {
                // Generating page url based on stored ID
                $page_url = get_permalink($this->get_option($option_name));
                
                // Redirect if page is not blank
                if($page_url != '')
                {
                    if($option_name == 'login_page_id' && isset($_GET['redirect_to']))
                    {
                        $url_data = parse_url($page_url);
                        $join_code = '/?';
                        if(isset($url_data['query']) && $url_data['query']!= '')
                        {
                            $join_code = '&';
                        }
                        
                        $page_url= $page_url.$join_code.'redirect_to='.$_GET['redirect_to'];
                    }			
					
                    
                    wp_redirect($page_url);
                    exit;
                }
            }    
        }
		}

	}
	
	
		
	
	/*Create Directory page */
	public function create_directory_page($parent) 
	{
		
	}
	
	/*Create login page */
	public function create_login_page() 
	{
		
	}
	
	/*Create register page */
	public function create_register_page() 
	{
		
	}
	
		
		
	public function bup_set_option($option, $newvalue)
	{
		$settings = get_option('bup_options');
		$settings[$option] = $newvalue;
		update_option('bup_options', $settings);
	}
	
	
	public function get_fname_by_userid($user_id) 
	{
		$f_name = get_user_meta($user_id, 'first_name', true);
		$l_name = get_user_meta($user_id, 'last_name', true);
		
		$f_name = str_replace(' ', '_', $f_name);
		$l_name = str_replace(' ', '_', $l_name);
		$name = $f_name . '-' . $l_name;
		return $name;
	}
	
	public function bup_get_user_meta($user_id, $meta) 
	{
		$data = get_user_meta($user_id, $meta, true);
		
		return $data;
	}
	
	public function upp_create_standard_fields ()	
	{
		
		/* Allowed input types */
		$this->allowed_inputs = array(
			'text' => __('Text','booking-ultra-pro'),
			
			'textarea' => __('Textarea','booking-ultra-pro'),
			'select' => __('Select Dropdown','booking-ultra-pro'),
			'radio' => __('Radio','booking-ultra-pro'),
			'checkbox' => __('Checkbox','booking-ultra-pro'),			
		  'datetime' => __('Date Picker','booking-ultra-pro')
		);
		
		/* Core registration fields */
		$set_pass = $this->get_option('set_password');
		if ($set_pass) 
		{
			$this->registration_fields = array( 
			50 => array( 
				'icon' => 'user', 
				'field' => 'text', 
				'type' => 'usermeta', 
				'meta' => 'display_name', 
				'name' => __('Your Name', 'booking-ultra-pro'),
				'required' => 1
			),
			100 => array( 
				'icon' => 'envelope', 
				'field' => 'text', 
				'type' => 'usermeta', 
				'meta' => 'user_email', 
				'name' => __('E-mail','booking-ultra-pro'),
				'required' => 1,
				'can_hide' => 1,
			),
			
			250 => array( 
				'icon' => 'phone', 
				'field' => 'text', 
				'type' => 'usermeta', 
				'meta' => 'telephone', 
				'name' => __('Phone Number','booking-ultra-pro'),
				'required' => 1,
				'can_hide' => 1,
				'help' => __('Input your phone number','booking-ultra-pro')
			)
		);
		} else {
			
		$this->registration_fields = array( 
			50 => array( 
				'icon' => 'user', 
				'field' => 'text', 
				'type' => 'usermeta', 
				'meta' => 'display_name', 
				'name' => __('Your Name','booking-ultra-pro'),
				'required' => 1
			),
			100 => array( 
				'icon' => 'envelope', 
				'field' => 'text', 
				'type' => 'usermeta', 
				'meta' => 'user_email', 
				'name' => __('E-mail','booking-ultra-pro'),
				'required' => 1,
				'can_hide' => 1,
				'help' => __('Information about your booking will be sent to you.','booking-ultra-pro')
			),
			
			250 => array( 
				'icon' => 'phone', 
				'field' => 'text', 
				'type' => 'usermeta', 
				'meta' => 'telephone', 
				'name' => __('Phone Number','booking-ultra-pro'),
				'required' => 1,
				'can_hide' => 1,
				'help' => __('Input your Phone Number','booking-ultra-pro')
			)
		);
		}
		
		/* Core login fields */
		$this->login_fields = array( 
			50 => array( 
				'icon' => 'user', 
				'field' => 'text', 
				'type' => 'usermeta', 
				'meta' => 'user_login', 
				'name' => __('Username or Email','booking-ultra-pro'),
				'required' => 1
			),
			100 => array( 
				'icon' => 'lock', 
				'field' => 'password', 
				'type' => 'usermeta', 
				'meta' => 'login_user_pass', 
				'name' => __('Password','booking-ultra-pro'),
				'required' => 1
			)
		);
		
		/* These are the basic profile fields */
		$this->fields = array(
			80 => array( 
			  'position' => '50',
				'type' => 'separator', 
				'name' => __('Appointment Info','booking-ultra-pro'),
				'private' => 0,
				'show_in_register' => 1,
				'deleted' => 0,
				'show_to_user_role' => 0
			),			
			
			170 => array( 
			  'position' => '200',
				'icon' => 'pencil',
				'field' => 'textarea',
				'type' => 'usermeta',
				'meta' => 'special_notes',
				'name' => __('Comments','booking-ultra-pro'),
				'can_hide' => 0,
				'can_edit' => 1,
				'show_in_register' => 1,
				'private' => 0,
				'social' => 0,
				'deleted' => 0,
				'allow_html' => 1,				
				'help_text' => ''
			
			)
		);
		
		
		
		/* Store default profile fields for the first time */
		if (!get_option('bup_profile_fields'))
		{
			update_option('bup_profile_fields', $this->fields);
		}	
		
		
		
		
	}
	
	public function create_default_business_hours() 
	{
		$business_hours = array();
		
		$business_hours[1] = array('from' =>'08:00', 'to' =>'18:00');
		$business_hours[2] = array('from' =>'08:00', 'to' =>'18:00');
		$business_hours[3] = array('from' =>'08:00', 'to' =>'18:00');
		$business_hours[4] = array('from' =>'08:00', 'to' =>'18:00');
		$business_hours[5] = array('from' =>'08:00', 'to' =>'18:00');
		
		if (!get_option('bup_business_hours'))
		{
			update_option('bup_business_hours', $business_hours);
		}	
	
	}
	
	public function xoousers_update_field_value($option, $newvalue) 
	{
		$fields = get_option('bup_profile_fields');
		$fields[$option] = $newvalue;
		update_option('bup_profile_fields', $settings);
	
	}
	
	public function get_template_label($value, $template_id, $parse_tags = false) 
	{
		$template = get_option('bup_template_'.$template_id);
		$ret_val = '';
		
		if(isset($template[$value]) && $template[$value]!='' && $template_id!='') 
		{
			$ret_val = $template[$value];
		
		
		}else{
			
			$ret_val = $this->get_template_default_label($value);			
		
		
		}
		
		return stripslashes($ret_val);
				
	
	}
	
	public function get_template_default_label($label) 
	{
		$def_label = '';
		
		if($label=='step1_label')
		{
			$def_label = __('Service','booking-ultra-pro');
			
		}elseif($label=='step2_label'){
			
			$def_label = __('Time','booking-ultra-pro');
			
		}elseif($label=='step3_label'){
			
			$def_label = __('Details & Payment','booking-ultra-pro'); 
		
		}elseif($label=='step3cart_label'){
			
			$def_label = __('Cart','booking-ultra-pro');
		
		}elseif($label=='step4_label'){
			
			$def_label = __('Thank you','booking-ultra-pro');
		
		}elseif($label=='bup_cus_bg_color'){
			
			$def_label = '#E55237';
		
		}elseif($label=='select_location_label'){
			
			$def_label =  __('Select Location','booking-ultra-pro');
		
		}elseif($label=='select_service_label'){
			
			$def_label =  __('Select Service','booking-ultra-pro');
			
		}elseif($label=='select_service_label_drop'){
			
			$def_label =  __('Select Service','booking-ultra-pro');
		
		}elseif($label=='select_date_label'){
			
			$def_label =  __('On or After','booking-ultra-pro');
		
		}elseif($label=='select_date_to_label'){
			
			$def_label =  __('Check-out date','booking-ultra-pro');
			
		}elseif($label=='select_provider_label'){
			
			$def_label =  __('With','booking-ultra-pro');
		
		}elseif($label=='step1_texts'){
			
			$def_label = __('Please select service, date and provider then click on the Find Appointments button.','booking-ultra-pro');
			
		}elseif($label=='step2_texts'){
			
			$def_label =  __('Below you can find a list of available time slots for <strong>[BUP_SERVICE]</strong> by <strong>[BUP_PROVIDER]</strong>.','booking-ultra-pro');
			
		}elseif($label=='step3_texts'){
			
			$def_label =  __("You're booking <strong>[BUP_SERVICE]</strong> by <strong>[BUP_PROVIDER]</strong> at <strong>[BUP_AT]</strong> on <strong>[BUP_DAY]</strong>",'booking-ultra-pro');
			
		}elseif($label=='step3_cart_texts'){
			
			$def_label =  __("Please fill out the following form to confirm your purchase ",'booking-ultra-pro');			
		
		}elseif($label=='layout_selected'){
			
			$def_label = 1;	
			
		}elseif($label=='cart_header_1_texts'){
			
			$def_label =  __("Service",'booking-ultra-pro');
		
		}elseif($label=='cart_header_2_texts'){
			
			$def_label =  __("Date",'booking-ultra-pro');
		
		}elseif($label=='cart_header_3_texts'){
			
			$def_label =  __("Time",'booking-ultra-pro');
		
		}elseif($label=='cart_header_4_texts'){
			
			$def_label =  __("Staff",'booking-ultra-pro');
		
		}elseif($label=='cart_header_5_texts'){
			
			$def_label =  __("Qty",'booking-ultra-pro');
		
		}elseif($label=='cart_header_6_texts'){
			
			$def_label =  __("Price",'booking-ultra-pro');
		
		}elseif($label=='cart_header_7_texts'){
			
			$def_label =  __("Action",'booking-ultra-pro');
			
		}elseif($label=='btn_check_availability_button_text'){
			
			$def_label =  __("Check Availability",'booking-ultra-pro');			
		
		}
		
				
		return $def_label;
		
	}
	
	
	
	
		
	function get_the_guid( $id = 0 )
	{
		$post = get_post($id);
		return apply_filters('get_the_guid', $post->guid);
	}
	   	
	function load_classes() 
	{	
		
		foreach ($this->classes_array as $key => $class) 
		{
			if (file_exists(bookingup_path."classes/$class.php")) 
			{
				require_once(bookingup_path."classes/$class.php");
						
					
			}
				
		}	
	}
	
	
	
	
	function uultra_my_theme_add_editor_styles( $mce_css ) 
	{
	  if ( !empty( $mce_css ) )
		$mce_css .= ',';
		//$mce_css .=  bup_url.'templates/'.bup_template.'/css/editor-style.css';
		return $mce_css;
	  }
	  
	  
	  /* register admin scripts */
	public function add_styles_scripts()
	{	
		
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_style("wp-jquery-ui-dialog");
		wp_enqueue_script('jquery-ui-datepicker' );
		
		wp_enqueue_script('plupload-all');	
		wp_enqueue_script('jquery-ui-progressbar');	
		
				
		wp_register_script( 'form-validate-lang', bookingup_url.'js/languages/jquery.validationEngine-en.js',array('jquery'));
			
		wp_enqueue_script('form-validate-lang');			
		wp_register_script( 'form-validate', bookingup_url.'js/jquery.validationEngine.js',array('jquery'));
		wp_enqueue_script('form-validate');		
	}
	
	/* register styles */
	public function add_front_end_styles()
	{
		global $wp_locale;
	
		
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_style("wp-jquery-ui-dialog");
		wp_enqueue_script('jquery-ui-datepicker' );				

		/* Font Awesome */
		wp_register_style('bup_font_awesome', bookingup_url.'css/css/font-awesome.min.css');
		wp_enqueue_style('bup_font_awesome');
		
		//----MAIN STYLES		
				
		/* Custom style */		
		wp_register_style('bup_style', bookingup_url.'templates/css/styles.css');
		wp_enqueue_style('bup_style');
		
		// Using imagesLoaded? Do this.
		//wp_enqueue_script('imagesloaded',  bookingup_url.'js/qtip/imagesloaded.pkgd.min.js' , null, false, true);
		//wp_enqueue_script('qtip',  bookingup_url.'js/qtip/jquery.qtip.min.js', array('jquery', 'imagesloaded'), false, true);	
		
		
		/*country detection css*/
		wp_register_style('bup_phone_code_detect_css', bookingup_url.'js/int-phone-code/css/intlTelInput.css');
		wp_enqueue_style('bup_phone_code_detect_css');
		
		/*country detection js*/		
		wp_register_script( 'bup_phone_code_detect_js', bookingup_url.'js/int-phone-code/js/intlTelInput.min.js',array('jquery'),  null);
		wp_enqueue_script('bup_phone_code_detect_js');
		
		
		/*Users JS*/		
		wp_register_script( 'bup-front_js', bookingup_url.'js/bup-front.js',array('jquery'),  null);
		wp_enqueue_script('bup-front_js');
			
		
		/*uploader*/					
		wp_enqueue_script('jquery-ui');			
		wp_enqueue_script('plupload-all');	
		wp_enqueue_script('jquery-ui-progressbar');		
				
		
		/*Validation Engibne JS*/		
			
		wp_register_script( 'form-validate-lang', bookingup_url.'js/languages/jquery.validationEngine-en.js',array('jquery'));
			
		wp_enqueue_script('form-validate-lang');			
		wp_register_script('form-validate', bookingup_url.'js/jquery.validationEngine.js',array('jquery'));
		wp_enqueue_script('form-validate');
		
		$message_wait_submit ='<img src="'.bookingup_url.'admin/images/loaderB16.gif" width="16" height="16" /></span>&nbsp; '.__("Please wait ...","bookingup").'';	
		
		
		$date_picker_format = $this->get_date_picker_format();	
		
		$var_util_countries = bookingup_url.'js/int-phone-code/js/utils.js';
		
		if($this->get_option('country_detection') == '0'){
			
			$country_detection = 'no';			
		}else{
			$country_detection = 'yes';		
		}
		
		
		wp_localize_script( 'bup-front_js', 'bup_pro_front', array(
            'wait_submit'     => $message_wait_submit,
			'country_detection'     => $country_detection,
			'button_legend_step2'     =>  __('Search Again',"bookingup"),
			'button_legend_step3'     => __("<< Back","bookingup"),
			'button_legend_step3_cart'     => __("Book More","bookingup"),
			'message_wait_staff_box'     => __("Please wait ...","bookingup"),
			'bb_date_picker_format'     => $date_picker_format,
			'country_util_url'     => $var_util_countries,
			'message_wait_availability'     => '<p><img src="'.bookingup_url.'admin/images/loaderB16.gif" width="16" height="16" /></span>&nbsp; '.__("Please wait ...","bookingup").'</p>'
            
            
        ) );
		
		
		
		$date_picker_array = array(
		            'closeText' =>  __('Done',"bookingup"),
		            'prevText' =>  __('Prev',"bookingup"),
		            'nextText' => __('Next',"bookingup"),
		            'currentText' => __('Today',"bookingup"),
		            'monthNames' => array(
		                        'Jan' =>  __('January',"bookingup"),
    		                    'Feb' =>  __('February',"bookingup"),
    		                    'Mar' =>  __('March',"bookingup"),
    		                    'Apr' =>  __('April',"bookingup"),
    		                    'May' =>  __('May',"bookingup"),
    		                    'Jun' =>  __('June',"bookingup"),
    		                    'Jul' =>  __('July',"bookingup"),
    		                    'Aug' =>  __('August',"bookingup"),
    		                    'Sep' =>  __('September',"bookingup"),
    		                    'Oct' => __('October' ,"bookingup"),
    		                    'Nov' =>  __('November' ,"bookingup"),
    		                    'Dec' =>  __('December' ,"bookingup")
		                    ),
		            'monthNamesShort' => array(
		                        'Jan' => __('Jan' ,"bookingup") ,
    		                    'Feb' => __('Feb' ,"bookingup"),
    		                    'Mar' => __('Mar' ,"bookingup"),
    		                    'Apr' => __('Apr' ,"bookingup"),
    		                    'May' => __('May' ,"bookingup"),
    		                    'Jun' => __('Jun' ,"bookingup"),
    		                    'Jul' => __('Jul' ,"bookingup"),
    		                    'Aug' => __('Aug' ,"bookingup"),
    		                    'Sep' => __('Sep' ,"bookingup"),
    		                    'Oct' =>__('Oct' ,"bookingup"),
    		                    'Nov' => __('Nov' ,"bookingup"),
    		                    'Dec' => __('Dec' ,"bookingup")
		                    ),
		            'dayNames' => array(
		                        'Sun' => __('Sunday'  ,"bookingup"),
    		                    'Mon' =>  __('Monday'  ,"bookingup"),
    		                    'Tue' => __( 'Tuesday'  ,"bookingup"),
    		                    'Wed' =>  __( 'Wednesday'  ,"bookingup"),
    		                    'Thu' =>  __(  'Thursday'  ,"bookingup"),
    		                    'Fri' =>   __('Friday'  ,"bookingup"),
    		                    'Sat' =>  __('Saturday'  ,"bookingup")
		                    ),
		            'dayNamesShort' => array(
		                        'Sun' => __('Sun'  ,"bookingup") ,
    		                    'Mon' => __('Mon'  ,"bookingup"),
    		                    'Tue' => __('Tue'  ,"bookingup"),
    		                    'Wed' => __('Wed'  ,"bookingup"),
    		                    'Thu' => __('Thu'  ,"bookingup"),
    		                    'Fri' =>__('Fri'  ,"bookingup"),
    		                    'Sat' =>__('Sat'  ,"bookingup")
		                    ),
		            'dayNamesMin' => array(
		                        'Sun' => __('Su'  ,"bookingup"),
    		                    'Mon' => __('Mo'  ,"bookingup"),
    		                    'Tue' => __('Tu'  ,"bookingup"),
    		                    'Wed' => __('We'  ,"bookingup"),
    		                    'Thu' => __('Th'  ,"bookingup"),
    		                    'Fri' => __('Fr'  ,"bookingup"),
    		                    'Sat' => __('Sa'  ,"bookingup")
		                    ),
		            'weekHeader' => 'Wk'
		        );
				
				//localize our js
				$date_picker_array = array(
					'closeText'         => __( 'Done', "bookingup" ),
					'currentText'       => __( 'Today', "bookingup" ),
					'prevText' =>  __('Prev',"bookingup"),
		            'nextText' => __('Next',"bookingup"),				
					'monthNames'        => array_values( $wp_locale->month ),
					'monthNamesShort'   => array_values( $wp_locale->month_abbrev ),
					'monthStatus'       => __( 'Show a different month', "bookingup" ),
					'dayNames'          => array_values( $wp_locale->weekday ),
					'dayNamesShort'     => array_values( $wp_locale->weekday_abbrev ),
					'dayNamesMin'       => array_values( $wp_locale->weekday_initial ),					
					// get the start of week from WP general setting
					'firstDay'          => get_option( 'start_of_week' ),
					// is Right to left language? default is false
					'isRTL'             => $wp_locale->is_rtl(),
				);
				
				
				wp_localize_script('bup-front_js', 'BUPDatePicker', $date_picker_array);
		
		
	}
	
	/* Custom WP Query*/
	public function get_results( $query ) 
	{
		$wp_user_query = new WP_User_Query($query);						
		return $wp_user_query;		
	
	}
	

	
	/* Show registration form on booking steps */
	function get_registration_form( $args=array() )
	{

		global $post;		
		
		// Loading scripts and styles only when required
		 /* Tipsy script */
        if (!wp_script_is('bup_tipsy')) {
            wp_register_script('bup_tipsy', bookingup_url . 'js/jquery.tipsy.js', array('jquery'));
            wp_enqueue_script('bup_tipsy');
        }

        /* Tipsy css */
        if (!wp_style_is('bup_tipsy')) {
            wp_register_style('bup_tipsy', bookingup_url . 'css/tipsy.css');
            wp_enqueue_style('bup_tipsy');
        }	
		
		/* Arguments */
		$defaults = array(       
			'redirect_to' => null,
			'form_header_text' => __('Sign Up','booking-ultra-pro'),
			'bup_date' => '',
			'service_id' => '',
			'form_id' => '',
			'location_id' => '',
			'field_legends' => 'yes',
			'placeholders' => 'yes',
			'staff_id' => '',
			'book_from' => '',	
			'bup_service_cost' => '',					
			'book_to' => '',
			'template_id' => NULL,
			'max_capacity' => 1,
			'max_available' => 1
			
        		    
		);
		$args = wp_parse_args( $args, $defaults );
		$args_2 = $args;
		extract( $args, EXTR_SKIP );
						
		// Default set to blank
		$this->captcha = '';
		
		
		$display = null;
		
		
		
		   $display .= '<div class="bup-user-data-registration-form">					';				
								
													
													
						/*Display errors*/
						if (isset($_POST['bup-register-form'])) 
						{
							$display .= $this->register->get_errors();
						}
						
						$display .= $this->display_registration_form_booking( $redirect_to, $args_2);

				$display .= '';
		
		
		return $display;
		
	}
	
	/* This is the Registration Form on booking */
	function display_registration_form_booking( $redirect_to=null , $args)
	{
		global $bup_register,  $bup_captcha_loader, $bupcomplement;
		$display = null;
		
						
		extract( $args, EXTR_SKIP );
		
		$require_phone = $this->get_option('phone_number_mandatory');
		$require_last_name = $this->get_option('last_name_mandatory');
				
		// Optimized condition and added strict conditions
		if (!isset($bup_register->registered) || $bup_register->registered != 1)
		{
		
		$display .= '<form action="" method="post" id="bup-registration-form" name="bup-registration-form" enctype="multipart/form-data">';
		
		
		$display .= '<input type="hidden" name="bup_date" id="bup_date" value="'.$bup_date.'">';
		$display .= '<input type="hidden" name="bup_service_cost" id="bup_service_cost" value="'.$bup_service_cost.'">';
		$display .= '<input type="hidden" name="service_id" id="service_id" value="'.$service_id.'">';
		$display .= '<input type="hidden" name="staff_id" id="staff_id" value="'.$staff_id.'">';
		$display .= '<input type="hidden" name="book_from" id="book_from" value="'.$book_from.'">';
		$display .= '<input type="hidden" name="book_to" id="book_to" value="'.$book_to.'">';
		$display .= '<input type="hidden" name="bup-custom-form-id" id="bup-custom-form-id" value="'.$form_id.'">';
		$display .= '<input type="hidden" name="bup-filter-id" id="bup-filter-id" value="'.$location_id.'">';
		$display .= '<input type="hidden" name="template_id" id="template_id" value="'.$template_id.'">';
		
			
		
		$display .= '<div class="bup-field ">'.__('Fields with (*) are required','booking-ultra-pro').'</div>';		
		$display .= '<div class="bup-profile-separator">'.__('Account Info','booking-ultra-pro').'</div>';
			
		/* These are the basic registrations fields */
		
		foreach($this->registration_fields as $key=>$field) 
		{
			extract($field);
			
			//check if exclude user from registration.
			
			$include_username =  true;
			
			if($this->get_option('allow_registering_only_with_email')=='yes')
			{
				if($meta=='user_login')
				{
					$include_username =  false;
				
				}
			
			}
			
			
			
			if ( $type == 'usermeta' && $include_username) {
				
				$display .= '<div class="bup-profile-field">';
				
				if(!isset($required))
				    $required = 0;
				
				$required_class = '';				
				$required_text = '';
				
				if($required == 1 && in_array($field, $this->include_for_validation))
				{
					$required_class = ' validate[required]';
					$required_text = '(*)';
				}
				
				//condition for telephone				
				if($meta=='telephone' && $require_phone=='no' )
				{
					$required_class = ' ';
					$required_text = '';
				
				}
				
				$field_legend = '';				
				if($field_legends=='yes')
				{
					//$placeholder = 'placeholder="'.$name.'"';
					
				}
				
				/* Show the label */
				if (isset($this->registration_fields[$key]['name']) && $name ) 
				{
					
					if ( $field_legends!='no') 
					{
						$display .= '<label class="bup-field-type" for="'.$meta.'">';
						
						if (isset($this->registration_fields[$key]['icon']) && $icon)
						 {
							$display .= '<i class="fa fa-'.$icon.'"></i>';
						} else {
							$display .= '<i class="fa fa-none"></i>';
						}
						
						$display .= '<span>'.$name.' '.$required_text.'</span></label>';
					
					}
					
					
					
				} else {
					$display .= '<label class="bup-field-type">&nbsp;</label>';
				}
				
				$placeholder = '';				
				if($placeholders=='yes')
				{
					$placeholder = 'placeholder="'.$name.'"';
					
				}
				
				$display .= '<div class="bup-field-value">';				
				
					
					switch($field) {					
						
						case 'textarea':
							$display .= '<textarea class="'.$required_class.' bup-input bup-input-text-area" name="'.$meta.'" id="reg_'.$meta.'" title="'.$name.'"  '.$placeholder.' data-errormessage-value-missing="'.__(' * This input is required!','booking-ultra-pro').'">'.$this->get_post_value($meta).'</textarea>';
							break;
						
						case 'text':
							$display .= '<input type="text" class="'.$required_class.' bup-input " name="'.$meta.'" id="reg_'.$meta.'" value="'.$this->get_post_value($meta).'" title="'.$name.'" '.$placeholder.' data-errormessage-value-missing="'.__(' * This input is required!','booking-ultra-pro').'"/>';
							
							if (isset($this->registration_fields[$key]['help']) && $help != '') {
								$display .= '<div class="xoouserultra-help">'.$help.'</div>';
							}
							
							break;
							
							case 'datetime':
							    
							    $display .= '<input type="text" class="'.$required_class.' bup-input bup-input-datepicker" name="'.$meta.'" id="reg_'.$meta.'" value="'.$this->get_post_value($meta).'" title="'.$name.'" data-errormessage-value-missing="'.__(' * This input is required!','booking-ultra-pro').'"/>';
							    
							    if (isset($this->registration_fields[$key]['help']) && $help != '') {
							        $display .= '<div class="xoouserultra-help">'.$help.'</div><div class="xoouserultra-clear"></div>';
							    }
							    break;							
					   
							
						case 'password':

							$display .= '<input type="password" class="'.$required_class.' bup-input password" name="'.$meta.'" id="reg_'.$meta.'" value="" autocomplete="off" title="'.$name.'"  '.$placeholder.' data-errormessage-value-missing="'.__(' * This input is required!','booking-ultra-pro').'" />';
							
							if (isset($this->registration_fields[$key]['help']) && $help != '') {
								$display .= '<div class="xoouserultra-help">'.$help.'</div><div class="xoouserultra-clear"></div>';
							}

							break;												
							
							
						case 'password_indicator':
							$display .= '<div class="password-meter"><div class="password-meter-message" id="password-meter-message">&nbsp;</div></div>';
							break;
							
					}								
					
					
					
				$display .= '</div>';
				
				$display .= '</div>';
				
				
				
				//last name			
				if($meta=='display_name')
				{
					if($require_last_name!='no'){
						
						$required_class = ' validate[required]';
						$required_text = '(*)';
						
						$display .= '<div class="bup-profile-field">';
						
						
						if ( $field_legends!='no') 
						{				
							$display .= '<label class="bup-field-type" for="user_last_name">';
							$display .= '<i class="fa fa-user"></i>';	
							$display .= '<span>'.__('Your Last Name', 'booking-ultra-pro').' '.$required_text.'</span></label>';					
						}
						
						$display .= '<div class="bup-field-value">';
					
						$display .= '<input type="text" class="'.$required_class.' bup-input " name="last_name" id="last_name" value="'.$this->get_post_value('last_name').'" title="'.__('Type your last name','booking-ultra-pro').'"  placeholder="'.__('Type your last name','booking-ultra-pro').'" data-errormessage-value-missing="'.__(' * This input is required!','booking-ultra-pro').'"/>';		
						
						$display .= '</div>';
						
					} //end if required
					
									
				
				}
								
				
				//re-type password				
				if($meta=='user_email')
				{
					$required_class = ' validate[required]';
					$required_text = '(*)';
					
					$display .= '<div class="bup-profile-field">';
					
					
					if ( $field_legends!='no') 
					{				
						$display .= '<label class="bup-field-type" for="user_email_2">';
						$display .= '<i class="fa fa-envelope"></i>';	
						$display .= '<span>'.__('Re-type your email', 'booking-ultra-pro').' '.$required_text.'</span></label>';
						
					}
					
					$display .= '<div class="bup-field-value">';
				
					$display .= '<input type="text" class="'.$required_class.' bup-input " name="user_email_2" id="reg_user_email_2" value="'.$this->get_post_value('user_email_2').'" title="'.__('Re-type your email','booking-ultra-pro').'"  placeholder="'.__('Re-type your email','booking-ultra-pro').'" data-errormessage-value-missing="'.__(' * This input is required!','booking-ultra-pro').'"/>';
					
					
					$display .= '</div>';					
				
				}
				
				
			}
			
								
		}
		
		$custom_form = '';

		if(isset($_GET["bup-custom-form-id"]))
		{ 
			$custom_form=$_GET["bup-custom-form-id"];
		}
		
		/* Get end of array */			
		if($form_id!="" || $custom_form !="")
		{
			//do we have a pre-set value in the get?			
			if($custom_form !="")
			{
				$form_id =$custom_form;			
			}
			
			$custom_form = 'bup_profile_fields_'.$form_id;		
			$array = get_option($custom_form);			
			$fields_set_to_update =$custom_form;
			
		}else{
			
			$array = get_option('bup_profile_fields');
			$fields_set_to_update ='bup_profile_fields';
		
		}
		
		if(!is_array($array))
		{
			$array = array();
		
		}		
		

		foreach($array as $key=>$field) 
		{		     
		    $exclude_array = array('user_pass', 'user_pass_confirm', 'user_email');
		    if(isset($field['meta']) && in_array($field['meta'], $exclude_array))
		    {
		        unset($array[$key]);
		    }
		}
		
		$i_array_end = end($array);
		
		if(isset($i_array_end['position']))
		{
		    $array_end = $i_array_end['position'];
		    
			if (isset($array[$array_end]['type']) && $array[$array_end]['type'] == 'seperator') 
			{
				if(isset($array[$array_end]))
				{
					unset($array[$array_end]);
				}
			}
		}
		
		
		/*Display custom profile fields added by the user*/		
		foreach($array as $key => $field) 
		{

			extract($field);
			
			// WP 3.6 Fix
			if(!isset($deleted))
			    $deleted = 0;
			
			if(!isset($private))
			    $private = 0;
			
			if(!isset($required))
			    $required = 0;
			
			$required_class = '';
			$required_text = '';
			if($required == 1 && in_array($field, $this->include_for_validation))
			{				
			    $required_class = 'validate[required] ';
				$required_text = '(*)';				
			}
			
			
			/* This is a Fieldset seperator */
						
			/* separator */
            if ($type == 'separator' && $deleted == 0 && $private == 0 && isset($array[$key]['show_in_register']) && $array[$key]['show_in_register'] == 1) 
			{
                   $display .= '<div class="bup-profile-separator">'.$name.'</div>';
				   
            }
			
					
			//check if display emtpy				
				
			if ($type == 'usermeta' && $deleted == 0 && $private == 0 && isset($array[$key]['show_in_register']) && $array[$key]['show_in_register'] == 1) 
			{
								
				$display .= '<div class="bup-profile-field">';
				
				/* Show the label */
				if (isset($array[$key]['name']) && $name)
				 {
					 
					if ( $field_legends!='no') 
					{
						
						$display .= '<label class="bup-field-type" for="'.$meta.'">';	
						
						if (isset($array[$key]['icon']) && $icon) 
						{
							
								$display .= '<i class="fa fa-' . $icon . '"></i>';
						} else {
								$display .= '<i class="fa fa-icon-none"></i>';
						}

						$tooltipip_class = '';					
						if (isset($array[$key]['tooltip']) && $tooltip)
						{
							$qtip_classes = 'qtip-light ';	
							$qtip_style = '';					
						
							 $tooltipip_class = '<a class="'.$qtip_classes.' uultra-tooltip" title="' . $tooltip . '" '.$qtip_style.'><i class="fa fa-info-circle reg_tooltip"></i></a>';
						} 
						
												
						$display .= '<span>'.$name. ' '.$required_text.' '.$tooltipip_class.'</span></label>';
						
					}
					
					
				} else {
					
					$display .= '<label class="">&nbsp;</label>';
				}
				
				$display .= '<div class="bup-field-value">';
				
				$placeholder = '';				
				if($placeholders=='yes')
				{
					$placeholder = 'placeholder="'.$name.'"';
					
				}
					
					switch($field) {
					
						case 'textarea':
							$display .= '<textarea class="'.$required_class.' bup-input bup-input-text-area" rows="10" name="'.$meta.'" id="'.$meta.'" title="'.$name.'" '.$placeholder.' data-errormessage-value-missing="'.__(' * This input is required!','booking-ultra-pro').'">'.$this->get_post_value($meta).'</textarea>';
							break;
							
						case 'text':
							$display .= '<input type="text" class="'.$required_class.' bup-input"  name="'.$meta.'" id="'.$meta.'" value="'.$this->get_post_value($meta).'"  title="'.$name.'"  '.$placeholder.' data-errormessage-value-missing="'.__(' * This input is required!','booking-ultra-pro').'"/>';
							break;							
							
						case 'datetime':						
						    $display .= '<input type="text" class="'.$required_class.' bup-input bupro-datepicker" name="'.$meta.'" id="'.$meta.'" value="'.$this->get_post_value($meta).'"  title="'.$name.'" />';
						    break;
							
						case 'select':						
							if (isset($array[$key]['predefined_options']) && $array[$key]['predefined_options']!= '' && $array[$key]['predefined_options']!= '0' )
							
							{
								$loop = $this->commmonmethods->get_predifined( $array[$key]['predefined_options'] );
								
							}elseif (isset($array[$key]['choices']) && $array[$key]['choices'] != '') {
								
															
								$loop = $this->uultra_one_line_checkbox_on_window_fix($choices);
								 	
								
							}
							
							if (isset($loop)) 
							{
								$display .= '<select class="'.$required_class.' bup-input" name="'.$meta.'" id="'.$meta.'" title="'.$name.'" data-errormessage-value-missing="'.__(' * This input is required!','booking-ultra-pro').'">';
								
								foreach($loop as $option)
								{
									
								$option = trim(stripslashes($option));
								
								    
								$display .= '<option value="'.$option.'" '.selected( $this->get_post_value($meta), $option, 0 ).'>'.$option.'</option>';
								}
								$display .= '</select>';
							}
							
							break;
							
						case 'radio':						
						
							if($required == 1 && in_array($field, $this->include_for_validation))
							{
								$required_class = "validate[required] radio ";
							}
						
							if (isset($array[$key]['choices']))
							{				
													
								
								 $loop = $this->uultra_one_line_checkbox_on_window_fix($choices);
								
							}
							if (isset($loop) && $loop[0] != '') 
							{
							  $counter =0;
							  
								foreach($loop as $option)
								{
								    if($counter >0)
								        $required_class = '';
								    
								    $option = trim(stripslashes($option));
									$display .= '<input type="radio" class="'.$required_class.'" title="'.$name.'" name="'.$meta.'" id="uultra_multi_radio_'.$meta.'_'.$counter.'" value="'.$option.'" '.checked( $this->get_post_value($meta), $option, 0 );
									$display .= '/> <label for="uultra_multi_radio_'.$meta.'_'.$counter.'"><span></span>'.$option.'</label>';
									
									$counter++;
									
								}
							}
							
							break;
							
						case 'checkbox':
						
						
							if($required == 1 && in_array($field, $this->include_for_validation))
							{
								$required_class = "validate[required] checkbox ";
							}						
						
							if (isset($array[$key]['choices'])) 
							{
																
								 $loop = $this->uultra_one_line_checkbox_on_window_fix($choices);
								
								
							}
							
							if (isset($loop) && $loop[0] != '') 
							{
							  $counter =0;
							  
								foreach($loop as $option)
								{
								   
								   if($counter >0)
								        $required_class = '';
								  
								  $option = trim(stripslashes($option));
								  
								  $display .= '<div class="bupro-checkbox"><input type="checkbox" class="'.$required_class.'" title="'.$name.'" name="'.$meta.'[]" id="bup_multi_box_'.$meta.'_'.$counter.'" value="'.$option.'" ';
									if (is_array($this->get_post_value($meta)) && in_array($option, $this->get_post_value($meta) )) {
									$display .= 'checked="checked"';
									}
									$display .= '/> <label for="bup_multi_box_'.$meta.'_'.$counter.'"> '.$option.'</label> </div>';
									
									
									$counter++;
								}
							}
							
							break;
							
						
													
						case 'password':						
							$display .= '<input type="password" class="bup-input'.$required_class.'" title="'.$name.'" name="'.$meta.'" id="'.$meta.'" value="'.$this->get_post_value($meta).'" />';
							
							if ($meta == 'user_pass') 
							{
								
							$display .= '<div class="bupro-help">'.__('If you would like to change the password type a new one. Otherwise leave this blank.','booking-ultra-pro').'</div>';
							
							} elseif ($meta == 'user_pass_confirm') {
								
							$display .= '<div class="bupro-help">'.__('Type your new password again.','booking-ultra-pro').'</div>';
							
							}
							break;
							
					}
					
					
					if (isset($array[$key]['help_text']) && $help_text != '') 
					{
						$display .= '<div class="bupro-help">'.$help_text.'</div>';
					}									
									
					
				$display .= '</div>';
				$display .= '</div>';
				
			}
		}
		
		
		$show_cart = $this->get_template_label("show_cart",$template_id);

		
		/*If we are using Paid Registration*/		
		if($this->get_option('registration_rules')!=1)
		{
			
			$service = $this->service->get_one_service($service_id);			
						
			//Payment Details
			$currency_symbol =  $this->get_option('paid_membership_symbol');
			
			$service_details = $this->userpanel->get_staff_service_rate( $staff_id, $service_id );
			
			$display .= '<div class="bup-profile-separator">'.__('Payment Details', 'booking-ultra-pro').'</div>';
			
			if($show_cart==1)
			{
				
				$display .= '<div class="bup-profile-field-ppd" id="bup-strip-payment-details">';				
				//display cart totals				
				$display .= $this->service->bup_get_shopping_cart_summary($template_id);			
				$display .= '</div>';
				
				
			}else{
			
				$display .= '<div class="bup-profile-field-ppd" id="bup-strip-payment-details">';
				
				$display .= '<div class="bup-total-qty" >';
					$display .= '<h4>'.__('Persons:', 'booking-ultra-pro').'</h4>';
					
					
					if($service->service_allow_multiple==1) //only if allowed multiple
					{
						
						$display .= '<select name="bup-purchased-qty" id="bup-purchased-qty" style="height:30px">';
						$i_q = 1;
						while ($i_q <= $max_available) 
						{
							$sel='';
							if($i_q==1){$sel='selected="selected"';}
							
							$display .= ' <option value="'.$i_q.'" '.$sel.'>'.$i_q.'</option>';
							$i_q++;
							
						}
						
						$display .= '</select>';
						
					}else{
						
						$display .= '<p id="bup-total-booking-amount">1</p>';
						$display .= '<input type="hidden" name="bup-purchased-qty" id="bup-purchased-qty" value="1">';
					
					}
					
												
				$display .= '</div>';
				
						
				
				$display .= '<div class="bup-total-qty" >';
				$display .= '<h4>'.__('Available:', 'booking-ultra-pro').'</h4>';				
				$display .= '<p >'.$max_available.'</p>';								
				$display .= '</div>';
				
				$display .= '<div class="bup-total-detail" >';
				$display .= '<h4>'.__('Total:', 'booking-ultra-pro').'</h4>';
				$display .= '<p id="bup-total-booking-amount">'.$currency_symbol.$service_details['price'].'</p>';
				$display .= '</div>';		
										
				$display .= '</div>';
			
			}
					
			
			$required_class = ' validate[required]';
			
			//payment methods			
			$display .= '<div class="bup-profile-separator">'.__('Select Payment Method', 'booking-ultra-pro').'</div>';
			 
			 		 
			 
			 /*Bank*/		
			if($this->get_option('gateway_bank_active')=='1')
			{
				//custom label
				
				$custmom_label = $this->get_option('gateway_bank_label');
				if($custmom_label=='')
				{
					$custmom_label = __('I will pay locally','booking-ultra-pro');
				
				}
				
				$display_payment_method = '<input type="radio" class="'.$required_class.' bup_payment_options" title="" name="bup_payment_method" id="bup_payment_method_bank" value="bank" data-method="bank" /> <label for="bup_payment_method_bank"><span></span>'.$custmom_label.'</label>';
													 
				$display .= '<div class="bup-profile-field">';
				$display .= '<label class="bup-field-type" for="bup_payment_method_bank">';			
				$display .= '<span>'.$display_payment_method.' </span></label>';
				$display .= '<div class="bup-field-value">';
				$display .= '</div>';				
				$display .= '</div>';				
				
			
			
			}
			
			
			/*Paypal*/		
			if($this->get_option('gateway_paypal_active')=='1')
			{
				$paypal_logo = bookingup_url.'templates/img/paypal-logo.jpg';
				$display_payment_method = '<input type="radio" class="'.$required_class.' bup_payment_options" title="" name="bup_payment_method" id="bup_payment_method_paypal" value="paypal" data-method="paypal"/> <label for="bup_payment_method_paypal"><span></span>'.__('Pay with PayPal','booking-ultra-pro').'<br><img align="absmiddle"  src="'.$paypal_logo.'" style="top:5px;"></label>';	
				
													 
				$display .= '<div class="bup-profile-field">';
				$display .= '<label class="bup-field-type" for="bup_payment_method_paypal">';			
				$display .= '<span>'.$display_payment_method.' </span></label>';
				$display .= '<div class="bup-field-value">';
				$display .= '</div>';				
				$display .= '</div>';		
			
			}
			
			/*Stripe*/		
			if($this->get_option('gateway_stripe_active')=='1' && isset($bupcomplement))
			{
				$cc_logo = bookingup_url.'templates/img/creditcard-icon.png';
				$display_payment_method = '<input type="radio" class="'.$required_class.' bup_payment_options" title="" name="bup_payment_method" id="bup_payment_method_stripe" value="stripe"  data-method="stripe" checked /> <label for="bup_payment_method_stripe"><span></span>'.__('Pay with Credit Card','booking-ultra-pro').'<br><img align="absmiddle"  src="'.$cc_logo.'" style="top:5px; "></label>';	
				
				$display .= '<input type="hidden"  name="bup_payment_method_stripe_hidden" id="bup_payment_method_stripe_hidden" value="stripe" >';	
											 
				$display .= '<div class="bup-profile-field">';
				$display .= '<label class="bup-field-type" for="bup_payment_method_stripe">';			
				$display .= '<span>'.$display_payment_method.' </span></label>';
				
				$display .= '<div class="bup-field-value">';
				$display .= '</div>';				
				$display .= '</div>'; 
				
				//cc form
				
				$display .= '<div class="bup-profile-field-cc" id="bup-strip-cc-form">';
				
				$display .= '<div class="bup-cc-frm-left" >';
				
				$display .= '<label class="ab-formLabel"><strong class="bup-cc-strong-t"> '.__('Credit Card Number','booking-ultra-pro').'</strong></label>';
				$display .= '<div class="bup-profile-field"><input class="card-number" type="text" id="bup_card_number"  autocomplete="off" data-stripe="number">'.'</div>';
				
				$display .= '</div>'; //left
				
				$display .= '<div class="bup-cc-frm-right" >';				
				$display .= '<label class="bup-formLabel"> <strong class="bup-cc-strong-t">'.__('Expiration Date','booking-ultra-pro').'</strong></label>';
				$display .= '<div class="bup-profile-field"><select id="bup_card_exp_month" class="card-expiry-month" style="width: 60px;float: left; margin-left: 10px;" data-stripe="exp-month">'.$this->commmonmethods->get_select_value(1,12).'</select><select id="bup_card_exp_year" class="card-expiry-year" style="width: 80px;float: left; margin-left: 10px;" data-stripe="exp-year">'.$this->commmonmethods->get_select_value(date('Y'),date('Y')+10).'</select>'.'</div>';
				
				$display .= '</div>'; //right				
								
				$display .= '</div>'; //field
				
				$display .= '<div class="bup-profile-field-cc" id="bup-strip-cc-form-sec">';
				
				$display .= '<div class="bup-cc-frm-left" >';
				
				$display .= '<label class="bup-formLabel"><strong class="bup-cc-strong-t"> '.__('Card Security Code','booking-ultra-pro').'</strong></label>';
				$display .= '<div class="bup-profile-field"><input class="card-cvc" type="text" id="bup_card_number"  autocomplete="off" style="width:60px" data-stripe="cvc">'.'</div>';
				
				$display .= '</div>'; //left
				
				$display .= '</div>'; //field
						
			
			}			
			
			/*Authorize*/		
			if($this->get_option('gateway_authorize_active')=='1' && isset($bupcomplement))
			{
				$cc_logo = bookingup_url.'templates/img/creditcard-icon.png';
				$display_payment_method = '<input type="radio" class="'.$required_class.' bup_payment_options" title="" name="bup_payment_method" id="bup_payment_method_authorize" value="authorize"  data-method="authorize" checked /> <label for="bup_payment_method_authorize"><span></span>'.__('Pay with Credit Card','booking-ultra-pro').'<br><img align="absmiddle"  src="'.$cc_logo.'" style="top:5px; "></label>';	
				
				$display .= '<input type="hidden"  name="bup_payment_method_authorize_hidden" id="bup_payment_method_authorize_hidden" value="authorize" >';	
											 
				$display .= '<div class="bup-profile-field">';
				$display .= '<label class="bup-field-type" for="bup_payment_method_authorize">';			
				$display .= '<span>'.$display_payment_method.' </span></label>';
				
				$display .= '<div class="bup-field-value">';
				$display .= '</div>';				
				$display .= '</div>'; 
				
				//cc form
				
				$display .= '<div class="bup-profile-field-cc" id="bup-authorize-cc-form">';
				
				$display .= '<div class="bup-cc-frm-left" >';
				
				$display .= '<label class="ab-formLabel"><strong class="bup-cc-strong-t"> '.__('Credit Card Number','booking-ultra-pro').'</strong></label>';
				$display .= '<div class="bup-profile-field"><input class="card-number" type="text" id="bup_card_number"  autocomplete="off" data-stripe="number">'.'</div>';
				
				$display .= '</div>'; //left
				
				$display .= '<div class="bup-cc-frm-right" >';				
				$display .= '<label class="bup-formLabel"> <strong class="bup-cc-strong-t">'.__('Expiration Date','booking-ultra-pro').'</strong></label>';
				$display .= '<div class="bup-profile-field"><select id="bup_card_exp_month" class="card-expiry-month" style="width: 60px;float: left; margin-left: 10px;" data-stripe="exp-month">'.$this->commmonmethods->get_select_value(1,12).'</select><select id="bup_card_exp_year" class="card-expiry-year" style="width: 80px;float: left; margin-left: 10px;" data-stripe="exp-year">'.$this->commmonmethods->get_select_value(date('Y'),date('Y')+10).'</select>'.'</div>';
				
				$display .= '</div>'; //right				
								
				$display .= '</div>'; //field
				
				$display .= '<div class="bup-profile-field-cc" id="bup-authorize-cc-form-sec">';
				
				$display .= '<div class="bup-cc-frm-left" >';
				
				$display .= '<label class="bup-formLabel"><strong class="bup-cc-strong-t"> '.__('Card Security Code','booking-ultra-pro').'</strong></label>';
				$display .= '<div class="bup-profile-field"><input class="card-cvc" type="text" id="bup_card_number"  autocomplete="off" style="width:60px" data-stripe="cvc">'.'</div>';
				
				$display .= '</div>'; //left
				
				$display .= '</div>'; //field
			
			}				
		
		}
			
				
		/*If mailchimp*/		
		if($this->get_option('newsletter_active')=='mailchimp' && $this->get_option('mailchimp_api')!="" && isset($bupcomplement))
		{
			
			//new mailchimp field			
			$mailchimp_text = stripslashes($this->get_option('mailchimp_text'));
			$mailchimp_header_text = stripslashes($this->get_option('mailchimp_header_text'));
			
			if($mailchimp_header_text==''){
				
				$mailchimp_header_text = __('Receive Daily Updates ', 'booking-ultra-pro');				
			}			
			
			
			//
			
			$mailchimp_autchecked = $this->get_option('mailchimp_auto_checked');
			
			$mailchimp_auto = '';
			if($mailchimp_autchecked==1){
				
				$mailchimp_auto = 'checked="checked"';				
			}
			
			 $display .= '<div class="bup-profile-separator">'.$mailchimp_header_text.'</div>';
			 
			 $display .= '<div class="bup-profile-field " style="text-align:left">';
			
						
			// $display .= '<label class="bup-field-type" for="'.$meta.'">';			
			//$display .= '<span>&nbsp;</span></label>';
			
			//$display .= '<div class="bup-field-value">';
			 $display .= '<input type="checkbox"  title="'.$mailchimp_header_text.'" name="bup-mailchimp-confirmation"  id="bup-mailchimp-confirmation" value="1"  '.$mailchimp_auto.' > <label for="bup-mailchimp-confirmation"><span></span>'.$mailchimp_text.'</label>' ;
			
			//$display .= '</div>';
			
			// $display .= '</label>';
			
			 $display .= '<div class="bup-field-value "></div>';
									
			 $display .= '</div>';
			
		
		}
		
		/*If aweber*/		
		if($this->get_option('newsletter_active')=='aweber' && $this->get_option('aweber_consumer_key')!="" && isset($bupcomplement))
		{
			
			//new aweber field			
			$aweber_text = stripslashes($this->get_option('aweber_text'));
			$aweber_header_text = stripslashes($this->get_option('aweber_header_text'));
			
			if($aweber_header_text==''){
				
				$aweber_header_text = __('Receive Daily Updates ', 'booking-ultra-pro');				
			}	
			
			if($aweber_text==''){
				
				$aweber_text = __('Yes, I want to receive daily updates. ', 'booking-ultra-pro');				
			}			
			
			
			//
			
			$aweber_autchecked = $this->get_option('aweber_auto_checked');
			
			$aweber_auto = '';
			if($aweber_autchecked==1){
				
				$aweber_auto = 'checked="checked"';				
			}
			
			 $display .= '<div class="bup-profile-separator">'.$aweber_header_text.'</div>';
			 
			 $display .= '<div class="bup-profile-field " style="text-align:left">';			
						
			// $display .= '<label class="bup-field-type" style="width:80%" for="'.$meta.'">';			
			
			//$display .= '<div class="bup-field-value">';
			 $display .= '<input type="checkbox"  title="'.$aweber_header_text.'" name="bup-aweber-confirmation"  id="bup-aweber-confirmation" value="1"  '.$aweber_auto.' > <label for="bup-aweber-confirmation"><span></span>'.$aweber_text.'</label>' ;
			
			
									
			 $display .= '</div>';
			
		
		}		
		
		$captcha_control = $this->get_option("captcha_plugin");
		
		if($captcha_control!='none' && $captcha_control!='')
		{
					
			//$display.=$this->captchamodule->load_captcha($this->captcha);
		
		}
		
		
		
					
				
		$display .= '<p>&nbsp;</p>';
		$display .= '<div class="bup-field ">
						<label class="bup-field-type "><button name="bup-btn-book-app-confirm" id="bup-btn-book-app-confirm" class="bup-button-submit-changes">'.__('Submit','booking-ultra-pro').'	</button><span id="bup-message-submit-booking-conf"></span></label>
						<div class="bup-field-value">
						    <input type="hidden" name="bup-register-form" value="bup-register-form" />
							 <input type="hidden" name="full_number" id="full_number" value="" />
							 <input type="hidden" name="full_number_prefix" id="full_number_prefix" value="" />
							 <input type="hidden" name="full_number_iso" id="full_number_iso" value="" />
														
							
							
						</div>
					</div>';
					
		$display .= '<div class="bup-profile-field-cc" id="bup-stripe-payment-errors"></div>';
					
					
					
					
		if ($redirect_to != '' )
		{
			$display .= '<input type="hidden" name="redirect_to" value="'.$redirect_to.'" />';
		}
		
		$display .= '</form>';
		
		} 
		
		
		return $display;
	}
	
	
	
	
	
	/**
	 * This has been added to avoid the window server issues
	 */
	public function uultra_one_line_checkbox_on_window_fix($choices)
	{		
		
		if($this->uultra_if_windows_server()) //is window
		{
			$loop = array();		
			$loop = explode(",", $choices);
		
		}else{ //not window
		
			$loop = array();		
			$loop = explode(PHP_EOL, $choices);	
			
		}	
		
		
		return $loop;
	
	}
	
	public function uultra_if_windows_server()
	{
		$os = PHP_OS;
		$os = strtolower($os);			
		$pos = strpos($os, "win");	
		
		if ($pos === false) {
			
			//echo "NO, It's not windows";
			return false;
		} else {
			//echo "YES, It's windows";
			return true;
		}			
	
	}
	
		
	
	public function get_price_format($price)
	{
		$new_price='';
		
		$currency_symbol =  $this->get_option('paid_membership_symbol');
		$currency_position =  $this->get_option('currenciy_position');
		
		//without milliar separator
		$price = number_format($price, 2, '.', '');
		
		if($currency_position=='before')
		{
			
			$new_price=$price.$currency_symbol;
			
		}else{
			
			$new_price=$currency_symbol.$price;
			
		}
		
		
		
		return $new_price;		
			
	}
	
		
	
	/**
	 * Public Profile
	 */
	public function show_pulic_profile($atts)
	{
		 return $this->userpanel->show_public_profile($atts);		
			
	}
	
	
	
	public function get_social_buttons_short_code ($atts)
	{
		require_once(xoousers_path."libs/fbapi/src/facebook.php");
		
		$display ="";
		
		extract( shortcode_atts( array(
			'provider' => '',
			
		), $atts ) );
		
		$socials = explode(',', $provider); ;	
		
		
		$FACEBOOK_APPID = $this->get_option('social_media_facebook_app_id');  
			$FACEBOOK_SECRET = $this->get_option('social_media_facebook_secret');
							
			$config = array();
			$config['appId'] = $FACEBOOK_APPID;
			$config['secret'] = $FACEBOOK_SECRET;
			
			$web_url = site_url()."/"; 
			
			$action_text = __('Connect with ','booking-ultra-pro');
			
			
			$atleast_one = false;
			
			
			if(in_array('facebook', $socials)) 
			{
				$atleast_one = true;
				$facebook = new Facebook($config);			
				
				
				
				$params = array(
						  'scope' => 'read_stream, email',						  				  
						  'redirect_uri' => $web_url
						);
						
				$loginUrl = $facebook->getLoginUrl($params);
			
				//Facebook
				$display .='<div class="txt-center FacebookSignIn">
				
				       	               	
						<a href="'.$loginUrl.'" class="btnuultra-facebook" >
							<span class="uultra-icon-facebook"> <img src="'.xoousers_url.'templates/'.xoousers_template.'/img/socialicons/facebook.png" ></span>'.$action_text.' Facebook </a>
					
					</div>';
					
			}
			
			if(in_array('yahoo', $socials)) 
			{
			
				$auth_url_yahoo = $web_url."?uultrasocialsignup=yahoo";			
				
				$atleast_one = true;
			
				//Yahoo
				$display .='<div class="txt-center YahooSignIn">	               	
							<a href="'.$auth_url_yahoo.'" class="btnuultra-yahoo" >
							<span class="uultra-icon-yahoo"><img src="'.xoousers_url.'templates/'.xoousers_template.'/img/socialicons/yahoo.png" ></span>'.$action_text.' Yahoo </a>
					
					</div>';
		     }
			 
			if(in_array('google', $socials)) 
			{
				//google
			
				$auth_url_google = $web_url."?uultrasocialsignup=google";
			
				$atleast_one = true;
			
				//Google
				$display .='<div class="txt-center GoogleSignIn">	               	
						<a href="'.$auth_url_google.'" class="btnuultra-google" >
							<span class="uultra-icon-google"><img src="'.xoousers_url.'templates/'.xoousers_template.'/img/socialicons/googleplus.png" ></span>'.$action_text.' Google </a>
					
					</div>';
			}
			
			if(in_array('twitter', $socials)) 
			{
				//google
			
				$auth_url_google = $web_url."?uultrasocialsignup=twitter";
			
				$atleast_one = true;
			
				//Google
				$display .='<div class="txt-center TwitterSignIn">	               	
						<a href="'.$auth_url_google.'" class="btnuultra-twitter" >
							<span class="uultra-icon-twitter"><img src="'.xoousers_url.'templates/'.xoousers_template.'/img/socialicons/twitter.png" ></span>'.$action_text.' Twitter </a>
					
					</div>';
			}
			
			if(in_array('yammer', $socials)) 
			{
				//google
			
				$auth_url_google = $web_url."?uultrasocialsignup=yammer";
			
				$atleast_one = true;
			
				//Google
				$display .='<div class="txt-center YammerSignIn">	               	
						<a href="'.$auth_url_google.'" class="btnuultra-yammer" >
							<span class="uultra-icon-yammer"><img src="'.xoousers_url.'templates/'.xoousers_template.'/img/socialicons/yammer.png" ></span>'.$action_text.' Yammer </a>
					
					</div>';
			}
			
			if(in_array('linkedin', $socials)) 
			{
				$atleast_one = true;
				
							
				$requestlink = $web_url."?uultrasocialsignup=linkedin";
				
				
				//LinkedIn
				$display .='<div class="txt-center LinkedSignIn">	               	
							<a href="'.$requestlink.'" class="btnuultra-linkedin" >
								<span class="uultra-icon-linkedin"><img src="'.xoousers_url.'templates/'.xoousers_template.'/img/socialicons/linkedin.png" ></span>'.$action_text.' LinkedIn </a>
					
					</div>';
			}	
			
				
		
		
	return $display;
		
	}
	
	

	
	public function get_social_buttons ($action_text, $atts)
	{
		
		
		$display ="";
		
		extract( shortcode_atts( array(
			'social_conect' => '',
			'display_style' => 'default', //default, minified
			'rounded_border' => 'no', //no, yes
			
		), $atts ) );
		
				
		
			
			
			
	return $display;
		
	}
	

	
	 /*---->> Set Account Status  ****/  
 	 public function user_account_status($user_id) 
  	{
	 // global $xoouserultra;
	  
	  //check if login automatically
	  $activation_type= $this->get_option('registration_rules');
	  
	  if($activation_type==1)
	  {
		  //automatic activation
		  update_user_meta ($user_id, 'bup_account_status', 'active');							
	  
	  }elseif($activation_type==2){
		  
		  //email activation link
		  update_user_meta ($user_id, 'bup_account_status', 'pending');	
	  
	  }elseif($activation_type==3){
		  
		  //manually approved
		  update_user_meta ($user_id, 'bup_account_status', 'pending_admin');
	  
	  
	  }
	
  }
  
 
	
	
		
	public function get_current_url()
	{
		$result = 'http';
		$script_name = "";
		if(isset($_SERVER['REQUEST_URI'])) 
		{
			$script_name = $_SERVER['REQUEST_URI'];
		} 
		else 
		{
			$script_name = $_SERVER['PHP_SELF'];
			if($_SERVER['QUERY_STRING']>' ') 
			{
				$script_name .=  '?'.$_SERVER['QUERY_STRING'];
			}
		}
		
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') 
		{
			$result .=  's';
		}
		$result .=  '://';
		
		if($_SERVER['SERVER_PORT']!='80')  
		{
			$result .= $_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$script_name;
		} 
		else 
		{
			$result .=  $_SERVER['HTTP_HOST'].$script_name;
		}
	
		return $result;
	}
	
	/* get setting */
	function get_option($option) 
	{
		$settings = get_option('bup_options');
		if (isset($settings[$option])) 
		{
			if(is_array($settings[$option]))
			{
				return $settings[$option];
			
			}else{
				
				return stripslashes($settings[$option]);
			}
			
		}else{
			
		    return '';
		}
		    
	}
	
	/* Get post value */
	function uultra_admin_post_value($key, $value, $post){
		if (isset($_POST[$key])){
			if ($_POST[$key] == $value)
				echo 'selected="selected"';
		}
	}
	
	/*Post value*/
	function get_post_value($meta) {
				
		if (isset($_POST['bup-register-form'])) {
			if (isset($_POST[$meta]) ) {
				return $_POST[$meta];
			}
		} else {
			if (strstr($meta, 'country')) {
			return 'United States';
			}
		}
	}
	
		

}
?>