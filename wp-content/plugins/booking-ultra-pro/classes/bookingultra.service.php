<?php
class BookingUltraService
{
	var $mBusinessHours;
	var $mDaysMaping;
	
	function __construct() 
	{
				
		$this->ini_module();		
		add_action( 'wp_ajax_display_categories', array( &$this, 'get_ajax_admin_categories' ));
		add_action( 'wp_ajax_display_admin_services', array( &$this, 'get_ajax_admin_services' ));
		add_action( 'wp_ajax_ubp_get_service', array( &$this, 'ubp_get_service' ));	
		add_action( 'wp_ajax_ubp_update_service', array( &$this, 'ubp_update_service' ));
		add_action( 'wp_ajax_ubp_update_global_business_hours', array( &$this, 'ubp_update_global_business_hours' ));
		add_action( 'wp_ajax_ubp_update_staff_business_hours', array( &$this, 'update_staff_business_hours' ));	
			
		add_action( 'wp_ajax_ubp_book_step_2',  array( &$this, 'ubp_book_step_2' ));
		add_action( 'wp_ajax_nopriv_ubp_book_step_2',  array( &$this, 'ubp_book_step_2' ));
		
		add_action( 'wp_ajax_ubp_book_step_3',  array( &$this, 'ubp_book_step_3' ));
		add_action( 'wp_ajax_nopriv_ubp_book_step_3',  array( &$this, 'ubp_book_step_3' ));	
		
		add_action( 'wp_ajax_ubp_book_step_4',  array( &$this, 'ubp_book_step_4' ));
		add_action( 'wp_ajax_nopriv_ubp_book_step_4',  array( &$this, 'ubp_book_step_4' ));
		
		add_action( 'wp_ajax_ubp_book_step_show_cart',  array( &$this, 'ubp_book_step_show_cart' ));
		add_action( 'wp_ajax_nopriv_ubp_book_step_show_cart',  array( &$this, 'ubp_book_step_show_cart' ));
		
		
		add_action( 'wp_ajax_ubp_book_step_2_hotels',  array( &$this, 'ubp_book_step_2_hotels' ));
		add_action( 'wp_ajax_nopriv_ubp_book_step_2_hotels',  array( &$this, 'ubp_book_step_2_hotels' ));
		
		add_action( 'wp_ajax_bup_update_purchase_total',  array( &$this, 'update_purchase_total_inline' ));
		add_action( 'wp_ajax_nopriv_bup_update_purchase_total',  array( &$this, 'update_purchase_total_inline' ));
		
		add_action( 'wp_ajax_bup_delete_cart_item',  array( &$this, 'delete_cart_item' ));
		add_action( 'wp_ajax_nopriv_bup_delete_cart_item',  array( &$this, 'delete_cart_item' ));
		
		add_action( 'wp_ajax_bup_get_shopping_cart',  array( &$this, 'bup_get_shopping_cart_2' ));
		add_action( 'wp_ajax_nopriv_bup_get_shopping_cart',  array( &$this, 'bup_get_shopping_cart_2' ));
		
		add_action( 'wp_ajax_bup_display_cart_checkout',  array( &$this, 'bup_display_cart_checkout' ));
		add_action( 'wp_ajax_nopriv_bup_display_cart_checkout',  array( &$this, 'bup_display_cart_checkout' ));
		
		
		
		
		add_action( 'wp_ajax_ubp_load_dw_of_staff',  array( &$this, 'get_cate_dw_ajax' ));
		add_action( 'wp_ajax_nopriv_ubp_load_dw_of_staff',  array( &$this, 'get_cate_dw_ajax' ));		
		add_action( 'wp_ajax_get_cate_dw_admin_ajax',  array( &$this, 'get_cate_dw_admin_ajax' ));	
		add_action( 'wp_ajax_ubp_check_adm_availability',  array( &$this, 'ubp_check_adm_availability' ));
		
		add_action( 'wp_ajax_ubp_check_adm_availability_admin',  array( &$this, 'ubp_check_adm_availability_admin' ));
		add_action( 'wp_ajax_bup_get_category_add_form',  array( &$this, 'get_category_add_form' ));
		add_action( 'wp_ajax_bup_add_category_confirm',  array( &$this, 'add_category_confirm' ));
		add_action( 'wp_ajax_bup_delete_category',  array( &$this, 'delete_category' ));
		add_action( 'wp_ajax_bup_delete_service',  array( &$this, 'delete_service' ));
		add_action( 'wp_ajax_bup_client_get_add_form',  array( &$this, 'client_get_add_form' ));
		
		add_action( 'wp_ajax_bup_get_service_pricing',  array( &$this, 'get_service_pricing' ));
		add_action( 'wp_ajax_bup_update_group_pricing_table',  array( &$this, 'update_group_pricing_table' ));
		

	}
	
