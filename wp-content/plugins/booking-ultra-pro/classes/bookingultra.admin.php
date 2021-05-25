<?php
class BookingUltraAdmin extends BookingUltraCommon 
{

	var $options;
	var $wp_all_pages = false;
	var $bup_default_options;
	var $valid_c;
	
	var $notifications_email = array();

	function __construct() {
	
		/* Plugin slug and version */
		$this->slug = 'bookingultra';
		
		$this->set_default_email_messages();				
		$this->update_default_option_ini();		
		$this->set_font_awesome();
		
		
		add_action('admin_menu', array(&$this, 'add_menu'), 9);
	
		add_action('admin_enqueue_scripts', array(&$this, 'add_styles'), 9);
		add_action('admin_head', array(&$this, 'admin_head'), 9 );
		add_action('admin_init', array(&$this, 'admin_init'), 9);
		add_action('admin_init', array(&$this, 'do_valid_checks'), 9);
				
		add_action( 'wp_ajax_save_fields_settings', array( &$this, 'save_fields_settings' ));
				
		add_action( 'wp_ajax_add_new_custom_profile_field', array( &$this, 'add_new_custom_profile_field' ));
		add_action( 'wp_ajax_delete_profile_field', array( &$this, 'delete_profile_field' ));
		add_action( 'wp_ajax_sort_fileds_list', array( &$this, 'sort_fileds_list' ));
		
		//user to get all fields
		add_action( 'wp_ajax_bup_reload_custom_fields_set', array( &$this, 'bup_reload_custom_fields_set' ));
		
		//used to edit a field
		add_action( 'wp_ajax_bup_reload_field_to_edit', array( &$this, 'bup_reload_field_to_edit' ));			
		
		add_action( 'wp_ajax_custom_fields_reset', array( &$this, 'custom_fields_reset' ));			
		add_action( 'wp_ajax_create_uploader_folder', array( &$this, 'create_uploader_folder' ));
		
		add_action( 'wp_ajax_reset_email_template', array( &$this, 'reset_email_template' ));
		
		add_action( 'wp_ajax_bup_vv_c_de_a', array( &$this, 'bup_vv_c_de_a' ));
		
		
		
	}
	
	function admin_init() 
	{
		
		$this->tabs = array(
		    'main' => __('Dashboard','booking-ultra-pro'),
			'services' => __('Services','booking-ultra-pro'),
			'users' => __('Staff','booking-ultra-pro'),
			'appointments' => __('Appointments','booking-ultra-pro'),
			'orders' => __('Payments','booking-ultra-pro'),
			'fields' => __('Fields','booking-ultra-pro'),
			'settings' => __('Settings','booking-ultra-pro'),				
			'mail' => __('Notifications','booking-ultra-pro'),		
			
			'gateway' => __('Gateways','booking-ultra-pro'),
			'help' => __('Help','booking-ultra-pro'),
			'pro' => __('PREMIUM FEATURES!','booking-ultra-pro'),
		);
		
		$this->default_tab = 'main';	
		
		
		$this->default_tab_membership = 'main';
		
		
	}
	
	public function update_default_option_ini () 
	{
		$this->options = get_option('bup_options');		
		$this->bup_set_default_options();
		
		if (!get_option('bup_options')) 
		{
			
			update_option('bup_options', $this->bup_default_options );
		}
		
		if (!get_option('bup_pro_active')) 
		{
			
			update_option('bup_pro_active', true);
		}	
		
		
	}
	
		
	public function custom_fields_reset () 
	{
		
		if($_POST["p_confirm"]=="yes")
		{			
			
			//multi fields		
			$custom_form = $_POST["bup_custom_form"];
			
			if($custom_form!="")
			{
				$custom_form = 'bup_profile_fields_'.$custom_form;		
				$fields_set_to_update =$custom_form;
				
			}else{
				
				$fields_set_to_update ='bup_profile_fields';
			
			}
			
			update_option($fields_set_to_update, NULL);
		
		
		
		}
		
		
	}
	

	
		
	
	
	function get_pending_verify_requests_count()
	{
		$count = 0;
		
		
		if ($count > 0){
			return '<span class="upadmin-bubble-new">'.$count.'</span>';
		}
	}
	
	function get_pending_verify_requests_count_only(){
		$count = 0;
		
		
		if ($count > 0){
			return $count;
		}
	}
	
	
	
	
	function admin_head(){
		$screen = get_current_screen();
		$slug = $this->slug;
		
	}

	function add_styles()
	{
		
		 global $wp_locale, $bookingultrapro, $pagenow;
		 
		if('customize.php' != $pagenow )
        {
			 
			wp_register_style('bup_admin', bookingup_url.'admin/css/admin.css');
			wp_enqueue_style('bup_admin');
			
			wp_register_style('bup_datepicker', bookingup_url.'admin/css/datepicker.css');
			wp_enqueue_style('bup_datepicker');
			
			wp_register_style('bup_admin_calendar', bookingup_url.'admin/css/bup-calendar.css');
			wp_enqueue_style('bup_admin_calendar');	
			
			
				/*google graph*/		
			wp_register_script('bupro_jsgooglapli', 'https://www.gstatic.com/charts/loader.js');
			wp_enqueue_script('bupro_jsgooglapli');						
				
				
			//color picker		
			 wp_enqueue_style( 'wp-color-picker' );			 	 
			 wp_register_script( 'bup_color_picker', bookingup_url.'admin/scripts/color-picker-js.js', array( 
					'wp-color-picker'
				) );
			wp_enqueue_script( 'bup_color_picker' );
			
			
			wp_register_script( 'bup_admin', bookingup_url.'admin/scripts/admin.js', array( 
				'jquery','jquery-ui-core','jquery-ui-draggable','jquery-ui-droppable',	'jquery-ui-sortable', 'jquery-ui-tabs', 'jquery-ui-autocomplete', 'jquery-ui-widget', 'jquery-ui-position'	), null );
			wp_enqueue_script( 'bup_admin' );	
    
            
            wp_register_style( 'bup_event_cal_css', bookingup_url.'admin/scripts/event-calendar.min.css');
			wp_enqueue_style('bup_event_cal_css');
            
			
			wp_register_script( 'bup_angular_calendar', bookingup_url.'admin/scripts/angular.min.js', array( 
				'jquery') );
			wp_enqueue_script( 'bup_angular_calendar' );
			
			wp_register_script( 'bup_angular_calendar_ui', bookingup_url.'admin/scripts/angular-ui-date-0.0.8.js', array( 
				'wp-color-picker') );
			wp_enqueue_script( 'bup_angular_calendar_ui' );	
            
            wp_register_script( 'bup_moment_calendar', bookingup_url.'admin/scripts/moment.min.js', array( 
				'wp-color-picker') );
			wp_enqueue_script( 'bup_moment_calendar' );
            
            
            wp_register_script( 'bup_date_range_picker', bookingup_url.'admin/scripts/daterangepicker.js' );
			wp_enqueue_script( 'bup_date_range_picker' );
            
           //  wp_register_script( 'bup_angular_date_range_picker', bookingup_url.'admin/scripts/angular-daterangepicker.js' );
			//wp_enqueue_script( 'bup_angular_date_range_picker' );
            
            
            
			

			
			wp_register_script( 'bup_event_calendar', bookingup_url.'admin/scripts/event-calendar.min.js', array( 
				'wp-color-picker') );
            
			wp_enqueue_script( 'bup_event_calendar' );
			
			//wp_register_script( 'bup_multi_staff_calendar', bookingup_url.'admin/scripts/fc-multistaff-view.js', array( 
			//	'wp-color-picker') );           
            
			//wp_enqueue_script( 'bup_multi_staff_calendar' );
            
            wp_register_script( 'bup_calendar_commons', bookingup_url.'admin/scripts/booking-ultra-calendar-common.js', array( 
				'wp-color-picker') );
			wp_enqueue_script( 'bup_calendar_commons' );
            
            
           wp_register_script( 'bup_calendar_js', bookingup_url.'admin/scripts/booking-ultra-calendar.js', array( 
				'wp-color-picker') );
			wp_enqueue_script( 'bup_calendar_js' );            
            
			
			wp_register_script( 'bup_calendar_funct_js', bookingup_url.'admin/scripts/bup-calendar.js', array( 
				'wp-color-picker') );
			wp_enqueue_script( 'bup_calendar_funct_js' );
            
            
			/* Font Awesome */
			wp_register_style( 'bup_font_awesome', bookingup_url.'css/css/font-awesome.min.css');
			wp_enqueue_style('bup_font_awesome');
			
			
			// Add the styles first, in the <head> (last parameter false, true = bottom of page!)
			wp_enqueue_style('qtip', bookingup_url.'js/qtip/jquery.qtip.min.css' , null, false, false);
			
			// Using imagesLoaded? Do this.
			wp_enqueue_script('imagesloaded',  bookingup_url.'js/qtip/imagesloaded.pkgd.min.js' , null, false, true);
			wp_enqueue_script('qtip',  bookingup_url.'js/qtip/jquery.qtip.min.js', array('jquery', 'imagesloaded'), false, true);		
		
		}
		
		
		$slot_length_minutes = $bookingultrapro->get_option( 'bup_calendar_time_slot_length' );
		
		if($slot_length_minutes==''){$slot_length_minutes ='15';}
		
		//$slot_length_minutes =10;
        
        $csrf_token = $this->get_csrf_token();		
        $slot = new DateInterval( 'PT' . $slot_length_minutes . 'M' );
		
		  wp_localize_script( 'bup_calendar_js', 'BuproL10n', array(
            'slotDuration'     => $slot->format( '%H:%I:%S' ),
            'csrf_token'     => $csrf_token,
            'datePicker'      => $this->datePickerOptions(),
            'dateRange'       => $this->dateRangeOptions(),
            'locale'          => $this->getShortLocale(),
            'shortMonths'      => array_values( $wp_locale->month_abbrev ),
            'longMonths'       => array_values( $wp_locale->month ),
            'shortDays'        => array_values( $wp_locale->weekday_abbrev ),
            'longDays'         => array_values( $wp_locale->weekday ),
            'AM'               => $wp_locale->meridiem[ 'AM' ],
            'PM'               => $wp_locale->meridiem[ 'PM' ],
			'mjsDateFormat'    => $this->convertFormat('date', 'fc'),
            'mjsTimeFormat'    => $this->convertFormat('time' , 'fc'),            
            'today'            => __( 'Today', 'booking-ultra-pro' ),
            'week'             => __( 'Week',  'booking-ultra-pro' ),
            'day'              => __( 'Day',   'booking-ultra-pro' ),
            'month'            => __( 'Month', 'booking-ultra-pro' ),
            'list'            => __( 'List', 'booking-ultra-pro' ),
            'allDay'           => __( 'All Day', 'booking-ultra-pro' ),
            'noStaffSelected'  => __( 'No staff selected', 'booking-ultra-pro' ),
            'newAppointment'   => __( 'New appointment',   'booking-ultra-pro' ),
            'editAppointment'  => __( 'Edit appointment',  'booking-ultra-pro' ),
            'are_you_sure'     => __( 'Are you sure?',     'booking-ultra-pro' ),
            'startOfWeek'      => (int) get_option( 'start_of_week' ),
			'msg_quick_list_pending_appointments'  => __( 'Pending Appointments', 'booking-ultra-pro' ),
			'msg_quick_list_cancelled_appointments'  => __( 'Cancelled Appointments', 'booking-ultra-pro' ),
			'msg_quick_list_noshow_appointments'  => __( 'No-show Appointments', 'booking-ultra-pro' ),
			'msg_quick_list_unpaid_appointments'  => __( 'Unpaid Appointments', 'booking-ultra-pro' ),
            
        ) );
		
		$date_picker_format = $bookingultrapro->get_date_picker_format();
		
		 wp_localize_script( 'bup_admin', 'bup_admin_v98', array(
            'msg_cate_delete'  => __( 'Are you totally sure that you wan to delete this category?', 'bookingup' ),
			'msg_service_edit'  => __( 'Edit Service', 'booking-ultra-pro' ),
			'msg_service_add'  => __( 'Add Service', 'booking-ultra-pro' ),
			'msg_category_edit'  => __( 'Edit Category', 'booking-ultra-pro' ),
			'msg_category_add'  => __( 'Add Category', 'booking-ultra-pro' ),
			'msg_service_input_title'  => __( 'Please input a title', 'booking-ultra-pro' ),
			'msg_service_input_price'  => __( 'Please input a price', 'booking-ultra-pro' ),
			'msg_service_delete'  => __( 'Are you totally sure that you wan to delete this service?', 'bookingup' ),
			'msg_user_delete'  => __( 'Are you totally sure that you wan to delete this user?', 'booking-ultra-pro' ),
			'message_wait_staff_box'     => __("Please wait ...","booking-ultra-pro"),
			'msg_wait'  => __( '<img src="'.bookingup_url.'templates/img/loaderB16.gif" width="16" height="16" /> &nbsp; Please wait ... ', 'booking-ultra-pro' ) ,
			'bb_date_picker_format'     => $date_picker_format
           
            
        ) );
		
		
		//localize our js
		$date_picker_array = array(
					'closeText'         => __( 'Done', "booking-ultra-pro" ),
					'currentText'       => __( 'Today', "booking-ultra-pro" ),
					'prevText' =>  __('Prev',"bookingup"),
		            'nextText' => __('Next',"bookingup"),				
					'monthNames'        => array_values( $wp_locale->month ),
					'monthNamesShort'   => array_values( $wp_locale->month_abbrev ),
					'monthStatus'       => __( 'Show a different month', "booking-ultra-pro" ),
					'dayNames'          => array_values( $wp_locale->weekday ),
					'dayNamesShort'     => array_values( $wp_locale->weekday_abbrev ),
					'dayNamesMin'       => array_values( $wp_locale->weekday_initial ),					
					// get the start of week from WP general setting
					'firstDay'          => get_option( 'start_of_week' ),
					// is Right to left language? default is false
					'isRTL'             => $wp_locale->is_rtl(),
				);
				
				
				wp_localize_script('bup_admin', 'BUPDatePicker', $date_picker_array);
				
		
	}
    
