<?php
class BookingUltraBreak
{
	
	
	function __construct() 
	{
				
		$this->ini_module();
		
		add_action( 'wp_ajax_bup_get_break_add',  array( &$this, 'get_break_add_frm' ));
		add_action( 'wp_ajax_bup_break_add_confirm',  array( &$this, 'break_add_confirm' ));
		add_action( 'wp_ajax_bup_get_current_staff_breaks',  array( &$this, 'get_current_staff_breaks' ));
		add_action( 'wp_ajax_bup_delete_break',  array( &$this, 'delete_break' ));	
		
		
		

	}
	
	public function ini_module()
	{
		global $wpdb;	
					
		     // Create table for breaks
			$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'bup_staff_availability_breaks (
				  `break_id` int(11) NOT NULL AUTO_INCREMENT,
				  `break_staff_id` int(11) NOT NULL,
				  `break_staff_day` int(11) NOT NULL,				 
				  `break_time_from` time NOT NULL,
				  `break_time_to` time NOT NULL,
				  PRIMARY KEY (`break_id`)
			) ENGINE=MyISAM COLLATE utf8_general_ci;';

		   $wpdb->query( $query );
		
		   
		
	}
	
	
	
	public function break_add_confirm()
	{
		global  $bookingultrapro , $wpdb;
		
		$staff_id = $_POST['staff_id'];
		$day_id = $_POST['day_id'];
		
		$from = $_POST['from'].':00';
		$to = $_POST['to'].':00';
				
		$html = '';		

		$sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bup_staff_availability_breaks  WHERE break_staff_id=%d AND break_staff_day = %d AND break_time_from=%s AND break_time_to=%s;',array($staff_id,$day_id ,$from ,$to));
		
		$results = $wpdb->get_results($sql);
		
		if ( empty( $results ))
		{				
			$new_record = array('break_id' => NULL,	
								'break_staff_id' => $staff_id,
								'break_staff_day' => $day_id,
								'break_time_from' => $from,
								'break_time_to'   => $to);								
									
			$wpdb->insert( $wpdb->prefix . 'bup_staff_availability_breaks', $new_record, array( '%d', '%d', '%d', '%s', '%s'));
			
			
			$html = __('Done!', 'booking-ultra-pro')	;
			
		}else{
			
			$html = __('ERROR. Duplicated Break!', 'booking-ultra-pro')	;
			
		
		}
		
		echo $html;
		die();
		
		
	
	}
	
	
	public function get_break_add_frm($staff_id = null, $day_id = null )
	{
		global  $bookingultrapro;
		
		$staff_id = $_POST['staff_id'];
		
		
		$day_id = $_POST['day_id'];
		
		
		$html = '<div class="bup-add-break-cont">';
		$html .='<input type="hidden" value="'.$day_id.'" id="bup_day_id">';
		$html .=''.$this->get_breaks_drop_downs($day_id,'bup-break-from-'.$day_id ,'bup_select_start', $staff_id). '<span> '.__('to', 'booking-ultra-pro').' </span>' .$this->get_breaks_drop_downs($day_id,'bup-break-to-'.$day_id ,'bup_select_end', $staff_id).'';
		
		$html .= '<button name="bup-btn-add-break-confirm" id="bup-btn-add-break-confirm" class="bup-button-submit-breaks" day-id="'.$day_id.'">'.__('Add','booking-ultra-pro').'	</button>';
		$html .= '<span id="bup-break-message-add-'.$day_id.'"></span>';
		$html .= '</div>';
		
		echo $html;
		die();
	
	}
	
	//returns the business hours drop down
	public function get_breaks_drop_downs($day, $cbox_id, $select_start_to_class, $staff_id)
	{
		global  $bookingultrapro;
		
		$hours = 24; //amount of hours working in day			
		$min_minutes=15	;
				
		$hours = (60/$min_minutes) *$hours;		
		$min_minutes=$min_minutes*60;		
		
		
		$html .= '<select id="'.$cbox_id.'" name="'.$cbox_id.'" class="'.$select_start_to_class.'">';
		//$html .= '<option value="" '.$selected.'>'.__('OFF','booking-ultra-pro').'</option>';
		
		//get default value for this week's day
		
		if($select_start_to_class=='bup_select_start')
		{
			$from_to_value = 'from';		
			
		}else{
				
			$from_to_value = 'to';			
			
		}
			
		//check selected value
		$selected_value = $bookingultrapro->service->get_business_hour_option($day, $from_to_value, $staff_id);		
		
		for($i = 0; $i < $hours ; $i++)
		{ 		
			$minutes_to_add = $min_minutes * $i; // add 30 - 60 - 90 etc.
			$timeslot = date('H:i:s', strtotime($row['hours_start'])+$minutes_to_add);	
			
			$selected = '';				
			if($selected_value==date('H:i', strtotime($timeslot)))
			{
				$selected = 'selected="selected"';
			}
			
			$html .= '<option value="'.date('H:i', strtotime($timeslot)).'" '.$selected.'  >'.date('h:i A', strtotime($timeslot)).'</option>';
		}
		
		
		$html .='</select>';
		
		return $html;
	
	}
	
	public function get_staff_breaks($staff_id)
	{
		global $wpdb, $bookingultrapro;
		
		$html='';
		
				
		
		$html .= '<ul class="bup-details-staff-sections">';
		
		$html .='<li class="left_widget_customizer_li">';
			
		$html .='<div class="bup-break-details-header" widget-id="1"><h3> '.__('Monday','booking-ultra-pro').'<h3>';
				
		$html .='<span class="bup-breaks-add" id="bup-widgets-icon-close-open-id-1"  day-id="1" >'.__('Add Break','booking-ultra-pro').'</span>';
		
		$html .= '</div>';
		
		$html .='<div id="bup-break-add-break-1" class="bup-add-new-break"></div>';	
			
		$html .='<div id="bup-break-adm-cont-id-1" class="bup-breaks-details">';
		$html .= $this->get_current_staff_breaks($staff_id , 1 );
		$html .= '</div>';
		
		$html .='</li>';
		
		$html .='<li class="left_widget_customizer_li">';
			
		$html .='<div class="bup-break-details-header" widget-id="1"><h3> '.__('Tuesday','booking-ultra-pro').'<h3>';
				
		$html .='<span class="bup-breaks-add"  day-id="2" >'.__('Add Break','booking-ultra-pro').'</span>';
		
		$html .= '</div>';
		
		
		$html .='<div id="bup-break-add-break-2" class="bup-add-new-break"></div>';	
		$html .='<div id="bup-break-adm-cont-id-2" class="bup-breaks-details">';
		$html .= $this->get_current_staff_breaks($staff_id , 2 );
		$html .= '</div>';
		$html .='</li>';
		
		$html .='<li class="left_widget_customizer_li">';
			
		$html .='<div class="bup-break-details-header" widget-id="1"><h3> '.__('Wednesday','booking-ultra-pro').'<h3>';
				
		$html .='<span class="bup-breaks-add"  day-id="3" >'.__('Add Break','booking-ultra-pro').'</span>';
		
		$html .= '</div>';
		
		$html .='<div id="bup-break-add-break-3" class="bup-add-new-break"></div>';			
		$html .='<div id="bup-break-adm-cont-id-3" class="bup-breaks-details">';
		$html .= $this->get_current_staff_breaks($staff_id , 3 );
		$html .= '</div>';
		$html .='</li>';
		
		$html .='<li class="left_widget_customizer_li">';
			
		$html .='<div class="bup-break-details-header" widget-id="1"><h3> '.__('Thursday ','booking-ultra-pro').'<h3>';
				
		$html .='<span class="bup-breaks-add"  day-id="4" >'.__('Add Break','booking-ultra-pro').'</span>';
		
		$html .= '</div>';
		
		$html .='<div id="bup-break-add-break-4" class="bup-add-new-break"></div>';			
		$html .='<div id="bup-break-adm-cont-id-4" class="bup-breaks-details">';
		$html .= $this->get_current_staff_breaks($staff_id , 4 );
		$html .= '</div>';
		$html .='</li>';
		
		$html .='<li class="left_widget_customizer_li">';
			
		$html .='<div class="bup-break-details-header" widget-id="1"><h3> '.__('Friday','booking-ultra-pro').'<h3>';
				
		$html .='<span class="bup-breaks-add" day-id="5" >'.__('Add Break','booking-ultra-pro').'</span>';
		
		$html .= '</div>';
		
		$html .='<div id="bup-break-add-break-5" class="bup-add-new-break"></div>';			
		$html .='<div id="bup-break-adm-cont-id-5" class="bup-breaks-details">';
		$html .= $this->get_current_staff_breaks($staff_id , 5 );
		$html .= '</div>';
		$html .='</li>';
		
		
		$html .='<li class="left_widget_customizer_li">';
			
		$html .='<div class="bup-break-details-header" widget-id="1"><h3> '.__('Saturday','booking-ultra-pro').'<h3>';
				
		$html .='<span class="bup-breaks-add"  day-id="6" >'.__('Add Break','booking-ultra-pro').'</span>';
		
		$html .= '</div>';
		
		$html .='<div id="bup-break-add-break-6" class="bup-add-new-break"></div>';			
		$html .='<div id="bup-break-adm-cont-id-6" class="bup-breaks-details">';
		$html .= $this->get_current_staff_breaks($staff_id , 6 );
		$html .= '</div>';
		$html .='</li>';
		
		$html .='<li class="left_widget_customizer_li">';
			
		$html .='<div class="bup-break-details-header" widget-id="1"><h3> '.__('Sunday','booking-ultra-pro').'<h3>';
				
		$html .='<span class="bup-breaks-add" day-id="7" >'.__('Add Break','booking-ultra-pro').'</span>';
		
		$html .= '</div>';
		
		$html .='<div id="bup-break-add-break-7" class="bup-add-new-break"></div>';			
		$html .='<div id="bup-break-adm-cont-id-7" class="bup-breaks-details">';
		$html .= $this->get_current_staff_breaks($staff_id , 7 );
		$html .= '</div>';
		$html .='</li>';
		$html .='</ul>';
		return $html;
		
	
	
	}
	
	
	public function delete_break()
	{
		global  $bookingultrapro , $wpdb;
		
		$staff_id = $_POST['staff_id'];
		$break_id = $_POST['break_id'];
		
		$sql = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'bup_staff_availability_breaks  WHERE break_staff_id=%d AND break_id = %d ;',array($staff_id,$break_id));
		
		$results = $wpdb->query($sql);
		die();
	
	}	
	
	public function get_current_staff_breaks($staff_id = null, $day_id = null)
	{
		global  $bookingultrapro , $wpdb;
		
		$action = $_POST['action'];
		
		$time_format = $bookingultrapro->service->get_time_format();
		
		if($action=='bup_get_current_staff_breaks')
		{	
			$staff_id = $_POST['staff_id'];
			$day_id = $_POST['day_id'];
		
		}		
						
		$html = '';		

		$sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bup_staff_availability_breaks  WHERE break_staff_id=%d AND break_staff_day = %d ;',array($staff_id,$day_id));
		
		$results = $wpdb->get_results($sql);
		
		if ( !empty( $results ))
		{
			$html .= '<ul>';	
			
			foreach ( $results as $row )
			{
				
				$html .= '<li><i class="fa fa-clock-o bup-clock-remove"></i>'.date($time_format,strtotime($row->break_time_from)).' - '.date($time_format,strtotime($row->break_time_to));
				
				$html .= '<span class="bup-breaks-remove" id="bup-break-add-'.$day_id.'"><a href="#" class="ubp-break-delete-btn" title="'.__("Delete Break", 'booking-ultra-pro').'" break-id="'.$row->break_id.'" day-id="'.$day_id.'"><i class="fa fa-remove"></i></a></span>';
				$html .= '</li>';
				
				
			}
			$html .= '</ul>';			
						
		}else{
			
			$html = __("There are no breaks for this day.", 'booking-ultra-pro')	;			
		
		}
		
		
		
		if($action=='bup_get_current_staff_breaks')
		{
			echo $html;
			die();
		}else{
			
			return $html;
			
			
		}
		
		
	
	}
	
	
	
	

	
}
$key = "breaks";
$this->{$key} = new BookingUltraBreak();
?>