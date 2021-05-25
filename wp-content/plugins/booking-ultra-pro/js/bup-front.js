if(typeof $ == 'undefined'){
	var $ = jQuery;
}
(function($) {
    jQuery(document).ready(function () { 
	
	   "use strict";
	   
	 //  alert(bup_pro_front.country_detection);
	  
	   if(bup_pro_front.country_detection != 'no'  ){
		   
	   
		  jQuery("#reg_telephone").intlTelInput({
			  // allowDropdown: false,
			  // autoHideDialCode: false,
			  // autoPlaceholder: "off",
			  // dropdownContainer: "body",
			  // excludeCountries: ["us"],
			  // formatOnDisplay: false,
			  geoIpLookup: function(callback) {
				 jQuery.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
				   var countryCode = (resp && resp.country) ? resp.country : "";
				  callback(countryCode);
				});
			   },
			  // hiddenInput: "full_number",
				initialCountry: "auto",
			  // nationalMode: false,
			  // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
			  // placeholderNumberType: "MOBILE",
			  // preferredCountries: ['cn', 'jp'],
			  // separateDialCode: true,
			  utilsScript: "../js/int-phone-code/js/utils.js"
			});
		
	   }else{
		   
		   // alert('NO Active');
		   
		   }
	   
	   // Adding jQuery Datepicker
		jQuery(function() {
			
			jQuery( ".bupro-datepicker" ).datepicker({ 
				showOtherMonths: true, 
				dateFormat: bup_pro_front.bb_date_picker_format, 
				closeText: BUPDatePicker.closeText,
				currentText: BUPDatePicker.currentText,
				monthNames: BUPDatePicker.monthNames,
				monthNamesShort: BUPDatePicker.monthNamesShort,
				dayNames: BUPDatePicker.dayNames,
				dayNamesShort: BUPDatePicker.dayNamesShort,
				dayNamesMin: BUPDatePicker.dayNamesMin,
				firstDay: BUPDatePicker.firstDay,
				isRTL: BUPDatePicker.isRTL,
				 minDate: 0
			 });
		
			jQuery("#ui-datepicker-div").wrap('<div class="ui-datepicker-wrapper" />');
		});
		
		
	
	jQuery(document).on("click", ".bup_payment_options", function(e) {
		
		
		var payment_method =  jQuery(this).attr("data-method");
		
		if(payment_method=='stripe')
		{
			$(".bup-profile-field-cc").slideDown();
			
			
		}else{
			
			$(".bup-profile-field-cc").slideUp();
						
		}			
			
		
				
       });
	   
	 
	 
	//this loads step 2	
	jQuery(document).on("click", ".bup-btn-delete-cart-item", function(e) {
			
						
			var cart_item=   jQuery(this).attr("item-cart-id");	
								
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_delete_cart_item", "cart_item": cart_item},
					
					success: function(data){					
																	
					
						bup_reload_cart();	
										    
						

						}
				});			
			
			 
    		e.preventDefault();		 
				
        });
		
	jQuery(document).on("click", "#bup-btn-clean-cart", function(e) {
			
						
			var cart_item=   jQuery(this).attr("item-cart-id");	
								
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_clear_cart", "cart_item": cart_item},
					
					success: function(data){					
																	
					
						bup_reload_cart();	
										    
						

						}
				});			
			
			 
    		e.preventDefault();		 
				
        });
		
    
		
	//checkout page with form
	jQuery(document).on("click", "#bup-btn-checkout-cart", function(e) {
			
			var template_id=   jQuery("#template_id").val();
	 		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_display_cart_checkout", "template_id": template_id},
					
					success: function(data){					
																	
					
						var res =jQuery.parseJSON(data);						
						if(res.response=='OK')
						{
							bup_update_booking_steps(3);						
						}
											
						jQuery("#bup-steps-cont-res").html(res.content);
										    
						bup_phone_format();

						}
				});			
			
			 
    		e.preventDefault();		 
				
        });
		
		
		
	//this loads step 2	
	jQuery(document).on("click", "#bup-btn-next-step1", function(e) {
			
						
			var b_category=   jQuery("#bup-category").val();
			var b_date=   jQuery("#bup-start-date").val();
			var b_staff=   jQuery("#bup-staff").val();
			var b_location=   jQuery("#bup-filter-id").val();
			var template_id=   jQuery("#template_id").val();
			var booking_form_type=   jQuery("#bup_booking_form_type").val();	
			
			jQuery("#bup-err-message" ).html( '' );			
			jQuery("#bup-steps-cont-res").html(bup_pro_front.message_wait_availability);				
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_book_step_2", "b_category": b_category, "b_date": b_date , "b_staff": b_staff, "b_location": b_location, "template_id": template_id },
					
					success: function(data){						
						
						var res =jQuery.parseJSON(data);						
						if(res.response=='OK')
						{
							bup_update_booking_steps(2);						
						}
											
						jQuery("#bup-steps-cont-res").html(res.content);											
										    
						

						}
				});				
			 
    		e.preventDefault();		 
				
        });
		
		
		//load cart
		
		jQuery(document).on("click", "#bup-btn-show-cart", function(e) {
			
			bup_reload_cart(); 	 
    		e.preventDefault();		 
				
        });
		

		
		jQuery(document).on("change", "#bup-category, #bup-filter-id", function(e) {
			
						
			var b_category=   jQuery("#bup-category").val();
			var filter_id=   jQuery("#bup-filter-id").val();
			var template_id=   jQuery("#template_id").val();	
			
			$('#bup-staff').prop('disabled', 'disabled');
			
			$('#bup-staff option:first-child').attr("selected", "selected");
			$('#bup-staff option:first-child').text(bup_pro_front.message_wait_staff_box);		
									
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_load_dw_of_staff", "b_category": b_category, "filter_id": filter_id , "template_id": template_id},
					
					success: function(data){
						
						
						var res = data;								
						jQuery("#bup-staff-booking-list").html(res);
						$('#bup-staff').prop('disabled', false);					    
						

						}
				});			
			
			
    		e.preventDefault();		 
				
        });
		

		
	
	jQuery(document).on("change", "#bup-purchased-qty", function(e) {
			
						
			var b_qty=   jQuery("#bup-purchased-qty").val();
			var service_id=   jQuery("#service_id").val();
			var staff_id=   jQuery("#staff_id").val();
			
							
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_update_purchase_total", 
					"b_qty": b_qty, "service_id": service_id , "staff_id": staff_id},
					
					success: function(data){						
						
						var res = data;
						var res =jQuery.parseJSON(data);						
						if(res.response=='OK')
						{
							jQuery("#bup-total-booking-amount").html(res.amount_with_symbol);
							jQuery("#bup_service_cost").val(res.amount);
						
						}

						

						}
				});			
			
			
    		e.preventDefault();		 
				
        });
		
		
	
			
	//this loads step 3	
	/*jQuery(document).on("click", ".bup-btn-book-app", function(e) {
			
			e.preventDefault();			
			
			var date_to_book =  jQuery(this).attr("bup-data-date");
			var service_and_staff_id =  jQuery(this).attr("bup-data-service-staff");
			var time_slot =  jQuery(this).attr("bup-data-timeslot");
			var form_id =  jQuery("#bup-custom-form-id").val();
			var location_id =  jQuery("#bup-filter-id").val();
			
			var field_legends =  jQuery("#field_legends").val();
			var placeholders =  jQuery("#placeholders").val();
			var template_id =  jQuery("#template_id").val();
			
			var max_capacity =   jQuery(this).attr("bup-max-capacity"); 
			var max_available =   jQuery(this).attr("bup-max-available"); 
			
			jQuery("#bup-err-message" ).html( '' );		
			
			
			jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "ubp_book_step_3", "date_to_book": date_to_book, "service_and_staff_id": service_and_staff_id  , "time_slot": time_slot , "form_id": form_id , "location_id": location_id  , "field_legends": field_legends  , 
						"placeholders": placeholders,
						"template_id": template_id ,
						"max_capacity": max_capacity,
						"max_available": max_available },
						
						success: function(data){
							
							//alert('ok');
							
							var res = data;
							var res =jQuery.parseJSON(data);
													
							if(res.response=='OK')
							{
								bup_update_booking_steps(3);						
							}
												
							jQuery("#bup-steps-cont-res").html(res.content);
							$("#bup-registration-form").validationEngine({promptPosition: 'inline'});				    
							
	
						}
						
			
				});
						
    		e.preventDefault();		 
				
        });*/
		
	/*	jQuery(document).on("click", ".bup-btn-book-app", function(e) {
			
			e.preventDefault();			
			
			var date_to_book =  jQuery(this).attr("bup-data-date");
			var service_and_staff_id =  jQuery(this).attr("bup-data-service-staff");
			var time_slot =  jQuery(this).attr("bup-data-timeslot");
			var form_id =  jQuery("#bup-custom-form-id").val();
			var location_id =  jQuery("#bup-filter-id").val();
			
			var field_legends =  jQuery("#field_legends").val();
			var placeholders =  jQuery("#placeholders").val();
			var template_id =  jQuery("#template_id").val();
			var show_cart =  jQuery("#bup_cart_id").val();
			
			var max_capacity =   jQuery(this).attr("bup-max-capacity"); 
			var max_available =   jQuery(this).attr("bup-max-available"); 
			
			jQuery("#bup-err-message" ).html( '' );		
			
			var completeCalled = false;
			
			jQuery('body, html').animate({scrollTop: jQuery("#bup-steps-cont-res").offset().top   }, 1500,
			
				function() {
					
					if(!completeCalled){
						
						completeCalled = true;
					
						jQuery.ajax({
							type: 'POST',
							url: ajaxurl,
							data: {"action": "ubp_book_step_3", "date_to_book": date_to_book, "service_and_staff_id": service_and_staff_id  , "time_slot": time_slot , "form_id": form_id , "location_id": location_id  , "field_legends": field_legends  , 
							"placeholders": placeholders,
							"template_id": template_id ,
							"max_capacity": max_capacity,
							"max_available": max_available },
							
							success: function(data){
								
								var res = data;
								var res =jQuery.parseJSON(data);
														
								if(res.response=='OK')
								{
									bup_update_booking_steps(3);						
								}
								
								
								if(show_cart==1){
									
									bup_reload_cart();											
								
								
								}else{
									
									jQuery("#bup-steps-cont-res").html(res.content);
									$("#bup-registration-form").validationEngine({promptPosition: 'inline'});
									
								}
								
		
							}
						});	
					
					
					}	
					
			
				}
			
			);
						
    		e.preventDefault();		 
				
        });*/
		
		//this is for the li element on reduced layout
		jQuery(document).on("click", ".bup-btn-book-app-li", function(e) {
			
			e.preventDefault();			
			
			var date_to_book =  jQuery(this).attr("bup-data-date");
			var service_and_staff_id =  jQuery(this).attr("bup-data-service-staff");
			var time_slot =  jQuery(this).attr("bup-data-timeslot");
			var form_id =  jQuery("#bup-custom-form-id").val();
			var location_id =  jQuery("#bup-filter-id").val();
			
			var field_legends =  jQuery("#field_legends").val();
			var placeholders =  jQuery("#placeholders").val();
			var template_id =  jQuery("#template_id").val();
			var show_cart =  jQuery("#bup_cart_id").val();
			
			var max_capacity =   jQuery(this).attr("bup-max-capacity"); 
			var max_available =   jQuery(this).attr("bup-max-available"); 
			
			jQuery("#bup-err-message" ).html( '' );		
			
			var completeCalled = false;
			
			jQuery('body, html').animate({scrollTop: jQuery("#bup-steps-cont-res").offset().top   }, 1500,
			
				function() {
					
					if(!completeCalled){
						
						completeCalled = true;
					
						jQuery.ajax({
							type: 'POST',
							url: ajaxurl,
							data: {"action": "ubp_book_step_3", 
							"date_to_book": date_to_book, 
							"service_and_staff_id": service_and_staff_id  , 
							"time_slot": time_slot , 
							"form_id": form_id , 
							"location_id": location_id  , 
							"field_legends": field_legends  , 
							"placeholders": placeholders,
							"template_id": template_id ,
							"max_capacity": max_capacity,
							"max_available": max_available },
							
							success: function(data){
								
								var res = data;
								var res =jQuery.parseJSON(data);
														
								if(res.response=='OK')
								{
									bup_update_booking_steps(3);						
								}
								
								
								if(show_cart==1){
									
									bup_reload_cart();	
                                    bup_update_booking_steps(33);
								
								
								}else{
									
									jQuery("#bup-steps-cont-res").html(res.content);
									$("#bup-registration-form").validationEngine({promptPosition: 'inline'});
									
								}
								
								//reoload phone format
								
								bup_phone_format();
								
								
		
							}
						});	
					
					
					}	
					
			
				}
			
			);
						
    		e.preventDefault();		 
				
        });
		
		jQuery(document).on("click", "#bup-btn-book-app-confirm", function(e) {
			
			e.preventDefault();
			
			$("#bup-registration-form").validationEngine({promptPosition: 'inline'});				
			var frm_validation  = $("#bup-registration-form").validationEngine('validate');	
			
		
			//check if user is a staff member trying to purchase an own service
			
			 if(bup_pro_front.country_detection != 'no'  ){
				 
				 var intlNumber = $("#reg_telephone").intlTelInput("getNumber");					
				jQuery("#full_number").val(intlNumber);
				
				var countryData = $("#reg_telephone").intlTelInput("getSelectedCountryData");
				
				jQuery("#full_number_prefix").val(countryData.dialCode);
				jQuery("#full_number_iso").val(countryData.iso2);
				 
				 				 
			}
			
			if(frm_validation)
			{
							
				var myRadioPayment = $('input[name=bup_payment_method]');
				var payment_method_selected = myRadioPayment.filter(':checked').val();				
				var payment_method =  jQuery("#bup_payment_method_stripe_hidden").val();
				

								
				if(payment_method=='stripe' && payment_method_selected=='stripe')
				{
					var wait_message = '<div class="bup_wait">' + bup_pro_front.wait_submit + '</div>';				
					jQuery('#bup-stripe-payment-errors').html(wait_message);					
					bup_stripe_process_card();
				
				} else if (payment_method=='stripe' && payment_method_selected=='authorize') {
					
					
				
				}else{
					
					
					jQuery("#bup-message-submit-booking-conf").html(bup_pro_front.message_wait_availability);					
					$('#bup-btn-book-app-confirm').prop('disabled', 'disabled');					
					$("#bup-registration-form").submit();
				
				}
				
				
			}else{
				
				
				
			}
			
			
									
    		e.preventDefault();		 
				
        });
		
 
       
    }); //END READY
})(jQuery);