    public  function getShortLocale()
    {
        $locale = $this->getLocale();
        // Cut tail for WP locales like Nederlands (Formeel) nl_NL_formal, Deutsch (Schweiz, Du) de_CH_informal and etc
        if ( $second = strpos( $locale, '_', min( 3, strlen( $locale ) ) ) ) {
            $locale = substr( $locale, 0, $second );
        }

        return $locale;
    }
    
    public  function getLocale()
    {
        $locale = get_locale();
        if ( function_exists( 'get_user_locale' ) ) {
            $locale = get_user_locale();
        }

        return $locale;
    }

    
    /**
     * @param array $array
     * @return array
     */
    public  function dateRangeOptions( $array = array() )
    {
        return array_merge(
            array(
                'format'           => $this->convertFormat( 'date','fc' ),
                'applyLabel'       => __( 'Apply', 'booking-ultra-pro' ),
                'cancelLabel'      => __( 'Cancel', 'booking-ultra-pro' ),
                'fromLabel'        => __( 'From', 'booking-ultra-pro' ),
                'toLabel'          => __( 'To', 'booking-ultra-pro' ),
                'customRangeLabel' => __( 'Custom range', 'booking-ultra-pro' ),
                'tomorrow'         => __( 'Tomorrow', 'booking-ultra-pro' ),
                'today'            => __( 'Today', 'booking-ultra-pro' ),
                'yesterday'        => __( 'Yesterday', 'booking-ultra-pro' ),
                'last_7'           => __( 'Last 7 days', 'booking-ultra-pro' ),
                'last_30'          => __( 'Last 30 days', 'booking-ultra-pro' ),
                'thisMonth'        => __( 'This month', 'booking-ultra-pro' ),
                'nextMonth'        => __( 'Next month', 'booking-ultra-pro' ),
                'firstDay'         => (int) get_option( 'start_of_week' ),
            ),
            $array
        );
    }

    /**
     * @param array $array
     * @return array
     */
    public  function datePickerOptions( $array = array() )
    {
        /** @var \WP_Locale $wp_locale */
        global $wp_locale;

        if ( is_rtl() ) {
            $array['direction'] = 'rtl';
        }

        return array_merge(
            array(
                'format'          => $this->convertFormat( 'date', 'fc' ),
                'monthNames'      => array_values( $wp_locale->month ),
                'daysOfWeek'      => array_values( $wp_locale->weekday_abbrev ),
                'firstDay'        => (int) get_option( 'start_of_week' ),
                'monthNamesShort' => array_values( $wp_locale->month_abbrev ),
                'dayNames'        => array_values( $wp_locale->weekday ),
                'dayNamesShort'   => array_values( $wp_locale->weekday_abbrev ),
                'meridiem'        => $wp_locale->meridiem
            ),
            $array
        );
    }
    
    public  function get_csrf_token( )
    {
        
        session_start();
        if (empty($_SESSION['token'])) {
            if (function_exists('mcrypt_create_iv')) {
                $_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
            } else {
                $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
            }
        }
        $token = $_SESSION['token'];
        
        return  $token;
        
    }
	
	
	
	public  function convertFormat( $source_format, $to )
    {
		global $bookingultrapro ;
		
        switch ( $source_format ) 
		{
            case 'date':
                $php_format = get_option( 'date_format', 'Y-m-d' );
                break;
            case 'time':
                $php_format = get_option( 'time_format', 'H:i' );
                break;
            default:
                $php_format = $source_format;
        }
		
		 switch ( $to ) {
            case 'fc' :
			
                $replacements = array(
                    'd' => 'DD',   '\d' => '[d]',
                    'D' => 'ddd',  '\D' => '[D]',
                    'j' => 'D',    '\j' => 'j',
                    'l' => 'dddd', '\l' => 'l',
                    'N' => 'E',    '\N' => 'N',
                    'S' => 'o',    '\S' => '[S]',
                    'w' => 'e',    '\w' => '[w]',
                    'z' => 'DDD',  '\z' => '[z]',
                    'W' => 'W',    '\W' => '[W]',
                    'F' => 'MMMM', '\F' => 'F',
                    'm' => 'MM',   '\m' => '[m]',
                    'M' => 'MMM',  '\M' => '[M]',
                    'n' => 'M',    '\n' => 'n',
                    't' => '',     '\t' => 't',
                    'L' => '',     '\L' => 'L',
                    'o' => 'YYYY', '\o' => 'o',
                    'Y' => 'YYYY', '\Y' => 'Y',
                    'y' => 'YY',   '\y' => 'y',
                    'a' => 'a',    '\a' => '[a]',
                    'A' => 'A',    '\A' => '[A]',
                    'B' => '',     '\B' => 'B',
                    'g' => 'h',    '\g' => 'g',
                    'G' => 'H',    '\G' => 'G',
                    'h' => 'hh',   '\h' => '[h]',
                    'H' => 'HH',   '\H' => '[H]',
                    'i' => 'mm',   '\i' => 'i',
                    's' => 'ss',   '\s' => '[s]',
                    'u' => 'SSS',  '\u' => 'u',
                    'e' => 'zz',   '\e' => '[e]',
                    'I' => '',     '\I' => 'I',
                    'O' => '',     '\O' => 'O',
                    'P' => '',     '\P' => 'P',
                    'T' => '',     '\T' => 'T',
                    'Z' => '',     '\Z' => '[Z]',
                    'c' => '',     '\c' => 'c',
                    'r' => '',     '\r' => 'r',
                    'U' => 'X',    '\U' => 'U',
                    '\\' => '',
                );
                return strtr( $php_format, $replacements );
			}
	}
	
