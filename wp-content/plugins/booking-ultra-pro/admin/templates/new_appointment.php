<?php
global $bookingultrapro;
?>
<div class="bup-adm-new-appointment">	

    <div class="bup-adm-frm-blocks" >               
                   
        <div class="field-header"><?php _e('Select Service','booking-ultra-pro')?></div>                   
        <?php echo $bookingultrapro->service->get_categories_drop_down_public();?>                            
               
    </div>
   
    <div class="bup-adm-frm-blocks" >
            
        <div class="field-header"><?php _e('On or After','booking-ultra-pro')?> </div> 
        <input type="text" class="bupro-datepicker" id="bup-start-date" value="<?php echo date( $bookingultrapro->get_date_picker_date(), current_time( 'timestamp', 0 ) )?>" />         
           
    </div>
        
     <div class="bup-adm-frm-blocks" id="bup-staff-booking-list" >
            
              <div class="field-header"><?php _e('With','booking-ultra-pro')?>  </div> 
           
              <?php echo $bookingultrapro->userpanel->get_staff_list_front();?>          
     </div>  
     
      
           


</div>

<div class="bup-adm-new-appointment">

			<div class="field-header"><?php _e('Client','booking-ultra-pro')?>  </div> 
           
              <input type="text" class="bupro-client-selector" id="bupclientsel" name="bupclientsel" placeholder="<?php _e('Input Name or Email Address','booking-ultra-pro')?>" />
              
              <span class="bup-add-client-m"><a href="#" id="bup-btn-client-new-admin" title="Add New Client"><i class="fa fa-plus"></i></a></span> 

</div>

 <div class="bup-adm-check-av-button"  > 
         
       <button id="bup-adm-check-avail-btn" class="bup-button-submit"><?php _e('Check Availability','booking-ultra-pro')?></button>
         
</div>   

<div class="bup-adm-new-appointment">
<input type="hidden" id="bup_time_slot" value="">
<input type="hidden" id="bup_booking_date" value="">
<input type="hidden" id="bup_client_id" value="">
<input type="hidden" id="bup_service_staff" value="">

<h3><?php _e('Availability','booking-ultra-pro')?> </h3>
    
    <div class="bup-adm-availa-box" id="bup-steps-cont-res" >         
                
               
               
    </div>


</div>

 <div class="bup-adm-check-av-button"  > 
         
      <input type="checkbox" id="bup_notify_client" checked="checked" name="bup_notify_client" value="1"> <?php _e('Send Notification To Client','booking-ultra-pro')?>
         
</div>