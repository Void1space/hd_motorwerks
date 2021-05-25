var $ = jQuery;


jQuery(document).ready(function($) {
	
	
	
	$('.uultra-tooltip').qtip();
	jQuery("#uultra-add-new-custom-field-frm").slideUp();	 
	jQuery( "#tabs-bupro" ).tabs({collapsible: false	});
	jQuery( "#tabs-bupro-settings" ).tabs({collapsible: false	});	
	jQuery( ".bup-datepicker" ).datepicker({changeMonth:true,changeYear:true,yearRange:"1940:2025"});
	
	// Adding jQuery Datepicker
	jQuery(function() {
			
			var uultra_date_format =  jQuery('#uultrbup-widgets-icon-close-opena_date_format').val();			
			if(uultra_date_format==''){uultra_date_format='m/d/Y'}		
			
			
			jQuery( ".bupro-datepicker" ).datepicker({ 
				showOtherMonths: true, 
				dateFormat: bup_admin_v98.bb_date_picker_format, 
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
		
		
	function bup_set_auto_c()
	{
			$("#bupclientsel").autocomplete({
				
			
			source: function( request, response ) {
					$.ajax({
						url: ajaxurl,
						dataType: "json",
						data: {
							action: 'bup_autocomple_clients_tesearch',
							term: this.term
						},
						
						success: function( data ) {
							
							response( $.map( data.results, function( item ) {
			                return {
								id: item.id,
			                	label: item.label,
			                	value: item.label
			                }
			           		 }));
							 
							 
							
						},
						
						error: function(jqXHR, textStatus, errorThrown) 
						{
							console.log(jqXHR, textStatus, errorThrown);
						}
						
					});
				},
			
				minLength: 2,			
				
				// optional (if other layers overlap autocomplete list)
				open: function(event, ui) {
					
					var dialog = $(this).closest('.ui-dialog');
					if(dialog.length > 0){
						$('.ui-autocomplete.ui-front').zIndex(dialog.zIndex()+1);
					}
				},
				
				select: function( event, ui ) {
					
					ui.item.ur
					
					jQuery( "#bup_client_id" ).val(ui.item.id);
						
				}
			
			});
		
	}
	

	jQuery(document).on("click", ".buppro-message-close", function(e) {
		
		e.preventDefault();	

		
		var message_id =  jQuery(this).attr("message-id");
		
		jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_hide_proversion_message","message_id": message_id 
						 },
						
						success: function(data){
							
							window.location.reload();
							//alert('blocked');
								
							
							}
					});
			
	});
	
	//seet gcal				
	$(document).on("click", "#bup-backenedb-set-gacal-adm", function(e) {			
			
			var google_calendar =   $('#bup_staff_calendar_list').val();
			var staff_id =  jQuery(this).attr("staff-id");	
			
					
			
			$("#bup-gcal-message3").html(bup_admin_v98.msg_wait);
						
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "bup_set_default_gcal_staff", "google_calendar": google_calendar,  "staff_id": staff_id },
				
				success: function(data){									
					
					jQuery("#bup-gcal-message1").hide();
					jQuery("#bup-gcal-message2").hide();					
					$("#bup-gcal-message3").html(data);									
					
					
					}
			});
			
    		e.preventDefault();		 
				
        });
	
	/* 	Close Open Sections in Dasbhoard */

	jQuery(document).on("click", ".bup-widget-home-colapsable", function(e) {
		
		e.preventDefault();
		var widget_id =  jQuery(this).attr("widget-id");		
		var iconheight = 20;
		
		
		if(jQuery("#bup-main-cont-home-"+widget_id).is(":visible")) 
	  	{
					
			jQuery( "#bup-close-open-icon-"+widget_id ).removeClass( "fa-sort-asc" ).addClass( "fa-sort-desc" );
			
		}else{
			
			jQuery( "#bup-close-open-icon-"+widget_id ).removeClass( "fa-sort-desc" ).addClass( "fa-sort-asc" );			
	 	 }
		
		
		jQuery("#bup-main-cont-home-"+widget_id).slideToggle();	
					
		return false;
	});
	
	
	
	jQuery(document).on("click", ".ubp-service-cate-parent", function(e) {
			
			var ischecked = $(this).is(":checked");			
			var service_id = $(this).val();
			
			if(ischecked)
			{
				jQuery('.bup-service-cate-'+service_id).each(function () {
						  
					$(this).prop('checked',1);										
					$("#bup-price-"+$(this).val()).prop("disabled",false);	
					$("#bup-qty-"+$(this).val()).prop("disabled",false);
								
				 });	
			
			}else{
			
				jQuery('.bup-service-cate-'+service_id).each(function () {
						  
					$(this).prop('checked',0);										
					$("#bup-price-"+$(this).val()).prop("disabled",true);	
					$("#bup-qty-"+$(this).val()).prop("disabled",true);
								
				 });
			}
			
	});
	
	
	//this will crop the avatar and redirect
	jQuery(document).on("click touchstart", "#uultra-confirm-avatar-cropping", function(e) {
			
			e.preventDefault();			
			
			var x1 = jQuery('#x1').val();
			var y1 = jQuery('#y1').val();
			
			
			var w = jQuery('#w').val();
			var h = jQuery('#h').val();
			var image_id = $('#image_id').val();
			var user_id = $('#user_id').val();				
			
			if(x1=="" || y1=="" || w=="" || h==""){
				alert("You must make a selection first");
				return false;
			}
			
			
			jQuery('#bup-cropping-avatar-wait-message').html(message_wait_availability);
			
			
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "bup_crop_avatar_user_profile_image", "x1": x1 , "y1": y1 , "w": w , "h": h  , "image_id": image_id , "user_id": user_id},
				
				success: function(data){					
					//redirect				
					var site_redir = jQuery('#site_redir').val();
					window.location.replace(site_redir);	
								
					
					
					}
			});
			
					
					
		     	
			 return false;
    		e.preventDefault();
			 

				
        });
		
	
	
	jQuery(document).on("click", "#bup-disconnect-gcal-user", function(e) {
			
			e.preventDefault();
			
			var user_id =  jQuery(this).attr("user-id");
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_disconnect_user_gcal", "user_id": user_id },
					
					success: function(data){
						
						bup_load_staff_details(user_id);
												
																
						
					}
				});
			
			
    		e.preventDefault();
			 
				
        });
	jQuery(document).on("click", "#btn-delete-user-avatar", function(e) {
			
			e.preventDefault();
			
			var user_id =  jQuery(this).attr("user-id");
			var redirect_avatar =  jQuery(this).attr("redirect-avatar");
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_delete_user_avatar", "user_id": user_id },
					
					success: function(data){
												
						refresh_my_avatar();
						
						if(redirect_avatar=='yes')
						{
							var site_redir = jQuery('#site_redir').val();
							window.location.replace(site_redir);
							
						}else{
							
							refresh_my_avatar();
							
						}
											
						
					}
				});
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
	
	function refresh_my_avatar ()
		{
			
			 jQuery.post(ajaxurl, {
							action: 'refresh_avatar'}, function (response){									
																
							jQuery("#uu-backend-avatar-section").html(response);
					
			});
			
		}
	
	
	jQuery(document).on("click", "#bup_re_schedule", function(e) {
			
			
			
			if ($(this).is(":checked")) 
			{
                $("#bup-availability-box").slideDown();
				$("#bup-availability-box-btn").slideDown();
				
            } else {
				
				$("#bup-availability-box-btn").slideUp();				
                $("#bup-availability-box").slideUp();
            }			
			
				 
				
        });
		
	jQuery(document).on("click", "#bupadmin-btn-validate-copy", function(e) {	
	
	
		 e.preventDefault();
		 
		 var p_ded =  $('#p_serial').val();
		 
		 jQuery("#loading-animation").slideDown();
		
		jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_vv_c_de_a", 
						"p_s_le": p_ded },
						
						success: function(data){
							
							jQuery("#loading-animation").slideUp();							
						
								jQuery("#bup-validation-results").html(data);
								jQuery("#bup-validation-results").slideDown();								
								setTimeout("hidde_noti('bup-validation-results')", 6000)
								
								window.location.reload();
							
							}
					});
			
		 	
		
				
		return false;
	});
		
	
		/* 	FIELDS CUSTOMIZER -  ClosedEdit Field Form */
	jQuery('#uu-fields-sortable').on('click','.bup-btn-close-edition-field',function(e)
	{		
		e.preventDefault();
		var block_id =  jQuery(this).attr("data-edition");		
		jQuery("#bup-edit-fields-bock-"+block_id).slideUp();				
		return false;
	});
    
    
	
	/* 	FIELDS CUSTOMIZER -  Add New Field Form */
	jQuery('#bup-add-field-btn').on('click',function(e)
	{
		
		e.preventDefault();
			
		jQuery("#bup-add-new-custom-field-frm").slideDown();				
		return false;
	});
	
	/* 	FIELDS CUSTOMIZER -  Add New Field Form */
	jQuery('#bup-close-add-field-btn').on('click',function(e){
		
		e.preventDefault();
			
		jQuery("#bup-add-new-custom-field-frm").slideUp();				
		return false;
	});
	
	
	/* 	FIELDS CUSTOMIZER -  Edit Field Form */
	jQuery('#uultra__custom_registration_form').on('change',function(e)
	{		
		e.preventDefault();
		bup_reload_custom_fields_set();
					
		return false;
	});
	
	
	/* Delete Users */
        
    jQuery('#bup-staff-details').on('click','#ubp-staff-member-delete',function(e)
	{
        
        
		e.preventDefault();	
			  
		  var staff_id =  jQuery(this).attr("staff-id");	
		  
		  
		  var doIt = false;
		
		  doIt=confirm(bup_admin_v98.msg_user_delete);
		  
		  if(doIt)
		  {
			  jQuery("#bup-spinner").show();
			  
				jQuery.ajax({
							type: 'POST',
							url: ajaxurl,
							data: {"action": "bup_delete_staff_admin", 
							"staff_id": staff_id 
							 },
							
							success: function(data){				
							
								
								var staff_id = data;								
								jQuery("#bup-spinner").hide();
								bup_load_staff_list_adm();
								bup_load_staff_details(staff_id);			
							
							}
					});
				
				
				}
			
		   	
				
		
	});
	
	
	/* 	Update Details */

    jQuery(document).on("click", "#bup-btn-user-details-confirm", function(e) {
        
   
		e.preventDefault();	
			  
		  var staff_id =  jQuery(this).attr("data-field");	
		  
		  var staff_id =  jQuery('#staff_id').val();
		  var display_name =  jQuery('#reg_display_name').val();
		  var reg_telephone =  jQuery('#reg_telephone').val();
		  
		  var reg_email =  jQuery('#reg_email').val();
		  var reg_email2 =  jQuery('#reg_email2').val();
		  
		  jQuery("#bup-edit-details-message").html(message_wait_availability);	 
		
			jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_update_staff_admin", 
						"staff_id": staff_id , 
						"display_name": display_name ,
						"reg_email": reg_email , 
						"reg_email2": reg_email2 , 
						"reg_telephone": reg_telephone },
						
						success: function(data){							
						
							jQuery("#bup-edit-details-message").html(data);				
						
							
							
							
							}
				});
			
		   	
				
		
	});
	
	/* 	FIELDS CUSTOMIZER - Delete Field */
	jQuery('#uu-fields-sortable').on('click','.bup-delete-profile-field-btn',function(e)
	{
		e.preventDefault();
		
		var doIt = false;
		
		doIt=confirm(custom_fields_del_confirmation);
		  
		  if(doIt)
		  {
			  
			  var p_id =  jQuery(this).attr("data-field");	
			  var uultra_custom_form =  jQuery('#uultra__custom_registration_form').val();
		
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "delete_profile_field", 
						"_item": p_id , "bup_custom_form": uultra_custom_form },
						
						success: function(data){					
						
							jQuery("#bup-sucess-delete-fields-"+p_id).slideDown();
						    setTimeout("hidde_noti('bup-sucess-delete-fields-" + p_id +"')", 1000);
							jQuery( "#"+p_id ).addClass( "bup-deleted" );
							setTimeout("hidde_noti('" + p_id +"')", 1000);
							
							//reload fields list added 08-08-2014						
							bup_reload_custom_fields_set();		
							
							
							}
					});
			
		  }
		  else{
			
		  }		
		
				
		return false;
	});
	
	
	/* 	FIELDS CUSTOMIZER - Add New Field Data */
	jQuery('#bup-btn-add-field-submit').on('click',function(e){
		e.preventDefault();
		
		
		var _position = $("#uultra_position").val();		
		var _type =  $("#uultra_type").val();
		var _field = $("#uultra_field").val();		
		
		var _meta_custom = $("#uultra_meta_custom").val();		
		var _name = $("#uultra_name").val();
		var _tooltip =  $("#uultra_tooltip").val();	
		var _help_text =  $("#uultra_help_text").val();		
	
		
		var _can_edit =  $("#uultra_can_edit").val();		
		var _allow_html =  $("#uultra_allow_html").val();
				
		var _private = $("#uultra_private").val();
		var _required =  $("#uultra_required").val();		
		var _show_in_register = $("#uultra_show_in_register").val();
		
		var _choices =  $("#uultra_choices").val();	
		var _predefined_options =  $("#uultra_predefined_options").val();		
		var uultra_custom_form =  $('#uultra__custom_registration_form').val();	
				
		var _icon =  $('input:radio[name=uultra_icon]:checked').val();
		
				
		jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "add_new_custom_profile_field", 
						"_position": _position , 
						"_type": _type ,
						"_field": _field ,
						"_meta_custom": _meta_custom ,
						"_name": _name  ,						
						"_tooltip": _tooltip ,
						
						"_help_text": _help_text ,	
						
						"_can_edit": _can_edit ,"_allow_html": _allow_html  ,
						"_private": _private, 
						"_required": _required  ,
						"_show_in_register": _show_in_register ,						
						"_choices": _choices,  
						"_predefined_options": _predefined_options , 
						"bup_custom_form": uultra_custom_form,						
						"_icon": _icon },
						
						success: function(data){		
						
													
							jQuery("#bup-sucess-add-field").slideDown();
							setTimeout("hidde_noti('bup-sucess-add-field')", 3000)		
							//alert("done");
							window.location.reload();
							 							
							
							
							}
					});
			
		 
		
				
		return false;
	});
	
	/* 	FIELDS CUSTOMIZER - Update Field Data */
	jQuery('#uu-fields-sortable').on('click','.bup-btn-submit-field',function(e){
		e.preventDefault();
		
		var key_id =  jQuery(this).attr("data-edition");	
		
		jQuery('#p_name').val()		  
		
		var _position = $("#uultra_" + key_id + "_position").val();		
		var _type =  $("#uultra_" + key_id + "_type").val();
		var _field = $("#uultra_" + key_id + "_field").val();		
		var _meta =  $("#uultra_" + key_id + "_meta").val();
		var _meta_custom = $("#uultra_" + key_id + "_meta_custom").val();		
		var _name = $("#uultra_" + key_id + "_name").val();
				
		var _tooltip =  $("#uultra_" + key_id + "_tooltip").val();	
		var _help_text =  $("#uultra_" + key_id + "_help_text").val();		
				
		var _can_edit =  $("#uultra_" + key_id + "_can_edit").val();		
		
		var _required =  $("#uultra_" + key_id + "_required").val();		
		var _show_in_register = $("#uultra_" + key_id + "_show_in_register").val();
				
		var _choices =  $("#uultra_" + key_id + "_choices").val();	
		var _predefined_options =  $("#uultra_" + key_id + "_predefined_options").val();		
		var _icon =  $('input:radio[name=uultra_' + key_id +'_icon]:checked').val();
		
		var uultra_custom_form =  $('#uultra__custom_registration_form').val();
		
		jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "save_fields_settings", 
						"_position": _position , "_type": _type ,
						"_field": _field ,
						"_meta": _meta ,
						"_meta_custom": _meta_custom  
						,"_name": _name  ,											
						
						"_tooltip": _tooltip ,
						"_help_text": _help_text ,												
						"_icon": _icon ,						
						"_required": _required  ,
						"_show_in_register": _show_in_register ,						
						"_choices": _choices, 
						"_predefined_options": _predefined_options,
						"pos": key_id  , 
						"bup_custom_form": uultra_custom_form 
						
																	
						},
						
						success: function(data){		
						
												
						jQuery("#bup-sucess-fields-"+key_id).slideDown();
						setTimeout("hidde_noti('bup-sucess-fields-" + key_id +"')", 1000);
						
						bup_reload_custom_fields_set();		
						
							
							}
					});
			
		 
		
				
		return false;
	});
	
	
	/* 	FIELDS CUSTOMIZER -  Edit Field Form */
		
	jQuery(document).on("click", ".bup-btn-edit-field", function(e) {
		
		e.preventDefault();
		var block_id =  jQuery(this).attr("data-edition");			
		
		var uultra_custom_form = jQuery('#uultra__custom_registration_form').val();
		
		jQuery("#bup-spinner").show();
		
		jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_reload_field_to_edit", 
						"pos": block_id, "bup_custom_form": uultra_custom_form},
						
						success: function(data){
							
							
							jQuery("#bup-edit-fields-bock-"+block_id).html(data);							
							jQuery("#bup-edit-fields-bock-"+block_id).slideDown();							
							jQuery("#bup-spinner").hide();								
							
							
							}
					});
		
					
		return false;
	});
    
	
	jQuery(document).on("click", "#bup-adm-check-avail-btn", function(e) {
			
			e.preventDefault();			
			
			var b_category=   jQuery("#bup-category").val();
			var b_date=   jQuery("#bup-start-date").val();
			var b_staff=   jQuery("#bup-staff").val();	
			
			jQuery("#bup-steps-cont-res" ).html( message_wait_availability );		
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_check_adm_availability", "b_category": b_category, "b_date": b_date , "b_staff": b_staff },
					
					success: function(data){
						
						
						var res = data;								
						jQuery("#bup-steps-cont-res").html(res);					    
						

						}
				});			
			
			 return false;
    		e.preventDefault();		 
				
        });
	
		jQuery(document).on("click", "#bup-adm-check-avail-btn-edit", function(e) {
			
			e.preventDefault();			
			
			var b_category=   jQuery("#bup-category").val();
			var b_date=   jQuery("#bup-start-date").val();
			var b_staff=   jQuery("#bup-staff").val();	
			
			jQuery("#bup-steps-cont-res-edit" ).html( message_wait_availability );		
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_check_adm_availability_admin", "b_category": b_category, "b_date": b_date , "b_staff": b_staff },
					
					success: function(data){
						
						
						var res = data;								
						jQuery("#bup-steps-cont-res-edit").html(res);					    
						

						}
				});			
			
			 return false;
    		e.preventDefault();		 
				
        });
		
	
	jQuery(document).on("click", ".bup-btn-book-app", function(e) {
			
			e.preventDefault();			
			
			var date_to_book =  jQuery(this).attr("bup-data-date");
			var service_and_staff_id =  jQuery(this).attr("bup-data-service-staff");
			var time_slot =  jQuery(this).attr("bup-data-timeslot");
			
			jQuery("#bup_time_slot").val(time_slot);
			jQuery("#bup_booking_date").val(date_to_book);
			jQuery("#bup_service_staff").val(service_and_staff_id);
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "appointment_get_selected_time", 
						   "bup_booking_date": date_to_book,
						   "bup_service_staff": service_and_staff_id,
						   "bup_time_slot": time_slot},
					
					success: function(data){						
						
						var res = data;							
						jQuery("#bup-steps-cont-res").html(res);						

						}
				});				
			
				
    		e.preventDefault();		 
				
    });
	
	jQuery(document).on("click", ".bup-btn-book-app-admin", function(e) {
			
			e.preventDefault();			
			
			var date_to_book =  jQuery(this).attr("bup-data-date");
			var service_and_staff_id =  jQuery(this).attr("bup-data-service-staff");
			var time_slot =  jQuery(this).attr("bup-data-timeslot");
			
			jQuery("#bup_time_slot").val(time_slot);
			jQuery("#bup_booking_date").val(date_to_book);
			jQuery("#bup_service_staff").val(service_and_staff_id);
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "appointment_get_selected_time", 
						   "bup_booking_date": date_to_book,
						   "bup_service_staff": service_and_staff_id,
						   "bup_time_slot": time_slot},
					
					success: function(data){						
						
						var res = data;							
						jQuery("#bup-steps-cont-res-edit").html(res);						

						}
				});				
			
				
			
    		e.preventDefault();		 
				
    });
	
	
	jQuery(document).on("click", ".ubp-load-services-by-cate", function(e) {
		
		e.preventDefault();
		var category_id =  jQuery(this).attr("data-id");			
		
		bup_load_services(category_id);
		
			
					
	});
	
	
		
	jQuery(document).on("change", "#bup-category", function(e) {
			
			e.preventDefault();			
			
			var b_category=   jQuery("#bup-category").val();
			
			$('#bup-staff').prop('disabled', 'disabled');
			
			$('#bup-staff option:first-child').attr("selected", "selected");
			$('#bup-staff option:first-child').text(bup_admin_v98.message_wait_staff_box);
							
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "get_cate_dw_admin_ajax", "b_category": b_category},
					
					success: function(data){						
						
						var res = data;						
						jQuery("#bup-staff-booking-list").html(res);					    
						

						}
				});			
			
			 return false;
    		e.preventDefault();		 
				
        });
	
	
	/* open staff member form */	
	jQuery( "#bup-staff-editor-box" ).dialog({
			autoOpen: false,			
			width: '400', // overcomes width:'auto' and maxWidth bug
   			maxWidth: 900,
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {
			"Add": function() {				
				
				var ret;
				
				var staff_name=   jQuery("#staff_name").val();
				var staff_email=   jQuery("#staff_email").val();
				var staff_nick=   jQuery("#staff_nick").val();	
				jQuery("#bup-err-message" ).html( '' );		
							
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "ubp_add_staff_confirm", "staff_name": staff_name, "staff_email": staff_email , "staff_nick": staff_nick },
						
						success: function(data){
							
							
							var res = data;						
							
							if(isInteger(res))	
							{
								//load staff								
								bup_load_staff_adm(res);																
								jQuery("#bup-staff-editor-box" ).dialog( "close" );
								
														
							}else{
							
								jQuery("#bup-err-message" ).html( res );	
							
							}				
													
							 
							
							
							}
					});
				
				
				
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	
	/* open client member form */	
	jQuery( "#bup-client-new-box" ).dialog({
			autoOpen: false,			
			width: '400px', // overcomes width:'auto' and maxWidth bug
   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {
			"Add": function() {				
				
				var ret;
				
				var client_name=   jQuery("#client_name").val();
				var client_last_name=   jQuery("#client_last_name").val();
				var client_email=   jQuery("#client_email").val();
					
				jQuery("#bup-err-message" ).html( '' );		
							
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "ubp_add_client_confirm", "client_name": client_name, "client_last_name": client_last_name , "client_email": client_email },
						
						success: function(data){					
							
													
							var res =jQuery.parseJSON(data);				
							
							if(res.response=='OK')	
							{
																
								jQuery("#bup_client_id" ).val(res.user_id);	
								jQuery("#bupclientsel" ).val(res.content);	
								jQuery("#bup-client-new-box" ).dialog( "close" );							
														
							}else{ //ERROR
							
								jQuery("#bup-add-client-message" ).html( res.content );	
							
							}				
							
						}
					});
				
				
				
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	jQuery(document).on("click", "#bup-btn-client-new-admin", function(e) {
			
			e.preventDefault();		
			
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_client_get_add_form"},
					
					success: function(data){						
						
						var res = data;
						jQuery("#bup-client-new-box" ).html( res );
						jQuery("#bup-client-new-box" ).dialog( "open" );						

					}
				});				
			
				
			
    		e.preventDefault();		 
				
    });
	
	/* appointment created confirmation */	
	jQuery( "#bup-new-app-conf-message" ).dialog({
			autoOpen: false,			
			width: 'auto', // overcomes width:'auto' and maxWidth bug
   			maxWidth: 900,
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {
						
			"Close": function() {
								
				jQuery( this ).dialog( "close" );
			},			
			
			},
			close: function() {
			
			
			}
	});
	
	
		
	//this adds the user and loads the user's details	
	jQuery(document).on("click", "#bup-create-new-app", function(e) {
			
			e.preventDefault();	
			
			jQuery("#bup-spinner").show();		
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_admin_new_appointment" },
					
					success: function(data){						
						
						var res = data;
						jQuery("#bup-appointment-new-box" ).html( res );
						jQuery("#bup-appointment-new-box" ).dialog( "open" );
						
						
						jQuery( ".bupro-datepicker" ).datepicker({ 
							showOtherMonths: true, 
							dateFormat: bup_admin_v98.bb_date_picker_format, 
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
						jQuery("#bup-spinner").hide();						
						bup_set_auto_c();	
					     
						
						
						}
				});
			
				
	});
	
	/* add Payment */	
	jQuery( "#bup-confirmation-cont" ).dialog({
			autoOpen: false,			
			width: '300', //   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Ok": function() {				
				
				jQuery( this ).dialog( "close" );
			}
			
			
			},
			close: function() {
			
			
			}
	});
	
	
	
	
	/* add break */	
	jQuery( "#bup-breaks-new-box" ).dialog({
			autoOpen: false,			
			width: '300', //   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Cancel": function() {				
				
				jQuery( this ).dialog( "close" );
			},
			
			"Save": function() {				
				
				var bup_payment_amount=   jQuery("#bup_payment_amount").val();
				var bup_payment_transaction=   jQuery("#bup_payment_transaction").val();
				var bup_payment_date=   jQuery("#bup_payment_date").val();
				var bup_booking_id=   jQuery("#bup_appointment_id").val();
				var bup_payment_status=   jQuery("#bup_payment_status").val();	
				
				var bup_payment_id=   jQuery("#bup_payment_id").val();			
				
				if(bup_payment_amount==''){alert(err_message_payment_amount); return;}
				if(bup_payment_date==''){alert(err_message_payment_date); return;}	
							
				
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_staff_break_add_confirm", 
						       "bup_payment_amount": bup_payment_amount,
							   "bup_payment_transaction": bup_payment_transaction,
							   "bup_payment_date": bup_payment_date,
							   "bup_booking_id": bup_booking_id,
							   "bup_payment_id": bup_payment_id,
							   "bup_payment_status": bup_payment_status },
						
						success: function(data){	
							
							jQuery("#bup-new-payment-cont" ).html( data );
							jQuery("#bup-new-payment-cont" ).dialog( "close" );	
							bup_load_appointment_payments(bup_booking_id);						
							
							
							}
					});
					
					
				
			
			}
			
			
			},
			close: function() {
			
			
			}
	});
	
	

	
	/* add Payment */	
	jQuery( "#bup-new-payment-cont" ).dialog({
			autoOpen: false,			
			width: '300', //   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Cancel": function() {				
				
				jQuery( this ).dialog( "close" );
			},
			
			"Save": function() {				
				
				var bup_payment_amount=   jQuery("#bup_payment_amount").val();
				var bup_payment_transaction=   jQuery("#bup_payment_transaction").val();
				var bup_payment_date=   jQuery("#bup_payment_date").val();
				var bup_booking_id=   jQuery("#bup_appointment_id").val();
				var bup_payment_status=   jQuery("#bup_payment_status").val();	
				
				var bup_payment_id=   jQuery("#bup_payment_id").val();			
				
				if(bup_payment_amount==''){alert(err_message_payment_amount); return;}
				if(bup_payment_date==''){alert(err_message_payment_date); return;}	
							
				
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_admin_payment_confirm", 
						       "bup_payment_amount": bup_payment_amount,
							   "bup_payment_transaction": bup_payment_transaction,
							   "bup_payment_date": bup_payment_date,
							   "bup_booking_id": bup_booking_id,
							   "bup_payment_id": bup_payment_id,
							   "bup_payment_status": bup_payment_status },
						
						success: function(data){	
							
							jQuery("#bup-new-payment-cont" ).html( data );
							jQuery("#bup-new-payment-cont" ).dialog( "close" );	
							bup_load_appointment_payments(bup_booking_id);						
							
							
							}
					});
					
					
				
			
			}
			
			
			},
			close: function() {
			
			
			}
	});
	
	/* add note */	
	jQuery( "#bup-new-note-cont" ).dialog({
			autoOpen: false,			
			width: '300', //   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Cancel": function() {				
				
				jQuery( this ).dialog( "close" );
			},
			
			"Save": function() {				
				
				var bup_note_title=   jQuery("#bup_note_title").val();
				var bup_note_text=   jQuery("#bup_note_text").val();
				var bup_note_id=   jQuery("#bup_note_id").val();
				var bup_booking_id=   jQuery("#bup_appointment_id").val();
								
				if(bup_note_title==''){alert(err_message_note_title); return;}
				if(bup_note_text==''){alert(err_message_note_text); return;}	
							
				
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_admin_note_confirm", 
						       "bup_note_title": bup_note_title,
							   "bup_booking_id": bup_booking_id,
							   "bup_note_text": bup_note_text,
							   "bup_note_id": bup_note_id},
						
						success: function(data){	
							
							jQuery("#bup-new-note-cont" ).html( data );
							jQuery("#bup-new-note-cont" ).dialog( "close" );	
							bup_load_appointment_notes(bup_booking_id);						
							
							
							}
					});
					
					
				
			
			}
			
			
			},
			close: function() {
			
			
			}
	});
	
	jQuery(document).on("click", ".bup-payment-deletion", function(e) {
			
			e.preventDefault();
			
			var appointment_id = jQuery(this).attr("bup-appointment-id");			
			var payment_id =  jQuery(this).attr("bup-payment-id");	 						
    		
			doIt=confirm(err_message_payment_delete);
		  
		    if(doIt)
		    {
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_delete_payment",  "payment_id": payment_id ,  "appointment_id": appointment_id },
						
						success: function(data){	
						
							bup_load_appointment_payments(appointment_id);	
						
						
							
							}
					});
				
				
			}
			
    		e.preventDefault();
			 
				
        });
	
	
	jQuery(document).on("click", ".bup-note-deletion", function(e) {
			
			e.preventDefault();
			
			var appointment_id = jQuery(this).attr("bup-appointment-id");			
			var note_id =  jQuery(this).attr("bup-note-id");	 						
    		
			doIt=confirm(err_message_note_delete);
		  
		    if(doIt)
		    {
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_delete_note",  "note_id": note_id ,  "appointment_id": appointment_id },
						
						success: function(data){	
						
							bup_load_appointment_notes(appointment_id);	
						
						
							
							}
					});
				
				
			}
			
    		e.preventDefault();
			 
				
        });
	
	jQuery(document).on("click", ".bup-payment-edit", function(e) {
			
			e.preventDefault();
			
			var appointment_id = jQuery(this).attr("bup-appointment-id");			
			var payment_id =  jQuery(this).attr("bup-payment-id");	 						
    		
			
			jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_get_payment_form",  "payment_id": payment_id ,  "appointment_id": appointment_id },
						
						success: function(data){	
						
						
							jQuery("#bup-new-payment-cont" ).html( data );	
							jQuery("#bup-new-payment-cont" ).dialog( "open" );	
							
							var uultra_date_format =  jQuery('#uultra_date_format').val();									
							if(uultra_date_format==''){uultra_date_format='dd/mm/yy';}	
						
							jQuery( ".bupro-datepicker" ).datepicker({ 
								showOtherMonths: true, 
								dateFormat: bup_admin_v98.bb_date_picker_format, 
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
						
							
							}
					});			
				

    		e.preventDefault();
			 
				
        });
	
	//
	jQuery(document).on("click", "#bup-adm-add-payment", function(e) {
			
			e.preventDefault();	
			jQuery("#bup-spinner").show();		
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_get_payment_form"},
					
					success: function(data){					
												
						jQuery("#bup-new-payment-cont" ).html( data );	
						jQuery("#bup-new-payment-cont" ).dialog( "open" );	
						jQuery("#bup-spinner").hide();	
						
						
						var uultra_date_format =  jQuery('#uultra_date_format').val();
									
						if(uultra_date_format==''){uultra_date_format='dd/mm/yy';}	
					
						jQuery( ".bupro-datepicker" ).datepicker({ 
							showOtherMonths: true, 
							dateFormat: bup_admin_v98.bb_date_picker_format, 
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
						
									     
						
						
						}
				});
			
			
			
    		e.preventDefault();
			 
				
    });
	
	
	jQuery(document).on("click", ".bup-breaks-add", function(e) {
			
			e.preventDefault();	
						
			var day_id = jQuery(this).attr("day-id");
			var staff_id=   jQuery("#staff_id").val();
			
			jQuery("#bup-break-add-break-" +day_id).show();			
			jQuery("#bup-break-add-break-" +day_id).html( message_wait_availability );
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_get_break_add", 
							"day_id": day_id,
							"staff_id": staff_id},
					
					success: function(data){								
												
						jQuery("#bup-break-add-break-" +day_id).html( data );
									
												
						
												
						
						}
				});
			
			
			
    		e.preventDefault();
			 
				
    });
	
	//confirm break addition
	jQuery(document).on("click", ".bup-button-submit-breaks", function(e) {
			
			e.preventDefault();	
						
			var day_id = jQuery(this).attr("day-id");
			var staff_id=   jQuery("#staff_id").val();	
			
			var bup_from=   jQuery("#bup-break-from-"+day_id).val();
			var bup_to=   jQuery("#bup-break-to-"+day_id).val();
			
		
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_break_add_confirm", 
							"day_id": day_id,
							"staff_id": staff_id,
							"from": bup_from,
							"to": bup_to},
					
					success: function(data){
						
						var res = data	;												
						jQuery("#bup-break-message-add-" +day_id).html( data );
						bup_reload_staff_breaks(staff_id, day_id);
												
						
						}
				});
			
			
			
    		e.preventDefault();
			 
				
    });
	
	jQuery(document).on("click", "#bup-adm-add-note", function(e) {
			
			e.preventDefault();	
			jQuery("#bup-spinner").show();		
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_get_note_form"},
					
					success: function(data){					
												
						jQuery("#bup-new-note-cont" ).html( data );	
						jQuery("#bup-new-note-cont" ).dialog( "open" );	
						jQuery("#bup-spinner").hide();	
						
												
						
						}
				});
			
			
			 // Cancel the default action
			
    		e.preventDefault();
			 
				
    });
	
	jQuery(document).on("click", ".bup-note-edit", function(e) {
			
			e.preventDefault();	
			jQuery("#bup-spinner").show();	
			
			var note_id = jQuery(this).attr("bup-note-id");	
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_get_note_form",
					       "note_id": note_id},
					
					success: function(data){					
												
						jQuery("#bup-new-note-cont" ).html( data );	
						jQuery("#bup-new-note-cont" ).dialog( "open" );	
						jQuery("#bup-spinner").hide();	
						
												
						
						}
				});
			
			
			 // Cancel the default action
			
    		e.preventDefault();
			 
				
    });
	
	/* edit appointment */	
	jQuery( "#bup-appointment-edit-box" ).dialog({
			autoOpen: false,			
			width: '880', // overcomes width:'auto' and maxWidth bug
   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Close": function() {				
				jQuery("#bup-appointment-edit-box" ).html('');
				jQuery( this ).dialog( "close" );
			}			
			
			},
			close: function() {
				
				jQuery("#bup-appointment-edit-box" ).html('');
			
			
			}
	});
	
	
	
	jQuery(document).on("click", "#bup-adm-update-info", function(e) {
			
			e.preventDefault();	
			jQuery("#bup-spinner").show();
			
			
			var booking_id =  jQuery("#bup_appointment_id").val();	
			var serial_data = $('.bup-custom-field').serialize();
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_update_booking_info", "custom_fields": serial_data, "booking_id": booking_id},
					
					success: function(data){					
												
						jQuery("#bup-confirmation-cont" ).html( gen_message_infoupdate_conf);	 
						jQuery("#bup-confirmation-cont" ).dialog( "open" );	
						jQuery("#bup-spinner").hide();	
						
						
												
						
						}
				});
			
			
    		e.preventDefault();
			 
				
    });
	
	jQuery(document).on("click", "#bup-add-category-btn", function(e) {
			
			e.preventDefault();	
			jQuery("#bup-spinner").show();
			
			jQuery('#bup-service-add-category-box').dialog('option', 'title', bup_admin_v98.msg_category_add);
			
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_get_category_add_form"},
					
					success: function(data){					
												
						jQuery("#bup-service-add-category-box" ).html( data);	 
						jQuery("#bup-service-add-category-box" ).dialog( "open" );	
						jQuery("#bup-spinner").hide();	
						
						
												
						
						}
				});
			
			
    		e.preventDefault();
			 
				
    });
	
	jQuery(document).on("click", ".bup-edit-category-btn", function(e) {
			
			e.preventDefault();	
			jQuery("#bup-spinner").show();
			
			var category_id =  jQuery(this).attr("category-id");
			jQuery('#bup-service-add-category-box').dialog('option', 'title', bup_admin_v98.msg_category_edit);
			
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_get_category_add_form",
						"category_id": category_id},
					
					success: function(data){					
												
						jQuery("#bup-service-add-category-box" ).html( data);	 
						jQuery("#bup-service-add-category-box" ).dialog( "open" );	
						jQuery("#bup-spinner").hide();	
						
						
												
						
						}
				});
			
			
    		e.preventDefault();
			 
				
    });
	
	
	

	
	
	// on window resize run function
	$(window).resize(function () {
		//fluidDialog();
	});
	
	// catch dialog if opened within a viewport smaller than the dialog width
	$(document).on("dialogopen", ".ui-dialog", function (event, ui) {
		//fluidDialog();
	});
	
	function fluidDialog()
	 {
		var $visible = $(".ui-dialog:visible");
		// each open dialog
		$visible.each(function () 
		{
			var $this = $(this);
			
			var dialog = $this.find(".ui-dialog-content").data("dialog");
			
			// if fluid option == true
			if (dialog.options.fluid) {
				var wWidth = $(window).width();
				// check window width against dialog width
				if (wWidth < dialog.options.maxWidth + 50) {
					// keep dialog from filling entire screen
					$this.css("max-width", "90%");
				} else {
					// fix maxWidth bug
					$this.css("max-width", dialog.options.maxWidth);
				}
				//reposition dialog
				dialog.option("position", dialog.options.position);
			}
		});
	
	}


	/* open service form */	
	jQuery( "#bup-service-editor-box" ).dialog({
			autoOpen: false,																							
			width: 550,
			modal: true,
			buttons: {
			"Update": function() {				
				
				var service_id=   jQuery("#bup-service-id").val();
				var service_title=   jQuery("#bup-title").val();
				var service_duration=   jQuery("#bup-duration").val();
				var service_price=   jQuery("#bup-price" ).val();
				var service_price_2=   jQuery("#bup-price-2" ).val();
				var service_capacity =  jQuery("#bup-capacity" ).val();
				var service_category =  jQuery("#bup-category" ).val();
				var service_color =  jQuery("#bup-service-color" ).val();
				var service_font_color =  jQuery("#bup-service-font-color" ).val();
				
				var service_padding_before =  jQuery("#bup-padding-before" ).val();
				var service_padding_after =  jQuery("#bup-padding-after" ).val();
				
				var service_groups =  jQuery("#bup-groups" ).val();
				var service_calculation =  jQuery("#bup-groups-calculation" ).val();
				
				if(service_title==''){alert(bup_admin_v98.msg_service_input_title); return;}
				if(service_price==''){alert(bup_admin_v98.msg_service_input_price); return;}
				
				jQuery.ajax({
							type: 'POST',
							url: ajaxurl,
							data: {"action": "ubp_update_service",  "service_id": service_id ,
							"service_title": service_title,
							"service_duration": service_duration,
							"service_price": service_price,
							"service_price_2": service_price_2,
							"service_capacity": service_capacity,
							"service_category": service_category,
							"service_color": service_color,
							"service_font_color": service_font_color,
							"service_padding_before": service_padding_before,
							"service_padding_after": service_padding_after,
							"service_groups": service_groups,
							"service_calculation": service_calculation
														
							 },
							
							success: function(data){	
							
								jQuery("#bup-service-editor-box" ).dialog( "close" );				
								bup_load_services();
							
								
								
								}
						});
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	/* open category form */	
	jQuery( "#bup-service-add-category-box" ).dialog({
			autoOpen: false,																							
			width: 300,
			modal: true,
			buttons: {
			"Save": function() {
				
				var catetory_title=   jQuery("#but-category-name").val();
				var category_id=   jQuery("#bup_category_id").val();
				
				if(catetory_title==''){alert(err_message_category_name); return;}
				
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_add_category_confirm",
					"category_title": catetory_title,
					"category_id": category_id},
					
					success: function(data){		
								
						jQuery("#bup-spinner").hide();						
						jQuery("#bup-service-add-category-box" ).dialog( "close" );						
						bup_load_categories();
						
						
												
						
						}
				});				
				
				
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	
	//this adds the user and loads the user's details	
	jQuery(document).on("click", "#ubp-save-acc-settings-staff", function(e) {
			
			var staff_id =  jQuery(this).attr("ubp-staff-id");		
			
			var bup_per_backend_access=   jQuery("#bup_per_backend_access").val();
			var bup_upload_picture=   jQuery("#bup_upload_picture").val();			
			var bup_reschedule=   jQuery("#bup_reschedule").val();
			var bup_add_notes=   jQuery("#bup_add_notes").val();
			var bup_update_details=   jQuery("#bup_update_details").val();	
			var bup_profile_bg_color=   jQuery("#bup-profile-bg-color").val();			
				
			jQuery("#bup-err-message" ).html( '' );	
			jQuery("#bup-loading-animation-acc-setting-staff" ).show( );		
			
			
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_update_user_account_settings",
					"staff_id": staff_id,
					"bup_per_backend_access": bup_per_backend_access, "bup_upload_picture": bup_upload_picture ,
					"bup_reschedule": bup_reschedule, "bup_reschedule": bup_reschedule ,
					"bup_add_notes": bup_add_notes, "bup_update_details": bup_update_details
					, "bup_profile_bg_color": bup_profile_bg_color 
					 
					 },
					
					success: function(data){
						
						
						var res = data;		
						
						jQuery("#bup-err-message" ).html( res );						
						jQuery("#bup-loading-animation-acc-setting-staff" ).hide( );	
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
	 // This sends a reset link to a staff member
	jQuery(document).on("click", "#ubp-save-acc-send-reset-link-staff", function(e) {
			
			var staff_id =  jQuery(this).attr("ubp-staff-id");		
						
			jQuery("#bup-err-message" ).html( '' );	
			jQuery("#bup-loading-animation-acc-resetlink-staff" ).show( );		
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_send_welcome_email_to_staff",
					"staff_id": staff_id
					 
					 },
					
					success: function(data){					
						
						var res = data;	
						jQuery("#bup-loading-animation-acc-resetlink-staff" ).hide( );						
						jQuery("#bup-acc-resetlink-staff-message" ).html( res );						
							
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
	

		
	//this adds the user and loads the user's details	
	jQuery(document).on("click", "#ubp-save-glogal-business-hours", function(e) {
			
			e.preventDefault();			
			
			var bup_mon_from=   jQuery("#bup-mon-from").val();
			var bup_mon_to=   jQuery("#bup-mon-to").val();			
			var bup_tue_from=   jQuery("#bup-tue-from").val();
			var bup_tue_to=   jQuery("#bup-tue-to").val();			
			var bup_wed_from=   jQuery("#bup-wed-from").val();
			var bup_wed_to=   jQuery("#bup-wed-to").val();			
			var bup_thu_from=   jQuery("#bup-thu-from").val();
			var bup_thu_to=   jQuery("#bup-thu-to").val();			
			var bup_fri_from=   jQuery("#bup-fri-from").val();
			var bup_fri_to=   jQuery("#bup-fri-to").val();			
			var bup_sat_from=   jQuery("#bup-sat-from").val();
			var bup_sat_to=   jQuery("#bup-sat-to").val();			
			var bup_sun_from=   jQuery("#bup-sun-from").val();
			var bup_sun_to=   jQuery("#bup-sun-to").val();
			
			
			
				
			jQuery("#bup-err-message" ).html( '' );	
			jQuery("#bup-loading-animation-business-hours" ).show( );		
			
			
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_update_global_business_hours", 
					"bup_mon_from": bup_mon_from, "bup_mon_to": bup_mon_to ,
					"bup_tue_from": bup_tue_from, "bup_tue_to": bup_tue_to ,
					"bup_wed_from": bup_wed_from, "bup_wed_to": bup_wed_to ,
					"bup_thu_from": bup_thu_from, "bup_thu_to": bup_thu_to ,
					"bup_fri_from": bup_fri_from, "bup_fri_to": bup_fri_to ,
					"bup_sat_from": bup_sat_from, "bup_sat_to": bup_sat_to ,
					"bup_sun_from": bup_sun_from, "bup_sun_to": bup_sun_to ,
					 
					 },
					
					success: function(data){
						
						
						var res = data;		
						
						jQuery("#bup-err-message" ).html( res );						
						jQuery("#bup-loading-animation-business-hours" ).hide( );		
						
						
						
						
						}
				});
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
		//this adds the user and loads the user's details	
	jQuery(document).on("click", ".bup_restore_template", function(e) {
			
			
			var template_id =  jQuery(this).attr("b-template-id");
			jQuery("#email_template").val(template_id);
			jQuery("#reset_email_template").val('yes');
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "reset_email_template", 
					"email_template": template_id					
					
					 
					 },
					
					success: function(data){
						
						
						var res = data;								
						$("#b_frm_settings").submit();				
						
						
						}
				});
			
			
			
			
			
			
			 
				
        });
		
		//this adds the user and loads the user's details	
	jQuery(document).on("click", "#ubp-save-glogal-business-hours-staff", function(e) {
			
			e.preventDefault();			
			
			var staff_id =  jQuery(this).attr("ubp-staff-id");
			
			var bup_mon_from=   jQuery("#bup-mon-from").val();
			var bup_mon_to=   jQuery("#bup-mon-to").val();			
			var bup_tue_from=   jQuery("#bup-tue-from").val();
			var bup_tue_to=   jQuery("#bup-tue-to").val();			
			var bup_wed_from=   jQuery("#bup-wed-from").val();
			var bup_wed_to=   jQuery("#bup-wed-to").val();			
			var bup_thu_from=   jQuery("#bup-thu-from").val();
			var bup_thu_to=   jQuery("#bup-thu-to").val();			
			var bup_fri_from=   jQuery("#bup-fri-from").val();
			var bup_fri_to=   jQuery("#bup-fri-to").val();			
			var bup_sat_from=   jQuery("#bup-sat-from").val();
			var bup_sat_to=   jQuery("#bup-sat-to").val();			
			var bup_sun_from=   jQuery("#bup-sun-from").val();
			var bup_sun_to=   jQuery("#bup-sun-to").val();			
				
			jQuery("#bup-err-message" ).html( '' );	
			jQuery("#bup-loading-animation-business-hours" ).show( );		
			
			
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_update_staff_business_hours", 
					"staff_id": staff_id,					
					"bup_mon_from": bup_mon_from, "bup_mon_to": bup_mon_to ,
					"bup_tue_from": bup_tue_from, "bup_tue_to": bup_tue_to ,
					"bup_wed_from": bup_wed_from, "bup_wed_to": bup_wed_to ,
					"bup_thu_from": bup_thu_from, "bup_thu_to": bup_thu_to ,
					"bup_fri_from": bup_fri_from, "bup_fri_to": bup_fri_to ,
					"bup_sat_from": bup_sat_from, "bup_sat_to": bup_sat_to ,
					"bup_sun_from": bup_sun_from, "bup_sun_to": bup_sun_to ,
					 
					 },
					
					success: function(data){
						
						
						var res = data;		
						
						jQuery("#bup-err-message" ).html( res );						
						jQuery("#bup-loading-animation-business-hours" ).hide( );		
						
						
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
	
	    var $form   = $('#business-hours');
		jQuery(document).on("change", ".bup_select_start", function(e) {	
			
			var $row = $(this).parent(),
				$end_select = $('.bup_select_end', $row),
				$start_select = $(this);
	
			if ($start_select.val()) {
				$end_select.show();
				$('span', $row).show();
	
				var start_time = $start_select.val();
	
				$('span > option', $end_select).each(function () {
					$(this).unwrap();
				});
	
				// Hides end time options with value less than in the start time
				$('option', $end_select).each(function () {
					if ($(this).val() <= start_time) {
						
						$(this).wrap("<span>").parent().hide();
					}
				});
				
			
				if (start_time >= $end_select.val()) {
					$('option:visible:first', $end_select).attr('selected', true);
				}
			} else { // OFF
			
				$end_select.hide();
				$('span', $row).hide();
			}
			
		}).each(function () {
			var $row = $(this).parent(),
				$end_select = $('.bup_select_end', $row);
	
			$(this).data('default_value', $(this).val());
			$end_select.data('default_value', $end_select.val());
	
			// Hides end select for "OFF" days
			if (!$(this).val()) {
				$end_select.hide();
				$('span', $row).hide();
			}
		}).trigger('change');

	
	
	//this adds the user and loads the user's details	
	jQuery(document).on("click", "#ubp-edit-staff-service-btn", function(e) {
			
			e.preventDefault();
			
			
			var staff_id=   jQuery("#staff_id").val();
				
			jQuery("#bup-err-message" ).html( '' );		
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_add_staff_confirm", "staff_name": staff_name, "staff_email": staff_email , "staff_nick": staff_nick },
					
					success: function(data){
						
						
						var res = data;						
															
					     
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
	/* 	Delete Service */
    jQuery(document).on("click", ".ubp-service-delete", function(e) {
    
	e.preventDefault();
		
		var doIt = false;
		
		doIt=confirm(bup_admin_v98.msg_service_delete);
		  
		  if(doIt)
		  {
			  
			  var service_id =  jQuery(this).attr("service-id");	
			 
			  jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_delete_service", 
						"service_id": service_id  },
						
						success: function(data){
							
							bup_load_services();							
							
							
						}
					});
			
		  }
		  else{
			
		  }		
		
				
		return false;
	});
		
	
	/* 	Delete category */
    jQuery(document).on("click", ".ubp-category-delete", function(e) {
		e.preventDefault();
		
		var doIt = false;
		
		doIt=confirm(bup_admin_v98.msg_cate_delete);
		  
		  if(doIt)
		  {
			  
			  var cate_id =  jQuery(this).attr("category-id");	
			 
			  jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_delete_category", 
						"cate_id": cate_id  },
						
						success: function(data){
							
							bup_load_categories();							
							
							
						}
					});
			
		  }
		  else{
			
		  }		
		
				
		return false;
	});
		
	function isInteger(x) {
        return x % 1 === 0;
    }
	
	
	jQuery(document).on("click", "#ubp-add-staff-btn", function(e) {
			
			e.preventDefault();	
			
			jQuery("#bup-spinner").show();		
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_get_new_staff" },
					
					success: function(data){								
					
						jQuery("#bup-staff-editor-box" ).html( data );							
						jQuery("#bup-staff-editor-box" ).dialog( "open" );
						jQuery("#bup-spinner").hide();
							
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
	
	
	jQuery(document).on("click", ".ubp-break-delete-btn", function(e) {
			
			e.preventDefault();		
			
			var break_id =  jQuery(this).attr("break-id");
			var day_id =  jQuery(this).attr("day-id");
			var staff_id =  jQuery("#staff_id" ).val();	
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_delete_break",
					"break_id": break_id,
					"staff_id": staff_id },
					
					success: function(data){
						
						bup_reload_staff_breaks (staff_id , day_id)							
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
		
		jQuery(document).on("click", ".ubp-daysoff-delete-btn", function(e) {
			
			e.preventDefault();		
			
			var dayoff_id =  jQuery(this).attr("dayoff-id");
			var staff_id =  jQuery("#staff_id" ).val();	
			
			jQuery("#bup-spinner").show();
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_delete_dayoff",
					"dayoff_id": dayoff_id,
					"staff_id": staff_id },
					
					success: function(data){
						
						bup_reload_days_off (staff_id);
						jQuery("#bup-spinner").hide();						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
		
		jQuery(document).on("click", ".ubp-specialschedule-delete-btn", function(e) {
			
			e.preventDefault();		
			
			var schedule_id =  jQuery(this).attr("dayoff-id");
			var staff_id =  jQuery("#staff_id" ).val();	
			
			jQuery("#bup-spinner").show();
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_delete_special_schedule",
					"schedule_id": schedule_id,
					"staff_id": staff_id },
					
					success: function(data){
						
						bup_reload_special_schedule (staff_id);
						jQuery("#bup-spinner").hide();						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
	
	jQuery(document).on("click", "#bup-btn-add-staff-day-off-confirm", function(e) {
			
			e.preventDefault();		
			
			var staff_id =  jQuery("#staff_id" ).val();			
			var day_off_from =  jQuery("#bup-start-date" ).val();
			var day_off_to =  jQuery("#bup-end-date" ).val();
			
			jQuery("#bup-dayoff-message-add").html( message_wait_availability );	
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_dayoff_add_confirm",
					"day_off_from": day_off_from,
					"day_off_to": day_off_to,
					"staff_id": staff_id },
					
					success: function(data){
						
						bup_reload_days_off (staff_id);
						jQuery("#bup-dayoff-message-add").html(data);
													
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
	jQuery(document).on("click", "#bup-btn-add-staff-special-schedule-confirm", function(e) {
			
			e.preventDefault();		
			
			var staff_id =  jQuery("#staff_id" ).val();	
			
			var day_available =  jQuery("#bup-special-schedule-date" ).val();					
			var time_from =  jQuery("#bup-special-schedule-from" ).val();
			var time_to =  jQuery("#bup-special-schedule-to" ).val();
			
			jQuery("#bup-speschedule-message-add").html( message_wait_availability );			
			jQuery("#bup-spinner").show();	
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_special_schedule_add_confirm",
					"time_from": time_from,
					"time_to": time_to,
					"day_available": day_available,
					"staff_id": staff_id },
					
					success: function(data){
						
						bup_reload_special_schedule (staff_id);
						jQuery("#bup-speschedule-message-add").html(data);						
						jQuery("#bup-spinner").hide();
													
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
	
	jQuery(document).on("click", "#bup-add-service-btn", function(e) {
			
			e.preventDefault();
			
			var service_id =  jQuery(this).attr("service-id");
			var cate_id =  jQuery("#cate_id" ).val();
			
			jQuery('#bup-service-editor-box').dialog('option', 'title', bup_admin_v98.msg_service_add);			
			jQuery("#bup-spinner").show();
				
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_get_service",  "service_id": service_id ,  "cate_id": cate_id },
					
					success: function(data){		
					
					
						jQuery("#bup-service-editor-box" ).html( data );							
						jQuery("#bup-service-editor-box" ).dialog( "open" );
						jQuery('.color-picker').wpColorPicker();
						
						jQuery("#bup-spinner").hide();	
						
						
						}
				});
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
	

	
	jQuery(document).on("click", ".bup-admin-edit-service", function(e) {
			
			e.preventDefault();
			
			var service_id =  jQuery(this).attr("service-id");
			var cate_id =  jQuery("#cate_id" ).val();			
			jQuery('#bup-service-editor-box').dialog('option', 'title', bup_admin_v98.msg_service_edit);
			
			jQuery("#bup-spinner").show();
				
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_get_service",  "service_id": service_id ,  "cate_id": cate_id },
					
					success: function(data){					
					
						jQuery("#bup-service-editor-box" ).html( data );							
						jQuery("#bup-service-editor-box" ).dialog( "open" );
						jQuery('.color-picker').wpColorPicker();					
						jQuery("#bup-spinner").hide();	
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
		jQuery(document).on("click", ".bup-staff-load", function(e) {
			
			e.preventDefault();
			
			var staff_id =  jQuery(this).attr("staff-id");			
			bup_load_staff_member(staff_id);	
				
    		
    		e.preventDefault();
			 
				
        });
		
				
		jQuery(document).on("click", ".ubp-service-cate", function(e) {
			
			
			var ischecked = $(this).is(":checked");			
			var service_id = $(this).val();
			
			if(ischecked)
			{
				 $("#bup-price-"+service_id).prop("disabled",false);	
				 $("#bup-qty-"+service_id).prop("disabled",false);	
			
			}else{
				
				$("#bup-price-"+service_id).prop("disabled",true);	
				$("#bup-qty-"+service_id).prop("disabled",true);	
			}
			
		});
		
		
		jQuery(document).on("click", "#bup-admin-edit-staff-service-save", function(e) {
			
			e.preventDefault();
			
			var staff_id =  jQuery('#staff_id').val();
			var service_list = ubp_get_checked_services();
			
			jQuery("#bup-loading-animation-services" ).html( message_wait_availability );	
				
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_update_staff_services",  "service_list": service_list,  "staff_id": staff_id },
					
					success: function(data){
						
						jQuery("#bup-loading-animation-services" ).html('');			
					
					
						
						}
				});
			
			
    		e.preventDefault();
			 
				
        });
		
		jQuery(document).on("click", "#bup-admin-edit-staff-location-save", function(e) {
			
			e.preventDefault();
			
			var staff_id =  jQuery('#staff_id').val();
			var location_list = ubp_get_checked_locations();
			
			jQuery("#bup-loading-animation-services" ).html( message_wait_availability );	
				
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_update_staff_locations",  "location_list": location_list,  "staff_id": staff_id },
					
					success: function(data){
						
						jQuery("#bup-loading-animation-services" ).html('');			
					
					
						
						}
				});
			
			
    		e.preventDefault();
			 
				
        });
		
		
		
		
		function ubp_get_checked_services ()	
		{
			
			var checkbox_value = "";
			jQuery(".ubp-cate-service-checked").each(function () {
				
				var ischecked = $(this).is(":checked");
			   
				if (ischecked) 
				{
					//get price and quantity
					var bup_price = jQuery("#bup-price-"+$(this).val()).val();
					var bup_qty = jQuery("#bup-qty-"+$(this).val()).val();
					checkbox_value += $(this).val() + "-" + bup_price + "-" + bup_qty + "|";
				}
				
				
			});
			
			return checkbox_value;		
		}
		
		function ubp_get_checked_locations ()	
		{
			
			var checkbox_value = "";
			jQuery(".ubp-location-checked").each(function () {
				
				var ischecked = $(this).is(":checked");
			   
				if (ischecked) 
				{
					
					checkbox_value += $(this).val()+ "|";
				}
				
				
			});
			
			return checkbox_value;		
		}
		
		
		
		/* 	FIELDS CUSTOMIZER -  restore default */
	jQuery('#bup-restore-fields-btn').on('click',function(e)
	{
		
		e.preventDefault();
		
		doIt=confirm(custom_fields_reset_confirmation);
		  
		  if(doIt)
		  {
			
			var uultra_custom_form = jQuery('#uultra__custom_registration_form').val();
			  
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "custom_fields_reset", 
						"p_confirm": "yes"  , 		"bup_custom_form": uultra_custom_form },
						
						success: function(data){
							
							jQuery("#fields-mg-reset-conf").slideDown();			
						
							 window.location.reload();						
							
							
							}
					});
			
		  }
			
					
		return false;
	});
	
	/* edit pricing */	
	jQuery( "#bup-service-variable-pricing-box" ).dialog({
			autoOpen: false,			
			width: '400px', // overcomes width:'auto' and maxWidth bug
   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {
			"Save & Exit": function() {				
				
				var pricing_list = ubp_get_flexible_pricing_values();				
				var service_id =  jQuery('#bup_pricing_service_id').val();
				
				
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_update_group_pricing_table", "pricing_list": pricing_list , "service_id": service_id },
					
					success: function(data){
						
						jQuery("#bup-service-variable-pricing-box" ).dialog( "close" );
												
																
						
					}
				});
				
			},
			
			"Close": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	function ubp_get_flexible_pricing_values ()	
		{
			
			var checkbox_value = "";
			jQuery(".bup-servicepricing-textbox").each(function () {					
				
				var person_pricing =  $(this).val();
			   
				if (person_pricing!='') 
				{
					checkbox_value += person_pricing + "|";
				}
				
				
			});
			
			
			return checkbox_value;		
		}
	
	jQuery(document).on("click", ".bup-admin-edit-pricing", function(e) {
			
			
			
			var service_id =  jQuery(this).attr("service-id");
			
			jQuery("#bup-spinner").show();
			
	
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_get_service_pricing", "service_id": service_id },
					
					success: function(data){
						
						jQuery("#bup-service-variable-pricing-box" ).html( data );
						jQuery("#bup-service-variable-pricing-box" ).dialog( "open" );
						jQuery("#bup-spinner").hide();
												
																
						
					}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
	
	
	
	
	/* 	WIDGETS CUSTOMIZER -  Close Open Widget */
	jQuery('#bup-staff-details').on('click','.bup-widgets-icon-close-open, .bup-staff-details-header',function(e)
	{
		
		e.preventDefault();
		var widget_id =  jQuery(this).attr("widget-id");		
		var iconheight = 20;
        
        
		
		
		if(jQuery("#bup-widget-adm-cont-id-"+widget_id).is(":visible")) 
	  	{
			
			jQuery("#bup-widgets-icon-close-open-id-"+widget_id).css('background-position', '0px 0px');
			
			
			
		}else{
			
			jQuery("#bup-widgets-icon-close-open-id-"+widget_id).css('background-position', '0px -'+iconheight+'px');			
	 	 }
		
		
		jQuery("#bup-widget-adm-cont-id-"+widget_id).slideToggle();	
					
		return false;
	});
	
	/* 	FIELDS CUSTOMIZER -  ClosedEdit Field Form */
	jQuery('.uultra-btn-close-edition-field').on('click',function(e)
	{
		
		e.preventDefault();
		var block_id =  jQuery(this).attr("data-edition");		
		jQuery("#uu-edit-fields-bock-"+block_id).slideUp();				
		return false;
	});
	
	
	function reload_full_callendar_bup()
	{
		
		
		
		
	}
	
			
	

	
});





function bup_reload_staff_breaks (staff_id , day_id)	
{
	
	jQuery.post(ajaxurl, {
							action: 'bup_get_current_staff_breaks',
							'staff_id': staff_id,
							'day_id': day_id
									
							}, function (response){									
																
							jQuery("#bup-break-adm-cont-id-"+day_id).html(response);
							
							//jQuery("#bup-spinner").hide();
							
		 });
}

function bup_reload_special_schedule (staff_id)	
{
	
	jQuery.post(ajaxurl, {
							action: 'bup_get_staff_special_schedule_list',
							'staff_id': staff_id
							
									
							}, function (response){									
																
							jQuery("#bup-staff-special-schedule-list").html(response);
							
							//jQuery("#bup-spinner").hide();
							
		 });
}

function bup_reload_days_off (staff_id)	
{
	
	jQuery.post(ajaxurl, {
							action: 'bup_get_staff_daysoff',
							'staff_id': staff_id
							
									
							}, function (response){									
																
							jQuery("#bup-staff-daysoff-list").html(response);
							
							//jQuery("#bup-spinner").hide();
							
		 });
}


function bup_load_categories ()	
	{
		jQuery("#bup-spinner").show();
		  jQuery.post(ajaxurl, {
							action: 'display_categories'
									
							}, function (response){									
																
							jQuery("#bup-categories-list").html(response);							
							jQuery("#bup-spinner").hide();
							
		 });
}

function bup_load_services (category_id)	
{
		jQuery("#bup-spinner").show();
		
		jQuery.post(ajaxurl, {
							action: 'display_admin_services',
							'cate_id': category_id
									
							}, function (response){									
																
							jQuery("#bup-services-list").html(response);							
							jQuery("#bup-spinner").hide();
							
		 });
}


function bup_load_staff_member (staff_id)	
	{
		jQuery("#bup-spinner").show();
		  jQuery.post(ajaxurl, {
							action: 'ubp_get_staff_details_ajax', 'staff_id': staff_id
									
							}, function (response){									
																
							jQuery("#bup-staff-details" ).html( response );							
							bup_rebuild_dom_date_picker();	
							jQuery('.color-picker').wpColorPicker();												
							jQuery("#bup-spinner").hide();
							
		 });
}


function bup_rebuild_dom_date_picker ()	
{
	var uultra_date_format =  jQuery('#uultra_date_format').val();			
							if(uultra_date_format==''){uultra_date_format='dd/mm/yy'}	
						
							jQuery( ".bupro-datepicker" ).datepicker({ 
								showOtherMonths: true, 
								dateFormat: bup_admin_v98.bb_date_picker_format, 
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
	
}

function get_disabled_modules_list ()	
{
	
	var checkbox_value = "";
    jQuery(".uultra-my-modules-checked").each(function () {
		
        var ischecked = $(this).is(":checked");
       
	    if (ischecked) 
		{
            checkbox_value += $(this).val() + "|";
        }
		
		
    });
	
	return checkbox_value;		
}

function sortable_user_menu()
{
	 var itemList = jQuery('#uultra-user-menu-option-list');
	 
	 itemList.sortable({
		  cursor: 'move',
          update: function(event, ui) {
           // $('#loading-animation').show(); // Show the animate loading gif while waiting

            opts = {
                url: ajaxurl, // ajaxurl is defined by WordPress and points to /wp-admin/admin-ajax.php
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                data:{
                    action: 'uultra_sort_user_menu_ajax', // Tell WordPress how to handle this ajax request
                    order: itemList.sortable('toArray').toString() // Passes ID's of list items in  1,3,2 format
                },
                success: function(response) {
                   // $('#loading-animation').hide(); // Hide the loading animation
				   uultra_reload_user_menu_customizer();
				  				   
                    return; 
                },
                error: function(xhr,textStatus,e) {  // This can be expanded to provide more information
                    alert(e);
                    // alert('There was an error saving the updates');
                  //  $('#loading-animation').hide(); // Hide the loading animation
                    return; 
                }
            };
            jQuery.ajax(opts);
        }
    }); 
	
}

function bup_reload_custom_fields_set ()	
{
	
	jQuery("#bup-spinner").show();
	
	 var uultra_custom_form =  jQuery('#uultra__custom_registration_form').val();
		
		jQuery.post(ajaxurl, {
							action: 'bup_reload_custom_fields_set', 'bup_custom_form': uultra_custom_form
									
							}, function (response){									
																
							jQuery("#uu-fields-sortable").html(response);							
							sortable_fields_list();
							
							jQuery("#bup-spinner").hide();
							
																
														
		 });
		
}
function sortable_fields_list ()
{
	var itemList = jQuery('#uu-fields-sortable');	 
	var uultra_custom_form =  jQuery('#uultra__custom_registration_form').val();
   
    itemList.sortable({
		cursor: 'move',
        update: function(event, ui) {
        jQuery("#bup-spinner").show(); // Show the animate loading gif while waiting

            opts = {
                url: ajaxurl, // ajaxurl is defined by WordPress and points to /wp-admin/admin-ajax.php
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                data:{
                    action: 'sort_fileds_list', // Tell WordPress how to handle this ajax request
					bup_custom_form: uultra_custom_form, // Tell WordPress how to handle this ajax request
                    order: itemList.sortable('toArray').toString() // Passes ID's of list items in  1,3,2 format
                },
                success: function(response) {
                   // $('#loading-animation').hide(); // Hide the loading animation
				   bup_reload_custom_fields_set();
                    return; 
                },
                error: function(xhr,textStatus,e) {  // This can be expanded to provide more information
                    alert(e);
                    // alert('There was an error saving the updates');
                  //  $('#loading-animation').hide(); // Hide the loading animation
                    return; 
                }
            };
            jQuery.ajax(opts);
        }
    }); 
	
	
}



function bup_load_appointment_payments(appointment_id)	
{					
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_get_payments_list",  "appointment_id": appointment_id},
					
					success: function(data){						
						
						var res = data;						
						jQuery("#bup-payments-cont-res").html(res);					    
						

						}
				});	
	
}

function bup_load_appointment_notes(appointment_id)	
{					
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_get_notes_list",  "appointment_id": appointment_id},
					
					success: function(data){						
						
						var res = data;						
						jQuery("#bup-notes-cont-res").html(res);					    
						

						}
				});	
	
}

function bup_load_staff_under_category(appointment_id)	
{
	
	var b_category=   jQuery("#bup-category").val();
							
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "get_cate_dw_admin_ajax", "b_category": b_category, "appointment_id": appointment_id},
					
					success: function(data){						
						
						var res = data;						
						jQuery("#bup-staff-booking-list").html(res);					    
						

						}
				});	
	
}

function bup_load_staff_adm(staff_id )	
{

	setTimeout("bup_load_staff_list_adm()", 1000);
	setTimeout("bup_load_staff_details(" + staff_id +")", 1000);
	
}

function bup_load_staff_list_adm()	
{
	jQuery("#bup-spinner").show();
	
    jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_get_staff_list_admin_ajax"},
					
					success: function(data){					
						
						var res = data;						
						jQuery("#bup-staff-list").html(res);
						jQuery("#bup-spinner").hide();					    
						
												

						}
				});	
	
}

function bup_load_staff_details(staff_id)	
{
	jQuery("#bup-spinner").show();	
    jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_get_staff_details_admin", "staff_id": staff_id},
					
					success: function(data){					
						
						var res = data;						
						jQuery("#bup-staff-details").html(res);					
						jQuery( "#tabs-bupro" ).tabs({collapsible: false	});						
						jQuery("#bup-spinner").hide();	
						
						bup_rebuild_dom_date_picker();	
						jQuery('.color-picker').wpColorPicker();									    
						

						}
				});	
	
}



function bup_edit_appointment_inline(appointment_id, conf_message, show_conf_message)	
{
	
	jQuery("#bup-spinner").show();
	
	jQuery.ajax({
				  type: 'POST',
				  url: ajaxurl,
				  data: {"action": "bup_admin_edit_appointment", 
				         "appointment_id": appointment_id},
						
					success: function(data){					
														
							jQuery("#bup-appointment-edit-box" ).html( data );
							jQuery("#bup-appointment-edit-box" ).dialog( "open" );
							
							var uultra_date_format =  jQuery('#uultra_date_format').val();			
							if(uultra_date_format==''){uultra_date_format='dd/mm/yy'}	
						
							jQuery( ".bupro-datepicker" ).datepicker({ 
								showOtherMonths: true, 
								dateFormat: bup_admin_v98.bb_date_picker_format, 
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
							jQuery("#bup-spinner").hide();						
							
							//load staff							
							bup_load_staff_under_category(appointment_id);							
							setTimeout("bup_load_appointment_payments(" + appointment_id +")", 1000);
							setTimeout("bup_load_appointment_notes(" + appointment_id +")", 1000);
							
							
							if(show_conf_message=='yes')
							{
							jQuery("#bup-new-app-conf-message" ).html( conf_message );
							jQuery("#bup-new-app-conf-message" ).dialog( "open" );
							
							}
							
													
							
							
							
					}
	});
	
	
}

function hidde_noti (div_d)
{
		jQuery("#"+div_d).slideUp();		
		
}
