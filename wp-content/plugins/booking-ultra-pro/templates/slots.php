<ul class="bup-time-slots-available-list">	
<?php		
		//get user
		
		$staff_id = $this->get_prefered_staff($b_staff );
		
		$cdiv = 0 ;
		

 while (strtotime($date_from) < strtotime($end_date)) 
		 {
			 $cdiv++;
			 
			 $day_num_of_week = date('N', strtotime($date_from));	
			  
					   
			 ?>
			  <h3><?php echo date('l, j F, Y', strtotime($date_from))?> - <?php echo  $day_num_of_week?></h3>
			  
			  ?>
              
			  <div class="bup-time-slots-divisor" id="bup-time-sl-div-<?php echo $cdiv?>">
			  
			 <?php  //get available slots for this date
			 
			 $time_slots = $this->get_time_slot_public_for_staff($day_num_of_week,  $staff_id, $b_category, $time_format);
			 

 $cdiv_range = 0 ;

 foreach($time_slots as $slot)
			 {
				  $cdiv_range++;				 
				 ?>
				
				<li id="bup-time-slot-hour-range-<?php echo $cdiv?>-<?php echo $cdiv_range?>">
				
				<span class="bup-timeslot-time"><i class="fa fa-clock-o"></i>&nbsp;&nbsp;<?php echo $slot['from'].' &ndash; '.$slot['to']?></span>
				<span class="bup-timeslot-count"><span class="spots-available">1 time slot available</span></span>
				<span class="bup-timeslot-people">
                <button class="new-appt bup-button bup-btn-book-app" bup-data-date="<?php echo date('Y-m-d', strtotime($date_from))?>" bup-data-timeslot="<?php echo $b_category.'-'.$staff_id?>">
                
				<span class="button-timeslot"></span><span class="bup-button-text">
				<?php _e('Book Appointment','booking-ultra-pro')?></span></button>
                 </span>
				
				</li>	
			 
<?php  } //end for each time slots ?>

</div>


<?php 
 //increase date
 $date_from = date ("Y-m-d", strtotime("+1 day", strtotime($date_from)));
 
 
  } 

?>

</ul>