function bup_update_booking_steps(current_step){
	
	var show_cart =  jQuery("#bup_cart_id").val();
	
	bup_update_booking_steps_remove_all();				
	jQuery( "#bup-step-rounded-" + current_step).removeClass( "bup-cart-step-inactive" ).addClass( "bup-cart-step-active" );
	
	if(current_step==3)	
	{	
		if(show_cart==1)
		{
			jQuery("#bup-btn-next-step1").text( bup_pro_front.button_legend_step3_cart);
		
		}else{
			
			jQuery("#bup-btn-next-step1").text( bup_pro_front.button_legend_step3);
		}
	
	}
	
	if(current_step==2)	
	{		
		jQuery("#bup-btn-next-step1").text( bup_pro_front.button_legend_step2);
	
	}		

}

function bup_update_booking_steps_remove_all()
{				
	
	jQuery( "#bup-step-rounded-1, #bup-step-rounded-2, #bup-step-rounded-3, #bup-step-rounded-33, #bup-step-rounded-4" ).removeClass( "bup-cart-step-active" ).addClass( "bup-cart-step-inactive" );				


}


function bup_load_step_4(order_key){
		
		
		jQuery("#bup-steps-cont-res").html(bup_pro_front.message_wait_availability);			
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_book_step_4", "order_key": order_key},
					
					success: function(data){					
						
						var res = data;								
						jQuery("#bup-steps-cont-res").html(res);
						bup_update_booking_steps(4);					    
						

						}
				});	
	
}