	function add_menu() 
	{
		global $bookingultrapro, $bupcomplement ;
		
		$pending_count = $bookingultrapro->appointment->get_appointments_total_by_status(0);
		
		
	
		$pending_title = esc_attr( sprintf(__( '%d  pending bookings','booking-ultra-pro'), $pending_count ) );
		
		if ($pending_count > 0)
		{
			$menu_label = sprintf( __( 'Booking Ultra %s','bookingup' ), "<span class='update-plugins count-$pending_count' title='$pending_title'><span class='update-count'>" . number_format_i18n($pending_count) . "</span></span>" );
			
		} else {
			
			$menu_label = __('Booking Ultra','booking-ultra-pro');
		}
		
		add_menu_page( __('Booking Ultra Pro','booking-ultra-pro'), $menu_label, 'manage_options', $this->slug, array(&$this, 'admin_page'), bookingup_url .'admin/images/small_logo_16x16.png', '159.140');
		
		//        
        add_submenu_page( $this->slug, __('Services','booking-ultra-pro'), __('Services','booking-ultra-pro'), 'manage_options', 'bookingultra&tab=services', array(&$this, 'admin_page') );
        
        add_submenu_page( $this->slug, __('Staff','booking-ultra-pro'), __('Staff','booking-ultra-pro'), 'manage_options', 'bookingultra&tab=users', array(&$this, 'admin_page') );
        
        add_submenu_page( $this->slug, __('Appointments','booking-ultra-pro'), __('Appointments','booking-ultra-pro'), 'manage_options', 'bookingultra&tab=appointments', array(&$this, 'admin_page') );
        
        add_submenu_page( $this->slug, __('Payments','booking-ultra-pro'), __('Payments','booking-ultra-pro'), 'manage_options', 'bookingultra&tab=orders', array(&$this, 'admin_page') );
    
        add_submenu_page( $this->slug, __('Custom Fields','booking-ultra-pro'), __('Custom Fields','booking-ultra-pro'), 'manage_options', 'bookingultra&tab=fields', array(&$this, 'admin_page') );
        
        add_submenu_page( $this->slug, __('Settings','booking-ultra-pro'), __('Settings','booking-ultra-pro'), 'manage_options', 'bookingultra&tab=settings', array(&$this, 'admin_page') );
        
        add_submenu_page( $this->slug, __('Notifications','booking-ultra-pro'), __('Notifications','booking-ultra-pro'), 'manage_options', 'bookingultra&tab=mail', array(&$this, 'admin_page') );
        
        add_submenu_page( $this->slug, __('Payment Gateways','booking-ultra-pro'), __('Payment Gateways','booking-ultra-pro'), 'manage_options', 'bookingultra&tab=gateway', array(&$this, 'admin_page') );
        
         add_submenu_page( $this->slug, __('Documentation','booking-ultra-pro'), __('Documentation','booking-ultra-pro'), 'manage_options', 'bookingultra&tab=help', array(&$this, 'admin_page') );
        
        
		
		if(!isset($bupcomplement))
		{
			add_submenu_page( $this->slug, __('More Functionality!','booking-ultra-pro'), __('More Functionality!','booking-ultra-pro'), 'manage_options', 'bookingultra&tab=pro', array(&$this, 'admin_page') );
		
		}
		
		
		add_submenu_page( $this->slug, __('Licensing','booking-ultra-pro'), __('Licensing','booking-ultra-pro'), 'manage_options', 'bookingultra&tab=licence', array(&$this, 'admin_page') );
		
		
		//if(!isset($bupcomplement))
		//{
		
			//add_submenu_page( $this->slug, __('Look & Feel','booking-ultra-pro'), __('Look & Feel','booking-ultra-pro'), 'manage_options', 'bookingultra&tab=appea', array(&$this, 'admin_page') );
		
		//}
		
		
		
		do_action('bup_admin_menu_hook');
		
			
	}
	
	

	function admin_tabs( $current = null ) 
	{
		 global $bupultimate, $bupcomplement;
			$tabs = $this->tabs;
			$links = array();
			if ( isset ( $_GET['tab'] ) ) {
				$current = $_GET['tab'];
			} else {
				$current = $this->default_tab;
			}
			foreach( $tabs as $tab => $name ) :
			
				$custom_badge = "";
				
				if($tab=="pro"){
					
					$custom_badge = 'bup-pro-tab-bubble ';
					
				}
				
				if(isset($bupcomplement) && $tab=="pro"){continue;}
				
				if ( $tab == $current ) :
					$links[] = "<a class='nav-tab nav-tab-active ".$custom_badge."' href='?page=".$this->slug."&tab=$tab'>$name</a>";
				else :
					$links[] = "<a class='nav-tab ".$custom_badge."' href='?page=".$this->slug."&tab=$tab'>$name</a>";
				endif;
				
			endforeach;
			foreach ( $links as $link )
				echo $link;
	}

	
	
	function do_action(){
		global $bup;
				
		
	}
	
    
		
	
	/* set a global option */
	function bup_set_option($option, $newvalue)
	{
		$settings = get_option('bup_options');		
		$settings[$option] = $newvalue;
		update_option('bup_options', $settings);
	}
	
	/* default options */
	function bup_set_default_options()
	{
	
		$this->bup_default_options = array(									
						
						'messaging_send_from_name' => __('Booking Ultra Plugin','booking-ultra-pro'),
						'bup_time_slot_length' => 15,
						'bup_calendar_time_slot_length' => 15,
						'bup_calendar_days_to_display' => 7,
						'gateway_free_default_status' => 0,
						'gateway_bank_default_status' => 0,
						'google_map_profile_active' => 1,
						'notifications_sms_reminder_at' => 18,
						
						
						'bup_noti_admin' => 'yes',
						'bup_noti_staff' => 'yes',
						'bup_noti_client' => 'yes',
						
						'google_calendar_template' => 'service_name',
						
						'currency_symbol' => '$',						
						'email_new_booking_admin' => $this->get_email_template('email_new_booking_admin'),
						'email_new_booking_subject_admin' => __('New Appointment Request has been received','booking-ultra-pro'),
						
						'email_new_booking_staff' => $this->get_email_template('email_new_booking_staff'),
						'email_new_booking_subject_staff' => __('You have a new appointment','booking-ultra-pro'),						
						'email_new_booking_client' => $this->get_email_template('email_new_booking_client'),
						'email_new_booking_subject_client' => __('Thank you for your appointment','booking-ultra-pro'),
						'email_reschedule' => $this->get_email_template('email_reschedule'),
						'email_reschedule_staff' => $this->get_email_template('email_reschedule_staff'),
						'email_reschedule_admin' => $this->get_email_template('email_reschedule_admin'),
						'email_reschedule_subject' => __('Appointment Reschedule','booking-ultra-pro'),
						'email_reschedule_subject_staff' => __('Appointment Reschedule','booking-ultra-pro'),
						'email_reschedule_subject_admin' => __('Appointment Reschedule','booking-ultra-pro'),
						
						'email_bank_payment' => $this->get_email_template('email_bank_payment'),
						'email_bank_payment_subject' => __('Appointment Details','booking-ultra-pro'),
						
						'email_bank_payment_admin' => $this->get_email_template('email_bank_payment_admin'),
						'email_bank_payment_admin_subject' => __('New Appointment','booking-ultra-pro'),
						
						'email_bank_payment_staff' => $this->get_email_template('email_bank_payment_staff'),
						'email_bank_payment_staff_subject' => __('You have a new Appointment','booking-ultra-pro'),
						
						'email_appo_status_changed_admin' => $this->get_email_template('email_appo_status_changed_admin'),
						'email_appo_status_changed_admin_subject' => __('Appointment Status Changed','booking-ultra-pro'),
						'email_appo_status_changed_staff' => $this->get_email_template('email_appo_status_changed_staff'),
						'email_appo_status_changed_staff_subject' => __('Appointment Status Changed','booking-ultra-pro'),
						'email_appo_status_changed_client' => $this->get_email_template('email_appo_status_changed_client'),
						'email_appo_status_changed_client_subject' => __('Appointment Status Changed','booking-ultra-pro'),
						
						'email_password_change_staff' => $this->get_email_template('email_password_change_staff'),
						'email_password_change_staff_subject' => __('Password Changed','booking-ultra-pro'),
						
						'email_reset_link_message_body' => $this->get_email_template('email_reset_link_message_body'),
						'email_reset_link_message_subject' => __('Password Reset','booking-ultra-pro'),
						
						'email_welcome_staff_link_message_body' => $this->get_email_template('email_welcome_staff_link_message_body'),
						'email_welcome_staff_link_message_subject' => __('Your Account Details','booking-ultra-pro'),
						
						'email_sms_body_reminder_customer_1' => $this->get_email_template('email_sms_body_reminder_customer_1'),
						
						
						
											
						
				);
		
	}
	
