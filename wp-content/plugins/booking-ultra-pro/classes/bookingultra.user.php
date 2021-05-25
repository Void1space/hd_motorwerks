<?php
class BookingUltraUser
{
	var $sys_prefix = 'bup';
	
	function __construct() 
	{
				
		$this->ini_module();
		
		add_action( 'wp_ajax_ubp_get_new_staff', array( &$this, 'ubp_get_new_staff' ));
		add_action( 'wp_ajax_ubp_get_staff_details_ajax', array( &$this, 'ubp_get_staff_details_ajax' ));
		add_action( 'wp_ajax_ubp_add_staff_confirm', array( &$this, 'ubp_add_staff_confirm' ));
		add_action( 'wp_ajax_ubp_add_client_confirm', array( &$this, 'ubp_add_client_confirm' ));
		add_action( 'wp_ajax_ubp_update_staff_services', array( &$this, 'ubp_update_staff_services' ));		
		add_action( 'wp_ajax_bup_autocomple_clients_tesearch', array( &$this, 'get_users_auto_complete' ));
		add_action( 'wp_ajax_bup_get_staff_list_admin_ajax', array( &$this, 'get_staff_list_admin_ajax' ));
		add_action( 'wp_ajax_bup_get_staff_details_admin', array( &$this, 'get_staff_details_admin_ajax' ));
		add_action( 'wp_ajax_bup_update_staff_admin', array( &$this, 'bup_update_staff_admin' ));
		add_action( 'wp_ajax_bup_delete_staff_admin', array( &$this, 'bup_delete_staff_admin' ));
		add_action( 'wp_ajax_bup_ajax_upload_avatar', array( &$this, 'bup_ajax_upload_avatar' ));
		add_action( 'wp_ajax_bup_crop_avatar_user_profile_image', array( &$this, 'bup_crop_avatar_user_profile_image' ));
		add_action( 'wp_ajax_bup_delete_user_avatar', array( &$this, 'delete_user_avatar' ));
		add_action( 'wp_ajax_bup_disconnect_user_gcal', array( &$this, 'disconnect_user_gcal' ));
		
		add_action( 'wp_ajax_bup_update_user_account_settings', array( &$this, 'update_user_account_settings' ));
		
		add_action( 'wp_ajax_bup_set_default_gcal_staff', array( &$this, 'set_default_google_calendar' ));


	}
	
	public function ini_module()
	{
		global $wpdb;
		
		   
		
	}
	
	public function get_user_info()
	{
		$current_user = wp_get_current_user();
		return $current_user;

		
	}
	
	public function disconnect_user_gcal()
	{
		global $wpdb, $bookingultrapro, $bupcomplement;	
		
		$staff_id = $_POST['user_id'];		
		delete_user_meta($staff_id, 'google_cal_access_token');	
		delete_user_meta($staff_id, 'google_calendar_default');		
		
		die();
		
	}
	
	
	
	public function ubp_get_new_staff()
	{	
	
		global $wpdb, $bookingultrapro, $bupcomplement;	
		
		
		$display = true;	
				
		if(!isset($bupcomplement))
		{
			//check for amount of staff members
			$total = $this->get_staff_members_total();				
			if($total!=0)
			{					
				$display = false;
			}			
		}
		
		
		$html = '';
		
		$html .= '<div class="bup-sect-adm-edit">';
		
		if($display)
		{
			$html .= '<p>'.__('Here you can add new staff members. Please fill in with the full name and email then click on the Add button.','booking-ultra-pro').'</p>';
		
		}
		
		$html .= '<div class="bup-edit-service-block">';
		
		
		
		if($display){
			
				$html .= '<div class="bup-field-separator"><label for="bup-box-title">'.__('Full Name','booking-ultra-pro').':</label><input type="text" name="staff_name" id="staff_name" class="ubp-common-textfields" /></div>';				
				
				$html .= '<div class="bup-field-separator"><label for="textfield">'.__('Email','booking-ultra-pro').':</label><input type="text" name="staff_email" id="staff_email" class="ubp-common-textfields" /></div>';					
				$html .= '<div class="bup-field-separator"><label for="textfield">'.__('Username','booking-ultra-pro').':</label><input type="text" name="staff_nick" id="staff_nick" class="ubp-common-textfields" /></div>';			
			
				$html .= '<div class="bup-field-separator" id="bup-err-message"></div>';	
		}else{
			
			$html .= __( "If you need to add more than one staff member, please consider upgrading your plugin. The lite version allows you to have only one Staff Member. ", 'bookingup' ).'<a href="https://bookingultrapro.com/compare-packages.html" target="_blank">Click here</a> to upgrade your plugin.';
			
		}
			
			
			$html .= '</div>';
		
		$html .= '</div>';
		
		
			
		echo $html ;		
		die();		
	
	}
	
	function get_staff_members_total()
	{
		global $bookingultrapro;
		$relation = "AND";
		$args= array('keyword' => $uultra_combined_search ,  'relation' => $relation,  'sortby' => 'ID', 'order' => 'DESC');
		$users = $this->get_staff_filtered($args);
		
		$total = $users['total'];
		if(!isset($users['total'])){$total=0;}
		
		return $total;
	}
	
	public function ubp_get_staff_details_ajax()
	{
		session_start();
		$staff_id = $_POST['staff_id']	;
		
		$_SESSION["current_staff_id"] =$staff_id ;		
		echo $this->ubp_get_staff_details($staff_id);
		die();
	
	}
	
	 public function getAppointmentsForFC( DateTime $start_date, DateTime $end_date, $staff_id = NULL )
    {
		
		global $wpdb, $bookingultrapro;
				
		$appointments_re = array();
		$staff_service_details = array();
		
		$time_format =  $bookingultrapro->service->get_time_format();
		
		$what_display_in_calendar =  $bookingultrapro->get_option('what_display_in_admin_calendar');
       		
		$sql =  'SELECT appo.*, usu.*, serv.*	 FROM ' . $wpdb->prefix . 'bup_bookings appo  ' ;				
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";	
		$sql .= " RIGHT JOIN ". $wpdb->prefix."bup_services serv ON (serv.service_id = appo.booking_service_id)";		
		$sql .= " WHERE (DATE(appo.booking_time_from) BETWEEN '".$start_date->format( 'Y-m-d' )."' AND  '".$end_date->format( 'Y-m-d' )."' ) AND appo.booking_staff_id = '".$staff_id."' AND serv.service_id = appo.booking_service_id AND  appo.booking_status= '1' ORDER BY appo.booking_id desc ";	
			
		$appointments = $wpdb->get_results($sql );
			
		
        foreach ( $appointments as  $appointment ) {
            $desc = '';
			
			$key = $appointment->booking_id;			
			$staff_service_details = $this->get_staff_service_rate( $staff_id, $appointment->service_id );			
			$appointment_capacity = $staff_service_details['capacity'];
			
			
			$availability_cap_groups = 0;
			
			$day_time_slot = $appointment->booking_time_from;
			$staff_time_slots = array();
			
			$booking_totals = array();
			$day=$appointment->booking_time_from;	
			$day_to=$appointment->booking_time_to;	
					
			$booking_totals = $bookingultrapro->service->get_total_bookings($staff_id, $appointment->service_id, $day, $day_to);
			
			$availability_cap_groups = $booking_totals['total_groups'];
			
			
			if($what_display_in_calendar==1 || $what_display_in_calendar==''){ //staff data
			
				$display_name = esc_html( $appointment->display_name );
				$display_email = esc_html( $appointment->user_email );
				
			}else{ //client data
			
				
				$client = get_user_by( 'id', $appointment->booking_user_id );			
				$display_name = esc_html( $client->display_name );
				$display_email = esc_html( $client->user_email );
				
			}		
			
			
            if ( $appointment_capacity == 1 ) 
			{
                				
				 $desc .= '<div>' . $appointment->service_title . '</div>'; 
                 $desc .= '<div>' . $display_name . '</div>';
				 $desc .= '<div>' . $display_email . '</div>';           	
				
				
            } else {
				
                $desc .= sprintf( '<div >%s %s</div>', __( 'Signed up', 'booking-ultra-pro' ), $availability_cap_groups);
				
                $desc .= sprintf( '<div >%s %s</div>', __( 'Capacity', 'booking-ultra-pro' ), $appointment_capacity );
				
				
            }
            
            $extra_prop = array('desc'     => $desc,
                                'staffId'  => $staff_id);

            $appointments_re[ $key ] = array(
                'id'       => $appointment->booking_id,
                'start'    => $appointment->booking_time_from	,
                'end'      => $appointment->booking_time_to,
               // 'title'    => $appointment->service_title ? esc_html( $appointment->service_title ) : __( 'Untitled', 'booking-ultra-pro' ),
                'title'    => ' ',
                
              //  'desc'     => $desc,
                'color'    =>   $appointment->service_color ?  $appointment->service_color : 'gray',
				'textColor'    =>   $appointment->service_font_color ?  $appointment->service_font_color : '#fff',				
                'staffId'  => $staff_id,
                'resourceId'  => $staff_id,
                'extendedProps'  => $extra_prop
            );
        }

        return $appointments_re;
    }
	
	public function get_working_hours($staff_id)
	{
		global $wpdb, $bookingultrapro;
		$hourly = array();
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'bup_staff_availability  WHERE avail_staff_id = "'.$staff_id.'" ' ;
		$rows = $wpdb->get_results($sql);
		
		if ( !empty( $rows ) )
		{
			foreach ( $rows as $row )
			{
				$hourly[$row->avail_day] = array(
                'day'       => $row->avail_day,
                'start_time'    => 	date('H:i', strtotime($row->avail_from)),
                'end_time'      =>  date('H:i', strtotime($row->avail_to))
            );
				
			}							
		
		}
		
		return $hourly;
	
	
	}
	
	
	
	public function ubp_update_staff_services()
	{
		$staff_id = $_POST['staff_id']	;
		
		$service_list = array();
		$modules = $_POST["service_list"]; 		
		
		//delete all services from this staff member
		if($staff_id!='')
		{
			$this->ubp_delete_staff_services($staff_id);
		}
		
		if($modules!="" && $staff_id!='')
		{
			$modules =rtrim($modules,"|");
			$service_list = explode("|", $modules);
			
						
			foreach($service_list as  $service)
			{
				$details = explode("-", $service);
				
			
				$service_id = $details[0];
				$service_price= $details[1];
				$service_qty= $details[2];
				
				//add in db				
				$this->ubp_assign_staff_services($staff_id, $service_id, $service_price, $service_qty);
			
			
			}
			
									
		}
		
		
		
		
		die();
	
	}
	
	function get_me_wphtml_editor($meta, $content)
	{
		// Turn on the output buffer
		ob_start();
		
		$editor_id = $meta;				
		$editor_settings = array('media_buttons' => false , 'textarea_rows' => 15 , 'teeny' =>true); 
							
					
		wp_editor( $content, $editor_id , $editor_settings);
		
		// Store the contents of the buffer in a variable
		$editor_contents = ob_get_clean();
		
		// Return the content you want to the calling function
		return $editor_contents;

	
	
	}
	
	
	