function bup_load_staff_by_service(b_category){
	
	
			var filter_id=   jQuery("#bup-filter-id").val();
			var template_id=   jQuery("#template_id").val();	
			
			$('#bup-staff').prop('disabled', 'disabled');
			
			$('#bup-staff option:first-child').attr("selected", "selected");
			$('#bup-staff option:first-child').text(bup_pro_front.message_wait_staff_box);									
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_load_dw_of_staff", "b_category": b_category, "filter_id": filter_id , "template_id": template_id},
					
					success: function(data){					
						
						var res = data;								
						jQuery("#bup-staff-booking-list").html(res);
						$('#bup-staff').prop('disabled', false);					    
						

						}
				});			
			
}

function bup_phone_format(){
	

if(bup_pro_front.country_detection != 'no'  ){
	
	 jQuery("#reg_telephone").intlTelInput({
			  // allowDropdown: false,
			  // autoHideDialCode: false,
			  // autoPlaceholder: "off",
			  // dropdownContainer: "body",
			  // excludeCountries: ["us"],
			  // formatOnDisplay: false,
			  geoIpLookup: function(callback) {
				 jQuery.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
				   var countryCode = (resp && resp.country) ? resp.country : "";
				  callback(countryCode);
				});
			   },
			   hiddenInput: "full_number",
				initialCountry: "auto",
			  // nationalMode: false,
			  // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
			   placeholderNumberType: "MOBILE",
			  // preferredCountries: ['cn', 'jp'],
			  // separateDialCode: true,
			//  utilsScript: "../js/int-phone-code/js/utils.js"
			   utilsScript: bup_pro_front.country_util_url
			  
			  
			});
	}

}


