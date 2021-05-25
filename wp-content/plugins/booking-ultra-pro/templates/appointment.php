<?php
global $bookingultrapro, $bupcomplement;
$preset_categories = $bookingultrapro->get_template_label("category_ids",$template_id);
$form_type = $bookingultrapro->get_template_label("booking_form_type",$template_id);

if($preset_categories!=''){ $category_ids = $preset_categories;}

$hide_staff= $bookingultrapro->get_template_label("hide_staff",$template_id);
$hide_service= $bookingultrapro->get_template_label("hide_service",$template_id);
$show_location= $bookingultrapro->get_template_label("show_location",$template_id);

if($show_location==1){ $four_colums_class = 'bup-four-cols-booking';}
$show_cart = $bookingultrapro->get_template_label("show_cart",$template_id);


//echo "date: " . date( $bookingultrapro->get_date_picker_date(), current_time( 'timestamp', 0 ) );
?>

<div class="bup-front-cont">

  <div class="bup-filter-header" id="up-filter-header">
  
  
  <?php if($show_cart==1){?>
  
  
    <div class="bup-book-steps-cont" id="bup-book-steps-cont-cart">
    
    	
        <div class="bup-book-step1 bup-steps-cart">
        		
                <div class="bup-cart-step-line-inactive"></div>
        
        		<div class="bup-cart-step-active bup-move-steps" id="bup-step-rounded-1">1</div>
                <div class="bup-cart-step-text-active"><?php echo $bookingultrapro->get_template_label("step1_label",$template_id);?></div>
        
        </div>
        
        <div class="bup-book-step2 bup-steps-cart">
        
        
            	<div class="bup-cart-step-line-inactive"></div>
        
        		<div class="bup-cart-step-inactive bup-move-steps" id="bup-step-rounded-2">2</div>
                  <div class="bup-cart-step-text-inactive"><?php echo $bookingultrapro->get_template_label("step2_label",$template_id);?></div>
        
        </div>
        
        <div class="bup-book-step3 bup-steps-cart">
        
        	    <div class="bup-cart-step-line-inactive"></div>
        
        		<div class="bup-cart-step-inactive bup-move-steps" id="bup-step-rounded-33">3</div>
                  <div class="bup-cart-step-text-inactive"><?php echo $bookingultrapro->get_template_label("step3cart_label",$template_id);?></div>
        
        </div>
        
         <div class="bup-book-step3 bup-steps-cart">
        
        	    <div class="bup-cart-step-line-inactive"></div>
        
        		<div class="bup-cart-step-inactive bup-move-steps" id="bup-step-rounded-3">4</div>
                  <div class="bup-cart-step-text-inactive"><?php echo $bookingultrapro->get_template_label("step3_label",$template_id);?></div>
        
        </div>
        
        <div class="bup-book-step4 bup-steps-cart">
        
        
        		 <div class="bup-cart-step-line-inactive"></div>
        
        		<div class="bup-cart-step-inactive bup-move-steps" id="bup-step-rounded-4">5</div>
                <div class="bup-cart-step-text-inactive"><?php echo $bookingultrapro->get_template_label("step4_label",$template_id);?></div>  
        
        
        </div>
    
       
    
    </div>
    
     <?php }else{?>
     
         <div class="bup-book-steps-cont" id="bup-book-steps-cont">
    
    	
        <div class="bup-book-step1 bup-steps">
        		
                <div class="bup-cart-step-line-inactive"></div>
        
        		<div class="bup-cart-step-active bup-move-steps" id="bup-step-rounded-1">1</div>
                <div class="bup-cart-step-text-active"><?php echo $bookingultrapro->get_template_label("step1_label",$template_id);?></div>
        
        </div>
        
        <div class="bup-book-step2 bup-steps">
        
        
            	<div class="bup-cart-step-line-inactive"></div>
        
        		<div class="bup-cart-step-inactive bup-move-steps" id="bup-step-rounded-2">2</div>
                  <div class="bup-cart-step-text-inactive"><?php echo $bookingultrapro->get_template_label("step2_label",$template_id);?></div>
        
        </div>
        
        <div class="bup-book-step3 bup-steps">
        
        	    <div class="bup-cart-step-line-inactive"></div>
        
        		<div class="bup-cart-step-inactive bup-move-steps" id="bup-step-rounded-3">3</div>
                  <div class="bup-cart-step-text-inactive"><?php echo $bookingultrapro->get_template_label("step3_label",$template_id);?></div>
        
        </div>
        
        <div class="bup-book-step4 bup-steps">
        
        
        		 <div class="bup-cart-step-line-inactive"></div>
        
        		<div class="bup-cart-step-inactive bup-move-steps" id="bup-step-rounded-4">4</div>
                <div class="bup-cart-step-text-inactive"><?php echo $bookingultrapro->get_template_label("step4_label",$template_id);?></div>  
        
        
        </div>
    
       
    
    </div>
     
     
      <?php }?>
      
      
    
    
    	<div class="bup-book-info-cont" id="bup-book-info-cont-div">
        
        
           <div class="bup-book-info-text" >
           
           
           </div>
           
            <?php if($show_location==1 && isset($bupcomplement) ){?>
            
         		   <div class="bup-book-info-block1 <?php echo $four_colums_class?>" >
                   
                   
                       <label><?php echo $bookingultrapro->get_template_label("select_location_label",$template_id);?></label>
                   
                      <?php echo $bupcomplement->get_all_locations_front_booking();
                     ?>                   
                   
                   </div> 
            
              <?php } ?>
           
           
           <?php if($form_type==1 || $form_type==''){?>
           
           
          	 <?php if($hide_service!=1 && $service_id == '' ){?>
           
                   <div class="bup-book-info-block1 <?php echo $four_colums_class?>" >
                   
                   
                       <label><?php echo $bookingultrapro->get_template_label("select_service_label",$template_id);?></label>
                   
                      <?php echo $bookingultrapro->service->get_categories_drop_down_public($service_id, $staff_id, $category_ids, $template_id);
                     ?>                   
                   
                   </div>    
               
              
              <?php } ?>
               
                              
           <?php } ?>
           
           <div class="bup-book-info-block1 <?php echo $four_colums_class?>" >
            
             <label><?php echo $bookingultrapro->get_template_label("select_date_label",$template_id);?></label>
              <input type="text" class="bupro-datepicker" id="bup-start-date"  value="<?php echo date( $bookingultrapro->get_date_picker_date(), current_time( 'timestamp', 0 ) )?>"/>       
              
                       
           </div>
           
           <?php if($form_type==2){?>
               
               <div class="bup-book-info-block1" >
                
                 <label><?php echo $bookingultrapro->get_template_label("select_date_to_label",$template_id);?></label>
                  <input type="text" class="bupro-datepicker" id="bup-end-date"  value="<?php echo date( $bookingultrapro->get_date_picker_date(), current_time( 'timestamp', 0 ) )?>"/>       
                  
                           
               </div>
           
            <?php } ?>
           
            <?php if($hide_staff!=1 && $staff_id == '' ){?>
                <div class="bup-book-info-block1 <?php echo $four_colums_class?>" id="bup-staff-booking-list" >
                
                 <label><?php echo $bookingultrapro->get_template_label("select_provider_label",$template_id);?> </label> 
                                 
                  <select name="bup-staff" id="bup-staff">                  
                  	<option value="" ><?php _e('Please select provider','booking-ultra-pro')?></option>
                  </select>        
               
               
                </div>    
       	   <?php } ?>
        	
    
        </div>
        
        
        
         </div>
         
          <div class="bup-nav-search-options-bar" id="bup-steps-nav-buttons" > 
          
          <?php $show_cart = $bookingultrapro->get_template_label("show_cart",$template_id);
		  
		  if($show_cart==1) //add to cart and display sucess message
			{
		  ?>
          
          <span class="bupbtncart">  <button id="bup-btn-show-cart" class="bup-button-submit" title="<?php _e('Shopping Cart','booking-ultra-pro')?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <span id="btn-display-cart-label" > <?php _e('Shopping Cart','booking-ultra-pro')?><span></button></span>
          
           <?php }?>
         
            <span class="bupbtnfind"><button id="bup-btn-next-step1" class="bup-button-submit"><?php echo $bookingultrapro->get_template_label("btn_check_availability_button_text",$template_id);?></button></span>
         
         </div>       
        
        
        
         <div class="bup-book-info-text" id="bup-steps-cont-res" >  
         
          <p ><?php echo $bookingultrapro->get_template_label("step1_texts",$template_id);?></p>       
           
         </div>
    
    