	public function ini_module()
	{
		global $wpdb;
		
		
			// Create table
			$query = '
				CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'bup_services(
				  `service_id` int(11) NOT NULL AUTO_INCREMENT,
				  `service_title` varchar(300) NOT NULL,
				  `service_color` varchar(10) DEFAULT NULL,
				  `service_font_color` varchar(10) DEFAULT NULL,
				  `service_duration` int(11) NOT NULL,
				  `service_padding_before` int(11) NOT NULL DEFAULT "0",
				  `service_padding_after` int(11) NOT NULL DEFAULT "0",
				  `service_capacity` int(11) NOT NULL DEFAULT "1",
				  `service_allow_multiple` int(1) DEFAULT NULL,
				  `service_pricing_calculation_type` int(1) DEFAULT "1",
				  `service_category_id` int(11) NOT NULL,
				  `service_icon` varchar(50) NOT NULL,
				  `service_price` decimal(11,2) NOT NULL,
				  `service_price_2` decimal(11,2) DEFAULT "0",
				  `service_type` int(1) NOT NULL DEFAULT "0",
				  `service_private` int(1) NOT NULL DEFAULT "0",
				  `service_order` int(11) NOT NULL DEFAULT "0",
				  PRIMARY KEY (`service_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
				';
				
			$wpdb->query( $query );
				
			// Create table
			$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'bup_service_variable_pricing (
				`rate_id` int(11) NOT NULL AUTO_INCREMENT,
				`rate_service_id` int(11) NOT NULL,				
				`rate_price` decimal(11,2) NOT NULL,
				`rate_person` int(11) NOT NULL,
				 PRIMARY KEY (`rate_id`)
			) ENGINE=MyISAM COLLATE utf8_general_ci;';

		   $wpdb->query( $query );
		   
		   // Create table
			$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'bup_categories (
				`cate_id` bigint(20) NOT NULL auto_increment,
				`cate_template_id` int(11) NOT NULL DEFAULT "0",
				`cate_name` varchar(300) NOT NULL 	,
				`cate_order` int(11) NOT NULL DEFAULT "0",						
				PRIMARY KEY (`cate_id`)
			) COLLATE utf8_general_ci;';

		   $wpdb->query( $query );
		    
		   
		    // Create table
			$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'bup_service_rates (
				`rate_id` int(11) NOT NULL AUTO_INCREMENT,
				`rate_service_id` int(11) NOT NULL,
				`rate_staff_id` int(11) NOT NULL,
				`rate_price` decimal(11,2) NOT NULL,
				`rate_capacity` int(11) NOT NULL,
				 PRIMARY KEY (`rate_id`)
			) ENGINE=MyISAM COLLATE utf8_general_ci;';

		   $wpdb->query( $query );
		   
		    // Create table
			$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'bup_staff_availability (
				  `avail_id` int(11) NOT NULL AUTO_INCREMENT,
				  `avail_staff_id` int(11) NOT NULL,
				  `avail_day` int(11) NOT NULL,
				  `avail_from` time NOT NULL,
				  `avail_to` time NOT NULL,
				  PRIMARY KEY (`avail_id`)
			) ENGINE=MyISAM COLLATE utf8_general_ci;';

		   $wpdb->query( $query );
		   
		   
		   
		    // Create table
			$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'bup_filters (
				`filter_id` bigint(20) NOT NULL auto_increment,
				`filter_name` varchar(300) NOT NULL ,	
				`filter_email` varchar(300) NOT NULL ,									
				PRIMARY KEY (`filter_id`)
			) COLLATE utf8_general_ci;';

		   $wpdb->query( $query );	
		   
		    // Create table
			$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'bup_filter_staff (
				`fstaff_id` bigint(20) NOT NULL auto_increment,
				`fstaff_staff_id` int(11) NOT NULL,
				`fstaff_location_id` int(11) NOT NULL,										
				PRIMARY KEY (`fstaff_id`)
			) COLLATE utf8_general_ci;';

		   $wpdb->query( $query );	
		   
		   
		   
		   $this->update_table();
		   
		   
		  		   
		
	}
	
	function update_table()
	{
		global $wpdb;	
		
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'bup_services where field="service_padding_before" ';		
		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) )
		{	
			$sql = 'Alter table  ' . $wpdb->prefix . 'bup_services add column service_padding_before int (11) default 0 ; ';
			$wpdb->query($sql);
		}
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'bup_services where field="service_padding_after" ';		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) )
		{	
			$sql = 'Alter table  ' . $wpdb->prefix . 'bup_services add column service_padding_after int (11) default 0 ; ';
			$wpdb->query($sql);
		}
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'bup_services where field="service_allow_multiple" ';		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) )
		{	
			$sql = 'Alter table  ' . $wpdb->prefix . 'bup_services add column service_allow_multiple int (1) default 0 ; ';
			$wpdb->query($sql);
		}
		
		/*added on 04-09-2019*/
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'bup_services where field="service_private" ';		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) )
		{	
			$sql = 'Alter table  ' . $wpdb->prefix . 'bup_services add column service_private int (1) default 0 ; ';
			$wpdb->query($sql);
		}
		
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'bup_services where field="service_pricing_calculation_type" ';		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) )
		{	
			$sql = 'Alter table  ' . $wpdb->prefix . 'bup_services add column service_pricing_calculation_type int (1) default 1 ; ';
			$wpdb->query($sql);
		}
		
		
		
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'bup_services where field="service_price_2" ';		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) )
		{	
			$sql = 'Alter table  ' . $wpdb->prefix . 'bup_services add column service_price_2 decimal(11,2) default 0 ; ';
			$wpdb->query($sql);
		}
		
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'bup_categories where field="cate_template_id" ';		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) )
		{	
			$sql = 'Alter table  ' . $wpdb->prefix . 'bup_categories add column cate_template_id int (11) default 0 ; ';
			$wpdb->query($sql);
		}
		
		
	}
	
	
	
	
	public function get_ajax_admin_categories()
	{
		$html = $this->get_admin_categories();	
		echo $html ;		
		die();		
	
	}
	
	public function get_ajax_admin_services()
	{
		
		if(isset($_POST['cate_id']))
		{
			$cate_id = $_POST['cate_id'];
		
		}else{
			
			$cate_id = '';
			
		}
		
		
		$html = $this->get_admin_services($cate_id);	
		echo $html ;		
		die();		
	
	}
	
	
	//this displays shopping cart
	public function ubp_book_step_show_cart()
	{
		
		global  $bookingultrapro;
		
		$response = array();
		
		$bup_date = $_POST['date_to_book'];
		
		
		$service = $this->get_one_service($service_id);
		
		$service_details = $bookingultrapro->userpanel->get_staff_service_rate( $staff_id, $service_id ); 
		$amount= $service_details['price'];
		
		
		$html='';
		
	
		$time_format = $this->get_time_format();		
		
		//staff member		
		$staff_member = $bookingultrapro->userpanel->get_staff_member($staff_id);	
		
		//parse content		
		$content_text = $bookingultrapro->get_template_label("step3_texts",$template_id);		
		$content_text = $this->ubp_parse_customizer_texts($content_text, $service, $staff_member, $date_from_l);
		
		
		$html .= '<div class="bup-selected-staff-booking-info">'; 
		
		$html .= $content_text;	
		
		
		$html .= '</div>';
		
					
		$html .= $bookingultrapro->get_registration_form($order_data);
		
		$response = array('response' => 'OK', 'content' => $html);
		echo json_encode($response) ;	
		
		die();
		
		
	}
	
	//this displays the login or payment form
	public function bup_display_cart_checkout()
	{
		
		global  $bookingultrapro;
		
		$response = array();
		$order_data = array();	
		
		$template_id = $_POST['template_id'];			
		$order_data = array(
						 'template_id' => $template_id); 
		
			
		
		$show_cart = $bookingultrapro->get_template_label("show_cart",$template_id);
		
		$html = '';		
		
		if($show_cart==1) //add to cart and display sucess message
		{
			//parse content		
			$content_text = $bookingultrapro->get_template_label("step3_cart_texts",$template_id);		
			$content_text = $this->ubp_parse_customizer_texts($content_text, $service, $staff_member, $date_from_l);		
		
			$html .= '<div class="bup-selected-staff-booking-info">'; 		
			$html .= $content_text;		
			$html .= '</div>';		
		
			$html .= $bookingultrapro->get_registration_form($order_data);
			
		
		}		
		
		$response = array('response' => 'OK', 'content' => $html);
		echo json_encode($response) ;		
		
		die();
		
	}
	
	
	//this displays the login or payment form
	public function ubp_book_step_3()
	{
		
		global  $bookingultrapro;
		
		$response = array();
		
		$bup_date = $_POST['date_to_book'];
		$service_and_staff_id = $_POST['service_and_staff_id'];
		$time_slot = $_POST['time_slot'];
		$form_id = $_POST['form_id'];
		$location_id = $_POST['location_id'];
		
		$field_legends = $_POST['field_legends'];
		$placeholders = $_POST['placeholders'];
		$template_id = $_POST['template_id'];
		
		$max_capacity = $_POST['max_capacity'];
		$max_available = $_POST['max_available'];
				
		
		$arr_ser = explode("-", $service_and_staff_id);			
		$service_id = $arr_ser[0]; 
		$staff_id = $arr_ser[1];
		
		$arr_time_slot = explode("-", $time_slot);			
		$book_from = $arr_time_slot[0]; 
		$book_to = $arr_time_slot[1];
		
		$service = $this->get_one_service($service_id);
		
		$service_details = $bookingultrapro->userpanel->get_staff_service_rate( $staff_id, $service_id ); 
		$amount= $service_details['price'];
		
		$order_data = array('bup_date' => $bup_date,						 
						 'service_id' => $service_id,
						 'form_id' => $form_id,
						 'staff_id' => $staff_id ,
						 'location_id' => $location_id ,
						 'book_from' => $book_from ,
						 'book_to' => $book_to,
						 
						 'bup_service_cost' =>$amount ,
						 'field_legends' => $field_legends,
						 'placeholders' => $placeholders,
						 'template_id' => $template_id,
						 'max_capacity' => $max_capacity,
						 'max_available' => $max_available); 
		
		
		$date_from_l =  $bup_date.' '.$book_from.':00';
		$html='';
		
	
		$time_format = $this->get_time_format();		
		
		//staff member		
		$staff_member = $bookingultrapro->userpanel->get_staff_member($staff_id);		
		
		$show_cart = $bookingultrapro->get_template_label("show_cart",$template_id);		
		
		if($show_cart==1) //add to cart and display sucess message
		{
			//add item to cart			
			
			//parse content		
			$content_text = $bookingultrapro->get_template_label("step3_texts",$template_id);		
			$content_text = $this->ubp_parse_customizer_texts($content_text, $service, $staff_member, $date_from_l);
		
			$html .= '<div class="bup-selected-staff-booking-info">'; 		
			$html .= $content_text;		
			$html .= '</div>';
			
			$this->add_item_to_cart($order_data);			
			$html .= $this->bup_get_shopping_cart($template_id);
			
		}else{ //display the registration form
		
		
			//parse content		
			$content_text = $bookingultrapro->get_template_label("step3_texts",$template_id);		
			$content_text = $this->ubp_parse_customizer_texts($content_text, $service, $staff_member, $date_from_l);
		
		
			$html .= '<div class="bup-selected-staff-booking-info">'; 		
			$html .= $content_text;		
			$html .= '</div>';		
		
			$html .= $bookingultrapro->get_registration_form($order_data);
			
		}
		
					
		
		
		$response = array('response' => 'OK', 'content' => $html);
		echo json_encode($response) ;		
		
		die();
		
	}
	
	function delete_cart_item()
	{
		
		global  $bookingultrapro;
		
		$html ='';
		
				
		$CURRENT_CART = $_COOKIE["BUP_SHOPPING_CART"];
		$CURRENT_CART = stripslashes($CURRENT_CART);
		$CURRENT_CART = json_decode($CURRENT_CART, true);
		
		$item = $_POST['cart_item'];		
		unset($CURRENT_CART[$item]);
		
		
		$CURRENT_CART = array_values($CURRENT_CART);
		
		$this->update_cart_cookie($CURRENT_CART);
		
		
		die();
		
	}
	
	function bup_get_shopping_cart_2()
	{
		
		global  $bookingultrapro;
		
		$template_id = $_POST["template_id"];
		
		$html ='';				
		$html .= $this->bup_get_shopping_cart($template_id);		
		echo $html;		
		die();
		
	}
	
	function bup_get_shopping_cart($template_id = NULL)
	{
		
		global  $bookingultrapro;
	
		$currency_symbol =  $bookingultrapro->get_option('paid_membership_symbol');
		$date_format =  $bookingultrapro->get_int_date_format();
		$time_format =  $bookingultrapro->service->get_time_format();
		
		if($template_id==NULL){$template_id=$_POST["template_id"];}

		
		$html ='';
		
		if(isset($_COOKIE["BUP_SHOPPING_CART"])) 
		{
		
			$CURRENT_CART = $_COOKIE["BUP_SHOPPING_CART"];
			$CURRENT_CART = stripslashes($CURRENT_CART);
			$CURRENT_CART = json_decode($CURRENT_CART, true);
			
			
			if(count($CURRENT_CART)>0)			
			{
						
				$html .= '<div class="bup-profile-separator">'.__('Shopping Cart','booking-ultra-pro').'</div>';			
				$html .= '<div class="bup-shopping-cart-cont">';			
				$html .= '<div class="bup-shopping-cart-header">';
				
				$html .= __('You have: ','booking-ultra-pro'). '(<span>'.count($CURRENT_CART).'</span>)'. __(' items in your cart ','booking-ultra-pro'); //cart div
				
				$html .= '</div>'; //cart header		
				
				
				$html .= ' <table width="100%" class="">
				<thead>
					<tr>
						<th>'.$bookingultrapro->get_template_label("cart_header_1_texts",$template_id).'</th>
						<th>'.$bookingultrapro->get_template_label("cart_header_2_texts",$template_id).'</th>
						 
											
						<th>'.$bookingultrapro->get_template_label("cart_header_3_texts",$template_id).'</th>
					   
					   
						 <th>'.$bookingultrapro->get_template_label("cart_header_4_texts",$template_id).'</th>
						  <th>'.$bookingultrapro->get_template_label("cart_header_5_texts",$template_id).'</th>
						<th>'.$bookingultrapro->get_template_label("cart_header_6_texts",$template_id).'</th>
						<th>'.$bookingultrapro->get_template_label("cart_header_7_texts",$template_id).'</th>
						
					   
					</tr>
				</thead>
				
				<tbody>';
				
				$total_price = 0;				
				
				foreach ($CURRENT_CART as $key => $ITEM)  
				{
					
					
					$booking_time = date($time_format, strtotime($ITEM['book_from']));				
					$booking_date =  date($date_format, strtotime($ITEM['book_date']));
					
					//service			
					$service = $bookingultrapro->service->get_one_service($ITEM['service_id']);
					
					//price				
					$service_price = $bookingultrapro->get_price_format($service->service_price);
					
					$total_unitary_price = $ITEM['book_qty']*$service->service_price;
					$total_price = $total_price+$total_unitary_price ;
					
					//get user				
					$staff_member = get_user_by( 'id', $ITEM['staff_id']);
					
					$html .='<tr>';
					$html .='<td>'.$service->service_title.'</td>';
					$html .='<td>'.$booking_date.'</td>';
					$html .='<td>'.$booking_time.'</td>';
					$html .='<td>'.$staff_member->display_name.'</td>';
					$html .='<td>'.$ITEM['book_qty'].'</td>';
					$html .='<td class="bupcartprice">'.$service_price.'</td>';
					$html .='<td><a href="#" class="bup-btn-delete-cart-item" item-cart-id="'.$key.'" title="'.__('Delete','booking-ultra-pro').'"><i class="fa fa-trash-o"></i></a></td>';
					$html .='</tr>';
				}
				
				  $html .='<tr>';
					$html .='<td>&nbsp;</td>';
					$html .='<td>&nbsp;</td>';
					$html .='<td>&nbsp;</td>';
					$html .='<td>&nbsp;</td>';
					$html .='<td>&nbsp;</td>';
					$html .='<td class="bupcarttotal"> '. $bookingultrapro->get_price_format($total_price).'</td>';
					$html .='<td>&nbsp;</td>';			
				  $html .='</tr>';
				
				
				
				$html .='</tbody>
						</table>';
						
				$html .= '</div>'; //cart div
				
				$html .= '<div class="bup-shopping-cart-footer">';
				
				$html .='  <span class="bupbtncartclear">  <button id="bup-btn-clean-cart" class="bup-button-submit"> '.__('Clear Cart','booking-ultra-pro').'</button></span>';			
				$html .='  <span class="bupbtncartcheckout">  <button id="bup-btn-checkout-cart" class="bup-button-submit"><i class="fa fa-check" aria-hidden="true"></i> '.__('Checkout','booking-ultra-pro').'</button></span>';
				
				
				$html .= '</div>';
			
			}else{
				
			
				$html .=__('Your cart is empty, please select at least one service.','booking-ultra-pro');
			
		    }
			
			
			
			
		
		}else{
			
			$html .=__('Your cart is empty, please select at least one service.','booking-ultra-pro');
			
		}
		
		if(isset($_POST["bup_reload_cart_front"])) 
		
		{
			
			echo $html;
			//echo " ajax";
			die();
			
		}else{
			
			return $html;
			
		}
		
		
	}
	
	
	function bup_get_shopping_cart_summary($template_id = NULL)
	{
		
		global  $bookingultrapro;
	
		$currency_symbol =  $bookingultrapro->get_option('paid_membership_symbol');
		$date_format =  $bookingultrapro->get_int_date_format();
		$time_format =  $bookingultrapro->service->get_time_format();
		
		if($template_id==NULL){$template_id=$_POST["template_id"];}

		
		$html ='';
		
		if(isset($_COOKIE["BUP_SHOPPING_CART"])) 
		{
		
			$CURRENT_CART = $_COOKIE["BUP_SHOPPING_CART"];
			$CURRENT_CART = stripslashes($CURRENT_CART);
			$CURRENT_CART = json_decode($CURRENT_CART, true);
			
			
			if(count($CURRENT_CART)>0)			
			{
						
				//$html .= '<div class="bup-profile-separator">'.__('Shopping Cart','booking-ultra-pro').'</div>';			
				$html .= '<div class="bup-shopping-cart-cont">';			
				$html .= '<div class="bup-shopping-cart-header">';
				
				//$html .= __('You have: ','booking-ultra-pro'). '(<span>'.count($CURRENT_CART).'</span>)'. __(' items in your cart ','booking-ultra-pro'); //cart div
				
				$html .= '</div>'; //cart header		
				
				
				$html .= ' <table width="100%" class="">
				<thead>
					<tr>
						<th>'.$bookingultrapro->get_template_label("cart_header_1_texts",$template_id).'</th>
						<th>'.$bookingultrapro->get_template_label("cart_header_2_texts",$template_id).'</th>
						 
											
						<th>'.$bookingultrapro->get_template_label("cart_header_3_texts",$template_id).'</th>
					   
					   
						 <th>'.$bookingultrapro->get_template_label("cart_header_4_texts",$template_id).'</th>
						  <th>'.$bookingultrapro->get_template_label("cart_header_5_texts",$template_id).'</th>
						<th>'.$bookingultrapro->get_template_label("cart_header_6_texts",$template_id).'</th>
						
						
					   
					</tr>
				</thead>
				
				<tbody>';
				
				$total_price = 0;				
				
				foreach ($CURRENT_CART as $key => $ITEM)  
				{
					
					
					$booking_time = date($time_format, strtotime($ITEM['book_from']));				
					$booking_date =  date($date_format, strtotime($ITEM['book_date']));
					
					//service			
					$service = $bookingultrapro->service->get_one_service($ITEM['service_id']);
					
					//price				
					$service_price = $bookingultrapro->get_price_format($service->service_price);
					
					$total_unitary_price = $ITEM['book_qty']*$service->service_price;
					$total_price = $total_price+$total_unitary_price ;
					
					//get user				
					$staff_member = get_user_by( 'id', $ITEM['staff_id']);
					
					$html .='<tr>';
					$html .='<td>'.$service->service_title.'</td>';
					$html .='<td>'.$booking_date.'</td>';
					$html .='<td>'.$booking_time.'</td>';
					$html .='<td>'.$staff_member->display_name.'</td>';
					$html .='<td>'.$ITEM['book_qty'].'</td>';
					$html .='<td class="bupcartprice">'.$service_price.'</td>';
					//$html .='<td><a href="#" class="bup-btn-delete-cart-item" item-cart-id="'.$key.'" title="'.__('Delete','booking-ultra-pro').'"><i class="fa fa-trash-o"></i></a></td>';
					$html .='</tr>';
				}
				
				  $html .='<tr>';
					$html .='<td>&nbsp;</td>';
					$html .='<td>&nbsp;</td>';
					$html .='<td>&nbsp;</td>';
					$html .='<td>&nbsp;</td>';
					$html .='<td>&nbsp;</td>';
					$html .='<td class="bupcarttotal"> '. $bookingultrapro->get_price_format($total_price).'</td>';
					//$html .='<td>&nbsp;</td>';			
				  $html .='</tr>';
				
				
				
				$html .='</tbody>
						</table>';
						
				$html .= '</div>'; //cart div
				
				$html .= '<div class="bup-shopping-cart-footer">';
				
				//$html .='  <span class="bupbtncartclear">  <button id="bup-btn-clean-cart" class="bup-button-submit"> '.__('Clear Cart','booking-ultra-pro').'</button></span>';			
				//$html .='  <span class="bupbtncartcheckout">  <button id="bup-btn-checkout-cart" class="bup-button-submit"><i class="fa fa-check" aria-hidden="true"></i> '.__('Checkout','booking-ultra-pro').'</button></span>';
				
				
				$html .= '</div>';
			
			}else{
				
			
				$html .=__('Your cart is empty, please select at least one service.','booking-ultra-pro');
			
		    }
			
			
			
			
		
		}else{
			
			$html .=__('Your cart is empty, please select at least one service.','booking-ultra-pro');
			
		}
		
		
			
		return $html;
			
		
		
		
	}
	
	function update_cart_cookie($CART)
	{
		
		setcookie( "BUP_SHOPPING_CART", json_encode($CART), time() + (30 * DAY_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
		
	}
	
	function add_item_to_cart($order_data)
	{
		
		global  $bookingultrapro;
		
		if(!isset($_COOKIE["BUP_SHOPPING_CART"])) 
		{
			
			//This if the first time then we create the cookie
			$BUP_CART = array();
			
			$BUP_CART[] = array('service_id' => $order_data['service_id'], 'staff_id' => $order_data['staff_id'], 'book_date' => $order_data['bup_date'], 'book_from' => $order_data['book_from'] , 'book_to' => $order_data['book_to'] , 'book_qty' =>1); 
				
			setcookie( "BUP_SHOPPING_CART", json_encode($BUP_CART), time() + (30 * DAY_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
			
			//echo "cookie NOT set ".print_r($_COOKIE["BUP_SHOPPING_CART"]);


		
		}else{ //
			
			//we update the shopping cart witht the new item.			
			$CURRENT_CART = $_COOKIE["BUP_SHOPPING_CART"];
			$CURRENT_CART = stripslashes($CURRENT_CART);
			$CURRENT_CART = json_decode($CURRENT_CART, true);
			
			$CURRENT_CART[] = array('service_id' => $order_data['service_id'], 'staff_id' => $order_data['staff_id'], 'book_date' => $order_data['bup_date'], 'book_from' => $order_data['book_from'] , 'book_to' => $order_data['book_to']  , 'book_qty' =>1); 
			//print_r($CURRENT_CART);
			setcookie( "BUP_SHOPPING_CART", json_encode($CURRENT_CART), time() + (30 * DAY_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
			
		}
		
	}
	
	//This gives us the service price depending on quantity and calculation type
	function calculate_service_price_cart($b_qty,$service_id,$staff_id)
	{
		
		global  $bookingultrapro;
		
		$currency_symbol =  $bookingultrapro->get_option('paid_membership_symbol');		
			
		if($b_qty==''){$b_qty=1;}
		
		$service_details = $bookingultrapro->userpanel->get_staff_service_rate( $staff_id, $service_id );
		$service = $bookingultrapro->service->get_one_service($service_id);	
		
		if($service->service_pricing_calculation_type==1 || $service->service_pricing_calculation_type=='')
		{
			//common calculation			
			$amount= $service_details['price'] * $b_qty;
			
		}elseif($service->service_pricing_calculation_type==2){
			
			//sum all pricing depending on quantity				
			$amount = $this->calculate_with_all_quantity($service_id, $b_qty,true );
			
		}elseif($service->service_pricing_calculation_type==3){
			
			//sum only one price depending on qty			
			$amount = $this->calculate_with_all_quantity($service_id, $b_qty, false );		
		
		}
		
		
		
		$response = array('response' => 'OK', 'amount' => $amount, 'amount_with_symbol' => $currency_symbol.$amount);
		return $response ;	
		
	
	}
	
	function update_purchase_total_inline()
	{
		
		global  $bookingultrapro;
		
		$currency_symbol =  $bookingultrapro->get_option('paid_membership_symbol');
		
			
		$b_qty = $_POST['b_qty'];
		$service_id = $_POST['service_id'];
		$staff_id = $_POST['staff_id'];
		
		if($b_qty==''){$b_qty=1;}
		
		$service_details = $bookingultrapro->userpanel->get_staff_service_rate( $staff_id, $service_id );
		$service = $bookingultrapro->service->get_one_service($service_id);	
		
		if($service->service_pricing_calculation_type==1 || $service->service_pricing_calculation_type=='')
		{
			//common calculation			
			$amount= $service_details['price'] * $b_qty;
			
		}elseif($service->service_pricing_calculation_type==2){
			
			//sum all pricing depending on quantity				
			$amount = $this->calculate_with_all_quantity($service_id, $b_qty,true );
			
		}elseif($service->service_pricing_calculation_type==3){
			
			//sum only one price depending on qty			
			$amount = $this->calculate_with_all_quantity($service_id, $b_qty, false );		
		
		}
		
		
		
		$response = array('response' => 'OK', 'amount' => $amount, 'amount_with_symbol' => $currency_symbol.$amount);
		echo json_encode($response) ;	
		
		die();
	
	}
	
	function calculate_with_all_quantity($service_id, $b_qty, $sum_all=true )
	{
		
		global  $wpdb, $bookingultrapro;
		
		$total = 0;
		
		if($sum_all) //this is used on variable pricing depending on how many persons the client selects
		{
			
			$sql ="SELECT * FROM " . $wpdb->prefix . "bup_service_variable_pricing  
			WHERE rate_service_id='".$service_id."' AND rate_person <= '".$b_qty."';";
		
		}else{ //this will apply a unique price depending on how many persons the client selects
			
			$sql ="SELECT * FROM " . $wpdb->prefix . "bup_service_variable_pricing  
			WHERE rate_service_id='".$service_id."' AND rate_person = '".$b_qty."';";
			
		}
		
		$rates = $wpdb->get_results($sql);		
		
		if (!empty($rates))
		{			
			foreach($rates as $rate) 
			{
				$total = $total+ $rate->rate_price;				
				
			}			
		}
		
		return $total;
		
	}
	
	//this displays thank you page
	public function ubp_book_step_4()
	{
		
		global  $bookingultrapro;
		
			
		$order_key = $_POST['order_key'];
		
		$html ='';
		$message ='';	
		
		if($order_key=='bank')
		{
			$message =$bookingultrapro->get_option('gateway_bank_success_message'); 	
			
		}elseif($order_key=='stripe'){
			
			$message =$bookingultrapro->get_option('gateway_stripe_success_message'); 
			
		
		}else{
			
			$message =$bookingultrapro->get_option('gateway_free_success_message'); 
		
		
		}
		
		if($message==''){
			
			$message = __("Thank you for your booking. Please check your email.",'booking-ultra-pro');
			
		
		}
				
				
		$html .= '<p>'.$message.'</p>'; 
		
		echo $html ;	
		
		die();
		
		
	}
	
	//used for reschedule
	public function ubp_check_adm_availability_admin()
	{
		
		global  $bookingultrapro;
		
		$business_hours = get_option('bup_business_hours');
		$time_format = $this->get_time_format();		
		
		$slot_length= $bookingultrapro->get_option('bup_time_slot_length');
		$slot_length_minutes= $slot_length*60;
		
		$display_only_from_hour=  $bookingultrapro->get_option('display_only_from_hour');
		$allow_bookings_outside_b_hours=  $bookingultrapro->get_option('allow_bookings_outsite_business_hours');
		
		
		$time_slots = array();		
		$b_category = $_POST['b_category'];			
		$b_staff = $_POST['b_staff'];
		$b_date = $_POST['b_date'];	
		
		
				
		
		$date_format = $this->get_date_format_conversion();	
		$date_f = DateTime::createFromFormat($date_format, $b_date);
		
		$html = '';
		
		//get days for this service		
		$date_from=  $date_f->format('Y-m-d');	
		$to_sum= $this->get_days_to_display();  
		$end_date=  date("Y-m-d", strtotime("$date_from + $to_sum day"));			
				
		//get random user		
		$staff_id = $this->get_prefered_staff($b_staff, $b_category);				
		
		// Schedule.
        $items_schedule = $bookingultrapro->userpanel->get_working_hours($staff_id);
		
		//staff member		
		$staff_member = $bookingultrapro->userpanel->get_staff_member($staff_id);			
		
		$cdiv = 0 ;				
		$service = $this->get_one_service($b_category);
		
		if($_POST['b_date']=='')
		{		
			$html .='<p>'.__("Please select a date.",'booking-ultra-pro').'</p>';			
			echo $html;
			die();
		
		}
		
		if($_POST['b_category']=='')
		{		
			$html .='<p>'.__("Please select a service.",'booking-ultra-pro').'</p>';			
			echo $html;
			die();
		
		}
		
		//Does the user offer this service?				
		if($bookingultrapro->userpanel->staff_offer_service( $staff_id, $b_category ))
		{
			$html .= '<div class="bup-selected-staff-booking-info">'; 
			$html .= '<p>'. __('Below you can find a list of available time slots for ','booking-ultra-pro').'<strong>'.$service->service_title.'</strong> '.__('by ','booking-ultra-pro').'<strong>'.$staff_member->display_name.'</strong>.'.'<p>';	
			$html .= '</div>';
			
			$available_previous =true;
			while (strtotime($date_from) < strtotime($end_date)) 
			{
				 $cdiv++;				 
				 $day_num_of_week = date('N', strtotime($date_from));	
				 
				 //is the staff member working on this day?			 
				  if(isset($items_schedule[$day_num_of_week]))
				  {					   
					 
					  $html .= '<h3>'.$bookingultrapro->commmonmethods->formatDate($date_from).'</h3>';	  
					  $html .= '<div class="bup-time-slots-divisor" id="bup-time-sl-div-'.$cdiv.'">';			  
					  $html .= '<ul class="bup-time-slots-available-list">';	
					  
					 //get available slots for this date				 
					 $time_slots = $this->get_time_slot_public_for_staff($day_num_of_week,  $staff_id, $b_category, $time_format);
					 
					 //check if staff member is in holiday this day					   
					  $is_in_holiday = $this->is_in_holiday($staff_id, $date_from);					  
					  
					  //staff hourly						 
					  $staff_hourly = $this->get_hourly_for_staf($staff_id, $day_num_of_week);	
					 
					 
					 $cdiv_range = 0 ;
					 					 
					 foreach($time_slots as $slot)
					 {
						 $cdiv_range++;	
						 
						 
						  $day_time_slot = date('Y-m-d', strtotime($date_from)).' '.$slot['from'].':00';
						  
						  $current_time_slot = $slot['from'].':00';
						  $increased_minutes = date('H:i:s', strtotime( $current_time_slot ) +$slot_length_minutes);
						  //$to_slot_limit = $date_from.' '. $increased_minutes;
						  $to_slot_limit = $date_from.' '. $slot['to'].':00';
						  $day_time_slot_to = $to_slot_limit;
							 
						  $staff_time_slots = array();					 
						  $staff_time_slots = $this->get_time_slots_availability_for_day($staff_id, $b_category, $day_time_slot, $day_time_slot_to);	
						  
						 //check if staff member is on break time for this day.						
						$is_in_break = $this->is_in_break($staff_id, $day_num_of_week, $slot['from'] , $slot['to']);
						
						$is_slot_outside_working_hours = false;
						if($this->is_booking_outside_working_hours($staff_hourly, $time_to, $date_from ) && $allow_bookings_outside_b_hours=='no')
						{
							$is_slot_outside_working_hours = true;							
						}
						
						
						if($staff_time_slots['available']==0 || $is_in_break || $staff_time_slots['busy']==true  ||  $is_in_holiday)
						{							
							$available_slot =false;
									
						}else{								
									
							$available_slot =true;							
						}
							
						$time_from = $slot['from'];
						$time_to = $slot['to'];
						
						//padding before?
						if($service->service_padding_before!='' && $service->service_padding_before!=0 )
						{
							//previous is not available, then we need to add padding
							if(!$available_previous)
							{
								$minutes_to_increate = $service->service_padding_before;								
								$increased_from = date('H:i:s', strtotime($time_from.':00')+$minutes_to_increate);
								$increased_from = date('H:i', strtotime($increased_from));							
								$time_from = $increased_from;
									
							}
								
						}				 
						 
											 
						 
						 if($display_only_from_hour=='yes' || $display_only_from_hour=='' )
						 {
							  //reduced view
							 $time_to_display = '&nbsp;&nbsp;'.date($time_format, strtotime($time_from));
						 }else{
							 
							 $time_to_display = '&nbsp;&nbsp;'.date($time_format, strtotime($time_from)).' &ndash; '.date($time_format, strtotime($time_to)).'';					 			
						 
						 }
						 
						 
						 //is All Day event?						
						if($service->service_duration==86400)	
						{
							$time_from = '00:00';
						    $time_to = '23:59';						
						}	 
						
						
						if($time_to>$staff_hourly->avail_to || $time_to<$staff_hourly->avail_from)
						{
							$display_unavailable = 'no';
							$is_slot_available = false;
						}
						
						
						if(!$is_slot_outside_working_hours)
						{
						 
						 
							
							 $html .= '<li id="bup-time-slot-hour-range-'.$cdiv.'-'.$cdiv_range.'">';					
							 $html .= '<div class="bup-timeslot-time"><i class="fa fa-clock-o"></i>'.$time_to_display.'</div>';
							 $html .= '<div class="bup-timeslot-count"><span class="spots-available">'.$staff_time_slots['label'].'</span></div>';
							 
							 $html .= '<span class="bup-timeslot-people">';
							 
							
							
							if($staff_time_slots['available']==0 || $is_in_break || $staff_time_slots['busy']==true ||  $is_in_holiday)
							{
								$button_class = 'bup-button-blocked ';
								$button_label = __('Unavailable','booking-ultra-pro');
							
							}else{
								
								$button_class = 'bup-button bup-btn-book-app-admin ';
								$button_label = __('Select Time Slot','booking-ultra-pro');
							
							}
							
							
							$html .= '<button class="new-appt '.$button_class.'" bup-data-date="'.date('Y-m-d', strtotime($date_from)).'" bup-data-timeslot="'.$time_from.'-'.$time_to.'" bup-data-service-staff="'.$b_category.'-'.$staff_id.'">'; //category-userid
							
							$html .= '<span class="button-timeslot"></span><span class="bup-button-text">'. $button_label.'</span></button>';
							
							
							
							 $html .= '</span>';						
							 $html .= '</li>';
							 
							 	
						 
						 $available_previous = $available_slot;
						 
						}
					 
					  }
					  
					  $html .='</ul>';			  			  
					  $html .= '</div>'; //end time slots divisor
				  
				  
				  } //end if working			  
				  
				 
				 //increase date
				 $date_from = date ("Y-m-d", strtotime("+1 day", strtotime($date_from))); 			 
				 
				 
			 }  //end while
			 
		}else{
			
			
			$html .='<p>'.__("This Provider doesn't offer this service.",'booking-ultra-pro').'</p>';
			
			
		
		}  //end if
		 		
		
		echo $html ;		
		die();		
	
	}
	
	public function get_padding_add_frm($service_id = null, $padding_before = null , $padding_after = null )
	{
		global  $bookingultrapro, $bupcomplement;
		
				
		//$html = '<div class="bup-add-break-cont">';
		
		$html .=''.$this->get_padding_drop_downs($service_id,  'bup-padding-before', $padding_before). '<span> </span>' .$this->get_padding_drop_downs($service_id,  'bup-padding-after', $padding_after).'';
		
		//$html .= '</div>';
		
		return $html;
		
	
	}
	
	//returns the business hours drop down
	public function get_padding_drop_downs($service_id,  $select_name,$actual_value)
	{
		global  $bookingultrapro;
		
		$html = '';
		
		$max_hours = 43200; //12 hours in seconds		
		$min_minutes = 15;
		
		$min_minutes=$min_minutes*60;
		
		$html .= '<select name="'.$select_name.'" id="'.$select_name.'">';
		$html .= '<option value="" '.$selected.'>'.__("OFF",'booking-ultra-pro').'</option>';
		
		for ($x = $min_minutes; $x <= $max_hours; $x=$x+$min_minutes)
		{
			$selected = '';
			if($actual_value==$x){$selected='selected="selected"';}
		
			$html .= '<option value="'.$x.'" '.$selected.'>'.$this->get_service_duration_format($x).'</option>';
			
		}
		
		$html .= '</select>';
		
		return $html;
		
	
	}
	
	public function get_date_format_conversion()
    {
		global  $bookingultrapro;
		$date_format = $bookingultrapro->get_option('bup_date_picker_format');
		
		if($date_format==''){
			
			$date_format = 'm/d/Y';
			
		}
        return $date_format;
    }
	
	//function used for the admin
	public function ubp_check_adm_availability()
	{
		
		global  $bookingultrapro;
		
		$business_hours = get_option('bup_business_hours');
		$time_format = $this->get_time_format();
		
		
		$slot_length= $bookingultrapro->get_option('bup_time_slot_length');
		$slot_length_minutes= $slot_length*60;
		
		$display_only_from_hour=  $bookingultrapro->get_option('display_only_from_hour');
		$allow_bookings_outside_b_hours=  $bookingultrapro->get_option('allow_bookings_outsite_business_hours');
		
		$time_slots = array();		
		$b_category = $_POST['b_category'];
		$b_staff = $_POST['b_staff'];		
		$b_date = $_POST['b_date'];
	
		
		$date_format = $this->get_date_format_conversion();	
		$date_f = DateTime::createFromFormat($date_format, $b_date);
		
		
		$html = '';
		
		//get days for this service		
		$date_from=  $date_f->format('Y-m-d');		
		$to_sum= $this->get_days_to_display();  
		$end_date=  date("Y-m-d", strtotime("$date_from + $to_sum day"));			
				
		//get random user		
		$staff_id = $this->get_prefered_staff($b_staff, $b_category);				
		
		// Schedule.
        $items_schedule = $bookingultrapro->userpanel->get_working_hours($staff_id);
		
		//staff member		
		$staff_member = $bookingultrapro->userpanel->get_staff_member($staff_id);			
		
		$cdiv = 0 ;				
		$service = $this->get_one_service($b_category);
		
		if($_POST['b_date']=='')
		{		
			$html .='<p>'.__("Please select a date.",'booking-ultra-pro').'</p>';			
			echo $html;
			die();
		
		}
		
		if($_POST['b_category']=='')
		{		
			$html .='<p>'.__("Please select a service.",'booking-ultra-pro').'</p>';			
			echo $html;
			die();
		
		}
		
		//Does the user offer this service?				
		if($bookingultrapro->userpanel->staff_offer_service( $staff_id, $b_category ))
		{
			$html .= '<div class="bup-selected-staff-booking-info">'; 
			$html .= '<p>'. __('Below you can find a list of available time slots for ','booking-ultra-pro').'<strong>'.$service->service_title.'</strong> '.__('by ','booking-ultra-pro').'<strong>'.$staff_member->display_name.'</strong>.'.'<p>';	
			$html .= '</div>';
			
		
			while (strtotime($date_from) < strtotime($end_date)) 
			{
				 $cdiv++;
				 
				 $day_num_of_week = date('N', strtotime($date_from));	
				 
				 //is the staff member working on this day?			 
				  if(isset($items_schedule[$day_num_of_week]))
				  {					   
					 
					  $html .= '<h3>'.$bookingultrapro->commmonmethods->formatDate($date_from).'</h3>';	  
					  $html .= '<div class="bup-time-slots-divisor" id="bup-time-sl-div-'.$cdiv.'">';  
					  $html .= '<ul class="bup-time-slots-available-list">';	
					  
					 //get available slots for this date				 
					 $time_slots = $this->get_time_slot_public_for_staff($day_num_of_week,  $staff_id, $b_category, $time_format);
					 
					 //check if staff member is in holiday this day					   
					 $is_in_holiday = $this->is_in_holiday($staff_id, $date_from);
					 
					  //staff hourly						 
					  $staff_hourly = $this->get_hourly_for_staf($staff_id, $day_num_of_week);
					 
					 
					 $cdiv_range = 0 ;
					 
					 $slot_previous = array();
					 $available_previous =  true;
					 
					// print_r($time_slots );
					 
					 foreach($time_slots as $slot)
					 {
						 $cdiv_range++;	
						 
						  $day_time_slot = date('Y-m-d', strtotime($date_from)).' '.$slot['from'].':00';
						  $current_time_slot = $slot['from'].':00';
						  $increased_minutes = date('H:i:s', strtotime( $current_time_slot ) +$slot_length_minutes);
						  $to_slot_limit = $date_from.' '. $slot['to'].':00';
						  $day_time_slot_to = $to_slot_limit;
						  
						  $staff_time_slots = array();					 
						  $staff_time_slots = $this->get_time_slots_availability_for_day($staff_id, $b_category, $day_time_slot, $day_time_slot_to);
						  
						 // print_r($staff_time_slots);
					  
					  	   //check if staff member is on break time for this day.						
					 	$is_in_break = $this->is_in_break($staff_id, $day_num_of_week, $slot['from'] , $slot['to']);
						
						$time_from = $slot['from'];
						$time_to = $slot['to'];
							
							
						//check if hour is available to book, we have to use the server time		 
						 $current_slot_time_stamp = strtotime($date_from.' '.$time_from.':00');		 
						 $current_site_time_stamp = strtotime(date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ));
						 
						 $is_passed = false;						 
						 if($current_site_time_stamp>$current_slot_time_stamp)
						 {							 
							 $is_passed = true;	
						 }	
						 
						 
						 $is_slot_outside_working_hours = false;
						if($this->is_booking_outside_working_hours($staff_hourly, $time_to, $date_from ) && $allow_bookings_outside_b_hours=='no')
						{
							$is_slot_outside_working_hours = true;							
						}
						
								
					  
				
						if($staff_time_slots['available']==0 || $is_in_break || $staff_time_slots['busy']==true || $is_in_holiday || $is_passed )
						   {							
								$available_slot =false;
									
							}else{								
									
								$available_slot =true;	
							}
								
							
							//padding before?
							if($service->service_padding_before!='' && $service->service_padding_before!=0)
							{
								//previous is not available, then we need to add padding
								if(!$available_previous)
								{
									$minutes_to_increate = $service->service_padding_before;	
									
									$increased_from = date('H:i:s', strtotime($time_from.':00')+$minutes_to_increate);
									$increased_from = date('H:i', strtotime($increased_from));							
									$time_from = $increased_from;
										
								}
									
							}
							 
							 
						 
						 if($display_only_from_hour=='yes' || $display_only_from_hour=='' )
						 {
							  //reduced view
							 $time_to_display = '&nbsp;&nbsp;'.date($time_format, strtotime($time_from));
						 }else{
							 
							 $time_to_display = '&nbsp;&nbsp;'.date($time_format, strtotime($time_from)).' &ndash; '.date($time_format, strtotime($time_to)).'';					 			
						 
						 }
						 
						 if(!$is_slot_outside_working_hours) 
						 {
						 
						
						 $html .= '<li id="bup-time-slot-hour-range-'.$cdiv.'-'.$cdiv_range.'">';					
						 $html .= '<div class="bup-timeslot-time"><i class="fa fa-clock-o"></i>'.$time_to_display.'</div>';
						 $html .= '<div class="bup-timeslot-count"><span class="spots-available">'.$staff_time_slots['label'].'</span></div>';
						 
						 $html .= '<span class="bup-timeslot-people">';		 
						 
						
						
						if($staff_time_slots['available']==0 || $is_in_break || $staff_time_slots['busy']==true || $is_in_holiday || $is_passed)
						{
							$button_class = 'bup-button-blocked ';
							$button_label = __('Unavailable','booking-ultra-pro');
							$unavailable =true;
						
						}else{
							
							$button_class = 'bup-button bup-btn-book-app';
							$button_label = __('Select Time Slot','booking-ultra-pro');
							$unavailable =false;
						
						}
						
						//is All Day event?						
						if($service->service_duration==86400)	
						{
							$time_from = '00:00';
						    $time_to = '23:59';						
						}
						
						if($time_to>$staff_hourly->avail_to || $time_to<$staff_hourly->avail_from)
						{
							$display_unavailable = 'no';
							$is_slot_available = false;
						}
						
						$html .= '<button class="new-appt '.$button_class.'" bup-data-date="'.date('Y-m-d', strtotime($date_from)).'" bup-data-timeslot="'.$time_from.'-'.$time_to.'" bup-data-service-staff="'.$b_category.'-'.$staff_id.'">'; //category-userid
							
						$html .= '<span class="button-timeslot"></span><span class="bup-button-text">'. $button_label.'</span></button>';
							
							
							
						 $html .= '</span>';						
						 $html .= '</li>';
						 
						 $slot_previous = $slot ;	
						 $available_previous = $available_slot;	 	
						 
						 
						 } //end if display slot
						 
						
						 
						 
						
						 
					 
					  }
					  
					  $html .='</ul>';			  			  
					  $html .= '</div>'; //end time slots divisor
				  
				  
				  } //end if working			  
				  
				 
				 //increase date
				 $date_from = date ("Y-m-d", strtotime("+1 day", strtotime($date_from))); 			 
				 
				 
			 }  //end while
			 
		}else{
			
			
			$html .='<p>'.__("This Provider doesn't offer this service.",'booking-ultra-pro').'</p>';
			
			
		
		}  //end if
		 		
		
		echo $html ;		
		die();		
	
	}
	
	public function get_days_to_display()
	{
		global  $bookingultrapro;
		
		$days = $bookingultrapro->get_option('bup_calendar_days_to_display');
		
		if($days==''){
			
			$days = 7;				
		}
		
		
		return $days;
		
	}
	
	public function ubp_parse_customizer_texts($text, $service, $provider = NULL , $date_from = NULL)
	{
		global  $bookingultrapro;
		
		$time_format = $this->get_time_format();		
				
		$from_at = date($time_format, strtotime($date_from));
		$from_date = $bookingultrapro->commmonmethods->formatDate($date_from);
		
		$text = str_replace("[BUP_SERVICE]", $service->service_title,  $text);
		$text = str_replace("[BUP_PROVIDER]", $provider->display_name,  $text);
		
		$text = str_replace("[BUP_AT]", $from_at,  $text);
		$text = str_replace("[BUP_DAY]", $from_date,  $text);
		
		return $text;
		
	
	}
	
	function convert_from_another_time($source, $source_timezone, $dest_timezone)
	{
		
		$offset = $dest_timezone - $source_timezone;
		
		if($offset == 0)
			return $source;
			
		$target = new DateTime($source_format);
		
	   
	   $hours_adjust = "+". $offset ." hours";
	   
	   $target = date('Y-m-d H:i:s',strtotime($hours_adjust,strtotime($source)));

		
		return $target;
	}
	
	public function ubp_book_step_2()
	{
		
		global  $bookingultrapro;
		
		$business_hours = get_option('bup_business_hours');
		$time_format = $this->get_time_format();
		
		$slot_length= $bookingultrapro->get_option('bup_time_slot_length');
		$slot_length_minutes= $slot_length*60;	
		
		$display_only_from_hour=  $bookingultrapro->get_option('display_only_from_hour');		
		$display_unavailable= $bookingultrapro->get_option('display_unavailable_slots_on_front');
		
		$allow_bookings_outside_b_hours=  $bookingultrapro->get_option('allow_bookings_outsite_business_hours');
		
		$response = array();
		
		$time_slots = array();		
		$b_category = $_POST['b_category'];
		$b_date = $_POST['b_date'];		
		$b_staff = $_POST['b_staff'];
		$b_location = $_POST['b_location'];
		$template_id = $_POST['template_id'];
		$visitor_offset_time = $_POST['visitor_offset_time'];
		
		$date_format = $this->get_date_format_conversion();	
		$date_f = DateTime::createFromFormat($date_format, $b_date);
						
		$html = '';
		
		//get days for this service		
		$date_from=  $date_f->format('Y-m-d');	
		$to_sum= $this->get_days_to_display();  
		$end_date=  date("Y-m-d", strtotime("$date_from + $to_sum day"));			
		
		
		//location and staff are empty
		if($b_location=='' && $b_staff =='')		
		{
			//get random user		
			$staff_id = $this->get_prefered_staff($b_staff, $b_category);			
		
		//location set but staff member disabled
		}elseif($b_location!='' && $b_staff ==''){
			
			//get random user for this location		
			$staff_id = $this->get_random_staff_member_for_location($b_location,  $b_category);
						
		}else{			
					
			$staff_id = $b_staff;					
		}
		
		// Schedule.
        $items_schedule = $bookingultrapro->userpanel->get_working_hours($staff_id);
		
		//staff member		
		$staff_member = $bookingultrapro->userpanel->get_staff_member($staff_id);			
		
		$cdiv = 0 ;				
		$service = $this->get_one_service($b_category);
		
		if($_POST['b_date']=='')
		{		
			$html .='<p>'.__("Please select a date.",'booking-ultra-pro').'</p>';				
			$response = array('response' => 'NOOK', 'content' => $html);		
			echo json_encode($response);
			die();		
		}
		
		if($_POST['b_category']=='')
		{		
			$html .='<p>'.__("Please select a service.",'booking-ultra-pro').'</p>';
			$response = array('response' => 'NOOK', 'content' => $html);		
			echo json_encode($response);			
			die();		
		}
		
		if($staff_id=='')
		{		
			$html .='<p>'.__("Please select a provider.",'booking-ultra-pro').'</p>';
			$response = array('response' => 'NOOK', 'content' => $html);		
			echo json_encode($response);			
			die();		
		}
		
		//parse content		
		$content_text = $bookingultrapro->get_template_label("step2_texts",$template_id);		
		$content_text = $this->ubp_parse_customizer_texts($content_text, $service, $staff_member);
		
		//minimized layout		
		$selected_layout = $bookingultrapro->get_template_label("layout_selected",$template_id);
		
		//echo "template : ".$selected_template;
		
		$class_day_divisor = '';
		$class_ul_divisor = '';
		$class_li_divisor = '';
		$class_h3 = '';
		$class_book_button = '';
		
		if($selected_layout==2) //minified		
		{
			$bg_color = $bookingultrapro->get_template_label("bup_cus_bg_color",$template_id);
			$class_day_divisor = ' bup-time-slots-divisor-reduced ';
			$class_ul_divisor = ' bup-time-slots-available-list-bupreduced ';
			$class_li_divisor = ' bupreduced ';
			$class_i_icon_bg = ' style=" color: '.$bg_color.' " ';
			$class_book_button = ' style=" display:none" ';
			$class_h3 = 'reduced';			
			
		}
		
		
		$wp_date_format = get_option( 'date_format' );
		
		//Does the user offer this service?				
		if($bookingultrapro->userpanel->staff_offer_service( $staff_id, $b_category ))
		{
			$html .= '<div class="bup-selected-staff-booking-info">'; 
			
			$html .= $content_text;		
			
			$html .= '</div>';
			
			
		    $available_previous = true;
			while (strtotime($date_from) < strtotime($end_date)) 
			{
				 $cdiv++;
				 
				 $day_num_of_week = date('N', strtotime($date_from));	
				 
				  if(isset($items_schedule[$day_num_of_week]))
				  {			 
					 			  
					  $html .= '<div class="bup-time-slots-divisor '.$class_day_divisor.'" id="bup-time-sl-div-'.$cdiv.'">';	
					  
					  if($selected_layout==2) //minified		
					  {					  
					  
					  	  $html .= '<h3 class="'.$class_h3.'">'.date($wp_date_format, strtotime($date_from)).'</h3>';					  
					  }else{
						  
						  $html .= '<h3>'.$bookingultrapro->commmonmethods->formatDate($date_from).'</h3>';
						  
					  }
					    
					  $html .= '<ul class="bup-time-slots-available-list '.$class_ul_divisor.'">';	
					  
					  //get available slots for this date				 
					  $time_slots = $this->get_time_slot_public_for_staff($day_num_of_week,  $staff_id, $b_category, $time_format);
					 
					 //check if staff member is in holiday this day					   
					  $is_in_holiday = $this->is_in_holiday($staff_id, $date_from);	
					  
					  //staff hourly						 
					  $staff_hourly = $this->get_hourly_for_staf($staff_id, $day_num_of_week);	
				 
					 $cdiv_range = 0 ;
					 
					 $at_least_one_available=false;
					 
					 foreach($time_slots as $slot)
					 {
						 $cdiv_range++;						 
						 
						 $day_time_slot = date('Y-m-d', strtotime($date_from)).' '.$slot['from'].':00'; 
						 $current_time_slot = $slot['from'].':00';
						 $increased_minutes = date('H:i:s', strtotime( $current_time_slot ) +$slot_length_minutes);
						 
						 $to_slot_limit = $date_from.' '. $slot['to'].':00';						
						 $day_time_slot_to = $to_slot_limit;
						 
						 $staff_time_slots = array();					 
						 $staff_time_slots = $this->get_time_slots_availability_for_day($staff_id, $b_category, $day_time_slot, $day_time_slot_to);	
					  
					     //check if staff member is on break time for this day.						
					      $is_in_break = $this->is_in_break($staff_id, $day_num_of_week, $slot['from'] , $slot['to']);
					   
					     //check if staff member is working on special schedule.						
					     $is_in_special_schedule = $this->is_in_special_schedule($staff_id, $date_from, $slot['from'] , $slot['to']);
					   
					   if($staff_time_slots['available']==0 || $is_in_break || $staff_time_slots['busy']==true || $is_in_holiday || $is_in_special_schedule )
					   {							
							$available_slot =false;							
								
						}else{												
								
							$available_slot =true;							
						}
							
						$time_from = $slot['from'];
						$time_to = $slot['to'];
						$time_to_overlap = $slot['to_overlap'];
						
						$time_from_display = $slot['from_display'];
						$time_to_display = $slot['to_display'];
						
						//padding before?
						if($service->service_padding_before!='' && $service->service_padding_before!=0 )
						{
							//previous is not available, then we need to add padding
							//if(!$available_previous)
							//{
								$minutes_to_increate = $service->service_padding_before;									
								$increased_from = date('H:i:s', strtotime($time_from.':00')+$minutes_to_increate);
								$increased_from = date('H:i', strtotime($increased_from));							
								$time_from = $increased_from;
									
							//}
								
						}				
							 
						 
						 if($display_only_from_hour=='yes' || $display_only_from_hour=='' )
						 {
							  //reduced view
							 $time_to_display = '&nbsp;&nbsp;'.date($time_format, strtotime($time_from));
						 }else{
							 
							 $time_to_display = '&nbsp;&nbsp;'.date($time_format, strtotime($time_from)).' &ndash; '.date($time_format, strtotime($time_to)).'';					 			
						 
						 }
						 
						 
						 //check if hour is available to book, we have to use the server time		 
						 $current_slot_time_stamp = strtotime($date_from.' '.$time_from.':00');		 
						 $current_site_time_stamp = strtotime(date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ));
						 
						 $is_passed = false;						 
						 if($current_site_time_stamp>$current_slot_time_stamp)
						 {							 
							 $is_passed = true;	 						
						 }
						 
						 //min prior to booking?						 
						 $min_hours_prior_booking = $this->check_prior_to_booking($current_slot_time_stamp);
						 
						 //special scheduling?						 
						 $li_class = '';					 	
						
						if($staff_time_slots['available']==0 || $is_in_break || $is_passed || $staff_time_slots['busy']==true || $is_in_holiday || $is_in_special_schedule || !$min_hours_prior_booking)
						{
							$button_class = 'bup-button-blocked ';
							$button_label = __('Unavailable','booking-ultra-pro');
							$li_avail_icon = '';							
							$class_disable_price_line = ' style=" text-decoration: line-through " ';							
							$is_slot_available = false;
						
						}else{
							
							$button_class = 'bup-button bup-btn-book-app';
							$button_label = __('Book Appointment','booking-ultra-pro');
							$li_class = 'bup-btn-book-app-li';	
							
							//used in minified mode
							$li_avail_icon = 'fa fa-check-square-o';
							$class_disable_price_line = ' ';	
							$is_slot_available = true;				
						}
						
						//is All Day event?						
						if($service->service_duration==86400)	
						{
							$time_from = '00:00';
						    $time_to = '23:59';						
						}	
						
						
						$is_slot_outside_working_hours = false;
						if($this->is_booking_outside_working_hours($staff_hourly, $time_to, $date_from ) && $allow_bookings_outside_b_hours=='no' )
						{
							$is_slot_outside_working_hours = true;							
						}
						
						
						//do we have to hide the slot in the fron-end?						
						if($display_unavailable=='no' && !$is_slot_available)
						{							
							$is_slot_visible = false;
							
						}else{
							
							$is_slot_visible = true;							
						}
						
						
						
				       if($is_slot_visible && !$is_slot_outside_working_hours)
					   {
						   
						 $at_least_one_available=true;							 	 
						
						 $html .= '<li class="'.$li_class.' '.$class_li_divisor.'"   id="bup-time-slot-hour-range-'.$cdiv.'-'.$cdiv_range.'" bup-data-date="'.date('Y-m-d', strtotime($date_from)).'" bup-max-capacity="'.$staff_time_slots['capacity'].'" bup-max-available="'.$staff_time_slots['available'].'" bup-data-timeslot="'.$time_from .'-'.$time_to.'" bup-data-service-staff="'.$b_category.'-'.$staff_id.'" >';
						 
						 //
						 
						 if($selected_layout==2) //minified		
						 {							 
							$html .= ' <span class="bup-front-mini-icons">							
							<i class="'.$li_avail_icon.'" '.$class_i_icon_bg.'></i> </span>';							
						 }
						 
						 					
						 $html .= '<div class="bup-timeslot-time" '.$class_disable_price_line.'><i class="fa fa-clock-o"></i>'.$time_to_display.'</div>';
						 $html .= '<div class="bup-timeslot-count"><span class="spots-available">'.$staff_time_slots['label'].'</span></div>';
						 
						 $html .= '<span class="bup-timeslot-people" '.$class_book_button.'>';	
						 
						
						 $html .= '<button class="new-appt '.$button_class.'" bup-data-date="'.date('Y-m-d', strtotime($date_from)).'" bup-max-capacity="'.$staff_time_slots['capacity'].'" bup-max-available="'.$staff_time_slots['available'].'" bup-data-timeslot="'.$time_from .'-'.$time_to.'" bup-data-service-staff="'.$b_category.'-'.$staff_id.'">'; //category-userid
						
						$html .= '<span class="button-timeslot"></span><span class="bup-button-text">'. $button_label.'</span></button>';
						
						 $html .= '</span>';						
						 $html .= '</li>';	
						 
					 	} // end if						 
						 
						 $available_previous =$available_slot;					 
					 
					 }
					  
					  $html .='</ul>';			  			  
					  $html .= '</div>'; //end time slots divisor
					  
					   //is the whole day signed off			 
						 if(!$at_least_one_available)
						 {
							 $html .='<p class="bup-unavailable-slot">'.__("There are no available time slots on this day.",'booking-ultra-pro').'</p>';
							 
						 }
				  
				  
				  } //end if working			  
				  
				 
				 //increase date
				 $date_from = date ("Y-m-d", strtotime("+1 day", strtotime($date_from))); 			 
				 
				 
			 }  //end while
			 
			 
			
			 
		}else{
			
			
			$html .='<p>'.__("This Provider doesn't offer this service.",'booking-ultra-pro').'</p>';
			
			
		
		}  //end if
		 		
		
		$response = array('response' => 'OK', 'content' => $html);
		echo json_encode($response) ;		
		die();		
	
	}
	
	//this will check if the user is within a special schedule	
	function is_booking_outside_working_hours($staff_hourly, $time_to, $date_from )
	{
		
		global  $wpdb, $bookingultrapro, $bupcomplement;
		
		$is_outside_working_hours = false;
		
		
		if($time_to>date('H:i',strtotime($staff_hourly->avail_to)) || $time_to<date('H:i',strtotime($staff_hourly->avail_from)))
						{
					//$display_unavailable = 'no';
					$is_outside_working_hours = true;
					
					//echo " On Day : ". date('Y-m-d', strtotime($date_from)). " " . "Times to: " . $time_to. " Overlap :" .$time_to_overlap. " Staff available to: " .date('H:i',strtotime($staff_hourly->avail_to)). '</br></br>';
					
				}else{
					
					
					
					$is_outside_working_hours = false;
					
				}
		
		return $is_outside_working_hours;  
		
	}
	
	//this will check if the user is within a special schedule	
	function is_in_special_schedule($staff_id, $day, $from_time, $to_time)
	{
		
		global  $wpdb, $bookingultrapro, $bupcomplement;
		
		$from_time = $from_time.':00';
		$to_time = $to_time.':00';
		
		$ret = false;  
				
		if(isset($bupcomplement))
		{
				
			$sql ="SELECT * FROM " . $wpdb->prefix . "bup_staff_availability_rules  
			WHERE special_schedule_date = '".$day."' AND special_schedule_staff_id = %d  AND  (special_schedule_time_to > '".$from_time."'  AND special_schedule_time_from < '".$to_time."'  );";
			
				
			$sql = $wpdb->prepare($sql,array($staff_id));	
			$rows = $wpdb->get_results($sql);
			
			if ( !empty( $rows )) 
			{			
				$ret = true;	
						
			}else{
				
				$ret = false;
				
			}
		
		}
		
		
		return $ret;
		
		
	
	}
	
	public function check_prior_to_booking($current_slot_time_stamp)
	{
		global  $bookingultrapro, $bupcomplement;
		
		
		if(isset($bupcomplement))
		{
			
						
			//min time in hours
			$min_hours_prior = $bookingultrapro->get_option('bup_min_prior_booking');
			
			if($min_hours_prior>=24) //by days
			{
				$current_site_time_stamp = strtotime(date( 'Y-m-d', current_time( 'timestamp', 0 ) ));
				$current_slot_time_stamp = strtotime(date( 'Y-m-d', $current_slot_time_stamp ));	
							
			}else{ //by hours
			
				$current_site_time_stamp = strtotime(date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ));		
			}
			
			if($min_hours_prior!=0 && $min_hours_prior!=''  ) //we need to check prior hours
			{
				
				$diff =  $current_slot_time_stamp - $current_site_time_stamp;
				$diff_in_hrs = $diff/3600;
				
				if($diff_in_hrs<=$min_hours_prior)
				{					
					return false;	///prior time			
				
				}else{
					
					return true;				
				}
				
							
			}else{ /// do not check	
			
			
				return true;
				
			
			}	
			
		
		}else{
			
			return true;
			
		}
		
		
	}
	
	public function ubp_book_step_2_hotels()
	{
		
		global  $bookingultrapro;
		
		$business_hours = get_option('bup_business_hours');
		$time_format = $this->get_time_format();
		
		$slot_length= $bookingultrapro->get_option('bup_time_slot_length');
		$slot_length_minutes= $slot_length*60;	
		
		$display_only_from_hour=  $bookingultrapro->get_option('display_only_from_hour');		
		$display_unavailable= $bookingultrapro->get_option('display_unavailable_slots_on_front');
		$allow_bookings_outside_b_hours=  $bookingultrapro->get_option('allow_bookings_outsite_business_hours');
		
		$response = array();
		
		$time_slots = array();		
		$b_category = $_POST['b_category'];
		$b_date = $_POST['b_date'];		
		$b_staff = $_POST['b_staff'];
		$b_location = $_POST['b_location'];
		$template_id = $_POST['template_id'];
		
		$date_format = $this->get_date_format_conversion();	
		$date_f = DateTime::createFromFormat($date_format, $b_date);
						
		$html = '';
		
		//get days for this service		
		$date_from=  $date_f->format('Y-m-d');	
		$to_sum= $this->get_days_to_display();  
		$end_date=  date("Y-m-d", strtotime("$date_from + $to_sum day"));			
		
		// Schedule.
        $items_schedule = $bookingultrapro->userpanel->get_working_hours($staff_id);
		
		//staff member		
		$staff_member = $bookingultrapro->userpanel->get_staff_member($staff_id);			
		
		$cdiv = 0 ;				
		$service = $this->get_one_service($b_category);
		
		if($_POST['b_date']=='')
		{		
			$html .='<p>'.__("Please select a date.",'booking-ultra-pro').'</p>';	
			
			$response = array('response' => 'NOOK', 'content' => $html);		
			echo json_encode($response);
			die();
		
		}
		
				
		//parse content		
		$content_text = $bookingultrapro->get_template_label("step2_texts",$template_id);		
		$content_text = $this->ubp_parse_customizer_texts($content_text, $service, $staff_member);
		

		
		$class_day_divisor = '';
		$class_ul_divisor = '';
		$class_li_divisor = '';
		$class_h3 = '';
		$class_book_button = '';
				
		
		$wp_date_format = get_option( 'date_format' );
		
		//Does the user offer this service?				
		if($bookingultrapro->userpanel->staff_offer_service( $staff_id, $b_category ))
		{
			$html .= '<div class="bup-selected-staff-booking-info">'; 
			
			$html .= $content_text;
			
			
			$html .= '</div>';
			
			
		    $available_previous = true;
			while (strtotime($date_from) < strtotime($end_date)) 
			{
				 $cdiv++;
				 
				 $day_num_of_week = date('N', strtotime($date_from));	
				 
				 //is the staff member working on this day?			 
				  if(isset($items_schedule[$day_num_of_week]))
				  {			 
					 			  
					  $html .= '<div class="bup-time-slots-divisor '.$class_day_divisor.'" id="bup-time-sl-div-'.$cdiv.'">';	
					  
					  if($selected_layout==2) //minified		
					  {					  
					  
					  	  $html .= '<h3 class="'.$class_h3.'">'.date($wp_date_format, strtotime($date_from)).'</h3>';					  
					  }else{
						  
						  $html .= '<h3>'.$bookingultrapro->commmonmethods->formatDate($date_from).'</h3>';
						  
					  }
					    
					  $html .= '<ul class="bup-time-slots-available-list '.$class_ul_divisor.'">';	
					  
					 //get available slots for this date				 
					 $time_slots = $this->get_time_slot_public_for_staff($day_num_of_week,  $staff_id, $b_category, $time_format);
					 
					 //check if staff member is in holiday this day					   
					  $is_in_holiday = $this->is_in_holiday($staff_id, $date_from);	
					  
					   //staff hourly						 
					  $staff_hourly = $this->get_hourly_for_staf($staff_id, $day_num_of_week);		   
					 
					 
					 $cdiv_range = 0 ;
					 
					 foreach($time_slots as $slot)
					 {
						 $cdiv_range++;	
						 
						 
						 $day_time_slot = date('Y-m-d', strtotime($date_from)).' '.$slot['from'].':00';
						  
						 
						 $current_time_slot = $slot['from'].':00';
						 $increased_minutes = date('H:i:s', strtotime( $current_time_slot ) +$slot_length_minutes);
						 //$to_slot_limit = $date_from.' '. $increased_minutes;
						 
						 $to_slot_limit = $date_from.' '. $slot['to'].':00';						
						 $day_time_slot_to = $to_slot_limit;
						 
						  //$day_time_slot_to = $to_slot_limit;
						  	 
						 $staff_time_slots = array();					 
						 $staff_time_slots = $this->get_time_slots_availability_for_day($staff_id, $b_category, $day_time_slot, $day_time_slot_to);	
					  
					  //check if staff member is on break time for this day.						
					   $is_in_break = $this->is_in_break($staff_id, $day_num_of_week, $slot['from'] , $slot['to']);
					   
					   if($staff_time_slots['available']==0 || $is_in_break || $staff_time_slots['busy']==true || $is_in_holiday )
					   {							
							$available_slot =false;
							
								
						}else{												
								
							$available_slot =true;							
						}
							
						$time_from = $slot['from'];
						$time_to = $slot['to'];
						
						$time_from_display = $slot['from_display'];
						$time_to_display = $slot['to_display'];
						
						//padding before?
						if($service->service_padding_before!='' && $service->service_padding_before!=0 )
						{
							//previous is not available, then we need to add padding
							if(!$available_previous)
							{
								$minutes_to_increate = $service->service_padding_before;	
								
								$increased_from = date('H:i:s', strtotime($time_from.':00')+$minutes_to_increate);
								$increased_from = date('H:i', strtotime($increased_from));							
								$time_from = $increased_from;
									
							}
								
						}			
						
						
							 
						 
						 if($display_only_from_hour=='yes' || $display_only_from_hour=='' )
						 {
							  //reduced view
							 $time_to_display = '&nbsp;&nbsp;'.date($time_format, strtotime($time_from));
						 }else{
							 
							 $time_to_display = '&nbsp;&nbsp;'.date($time_format, strtotime($time_from)).' &ndash; '.date($time_format, strtotime($time_to)).'';					 			
						 
						 }
						 
						 
						 //check if hour is available to book, we have to use the server time			 
						 $current_slot_time_stamp = strtotime($date_from.' '.$time_from.':00');			 
						 $current_site_time_stamp = strtotime(date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ));
						 
						 $is_passed = false;						 
						 if($current_site_time_stamp>$current_slot_time_stamp)
						 {							 
							 $is_passed = true;	 						
						 }
						 
						 $li_class = '';					 	
						
						if($staff_time_slots['available']==0 || $is_in_break || $is_passed || $staff_time_slots['busy']==true || $is_in_holiday)
						{
							$button_class = 'bup-button-blocked ';
							$button_label = __('Unavailable','booking-ultra-pro');
							$li_avail_icon = '';							
							$class_disable_price_line = ' style=" text-decoration: line-through " ';
							
							$is_slot_available = false;
						
						}else{
							
							$button_class = 'bup-button bup-btn-book-app';
							$button_label = __('Book Appointment','booking-ultra-pro');
							$li_class = 'bup-btn-book-app-li';	
							
							//used in minified mode
							$li_avail_icon = 'fa fa-check-square-o';
							$class_disable_price_line = ' ';	
							$is_slot_available = true;				
						}
						
						//is All Day event?						
						if($service->service_duration==86400)	
						{
							$time_from = '00:00';
						    $time_to = '23:59';						
						}
						
						

						//if($time_to>$staff_hourly->avail_to || $time_to<$staff_hourly->avail_from)
						////{
							//$display_unavailable = 'no';
							//$is_slot_available = false;
						//}
						
						$is_slot_outside_working_hours = false;
						if($this->is_booking_outside_working_hours($staff_hourly, $time_to, $date_from ) && $allow_bookings_outside_b_hours=='no')
						{
							$is_slot_outside_working_hours = true;
							
						}
						
						
						//do we have to hide the slot in the fron-end?						
						if($display_unavailable=='no' && !$is_slot_available)
						{							
							$is_slot_visible = false;
							
						}else{
							
							$is_slot_visible = true;							
						}
						
				       if($is_slot_visible && !$is_slot_outside_working_hours)
					   {			
						 	 
						
						 $html .= '<li class="'.$li_class.' '.$class_li_divisor.'"   id="bup-time-slot-hour-range-'.$cdiv.'-'.$cdiv_range.'" bup-data-date="'.date('Y-m-d', strtotime($date_from)).'" bup-max-capacity="'.$staff_time_slots['capacity'].'" bup-max-available="'.$staff_time_slots['available'].'" bup-data-timeslot="'.$time_from .'-'.$time_to.'" bup-data-service-staff="'.$b_category.'-'.$staff_id.'" >';
						 
						 //
						 
						 if($selected_layout==2) //minified		
						 {							 
							$html .= ' <span class="bup-front-mini-icons">							
							<i class="'.$li_avail_icon.'" '.$class_i_icon_bg.'></i> </span>';							
						 }
						 
						 					
						 $html .= '<div class="bup-timeslot-time" '.$class_disable_price_line.'><i class="fa fa-clock-o"></i>'.$time_to_display.'</div>';
						 $html .= '<div class="bup-timeslot-count"><span class="spots-available">'.$staff_time_slots['label'].'</span></div>';
						 
						 $html .= '<span class="bup-timeslot-people" '.$class_book_button.'>';	
						 
						
						 $html .= '<button class="new-appt '.$button_class.'" bup-data-date="'.date('Y-m-d', strtotime($date_from)).'" bup-max-capacity="'.$staff_time_slots['capacity'].'" bup-max-available="'.$staff_time_slots['available'].'" bup-data-timeslot="'.$time_from .'-'.$time_to.'" bup-data-service-staff="'.$b_category.'-'.$staff_id.'">'; //category-userid
						
						$html .= '<span class="button-timeslot"></span><span class="bup-button-text">'. $button_label.'</span></button>';
						
						 $html .= '</span>';						
						 $html .= '</li>';	
						 
					 } // end if
						 
						 
						 $available_previous =$available_slot;
						 
						 
					 
					  }
					  
					  $html .='</ul>';			  			  
					  $html .= '</div>'; //end time slots divisor
				  
				  
				  } //end if working			  
				  
				 
				 //increase date
				 $date_from = date ("Y-m-d", strtotime("+1 day", strtotime($date_from))); 			 
				 
				 
			 }  //end while
			 
		}else{
			
			
			$html .='<p>'.__("This Provider doesn't offer this service.",'booking-ultra-pro').'</p>';
			
			
		
		}  //end if
		 		
		
		$response = array('response' => 'OK', 'content' => $html);
		echo json_encode($response) ;		
		die();		
	
	}
	
	
	function delete_category()
	{
		
		global  $wpdb, $bookingultrapro;
		
		$category = $_POST['cate_id'];
						
		$sql ="DELETE FROM " . $wpdb->prefix . "bup_categories WHERE cate_id=%d ;";			
		$sql = $wpdb->prepare($sql,array($category));	
		$rows = $wpdb->get_results($sql);
		die();
	
	}
	
	function delete_service()
	{
		
		global  $wpdb, $bookingultrapro;
		
		$service = $_POST['service_id'];						
		$sql ="DELETE FROM " . $wpdb->prefix . "bup_services WHERE service_id=%d ;";			
		$sql = $wpdb->prepare($sql,array($service));	
		$rows = $wpdb->get_results($sql);
		die();
	
	}
	
	
	//this will check if the user is in holiday 	
	function is_in_holiday($staff_id, $date)
	{
		
		global  $wpdb, $bookingultrapro, $bupcomplement;
		
		
		if(isset($bupcomplement))
		{
			return $bupcomplement->dayoff->is_in_holiday($staff_id, $date);
							
		}else{
			
			return false;		
				
		}		
	
	}
	
	//this will check if the user is in break time 	
	function is_in_break($staff_id, $day, $from_time, $to_time)
	{
		
		global  $wpdb, $bookingultrapro;
		
		$from_time = $from_time.':00';
		$to_time = $to_time.':00';
		
		$ret = false;
				
		$sql ="SELECT * FROM " . $wpdb->prefix . "bup_staff_availability_breaks  
		WHERE break_staff_day=%d AND break_staff_id = %d  AND  (break_time_to > '".$from_time."'  AND 	break_time_from < '".$to_time."'  );";
		
			
		$sql = $wpdb->prepare($sql,array($day, $staff_id));	
		$rows = $wpdb->get_results($sql);
		
		if ( !empty( $rows ))
		{			
			$ret = true;			
		}
		
		
		return $ret;
		
		
	
	}
	
	function get_total_bookings($staff_id, $service_id, $day, $day_to)
	{
		
		global  $wpdb, $bookingultrapro;
		
		
		$res = array();	
		
		$total_groups =0;
		$total_individual =0;
		
		//get total on quantity row	for this service only	
		$sql ="SELECT SUM(booking_qty) as total FROM " . $wpdb->prefix . "bup_bookings  
		WHERE booking_staff_id = %d  AND  booking_service_id = %d AND booking_status <> '2' AND (booking_time_to > '".$day."'  AND 	booking_time_from < '".$day_to."'  );";	
		
		
			
		$sql = $wpdb->prepare($sql,array($staff_id,$service_id));	
		$rows = $wpdb->get_results($sql);
		$booked = $wpdb->num_rows;
		
		
		
		
		if ( !empty( $rows )) 
		{
			foreach ( $rows as $row )
			{
				$total_groups = $row->total;		
			}
			
				
		}
		
		
		
		//get total bookins individually
		$sql ="SELECT count(*) as total FROM " . $wpdb->prefix . "bup_bookings  
		WHERE booking_staff_id = %d  AND  booking_service_id = %d AND booking_status <> '2' AND (booking_time_to > '".$day."'  AND 	booking_time_from < '".$day_to."'  );";		
			
		$sql = $wpdb->prepare($sql,array($staff_id,$service_id));	
		$rows = $wpdb->get_results($sql);
	
		if ( !empty( $rows )) // the staff member is busy in this time.
		{
			foreach ( $rows as $row )
			{
				$total_individual = $row->total;		
			}
		}
		
		if($total_individual !=0){
			//echo "HAS MANY Bookings: $total_individual - " .$sql.'<br>.';
			}
		
		$res = array('total_groups'=>$total_groups, 
				     'total_individual'=>$total_individual, 
					);	
		
		
		
		return $res;
	}
	
	function is_staff_available($staff_id, $service_id, $day, $day_to)
	{
		global  $wpdb, $bookingultrapro;
		
		//Is the staff member busy?
		$sql ="SELECT * FROM " . $wpdb->prefix . "bup_bookings  
		WHERE booking_staff_id = %d  AND  booking_service_id <> %d AND booking_status <> '2' AND  (booking_time_to > '".$day."'  AND 	booking_time_from < '".$day_to."'  );";	
				
		$sql = $wpdb->prepare($sql,array($staff_id, $service_id));	
		$rows = $wpdb->get_results($sql);			
		$booked = $wpdb->num_rows;	
		
		if ( !empty( $rows )) // the staff member is busy in this time.
		{			
			$busy = true;		
				
		}else{
			
			$busy = false;		
		}
		
		if($busy){
			//echo "HAS MANY Bookings: $booked  - " .$sql.'<br>.';
			}
		
		return $busy;
		
		
		
	}
	
	//this will give me availability for this service 	
	function get_time_slots_availability_for_day($staff_id, $service_id, $day, $day_to)
	{
		
		global  $wpdb, $bookingultrapro;
				
		$res = array();	
		$booking_totals = array();
		
		//we need to add a setting so pending orders are not being calculated.		
		
		$booking_totals = $this->get_total_bookings($staff_id, $service_id, $day, $day_to);	
		
		//Is staff offering a different service at the same time?	
	    $busy = $this->is_staff_available($staff_id, $service_id, $day, $day_to);	
		
		//Is this a group booking services?
		$service = $this->get_one_service($service_id);
		
		if($service->service_allow_multiple==1) //group booking
		{
			
			$booked = $booking_totals['total_groups'];
			
		}else{ //one at once booking
		
			$booked = $booking_totals['total_individual'];
			
		}
		
		$staff_service_details = $bookingultrapro->userpanel->get_staff_service_rate( $staff_id, $service_id );			
		$appointment_capacity = $staff_service_details['capacity'];	
		$available_slots = $appointment_capacity - $booked;
		
		if($available_slots<0 ){$available_slots = 0;}		
		
		//label
		$s = '';		
		if($available_slots>1 || $available_slots==0 ){$s = 's';}
		
		//printf(__( 'Month: %s', 'bookingup' ),    $current_month_legend);
		
		$label = sprintf(__('%s time slot%s available ','booking-ultra-pro'),$available_slots,$s);
			
		$res = array('price'=>$staff_service_details['price'], 
				             'capacity'=>$staff_service_details['capacity'] , 
							 'booked'=>$booked,
							 'label'=>$label,
							 'available'=>$available_slots,
							 'busy'=>$busy
							 );	
		return $res;
	
	}
	
	function get_time_format()
	{
		global  $bookingultrapro;
	
		$data = $bookingultrapro->get_option('bup_time_format');
		
		if($data=='')
		{
			$data = 'h:i A';
		
		}
		
		return $data;
	}	
	
	//returns an array with time slots for this user
	public function get_time_slot_public_for_staff($day,  $staff_id, $service_id, $time_format)
	{
		global  $bookingultrapro;
		
		$time_slots = array();
		
		$hours = 24; //amount of hours working in day
		
		$selected_value = '';
		
		//get duration of this category		
		$service = $this->get_one_service($service_id);			
		
		
		if($service->service_duration=='')
		{
			$min_minutes = $bookingultrapro->get_option('bup_time_slot_length');
			$service_minutes = 1800; //30 minutes
		
		}else{			
			
			$min_minutes = $bookingultrapro->get_option('bup_time_slot_length');			
			$service_minutes = $service->service_duration;	
		
		}
		
		//check if consecutives
				
		if($min_minutes ==''){$min_minutes=15;}	
				
		$hours = (60/$min_minutes) *$hours;		
		$min_minutes=$min_minutes*60; //seconds
		
		
		
					
		//check selected value
		$selected_value_from = $this->get_business_hour_option($day, 'from', $staff_id);		
		$selected_value_to= $this->get_business_hour_option($day, 'to', $staff_id);
		
		for($i = 0; $i <= $hours ; $i++)
		{ 		
			$minutes_to_add_display = $min_minutes * $i; // add 30 - 60 - 90 etc.
			$minutes_to_add = $min_minutes  ; // add 30 - 60 - 90 etc.		
			
				
			$timeslot = date('H:i:s', strtotime(0)+$minutes_to_add_display );						
			$timeslot_display = date('H:i:s', strtotime(0)+$minutes_to_add_display);
			
			$endTime = date('H:i:s', strtotime($timeslot)+$service_minutes);			
			$endTime_display = date('H:i:s', strtotime($timeslot_display)+$minutes_to_add);
						
			$time_slot_hours_mins = date('H:i', strtotime($timeslot_display));	
			
			//improvement	
			$endTime_overlapped = date('H:i:s', strtotime($timeslot)+$service_minutes*2);	
			
			
			if($time_slot_hours_mins >= $selected_value_from && $time_slot_hours_mins < $selected_value_to)
			{
				$from_value	=date('H:i', strtotime($timeslot));	
				$to_value	=date('H:i', strtotime($endTime ));
				$to_value_overlap	=date('H:i', strtotime($endTime_overlapped ));	
								
				//to display
				$from_value_display	=date('H:i', strtotime($timeslot_display));	
				$to_value_display	=date('H:i', strtotime($endTime_display ));	
												
				$time_slots[] = array('from' =>  $from_value, 'to' => $to_value, 'to_overlap' => $to_value_overlap, 
				'from_display' =>  $from_value_display, 
				'to_display' => $to_value_display);
			
			}
			
			
		}	
		
		
		return $time_slots;
	
	}
	
	function get_availability_for_user($b_staff, $date_from, $b_category)
	{
		
		global $wpdb, $bookingultrapro;
		
		
	
	}
	
	function get_prefered_staff($staff_id = null, $service_id)
	{
		global $wpdb, $bookingultrapro;
		
		if($staff_id=='')
		{
			//get random staff providing this service			
			$staff_members = array();			
			$staff_members = $this->get_staff_offering_service($service_id);			
			$staff_id = $staff_members[array_rand($staff_members)];	
		
		}
		
		return $staff_id;
	
	}
	
	
	
	public function update_staff_business_hours()
	{
		global $wpdb, $bookingultrapro;
		
		$staff_id = $_POST['staff_id'];		
		
		$bup_mon_from = $_POST['bup_mon_from'];
		$bup_mon_to = $_POST['bup_mon_to'];		
		$bup_tue_from = $_POST['bup_tue_from'];
		$bup_tue_to = $_POST['bup_tue_to'];		
		$bup_wed_from = $_POST['bup_wed_from'];
		$bup_wed_to = $_POST['bup_wed_to'];		
		$bup_thu_from = $_POST['bup_thu_from'];
		$bup_thu_to = $_POST['bup_thu_to'];
		$bup_fri_from = $_POST['bup_fri_from'];
		$bup_fri_to = $_POST['bup_fri_to'];		
		$bup_sat_from = $_POST['bup_sat_from'];
		$bup_sat_to = $_POST['bup_sat_to'];		
		$bup_sun_from = $_POST['bup_sun_from'];
		$bup_sun_to = $_POST['bup_sun_to'];
		
		$business_hours = array();
		
		if($bup_mon_from!=''){$business_hours[1] = array('from' =>$bup_mon_from, 'to' =>$bup_mon_to);}
		if($bup_tue_from!=''){$business_hours[2] = array('from' =>$bup_tue_from, 'to' =>$bup_tue_to);}
		if($bup_wed_from!=''){$business_hours[3] = array('from' =>$bup_wed_from, 'to' =>$bup_wed_to);}
		if($bup_thu_from!=''){$business_hours[4] = array('from' =>$bup_thu_from, 'to' =>$bup_thu_to);}
		if($bup_fri_from!=''){$business_hours[5] = array('from' =>$bup_fri_from, 'to' =>$bup_fri_to);}
		if($bup_sat_from!=''){$business_hours[6] = array('from' =>$bup_sat_from, 'to' =>$bup_sat_to);}
		if($bup_sun_from!=''){$business_hours[7] = array('from' =>$bup_sun_from, 'to' =>$bup_sun_to);}
		
		
		if($staff_id!='')
		{
			//clean 			
			$sql = 'DELETE FROM ' . $wpdb->prefix . 'bup_staff_availability  WHERE avail_staff_id="'.(int)$staff_id.'" ';			$wpdb->query($sql);		
			
			
			if($bup_mon_from!='')
			{
				
				$new_record = array('avail_id' => NULL,	'avail_staff_id' => $staff_id,
								'avail_day' => '1','avail_from' => $bup_mon_from,'avail_to'   => $bup_mon_to);
				$wpdb->insert( $wpdb->prefix . 'bup_staff_availability', $new_record, array( '%d', '%s', '%s', '%s', '%s'));			
			}
			
			
			if($bup_tue_from!='')
			{		
			
				//2			
				$new_record = array('avail_id' => NULL,	'avail_staff_id' => $staff_id,
									'avail_day' => '2','avail_from' => $bup_tue_from,'avail_to'   => $bup_tue_to);
						
				$wpdb->insert( $wpdb->prefix . 'bup_staff_availability', $new_record, array( '%d', '%s', '%s', '%s', '%s'));			
			}
			
			if($bup_wed_from!='')
			{			
				//3			
				$new_record = array('avail_id' => NULL,	'avail_staff_id' => $staff_id,
									'avail_day' => '3','avail_from' => $bup_wed_from,'avail_to'   => $bup_wed_to);
						
				$wpdb->insert( $wpdb->prefix . 'bup_staff_availability', $new_record, array( '%d', '%s', '%s', '%s', '%s'));
			
			}
			
			if($bup_thu_from!='')
			{
			
				//4			
				$new_record = array('avail_id' => NULL,	'avail_staff_id' => $staff_id,
									'avail_day' => '4','avail_from' => $bup_thu_from,'avail_to'   => $bup_thu_to);
						
				$wpdb->insert( $wpdb->prefix . 'bup_staff_availability', $new_record, array( '%d', '%s', '%s', '%s', '%s'));			
			}
			
			if($bup_fri_from!='')
			{
		
				//5			
				$new_record = array('avail_id' => NULL,	'avail_staff_id' => $staff_id,
									'avail_day' => '5','avail_from' => $bup_fri_from,'avail_to'   => $bup_fri_to);
						
				$wpdb->insert( $wpdb->prefix . 'bup_staff_availability', $new_record, array( '%d', '%s', '%s', '%s', '%s'));
			
			}
			
			if($bup_sat_from!='')
			{
			
				//6		
				$new_record = array('avail_id' => NULL,	'avail_staff_id' => $staff_id,
									'avail_day' => '6','avail_from' => $bup_sat_from,'avail_to'   => $bup_sat_to);
						
				$wpdb->insert( $wpdb->prefix . 'bup_staff_availability', $new_record, array( '%d', '%s', '%s', '%s', '%s'));
			
			}
			
			
			if($bup_sun_from!='')
			{			
			
				//7		
				$new_record = array('avail_id' => NULL,	'avail_staff_id' => $staff_id,
									'avail_day' => '7','avail_from' => $bup_sun_from,'avail_to'   => $bup_sun_to);
						
				$wpdb->insert( $wpdb->prefix . 'bup_staff_availability', $new_record, array( '%d', '%s', '%s', '%s', '%s'));
			}
			
			
		}
		
		
	
		//print_r($business_hours);			
		
		die();
	
	
	}
	
	
	public function ubp_update_global_business_hours()
	{
		global $wpdb, $bookingultrapro;
		
		$bup_mon_from = $_POST['bup_mon_from'];
		$bup_mon_to = $_POST['bup_mon_to'];		
		$bup_tue_from = $_POST['bup_tue_from'];
		$bup_tue_to = $_POST['bup_tue_to'];		
		$bup_wed_from = $_POST['bup_wed_from'];
		$bup_wed_to = $_POST['bup_wed_to'];		
		$bup_thu_from = $_POST['bup_thu_from'];
		$bup_thu_to = $_POST['bup_thu_to'];
		$bup_fri_from = $_POST['bup_fri_from'];
		$bup_fri_to = $_POST['bup_fri_to'];		
		$bup_sat_from = $_POST['bup_sat_from'];
		$bup_sat_to = $_POST['bup_sat_to'];		
		$bup_sun_from = $_POST['bup_sun_from'];
		$bup_sun_to = $_POST['bup_sun_to'];
		
		$business_hours = array();
		
		if($bup_mon_from!=''){$business_hours[1] = array('from' =>$bup_mon_from, 'to' =>$bup_mon_to);}
		if($bup_tue_from!=''){$business_hours[2] = array('from' =>$bup_tue_from, 'to' =>$bup_tue_to);}
		if($bup_wed_from!=''){$business_hours[3] = array('from' =>$bup_wed_from, 'to' =>$bup_wed_to);}
		if($bup_thu_from!=''){$business_hours[4] = array('from' =>$bup_thu_from, 'to' =>$bup_thu_to);}
		if($bup_fri_from!=''){$business_hours[5] = array('from' =>$bup_fri_from, 'to' =>$bup_fri_to);}
		if($bup_sat_from!=''){$business_hours[6] = array('from' =>$bup_sat_from, 'to' =>$bup_sat_to);}
		if($bup_sun_from!=''){$business_hours[7] = array('from' =>$bup_sun_from, 'to' =>$bup_sun_to);}
		
		update_option('bup_business_hours', $business_hours);
		
		die();
	
	
	}
	
	
	
	public function ubp_update_service()
	{
		global $wpdb, $bookingultrapro;
		
		$service_id = $_POST['service_id'];
		$service_title = $_POST['service_title'];
		$service_duration = $_POST['service_duration'];
		$service_price = $_POST['service_price'];
		$service_price_2 = $_POST['service_price_2'];		
		$service_capacity = $_POST['service_capacity'];
		$service_category = $_POST['service_category'];
		$service_color = $_POST['service_color'];
		$service_font_color = $_POST['service_font_color'];
		
		$service_padding_before = $_POST['service_padding_before'];
		$service_padding_after = $_POST['service_padding_after'];
		
		$service_groups = $_POST['service_groups'];		
		$service_calculation = $_POST['service_calculation'];
		
		if($service_calculation==''){$service_calculation=1;}			
		if($service_groups==''){$service_groups=0;}		
		if($service_padding_before==''){$service_padding_before=0;}
		if($service_padding_after==''){$service_padding_after=0;}		
		
		//if($service_color==''){$service_color=0;}
		//if($service_font_color==''){$service_font_color=0;}
		
		
		if($service_id!='')
		{			
			$sql = 'UPDATE ' . $wpdb->prefix . 'bup_services  SET service_title = "'.$service_title.'",			
			service_duration = "'.$service_duration.'",
			service_price = "'.$service_price.'",
			service_price_2 = "'.$service_price_2.'", 
			service_allow_multiple = "'.$service_groups.'", 
			service_pricing_calculation_type = "'.$service_calculation.'", 
			service_capacity = "'.$service_capacity.'", 
			service_category_id = "'.$service_category.'", 
			service_color = "'.$service_color.'",
			service_font_color = "'.$service_font_color.'",
			service_padding_before = "'.$service_padding_before.'",
			service_padding_after = "'.$service_padding_after.'"			
			WHERE service_id="'.(int)$service_id.'" ';
			$wpdb->query($sql);
		
		
		}else{ //this is a new service
			
			
			$new_record = array('service_id' => NULL,	
								'service_title' => $service_title,
								'service_duration' => $service_duration,
								'service_price' => $service_price,
								'service_price_2' => $service_price_2,
								'service_capacity'   => $service_capacity,
								'service_category_id'   => $service_category,
								'service_color'   => $service_color,
								'service_font_color'   => $service_font_color,
								'service_padding_before'   => $service_padding_before,
								'service_padding_after'   => $service_padding_after,
								'service_allow_multiple'   => $service_groups,
								'service_pricing_calculation_type'   => $service_calculation);								
									
			$wpdb->insert( $wpdb->prefix . 'bup_services', $new_record, array( '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s' , '%s' , '%s'));
			
		}
		
		
		
		
		die();
	
	
	}
	
	public function ubp_get_service()
	{
		global $wpdb, $bookingultrapro, $bupcomplement;
		
		$service_id = '';
		$category_id = '';
		
		if(isset($_POST['service_id'])){
			
			$service_id = $_POST['service_id'];	
		}
		
		if(isset($_POST['category_id'])){
			
			$category_id = $_POST['category_id'];	
		}
		
		if($service_id!='') //we are editing
		{		
			$service = $this->get_one_service($service_id);			
			$mess = __('Here you can update the information of this service. Once you have modified the information click on the save button.','booking-ultra-pro');
		
		}else{
			
			$mess = __('Here you can create a new service. Once you have filled in the form click on the save button.','booking-ultra-pro');
			
		
		}
		
		$html = '';
		
		$html .= '<div class="bup-sect-adm-edit">';
		
		$html .= '<p>'.$mess.'</p>';
		
			$html .= '<div class="bup-edit-service-block">';
						
			
			$html .= '<div class="bup-field-separator"><label for="bup-box-title">'.__('Title','booking-ultra-pro').':</label><input type="text" name="bup-title" id="bup-title" class="ubp-common-textfields" value="'.$service->service_title.'" /></div>';
			
			$html .= '<div class="bup-field-separator"><label for="textfield">'.__('Background Color','booking-ultra-pro').':</label><input name="bup-service-color" type="text" id="bup-service-color" value="'.$service->service_color.'" class="color-picker" data-default-color=""/></div>';
				
			$html .= '<div class="bup-field-separator"><label for="textfield">'.__('Font Color','booking-ultra-pro').':</label><input name="bup-service-font-color" type="text" id="bup-service-font-color" value="'.$service->service_font_color.'" class="color-picker" data-default-color=""/></div>';
			
								
			$html .= '<div class="bup-field-separator"><label for="textfield">'.__('Duration','booking-ultra-pro').':</label>'.$this->get_duration_drop_down($service->service_duration).'</div>';
			
			//padding
			
			//if(isset($bupcomplement))
			//{
				
				$html .= '<div class="bup-field-separator"><label for="textfield">'.__('Padding time (before and after)','booking-ultra-pro').':</label>'.$this->get_padding_add_frm($service_id , $service->service_padding_before, $service->service_padding_after).'</div>';
			
			//}		
			
			
			$html .= '<div class="bup-field-separator"><label for="textfield">'.__('Price','booking-ultra-pro').':</label><input type="text" name="bup-price" id="bup-price" class="ubp-common-textfields" value="'.$service->service_price.'" /></div>';
			
						
			
				
			$html .= '<div class="bup-field-separator"><label for="textfield">'.__('Capacity','booking-ultra-pro').':</label><input type="number"   min="1" name="bup-capacity" id="bup-capacity" class="ubp-common-textfields" value="'.$service->service_capacity.'"/></div>';
				
			$html .= '<div class="bup-field-separator"><label for="textfield">'.__('Category','booking-ultra-pro').':</label>'.$this->get_categories_drop_down($service->service_category_id).'</div>';
				
			$html .= '<input type="hidden" name="bup-service-id" id="bup-service-id" value="'.$service->service_id.'" />';				
			
			
		$html .= '</div>';
		
		
		if(isset($bupcomplement))
			{
				$sel_group_yes ='';
				$sel_group_no =''; 
				
				if($service->service_allow_multiple==1)
				{					
					$sel_group_yes ='selected="selected"';					
				}
				
				if($service->service_allow_multiple==0 || $service->service_allow_multiple=='')
				{					
					$sel_group_no ='selected="selected"';					
				}
				
				//private
				
				$isprivate_yes ='';
				$isprivate_no =''; 
				
				if($service->service_private==1)
				{					
					$isprivate_yes ='selected="selected"';					
				}
				
				if($service->service_private==0 || $service->service_private=='')
				{					
					$isprivate_no ='selected="selected"';					
				}
				
				
			
				$html .= '<div class="bup-field-separator"><label for="textfield">'.__('Allow Group Bookings?','booking-ultra-pro').':</label><select name="bup-groups"  id="bup-groups">
		  <option value="0" '.$isprivate_yes.'>'.__('NO','booking-ultra-pro').'</option>
		  <option value="1" '.$isprivate_no.'>'.__('YES','booking-ultra-pro').'</option>
		</select>
					
			</div>';
			
			
				$html .= '<div class="bup-field-separator"><label for="textfield">'.__('Is Private?','booking-ultra-pro').':</label><select name="bup-isprivate"  id="bup-isprivate">
		  <option value="0" '.$sel_group_no.'>'.__('NO','booking-ultra-pro').'</option>
		  <option value="1" '.$sel_group_yes.'>'.__('YES','booking-ultra-pro').'</option>
		</select>
					
			</div>';
			
			//calculation method
			
			if($service->service_pricing_calculation_type==1 || $service->service_pricing_calculation_type=='')
			{					
					$calculation_method_1 ='selected="selected"';					
			}
			if($service->service_pricing_calculation_type==2 )
			{					
					$calculation_method_2 ='selected="selected"';					
			}
			
			if($service->service_pricing_calculation_type==3 )
			{					
					$calculation_method_3 ='selected="selected"';					
			}
			
			$html .= '<div class="bup-field-separator"><label for="textfield">'.__('Calculation Way','booking-ultra-pro').':</label><select name="bup-groups-calculation"  id="bup-groups-calculation">
		  <option value="1" '.$calculation_method_1.'>'.__('Common Method (Quantity X Price)','booking-ultra-pro').'</option>
		  <option value="2" '.$calculation_method_2.'>'.__("Sum All Prices",'booking-ultra-pro').'</option>
		  <option value="3" '.$calculation_method_3.'>'.__("Total Bassed on Quantity",'booking-ultra-pro').'</option>
		</select>
					
			</div>';
			
			
			
			}else{
				
				$html .= '<div class="bup-field-separator"><label for="textfield">'.__('Allow Group Bookings?','booking-ultra-pro').':</label><span><i class="fa fa-info-circle "></i></span>'.__(' Available on Premium Versions','booking-ultra-pro').'
			
			</div>';
				
			
			}	
		
		
		$html .= '</div>';
		
		
			
		echo $html ;		
		die();		
	
	}
	
	public function add_category_confirm()
	{
		
		global $wpdb, $bookingultrapro;	
		
		$html='';	
		
		$category_id = $_POST['category_id'];
		$category_name = $_POST['category_title'];
		
		if($category_id=='')		
		{
					
						
			$new_record = array('cate_id' => NULL,	
								'cate_name' => $category_name);								
			$wpdb->insert( $wpdb->prefix . 'bup_categories', $new_record, array( '%d', '%s'));					
						
			$html ='OK INSERT';
		
	    }else{
			
			$sql = $wpdb->prepare('UPDATE  ' . $wpdb->prefix . 'bup_categories SET cate_name =%s  WHERE cate_id = %d ;',array($category_name,$category_id));
		
			$results = $wpdb->query($sql);
			$html ='OK';
			
			
		}
		
		echo $html;
		die();
		
				
	
	}
	
	public function get_category_add_form()
	{		
		
		$html = '';		
		
		$category_id = '';
		
		if(isset($_POST['category_id']))
		{
			$category_id = $_POST['category_id'];
			
		}
		
		$category_name = '';		
				
		if($category_id!='')		
		{
			//get payments			
			$category = $this->get_one_category( $category_id);
			$category_name =	$category->cate_name;
		}		
		
		$html .= '<p>'.__('Name:','booking-ultra-pro').'</p>' ;	
		$html .= '<p><input type="text" id="but-category-name" value="'.$category_name.'"></p>' ;
		$html .= '<input type="hidden" id="bup_category_id" value="'.$category_id .'" />' ;		
			
		echo $html ;		
		die();		
	
	}
	
	public function client_get_add_form()
	{		
		
		$html = '';		
		
		$client_id = $_POST['client_id'];		
		$category_name = '';		
				
		if($client_id!='')		
		{
			//get client			
		//	$category = $this->get_one_category( $category_id);
			//$category_name =	$category->cate_name;
		}		
		
		$html .= '<p>'.__('Name:','booking-ultra-pro').'</p>' ;	
		$html .= '<p><input type="text" id="client_name" value="'.$category_name.'"></p>' ;
		$html .= '<p>'.__('Last Name:','booking-ultra-pro').'</p>' ;	
		$html .= '<p><input type="text" id="client_last_name" value="'.$category_name.'"></p>' ;
		$html .= '<p>'.__('Email:','booking-ultra-pro').'</p>' ;	
		$html .= '<p><input type="text" id="client_email" value="'.$category_name.'"></p>' ;
		$html .= '<p id="bup-add-client-message"></p>' ;		
			
		echo $html ;		
		die();		
	
	}
	
	//returns the business hours drop down
	public function get_business_staff_business_hours($staff_id)
	{
		$this->mBusinessHours = get_option('bup_business_hours');
		
		$html .=' <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td>'.__('Monday','booking-ultra-pro').'</td>
			<td>'.$this->get_business_hours_drop_down_for_staff(1,'bup-mon-from' ,'bup_select_start', $staff_id). '<span> '.__('to', 'booking-ultra-pro').' </span>' .$this->get_business_hours_drop_down_for_staff(1,'bup-mon-to' ,'bup_select_end', $staff_id).'</td>
		  </tr>
		  <tr>
			<td>'.__('Tuesday','booking-ultra-pro').'</td>
			<td>'.$this->get_business_hours_drop_down_for_staff(2,'bup-tue-from' ,'bup_select_start', $staff_id). '<span> '.__('to', 'booking-ultra-pro').' </span>' .$this->get_business_hours_drop_down_for_staff(2,'bup-tue-to' ,'bup_select_end', $staff_id).'</td>
		  </tr>
		  <tr>
			<td>'.__('Wednesday','booking-ultra-pro').'</td>
			<td>'.$this->get_business_hours_drop_down_for_staff(3,'bup-wed-from' ,'bup_select_start', $staff_id). '<span> '.__('to', 'booking-ultra-pro').' </span>' .$this->get_business_hours_drop_down_for_staff(3,'bup-wed-to' ,'bup_select_end', $staff_id).'</td>
		  </tr>
		  <tr>
			<td>'.__('Thursday ','booking-ultra-pro').'</td>
			<td>'.$this->get_business_hours_drop_down_for_staff(4,'bup-thu-from' ,'bup_select_start', $staff_id). '<span> '.__('to', 'booking-ultra-pro').' </span>' .$this->get_business_hours_drop_down_for_staff(4,'bup-thu-to' ,'bup_select_end', $staff_id).'</td>
		  </tr>
		  <tr>
			<td>'.__('Friday ','booking-ultra-pro').'</td>
			<td>'.$this->get_business_hours_drop_down_for_staff(5,'bup-fri-from' ,'bup_select_start', $staff_id). '<span> '.__('to', 'booking-ultra-pro').' </span>' .$this->get_business_hours_drop_down_for_staff(5,'bup-fri-to' ,'bup_select_end', $staff_id).'</td>
		  </tr>
		  <tr>
			<td>'.__('Saturday  ','booking-ultra-pro').'</td>
			<td>'.$this->get_business_hours_drop_down_for_staff(6,'bup-sat-from' ,'bup_select_start', $staff_id). '<span> '.__('to', 'booking-ultra-pro').' </span>' .$this->get_business_hours_drop_down_for_staff(6,'bup-sat-to' ,'bup_select_end', $staff_id).'</td>
		  </tr>
		  <tr>
			<td>'.__('Sunday ','booking-ultra-pro').'</td>
			<td>'.$this->get_business_hours_drop_down_for_staff(7,'bup-sun-from' ,'bup_select_start', $staff_id) . '<span> '.__('to', 'booking-ultra-pro').' </span>' .$this->get_business_hours_drop_down_for_staff(7,'bup-sun-to' ,'bup_select_end', $staff_id).'</td>
		  </tr>
		  </table>';
		  
		  $html .=' <p class="submit">
	<button name="ubp-save-glogal-business-hours-staff" id="ubp-save-glogal-business-hours-staff" class="bup-button-submit-changes" ubp-staff-id= "'.$staff_id.'">'.__('Save Changes','booking-ultra-pro').'	</button>&nbsp; <span id="bup-loading-animation-business-hours">  <img src="'.bookingup_url.'admin/images/loaderB16.gif" width="16" height="16" /> &nbsp; '.__('Please wait ...','booking-ultra-pro').' </span>
	
	</p>';
		  
	  
		  return $html;
	
	}
	
		
	//returns the business hours drop down
	public function get_business_hours_global_settings()
	{
		$this->mBusinessHours = get_option('bup_business_hours');
		
		$html .=' <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td>'.__('Monday','booking-ultra-pro').'</td>
			<td>'.$this->get_business_hours_drop_down(1,'bup-mon-from' ,'bup_select_start'). '<span> '.__('to', 'booking-ultra-pro').' </span>' .$this->get_business_hours_drop_down(1,'bup-mon-to' ,'bup_select_end').'</td>
		  </tr>
		  <tr>
			<td>'.__('Tuesday','booking-ultra-pro').'</td>
			<td>'.$this->get_business_hours_drop_down(2,'bup-tue-from' ,'bup_select_start'). '<span> '.__('to', 'booking-ultra-pro').' </span>' .$this->get_business_hours_drop_down(2,'bup-tue-to' ,'bup_select_end').'</td>
		  </tr>
		  <tr>
			<td>'.__('Wednesday','booking-ultra-pro').'</td>
			<td>'.$this->get_business_hours_drop_down(3,'bup-wed-from' ,'bup_select_start'). '<span> '.__('to', 'booking-ultra-pro').' </span>' .$this->get_business_hours_drop_down(3,'bup-wed-to' ,'bup_select_end').'</td>
		  </tr>
		  <tr>
			<td>'.__('Thursday ','booking-ultra-pro').'</td>
			<td>'.$this->get_business_hours_drop_down(4,'bup-thu-from' ,'bup_select_start'). '<span> '.__('to', 'booking-ultra-pro').' </span>' .$this->get_business_hours_drop_down(4,'bup-thu-to' ,'bup_select_end').'</td>
		  </tr>
		  <tr>
			<td>'.__('Friday ','booking-ultra-pro').'</td>
			<td>'.$this->get_business_hours_drop_down(5,'bup-fri-from' ,'bup_select_start'). '<span> '.__('to', 'booking-ultra-pro').' </span>' .$this->get_business_hours_drop_down(5,'bup-fri-to' ,'bup_select_end').'</td>
		  </tr>
		  <tr>
			<td>'.__('Saturday  ','booking-ultra-pro').'</td>
			<td>'.$this->get_business_hours_drop_down(6,'bup-sat-from' ,'bup_select_start'). '<span> '.__('to', 'booking-ultra-pro').' </span>' .$this->get_business_hours_drop_down(6,'bup-sat-to' ,'bup_select_end').'</td>
		  </tr>
		  <tr>
			<td>'.__('Sunday ','booking-ultra-pro').'</td>
			<td>'.$this->get_business_hours_drop_down(7,'bup-sun-from' ,'bup_select_start') . '<span> '.__('to', 'booking-ultra-pro').' </span>' .$this->get_business_hours_drop_down(7,'bup-sun-to' ,'bup_select_end').'</td>
		  </tr>
		  </table>';
		  
	  
		  return $html;
	
	}
	
	function get_business_hour_option($day, $from_to, $staff_id = null)
	{
		$business_hours = $this->mBusinessHours;
		
		$value = '';
		
		if(!isset($staff_id))
		{	
			
			if(isset($business_hours[$day])) //we have the week's day
			{					
				$value =  $business_hours[$day][$from_to];				
				if($business_hours[$day][$from_to]=='24:00:00'){$value='24:00';}
				
			}
		
		}else{
			
			//get the value for this day and this staff
			$hourly = $this->get_hourly_for_staf($staff_id, $day);	
			
			if(!$hourly) //not hourly, we retreive a predefined value
			{
				$value =  '';							
				
			}else{	//we retreive from the database
			
				if($from_to=='from')
				{
					$value =   date('H:i', strtotime($hourly->avail_from));	
					
				}else{
						
					$value =  date('H:i', strtotime($hourly->avail_to));	
									
					if($hourly->avail_to=='24:00:00'){$value='24:00';}
					
				}
			
			}
		
		
		}
		
		return $value;
	
	}
	
	public function get_hourly_for_staf($staff_id, $day)
	{
		global $wpdb, $bookingultrapro;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'bup_staff_availability  WHERE avail_staff_id = "'.$staff_id.'"  AND avail_day= "'.$day.'" ' ;
		$rows = $wpdb->get_results($sql);
		
		if ( !empty( $rows ) )
		{
			foreach ( $rows as $row )
			{
				//$row_ret = array(); 
				return $row;
				
			}
		}else{
			
			return false;
		
		
		}
	
	
	}
	
	
	//returns the business hours drop down
	public function get_business_hours_drop_down_for_staff($day, $cbox_id, $select_start_to_class, $staff_id)
	{
		global  $bookingultrapro;
		
		//bup_calendar_working_hours_start
		
		$hours = 24; //amount of hours working in day		
		$min_minutes = $bookingultrapro->get_option('bup_time_slot_length');
		
		//added on 11-28-2017 to give flexibility on the schedule		
		$min_minutes_schedule = $bookingultrapro->get_option('bup_calendar_working_hours_start');
		
		if($min_minutes_schedule ==''){
			
			if($min_minutes ==''){$min_minutes=15;}	
			
		}else{
			
			$min_minutes=$min_minutes_schedule ;
			
		
		}
				
		
		
		
		$min_minutes_set=$min_minutes;
				
		$hours = (60/$min_minutes) *$hours;		
		$min_minutes=$min_minutes*60;		
		
		
		$html .= '<select id="'.$cbox_id.'" name="'.$cbox_id.'" class="'.$select_start_to_class.'">';
		
		//get default value for this week's day		
		if($select_start_to_class=='bup_select_start')
		{
			$from_to_value = 'from';		
			
		}else{
				
			$from_to_value = 'to';			
			
		}
		
		//if($from_to_value == 'from')
		//{
			$html .= '<option '.$selected.' value="">'.__('OFF','booking-ultra-pro').'</option>';
		//}	
			
		//check selected value
		$selected_value = $this->get_business_hour_option($day, $from_to_value, $staff_id);		
		
		for($i = 0; $i <= $hours ; $i++)
		{ 		
			$minutes_to_add = $min_minutes * $i; // add 30 - 60 - 90 etc.
			$timeslot = date('H:i:s', strtotime('midnight')+$minutes_to_add);	
			
						
			$selected = '';				
			if($selected_value==date('H:i', strtotime($timeslot)))
			{
				$selected = 'selected="selected"';
				
			}elseif($selected_value=='24:00' && date('H:i', strtotime($timeslot)) =='00:00'){
				
				$selected = 'selected="selected"';
			
		    }
			
			if( ($from_to_value == 'to' && $i == 48 && $min_minutes_set==30) || ($from_to_value == 'to' && $i == 96 && $min_minutes_set==15) || ($from_to_value == 'to' && $i == 24 && $min_minutes_set==60) )
			{
				$sel_value ='24:00';
							
			}else{
								
				$sel_value =date('H:i', strtotime($timeslot));			
				
			}	
			
			
			$html .= '<option value="'.$sel_value.'" '.$selected.'  >'.date('h:i A', strtotime($timeslot)).'</option>';
						
			
		}
		
		
		
		$html .='</select>';
		
		return $html;
	
	}
	
	//returns the business hours drop down
	public function get_business_hours_drop_down($day, $cbox_id, $select_start_to_class)
	{
		global  $bookingultrapro;
		
		$hours = 24; //amount of hours working in day		
		$min_minutes = $bookingultrapro->get_option('bup_time_slot_length');
		
		$selected = "";
		$html = "";
				
		if($min_minutes =='')
		{
			$min_minutes=15;						
		}
		
		$min_minutes_set=$min_minutes;
				
		$hours = (60/$min_minutes) *$hours;		
		$min_minutes=$min_minutes*60;
		
		//get default value for this week's day		
		if($select_start_to_class=='bup_select_start')
		{
			$from_to_value = 'from';		
			
		}else{
				
			$from_to_value = 'to';			
			
		}		
		
		
		$html .= '<select id="'.$cbox_id.'" name="'.$cbox_id.'" class="'.$select_start_to_class.'">';				
		$html .= '<option '.$selected.' value="">'.__('OFF','booking-ultra-pro').'</option>';		
			
		//check selected value
		$selected_value = $this->get_business_hour_option($day, $from_to_value);		
		
		for($i = 0; $i <= $hours ; $i++)
		{ 		
			$minutes_to_add = $min_minutes * $i; // add 30 - 60 - 90 etc.
			$timeslot = date('H:i:s', strtotime($row['hours_start'])+$minutes_to_add);	
			
			$selected = '';				
			if($selected_value==date('H:i', strtotime($timeslot)))
			{
				$selected = 'selected="selected"';
				
			}elseif($selected_value=='24:00' && date('H:i', strtotime($timeslot)) =='00:00'){
				
				$selected = 'selected="selected"';
			
		    }
			
			if( ($from_to_value == 'to' && $i == 48 && $min_minutes_set==30) || ($from_to_value == 'to' && $i == 96 && $min_minutes_set==15) || ($from_to_value == 'to' && $i == 24 && $min_minutes_set==60))
			{
				$sel_value ='24:00';
							
			}else{
								
				$sel_value =date('H:i', strtotime($timeslot));			
				
			}	
			
			
			$html .= '<option value="'.$sel_value.'" '.$selected.'  >'.date('h:i A', strtotime($timeslot)).'</option>';
			
			
		}
		
		$html .='</select>';
		
		return $html;
	
	}
	
		
	
	
	
	public function get_admin_categories()
	{
		$rows = $this->get_all_categories();
		
		$html = '';
		
		
		$html .='<h3>'.__('Categories','booking-ultra-pro').' ('.count($rows).')</h3>';
		
		$html .='<span class="bup-add-service"><a href="#" id="bup-add-category-btn" title="'.__('Add New Category','booking-ultra-pro').'" ><i class="fa fa-plus"></i></a></span>';
		
				
		$html .='<ul >';
		
		
		
		
				
		if ( !empty( $rows ) )
		{
			foreach ( $rows as $row )
			{
				$html .= '<li>';
				
				$html .='<span class="bup-action-service"><a href="#" class="ubp-category-delete"  title="'.__('Delete','booking-ultra-pro').'" category-id="'.$row->cate_id.'" ><i class="fa fa-trash-o"></i></a> <a href="#" class="bup-edit-category-btn" category-id="'.$row->cate_id.'" id="bup-eidt-category-btn" title="'.__('Edit','booking-ultra-pro').'" ><i class="fa fa-edit"></i></a></span>';
				
				$html .= '<a href="#" class="ubp-load-services-by-cate" data-id="'.$row->cate_id.'">'.$row->cate_id." - ".$row->cate_name.'</a>';
				
				$html .= '</li>';			
			
			}
			
			$html .= '<p><a href="#" class="ubp-load-services-by-cate" data-id=""><i class="fa fa-refresh"></i>&nbsp;'.__('reload all','booking-ultra-pro').'</a></p>';
			
			
		}else{
		
			$html .= '<p>'.__('There are no categories','booking-ultra-pro').'</p>';
				
	    }
		
		$html .='</ul>';
       
		
		
		return $html ;	
		
	
	}
	
	public function get_admin_services($cate_id = null)
	{
		global $bookingultrapro, $bupcomplement;
		$html = '';
		
		$rows = $this->get_all_services($cate_id);
		
		$html .='<div class="bup-service-header-bar">';
		$html .='<h3>'.__('Services','booking-ultra-pro').' ('.count($rows).')</h3>';
		
		$html .='<span class="bup-add-service-m"><a href="#" id="bup-add-service-btn" title="'.__('Add New Service','booking-ultra-pro').'" ><i class="fa fa-plus"></i></a></span>';
		$html .='</div>';
		
			
		
		if ( !empty( $rows ) )
		{
			$html .= '<table width="100%" class="wp-list-table widefat fixed posts table-generic">
            <thead>';
			
		$html .= '<thead>
                <tr >
				    <th width="2%"><div style:background-color:></div></th>
					 <th width="4%">'.__('ID', 'booking-ultra-pro').'</div></th>
                    <th width="24%">'.__('Title', 'booking-ultra-pro').'</th>
                    <th width="19%">'.__('Duration', 'booking-ultra-pro').'</th>
                    <th width="26%">'.__('Price', 'booking-ultra-pro').'</th>
                    <th width="13%">'.__('Capacity', 'booking-ultra-pro').'</th>
                    <th width="16%">'.__('Category', 'booking-ultra-pro').'</th>
					<th width="16%">'.__('Actions', 'booking-ultra-pro').'</th>
                </tr>
            </thead>
            
            <tbody>';	
			
			foreach ( $rows as $row )
			{
				//duration 
				
				$duration = $this->get_service_duration_format($row->service_duration);	
				
				$html .= '<tr>
				    <td><div class="service-color-blet" style="background-color:'.$row->service_color.';" ></div></td>
					<td>'.$row->service_id.'</td>
                    <td>'.$row->service_title.'</td>
                    <td>'.$duration.'</td>
                    <td>'.$row->service_price.'</td>
                    <td>'.$row->service_capacity.'</td>
					<td>'.$row->cate_name.'</td>
                   <td><a href="#" class="ubp-service-delete"  title="'.__('Delete','booking-ultra-pro').'" service-id="'.$row->service_id.'" ><i class="fa fa-trash-o"></i></a>&nbsp;<a class="bup-admin-edit-service" href="#" id="" service-id="'.$row->service_id.'" ><span><i class="fa fa-edit fa-lg"></i></span></a>'; 
				   
				   if($row->service_allow_multiple==1 && isset($bupcomplement))
				   
				   {
				  	 $html .= '&nbsp;<a class="bup-admin-edit-pricing" href="#" id="" service-id="'.$row->service_id.'" ><span><i class="fa fa-users fa-lg"></i></span></a>';
				   
				   }
				   
				   $html .= ' </td>
                </tr>';			
			
			}
		}else{
		
			$html .= '<p>'.__('There are no services within this category','booking-ultra-pro').'</p>';
				
	    }
		
        $html .= '</table>';
		
		return $html ;	
		
	
	}
	
	public function get_price_for_person ($service_id, $person_number) 
	{
		global $wpdb, $bookingultrapro;
		
		$sql ="SELECT * FROM " . $wpdb->prefix . "bup_service_variable_pricing  
			WHERE rate_service_id = '".$service_id."' AND rate_person = '".$person_number."' ;";
		
		
		$res = $wpdb->get_results($sql);
		
		if (!empty($res))
		{
			
			foreach($res as $price) 
			{
				$service_price = $price->rate_price;				
				
			}		
		
		}else{
			
			$service_price = 0;	
			
		}
		
		
		return $service_price ;	
	
	}
	
	public function get_service_pricing ()
	{
		global $wpdb, $bookingultrapro;
		
		$service_id = $_POST["service_id"];
		
		$value= '';
		$html = '';
		
		$service =$bookingultrapro->service->get_one_service($service_id);
		$service_capacity = $service->service_capacity;
		
	
		$html .= '<div class="bup-customizer">' ;
		$html .= '<table width="100%" class="wp-list-table widefat fixed posts table-generic">
            <thead>
                <tr>
                    <th width="50%" style="color:# 333">'.__('Person/s', 'booking-ultra-pro').'</th>
                    <th width="50%">'.__('Price', 'booking-ultra-pro').'</th>
                    
                </tr>
            </thead>
            
            <tbody>' ;	
			
			
				$html .='<ul>';
				
				$i = 1;
				while ($i <= $service_capacity) {
				
					
					$current_pricing = $this->get_price_for_person ($service_id, $i);
					
					$label = $i.__(' Person', 'booking-ultra-pro') 	;
					
					if($i >1){$label = $label.'s';}
								
							
					 $html .= '<tr>
						 <td width="50%">'.$label.'</td>
						 <td width="50%"><input type="text" style="width:99%"  id="bup_pricing_id_'.$i.'"  name="bup_pricing['.$i.']" class="bup-servicepricing-textbox" value="'.$current_pricing.'"  /></td>
					   </tr>';
					   
					   $i++;
					   
					   
			   }	
			
			
		
		$html .= '<input type="hidden"  id="bup_pricing_service_id"  name="bup_pricing_service_id"  value="'.$service_id.'"  /></tbody>
        </table>';
        			
		
		$html .= '</div>' ;
		
		echo $html;
		
		die();
		
		
	}
	
	public function update_group_pricing_table()
	{
		$service_id = $_POST['service_id']	;		
		$pricing_list = $_POST["pricing_list"]; 	
		
		if($service_id!='')
		{
			//delete old pricing for this service
			
			$this->delete_service_pricing($service_id);						
			$pricing_list =rtrim($pricing_list,"|");
			$pricing_list = explode("|", $pricing_list);
			
			$persons=1;								
			foreach($pricing_list as  $price)
			{				
				$this->insert_service_pricing($service_id, $persons, $price);
				$persons++;				
			
			}	
			
		}
		
		print_r($pricing_list);	
		
		die();
	}
	
	function delete_service_pricing($service_id)
	{
		global  $wpdb, $bookingultrapro;
		
		$sql = 'DELETE FROM ' . $wpdb->prefix . 'bup_service_variable_pricing  WHERE rate_service_id="'.(int)$service_id.'" ';
		
		$wpdb->query($sql);	
		
	}
	
	function insert_service_pricing($service_id, $persons, $price)
	{
		global  $wpdb, $bookingultrapro;
		
    	$new_record = array('rate_id' => NULL,	
								'rate_service_id' => $service_id,
								'rate_person' => $persons,
								'rate_price' => $price,
								);								
									
		$wpdb->insert( $wpdb->prefix . 'bup_service_variable_pricing', $new_record, array( '%d', '%s', '%s', '%s'));
		
	}
	
	function get_categories_drop_down($category = null)
	{
		global  $bookingultrapro;
		
		$html = '';
		
		$cate_rows = $this->get_all_categories();	
		
		$html .= '<select name="bup-category" id="bup-category">';
		
		foreach ( $cate_rows as $cate )
		{
			$selected = '';
			if($category==$cate->cate_id){$selected='selected="selected"';}
		
			$html .= '<option value="'.$cate->cate_id.'" '.$selected.'>'.$cate->cate_name.'</option>';
			
		}
		
		$html .= '</select>';
		
		return $html;
	
	}
	
	
	function get_staff_offering_service($service_id)
	{
		global  $bookingultrapro, $wpdb;
		
		$html = array();
		
		$category_id = $_POST['b_category'];		
		
		$sql = ' SELECT serv.*,user.*  FROM ' . $wpdb->users . '  user ' ;		
		$sql .= "RIGHT JOIN ".$wpdb->prefix ."bup_service_rates serv ON (serv.rate_service_id = '".$service_id.
		"')";
		$sql .= ' WHERE user.ID = serv.rate_staff_id' ;					
		$sql .= ' ORDER BY user.display_name ASC  ' ;
		
		$users = $wpdb->get_results($sql);		
		
		if (!empty($users))
		{
			
			foreach($users as $user) 
			{
				$html[$user->ID] = $user->ID;				
				
			}
		
		
		}
		
		
		return $html;
		
	
	}
	
	function get_cate_dw_admin_ajax()
	{
		global  $bookingultrapro, $wpdb;
		
		$html = '';
		
		$currency_symbol = $bookingultrapro->get_currency_symbol();
		$display_price = $bookingultrapro->get_option('price_on_staff_list_front');
		$price_label = '';
		$staff_id = '';
		
		$category_id = '';
		$appointment_id = '';
		if(isset($_POST['b_category']))
		{
			$category_id = $_POST['b_category'];
			
		}
		
		if(isset($_POST['appointment_id']))
		{
			$appointment_id = $_POST['appointment_id'];	
			
		}
		
		
		
		//get appointment			
		$appointment = $bookingultrapro->appointment->get_one($appointment_id);
		$staff_id = $appointment->booking_staff_id;	
		
		$sql = ' SELECT serv.*,user.*  FROM ' . $wpdb->users . '  user ' ;		
		$sql .= "RIGHT JOIN ".$wpdb->prefix ."bup_service_rates serv ON (serv.rate_service_id = '".$category_id.
		"')";
		$sql .= ' WHERE user.ID = serv.rate_staff_id' ;		
		$sql .= ' GROUP BY user.ID	  ' ;
					
		$sql .= ' ORDER BY user.display_name ASC  ' ;
		
		$users = $wpdb->get_results($sql);

	
		$html = '';
		
		$html .= '<div class="field-header">'.__('With','booking-ultra-pro').'</div>';		
		$html .= '<select name="bup-staff" id="bup-staff">';
		$html .= '<option value="" selected="selected" >'.__('Any', 'booking-ultra-pro').'</option>';
		
		if (!empty($users))
		{			
			foreach($users as $user) 
			{
				$service_details = $bookingultrapro->userpanel->get_staff_service_rate( $user->ID, $category_id );				
				$service_price = 	$service_details['price'];
				
				if($display_price=='' || $display_price=='yes')
				{
					$price_label= '('.$currency_symbol.''.$service_price.')';				
				}
				
				$selected='';
				if($staff_id==$user->ID)
				{
					$selected='selected';				
				}
						
				$html .= '<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.' '.$price_label.'</option>';		
				
				
			}
			$html .= '</select>';
		
		
		}
		
		
		echo $html;
		die();
	
	}
	
	//used when using service_id shortcode only	
	function get_cate_list_front($category_id, $template_id)
	{
		global  $bookingultrapro, $wpdb;
		
		$html = '';
		
		$currency_symbol = $bookingultrapro->get_currency_symbol();
		$display_price = $bookingultrapro->get_option('price_on_staff_list_front');
		$price_label = '';
		
		$filter_id = $_POST['filter_id'];
		
		if($template_id!='')
		{
			$select_label = $bookingultrapro->get_template_label("select_provider_label",$template_id);
		
		}else{
			
			$select_label = __('With','booking-ultra-pro');			
		
		}
		
		$selected = '';	
		
		if($filter_id=='')
		{
			
			$sql = ' SELECT serv.*,user.*  FROM ' . $wpdb->users . '  user ' ;		
			$sql .= "RIGHT JOIN ".$wpdb->prefix ."bup_service_rates serv ON (serv.rate_service_id = '".$category_id.
			"')";
			$sql .= ' WHERE user.ID = serv.rate_staff_id' ;	
			$sql .= ' GROUP BY user.ID	  ' ;				
			$sql .= ' ORDER BY user.display_name ASC  ' ;
		
		}else{
			
			$sql = ' SELECT serv.*, user.*, staff_location.*  FROM ' . $wpdb->prefix . 'users  user ' ;		
			$sql .= "RIGHT JOIN ".$wpdb->prefix ."bup_service_rates serv ON (serv.rate_service_id = '".$category_id.
			"')";
			$sql .= " RIGHT JOIN ". $wpdb->prefix."bup_filter_staff staff_location ON (staff_location.fstaff_staff_id = user.ID)";
			
			$sql .= " WHERE user.ID = serv.rate_staff_id AND staff_location.fstaff_staff_id = user.ID AND  staff_location.fstaff_location_id = '".$filter_id."' " ;
			
			$sql .= ' GROUP BY user.ID	  ' ;
								
			$sql .= ' ORDER BY user.display_name ASC  ' ;
			
		}
		
		
		$users = $wpdb->get_results($sql);

	
		$html = '';
		
		$html .= '<label>'.$select_label.'</label>';		
		$html .= '<select name="bup-staff" id="bup-staff">';
		$html .= '<option value="" selected="selected" >'.__('Any', 'booking-ultra-pro').'</option>';
		
		if (!empty($users))
		{			
			foreach($users as $user) 
			{
				$service_details = $bookingultrapro->userpanel->get_staff_service_rate( $user->ID, $category_id );				
				$service_price = 	$service_details['price'];
				
				if($display_price=='' || $display_price=='yes')
				{
					$price_label= '('.$currency_symbol.''.$service_price.')';				
				}
						
				$html .= '<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.' '.$price_label.'</option>';		
				
				
			}
			$html .= '</select>';
		
		
		}
		
		
		echo $html;
		die();
	
	}
	
	function get_random_staff_member_for_location($filter_id , $service_id)
	{
		global  $bookingultrapro, $wpdb;
		
		$staff_id = '';
		$staff_members = array();
		
	
		$sql = ' SELECT serv.*, user.*, staff_location.*  FROM ' . $wpdb->users . '  user ' ;		
		$sql .= "RIGHT JOIN ".$wpdb->prefix ."bup_service_rates serv ON (serv.rate_service_id = '".$service_id.
			"')";
		$sql .= " RIGHT JOIN ". $wpdb->prefix."bup_filter_staff staff_location ON (staff_location.fstaff_staff_id = user.ID)";
			
		$sql .= " WHERE user.ID = serv.rate_staff_id AND staff_location.fstaff_staff_id = user.ID AND  staff_location.fstaff_location_id = '".$filter_id."' " ;					
		$sql .= ' ORDER BY user.display_name ASC  ' ;
		
		$users = $wpdb->get_results($sql);
		
		if (!empty($users))
		{			
			foreach($users as $user) 
			{
				$staff_members[$user->ID] = $user->ID;				
				
			}	
		}		
		
		$staff_id = $staff_members[array_rand($staff_members)];		
		
		return $staff_id;
		
	
	}
	
	function get_cate_dw_ajax()
	{
		global  $bookingultrapro, $wpdb;
		
		$html = '';
		
		$currency_symbol = $bookingultrapro->get_currency_symbol();
		$display_price = $bookingultrapro->get_option('price_on_staff_list_front');
		$price_label = '';
		
		$category_id = $_POST['b_category'];
		$filter_id = $_POST['filter_id'];
		$template_id = $_POST['template_id'];
		
		if($template_id!='')
		{
			$select_label = $bookingultrapro->get_template_label("select_provider_label",$template_id);
		
		}else{
			
			$select_label = __('With','booking-ultra-pro');			
		
		}
		
		$selected = '';	
		
		if($filter_id=='')
		{
			
			$sql = ' SELECT serv.*,  user.*  FROM ' . $wpdb->users . '  user ' ;		
			$sql .= "RIGHT JOIN ".$wpdb->prefix ."bup_service_rates serv ON (serv.rate_service_id = '".$category_id."')";			
					
			$sql .= ' WHERE user.ID = serv.rate_staff_id AND serv.rate_service_id = "'.$category_id.'"' ;
			$sql .= ' GROUP BY user.ID	  ' ;		
						
			$sql .= ' ORDER BY user.display_name ASC  ' ;
			
			
		
		}else{
			
			$sql = ' SELECT serv.*, user.*, staff_location.*  FROM ' . $wpdb->users . '  user ' ;		
			$sql .= "RIGHT JOIN ".$wpdb->prefix ."bup_service_rates serv ON (serv.rate_service_id = '".$category_id.
			"')";
			$sql .= " RIGHT JOIN ". $wpdb->prefix."bup_filter_staff staff_location ON (staff_location.fstaff_staff_id = user.ID)";
			
			$sql .= " WHERE user.ID = serv.rate_staff_id AND staff_location.fstaff_staff_id = user.ID AND  staff_location.fstaff_location_id = '".$filter_id."' " ;	
			
			$sql .= ' GROUP BY user.ID	  ' ;
							
			$sql .= ' ORDER BY user.display_name ASC  ' ;
			
		}
		
		
		$users = $wpdb->get_results($sql);
	
		$html = '';
		
		$html .= '<label>'.$select_label.'</label>';		
		$html .= '<select name="bup-staff" id="bup-staff">';
		$html .= '<option value="" selected="selected" >'.__('Any', 'booking-ultra-pro').'</option>';
		
		if (!empty($users))
		{			
			foreach($users as $user) 
			{
				$service_details = $bookingultrapro->userpanel->get_staff_service_rate( $user->ID, $category_id );				
				$service_price = 	$service_details['price'];
				
				if($display_price=='' || $display_price=='yes')
				{
					$price_label= '('.$currency_symbol.''.$service_price.')';				
				}
						
				$html .= '<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.' '.$price_label.'</option>';		
				
				
			}
			$html .= '</select>';
		
		
		}
		
		
		echo $html;
		die();
	
	}
	
	function get_categories_drop_down_public($service_id = null, $staff_id = null , $category_ids = null, $template_id = null)
	{
		global  $bookingultrapro;
		
		$html = '';
		
		$cate_rows = $this->get_all_categories();
		
		//check if category restriction applied		
		$allowed_cate = array();		
		if($category_ids!='')
		{			
			$allowed_cate = explode(",", $category_ids);		
		}
		
		if($template_id!=''){
			
			$service_label =  $bookingultrapro->get_template_label("select_service_label_drop",$template_id);	
		
		}else{
			
			$service_label =  __('Select Service','booking-ultra-pro');		
		}
		
		
		$html .= '<select name="bup-category" id="bup-category">';
		$html .= '<option value="" selected="selected">'.$service_label .'</option>';
		
		foreach ( $cate_rows as $cate )
		{
			//is this user offering (display only the speficied categories)			
			if($category_ids!='' && !in_array($cate->cate_id,$allowed_cate))
				{
					continue;					
				}
			
			if($staff_id!='') //a staff id has been set, then we have to check if the user offers this service
			{
				//is this staff member offering this?	
				if(!$bookingultrapro->userpanel->staff_offer_this_category( $staff_id, $cate->cate_id ))
				{
					//echo "not offered : " .$cate->cate_name ."<br>";
					continue;
				}			
			}
			
			
			$html .= '<optgroup label="'.$cate->cate_name.'" >';
			
			//get services						
			$servi_rows = $this->get_all_services($cate->cate_id);
			foreach ( $servi_rows as $serv )
			{
				$selected = '';
				
				
				if($staff_id!='') //check if the staff offers this service
				{
					if($bookingultrapro->userpanel->staff_offer_service( $staff_id, $serv->service_id ))
					{
						$html .= '<option value="'.$serv->service_id.'" '.$selected.' >'.$serv->service_title.'</option>';					
					}					
				
				}else{
					
					$html .= '<option value="'.$serv->service_id.'" '.$selected.' >'.$serv->service_title.'</option>';
				
				}
			}
			
			$html .= '</optgroup>';
			
			
			
		}
		
		$html .= '</select>';
		
		return $html;
	
	}
	
	function get_categories_drop_down_admin($service_id = null)
	{
		global  $bookingultrapro;
		
		$html = '';
		
		$cate_rows = $this->get_all_categories();
		
		
		$html .= '<select name="bup-category" id="bup-category">';
		$html .= '<option value="" selected="selected">'.__('Select a Service','booking-ultra-pro').'</option>';
		
		foreach ( $cate_rows as $cate )
		{		
			
			$html .= '<optgroup label="'.$cate->cate_name.'" >';
			
			//get services						
			$servi_rows = $this->get_all_services($cate->cate_id);
			foreach ( $servi_rows as $serv )
			{
				$selected = '';
				
						
				if($serv->service_id==$service_id){$selected = 'selected';}
				$html .= '<option value="'.$serv->service_id.'" '.$selected.' >'.$serv->service_title.'</option>';
				
			}
			
			$html .= '</optgroup>';
			
		}
		
		$html .= '</select>';
		
		return $html;
	
	}
	
	function get_duration_drop_down($seconds = null)
	{
		global  $bookingultrapro, $bupcomplement;
		
		$html = '';
		
		//$max_hours = 43200; //12 hours in seconds	
		$max_hours = 43200; //12 hours in seconds		
		$min_minutes = $bookingultrapro->get_option('bup_time_slot_length');
		
		if($min_minutes ==''){$min_minutes=15;}		
		$min_minutes=$min_minutes*60;
		
		$html .= '<select name="bup-duration" id="bup-duration">';
		
		for ($x = $min_minutes; $x <= $max_hours; $x=$x+$min_minutes)
		{
			$selected = '';
			if($seconds==$x){$selected='selected="selected"';}
		
			$html .= '<option value="'.$x.'" '.$selected.'>'.$this->get_service_duration_format($x).'</option>';
			
		}
		
		if(isset($bupcomplement))
		{
			$selected = '';		
			if($seconds==86400){$selected='selected="selected"';}		
			$html .= '<option value="86400" '.$selected.'>'.__('All Day ','booking-ultra-pro').'</option>';
		}
		
		
		
		$html .= '</select>';
		
		return $html;
	
	}
	
	function get_service_duration_format($seconds)
	{
		global $wpdb, $bookingultrapro;
		
		$time_formated = $bookingultrapro->commmonmethods->secondsToTime($seconds);
		
		
		if($seconds<3600) //less than an hour
		{
			$str = $time_formated["m"] . " min ";		
		
		}else{
			
			$str = $time_formated["h"] ." h ";
			
			
			if($time_formated["m"] > 0)
			 {
				$str =  $str." ".$time_formated["m"]." min ";
			
			}
			
		
		
		}
		
		
		
		return $str;
	
	
	}
	
	
	public function get_all_categories () 
	{
		global $wpdb, $bookingultrapro;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'bup_categories ORDER BY cate_name ASC  ' ;
		$res = $wpdb->get_results($sql);
		return $res ;	
	
	}
	
	public function get_all_services ($cate_id = NULL) 
	{
		global $wpdb, $bookingultrapro;
		
		$sql = ' SELECT serv.*, cate.* FROM ' . $wpdb->prefix . 'bup_services  serv ' ;		
		$sql .= " RIGHT JOIN ".$wpdb->prefix ."bup_categories cate ON (cate.cate_id = serv.service_category_id)";
		$sql .= ' WHERE cate.cate_id = serv.service_category_id' ;
		
		if($cate_id!='')
		{
			$sql .= ' AND serv.service_category_id = "'.(int)$cate_id.'"  ' ;		
		}
		
		$sql .= ' ORDER BY serv.service_category_id ASC, serv.service_title ASC  ' ;
		
		$res = $wpdb->get_results($sql);
		return $res ;	
	
	}
	
	public function get_one_service ($service_id) 
	{
		global $wpdb, $bookingultrapro;
		
		$sql = ' SELECT serv.*, cate.* FROM ' . $wpdb->prefix . 'bup_services  serv ' ;
		
		$sql .= " RIGHT JOIN ".$wpdb->prefix ."bup_categories cate ON (cate.cate_id = serv.service_category_id)";
		$sql .= ' WHERE cate.cate_id = serv.service_category_id' ;			
		$sql .= ' AND serv.service_id = "'.(int)$service_id.'"  ' ;					
				
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				return $row;			
			
			}
			
		}	
	
	}
	
	public function get_one_category ($category_id) 
	{
		global $wpdb, $bookingultrapro;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'bup_categories  ' ;
		$sql .= ' WHERE cate_id = "'.$category_id.'"' ;			
				
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				return $row;			
			
			}
			
		}	
	
	}

	
}
$key = "service";
$this->{$key} = new BookingUltraService();
?>