	public function set_default_email_messages()
	{
		$line_break = "\r\n";	
						
		//notify admin 		
		$email_body = __('Hello Admin ' ,"bookingup") .$line_break.$line_break;
		$email_body .= __("A new booking has been received. Below are the details of the appointment.","bookingup") .  $line_break.$line_break;
		
		$email_body .= "<strong>".__("Appointment Details:","bookingup")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{bup_booking_service}}','booking-ultra-pro') . $line_break;	
		$email_body .= __('Client: {{bup_client_name}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Phone: {{bup_client_phone}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Client Email: {{bup_client_email}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Date: {{bup_booking_date}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Time: {{bup_booking_time}}','booking-ultra-pro') . $line_break;
		$email_body .= __('With: {{bup_booking_staff}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Cost: {{bup_booking_cost}}','booking-ultra-pro'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','booking-ultra-pro'). $line_break;
		$email_body .= '{{bup_company_name}}'. $line_break;
		$email_body .= '{{bup_company_phone}}'. $line_break;
		$email_body .= '{{bup_company_url}}'. $line_break;
		
		$email_body .= __("Please, use the following link in case you'd like to approve this appointment.",'booking-ultra-pro'). $line_break;
		$email_body .='{{bup_booking_approval_url}}';		
		
		
	    $this->notifications_email['email_new_booking_admin'] = $email_body;
		
		//notify staff 		
		$email_body =  '{{bup_staff_name}},'.$line_break.$line_break;
		$email_body .= __("You have a new appointment. ","bookingup") .  $line_break.$line_break;
		
		$email_body .= "<strong>".__("Appointment Details:","bookingup")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{bup_booking_service}}','booking-ultra-pro') . $line_break;	
		$email_body .= __('Client: {{bup_client_name}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Phone: {{bup_client_phone}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Client Email: {{bup_client_email}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Date: {{bup_booking_date}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Time: {{bup_booking_time}}','booking-ultra-pro') . $line_break;
		$email_body .= __('With: {{bup_booking_staff}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Cost: {{bup_booking_cost}}','booking-ultra-pro'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','booking-ultra-pro'). $line_break;
		$email_body .= '{{bup_company_name}}'. $line_break;
		$email_body .= '{{bup_company_phone}}'. $line_break;
		$email_body .= '{{bup_company_url}}'. $line_break. $line_break;		
		
	    $this->notifications_email['email_new_booking_staff'] = $email_body;
		
		//notify client 		
		$email_body =  '{{bup_client_name}},'.$line_break.$line_break;
		$email_body .= __("Thank you for booking {{bup_booking_service}}. Below are the details of your appointment.","bookingup") .  $line_break.$line_break;
		
		$email_body .= "<strong>".__("Appointment Details:","bookingup")."</strong>".  $line_break.$line_break;
		$email_body .= __('Service: {{bup_booking_service}}','booking-ultra-pro') . $line_break;		
		$email_body .= __('Date: {{bup_booking_date}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Time: {{bup_booking_time}}','booking-ultra-pro') . $line_break;
		$email_body .= __('With: {{bup_booking_staff}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Cost: {{bup_booking_cost}}','booking-ultra-pro'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','booking-ultra-pro'). $line_break;
		$email_body .= '{{bup_company_name}}'. $line_break;
		$email_body .= '{{bup_company_phone}}'. $line_break;
		$email_body .= '{{bup_company_url}}'. $line_break. $line_break;
		
		$email_body .= __("Please, use the following link in case you'd like to cancel your appointment.",'booking-ultra-pro'). $line_break;
		$email_body .='{{bup_booking_cancelation_url}}';
		
	    $this->notifications_email['email_new_booking_client'] = $email_body;
		
		//notify reschedule client		
		$email_body =  '{{bup_client_name}},'.$line_break.$line_break;
		$email_body .= __("Your appointment has been rescheduled . ","bookingup") .  $line_break.$line_break;
		
		$email_body .= "<strong>".__("Appointment Details:","bookingup")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{bup_booking_service}}','booking-ultra-pro') . $line_break;	
		$email_body .= __('Date: {{bup_booking_date}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Time: {{bup_booking_time}}','booking-ultra-pro') . $line_break;
		$email_body .= __('With: {{bup_booking_staff}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Cost: {{bup_booking_cost}}','booking-ultra-pro'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','booking-ultra-pro'). $line_break;
		$email_body .= '{{bup_company_name}}'. $line_break;
		$email_body .= '{{bup_company_phone}}'. $line_break;
		$email_body .= '{{bup_company_url}}'. $line_break. $line_break;		
		
	    $this->notifications_email['email_reschedule'] = $email_body;
		
		//notify reschedule staff		
		$email_body =  '{{bup_staff_name}},'.$line_break.$line_break;
		$email_body .= __("One of your appointments has been rescheduled . ","bookingup") .  $line_break.$line_break;
		
		$email_body .= "<strong>".__("Appointment Details:","bookingup")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{bup_booking_service}}','booking-ultra-pro') . $line_break;	
		$email_body .= __('Date: {{bup_booking_date}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Time: {{bup_booking_time}}','booking-ultra-pro') . $line_break;
		$email_body .= __('With: {{bup_booking_staff}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Cost: {{bup_booking_cost}}','booking-ultra-pro'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','booking-ultra-pro'). $line_break;
		$email_body .= '{{bup_company_name}}'. $line_break;
		$email_body .= '{{bup_company_phone}}'. $line_break;
		$email_body .= '{{bup_company_url}}'. $line_break. $line_break;		
		
	    $this->notifications_email['email_reschedule_staff'] = $email_body;
		
		//notify reschedule admin		
		$email_body =  'Dear Admin,'.$line_break.$line_break;
		$email_body .= __("This is a confirmation that an appointment has been rescheduled . ","bookingup") .  $line_break.$line_break;
		
		$email_body .= "<strong>".__("Appointment Details:","bookingup")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{bup_booking_service}}','booking-ultra-pro') . $line_break;	
		$email_body .= __('Date: {{bup_booking_date}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Time: {{bup_booking_time}}','booking-ultra-pro') . $line_break;
		$email_body .= __('With: {{bup_booking_staff}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Cost: {{bup_booking_cost}}','booking-ultra-pro'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','booking-ultra-pro'). $line_break;
		$email_body .= '{{bup_company_name}}'. $line_break;
		$email_body .= '{{bup_company_phone}}'. $line_break;
		$email_body .= '{{bup_company_url}}'. $line_break. $line_break;		
		
	    $this->notifications_email['email_reschedule_admin'] = $email_body;		
		
		//notify bank 		
		$email_body =  '{{bup_client_name}},'.$line_break.$line_break;
		$email_body .= __("Please deposit the payment in the following bank account: ","bookingup") .  $line_break.$line_break;
		
		$email_body .= "<strong>Bank Name</strong>: ".  $line_break;
		$email_body .= "<strong>Account Number</strong>: ".  $line_break;
		$email_body .=   $line_break;
		
		$email_body .= "<strong>".__("Appointment Details:","bookingup")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{bup_booking_service}}','booking-ultra-pro') . $line_break;	
		
		$email_body .= __('Date: {{bup_booking_date}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Time: {{bup_booking_time}}','booking-ultra-pro') . $line_break;
		$email_body .= __('With: {{bup_booking_staff}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Cost: {{bup_booking_cost}}','booking-ultra-pro'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','booking-ultra-pro'). $line_break;
		$email_body .= '{{bup_company_name}}'. $line_break;
		$email_body .= '{{bup_company_phone}}'. $line_break;
		$email_body .= '{{bup_company_url}}'. $line_break. $line_break;		
		
	    $this->notifications_email['email_bank_payment'] = $email_body;
		
		//notify bank to admin	
		$email_body = __('Hello Admin ' ,"bookingup") .$line_break.$line_break;
		$email_body .= __("A new appointment with local payment has been submitted. ","bookingup") .  $line_break.$line_break;			
		$email_body .= "<strong>".__("Appointment Details:","bookingup")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{bup_booking_service}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Client: {{bup_client_name}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Phone: {{bup_client_phone}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Client Email: {{bup_client_email}}','booking-ultra-pro') . $line_break;	
		$email_body .= __('Date: {{bup_booking_date}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Time: {{bup_booking_time}}','booking-ultra-pro') . $line_break;
		$email_body .= __('With: {{bup_booking_staff}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Cost: {{bup_booking_cost}}','booking-ultra-pro'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','booking-ultra-pro'). $line_break;
		$email_body .= '{{bup_company_name}}'. $line_break;
		$email_body .= '{{bup_company_phone}}'. $line_break;
		$email_body .= '{{bup_company_url}}'. $line_break. $line_break;	
		
		$email_body .= __("Please, use the following link in case you'd like to approve this appointment.",'booking-ultra-pro'). $line_break;
		$email_body .='{{bup_booking_approval_url}}';		
		
	    $this->notifications_email['email_bank_payment_admin'] = $email_body;
		
		//notify bank to staff	
		$email_body = '{{bup_staff_name}},' .$line_break.$line_break;
		$email_body .= __("Dear staff member, new appointment with local payment has been submitted. ","bookingup") .  $line_break.$line_break;			
		$email_body .= "<strong>".__("Appointment Details:","bookingup")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{bup_booking_service}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Client: {{bup_client_name}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Phone: {{bup_client_phone}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Client Email: {{bup_client_email}}','booking-ultra-pro') . $line_break;	
		$email_body .= __('Date: {{bup_booking_date}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Time: {{bup_booking_time}}','booking-ultra-pro') . $line_break;
		$email_body .= __('With: {{bup_booking_staff}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Cost: {{bup_booking_cost}}','booking-ultra-pro'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','booking-ultra-pro'). $line_break;
		$email_body .= '{{bup_company_name}}'. $line_break;
		$email_body .= '{{bup_company_phone}}'. $line_break;
		$email_body .= '{{bup_company_url}}'. $line_break. $line_break;	
		
		$email_body .= __("Please, use the following link in case you'd like to approve this appointment.",'booking-ultra-pro'). $line_break;
		$email_body .='{{bup_booking_approval_url}}';		
		
	    $this->notifications_email['email_bank_payment_staff'] = $email_body;
		
		//Appointment Status Changed Admin	
		$email_body = __('Hello Admin ' ,"bookingup") .$line_break.$line_break;
		$email_body .= __("The status of the following appointment has changed. ","bookingup") .  $line_break.$line_break;
		
		$email_body .= __('New Status: {{bup_booking_status}}','booking-ultra-pro') . $line_break.$line_break;		
				
		$email_body .= "<strong>".__("Appointment Details:","bookingup")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{bup_booking_service}}','booking-ultra-pro') . $line_break;	
		$email_body .= __('Date: {{bup_booking_date}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Time: {{bup_booking_time}}','booking-ultra-pro') . $line_break;
		$email_body .= __('With: {{bup_booking_staff}}','booking-ultra-pro') . $line_break;
		
		$email_body .= __('Best Regards!','booking-ultra-pro'). $line_break;
		$email_body .= '{{bup_company_name}}'. $line_break;
		$email_body .= '{{bup_company_phone}}'. $line_break;
		$email_body .= '{{bup_company_url}}'. $line_break. $line_break;		
		
	    $this->notifications_email['email_appo_status_changed_admin'] = $email_body;
		
		//Appointment Status Changed Staff	
		$email_body =  '{{bup_staff_name}},'.$line_break.$line_break;
		$email_body .= __("The status of the following appointment has changed. ","bookingup") .  $line_break.$line_break;
		
		$email_body .= __('New Status: {{bup_booking_status}}','booking-ultra-pro') . $line_break.$line_break;
		
				
		$email_body .= "<strong>".__("Appointment Details:","bookingup")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{bup_booking_service}}','booking-ultra-pro') . $line_break;	
		$email_body .= __('Date: {{bup_booking_date}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Time: {{bup_booking_time}}','booking-ultra-pro') . $line_break;
		$email_body .= __('With: {{bup_booking_staff}}','booking-ultra-pro') . $line_break;
		
		$email_body .= __('Best Regards!','booking-ultra-pro'). $line_break;
		$email_body .= '{{bup_company_name}}'. $line_break;
		$email_body .= '{{bup_company_phone}}'. $line_break;
		$email_body .= '{{bup_company_url}}'. $line_break. $line_break;		
		
	    $this->notifications_email['email_appo_status_changed_staff'] = $email_body;
		
		//Appointment Status Changed Client	
		$email_body =  '{{bup_client_name}},'.$line_break.$line_break;
		$email_body .= __("The status of your appointment has changed. ","bookingup") .  $line_break.$line_break;
		
		$email_body .= __('New Status: {{bup_booking_status}}','booking-ultra-pro') . $line_break.$line_break;		
				
		$email_body .= "<strong>".__("Appointment Details:","bookingup")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{bup_booking_service}}','booking-ultra-pro') . $line_break;	
		$email_body .= __('Date: {{bup_booking_date}}','booking-ultra-pro') . $line_break;
		$email_body .= __('Time: {{bup_booking_time}}','booking-ultra-pro') . $line_break;
		$email_body .= __('With: {{bup_booking_staff}}','booking-ultra-pro') . $line_break;
		
		$email_body .= __('Best Regards!','booking-ultra-pro'). $line_break;
		$email_body .= '{{bup_company_name}}'. $line_break;
		$email_body .= '{{bup_company_phone}}'. $line_break;
		$email_body .= '{{bup_company_url}}'. $line_break. $line_break;		
		
	    $this->notifications_email['email_appo_status_changed_client'] = $email_body;
		
		//Staff Password Change	
		$email_body =  '{{bup_staff_name}},'.$line_break.$line_break;
		$email_body .= __("This is a notification that your password has been changed. ","bookingup") .  $line_break.$line_break;
				
		$email_body .= __('Best Regards!','booking-ultra-pro'). $line_break;
		$email_body .= '{{bup_company_name}}'. $line_break;
		$email_body .= '{{bup_company_phone}}'. $line_break;
		$email_body .= '{{bup_company_url}}'. $line_break. $line_break;
		
	    $this->notifications_email['email_password_change_staff'] = $email_body;
		
		//Staff Password Reset	
		$email_body =  '{{bup_staff_name}},'.$line_break.$line_break;
		$email_body .= __("Please use the following link to reset your password.","bookingup") . $line_break.$line_break;			
		$email_body .= "{{bup_reset_link}}".$line_break.$line_break;
		$email_body .= __('If you did not request a new password delete this email.','booking-ultra-pro'). $line_break.$line_break;	
			
		$email_body .= __('Best Regards!','booking-ultra-pro'). $line_break;
		$email_body .= '{{bup_company_name}}'. $line_break;
		$email_body .= '{{bup_company_phone}}'. $line_break;
		$email_body .= '{{bup_company_url}}'. $line_break. $line_break;
		
	    $this->notifications_email['email_reset_link_message_body'] = $email_body;
		
		//Staff Welcome Account Reset Link	
		$email_body =  '{{bup_staff_name}},'.$line_break.$line_break;
		$email_body .= __("Your login details for your account are as follows:","bookingup") . $line_break.$line_break;
		$email_body .= __('Username: {{bup_user_name}}','booking-ultra-pro') . $line_break;
		$email_body .= __("Please use the following link to reset your password.","bookingup") . $line_break.$line_break;			
		$email_body .= "{{bup_reset_link}}".$line_break.$line_break;
			
		$email_body .= __('Best Regards!','booking-ultra-pro'). $line_break;
		$email_body .= '{{bup_company_name}}'. $line_break;
		$email_body .= '{{bup_company_phone}}'. $line_break;
		$email_body .= '{{bup_company_url}}'. $line_break. $line_break;
		
	    $this->notifications_email['email_welcome_staff_link_message_body'] = $email_body;	
		
		//SMS Reminder to Customer
		$email_body =  __('Dear ','booking-ultra-pro').'{{bup_client_name}}. ';
		$email_body .= __("You have an appointment with our company tomorrow at {{bup_booking_time}}.","bookingup");
		$email_body .= __(" We are waiting you at {{bup_company_address}}. ","bookingup") ;
		$email_body .= '{{bup_company_name}}';	
		
	    $this->notifications_email['email_sms_body_reminder_customer_1'] = $email_body;	
		
		
		
		
	
	}
	
	public function get_email_template($key)
	{
		return $this->notifications_email[$key];
	
	}
	
	public function set_font_awesome()
	{
		        /* Store icons in array */
        $this->fontawesome = array(
                'cloud-download','cloud-upload','lightbulb','exchange','bell-alt','file-alt','beer','coffee','food','fighter-jet',
                'user-md','stethoscope','suitcase','building','hospital','ambulance','medkit','h-sign','plus-sign-alt','spinner',
                'angle-left','angle-right','angle-up','angle-down','double-angle-left','double-angle-right','double-angle-up','double-angle-down','circle-blank','circle',
                'desktop','laptop','tablet','mobile-phone','quote-left','quote-right','reply','github-alt','folder-close-alt','folder-open-alt',
                'adjust','asterisk','ban-circle','bar-chart','barcode','beaker','beer','bell','bolt','book','bookmark','bookmark-empty','briefcase','bullhorn',
                'calendar','camera','camera-retro','certificate','check','check-empty','cloud','cog','cogs','comment','comment-alt','comments','comments-alt',
                'credit-card','dashboard','download','download-alt','edit','envelope','envelope-alt','exclamation-sign','external-link','eye-close','eye-open',
                'facetime-video','film','filter','fire','flag','folder-close','folder-open','gift','glass','globe','group','hdd','headphones','heart','heart-empty',
                'home','inbox','info-sign','key','leaf','legal','lemon','lock','unlock','magic','magnet','map-marker','minus','minus-sign','money','move','music',
                'off','ok','ok-circle','ok-sign','pencil','picture','plane','plus','plus-sign','print','pushpin','qrcode','question-sign','random','refresh','remove',
                'remove-circle','remove-sign','reorder','resize-horizontal','resize-vertical','retweet','road','rss','screenshot','search','share','share-alt',
                'shopping-cart','signal','signin','signout','sitemap','sort','sort-down','sort-up','spinner','star','star-empty','star-half','tag','tags','tasks',
                'thumbs-down','thumbs-up','time','tint','trash','trophy','truck','umbrella','upload','upload-alt','user','volume-off','volume-down','volume-up',
                'warning-sign','wrench','zoom-in','zoom-out','file','cut','copy','paste','save','undo','repeat','text-height','text-width','align-left','align-right',
                'align-center','align-justify','indent-left','indent-right','font','bold','italic','strikethrough','underline','link','paper-clip','columns',
                'table','th-large','th','th-list','list','list-ol','list-ul','list-alt','arrow-down','arrow-left','arrow-right','arrow-up','caret-down',
                'caret-left','caret-right','caret-up','chevron-down','chevron-left','chevron-right','chevron-up','circle-arrow-down','circle-arrow-left',
                'circle-arrow-right','circle-arrow-up','hand-down','hand-left','hand-right','hand-up','play-circle','play','pause','stop','step-backward',
                'fast-backward','backward','forward','step-forward','fast-forward','eject','fullscreen','resize-full','resize-small','phone','phone-sign',
                'facebook','facebook-sign','twitter','twitter-sign','github','github-sign','linkedin','linkedin-sign','pinterest','pinterest-sign',
                'google-plus','google-plus-sign','sign-blank'
        );
        asort($this->fontawesome);
		
	
	
	}
	
		
	
	/*This Function Change the Profile Fields Order when drag/drop */	
	public function sort_fileds_list() 
	{
		global $wpdb;
	
		$order = explode(',', $_POST['order']);
		$counter = 0;
		$new_pos = 10;
		
		//multi fields		
		$custom_form = $_POST["bup_custom_form"];
		
		if($custom_form!="")
		{
			$custom_form = 'bup_profile_fields_'.$custom_form;		
			$fields = get_option($custom_form);			
			$fields_set_to_update =$custom_form;
			
		}else{
			
			$fields = get_option('bup_profile_fields');
			$fields_set_to_update ='bup_profile_fields';
		
		}
		
		$new_fields = array();
		
		$fields_temp = $fields;
		ksort($fields);
		
		foreach ($fields as $field) 
		{
			
			$fields_temp[$order[$counter]]["position"] = $new_pos;			
			$new_fields[$new_pos] = $fields_temp[$order[$counter]];				
			$counter++;
			$new_pos=$new_pos+10;
		}
		
		ksort($new_fields);		
		
		
		update_option($fields_set_to_update, $new_fields);		
		die(1);
		
    }
	/*  delete profile field */
    public function delete_profile_field() 
	{						
		
		if($_POST['_item']!= "")
		{
			//$fields = get_option('usersultra_profile_fields');
			
			//multi fields		
			$custom_form = $_POST["bup_custom_form"];
			
			if($custom_form!="")
			{
				$custom_form = 'bup_profile_fields_'.$custom_form;		
				$fields = get_option($custom_form);			
				$fields_set_to_update =$custom_form;
				
			}else{
				
				$fields = get_option('bup_profile_fields');
				$fields_set_to_update ='bup_profile_fields';
			
			}
			
			$pos = $_POST['_item'];
			
			unset($fields[$pos]);
			
			ksort($fields);
			print_r($fields);
			update_option($fields_set_to_update, $fields);
			
		
		}
	
	}
	
	
	 /* create new custom profile field */
    public function add_new_custom_profile_field() 
	{				
		
		
		if($_POST['_meta']!= "")
		{
			$meta = $_POST['_meta'];
		
		}else{
			
			$meta = $_POST['_meta_custom'];
		}
		
		//if custom fields
		
		
		//multi fields		
		$custom_form = $_POST["bup_custom_form"];
		
		if($custom_form!="")
		{
			$custom_form = 'bup_profile_fields_'.$custom_form;		
			$fields = get_option($custom_form);			
			$fields_set_to_update =$custom_form;
			
		}else{
			
			$fields = get_option('bup_profile_fields');
			$fields_set_to_update ='bup_profile_fields';
		
		}
		
		$min = min(array_keys($fields)); 
		
		$pos = $min-1;
		
		$fields[$pos] =array(
			  'position' => $pos,
				'icon' => filter_var($_POST['_icon']),
				'type' => filter_var($_POST['_type']),
				'field' => filter_var($_POST['_field']),
				'meta' => filter_var($meta),
				'name' => filter_var($_POST['_name']),				
				'tooltip' => filter_var($_POST['_tooltip']),
				'help_text' => filter_var($_POST['_help_text']),							
				'can_edit' => filter_var($_POST['_can_edit']),
				'allow_html' => filter_var($_POST['_allow_html']),
				'can_hide' => filter_var($_POST['_can_hide']),				
				'private' => filter_var($_POST['_private']),
				'required' => filter_var($_POST['_required']),
				'show_in_register' => filter_var($_POST['_show_in_register']),
				'predefined_options' => filter_var($_POST['_predefined_options']),				
				'choices' => filter_var($_POST['_choices']),												
				'deleted' => 0
				

			);
			
					
			ksort($fields);
			print_r($fields);
			
		   update_option($fields_set_to_update, $fields);         


    }
	


    // save form
    public function save_fields_settings() 
	{		
		
		$pos = filter_var($_POST['pos']);
		
		if($_POST['_meta']!= "")
		{
			$meta = $_POST['_meta'];
		
		}else{
			
			$meta = $_POST['_meta_custom'];
		}
		
		//if custom fields
		
		//multi fields		
		$custom_form = $_POST["bup_custom_form"];
		
		if($custom_form!="")
		{
			$custom_form = 'bup_profile_fields_'.$custom_form;		
			$fields = get_option($custom_form);			
			$fields_set_to_update =$custom_form;
			
		}else{
			
			$fields = get_option('bup_profile_fields');
			$fields_set_to_update ='bup_profile_fields';
		
		}
		
		$fields[$pos] =array(
			  'position' => $pos,
				'icon' => $_POST['_icon'],
				'type' => filter_var($_POST['_type']),
				'field' => filter_var($_POST['_field']),
				'meta' => filter_var($meta),
				'name' => filter_var($_POST['_name']),
				'ccap' => filter_var($_POST['_ccap']),
				'tooltip' => filter_var($_POST['_tooltip']),
				'help_text' => filter_var($_POST['_help_text']),
				'social' =>  filter_var($_POST['_social']),
				'is_a_link' =>  filter_var($_POST['_is_a_link']),
				'can_edit' => filter_var($_POST['_can_edit']),
				'allow_html' => filter_var($_POST['_allow_html']),				
				'required' => filter_var($_POST['_required']),
				'show_in_register' => filter_var($_POST['_show_in_register']),
				
				'predefined_options' => filter_var($_POST['_predefined_options']),				
				'choices' => filter_var($_POST['_choices']),												
				'deleted' => 0,
				'show_to_user_role' => $_POST['_show_to_user_role'],
                'edit_by_user_role' => $_POST['_edit_by_user_role']
			);
			
			
						
			print_r($fields);
			
		    update_option($fields_set_to_update , $fields);
		
         


    }
	
		
	/*This load a custom field to be edited Implemented on 08-08-2014*/
	function bup_reload_field_to_edit()	
	{
		global $bookingultrapro;
		
		//get field
		$pos = $_POST["pos"];
		
		
		//multi fields		
		$custom_form = $_POST["bup_custom_form"];
		
		if($custom_form!="")
		{
			$custom_form = 'bup_profile_fields_'.$custom_form;		
			$fields = get_option($custom_form);			
			$fields_set_to_update =$custom_form;
			
		}else{
			
			$fields = get_option('bup_profile_fields');
			$fields_set_to_update ='bup_profile_fields';
		
		}
		
		$array = $fields[$pos];
		
		
		extract($array); $i++;

		if(!isset($required))
		       $required = 0;

		    if(!isset($fonticon))
		        $fonticon = '';				
				
			if ($type == 'seperator' || $type == 'separator') {
			   
				$class = "separator";
				$class_title = "";
			} else {
			  
				$class = "profile-field";
				$class_title = "profile-field";
			}
		
		
		?>
		
		

				<p>
					<label for="uultra_<?php echo $pos; ?>_position"><?php _e('Position','booking-ultra-pro'); ?>
					</label> <input name="uultra_<?php echo $pos; ?>_position"
						type="text" id="uultra_<?php echo $pos; ?>_position"
						value="<?php echo $pos; ?>" class="small-text" /> <i
						class="uultra_icon-question-sign uultra-tooltip2"
						title="<?php _e('Please use a unique position. Position lets you place the new field in the place you want exactly in Profile view.','booking-ultra-pro'); ?>"></i>
				</p>

				<p>
					<label for="uultra_<?php echo $pos; ?>_type"><?php _e('Field Type','booking-ultra-pro'); ?>
					</label> <select name="uultra_<?php echo $pos; ?>_type"
						id="uultra_<?php echo $pos; ?>_type">
						<option value="usermeta" <?php selected('usermeta', $type); ?>>
							<?php _e('Profile Field','booking-ultra-pro'); ?>
						</option>
						<option value="separator" <?php selected('separator', $type); ?>>
							<?php _e('Separator','booking-ultra-pro'); ?>
						</option>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('You can create a separator or a usermeta (profile field)','booking-ultra-pro'); ?>"></i>
				</p> 
				
				<?php if ($type != 'separator') { ?>

				<p class="uultra-inputtype">
					<label for="uultra_<?php echo $pos; ?>_field"><?php _e('Field Input','booking-ultra-pro'); ?>
					</label> <select name="uultra_<?php echo $pos; ?>_field"
						id="uultra_<?php echo $pos; ?>_field">
						<?php
						
						 foreach($bookingultrapro->allowed_inputs as $input=>$label) { ?>
						<option value="<?php echo $input; ?>"
						<?php selected($input, $field); ?>>
							<?php echo $label; ?>
						</option>
						<?php } ?>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('When user edit profile, this field can be an input (text, textarea, image upload, etc.)','booking-ultra-pro'); ?>"></i>
				</p>

				
				<p>
					<label for="uultra_<?php echo $pos; ?>_meta_custom"><?php _e('Custom Meta Field','booking-ultra-pro'); ?>
					</label> <input name="uultra_<?php echo $pos; ?>C"
						type="text" id="uultra_<?php echo $pos; ?>_meta_custom"
						value="<?php if (!isset($all_meta_for_user[$meta])) echo $meta; ?>" />
					<i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Enter a custom meta key for this profile field if do not want to use a predefined meta field above. It is recommended to only use alphanumeric characters and underscores, for example my_custom_meta is a proper meta key.','booking-ultra-pro'); ?>"></i>
				</p> <?php } ?>

				
                
                
                <p>
					<label for="uultra_<?php echo $pos; ?>_name"><?php _e('Label / Name','booking-ultra-pro'); ?>
					</label> <input name="uultra_<?php echo $pos; ?>_name" type="text"
						id="uultra_<?php echo $pos; ?>_name" value="<?php echo $name; ?>" />
					<i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Enter the label / name of this field as you want it to appear in front-end (Profile edit/view)','booking-ultra-pro'); ?>"></i>
				</p>
                
                

			<?php if ($type != 'separator' ) { ?>

				
				<p>
					<label for="uultra_<?php echo $pos; ?>_tooltip"><?php _e('Tooltip Text','booking-ultra-pro'); ?>
					</label> <input name="uultra_<?php echo $pos; ?>_tooltip" type="text"
						id="uultra_<?php echo $pos; ?>_tooltip"
						value="<?php echo $tooltip; ?>" /> <i
						class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('A tooltip text can be useful for social buttons on profile header.','booking-ultra-pro'); ?>"></i>
				</p> 
                
               <p>
               
               <label for="uultra_<?php echo $pos; ?>_help_text"><?php _e('Help Text','booking-ultra-pro'); ?>
                </label><br />
                    <textarea class="uultra-help-text" id="uultra_<?php echo $pos; ?>_help_text" name="uultra_<?php echo $pos; ?>_help_text" title="<?php _e('A help text can be useful for provide information about the field.','booking-ultra-pro'); ?>" ><?php echo $help_text; ?></textarea>
                    <i class="uultra-icon-question-sign uultra-tooltip2"
                                title="<?php _e('Show this help text under the profile field.','booking-ultra-pro'); ?>"></i>
                              
               </p> 
				
				
				
                
               				
				<?php 
				if(!isset($can_edit))
				    $can_edit = '1';
				?>
				<p>
					<label for="uultra_<?php echo $pos; ?>_can_edit"><?php _e('User can edit','booking-ultra-pro'); ?>
					</label> <select name="uultra_<?php echo $pos; ?>_can_edit"
						id="uultra_<?php echo $pos; ?>_can_edit">
						<option value="1" <?php selected(1, $can_edit); ?>>
							<?php _e('Yes','booking-ultra-pro'); ?>
						</option>
						<option value="0" <?php selected(0, $can_edit); ?>>
							<?php _e('No','booking-ultra-pro'); ?>
						</option>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Users can edit this profile field or not.','booking-ultra-pro'); ?>"></i>
				</p> 
				
				<?php if (!isset($array['allow_html'])) { 
				    $allow_html = 0;
				} ?>
				<p>
					<label for="uultra_<?php echo $pos; ?>_allow_html"><?php _e('Allow HTML','booking-ultra-pro'); ?>
					</label> <select name="uultra_<?php echo $pos; ?>_allow_html"
						id="uultra_<?php echo $pos; ?>_allow_html">
						<option value="0" <?php selected(0, $allow_html); ?>>
							<?php _e('No','booking-ultra-pro'); ?>
						</option>
						<option value="1" <?php selected(1, $allow_html); ?>>
							<?php _e('Yes','booking-ultra-pro'); ?>
						</option>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('If yes, users will be able to write HTML code in this field.','booking-ultra-pro'); ?>"></i>
				</p> 
				
				
				
				<?php 
				if(!isset($required))
				    $required = '0';
				?>
				<p>
					<label for="uultra_<?php echo $pos; ?>_required"><?php _e('This field is Required','booking-ultra-pro'); ?>
					</label> <select name="uultra_<?php echo $pos; ?>_required"
						id="uultra_<?php echo $pos; ?>_required">
						<option value="0" <?php selected(0, $required); ?>>
							<?php _e('No','booking-ultra-pro'); ?>
						</option>
						<option value="1" <?php selected(1, $required); ?>>
							<?php _e('Yes','booking-ultra-pro'); ?>
						</option>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Selecting yes will force user to provide a value for this field at registration and edit profile. Registration or profile edits will not be accepted if this field is left empty.','booking-ultra-pro'); ?>"></i>
				</p> <?php } ?> <?php

				/* Show Registration field only when below condition fullfill
				1) Field is not private
				2) meta is not for email field
				3) field is not fileupload */
				if(!isset($private))
				    $private = 0;

				if(!isset($meta))
				    $meta = '';

				if(!isset($field))
				    $field = '';


				//if($type == 'separator' ||  ($private != 1 && $meta != 'user_email' ))
				if($type == 'separator' ||  ($private != 1 && $meta != 'user_email' ))
				{
				    if(!isset($show_in_register))
				        $show_in_register= 0;
						
					 if(!isset($show_in_widget))
				        $show_in_widget= 0;
				    ?>
				<p>
					<label for="uultra_<?php echo $pos; ?>_show_in_register"><?php _e('Show on Registration Form','booking-ultra-pro'); ?>
					</label> <select name="uultra_<?php echo $pos; ?>_show_in_register"
						id="uultra_<?php echo $pos; ?>_show_in_register">
						<option value="0" <?php selected(0, $show_in_register); ?>>
							<?php _e('No','booking-ultra-pro'); ?>
						</option>
						<option value="1" <?php selected(1, $show_in_register); ?>>
							<?php _e('Yes','booking-ultra-pro'); ?>
						</option>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Show this profile field on the registration form','booking-ultra-pro'); ?>"></i>
				</p>    
               
                
                 <?php } ?>
                 
			<?php if ($type != 'seperator' || $type != 'separator') { ?>

		  <?php if (in_array($field, array('select','radio','checkbox')))
				 {
				    $show_choices = null;
				} else { $show_choices = 'uultra-hide';
				
				
				} ?>

				<p class="uultra-choices <?php echo $show_choices; ?>">
					<label for="uultra_<?php echo $pos; ?>_choices"
						style="display: block"><?php _e('Available Choices','booking-ultra-pro'); ?> </label>
					<textarea name="uultra_<?php echo $pos; ?>_choices" type="text" id="uultra_<?php echo $pos; ?>_choices" class="large-text"><?php if (isset($array['choices'])) echo trim($choices); ?></textarea>
                    
                    <?php
                    
					if($bookingultrapro->uultra_if_windows_server())
					{
						echo ' <p>'.__('<strong>PLEASE NOTE: </strong>Enter values separated by commas, example: 1,2,3. The choices will be available for front end user to choose from.').' </p>';					
					}else{
						
						echo ' <p>'.__('<strong>PLEASE NOTE:</strong> Enter one choice per line please. The choices will be available for front end user to choose from.').' </p>';
					
					
					}
					
					?>
                    <p>
                    
                    
                    </p>
					<i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Enter one choice per line please. The choices will be available for front end user to choose from.','booking-ultra-pro'); ?>"></i>
				</p> <?php //if (!isset($array['predefined_loop'])) $predefined_loop = 0;
				
				if (!isset($predefined_options)) $predefined_options = 0;
				
				 ?>

				<p class="uultra_choices <?php echo $show_choices; ?>">
					<label for="uultra_<?php echo $pos; ?>_predefined_options" style="display: block"><?php _e('Enable Predefined Choices','booking-ultra-pro'); ?>
					</label> 
                    <select name="uultra_<?php echo $pos; ?>_predefined_options"id="uultra_<?php echo $pos; ?>_predefined_options">
						<option value="0" <?php selected(0, $predefined_options); ?>>
							<?php _e('None','booking-ultra-pro'); ?>
						</option>
						<option value="countries" <?php selected('countries', $predefined_options); ?>>
							<?php _e('List of Countries','booking-ultra-pro'); ?>
						</option>
                        
                        <option value="age" <?php selected('age', $predefined_options); ?>>
							<?php _e('Age','booking-ultra-pro'); ?>
						</option>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('You can enable a predefined filter for choices. e.g. List of countries It enables country selection in profiles and saves you time to do it on your own.','booking-ultra-pro'); ?>"></i>
				</p>

				<p>

					<span style="display: block; font-weight: bold; margin: 0 0 10px 0"><?php _e('Field Icon:','booking-ultra-pro'); ?>&nbsp;&nbsp;
						<?php if ($icon) { ?>
                        
                        <i class="fa fa-<?php echo $icon; ?>"></i>
                        
						<?php } else { 
						
						_e('None','booking-ultra-pro'); 
						
						} ?>
                        
                        &nbsp;&nbsp; <a href="#changeicon"
						class="button button-secondary uultra-inline-icon-uultra-edit"><?php _e('Change Icon','booking-ultra-pro'); ?>
					</a> </span> <label class="uultra-icons">
                    
                    <input type="radio"	name="uultra_<?php echo $pos; ?>_icon" value=""
						<?php checked('', $fonticon); ?> /> <?php _e('None','booking-ultra-pro'); ?> </label>
                        
                        
                        

					<?php 
					
					foreach($this->fontawesome as $fonticon) { 
					
					
					?>
					  
                      
                      <label class="uultra-icons"><input type="radio"	name="uultra_<?php echo $pos; ?>_icon" value="<?php echo $fonticon; ?>"
						<?php checked($fonticon, $icon); ?> />

                        <i class="fa fa-<?php echo $fonticon; ?> uultra-tooltip3"
						title="<?php echo $fonticon; ?>"></i> </label>
                        
                        
					<?php } //for each ?>
                    
                    

				</p>
				<div class="clear"></div> 
				
				<?php } ?>


  <div class="bup-ultra-success bup-notification" id="bup-sucess-fields-<?php echo $pos; ?>"><?php _e('Success ','booking-ultra-pro'); ?></div>
				<p>
                
               
                 
				<input type="button" name="submit"	value="<?php _e('Update','booking-ultra-pro'); ?>"						class="button button-primary bup-btn-submit-field"  data-edition="<?php echo $pos; ?>" /> 
                   <input type="button" value="<?php _e('Cancel','booking-ultra-pro'); ?>"
						class="button button-secondary bup-btn-close-edition-field" data-edition="<?php echo $pos; ?>" />
				</p>
                
      <?php
	  
	  die();
		
	}
	
	public function bup_create_standard_form_fields ($form_name )	
	{		
	
		/* These are the basic profile fields */
		$fields_array = array(
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
		if (!get_option($form_name))
		{
			if($form_name!="")
			{
				update_option($form_name,$fields_array);
			
			}
			
		}	
		
		
	}
	
	/*Loads all field list */	
	function bup_reload_custom_fields_set ()	
	{
		
		global $bookingultrapro;
		
		$custom_form = $_POST["bup_custom_form"];		
		
		if($custom_form!="") //use a custom form
		{
			//check if fields have been added			
			$custom_form = 'bup_profile_fields_'.$custom_form;
			
			if (!get_option($custom_form)) //we need to create a default field set for this form
			{
				
				$this->bup_create_standard_form_fields($custom_form);									
				$fields = get_option($custom_form);
				
			}else{
				
				//fields have been added to the custom form.				
				$fields = get_option($custom_form);
			
			
			}
			
		
		}else{ //use the default registration from
			
			$fields = get_option('bup_profile_fields');
			
		
		}
		
		ksort($fields);		
		
		$i = 0;
		foreach($fields as $pos => $array) 
		{
		    extract($array); $i++;

		    if(!isset($required))
		        $required = 0;

		    if(!isset($fonticon))
		        $fonticon = '';
				
				
			if ($type == 'seperator' || $type == 'separator') {
			   
				$class = "separator";
				$class_title = "";
			} else {
			  
				$class = "profile-field";
				$class_title = "profile-field";
			}
		    ?>
            
          <li class="bup-profile-fields-row <?php echo $class_title?>" id="<?php echo $pos; ?>">
            
            
            <div class="heading_title  <?php echo $class?>">
            
            <h3>
            <?php
			
			if (isset($array['name']) && $array['name'])
			{
			    echo  $array['name'];
			}
			?>
            
            <?php
			if ($type == 'separator') {
				
			    echo __(' - Separator','booking-ultra-pro');
				
			} else {
				
			    echo __(' - Profile Field','booking-ultra-pro');
				
			}
			?>
            
            </h3>
            
            
              <div class="options-bar">
             
                 <p>                
                    <input type="submit" name="submit" value="<?php _e('Edit','booking-ultra-pro'); ?>"						class="button bup-btn-edit-field button-primary" data-edition="<?php echo $pos; ?>" /> <input type="button" value="<?php _e('Delete','booking-ultra-pro'); ?>"	data-field="<?php echo $pos; ?>" class="button button-secondary bup-delete-profile-field-btn" />
                    </p>
            
             </div>
            
            
          

            </div>
            
             
             <div class="bup-ultra-success bup-notification" id="bup-sucess-delete-fields-<?php echo $pos; ?>"><?php _e('Success! This field has been deleted ','booking-ultra-pro'); ?></div>
            
           
        
          <!-- edit field -->
          
          <div class="user-ultra-sect-second uultra-fields-edition user-ultra-rounded"  id="bup-edit-fields-bock-<?php echo $pos; ?>">
        
          </div>
          
          
          <!-- edit field end -->

       </li>







	<?php
	
	}
		
		die();
		
	
	}
		
	// update settings
    function update_settings() 
	{
		foreach($_POST as $key => $value) 
		{
            if ($key != 'submit')
			{
				if (strpos($key, 'html_') !== false)
                {
                      //$this->userultra_default_options[$key] = stripslashes($value);
                }else{
					
					 // $this->userultra_default_options[$key] = esc_attr($value);
                 }
					
								
					  
					
					$this->bup_set_option($key, $value) ; 
					
					//special setting for page
					if($key=="bup_my_account_page")
					{						
						//echo "Page : " . $value;
						 update_option('bup_my_account_page',$value);				 
						 
						 
					}  

            }
        }
		
		//get checks for each tab
		
		
			  
		  
		 if ( isset ( $_GET['tab'] ) )
		 {			 
			  $current = $_GET['tab'];
				
          } else {
               $current = $_GET['page'];
				
          }	
            
		$special_with_check = $this->get_special_checks($current);
         
        foreach($special_with_check as $key)
        {
           
            
                if(!isset($_POST[$key]))
				{			
                    $value= '0';
					
				 } else {
					 
					  $value= $_POST[$key];
				}	 	
         
			
			$this->bup_set_option($key, $value) ;  
			
			
            
        }
         
      $this->options = get_option('bup_options');

        echo '<div class="updated"><p><strong>'.__('Settings saved.','booking-ultra-pro').'</strong></p></div>';
    }
	
	public function get_special_checks($tab) 
	{
		$special_with_check = array();
		
		if($tab=="settings")
		{				
		
		 $special_with_check = array('uultra_loggedin_activated', 'private_message_system','redirect_backend_profile','redirect_backend_registration', 'redirect_registration_when_social','redirect_backend_login', 'social_media_fb_active', 'social_media_linked_active', 'social_media_yahoo', 'social_media_google', 'twitter_connect', 'instagram_connect', 'gateway_free_success_active', 'mailchimp_active', 'mailchimp_auto_checked',  'aweber_active', 'aweber_auto_checked');
		 
		}elseif($tab=="gateway"){
			
			 $special_with_check = array('gateway_paypal_active', 'gateway_bank_active', 'gateway_authorize_active', 'gateway_authorize_success_active' ,'gateway_stripe_active', 'gateway_stripe_success_active' ,'gateway_bank_success_active', 'gateway_free_success_active',  'gateway_paypal_success_active' ,  'appointment_cancellation_active', 'gateway_paypal_cancel_active');
		
		}elseif($tab=="mail"){
			
			 $special_with_check = array('bup_smtp_mailing_return_path', 'bup_smtp_mailing_html_txt');
		 
		}
		
		
		if($tab=="bup-reminders")
		{				
		
			 $special_with_check = array('notifications_sms_reminder_1');		
		 
		}
	
	return  $special_with_check ;
	
	}	
	
	public function do_valid_checks()
	{
		
		global $bookingultrapro, $bupcomplement, $bupultimate ;
		
		$va = get_option('bup_c_key');
		
		if(isset($bupcomplement))		
		{		
			if($va=="")
			{
				if(isset($bupultimate)) //no need to validate
				{
					$this->valid_c = "";						
				
				}else{
					
					$this->valid_c = "no";				
				
				}				
				//
					
			}
		
		}
	
	
	}
	
	public function bup_vv_c_de_a () 
	{		
		global $bookingultrapro, $wpdb ;
		
		 	
		$p = $_POST["p_s_le"];		
		
		//validate ulr
		
		$domain = $_SERVER['SERVER_NAME'];		
		$server_add = $_SERVER['SERVER_ADDR'];
		
		
		$url = bup_pro_url."check_l_p.php";	
		
		
		$response = wp_remote_post(
            $url,
            array(
                'body' => array(
                    'd'   => $domain,
                    'server_ip'     => $server_add,
                    'sial_key' => $p,
					'action' => 'validate',
					
                )
            )
        );

		
		
		$response = json_decode($response["body"]);
		
		$message =$response->{'message'}; 
		$result =$response->{'result'}; 
		$expiration =$response->{'expiration'};
		$serial =$response->{'serial'};
		
		//validate
		
		if ( is_multisite() ) // See if being activated on the entire network or one blog
		{		
			
	 
			// Get this so we can switch back to it later
			$current_blog = $wpdb->blogid;
			// For storing the list of activated blogs
			$activated = array();
			
			// Get all blogs in the network and activate plugin on each one
			
			$args = array(
				'network_id' => $wpdb->siteid,
				'public'     => null,
				'archived'   => null,
				'mature'     => null,
				'spam'       => null,
				'deleted'    => null,
				'limit'      => 100,
				'offset'     => 0,
			);
			$blog_ids = wp_get_sites( $args ); 
		   // print_r($blog_ids);
		
		
			foreach ($blog_ids as $key => $blog)
			{
				$blog_id = $blog["blog_id"];

				switch_to_blog($blog_id);				
				
				if($result =="OK")
				{
					update_option('bup_c_key',$serial );
					update_option('bup_c_expiration',$expiration );
					
					$html = '<div class="bup-ultra-success">'. __("Congratulations!. Your copy has been validated", 'booking-ultra-pro').'</div>';
				
				}elseif($result =="EXP"){
					
					update_option('bup_c_key',"" );
					update_option('bup_c_expiration',$expiration );
					
					$html = '<div class="bup-ultra-error">'. __("We are sorry the serial key you have used has expired", 'booking-ultra-pro').'</div>';
				
				}elseif($result =="NO"){
					
					update_option('bup_c_key',"" );
					update_option('bup_c_expiration',$expiration );
					
					$html = '<div class="bup-ultra-error">'. __("We are sorry your serial key is not valid", 'booking-ultra-pro').'</div>';
				
				}
				
				
			} //end for each
			
			//echo "current blog : " . $current_blog;
			// Switch back to the current blog
			switch_to_blog($current_blog); 
			
			
		}else{
			
			//this is not a multisite
			
			if($result =="OK")
			{
				update_option('bup_c_key',$serial );
				update_option('bup_c_expiration',$expiration );
				
				$html = '<div class="bup-ultra-success">'. __("Congratulations!. Your copy has been validated", 'booking-ultra-pro').'</div>';
			
			}elseif($result =="EXP"){
				
				update_option('bup_c_key',"" );
				update_option('bup_c_expiration',$expiration );
				
				$html = '<div class="bup-ultra-error">'. __("We are sorry the serial key you have used has expired", 'booking-ultra-pro').'</div>';
			
			}elseif($result =="NO"){
				
				update_option('bup_c_key',"" );
				update_option('bup_c_expiration',$expiration );
				
				$html = '<div class="bup-ultra-error">'. __("We are sorry your serial key is not valid", 'booking-ultra-pro').'</div>';
			
			}
			
			
			
		
		}
		
		//
		echo "Domain: " .$domain;
		echo $html;		 
		
		
		die();
		
	}
	
	function include_tab_content() {
		
		global $bookingultrapro, $wpdb, $bupcomplement ;
		
		$screen = get_current_screen();
		
		if( strstr($screen->id, $this->slug ) ) 
		{
			if ( isset ( $_GET['tab'] ) ) 
			{
				$tab = $_GET['tab'];
				
			} else {
				
				$tab = $this->default_tab;
			}
			
			if($this->valid_c=="" )
			{
				require_once (bookingup_path.'admin/tabs/'.$tab.'.php');			
			
			}else{ //no validated
				
				$tab = "licence";				
				require_once (bookingup_path.'admin/tabs/'.$tab.'.php');
				
			}
			
			
		}
	}
	
	function reset_email_template() 	
	{
		
		$template = $_POST['email_template'];
		$new_template = $this->get_email_template($template);
		$this->bup_set_option($template, $new_template);
		die();
		
		
	}
	
	public function display_ultimate_validate_copy () 
	{	
			
		$res_message  = get_option( 'bup_pro_improvement_13' );		
		if($res_message=="" )
		{
		
			$message = '<div id="message" class="updated buppro-message wc-connect">
	<a class="buppro-message-close notice-dismiss" href="#" message-id="13"> '.__('Dismiss','booking-ultra-pro').'</a>

	<p><strong>Booking Ultra Pro Updates:</strong> – We highly recommend you creating a serial number for your domain which will allow you to update your plugin automatically.</p>
	
	<p class="submit">
		
		<a href="?page=bookingultra&tab=licence" class="button-secondary" > '.__('Validate your Copy','booking-ultra-pro').'</a>
	</p>
</div>';
			
			
		echo $message;	
		
		}
		
		
		
		
	}
	
	function admin_page() 
	{
		global $bookingultrapro; $bupcomplement;

		
		
		if (isset($_POST['update_settings']) &&  $_POST['reset_email_template']=='') {
            $this->update_settings();
        }
		
		if (isset($_POST['update_settings']) && $_POST['reset_email_template']=='yes' && $_POST['email_template']!='') {
           
			echo '<div class="updated"><p><strong>'.__('Email Template has been restored.','booking-ultra-pro').'</strong></p></div>';
        }
		
		
		if (isset($_POST['update_bup_slugs']) && $_POST['update_bup_slugs']=='bup_slugs')
		{
           $bookingultrapro->create_rewrite_rules();
          // flush_rewrite_rules();
			echo '<div class="updated"><p><strong>'.__('Rewrite Rules were Saved.','booking-ultra-pro').'</strong></p></div>';
        }
		

		
		
		
			
	?>
	
		<div class="wrap <?php echo $this->slug; ?>-admin"> 
            
            
            <div class="wrap bup-top-main-bar"> 
                
                
                 <table width="100%" border="0">
                  <tbody>
                    <tr>
                      <td width="10%"  rowspan="3">
                          
                          <a href="?page=bookingultra">
                          
                          <img width="100px" src="<?php echo bookingup_url?>admin/images/admin_icon.png"  /> </a>
                        
                      </td>
                      <td width="63%" >
                          <h2>BOOKING ULTRA PRO - <?php _e('The best solution for managing your time.','booking-ultra-pro')?><span class="bup-go-pro-topbutton"><a href="?page=bookingultra&tab=pro" class="button button-secondary bup-btn-red-pro" ><i class="uultra-icon-plus"></i>&nbsp;&nbsp; <?php _e('Why Go Pro?','booking-ultra-pro')?>
                </a></span></h2>
                        </td>
                        
                      <td width="27%" >
                          
                     </td>
                    </tr>
                    <tr>
                      <td><?php _e('This powerful plugin gives you even more control of what your members can see based on the different subscription levels you offer.','wp-users-pro')?></td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>                    
                        </td>
                      <td>&nbsp;</td>
                    </tr>
                  </tbody>
            </table>
                
            </div>
            

			<div class="<?php echo $this->slug; ?>-admin-contain">          
            
			
				<?php $this->include_tab_content(); ?>
				
				<div class="clear"></div>
				
			</div>
			
		</div>

	<?php }

}

$key = "buupadmin";
$this->{$key} = new BookingUltraAdmin();