</div>

<?php if($staff_id!='' && isset($bupcomplement)){?>
<input type="hidden"  id="bup-staff" name="bup-staff" value="<?php echo $staff_id?>" />
 <?php }?>
 
 
 <?php if($service_id!='' && isset($bupcomplement)){?>
<input type="hidden"  id="bup-category" name="bup-category" value="<?php echo $service_id?>" />
 <?php }?>
 
<input type="hidden"  id="bup-custom-form-id" name="bup-custom-form-id" value="<?php echo $form_id?>" />

<?php if($show_location!=1 && isset($bupcomplement)){?>
<input type="hidden"  id="bup-filter-id" name="bup-filter-id" value="<?php echo $location_id?>" />
<?php }?>

<input type="hidden"  id="redirect_url" name="redirect_url" value="<?php echo $redirect_url?>" />
<input type="hidden"  id="field_legends" name="field_legends" value="<?php echo $field_legends?>" />
<input type="hidden"  id="placeholders" name="placeholders" value="<?php echo $placeholders?>" />
<input type="hidden"  id="template_id" name="template_id" value="<?php echo $template_id?>" />
<input type="hidden"  id="bup_booking_form_type" name="bup_booking_form_type" value="<?php echo $form_type?>" />
<input type="hidden"  id="bup_cart_id" name="bup_cart_id" value="<?php echo $show_cart?>" />

<input type="hidden"  id="bup_user_timezone" name="bup_user_timezone" value="" />


<script type="text/javascript">

<?php 
//when the services list is hidden and a service is pre-set
if($service_id!='' && $auto_display_slots=='no' && isset($bupcomplement)){?>
 
 	bup_load_staff_by_service( "<?php echo $service_id?>" );
 
<?php }?>	
						 
 <?php if(isset($_GET['bup_order_key']) && $_GET['bup_order_key']!=''){ //load step 4, order completed?>
			  
	  bup_load_step_4("<?php echo $_GET['bup_payment_method']?>");			  
			  
 <?php }?>
			  
<?php 
//when a service, staff and auto display is preset
if($service_id!='' && $staff_id!='' && $auto_display_slots=='yes'){?>
 
 	bup_auto_display_slots( "<?php echo $service_id?>",  "<?php echo $staff_id?>" );
 
<?php }?>	


   <?php if( $bookingultrapro->get_option('allow_timezone') =='1' && isset($bupcomplement)){  ?>	
   
  	  var currentTime = new Date(),
      hours = currentTime.getHours(),
      minutes = currentTime.getMinutes(),
	  offset_t = currentTime.getTimezoneOffset();
	   
	  var visitortimezone = -currentTime.getTimezoneOffset()/60;
	  
	  jQuery("#bup_user_timezone").val(visitortimezone);
   
   <?php }?>	  
		
	</script>
 