	public function ubp_delete_staff_services($staff_id)
	{
		global $wpdb;
		
		$sql = 'DELETE FROM ' . $wpdb->prefix . 'bup_service_rates  WHERE rate_staff_id="'.(int)$staff_id.'" ';
		$wpdb->query($sql);		
	}
	
	public function ubp_assign_staff_services($staff_id, $service_id, $service_price, $service_qty)
	{
		global $wpdb;
		
		
		$new_record = array(
						'rate_id'        => NULL,
						'rate_staff_id' => $staff_id,
						'rate_service_id' => $service_id, 
						'rate_price' => $service_price,
						'rate_capacity'   => $service_qty						
						
						
						
					);
					
		$wpdb->insert( $wpdb->prefix . 'bup_service_rates', $new_record, array( '%d', '%s', '%s', '%s', '%s'));
						
	}
	
	
	
	
	
	public function ubp_add_staff_confirm()
	{
		global $blog_id;
		$staff_name = $_POST['staff_name']	;
		$email = $_POST['staff_email'];
		$user_name = $_POST['staff_nick'];
		$convert = $_POST['bup_create_auto'];		
		$user_pass = wp_generate_password( 12, false);		
		
		/* Create account, update user meta */
		$sanitized_user_login = sanitize_user($user_name);
		
		if(email_exists($email))
		{			
			
			//$error .=__('<strong>ERROR:</strong> This email is already registered. Please choose another one.','booking-ultra-pro');
		
		}elseif(username_exists($user_name)){
			
			$error .=__('<strong>ERROR:</strong> This username is already registered. Please choose another one.','booking-ultra-pro');
		
		}elseif($staff_name=='' || $email=='' || $user_name==''){
			
			$error .=__('<strong>ERROR:</strong> All fields are mandatory.','booking-ultra-pro');		
		
		}
		
		if($error=='')
		{
			
			if(email_exists($email))
			{
				
				/* We Update Already user */
				$user = get_user_by( 'email', $email );
				$user_id = $user->ID;
				update_user_meta ($user_id, 'bup_is_staff_member',1);
				
				//check multisite				
				if ( is_multisite() ) 
				{					
					if ($user_id && !is_user_member_of_blog($user_id, $blog_id)) 
					{
						//Exist's but is not user to the current blog id
						$result = add_user_to_blog( $blog_id, $user_id, 'subscriber');

   					 }
		
		
				} 
				
			
			}else{
				
				/* We create the New user */
				$user_id = wp_create_user( $sanitized_user_login, $user_pass, $email);
				
				if($user_id)
				{
					update_user_meta ($user_id, 'bup_is_staff_member',1);
					wp_update_user( array('ID' => $user_id, 'display_name' => esc_attr($staff_name)) );
				
				}
				
			
			}
				
			
			
			echo $user_id;		
		
		}else{
			
			echo $error;		
		
		}
		
			
		
		die();
	
	}
	
	public function ubp_add_client_confirm()
	{
		$user_id = '';
		$client_name = $_POST['client_name']	;
		$client_last_name = $_POST['client_last_name'];
		$email = $_POST['client_email'];
		
		$user_name = strtolower($client_name.$this->genRandomString());		
		
		$user_pass = wp_generate_password( 12, false);		
		
		/* Create account, update user meta */
		$sanitized_user_login = sanitize_user($user_name);
		
		if(email_exists($email))
		{			
			
			$error .=__('<strong>ERROR:</strong> This email is already registered. Please choose another one.','booking-ultra-pro');
		
		}elseif(username_exists($user_name)){
			
			$error .=__('<strong>ERROR:</strong> This username is already registered. Please choose another one.','booking-ultra-pro');
		
		}elseif($client_name=='' || $email=='' || $client_last_name==''){
			
			$error .=__('<strong>ERROR:</strong> All fields are mandatory.','booking-ultra-pro');		
		
		}
		
		if($error=='')
		{			
			/* We create the New user */
			$user_id = wp_create_user( $sanitized_user_login, $user_pass, $email);
			
			if($user_id)
			{
				$display_name =$client_name.' '.$client_last_name ;
				$respon = $display_name.' ('.$email.')';
				wp_update_user( array('ID' => $user_id, 'display_name' => esc_attr($display_name)) );
			
			}
			
			$response = array('response' => 'OK', 'content' => $respon, 'user_id' => $user_id);	
		
		}else{
			
			$response = array('response' => 'ERROR', 'content' => $error, 'user_id' => $user_id);	
		
		}
		
		
		
		echo json_encode($response) ;
		
			
		
		die();
	
	}
	
	public function bup_update_staff_admin()
	{
		$staff_id = $_POST['staff_id']	;
		$staff_name = $_POST['display_name']	;
		$reg_telephone = $_POST['reg_telephone'];
		
		$email = $_POST['reg_email'];
		$email2 = $_POST['reg_email2'];
		
		
		if($email=='')
		{
			$error .=__('<strong>ERROR:</strong> Please input an email address.','booking-ultra-pro');			
		
				
		}elseif($staff_name==''){
			
			$error .=__('<strong>ERROR:</strong> Please input a Full Name.','booking-ultra-pro');		
		
		}
		
		if($email!=$email2)
		{
			if(email_exists($email))
			{
				$error .=__('<strong>ERROR:</strong> This email is already registered. Please choose another one.','booking-ultra-pro');
			
			}else{
				
				wp_update_user( array('ID' => $staff_id, 'user_email' => esc_attr($email)) );
				
			}
		
		}	
		
		if($error=='')
		{			
						
			if($staff_id)
			{
				update_user_meta ($staff_id, 'reg_telephone',$reg_telephone);
				update_user_meta ($staff_id, 'display_name',$staff_name);
				wp_update_user( array('ID' => $staff_id, 'display_name' => esc_attr($staff_name)) );
				delete_user_meta ($staff_id, 'bup_is_client'	);
				
				
			
			}
			
			echo __('<strong>Done!</strong>','booking-ultra-pro');			;		
		
		}else{
			
			echo $error;		
		
		}
		
			
		
		die();
	
	}
	
	public function bup_delete_staff_admin()
	{
		global $wpdb,  $bookingultrapro;
		
		require_once(ABSPATH. 'wp-admin/includes/user.php' );
		
		$html = '';		
		
		//close
		$user_to_delete = $_POST["staff_id"];
		
		if(!is_super_admin( $user_to_delete ))
		{
			if ( current_user_can( 'manage_options' ) ) 
			{
				//delete meta data		
				$sql = 'DELETE FROM ' . $wpdb->prefix . 'usermeta WHERE user_id = "'.$user_to_delete.'" ' ;			
				$wpdb->query( $sql );
				
				//delete availability
				$sql = 'DELETE FROM ' . $wpdb->prefix . 'bup_staff_availability WHERE avail_staff_id = "'.$user_to_delete.'" ' ;			
				$wpdb->query( $sql );
							
				//delete breaks
				$sql = 'DELETE FROM ' . $wpdb->prefix . 'bup_staff_availability_breaks WHERE break_staff_id = "'.$user_to_delete.'" ' ;			
				$wpdb->query( $sql );
				
				//delete rates
				$sql = 'DELETE FROM ' . $wpdb->prefix . 'bup_service_rates WHERE rate_staff_id = "'.$user_to_delete.'" ' ;			
				$wpdb->query( $sql );
				
				//delete user					
				wp_delete_user( $user_to_delete );	
				delete_user_meta ($user_to_delete, 'bup_is_staff_member'	);	
				delete_user_meta ($user_to_delete, 'bup_is_client'	);	
					
				$html  = $this->get_first_staff_on_list();
			
			}
			
			
		}else{
			
			delete_user_meta ($user_to_delete, 'bup_is_staff_member'	);	
			delete_user_meta ($user_to_delete, 'bup_is_client'	);
			$html  = $this->get_first_staff_on_list();			
				
			
		}
		echo $html;
		die();		
			
	}
		
