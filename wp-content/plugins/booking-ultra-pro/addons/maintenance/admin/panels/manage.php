<?php
global $bookingultrapro, $bup_maintenance;

$currency_symbol =  $bookingultrapro->get_option('paid_membership_symbol');
$date_format =  $bookingultrapro->get_int_date_format();
$time_format =  $bookingultrapro->service->get_time_format();


?>
<div class="bup-ultra-sect ">

 <h3><?php _e('Appointments without a service assigned','booking-ultra-pro'); ?></h3>
        
              <p><?php _e('This feature will help you to delete appointments without a service assigned. This happens when you delete a service that’s used by some appointment or client. Although, the plugin control this from happening, sometimes the service is deleted directly from the database causing inconsistences. ','booking-ultra-pro'); ?></p>
        <?php 
		
		$appointments = $bup_maintenance->get_without_service();
		
		if ( !empty( $appointments ) )
		{
			
			$html = '<div class="bup-ultra-error">'. __("Some appointment(s) are linked to a non-existent service.", 'booking-ultra-pro').'</div>';
			
			echo $html ;
		 ?>
				
				           <table width="100%" class="wp-list-table widefat fixed posts table-generic">
            <thead>
                <tr>
                    <th width="4%"><?php _e('#', 'booking-ultra-pro'); ?></th>
                    
                     <th width="13%"><?php _e('Date', 'booking-ultra-pro'); ?></th>                     
                                        
                    <th width="23%"><?php _e('Client', 'booking-ultra-pro'); ?></th>
                    <th width="23%"><?php _e('Phone Number', 'booking-ultra-pro'); ?></th>
                    <th width="23%"><?php _e('Provider', 'booking-ultra-pro'); ?></th>
                     <th width="18%"><?php _e('Service', 'booking-ultra-pro'); ?></th>
                    <th width="16%"><?php _e('At', 'booking-ultra-pro'); ?></th>
                    
                     
                     <th width="9%"><?php _e('Status', 'booking-ultra-pro'); ?></th>
                   
                </tr>
            </thead>
            
            <tbody>
            
            <?php 
			
			foreach ( $appointments as $appointment )
			{
				
				$date_from=  date("Y-m-d", strtotime($appointment->booking_time_from));
				$booking_time = date($time_format, strtotime($appointment->booking_time_from ))	.' - '.date($time_format, strtotime($appointment->booking_time_to ));
				 
				$staff = $bookingultrapro->userpanel->get_staff_member($appointment->booking_staff_id);
				
				$client_id = $appointment->booking_user_id;				
				$client = get_user_by( 'id', $client_id );
				
				//get phone			
				$phone = $bookingultrapro->appointment->get_booking_meta($appointment->booking_id, 'full_number');
			
			?>
              

                <tr>
                    <td><?php echo $appointment->booking_id; ?></td>
                   
                     <td><?php echo  date($date_format, strtotime($date_from)); ?>      </td> 
                     
                                          
                    <td><?php echo $client->display_name; ?> (<?php echo $client->user_email; ?>)</td>
                    <td><?php echo $phone; ?></td>
                    <td><?php echo $staff->display_name; ?></td>
                    <td>N/A </td>
                    <td><?php echo  $booking_time; ?></td>                  
                     
                      <td><?php echo $bookingultrapro->appointment->get_status_legend($appointment->booking_status); ?></td>
                </tr>
                
                
                <?php
				
			}	 ?>
			
			
			</tbody>
        </table>
        
        <p class="submit">
	<input type="button" name="submit" id="bup_clean_app_without_service" class="button button-primary" value="<?php _e('Fix Inconsistency','booking-ultra-pro'); ?>"  />
	
</p>

        
					
	<?php	}else{
			?>
            
			 <p><?php _e("Don't worry. Everything looks great!. Al the appointments are linked to a service.",'booking-ultra-pro'); ?></p>
			
			
		<?php }
		?>
 

             

</div>





<div class="bup-ultra-sect ">

 <h3><?php _e('Appointments without a Staff member assigned','booking-ultra-pro'); ?></h3>
        
              <p><?php _e('Here you will see a list of appointments that are assigned to staff members that were deleted manually or by using the WP Users Link. If this happens you can use this feature to fix them.','booking-ultra-pro'); ?></p>
        
     
    <?php 
		
		$appointments = $bup_maintenance->get_without_user();
		
		if ( !empty( $appointments ) )
		{
			
			$html = '<div class="bup-ultra-error">'. __("Some appointment(s) are linked to a non-existent service.", 'booking-ultra-pro').'</div>';
			
			echo $html ;
		 ?>
				
				           <table width="100%" class="wp-list-table widefat fixed posts table-generic">
            <thead>
                <tr>
                    <th width="4%"><?php _e('#', 'booking-ultra-pro'); ?></th>
                    
                     <th width="13%"><?php _e('Date', 'booking-ultra-pro'); ?></th>                     
                                        
                    <th width="23%"><?php _e('Client', 'booking-ultra-pro'); ?></th>
                    <th width="23%"><?php _e('Phone Number', 'booking-ultra-pro'); ?></th>
                    <th width="23%"><?php _e('Provider', 'booking-ultra-pro'); ?></th>
                   
                    <th width="16%"><?php _e('At', 'booking-ultra-pro'); ?></th>
                    
                     
                     <th width="9%"><?php _e('Status', 'booking-ultra-pro'); ?></th>
                   
                </tr>
            </thead>
            
            <tbody>
            
            <?php 
			
			foreach ( $appointments as $appointment )
			{
				
				$date_from=  date("Y-m-d", strtotime($appointment->booking_time_from));
				$booking_time = date($time_format, strtotime($appointment->booking_time_from ))	.' - '.date($time_format, strtotime($appointment->booking_time_to ));
				 
				$staff = $bookingultrapro->userpanel->get_staff_member($appointment->booking_staff_id);
				
				$client_id = $appointment->booking_user_id;				
				$client = get_user_by( 'id', $client_id );
				
				//get phone			
				$phone = $bookingultrapro->appointment->get_booking_meta($appointment->booking_id, 'full_number');
			
			?>
              

                <tr>
                    <td><?php echo $appointment->booking_id; ?></td>
                   
                     <td><?php echo  date($date_format, strtotime($date_from)); ?>      </td> 
                     
                                          
                    <td><?php echo $client->display_name; ?> (<?php echo $client->user_email; ?>)</td>
                    <td><?php echo $phone; ?></td>
                    <td>N/A</td>
                   
                    <td><?php echo  $booking_time; ?></td>                  
                     
                      <td><?php echo $bookingultrapro->appointment->get_status_legend($appointment->booking_status); ?></td>
                </tr>
                
                
                <?php
				
			}	 ?>
			
			
			</tbody>
        </table>
        
        <p class="submit">
	<input type="button" name="submit" id="bup_clean_app_without_staff" class="button button-primary" value="<?php _e('Fix Inconsistency','booking-ultra-pro'); ?>"  />
	
</p>

        
					
	<?php	}else{
			?>
            
			 <p><?php _e("Don't worry. Everything looks great!. Al the appointments are linked to an existen Staff provider.",'booking-ultra-pro'); ?></p>
			
			
		<?php }
		?>
 


             

</div>

<p class="submit">
	<input type="button" name="submit_d" id="submit_d" class="button button-primary" value="<?php _e('Save Changes','booking-ultra-pro'); ?>"  />
	
</p>
