<?php
global $bookingultrapro, $bupultimate;

//

$va = get_option('bup_c_key');

///echo "licence ".$va;
$domain = $_SERVER['SERVER_NAME'];
	
?>


 <div class="bup-sect bup-welcome-panel ">
 
 <?php if($va=='' && isset($bupultimate)){ //user is running either professional or utlimate?>
 
  <h3><?php _e('Recommendation!','booking-ultra-pro'); ?></h3>
   <p><?php _e("You're running either Professional or Ultimate version which doesn't require a serial number to each one of your websites. However, if you don't create a serial number for this domain :",'booking-ultra-pro'); ?><strong> <?php echo $domain ; ?></strong>, <?php _e(" you won't be able to update the plugin automatically through the WP Update Section. So.. we highly recommend you creating a serial number for your domain.",'booking-ultra-pro'); ?></p>

  <?php }?>
  
  
  <?php if($va!='' && isset($bupultimate)){ //user is running a validated copy?>
  
  <h3><?php _e('Congratulations!','booking-ultra-pro'); ?></h3>
   <p><?php _e("Your copy has been validated. You should be able to update the plugin through your WP Update sections. Also, you should start receiving an notice every time the plugin is updated.",'booking-ultra-pro'); ?></p>

   <?php }else{?>  
   
   		
        <?php if($va=='' && isset($bupcomplement)){ //user is running either professional or utlimate?>    
   
       
            <h3><?php _e('Validate your copy','booking-ultra-pro'); ?></h3>
            <p><?php _e("Please fill out the form below with the serial number generated when you registered your domain through your account at BookingUltraPro.com",'booking-ultra-pro'); ?>. <a href="http://doc.bookingultrapro.com/installing-booking-ultra-pro/" target="_blank"><?php _e('Click here to create your serial number','booking-ultra-pro'); ?></a></p> 
            
            <p> <?php _e('INPUT YOUR SERIAL KEY','booking-ultra-pro'); ?></p>
             <p><input type="text" name="p_serial" id="p_serial" style="width:200px" /></p>
            
            
            <p class="submit">
        <input type="submit" name="submit" id="bupadmin-btn-validate-copy" class="button button-primary " value="<?php _e('CLICK HERE TO VALIDATE YOUR COPY','booking-ultra-pro'); ?>"  /> &nbsp; <span id="loading-animation">  <img src="<?php echo bookingup_url?>admin/images/loaderB16.gif" width="16" height="16" /> &nbsp; <?php _e('Please wait ...','booking-ultra-pro'); ?> </span>
        
           </p>
       
       <?php }?> 
       
   <?php }?> 
       
       <p id='bup-validation-results'>
       
       </p>
                     
       
    
</div>  