	public function ubp_get_staff_details($staff_id)
	{
		global  $bookingultrapro, $bupcomplement, $bupultimate;
		
		
		$html = '';
		
		$html .= '<div class="bup-sect-adm-edit">';
		$html .= '<input type="hidden" value="'.$staff_id.'" id="staff_id" name="staff_id">';
		
		$html .= '<ul class="bup-details-staff-sections">';
		
		$html .='<li class="left_widget_customizer_li">';
			
		$html .='<div class="bup-staff-details-header" widget-id="1"><h3> '.__('Details','booking-ultra-pro').'<h3>';
				
		$html .='<span class="bup-widgets-icon-close-open" id="bup-widgets-icon-close-open-id-1"  widget-id="1" style="background-position: 0px 0px;"></span>';
		
		$html .= '</div>';
		
		$html .='<div id="bup-widget-adm-cont-id-1" class="bup-staff-details">';
		
		$html .='<span class="bup-action-staff-id">'.__('ID: ','booking-ultra-pro').' '.$staff_id.' </span>';
		
		$html .='<span class="bup-action-staff"><a href="#" id="ubp-staff-member-delete"  title="'.__('Delete','booking-ultra-pro').'" staff-id="'.$staff_id.'" ><i class="fa fa-trash-o"></i></a> </span>';
		
		$html .= $this->get_staff_personal_details($staff_id);
		$html .= '</div>';
		$html .='</li>';
		
		//account and backend		
		$html .='<li class="left_widget_customizer_li">';
		$html .='<div class="bup-staff-details-header"  widget-id="8"><h3> '.__('Account & Backend','booking-ultra-pro').'<h3>';
		
		$html .='<span class="bup-widgets-icon-close-open" id="bup-widgets-icon-close-open-id-8"  widget-id="8" style="background-position: 0px 0px;"></span>';
		
		$html .= '</div>';
		
		$html .='<div id="bup-widget-adm-cont-id-8" class="bup-staff-details" style=" display:none">';
		$html .=  $this->get_staff_backend_settings($staff_id);
		$html .= '</div>';
		$html .='</li>';
		
		
		$html .='<li class="left_widget_customizer_li">';
		$html .='<div class="bup-staff-details-header" widget-id="2" ><h3> '.__('Services','booking-ultra-pro').'<h3>';
		
		$html .='<span class="bup-widgets-icon-close-open" id="bup-widgets-icon-close-open-id-2"  widget-id="2" style="background-position: 0px 0px;"></span>';
		
		$html .= '</div>';
				
		$html .='<div id="bup-widget-adm-cont-id-2" class="bup-tabs-sections-staff-services bup-services-list-adm" style=" display:none">';
		$html .= $this->get_staff_services_admin($staff_id);
		$html .= '</div>';
		$html .='</li>';
		
		
		$html .='<li class="left_widget_customizer_li">';
		$html .='<div class="bup-staff-details-header"  widget-id="3"><h3> '.__('Schedule','booking-ultra-pro').'<h3>';
		
		$html .='<span class="bup-widgets-icon-close-open" id="bup-widgets-icon-close-open-id-3"  widget-id="3" style="background-position: 0px 0px;"></span>';
		
		$html .= '</div>';
		
		$html .='<div id="bup-widget-adm-cont-id-3" class="bup-tabs-sections-staff-services" style=" display:none">';
		$html .=  $bookingultrapro->service->get_business_staff_business_hours($staff_id);
		$html .= '</div>';
		$html .='</li>';
		
		$html .='<li class="left_widget_customizer_li">';
		$html .='<div class="bup-staff-details-header" widget-id="7"><h3> '.__('Special Schedule','booking-ultra-pro').'<h3>';
			
			$html .='<span class="bup-widgets-icon-close-open" id="bup-widgets-icon-close-open-id-7"  widget-id="7" style="background-position: 0px 0px;"></span>';
			
			$html .= '</div>';
			
			$html .='<div id="bup-widget-adm-cont-id-7" class="bup-tabs-sections-staff-services" style=" display:none">';
			
			if(isset($bupcomplement) && class_exists('BupComplementDayOff'))
			{
				$html .= $bupcomplement->dayoff->get_staff_special_schedule($staff_id);
			
			}else{
				
				$html .= __('Please consider upgrading your plugin if you need to set special rules for your schedule. This feature allows you to set your availability on a particular day in advance.','booking-ultra-pro');
				
			}
			
			$html .= '</div>';
			$html .='</li>';
		
		
		$html .='<li class="left_widget_customizer_li">';
		$html .='<div class="bup-staff-details-header" widget-id="4"><h3> '.__('Breaks','booking-ultra-pro').'<h3>';
		
		$html .='<span class="bup-widgets-icon-close-open" id="bup-widgets-icon-close-open-id-4"  widget-id="4" style="background-position: 0px 0px;"></span>';
		
		$html .= '</div>';
		
		$html .='<div id="bup-widget-adm-cont-id-4" class="bup-staff-break" style=" display:none">';
		
		$html .=  $bookingultrapro->breaks->get_staff_breaks($staff_id);
		$html .= '</div>';
		$html .='</li>';
		
		if(isset($bupultimate))
		{
		
			$html .='<li class="left_widget_customizer_li">';
			$html .='<div class="bup-staff-details-header" widget-id="6"><h3> '.__('Locations','booking-ultra-pro').'<h3>';
			
			$html .='<span class="bup-widgets-icon-close-open" id="bup-widgets-icon-close-open-id-6"  widget-id="6" style="background-position: 0px 0px;"></span>';
			
			$html .= '</div>';
			
			$html .='<div id="bup-widget-adm-cont-id-6" class="bup-tabs-sections-staff-services bup-services-list-adm" style=" display:none">';
			
			if(isset($bupcomplement))
			{
				$html .= $this->get_staff_locations_admin($staff_id);
			
			}else{
				
				$html .= __('Please consider upgrading your plugin if you need to manage multiple locations.','booking-ultra-pro');
				
			}
			
			$html .= '</div>';
			$html .='</li>';
		
		}
		
		
		
			$html .='<li class="left_widget_customizer_li">';
			$html .='<div class="bup-staff-details-header" widget-id="5"><h3> '.__('Days off','booking-ultra-pro').'<h3>';
			
			$html .='<span class="bup-widgets-icon-close-open" id="bup-widgets-icon-close-open-id-5"  widget-id="5" style="background-position: 0px 0px;"></span>';
			
			$html .= '</div>';
			
			$html .='<div id="bup-widget-adm-cont-id-5" class="bup-tabs-sections-staff-services" style=" display:none">';
			
			if(isset($bupcomplement) && class_exists('BupComplementDayOff'))
			{
				$html .= $bupcomplement->dayoff->get_staff_daysoff($staff_id);
			
			}else{
				
				$html .= __('Please consider upgrading your plugin if you need to add breaks.','booking-ultra-pro');
				
			}
			
			$html .= '</div>';
			$html .='</li>';
		
		
		
		
		
		$html .= '</ul>';
		
		$html .= '</div>';
			
		return $html ;		
			
	
	}
	
	
	//this returns the staff permissions and settings
	function get_staff_backend_setting_dropdown($settings, $setting_id)
	{
		$html ='';
		
		if(isset($settings[$setting_id]) && $settings[$setting_id]=='NO')
		{
			$selected_yes = '';
			$selected_no = 'selected="selected"';
			
		}else{
			
			$selected_yes = 'selected="selected"';
			$selected_no = '';
			
		}
		
		
		
		$html .= '<select name="'.$setting_id.'" size="1" id="'.$setting_id.'">
  					<option '.$selected_yes.' value="YES">'.__('YES','booking-ultra-pro').'</option>
					<option '.$selected_no.' value="NO">'.__('NO','booking-ultra-pro').'</option>
				</select>';
		
		return $html;
		
	}
	
	
	
	//this returns the staff permissions and settings
	function get_staff_backend_settings( $staff_id)
	{
		global $wpdb, $bookingultrapro;
		
		$settings = array();
		$settings = get_user_meta( $staff_id, 'bup_staff_acc_setting', true ); 
		
		if(!is_array($settings)){$settings== array();}
		
		$html ='';
		
				
		$html .='<div class="bup-profile-field" >';		
		$html .='<label class="bup-field-type" for="display_name"><span>'.__('Backend Access?','booking-ultra-pro').'</span></label>';
		$html .='<div class="bup-field-value" >'.$this->get_staff_backend_setting_dropdown($settings, "bup_per_backend_access").'</div>';		
		$html .= '</div>';
		
		$html .='<div class="bup-profile-field" >';		
		$html .='<label class="bup-field-type" for="display_name"><span>'.__('Can Update Picture?','booking-ultra-pro').'</span></label>';
		$html .='<div class="bup-field-value" >'.$this->get_staff_backend_setting_dropdown($settings, "bup_upload_picture").'</div>';		
		$html .= '</div>';
		
		$html .='<div class="bup-profile-field" >';		
		$html .='<label class="bup-field-type" for="display_name"><span>'.__('Can Reschedule Appointments?','booking-ultra-pro').'</span></label>';
		$html .='<div class="bup-field-value" >'.$this->get_staff_backend_setting_dropdown($settings, "bup_reschedule").'</div>';		
		$html .= '</div>';
		
		$html .='<div class="bup-profile-field" >';		
		$html .='<label class="bup-field-type" for="display_name"><span>'.__('Can Add Notes?','booking-ultra-pro').'</span></label>';
		$html .='<div class="bup-field-value" >'.$this->get_staff_backend_setting_dropdown($settings, "bup_add_notes").'</div>';		
		$html .= '</div>';
		
		$html .='<div class="bup-profile-field" >';		
		$html .='<label class="bup-field-type" for="display_name"><span>'.__('Update Booking Details?','booking-ultra-pro').'</span></label>';
		$html .='<div class="bup-field-value" >'.$this->get_staff_backend_setting_dropdown($settings, "bup_update_details").'</div>';		
		$html .= '</div>';
		
		$user_profile_bg_color = get_user_meta( $staff_id, 'bup_profile_bg_color', true ); 
		
		$html .='<div class="bup-field "><span id="bup-edit-details-message">&nbsp;</span></div>';
		
		$html .='<h2>'.__("Profile Customization",'booking-ultra-pro').'</h2>';
		
		$html .='<div class="bup-profile-field" >';		
		$html .='<label class="bup-field-type" for="display_name"><span>'.__("Header Color",'booking-ultra-pro').'</span></label>';
		$html .='<div class="bup-field-value" ><input name="bup-profile-bg-color" type="text" id="bup-profile-bg-color" value="'.$user_profile_bg_color.'" class="color-picker" data-default-color=""/></div>';		
		$html .= '</div>';
		
		$html .=' <p class="submit">
	<button name="ubp-save-acc-settings-staff" id="ubp-save-acc-settings-staff" class="bup-button-submit-changes" ubp-staff-id= "'.$staff_id.'">'.__('Save Settings','booking-ultra-pro').'	</button>&nbsp; <span id="bup-loading-animation-acc-setting-staff">  <img src="'.bookingup_url.'admin/images/loaderB16.gif" width="16" height="16" /> &nbsp; '.__('Please wait ...','booking-ultra-pro').' </span>
	
	</p>';
	
		$html .= '<p><i class="fa fa-info-circle"></i> '.__('You can use the following button to send a password reset link to this staff member. The reset will allow the staff meber to login and manage their appointments','booking-ultra-pro').'</p>';
			
		$html .=' <p class="submit">
		<button name="ubp-save-acc-send-reset-link-staff" id="ubp-save-acc-send-reset-link-staff" class="bup-button-submit-changes" ubp-staff-id= "'.$staff_id.'"><i class="fa fa-refresh "></i> '.__('Send Password Reset Link','booking-ultra-pro').'	</button>&nbsp; <span id="bup-loading-animation-acc-resetlink-staff">  <img src="'.bookingup_url.'admin/images/loaderB16.gif" width="16" height="16" /> &nbsp; '.__('Please wait ...','booking-ultra-pro').' </span> <p id="bup-acc-resetlink-staff-message">   </p>
		
		</p>';
		
		$reset_link_page = $bookingultrapro->get_option("bup_password_reset_page");
		
		if($reset_link_page=='')
		{	
			$html .= '<p class="bup-backend-info-tool">'.'<i class="fa fa-info-circle"></i> <strong>'.__("You haven't set a password reset page, this is very imporant. Click on Staff & Client Account link and set a page for the reset password shortcode. ",'booking-ultra-pro').'</strong>'.'</p>';
		
		}
		
		
		
		
		
				
		return $html;
		
	}
	
	public function has_account_permision($staff_id, $setting_id)
	{
		global $wpdb, $bookingultrapro;
		
		$settings = array();
		$settings = get_user_meta( $staff_id, 'bup_staff_acc_setting', true ); 
		
		if(!is_array($settings)){$settings== array();}
		
		if(isset($settings[$setting_id]) && $settings[$setting_id]=='NO')
		{
			return false;
			
		}else{
			
			return true;
			
		}		
		
	}
	
	public function update_user_account_settings()
	{
		global $wpdb, $bookingultrapro;
		
		
		$bup_per_backend_access = $_POST['bup_per_backend_access'];		
		$bup_upload_picture = $_POST['bup_upload_picture'];
		$bup_reschedule = $_POST['bup_reschedule'];
		$bup_add_notes = $_POST['bup_add_notes'];
		$bup_update_details = $_POST['bup_update_details'];		
		$bup_profile_bg_color = $_POST['bup_profile_bg_color'];
		
		
		$staff_id = $_POST['staff_id'];
		
		$settings = array('bup_per_backend_access' =>$bup_per_backend_access, 
						  'bup_upload_picture' =>$bup_upload_picture,
						  'bup_reschedule' =>$bup_reschedule,
						  'bup_add_notes' =>$bup_add_notes,
						  'bup_update_details' =>$bup_update_details,
						   'bup_profile_bg_color' =>$bup_profile_bg_color);		
		update_user_meta($staff_id, 'bup_staff_acc_setting', $settings);
		
		if(!isset($_POST['bup_profile_bg_color']) || $_POST['bup_profile_bg_color']==''){			
			$bup_profile_bg_color = '';			
		}
		
		update_user_meta($staff_id, 'bup_profile_bg_color', $bup_profile_bg_color);
		
		die();
	
	
	}
	
