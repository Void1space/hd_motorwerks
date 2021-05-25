<?php
class BookingUltraOrder 
{
	var $pages;
	var $total_result;

	function __construct() 
	{
		$this->ini_db();		

	}
	
	public function ini_db()
	{
		global $wpdb;			

		// Create table
		$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'bup_orders (
				`order_id` bigint(20) NOT NULL auto_increment,				
				`order_booking_id` int(11) NOT NULL,
				`order_method_name`  varchar(60) NOT NULL,				
				`order_key` varchar(250) NOT NULL,
				`order_txt_id` varchar(60) NOT NULL,
				`order_status` varchar(60) NOT NULL,
				`order_amount` decimal(11,2) NOT NULL,
				`order_qty` int(11) NOT NULL DEFAULT "1",
				`order_date` date NOT NULL,									 			
				PRIMARY KEY (`order_id`)
			) COLLATE utf8_general_ci;';
	
	
		$wpdb->query( $query );	
		
		$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'bup_bookings (
				`booking_id` bigint(20) NOT NULL auto_increment,
				`booking_user_id` int(11) NOT NULL,
				`booking_service_id` int(11) NOT NULL,
				`booking_staff_id` int(11) NOT NULL,
				`booking_cart_id` int(11) NOT NULL DEFAULT "0",
				`booking_template_id` int(1) NOT NULL DEFAULT "0",
				`booking_date` date NOT NULL,					
				`booking_time_from` datetime NOT NULL,	
				`booking_time_to` datetime NOT NULL,	
				`booking_time_offset` int(11) NOT NULL DEFAULT "0",	
				`booking_status` int(1) NOT NULL DEFAULT "0",
				`booking_qty` int(11) NOT NULL DEFAULT "1",					
				`booking_qty_2` int(11) NOT NULL DEFAULT "0",	
				`booking_amount` decimal(11,2) NOT NULL,
				`booking_key` varchar(250) NOT NULL,					 			
				PRIMARY KEY (`booking_id`)
			) COLLATE utf8_general_ci;';
	
	
		$wpdb->query( $query );	
		
		
		$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'bup_bookings_meta (
				`meta_id` bigint(20) NOT NULL auto_increment,
				`meta_booking_id` int(11) NOT NULL,				
				`meta_booking_name` varchar(300) NOT NULL,
				`meta_booking_value` longtext,					 			
				PRIMARY KEY (`meta_id`)
			) COLLATE utf8_general_ci;';
	
		$wpdb->query( $query );
		
		
		$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'bup_carts (
				`cart_id` bigint(20) NOT NULL auto_increment,
				`cart_key` varchar(250) NOT NULL,
				`cart_date` date NOT NULL,
				`cart_amount` decimal(11,2) NOT NULL,	
				`cart_status` int(1) NOT NULL DEFAULT "0",			 			
				PRIMARY KEY (`cart_id`),
				UNIQUE KEY `cart_key` (`cart_key`)
			) COLLATE utf8_general_ci;';
	
		$wpdb->query( $query );		
		
		$this->update_table();
		
	}
	
	
	function update_table()
	{
		global $wpdb;
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'bup_bookings where field="booking_qty" ';		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) )
		{	
			$sql = 'Alter table  ' . $wpdb->prefix . 'bup_bookings add column booking_qty int (11) default 1 ; ';
			$wpdb->query($sql);
		}
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'bup_bookings where field="booking_qty_2" ';		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) )
		{	
			$sql = 'Alter table  ' . $wpdb->prefix . 'bup_bookings add column booking_qty_2 int (11) default 0 ; ';
			$wpdb->query($sql);
		}
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'bup_bookings where field="booking_time_offset" ';		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) )
		{	
			$sql = 'Alter table  ' . $wpdb->prefix . 'bup_bookings add column booking_time_offset int (11) default 0 ; ';
			$wpdb->query($sql);
		}	
		
		
		
		
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'bup_bookings where field="booking_template_id" ';		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) )
		{	
			$sql = 'Alter table  ' . $wpdb->prefix . 'bup_bookings add column booking_template_id int (11) default 0 ; ';
			$wpdb->query($sql);
		}
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'bup_bookings where field="booking_cart_id" ';		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) )
		{	
			$sql = 'Alter table  ' . $wpdb->prefix . 'bup_bookings add column booking_cart_id int (11) default 0 ; ';
			$wpdb->query($sql);
		}
		
		
		
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'bup_orders where field="order_qty" ';		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) )
		{	
			$sql = 'Alter table  ' . $wpdb->prefix . 'bup_orders add column order_qty int (11) default 1 ; ';
			$wpdb->query($sql);
		}
		
		
								
		
		
	}
	
	public function update_cart_amount ($cart_id,$amount)
	{
		global $wpdb,  $bookingultrapro;
		
		$query = "UPDATE " . $wpdb->prefix ."bup_carts SET cart_amount = '$amount' WHERE cart_id = '$cart_id' ";
		$wpdb->query( $query );
	
	}
	
	/*Create Order*/
	public function create_order ($orderdata)
	{
		global $wpdb,  $bookingultrapro;
		
		extract($orderdata);
		
		//update database
		$query = "INSERT INTO " . $wpdb->prefix ."bup_orders (`order_booking_id`,`order_key`, `order_method_name`, `order_status` ,`order_amount` , `order_qty`, `order_date`) VALUES ('$booking_id','$transaction_key','$method','$status', '$amount', '$quantity',  '".date('Y-m-d')."')";
		
		//echo $query;						
		$wpdb->query( $query );	
		return $wpdb->insert_id;					
						
	}
	
	/*Create Order*/
	public function create_cart ($transaction_key)
	{
		global $wpdb,  $bookingultrapro;
		
		//update database
		$query = "INSERT INTO " . $wpdb->prefix ."bup_carts (`cart_key`,`cart_date`) VALUES ('$transaction_key',  '".date('Y-m-d')."')";		
		$wpdb->query( $query );	
		return $wpdb->insert_id;					
						
	}
	
	/*Create Appointment*/
	public function create_reservation ($orderdata)
	{
		global $wpdb,  $bookingultrapro;
		
		extract($orderdata);
		
		$start = $day.' '.$time_from.':00';
		$ends = $day.' '.$time_to.':00';
		
		//update database
		$query = "INSERT INTO " . $wpdb->prefix ."bup_bookings (`booking_user_id`,`booking_service_id`, `booking_staff_id`, `booking_date` ,`booking_time_from` ,`booking_time_to`  , `booking_amount`, `booking_key`, `booking_qty`,  `booking_template_id`,  `booking_cart_id`) VALUES ('$user_id','$service_id','$staff_id','".date('Y-m-d')."','$start', '$ends', '$amount', '$transaction_key',  '$quantity', '$template_id' , '$cart_id')";
		
		//echo $query;						
		$wpdb->query( $query );		
		
		return $wpdb->insert_id;				
						
	}
	
	public function update_appointment ($orderdata)
	{
		global $wpdb,  $bookingultrapro;
		
		extract($orderdata);
		
		$start = $day.' '.$time_from.':00';
		$ends = $day.' '.$time_to.':00';
		
		//update database
		$query = "UPDATE " . $wpdb->prefix ."bup_bookings SET `booking_service_id` = '$service_id', `booking_staff_id` = '$staff_id' , `booking_time_from` = '$start' ,`booking_time_to` = '$ends'  , `booking_amount` = '$amount' WHERE `booking_id` = '$booking_id' ";
		
		//echo $query;						
		$wpdb->query( $query );		
		
		return $wpdb->insert_id;				
						
	}
	
	public function update_order_status ($id,$status)
	{
		global $wpdb,  $bookingultrapro;
		
		//update database
		$query = "UPDATE " . $wpdb->prefix ."bup_orders SET order_status = '$status' WHERE order_id = '$id' ";
		$wpdb->query( $query );
	
	}
	
	public function update_cart_status ($id,$status)
	{
		global $wpdb,  $bookingultrapro;
		
		//update database
		$query = "UPDATE " . $wpdb->prefix ."bup_carts SET cart_status = '$status' WHERE cart_id = '$id' ";
		$wpdb->query( $query );
	
	}
	
	public function update_expiration_date ($id,$expiration_date)
	{
		global $wpdb,  $bookingultrapro;
		
		//update database
		$query = "UPDATE " . $wpdb->prefix ."bup_orders SET order_expiration = '$expiration_date' WHERE order_id = '$id' ";
		$wpdb->query( $query );
	
	}
	
	public function update_order_payment_response ($id,$order_txt_id)
	{
		global $wpdb,  $bookingultrapro;
		
		//update database
		$query = "UPDATE " . $wpdb->prefix ."bup_orders SET order_txt_id = '$order_txt_id' WHERE order_id = '$id' ";
		$wpdb->query( $query );
	
	}
	
	
	/*Get Order With Booking*/
	public function get_order_with_booking_id ($booking_id)
	{
		global $wpdb,  $bookingultrapro;
		
		$orders = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'bup_orders WHERE order_booking_id = "'.$booking_id.'" ' );
		
		if ( empty( $orders ) )
		{
		
		
		}else{
			
			
			foreach ( $orders as $order )
			{
				return $order;			
			
			}
			
		}
		
		
	
	}
	
	/*Get Cart*/
	public function get_cart_with_key_status ($key, $status)
	{
		global $wpdb,  $bookingultrapro;
		
		$orders = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'bup_carts WHERE cart_key = "'.$key.'" AND cart_status = "'.$status.'" ' );
		
		if ( empty( $orders ) )
		{
		
		
		}else{
			
			
			foreach ( $orders as $order )
			{
				return $order;			
			
			}
			
		}
		
		
	
	}
	
	
	/*Get Order*/
	public function get_order ($id)
	{
		global $wpdb,  $bookingultrapro;
		
		$orders = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'bup_orders WHERE order_key = "'.$id.'" ' );
		
		if ( empty( $orders ) )
		{
		
		
		}else{
			
			
			foreach ( $orders as $order )
			{
				return $order;			
			
			}
			
		}
		
		
	
	}
	
	/*Get Order*/
	public function get_order_edit ($order_id , $booking_id)
	{
		global $wpdb,  $bookingultrapro;
		
		$orders = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'bup_orders WHERE order_id = "'.$order_id.'" AND order_booking_id = "'.$booking_id.'" ' );
		
		if ( empty( $orders ) )
		{		
		
		}else{
			
			
			foreach ( $orders as $order )
			{
				return $order;			
			
			}
			
		}
		
		
	
	}
	
	/*Get Latest*/
	public function get_latest ($howmany)
	{
		global $wpdb,  $bookingultrapro;
		
		$sql = 'SELECT ord.*, usu.*	 FROM ' . $wpdb->prefix . 'bup_orders ord  ' ;		
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = ord.order_user_id)";		
		$sql .= " WHERE ord.order_id <> 0 AND usu.ID = ord.order_user_id ORDER BY ord.order_id desc  LIMIT $howmany";	
			
		$orders = $wpdb->get_results($sql );
		
		return $orders ;		
	
	}
	
	/*Get Orders*/
	public function get_booking_payments ($appointment_id)
	{
		global $wpdb,  $bookingultrapro;
		
		$sql = 'SELECT ord.*	 FROM ' . $wpdb->prefix . 'bup_orders ord  ' ;					
		$sql .= " WHERE ord.order_id <> 0 AND ord.order_booking_id = '".$appointment_id."' ORDER BY ord.order_date DESC  ";		
		
		$orders = $wpdb->get_results($sql );		
		return $orders ;		
	
	}
	
	public function get_booking_payments_balance ($appointment_id)
	{
		global $wpdb,  $bookingultrapro;
		
		$totals = array();
		
		$total_confirmed = 0;
		$total_pending = 0;
		$balance = 0;		
		$booking_cost = 0;
		
		$sql = 'SELECT SUM(order_amount) as total FROM ' . $wpdb->prefix . 'bup_orders   ' ;					
		$sql .= " WHERE order_booking_id = '".$appointment_id."' AND order_status = 'confirmed' ";			
		$orders = $wpdb->get_results($sql );
		
		foreach ( $orders as $order )
		{
			$total_confirmed =$order->total;					
			
		}
		
		$sql = 'SELECT SUM(order_amount) as total FROM ' . $wpdb->prefix . 'bup_orders   ' ;					
		$sql .= " WHERE order_booking_id = '".$appointment_id."' AND order_status = 'pending' ";			
		$orders = $wpdb->get_results($sql );
		
		foreach ( $orders as $order )
		{
			$total_pending =$order->total;					
			
		}
		
		$sql = 'SELECT booking_amount as total FROM ' . $wpdb->prefix . 'bup_bookings   ' ;					
		$sql .= " WHERE booking_id = '".$appointment_id."'  ";			
		$orders = $wpdb->get_results($sql );
		
		foreach ( $orders as $order )
		{
			$booking_cost =$total_confirmed+$total_pending;					
			
		}
		
		if($total_confirmed==''){$total_confirmed=0;}
		if($total_pending==''){$total_pending=0;}
		
		$balance = $booking_cost - $total_confirmed ;			
		$totals = array('cost' => $booking_cost ,'confirmed' => $total_confirmed , 'pending' => $total_pending , 'balance' => $balance);
				
		return $totals ;		
	
	}
	
	
	/*Get all*/
	public function get_all ()
	{
		global $wpdb,  $bookingultrapro;
		
		$keyword = "";
		$month = "";
		$day = "";
		$year = "";
		$howmany = "";
		$ini = "";
		
		if(isset($_GET["keyword"]))
		{
			$keyword = $_GET["keyword"];		
		}
		
		if(isset($_GET["month"]))
		{
			$month = $_GET["month"];		
		}
		
		if(isset($_GET["day"]))
		{
			$day = $_GET["day"];		
		}
		
		if(isset($_GET["year"]))
		{
			$year = $_GET["year"];		
		}
		
		if(isset($_GET["howmany"]))
		{
			$howmany = $_GET["howmany"];		
		}
		
		
				
		$uri= $_SERVER['REQUEST_URI'] ;
		$url = explode("&ini=",$uri);
		
		if(is_array($url ))
		{
			//print_r($url);
			if(isset($url["1"]))
			{
				$ini = $url["1"];
			    if($ini == ""){$ini=1;}
			
			}
		
		}		
		
		
		
		if($howmany == ""){$howmany=20;}
		
		
		
		//get total				
				
		$sql =  'SELECT count(*) as total, ord.*,  usu.*, serv.* , appo.*	 FROM ' . $wpdb->prefix . 'bup_orders ord  ' ;
		$sql .= " RIGHT JOIN ". $wpdb->prefix."bup_bookings appo ON (ord.order_booking_id = appo.booking_id)";				
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";	
		$sql .= " RIGHT JOIN ". $wpdb->prefix."bup_services serv ON (serv.service_id = appo.booking_service_id)";	
			
		$sql .= " WHERE serv.service_id = appo.booking_service_id  AND  ord.order_booking_id = appo.booking_id   ORDER BY appo.booking_time_from   asc ";
			
		if($keyword!="")
		{
			$sql .= " AND (ord.order_txt_id LIKE '%".$keyword."%' OR usu.display_name LIKE '%".$keyword."%' OR usu.user_email LIKE '%".$keyword."%' OR usu.user_login LIKE '%".$keyword."%'  )  ";
		}
		
		if($day!=""){$sql .= " AND DAY(ord.order_date) = '$day'  ";	}
		if($month!=""){	$sql .= " AND MONTH(ord.order_date) = '$month'  ";	}		
		if($year!=""){$sql .= " AND YEAR(ord.order_date) = '$year'";}	
		
		$orders = $wpdb->get_results($sql );
		$orders_total = $this->fetch_result($orders);
		$orders_total = $orders_total->total;
		$this->total_result = $orders_total ;
		
		$total_pages = $orders_total;
				
		$limit = "";
		$current_page = $ini;
		$target_page =  site_url()."/wp-admin/admin.php?page=bookingultra&tab=orders";
		
		$how_many_per_page =  $howmany;
		
		$to = $how_many_per_page;
		
		//caluculate from
		$from = $this->calculate_from($ini,$how_many_per_page,$orders_total );
		
		//$this->pages = $bookingultrapro->commmonmethods->getPages($total_pages, $current_page, $target_page, $how_many_per_page);
		
		//get all			
		
		$sql =  'SELECT ord.*,  usu.*, serv.* , appo.*	 FROM ' . $wpdb->prefix . 'bup_orders ord  ' ;
		$sql .= " RIGHT JOIN ". $wpdb->prefix."bup_bookings appo ON (ord.order_booking_id = appo.booking_id)";				
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";	
		$sql .= " RIGHT JOIN ". $wpdb->prefix."bup_services serv ON (serv.service_id = appo.booking_service_id)";	
			
		$sql .= " WHERE serv.service_id = appo.booking_service_id  AND  ord.order_booking_id = appo.booking_id  ";	
		
		
			
		if($keyword!="")
		{
			$sql .= " AND (ord.order_txt_id LIKE '%".$keyword."%' OR usu.display_name LIKE '%".$keyword."%' OR usu.user_email LIKE '%".$keyword."%' OR usu.user_login LIKE '%".$keyword."%'  )  ";
		}
		
		if($day!=""){$sql .= " AND DAY(ord.order_date) = '$day'  ";	}
		if($month!=""){	$sql .= " AND MONTH(ord.order_date) = '$month'  ";	}		
		if($year!=""){$sql .= " AND YEAR(ord.order_date) = '$year'";}	
		
		$sql .= " ORDER BY ord.order_id DESC";		
		
	    if($from != "" && $to != ""){	$sql .= " LIMIT $from,$to"; }
	 	if($from == 0 && $to != ""){	$sql .= " LIMIT $from,$to"; }
		
					
		$orders = $wpdb->get_results($sql );
		
		return $orders ;
		
	
	}
	
	public function calculate_from($ini, $howManyPagesPerSearch, $total_items)	
	{
		if($ini == ""){$initRow = 0;}else{$initRow = $ini;}
		
		if($initRow<= 1) 
		{
			$initRow =0;
		}else{
			
			if(($howManyPagesPerSearch * $ini)-$howManyPagesPerSearch>= $total_items) {
				$initRow = $totalPages-$howManyPagesPerSearch;
			}else{
				$initRow = ($howManyPagesPerSearch * $ini)-$howManyPagesPerSearch;
			}
		}
		
		
		return $initRow;
		
		
	}
	
	public function fetch_result($results)
	{
		if ( empty( $results ) )
		{
		
		
		}else{
			
			
			foreach ( $results as $result )
			{
				return $result;			
			
			}
			
		}
		
	}
	
	public function get_order_pending ($id)
	{
		global $wpdb,  $bookingultrapro;
		
		$orders = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'bup_orders WHERE order_key = "'.$id.'"  AND order_status="pending" ' );
		
		if ( empty( $orders ) )
		{
		
		
		}else{			
			
			foreach ( $orders as $order )
			{
				return $order;			
			
			}
			
		}
		
	
	}
	
	public function get_orders_by_status ($status)
	{
		global $wpdb,  $bookingultrapro;
		
		//$sql = 'SELECT count(*) as total FROM ' . $wpdb->prefix . 'bup_orders WHERE order_status="'.$status.'" ';
		
		
		$sql =  'SELECT count(*) as total, ord.*,  usu.*, serv.* , appo.*	 FROM ' . $wpdb->prefix . 'bup_orders ord  ' ;
		$sql .= " RIGHT JOIN ". $wpdb->prefix."bup_bookings appo ON (ord.order_booking_id = appo.booking_id)";				
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";	
		$sql .= " RIGHT JOIN ". $wpdb->prefix."bup_services serv ON (serv.service_id = appo.booking_service_id)";	
			
		$sql .= " WHERE serv.service_id = appo.booking_service_id  AND  ord.order_booking_id = appo.booking_id    ";
		$sql .= " AND ord.order_status = '".$status."'  ";

		$rows = $wpdb->get_results( $sql );	
		
		
		if ( empty( $rows ) )
		{
		
		}else{
			
			foreach ( $rows as $order )
			{
				return $order->total;			
			
			}
		}
				
	}
	
	public function get_order_confirmed ($id)
	{
		global $wpdb,  $bookingultrapro;
		
		$orders = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'bup_orders WHERE order_key = "'.$id.'"  AND order_status="confirmed" ' );
		
		if ( empty( $orders ) )
		{
		
		
		}else{
			
			
			foreach ( $orders as $order )
			{
				return $order;			
			
			}
			
		}
		
	
	}
	
	/*Get Latest*/
	public function get_latest_user ($user_id, $howmany)
	{
		global $wpdb,  $bookingultrapro;
		
		$sql = 'SELECT ord.*, usu.*	 FROM ' . $wpdb->prefix . 'bup_orders ord  ' ;		
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = ord.order_user_id)";		
		$sql .= " WHERE ord.order_id <> 0 AND usu.ID = '".$user_id."' ORDER BY ord.order_id desc  LIMIT $howmany";	
			
		$orders = $wpdb->get_results($sql );
		
		return $orders ;		
	
	}
	
	/**
	 * My Orders 
	 */
	function show_my_latest_orders($howmany, $status=null)
	{
		global $wpdb, $current_user, $bookingultrapro; 
		
			
		
		$currency_symbol =  $xoouserultra->get_option('paid_membership_symbol');
		
		
		$user_id = get_current_user_id();
		 
		
        $drOr = $this->get_latest_user($user_id,30);
		
		//print_r($loop );
				
		
		if (  empty( $drOr) )
		{
			echo '<p>', __( 'You have no orders.', 'bookingup' ), '</p>';
		}
		else
		{
			$n = count( $drOr );
			
			
			?>
			<form action="" method="get">
				<?php wp_nonce_field( 'usersultra-bulk-action_inbox' ); ?>
				<input type="hidden" name="page" value="usersultra_inbox" />
	
				
	
				<table class="widefat fixed" id="table-3" cellspacing="0">
					<thead>
					<tr>
						
                       
						<th class="manage-column" ><?php _e( 'Order #', 'bookingup' ); ?></th>
                        <th class="manage-column"><?php _e( 'Total', 'bookingup' ); ?></th>
						<th class="manage-column"><?php _e( 'Date', 'bookingup' ); ?></th>
						<th class="manage-column" ><?php _e( 'Package', 'bookingup' ); ?></th>
                        <th class="manage-column" ><?php _e( 'Status', 'bookingup' ); ?></th>
					</tr>
					</thead>
					<tbody>
						<?php
							
							foreach ( $drOr as $order){
							$order_id = $order->order_id;
							
							//get package
							
							$package = $xoouserultra->paypal->get_package($order->order_package_id);
							
							
							//print_r($order );
							
							?>
						<tr>
							                         
                            
							<td>#<?php echo $order_id; ?></td>
                            <td><?php echo  $currency_symbol.$order->order_amount?></td>
							<td> <?php echo $order->order_date; ?></td>
							<td><?php echo $package->package_name; ?></td>
                            <td><?php echo $order->order_status; ?></td>
                            
                            
							<?php
	
							}
						?>
					</tbody>
					
				</table>
			</form>
			<?php
	
		}
		?>

	<?php
	}
	
	
	

}
$key = "order";
$this->{$key} = new BookingUltraOrder();