function bup_auto_display_slots(b_category, b_staff){	

			var b_date=   jQuery("#bup-start-date").val();
			var b_location=   jQuery("#bup-filter-id").val();
			var template_id=   jQuery("#template_id").val();
			var booking_form_type=   jQuery("#bup_booking_form_type").val();	
			
			jQuery("#bup-err-message" ).html( '' );			
			jQuery("#bup-steps-cont-res").html(bup_pro_front.message_wait_availability);				
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_book_step_2", "b_category": b_category, "b_date": b_date , "b_staff": b_staff, "b_location": b_location, "template_id": template_id },
					
					success: function(data){						
						
						var res =jQuery.parseJSON(data);						
						if(res.response=='OK')
						{
							bup_update_booking_steps(2);						
						}
											
						jQuery("#bup-steps-cont-res").html(res.content);											
										    
						

						}
				});				
			 
}

function bup_reload_cart(){	


	
	//jQuery("#bup-steps-cont-res").html(bup_pro_front.message_wait_availability);
	
	var template_id=   jQuery("#template_id").val();
		
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_get_shopping_cart","reload_cart": "reload_cart","template_id": template_id},
					
					success: function(data){					
						
						var res = data;								
						jQuery("#bup-steps-cont-res").html(res);
                        bup_update_booking_steps(33);
									    
						

						}
						
						,
				  error: function(errorThrown){
					  alert(errorThrown);
				  } 
				});	
	
}