	//this returns the service for a particular user, if it has not been set we will take the defaul.	
	function get_staff_personal_details( $staff_id )
	{
		global $wpdb, $bookingultrapro, $bupcomplement;		
		
		$user = get_user_by( 'id', $staff_id );
		
		$html = '';
		
		
		$html .='<div class="bup-profile-field" >';		
		$html .='<label class="bup-field-type" for="display_name"><span>'.$bookingultrapro->userpanel->get_user_pic( $staff_id, 80, 'avatar', null, null, false).' <div class="bup-div-for-avatar-upload"> <a href="?page=bookingultra&tab=users&avatar='.$staff_id.'"><button name="bup-button-change-avatar" id="bup-button-change-avatar" class="bup-button-change-avatar" type="link"><span><i class="fa fa-camera"></i></span>'.__('Update Pic','booking-ultra-pro').'	</button></a></div></span>
		
		</label>';
		
		$html .='<div class="bup-field-value" ></div>';		
		$html .= '</div>';
		
		$html .='<div class="bup-profile-field" >';		
		$html .='<label class="bup-field-type" for="display_name"><span>'.__('Full Name','booking-ultra-pro').'</span></label>';
		$html .='<div class="bup-field-value" ><input type="text" class=" bup-input " name="display_name" id="reg_display_name" value="'.$user->display_name.'" title="'.__('Your Full Name','booking-ultra-pro').'" ></div>';		
		$html .= '</div>';
		
		$html .='<div class="bup-profile-field" >';		
		$html .='<label class="bup-field-type" for="display_name"><span>'.__('Phone','booking-ultra-pro').'</span></label>';
		$html .='<div class="bup-field-value" ><input type="text" class=" bup-input " name="reg_telephone" id="reg_telephone" value="'.$bookingultrapro->bup_get_user_meta($staff_id, 'reg_telephone').'" title="'.__('Your Phone Number','booking-ultra-pro').'" ></div>';		
		$html .= '</div>';
		
		$html .='<div class="bup-profile-field" >';		
		$html .='<label class="bup-field-type" for="display_name"><span>'.__('E-mail','booking-ultra-pro').'</span></label>';
		$html .='<div class="bup-field-value" > <input type="text" class=" bup-input " name="reg_email" id="reg_email" value="'.$user->user_email.'" title="'.__('Your Email','booking-ultra-pro').'" > <input type="hidden" class=" bup-input " name="reg_email2" id="reg_email2" value="'.$user->user_email.'"  ></div>';		
		$html .= '</div>';
		
		
		$html .= '<div class="bup-field ">';
		$html .= '				<label class="bup-field-type "><button name="bup-btn-user-details-confirm" id="bup-btn-user-details-confirm" class="bup-button-submit-changes">'.__('Submit','booking-ultra-pro').'	</button></label>';
		
	
		$html .= '<div class="bup-field-value">
						    <input type="hidden" name="bup-register-form" value="bup-register-form">								
							
							
				   </div>';
		$html .= '</div>';
		
		$html .= '<div class="bup-field "><span id="bup-edit-details-message">&nbsp;</span>';
		$html .= '</div>';
		
		
		$html .= '<h2>'.__('Google Calendar','booking-ultra-pro').'</h2>';
		
		$html .= '<div class="bup-field ">';
		
			if(isset($bupcomplement->googlecalendar))
			{
				//do we have a calendar list?				
				$html .= $this->get_user_auth_status_staff($staff_id);					
				
				$html .= $bupcomplement->googlecalendar->get_user_auth_status($staff_id);			
				
			
			}else{			
				
				$html .= '<p>'.__('Please consider upgrading your plugin if you wish to use Google Calendar features.','booking-ultra-pro').'</p>';
				
			
			}
		
		
		
		$html .= '</div>';
		
		
		return $html;
	
	
	}
	
	//this is used on admin dashboard
	public function get_user_auth_status_staff($staff_id)
	{
		global $bookingultrapro;
		
		$html = '';
		
		$client_id = $bookingultrapro->get_option('google_calendar_client_id');
		$client_secret = $bookingultrapro->get_option('google_calendar_client_secret');
		
		//get client access token		
		$accessToken = $bookingultrapro->bup_get_user_meta($staff_id, 'google_cal_access_token');
		
		if($accessToken=='') //get auth url
		{
			if($client_id=='' || $client_secret=='')
			{				
				$html = "<p>".__('Please set client ID and client Secret!','booking-ultra-pro')."</p>";
				
			
			}else{
				
				//$auth_url = $this->get_auth_url_staff();			
				//$html = "<p><a href='$auth_url'>".__('Connect Me!','booking-ultra-pro')."</a></p>";
				
			}	
		
		}else{
			
			if($client_id=='' || $client_secret=='')
			{				
				$html = "<p>".__('Please set client ID and client Secret!','booking-ultra-pro')."</p>";
				
			
			}else{	
								
				$html .= "<p>".__('Select Your Calendar:','booking-ultra-pro')."</p>";
				$html .= "<p>".$this->get_calendar_list_drop($staff_id)."</p>";
					
				$html .= '<p> <button name="bup-backenedb-set-gacal-adm" id="bup-backenedb-set-gacal-adm" class="bup-button-submit-changes" staff-id="'.$staff_id.'">'.__('SET CALENDAR','booking-ultra-pro').'	</button> </p>';				
				$html .= "<p id='bup-gcal-message3'>&nbsp;</p>";			
				
				$google_calendar_default = $bookingultrapro->bup_get_user_meta($staff_id, 'google_calendar_default');
				
				if($google_calendar_default=='')
				{
					$html .= "<p id='bup-gcal-message1' ><strong class='bup-backend-info-tool-warning'>".__("IMPORTANT: You haven't set a calendar, yet.",'booking-ultra-pro')."</strong></p>";
					
					$html .= "<p class='bup-backend-info-tool' id='bup-gcal-message2'><i class='fa fa-info-circle'></i><strong>&nbsp;".__("If you don't set a calendar the primary calendar will be used by default. ",'booking-ultra-pro')."</strong></p>";
				
				}else{
					
					$html .= "<p class='bup-backend-info-tool' id='bup-gcal-message44'><i class='fa fa-info-circle'></i><strong>&nbsp;".__("If you don't see your new calendars, plase disconnect and connect again. ",'booking-ultra-pro')."</strong></p>";
					
					
				
				}							
			
			}
			
			
		}		
		
		return $html;
		
			
	}
	
	public function set_default_google_calendar()
	{
		
		global $wpdb, $bookingultrapro;
		
		$staff_id =$_POST['staff_id'];		
		$google_calendar = $_POST['google_calendar'];		
		update_user_meta ($staff_id, 'google_calendar_default', $google_calendar);		
		
		$html =__("Your calendar has been set! ", 'booking-ultra-pro');
		
		echo $html;
		
		die();
	
	}
	
	function get_calendar_list_drop($staff_id)	
	{
		global $bookingultrapro;
		
		$html = '<select name="bup_staff_calendar_list" size="1" id="bup_staff_calendar_list">';
		
		//display calendars list				
		$google_calendar_list = $bookingultrapro->bup_get_user_meta($staff_id, 'google_calendar_list');
		$google_calendar_default = $bookingultrapro->bup_get_user_meta($staff_id, 'google_calendar_default');
		
		 foreach ($google_calendar_list as $calendar) 
		 {
			 $sel =  '';
			 if($calendar['id']==$google_calendar_default){$sel =  'selected="selected"';}
			 
 			$html .= '<option value="'.$calendar['id'].'" '.$sel.'>'.$calendar['summary'].'</option>'; 		 			   
			   
    	 }
		 
		 $html .= '</select>';
		 
		 if(empty($google_calendar_list))
		 {
		 
			 $html .= "<p class='bup-backend-info-tool' id='bup-gcal-message2'><i class='fa fa-info-circle'></i><strong>&nbsp;".__("If the calendars list is empty, please disconnect and connect again. ",'booking-ultra-pro')."</strong></p>";
		 
		  }
				
				
		return $html;
	
	}
	
