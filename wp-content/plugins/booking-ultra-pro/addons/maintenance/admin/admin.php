<?php
class BookingUltraMaintenance {

	var $options;

	function __construct() {
		
		
		$this->ini_module();
	
		/* Plugin slug and version */
		$this->slug = 'bookingultra';
		$this->subslug = 'bup-maintenance';
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$this->plugin_data = get_plugin_data( bup_maintenance_path . 'index.php', false, false);
		$this->version = $this->plugin_data['Version'];
		
		/* Priority actions */
		add_action('admin_menu', array(&$this, 'add_menu'), 11);
		add_action('admin_enqueue_scripts', array(&$this, 'add_styles'), 13);
		add_action('admin_head', array(&$this, 'admin_head'), 12 );
		add_action('admin_init', array(&$this, 'admin_init'), 12);
		
		add_action( 'wp_ajax_bup_clean_appo_without_service', array( &$this, 'bup_clean_appo_without_service' ));
		add_action( 'wp_ajax_bup_clean_appo_without_staff', array( &$this, 'bup_clean_appo_without_staff' ));
		

	}
	
	
	
	public function bup_set_option($option, $newvalue)
	{
		$settings = get_option('bup_options');
		$settings[$option] = $newvalue;
		update_option('bup_options', $settings);
	}
	
	function get_without_service(){
		
		global $wpdb, $bookingultrapro;		
		
		$sql =  'SELECT  appo.* FROM ' . $wpdb->prefix . 'bup_bookings appo  ' ;				
				$sql .= " WHERE NOT EXISTS (".'SELECT  serv.* FROM ' . $wpdb->prefix . 'bup_services serv'." 
				            WHERE appo.booking_service_id  = serv.service_id) ";
							
							//echo $sql;
			
				 
		$res = $wpdb->get_results($sql );		
		return $res ;	
	
	
	}
	
	function get_without_user(){
		
		global $wpdb, $bookingultrapro;		
		
		$sql =  'SELECT  appo.* FROM ' . $wpdb->prefix . 'bup_bookings appo  ' ;				
				$sql .= " WHERE NOT EXISTS (".'SELECT  usu.* FROM ' . $wpdb->users . ' usu'." 
				            WHERE appo.booking_staff_id  = usu.ID) ";
							
						//	echo $sql;
			
				 
		$res = $wpdb->get_results($sql );		
		return $res ;	
	
	
	}
	
	
	function bup_clean_appo_without_staff(){
		
		global $wpdb, $bookingultrapro;		
		
		$sql =  'DELETE FROM ' . $wpdb->prefix . 'bup_bookings   ' ;				
				$sql .= " WHERE NOT EXISTS (".'SELECT  NULL FROM ' .  $wpdb->users. ' usu'." 
				            WHERE booking_staff_id  = usu.ID) ";
							
							//echo $sql;
			
				 
		$res = $wpdb->get_results($sql );		
		return $res ;	
	
	
	}
	
	function bup_clean_appo_without_service(){
		
		global $wpdb, $bookingultrapro;		
		
		$sql =  'DELETE FROM ' . $wpdb->prefix . 'bup_bookings   ' ;				
				$sql .= " WHERE NOT EXISTS (".'SELECT  NULL FROM ' . $wpdb->prefix . 'bup_services serv'." 
				            WHERE booking_service_id  = serv.service_id) ";
							
							//echo $sql;
			
				 
		$res = $wpdb->get_results($sql );		
		return $res ;	
	
	
	}
	
	
	
	
	
	public function ini_module()
	{
		global $wpdb;		   		  		   
		
	}
	
	function admin_init() 
	{
	
		$this->tabs = array(
			'manage' => __('Maintenance','booking-ultra-pro')
			
		);
		$this->default_tab = 'manage';		
		
	}		
	
	function admin_head(){

	}

	function add_styles(){
	
		wp_register_script( 'bup_maintenance_js', bup_maintenance_url . 'admin/scripts/admin.js', array( 
			'jquery'
		) );
		wp_enqueue_script( 'bup_maintenance_js' );
	
		wp_register_style('bup_maintenance_css', bup_maintenance_url . 'admin/css/admin.css');
		wp_enqueue_style('bup_maintenance_css');
		
	}
	
	function add_menu()
	{
		
		$appointments = $this->get_without_service();
		
		$pending_count = count($appointments);
		
		if ($pending_count > 0)
		{
			$menu_label = sprintf( __( 'Maintenance %s','bookingup' ), "<span class='update-plugins count-$pending_count' title='$pending_title'><span class='update-count'>" . number_format_i18n($pending_count) . "</span></span>" );
			
		} else {
			
			$menu_label = __('Maintenance','booking-ultra-pro');
		}
		
		
	
		$pending_title = esc_attr( sprintf(__( '%d  pending bookings','booking-ultra-pro'), $pending_count ) );
		
		add_submenu_page( 'bookingultra', __('Maintenance','booking-ultra-pro'), $menu_label, 'manage_options', 'bup-maintenance', array(&$this, 'admin_page') );
		
	
		
	}

	function admin_tabs( $current = null ) {
			$tabs = $this->tabs;
			$links = array();
			if ( isset ( $_GET['tab'] ) ) {
				$current = $_GET['tab'];
			} else {
				$current = $this->default_tab;
			}
			foreach( $tabs as $tab => $name ) :
				if ( $tab == $current ) :
					$links[] = "<a class='nav-tab nav-tab-active' href='?page=".$this->subslug."&tab=$tab'>$name</a>";
				else :
					$links[] = "<a class='nav-tab' href='?page=".$this->subslug."&tab=$tab'>$name</a>";
				endif;
			endforeach;
			foreach ( $links as $link )
				echo $link;
	}

	function get_tab_content() {
		$screen = get_current_screen();
		if( strstr($screen->id, $this->subslug ) ) {
			if ( isset ( $_GET['tab'] ) ) {
				$tab = $_GET['tab'];
			} else {
				$tab = $this->default_tab;
			}
			require_once bup_maintenance_path.'admin/panels/'.$tab.'.php';
		}
	}
	
	
	
	function admin_page() {
		
		
		global $bookingultrapro, $bupcomplement;
		
		
		if (isset($_POST['update_settings']) &&  $_POST['reset_email_template']=='' && !isset($_POST['update_bup_slugs'])) {
            $bookingultrapro->buupadmin->update_settings();
        }
		
		
		
				
	?>
	
		<div class="wrap <?php echo $this->slug; ?>-admin">
        
           <h2>BOOKING ULTRA PRO - <?php _e('Maintenance','booking-ultra-pro'); ?></h2>
           
           <div id="icon-users" class="icon32"></div>
			
						
			<h2 class="nav-tab-wrapper"><?php $this->admin_tabs(); ?></h2>

			<div class="<?php echo $this->slug; ?>-admin-contain">
				
				<?php $this->get_tab_content(); ?>
				
				<div class="clear"></div>
				
			</div>
			
		</div>

	<?php }

}
global $bup_maintenance;
$bup_maintenance = new BookingUltraMaintenance();