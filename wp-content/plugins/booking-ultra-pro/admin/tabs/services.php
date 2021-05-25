<?php
global $bookingultrapro;
		
?>

        
        <div class="bup-sect bup-welcome-panel"> 
        
                
           <div class="bup-services">
           
           		<div class="bup-categories" id="bup-categories-list">
                
                 
                 
                                 
                </div>
                
                <div class="bup-services" id="bup-services-list">
                
                 
                
                
                </div>
           
               
           
           </div>
       
       
         
        
        </div>
        
        <div id="bup-service-editor-box"></div>
        <div id="bup-service-variable-pricing-box"  title="<?php echo __('Set Flexible Pricing','booking-ultra-pro')?>"></div>
        <div id="bup-service-add-category-box" title="<?php echo __('Add Category','booking-ultra-pro')?>"></div>
        
        
         <script type="text/javascript">
		 
			 var err_message_category_name ="<?php _e('Please input a name.','booking-ultra-pro'); ?>";  
		   		 
			 bup_load_categories();
			 bup_load_services();
		 </script>
<div id="bup-spinner" class="bup-spinner" style="display:">
            <span> <img src="<?php echo bookingup_url?>admin/images/loaderB16.gif" width="16" height="16" /></span>&nbsp; <?php echo __('Please wait ...','booking-ultra-pro')?>
	</div>