	//this returns the service for a particular user, if it has not been set we will take the defaul.	
	function get_staff_service_rate( $staff_id, $service_id )
	{
		global $wpdb, $bookingultrapro;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'bup_service_rates WHERE rate_service_id =  "'.$service_id.'" AND	rate_staff_id= "'.$staff_id.'" ' ;			
				
		$res = $wpdb->get_results($sql);
		
				
		$ret = array();
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				$ret = array('price'=>$row->rate_price, 'capacity'=>$row->rate_capacity);			
			
			}
			
		}else{
			
			//we need to get the default values for this service
			$serv = $bookingultrapro->service->get_one_service($service_id);
			
			$ret = array('price'=>$serv->service_price, 'capacity'=>$serv->service_capacity);
		}
		
		return $ret;
	
		
	}
	
	//returns true or false if the service is offered by the staff member	
	function staff_offer_service( $staff_id, $service_id )
	{
		global $wpdb, $bookingultrapro;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'bup_service_rates WHERE rate_service_id =  "'.$service_id.'" AND	rate_staff_id= "'.$staff_id.'" ' ;			
				
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				return true			;
			
			}
			
		}else{
			
			return false;
		}
	
	}
	
	//returns true or false if the offers some of the services within this category	
	function staff_offer_this_category( $staff_id, $category_id )
	{
		global $wpdb, $bookingultrapro;
		
			
		$sql = ' SELECT cate.*,sercvate.*, servrate.*  FROM ' . $wpdb->prefix.$this->sys_prefix.'_categories cate ' ;		
		$sql .= "RIGHT JOIN ".$wpdb->prefix .$this->sys_prefix."_services sercvate ON (sercvate.service_category_id = '".$category_id."')";
		
		$sql .= "RIGHT JOIN ".$wpdb->prefix ."bup_service_rates servrate ON (servrate.rate_service_id = sercvate.service_id )";
		
		
		$sql .= ' WHERE servrate.rate_service_id = sercvate.service_id AND servrate.rate_staff_id = "'.$staff_id.'" AND sercvate.service_category_id = "'.$category_id.'"' ;	
		//echo $sql;				
					
				
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				return true			;
			
			}
			
		}else{
			
			return false;
		}
	
	}
	
	//returns true if the staff works on this location
	function staff_works_in_location( $staff_id, $location_id )
	{
		global $wpdb, $bookingultrapro;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'bup_filter_staff WHERE fstaff_location_id =  "'.$location_id.'" AND	fstaff_staff_id= "'.$staff_id.'" ' ;			
				
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				return true			;
			
			}
			
		}else{
			
			return false;
		}
	
	}
	
	function get_staff_locations_admin( $staff_id )
	{
		global $wpdb, $bookingultrapro, $bup_filter;

		
		$html = '';
		
		//get locations
		$locations_list = $bup_filter->get_all(); 
				
		if ( !empty( $locations_list ) )
		{
				
			$html .='<ul>';
					
			foreach ( $locations_list as $location )
			{
											
						//print_r($serv_data);
						
						$checked_service = 'checked="checked"';
						$disable_service = '';
						if(!$this->staff_works_in_location($staff_id, $location->filter_id))	
						{
							 $checked_service = '';
							 $disable_service = 'disabled="disabled"'; 
						}		
						
						$html .='<li>';					
											
						
						$html .='<input type="checkbox" class="ubp-location-checked ubp-service-cate" value="'.$location->filter_id.'" name="bup-locations[]" data-location-id="'.$location->filter_id.'" id="bup-location-'.$location->filter_id.'" '. $checked_service.'><label for="bup-location-'.$location->filter_id.'"><span></span>'.$location->filter_name.'</label>';									
						
						
						$html .='</li>';
					
				}			
					
					
					$html .='</ul>'; //end categories
					
				
			
			$html .=' <p> <button name="bup-admin-edit-staff-service-save" id="bup-admin-edit-staff-location-save" class="bup-button-submit-changes" ubp-staff-id= "'.$staff_id.'">'.__('Save Changes','booking-ultra-pro').'</button>&nbsp; <span id="bup-loading-animation-services">  </span></p>';
			
			
		}	
		
		
		return $html;		
	
	}
	
	function get_staff_services_admin( $staff_id )
	{
		global $wpdb, $bookingultrapro;

		
		$html = '';
		
		$cate_list = $bookingultrapro->service->get_all_categories(); 
		
		if ( !empty( $cate_list ) )
		{		
		
			foreach ( $cate_list as $cate )
			{
				$html .='<div class="bup-serv-category-title">';
				
				
				    $html .='<div class="bup-col1">';					
						$html .='<input type="checkbox" class="ubp-cate-service-checked ubp-service-cate-parent" value="'.$cate->cate_id.'" name="bup-cate[]" data-category-id="'.$cate->cate_id.'" id="bup-cate-'.$cate->cate_id.'"><label for="bup-cate-'.$cate->cate_id.'"><span></span>'.$cate->cate_name.'</label>';					
					$html .='</div>';	
					
					
					$html .='<div class="bup-col2">'.__('Price','booking-ultra-pro').''.'</div>';
					$html .='<div class="bup-col3">'.__('Capacity','booking-ultra-pro').''.'</div>';
					
					
				$html .='</div>';
				
				//get services
				
				$service_list = $bookingultrapro->service->get_all_services($cate->cate_id); 
				
				if ( !empty( $service_list ) )
				{
				
					$html .='<ul>';
					
					foreach ( $service_list as $service )
					{
						//get service data						
						$serv_data = $this->get_staff_service_rate($staff_id, $service->service_id);
						
						//print_r($serv_data);
						
						$checked_service = 'checked="checked"';
						$disable_service = '';
						if(!$this->staff_offer_service($staff_id, $service->service_id))	
						{
							 $checked_service = '';
							 $disable_service = 'disabled="disabled"'; 
						}		
						
						$html .='<li>';
						
						$html .='<div class="bup-services-left">';						
						
						$html .='<input type="checkbox" class="ubp-cate-service-checked ubp-service-cate bup-service-cate-'.$cate->cate_id.'" value="'.$service->service_id.'" name="bup-service[]" data-category-id="'.$cate->cate_id.'" id="bup-service-'.$service->service_id.'" '. $checked_service.'><label for="bup-service-'.$service->service_id.'"><span></span>'.$service->service_title.'</label>';	
						
						$html .='</div>';
						
										
						$html .='<div class="bup-services-right">';
						$html .='<input type="text" value="'.$serv_data['price'].'" name="price['.$service->service_id.']" class="bup-price-box" id="bup-price-'.$service->service_id.'" '.$disable_service.'>';
						
						
						$html .='</div>';
						
						$html .='<div class="bup-services-right">';
						$html .='<input type="number" value="'.$serv_data['capacity'].'" name="capacity['.$service->service_id.']" min="1" class="bup-price-box" id="bup-qty-'.$service->service_id.'" '.$disable_service.'>';
						
						$html .='</div>';
						
						
						
						
						$html .'<div style="border-bottom: 1px dotted black; overflow: hidden; padding-top: 15px;"></div>';
						
						$html .='</li>';
					
					}			
					
					
					$html .='</ul>'; //end categories
					
				
				}
						
			
			}		
			
			
			$html .=' <p> <button name="bup-admin-edit-staff-service-save" id="bup-admin-edit-staff-service-save" class="bup-button-submit-changes" ubp-staff-id= "'.$staff_id.'">'.__('Save Changes','booking-ultra-pro').'</button>&nbsp; <span id="bup-loading-animation-services">  </span></p>';
			
			
		}	
		
		
		return $html;		
	
	}
	
	function get_staff_member($staff_id)
	{
		 global $wpdb,$blog_id, $wp_query;	
		 
		$args = array( 	
						
			'meta_key' => 'bup_is_staff_member',                    
			'meta_value' => 1,                  
			'meta_compare' => '=',  
			'count_total' => true,   


			);		

		$user_query = new WP_User_Query( $args );
		$users= $user_query->get_results();
		
		
		if (!empty($users))
		{
			
			foreach($users as $user) 
			{
				if($user->ID==$staff_id)
				{
				
					return $user;
				}			
				
				
			}				
		
		}
		
		return $users;
	
	}
	
	//get all staf for FULL Calendar		
	function get_staff_list_fc($location_id = NULL)
	{
		 global $wpdb,$blog_id, $wp_query;	
		 
		 
		if($location_id=='' || $location_id=='undefined' )
		{
		 
			$args = array( 	
							
				'meta_key' => 'bup_is_staff_member',                    
				'meta_value' => 1,                  
				'meta_compare' => '=',  
				'count_total' => true,   
	
	
				);			
	
			 // Create the WP_User_Query object
			$user_query = new WP_User_Query( $args );
			$users= $user_query->get_results();			
		
		}else{			
			
			$sql =  "SELECT  usu.*, staff_location.* 	" ;		
			$sql .= " FROM " . $wpdb->users . " usu ";				
			$sql .= " RIGHT JOIN ". $wpdb->prefix."bup_filter_staff staff_location ON (staff_location.	fstaff_staff_id = usu.ID)";		
					
			$sql .= " WHERE staff_location.	fstaff_staff_id = usu.ID AND  staff_location.		fstaff_location_id  = '".$location_id."'  ";
			
			$users = $wpdb->get_results($sql);		
		
		}
		
		
		return $users;
	
	}
    
    function get_staff_list_calendar_bar( $service_id=null )
	{
		 global $wpdb, $wp_query;	
		 
		$args = array( 	
						
			'meta_key' => 'bup_is_staff_member',                    
			'meta_value' => 1,                  
			'meta_compare' => '=',  
			'count_total' => true,   


			);
			
			
		if(isset($_GET["bup-staff-calendar"]))
		{
			$bup_staff_calendar = $_GET["bup-staff-calendar"];		
		}
		

		 // Create the WP_User_Query object
		$user_query = new WP_User_Query( $args );
		$users= $user_query->get_results();
		
		$selected ='';

		
		$count = 0;
		
		//$html = '';
		
		$htm = '<ul> ';		
		
        
        
         $htm .= '<li uuid="" class="bupro-staff-cal-list bupro-staff-noselect buu-all" >';
                $htm .= '<a href="#" uuid="">';

                $htm .= '<div class="bupro-st-photo" ><span><i class="fa fa-users "></i></span></div>';    
                $htm .= '<div class="bupro-st-name">'.__('All', 'booking-ultra-pro').'</div>';
                $htm .= '</a>';                
       $htm .= '</li>';
        
        
		if (!empty($users))
		{
			
			foreach($users as $user) 
			{
                $staff_id = $user->ID;
				
				$selected = '';				
				if($bup_staff_calendar==$user->ID){$selected = 'selected="selected"';}
                
                 $htm .= '<li data-li-staff_id="'.$user->ID.'" class="bupro-staff-cal-list bupro-staff-noselect" >';
                    $htm .= '<a href="#" data-staff_id="'.$user->ID.'" staff_id="'.$user->ID.'">';

                    $htm .= '<div class="bupro-st-photo" >'.$this->get_user_pic( $staff_id, 60, 'avatar', null, null, false).'</div>';    
                    $htm .= '<div class="bupro-st-name">'.$user->display_name.'</div>';
                    $htm .= '</a>';
                
                 $htm .= '</li>';
				
				
               
				
				
				
			}
			
		
		}
		
		$htm .= '</ul>';
		
		return $htm;
	
	}
	
	function get_staff_list_calendar_filter( $service_id=null )
	{
		 global $wpdb, $wp_query;	
		 
		$args = array( 	
						
			'meta_key' => 'bup_is_staff_member',                    
			'meta_value' => 1,                  
			'meta_compare' => '=',  
			'count_total' => true,   


			);
			
			
		if(isset($_GET["bup-staff-calendar"]))
		{
			$bup_staff_calendar = $_GET["bup-staff-calendar"];		
		}
		

		 // Create the WP_User_Query object
		$user_query = new WP_User_Query( $args );
		$users= $user_query->get_results();
		
		$selected ='';

		
		$count = 0;
		
		//$html = '';
		
		$htm = '<select id="bup-staff-calendar" name="bup-staff-calendar"> ';		
		$htm .= '<option value="" selected="selected" >'.__('All Staff Members', 'booking-ultra-pro').'</option>';
				
		if (!empty($users))
		{
			
			foreach($users as $user) 
			{
				
				$selected = '';				
				if($bup_staff_calendar==$user->ID){$selected = 'selected="selected"';}
				
				$htm .= '<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.'</option>';
				
				
				
			}
			
		
		}
		
		$htm .= '</select>';
		
		return $htm;
	
	}
	
	function get_not_staff_users_to_convert()
	{
		 global $wpdb,$blog_id, $wp_query;	
		 
		
		$args = array( 	
						
			'meta_key' => 'bup_is_staff_member',                    
			'meta_value' => '1',                  
			'meta_compare' => '!=',  
			'count_total' => true,   


			);
		

		// Create the WP_User_Query object
		$user_query = new WP_User_Query( $args );
		$users= $user_query->get_results();	
		
		
		
		
		$selected ='';

		
		$count = 0;
		
		$html = '';
		
		$html .= '<select name="bup-staff" id="bup-staff">';
		$html .= '<option value="" selected="selected" >'.__('Select User', 'booking-ultra-pro').'</option>';
		
		if (!empty($users))
		{
			
			foreach($users as $user) 
			{
				
		
				$html .= '<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.'</option>';
				
				
				
			}
			$html .= '</select>';
		
		
		
					
		
		}
		
		return $html;
	
	}
	
	function get_staff_list_front( $location_id=null )
	{
		 global $wpdb,$blog_id, $wp_query;	
		 
		
		if($location_id=='')
		{
			$args = array( 	
						
			'meta_key' => 'bup_is_staff_member',                    
			'meta_value' => 1,                  
			'meta_compare' => '=',  
			'count_total' => true,   


			);
		

			 // Create the WP_User_Query object
			$user_query = new WP_User_Query( $args );
			$users= $user_query->get_results();
		
		}else{
		
		
			$sql = ' SELECT  user.*, staff_location.*  FROM ' . $wpdb->users . '  user ' ;		
			
			$sql .= " RIGHT JOIN ". $wpdb->prefix."bup_filter_staff staff_location ON (staff_location.fstaff_staff_id = user.ID)";
			
			$sql .= " WHERE staff_location.fstaff_staff_id = user.ID AND  staff_location.fstaff_location_id = '".$location_id."' " ;					
			$sql .= ' ORDER BY user.display_name ASC  ' ;
			$users = $wpdb->get_results($sql);
			
		}
		
		
		
		
		
		$selected ='';

		
		$count = 0;
		
		$html = '';
		
		$html .= '<select name="bup-staff" id="bup-staff">';
		$html .= '<option value="" selected="selected" >'.__('Any', 'booking-ultra-pro').'</option>';
		
		if (!empty($users))
		{
			
			foreach($users as $user) 
			{
				
		
				$html .= '<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.'</option>';
				
				
				
			}
			$html .= '</select>';
		
		
		
					
		
		}
		
		return $html;
	
	}
	
	
	function get_staff_filtered( $args )
	{

        global $wpdb,$blog_id, $wp_query;
		
		$bup_meta = '';			
		
		extract($args);		
		$memberlist_verified = 1;		
		$blog_id = get_current_blog_id();

		$paged = (!empty($_GET['paged'])) ? $_GET['paged'] : 1;	
		
		if($per_page!=''){
			
			$offset = ( ($paged -1) * $per_page);			
		}
		
		
		$query['search_columns']= array('display_name', 'user_email');					
		$query['meta_query'] = array('relation' => strtoupper($relation) );	
		
		$total_pages = '';
	  	
				
		if ($bup_meta)
		{
			
			$query['meta_query'][] = array(
					'key' => $uultra_meta,
					'value' => $keyword,
					'compare' => 'LIKE'
				);				
		}
		
		$query['meta_query'][] = array(
					'key' => 'bup_is_staff_member',
					'value' => 1,
					'compare' => '='
		);			
		
				
				
    	if ($sortby) $query['orderby'] = $sortby;			
	    if ($order) $query['order'] = strtoupper($order); // asc to ASC
			
		/** QUERY ARGS END **/
			
		$query['number'] = $per_page;
		$query['offset'] = $offset;
			
		/* Search mode */
		if ( ( isset($_GET['bup_search']) && !empty($_GET['bup_search']) ) || count($query['meta_query']) > 1 )
		{
			$count_args = array_merge($query, array('number'=>10000));
			unset($count_args['offset']);
			$user_count_query = new WP_User_Query($count_args);
						
		}

		if ($per_page) 
		{			
		
			/* Get Total Users */
			if ( ( isset($_GET['bup_search']) && !empty($_GET['bup_search']) ) || count($query['meta_query']) > 1 )
			{
				$user_count = $user_count_query->get_results();								
				$total_users = $user_count ? count($user_count) : 1;
				
			} else {
				
			
				$result = count_users();
				$total_users = $result['total_users'];
				
			}
			
			$total_pages = ceil($total_users / $per_page);
		
		}
		
		$user_count = $user_count_query->get_results();								
		$total_users = $user_count ? count($user_count) : 1;
		
		$wp_user_query = new WP_User_Query($query);
		
	
		if (! empty( $wp_user_query->results )) 
		{
			$arr['total'] = $total_users;
			$arr['paginate'] = paginate_links( array(
					'base'         => @add_query_arg('paged','%#%'),
					'total'        => $total_pages,
					'current'      => $paged,
					'show_all'     => false,
					'end_size'     => 1,
					'mid_size'     => 2,
					'prev_next'    => true,
					'prev_text'    => __('« Previous','booking-ultra-pro'),
					'next_text'    => __('Next »','booking-ultra-pro'),
					'type'         => 'plain',
				));
			$arr['users'] = $wp_user_query->results;
		}
		
				
		return $arr;
		
		
	}
	
	function get_staff_details_admin_ajax()
	{
		global $wpdb, $bookingultrapro;
		
		$html='';
		
		$staff_id = $_POST['staff_id'];		
		$html .= $this->ubp_get_staff_details($staff_id);
					
		
		echo $html;
		die();
		
	}
	
	function get_first_staff_on_list()
	{
		global $wpdb, $bookingultrapro;
		
		$relation = "AND";
		$howmany = '1';
		$uultra_combined_search = '';
		$uultra_meta = '';
		$args= array('per_page' => $howmany, 'keyword' => $uultra_combined_search , 'bup_meta' => $uultra_meta,  'relation' => $relation,  'sortby' => 'ID', 'order' => 'DESC');
		$users = $bookingultrapro->userpanel->get_staff_filtered($args);
		
		$c_c =0;
		$user_id = '';
		
		if(!empty($users['users']))
		{
			foreach($users['users'] as $user) 
			{
					
					$user_id = $user->ID;				
					$c_c++;				
					if($c_c==1){return $user_id;}
			}
		}
	}
	
	function get_staff_list_admin_ajax()
	{
		global $wpdb, $bookingultrapro;
		
		$html='';
		$uultra_combined_search = '';
		
		$relation = "AND";
		$args= array('keyword' => $uultra_combined_search ,  'relation' => $relation,  'sortby' => 'ID', 'order' => 'DESC');
		$users = $bookingultrapro->userpanel->get_staff_filtered($args);
		
		$total = $users['total'];
		
		if (empty($users['users']))
		{
			$total = 0;		
		
		}
		
			
		$html .='<div class="bup-staff-list-act">';
		$html .='<h1>'.__('Staff','booking-ultra-pro').'('.$total.')</h1>';
		$html .='<span class="bup-add-staff"><a href="#" id="ubp-add-staff-btn" title="'.__('Add New Staff Member','booking-ultra-pro').'" ><i class="fa fa-plus"></i></a></span>';
		$html .='</div>';
		
		if (!empty($users['users']))
		{
			$html .='<ul>';
			$c_c =0;
			
			foreach($users['users'] as $user) {
				
				$user_id = $user->ID;
				
				$c_c++;
				
				if($c_c==1){$html .='<input type="hidden" id="bup-first-staff-id" value="'.$user_id.'">';}
			
				$html .='<li>';
				$html .='<a href="#" id="bup-staff-load" class="bup-staff-load" staff-id="'.$user_id.'"> ';
				
				$html .= $bookingultrapro->userpanel->get_user_pic( $user_id, 50, 'avatar', null, null, false);
				$html .='<h3>'.$user->display_name.'</h3>';
				$html .='</a>';
				$html .='</li>';
				
			}
			
			$html .='</ul>';
		
		}else{
			
			$html .=__('There are no staff members.','booking-ultra-pro');
			
		
		}
		
		
		
		echo $html;
		die();
		
	}
	
		/* Get picture by ID */
	function get_user_pic( $id, $size, $pic_type=NULL, $pic_boder_type= NULL, $size_type=NULL, $with_url=true ) 
	{
		
		 global  $bookingultrapro;
		 
		 require_once(ABSPATH . 'wp-includes/link-template.php');
	 
		
		$site_url = site_url()."/";
		
		//rand_val_cache		
		$cache_rand = time();
			 
		$avatar = "";
		$pic_size = "";
		
				
		$upload_dir = wp_upload_dir(); 
		$path =   $upload_dir['baseurl']."/".$id."/";
				
		$author_pic = get_the_author_meta('user_pic', $id);
		
		//get user url
		//$user_url=$this->get_user_profile_permalink($id);
		
		if($pic_boder_type=='none'){$pic_boder_type='uultra-none';}
		
		
		if($size_type=="fixed" || $size_type=="")
		{
			$dimension = "width:";
			$dimension_2 = "height:";
		}
		
		if($size_type=="dynamic" )
		{
			$dimension = "max-width:";
		
		}
		
		if($size!="")
		{
			$pic_size = $dimension.$size."px".";".$dimension_2.$size."px";
		
		}
		
		if($bookingultrapro->get_option('bup_force_cache_issue')=='yes')
		{
			$cache_by_pass = '?rand_cache='.$cache_rand;
		
		}
		
		$user = get_user_by( 'id', $id );
		
			
		
		if ($author_pic  != '') 
			{
				$avatar_pic = $path.$author_pic;
				
				
				if($with_url)
				{
		 
					$avatar= '<a href="'.$user_url.'">'. '<img src="'.$avatar_pic.'" class="avatar '.$pic_boder_type.'" style="'.$pic_size.' "   id="bup-avatar-img-'.$id.'" title="'.$user->display_name.'" /></a>';
				
				}else{
					
					$avatar=  '<img src="'.$avatar_pic.'" class="avatar '.$pic_boder_type.'" style="'.$pic_size.' "   id="bup-avatar-img-'.$id.'" title="'.$user->display_name.'" />';
				
				}
				
				
				
			} else {
				
				$user = get_user_by( 'id', $id );		
				$avatar = get_avatar( $user->user_email, $size );
		
	    	}
		
		return $avatar;
	}
	
	
	
	
	/* delete avatar */
	function delete_user_avatar() 
	{			
		$user_id =   $_POST['user_id'];			
		update_user_meta($user_id, 'user_pic', '');
		die();
	}
	
	public function avatar_uploader($staff_id=NULL) 
	{
		
	   // Uploading functionality trigger:
	  // (Most of the code comes from media.php and handlers.js)
	      $template_dir = get_template_directory_uri();
?>
		
		<div id="uploadContainer" style="margin-top: 10px;">
			
			
			<!-- Uploader section -->
			<div id="uploaderSection" style="position: relative;">
				<div id="plupload-upload-ui-avatar" class="hide-if-no-js">
                
					<div id="drag-drop-area-avatar">
						<div class="drag-drop-inside">
							<p class="drag-drop-info"><?php	_e('Drop '.$avatar_is_called.' here', 'booking-ultra-pro') ; ?></p>
							<p><?php _ex('or', 'Uploader: Drop files here - or - Select Files'); ?></p>
							                            
                            
							<p>
                                                      
                            <button name="plupload-browse-button-avatar" id="plupload-browse-button-avatar" class="bup-button-upload-avatar" ><span><i class="fa fa-camera"></i></span> <?php	_e('Select Image', 'booking-ultra-pro') ; ?>	</button>
                            </p>
                            
                            <p>
                                                      
                            <button name="plupload-browse-button-avatar" id="btn-delete-user-avatar" class="bup-button-delete-avatar" user-id="<?php echo $staff_id?>" redirect-avatar="yes"><span><i class="fa fa-times"></i></span> <?php	_e('Remove', 'booking-ultra-pro') ; ?>	</button>
                            </p>
                            
                            <p>
                            <a href="?page=bookingultra&tab=users&ui=<?php echo $staff_id?>" class="uultra-remove-cancel-avatar-btn"><?php	_e('Cancel', 'booking-ultra-pro') ; ?></a>
                            </p>
                                                        
                           
														
						</div>
                        
                        <div id="progressbar-avatar"></div>                 
                         <div id="bup_filelist_avatar" class="cb"></div>
					</div>
				</div>
                
                 
			
			</div>
            
           
		</div>
        
         <form id="bup_frm_img_cropper" name="bup_frm_img_cropper" method="post">                
                
                	<input type="hidden" name="image_to_crop" value="" id="image_to_crop" />
                    <input type="hidden" name="crop_image" value="crop_image" id="crop_image" />
                    
                    <input type="hidden" name="site_redir" value="<?php echo $my_account_url."?page=bookingultra&tab=users&ui=".$staff_id.""?>" id="site_redir" />                   
                
                </form>

		<?php
			
			$plupload_init = array(
				'runtimes'            => 'html5,silverlight,flash,html4',
				'browse_button'       => 'plupload-browse-button-avatar',
				'container'           => 'plupload-upload-ui-avatar',
				'drop_element'        => 'bup-drag-avatar-section',
				'file_data_name'      => 'async-upload',
				'multiple_queues'     => true,
				'multi_selection'	  => false,
				'max_file_size'       => wp_max_upload_size().'b',
				//'max_file_size'       => get_option('drag-drop-filesize').'b',
				'url'                 => admin_url('admin-ajax.php'),
				'flash_swf_url'       => includes_url('js/plupload/plupload.flash.swf'),
				'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
				//'filters'             => array(array('title' => __('Allowed Files', $this->text_domain), 'extensions' => "jpg,png,gif,bmp,mp4,avi")),
				'filters'             => array(array('title' => __('Allowed Files', "xoousers"), 'extensions' => "jpg,png,gif,jpeg")),
				'multipart'           => true,
				'urlstream_upload'    => true,

				// Additional parameters:
				'multipart_params'    => array(
					'_ajax_nonce' => wp_create_nonce('photo-upload'),
					'staff_id' => $staff_id,
					'action'      => 'bup_ajax_upload_avatar' // The AJAX action name
					
				),
			);
			
			//print_r($plupload_init);

			// Apply filters to initiate plupload:
			$plupload_init = apply_filters('plupload_init', $plupload_init); ?>

			<script type="text/javascript">
			
				jQuery(document).ready(function($){
					
					// Create uploader and pass configuration:
					var uploader_avatar = new plupload.Uploader(<?php echo json_encode($plupload_init); ?>);

					// Check for drag'n'drop functionality:
					uploader_avatar.bind('Init', function(up){
						
						var uploaddiv_avatar = $('#plupload-upload-ui-avatar');
						
						// Add classes and bind actions:
						if(up.features.dragdrop){
							uploaddiv_avatar.addClass('drag-drop');
							
							$('#drag-drop-area-avatar')
								.bind('dragover.wp-uploader', function(){ uploaddiv_avatar.addClass('drag-over'); })
								.bind('dragleave.wp-uploader, drop.wp-uploader', function(){ uploaddiv_avatar.removeClass('drag-over'); });

						} else{
							uploaddiv_avatar.removeClass('drag-drop');
							$('#drag-drop-area').unbind('.wp-uploader');
						}

					});

					
					// Init ////////////////////////////////////////////////////
					uploader_avatar.init(); 
					
					// Selected Files //////////////////////////////////////////
					uploader_avatar.bind('FilesAdded', function(up, files) {
						
						
						var hundredmb = 100 * 1024 * 1024, max = parseInt(up.settings.max_file_size, 10);
						
						// Limit to one limit:
						if (files.length > 1){
							alert("<?php _e('You may only upload one image at a time!', 'booking-ultra-pro'); ?>");
							return false;
						}
						
						// Remove extra files:
						if (up.files.length > 1){
							up.removeFile(uploader_avatar.files[0]);
							up.refresh();
						}
						
						// Loop through files:
						plupload.each(files, function(file){
							
							// Handle maximum size limit:
							if (max > hundredmb && file.size > hundredmb && up.runtime != 'html5'){
								alert("<?php _e('The file you selected exceeds the maximum filesize limit.', 'booking-ultra-pro'); ?>");
								return false;
							}
						
						});
						
						jQuery.each(files, function(i, file) {
							jQuery('#bup_filelist_avatar').append('<div class="addedFile" id="' + file.id + '">' + file.name + '</div>');
						});
						
						up.refresh(); 
						uploader_avatar.start();
						
					});
					
					// A new file was uploaded:
					uploader_avatar.bind('FileUploaded', function(up, file, response){					
						
						
						
						var obj = jQuery.parseJSON(response.response);												
						var img_name = obj.image;							
						
						$("#image_to_crop").val(img_name);
						$("#bup_frm_img_cropper").submit();

						
						
						
						jQuery.ajax({
							type: 'POST',
							url: ajaxurl,
							data: {"action": "refresh_avatar"},
							
							success: function(data){
								
								//$( "#uu-upload-avatar-box" ).slideUp("slow");								
								$("#uu-backend-avatar-section").html(data);
								
								//jQuery("#uu-message-noti-id").slideDown();
								//setTimeout("hidde_noti('uu-message-noti-id')", 3000)	;
								
								
								}
						});
						
						
					
					});
					
					// Error Alert /////////////////////////////////////////////
					uploader_avatar.bind('Error', function(up, err) {
						alert("Error: " + err.code + ", Message: " + err.message + (err.file ? ", File: " + err.file.name : "") + "");
						up.refresh(); 
					});
					
					// Progress bar ////////////////////////////////////////////
					uploader_avatar.bind('UploadProgress', function(up, file) {
						
						var progressBarValue = up.total.percent;
						
						jQuery('#progressbar-avatar').fadeIn().progressbar({
							value: progressBarValue
						});
						
						jQuery('#progressbar-avatar').html('<span class="progressTooltip">' + up.total.percent + '%</span>');
					});
					
					// Close window after upload ///////////////////////////////
					uploader_avatar.bind('UploadComplete', function() {
						
						//jQuery('.uploader').fadeOut('slow');						
						jQuery('#progressbar-avatar').fadeIn().progressbar({
							value: 0
						});
						
						
					});
					
					
					
				});
				
					
			</script>
			
		<?php
	
	
	}
	
	//crop avatar image
	function bup_crop_avatar_user_profile_image()
	{
		global $bookingultrapro;
		global $wpdb;
		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		$site_url = site_url()."/";		
	
		/// Upload file using Wordpress functions:
		$x1 = $_POST['x1'];
		$y1 = $_POST['y1'];
		
		$x2 = $_POST['x2'];
		$y2= $_POST['y2'];
		$w = $_POST['w'];
		$h = $_POST['h'];	
		
		$image_id =   $_POST['image_id'];
		$user_id =   $_POST['user_id'];		
		
		if($user_id==''){echo 'error';exit();}
				
		
		$bookingultrapro->imagecrop->setDimensions($x1, $y1, $w, $h)	;
		
		$upload_dir = wp_upload_dir(); 
		$path_pics =   $upload_dir['basedir'];		
		$src = $path_pics.'/'.$user_id.'/'.$image_id;
		
		//new random image and crop procedure				
		$bookingultrapro->imagecrop->setImage($src);
		$bookingultrapro->imagecrop->createThumb();		
		$info = pathinfo($src);
        $ext = $info['extension'];
		$ext=strtolower($ext);		
		$new_i = time().".". $ext;		
		$new_name =  $path_pics.'/'.$user_id.'/'.$new_i;				
		$bookingultrapro->imagecrop->renderImage($new_name);
		//end cropping
		
		//check if there is another avatar						
		$user_pic = get_user_meta($user_id, 'user_pic', true);	
		
		//resize
		//check max width		
		$original_max_width = $bookingultrapro->get_option('media_avatar_width'); 
        $original_max_height =$bookingultrapro->get_option('media_avatar_height'); 
		
		if($original_max_width=="" || $original_max_height=="")
		{			
			$original_max_width = 80;			
			$original_max_height = 80;			
		}
														
		list( $source_width, $source_height, $source_type ) = getimagesize($new_name);
		
		if($source_width > $original_max_width) 
		{
			if ($this->image_resize($new_name, $new_name, $original_max_width, $original_max_height,0)) 
			{
				$old = umask(0);
				chmod($new_name, 0755);
				umask($old);										
			}		
		}					
						
		if ( $user_pic!="" )
		{
				
			 //there is a pending avatar - delete avatar																					
			 	
			 $path_avatar = $path_pics['baseurl']."/".$user_id."/".$image_id;					
										  
			 //delete								
			 //update meta
			  update_user_meta($user_id, 'user_pic', $new_i);		  
			  
		  }else{
			  
			  //update meta
			  update_user_meta($user_id, 'user_pic', $new_i);
								  
		  
		  }
		  
		  
		  if(file_exists($src))
		  {
			  unlink($src);
		  }
			 
	
		// Create response array:
		$uploadResponse = array('image' => $new_name);
		
		// Return response and exit:
		echo json_encode($uploadResponse);
		
		die();
		
	}
	
	function image_resize($src, $dst, $width, $height, $crop=0)
	{
		
		  if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";
		
		  $type = strtolower(substr(strrchr($src,"."),1));
		  if($type == 'jpeg') $type = 'jpg';
		  switch($type){
			case 'bmp': $img = imagecreatefromwbmp($src); break;
			case 'gif': $img = imagecreatefromgif($src); break;
			case 'jpg': $img = imagecreatefromjpeg($src); break;
			case 'png': $img = imagecreatefrompng($src); break;
			default : return "Unsupported picture type!";
		  }
		
		  // resize
		  if($crop){
			if($w < $width or $h < $height) return "Picture is too small!";
			$ratio = max($width/$w, $height/$h);
			$h = $height / $ratio;
			$x = ($w - $width / $ratio) / 2;
			$w = $width / $ratio;
		  }
		  else{
			if($w < $width and $h < $height) return "Picture is too small!";
			$ratio = min($width/$w, $height/$h);
			$width = $w * $ratio;
			$height = $h * $ratio;
			$x = 0;
		  }
		
		  $new = imagecreatetruecolor($width, $height);
		
		  // preserve transparency
		  if($type == "gif" or $type == "png"){
			imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
			imagealphablending($new, false);
			imagesavealpha($new, true);
		  }
		
		  imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);
		
		  switch($type){
			case 'bmp': imagewbmp($new, $dst); break;
			case 'gif': imagegif($new, $dst); break;
			case 'jpg': imagejpeg($new, $dst,100); break;
			case 'jpeg': imagejpeg($new, $dst,100); break;
			case 'png': imagepng($new, $dst,9); break;
		  }
		  return true;
	}
	
	function display_avatar_image_to_crop($image, $user_id=NULL)	
	{
		 global $bookingultrapro;
		
		/* Custom style */		
		wp_register_style( 'bup_image_cropper_style', bookingup_url.'js/cropper/cropper.min.css');
		wp_enqueue_style('bup_image_cropper_style');	
					
		wp_enqueue_script('simple_cropper',  bookingup_url.'js/cropper/cropper.min.js' , array('jquery'), false, false);
		
	  
	    $template_dir = get_template_directory_uri();		  
				
		$site_url = site_url()."/";
		
		$html = "";
		
		$upload_dir = wp_upload_dir(); 
		$upload_folder =   $upload_dir['basedir'];		
				
		$user_pic = get_user_meta($user_id, 'user_profile_bg', true);		
		
		if($image!="")
		{
			$url_image_to_crop = $upload_dir['baseurl'].'/'.$user_id.'/'.$image;			
			$html_image = '<img src="'.$url_image_to_crop.'" id="uultra-profile-cover-horizontal" />';					
			
		}
		
		$my_account_url = $bookingultrapro->userpanel->get_my_account_direct_link 
		
		
		
		?>
        
        
      	<div id="uultra-dialog-user-bg-cropper-div" class="bup-dialog-user-bg-cropper"  >	
				<?php echo $html_image ?>                   
		</div>
            
            
             
             
             <p>
                                                      
                            <button name="plupload-browse-button-avatar" id="uultra-confirm-avatar-cropping" class="bup-button-upload-avatar" type="link"><span><i class="fa fa-crop"></i></span> <?php	_e('Crop & Save', 'booking-ultra-pro') ; ?>	</button>
                            
                            
                            <div class="bup-please-wait-croppingmessage" id="bup-cropping-avatar-wait-message">&nbsp;</div>
                            </p>
                            
                            
                            <div class="uultra-uploader-buttons-delete-cancel" id="btn-cancel-avatar-cropping" >
                            <a href="?page=bookingultra&tab=users&ui=<?php echo $user_id?>" class="uultra-remove-cancel-avatar-btn"><?php	_e('Cancel', 'booking-ultra-pro') ; ?></a>
                            </div>
            
     			<input type="hidden" name="x1" value="0" id="x1" />
				<input type="hidden" name="y1" value="0" id="y1" />				
				<input type="hidden" name="w" value="<?php echo $w?>" id="w" />
				<input type="hidden" name="h" value="<?php echo $h?>" id="h" />
                <input type="hidden" name="image_id" value="<?php echo $image?>" id="image_id" />
                <input type="hidden" name="user_id" value="<?php echo $user_id?>" id="user_id" />
                <input type="hidden" name="site_redir" value="<?php echo $my_account_url."?page=bookingultra&tab=users&ui=".$user_id.""?>" id="site_redir" />
                
		
		<script type="text/javascript">
		
		
				jQuery(document).ready(function($){
					
				
					<?php
					
					
					
					$source_img = $upload_folder.'/'.$user_id.'/'.$image;	
									 
					 $r_width = $this->getWidth($source_img);
					 $r_height= $this->getHeight($source_img);
					 
					$original_max_width = $bookingultrapro->get_option('media_avatar_width'); 
					$original_max_height =$bookingultrapro->get_option('media_avatar_height'); 
					
					if($original_max_width=="" || $original_max_height=="")
					{			
						$original_max_width = 80;			
						$original_max_height = 80;
						
					}
					
					$aspectRatio = $original_max_width/$original_max_height;
					
					
					 
						 ?>
						var $image = jQuery(".bup-dialog-user-bg-cropper img"),
						$x1 = jQuery("#x1"),
						$y1 = jQuery("#y1"),
						$h = jQuery("#h"),
						$w = jQuery("#w");
					
					$image.cropper({
								  aspectRatio: <?php echo $aspectRatio?>,
								  autoCropArea: 0.6, // Center 60%
								  zoomable: false,
								  preview: ".img-preview",
								  done: function(data) {
									$x1.val(Math.round(data.x));
									$y1.val(Math.round(data.y));
									$h.val(Math.round(data.height));
									$w.val(Math.round(data.width));
								  }
								});
			
			})	
				
									
			</script>
		
		
	<?php	
		
	}
	
	//You do not need to alter these functions
	function getHeight($image) {
		$size = getimagesize($image);
		$height = $size[1];
		return $height;
	}

	//You do not need to alter these functions
	function getWidth($image) {
		$size = getimagesize($image);
		$width = $size[0];
		return $width;
	}
	
	
	// File upload handler:
	function bup_ajax_upload_avatar()
	{
		global $bookingultrapro;
		global $wpdb;
		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		$site_url = site_url()."/";
		
		// Check referer, die if no ajax:
		check_ajax_referer('photo-upload');
		
		/// Upload file using Wordpress functions:
		$file = $_FILES['async-upload'];
		
		
		$original_max_width = $bookingultrapro->get_option('media_avatar_width'); 
        $original_max_height =$bookingultrapro->get_option('media_avatar_height'); 
		
		if($original_max_width=="" || $original_max_height=="")
		{			
			$original_max_width = 80;			
			$original_max_height = 80;
			
		}
		
			
				
		$o_id = $_POST['staff_id'];
		
				
		$info = pathinfo($file['name']);
		$real_name = $file['name'];
        $ext = $info['extension'];
		$ext=strtolower($ext);
		
		$rand = $this->genRandomString();
		
		$rand_name = "avatar_".$rand."_".session_id()."_".time(); 
		
	
		$upload_dir = wp_upload_dir(); 
		$path_pics =   $upload_dir['basedir'];
			
			
		if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif') 
		{
			if($o_id != '')
			{
				
				   if(!is_dir($path_pics."/".$o_id."")) 
				   {
						 wp_mkdir_p( $path_pics."/".$o_id );							   
					}					
										
					$pathBig = $path_pics."/".$o_id."/".$rand_name.".".$ext;						
					
					
					if (copy($file['tmp_name'], $pathBig)) 
					{
						//check auto-rotation						
						if($bookingultrapro->get_option('avatar_rotation_fixer')=='yes')
						{
							$this->orient_image($pathBig);
						
						}
						
						$upload_folder = $bookingultrapro->get_option('media_uploading_folder');				
						$path = $site_url.$upload_folder."/".$o_id."/";
						
						//check max width												
						list( $source_width, $source_height, $source_type ) = getimagesize($pathBig);
						
						if($source_width > $original_max_width) 
						{
							//resize
						//	if ($this->createthumb($pathBig, $pathBig, $original_max_width, $original_max_height,$ext)) 
							//{
								//$old = umask(0);
								//chmod($pathBig, 0755);
								//umask($old);
														
							//}
						
						
						}
						
						
						
						$new_avatar = $rand_name.".".$ext;						
						$new_avatar_url = $path.$rand_name.".".$ext;				
						
						
						//check if there is another avatar						
						$user_pic = get_user_meta($o_id, 'user_pic', true);						
						
						if ( $user_pic!="" )
			            {
							//there is a pending avatar - delete avatar																					
							$path_avatar = $path_pics."/".$o_id."/".$user_pic;					
														
							//delete								
							if(file_exists($path_avatar))
							{
								unlink($path_avatar);
							}
							
												
							
						}else{
							
																	
						
						}
						
						//update user meta
						
					}
									
					
			     }  		
			
        } // image type
		
		// Create response array:
		$uploadResponse = array('image' => $new_avatar);
		
		// Return response and exit:
		echo json_encode($uploadResponse);
		
		//echo $new_avatar_url;
		die();
		
	}
	
	public function genRandomString() 
	{
		$length = 5;
		$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWZYZ";
		
		$real_string_legnth = strlen($characters) ;
		//$real_string_legnth = $real_string_legnth– 1;
		$string="ID";
		
		for ($p = 0; $p < $length; $p++)
		{
			$string .= $characters[mt_rand(0, $real_string_legnth-1)];
		}
		
		return strtolower($string);
	}
	
	public function orient_image($file_path) 
	{
        if (!function_exists('exif_read_data')) {
            return false;
        }
        $exif = @exif_read_data($file_path);
        if ($exif === false) {
            return false;
        }
        $orientation = intval(@$exif['Orientation']);
        if (!in_array($orientation, array(3, 6, 8))) {
            return false;
        }
        $image = @imagecreatefromjpeg($file_path);
        switch ($orientation) {
            case 3:
                $image = @imagerotate($image, 180, 0);
                break;
            case 6:
                $image = @imagerotate($image, 270, 0);
                break;
            case 8:
                $image = @imagerotate($image, 90, 0);
                break;
            default:
                return false;
        }
        $success = imagejpeg($image, $file_path);
        // Free up memory (imagedestroy does not delete files):
        @imagedestroy($image);
        return $success;
    }
	
	function validate_if_user_has_gravatar($user_id)
	{
		
		$has_gravatar = get_user_meta( $user_id, 'bup_has_gravatar', true);
		
		if($has_gravatar=='' || $has_gravatar=='0')
		{			
			//check if user has a valid gravatar
			if($this->uultra_validate_gravatar($user_id))
			{
				//has a valid gravatar				
				update_user_meta($user_id, 'bup_has_gravatar', 1);			
			
			}else{
				
				delete_user_meta($user_id, 'bup_has_gravatar')	;		
				
			}
		
		
		}
	
	}
	
	
	/**
	 * Utility function to check if a gravatar exists for a given email or id
	 * @param int|string|object $id_or_email A user ID,  email address, or comment object
	 * @return bool if the gravatar exists or not
	 */
	
	function uultra_validate_gravatar($id_or_email) 
	{
	  //id or email code borrowed from wp-includes/pluggable.php
		$email = '';
		if ( is_numeric($id_or_email) ) {
			$id = (int) $id_or_email;
			$user = get_userdata($id);
			if ( $user )
				$email = $user->user_email;
		} elseif ( is_object($id_or_email) ) {
			// No avatar for pingbacks or trackbacks
			$allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
			if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) )
				return false;
	
			if ( !empty($id_or_email->user_id) ) {
				$id = (int) $id_or_email->user_id;
				$user = get_userdata($id);
				if ( $user)
					$email = $user->user_email;
			} elseif ( !empty($id_or_email->comment_author_email) ) {
				$email = $id_or_email->comment_author_email;
			}
		} else {
			$email = $id_or_email;
		}
	
		$hashkey = md5(strtolower(trim($email)));
		$uri = 'http://www.gravatar.com/avatar/' . $hashkey . '?d=404';
	
		$data = wp_cache_get($hashkey);
		if (false === $data) {
			$response = wp_remote_head($uri);
			if( is_wp_error($response) ) {
				$data = 'not200';
			} else {
				$data = $response['response']['code'];
			}
			wp_cache_set($hashkey, $data, $group = '', $expire = 60*5);
	
		}		
		if ($data == '200'){
			return true;
		} else {
			return false;
		}
	}
	
	function validate_gravatar($email) 
	{
		// Craft a potential url and test its headers
		/*$hash = md5(strtolower(trim($email)));
		$uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
		$headers = @get_headers($uri);
		if (!preg_match("|200|", $headers[0])) {
			$has_valid_avatar = FALSE;
		} else {
			$has_valid_avatar = TRUE;
		}*/
		$has_valid_avatar = TRUE;
		return $has_valid_avatar;
	}

	function get_avatar_url( $avatar) 
	{

		preg_match( '#src=["|\'](.+)["|\']#Uuis', $avatar, $matches );
	
		return ( isset( $matches[1] ) && ! empty( $matches[1]) ) ?
			(string) $matches[1] : '';  
	
	}
	
		
	function get_users_auto_complete()
	{
		global $wpdb, $bookingultrapro;
		
		$term     = sanitize_text_field( $_GET['term'] );
		
		// Initialise suggestions array
    	$suggestions=array();
		
		$sql = ' SELECT * FROM ' . $wpdb->users . ' WHERE display_name LIKE  "%'.$term.'%" OR user_email LIKE "%'.$term.'%" LIMIT 12' ;			
				
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				 // Initialise suggestion array
								
				$options['results'][] = array(
						'id' => $row->ID,
						'value'    => $row->display_name,
						'label'    => $row->display_name.' '. '('.$row->user_email.')',
					); 
					 
							
			}
			
			
			
		
		}else{
			
			$options['results'][] = array(
						'id' => '0',
						'value'    => '0',
						'label'    => __('No results found','booking-ultra-pro'),
					);
			
		}
		
		
		$response = json_encode( $options );
    	echo $response;
    	exit();
	
	}
	
	function get_one($id)
	{
		global $wpdb, $bookingultrapro;
		
		$user = get_user_by( 'id', $id );
		
		return $user;
		
	}
	
}
$key = "userpanel";
$this->{$key} = new BookingUltraUser();
?>