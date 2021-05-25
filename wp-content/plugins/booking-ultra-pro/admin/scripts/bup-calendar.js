jQuery(document).ready(function($) {
	
	
	jQuery(document).on("click", ".ubp-appo-change-status", function(e) {
			
			e.preventDefault();	
			jQuery("#bup-spinner").show();	
			
			
			var appointment_id = jQuery(this).attr("appointment-id");			
			var appointment_status =  jQuery(this).attr("appointment-status");
			var bup_type =  jQuery(this).attr("bup-type");	
			var bup_status = jQuery(this).attr("bup-status");		
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_update_appointment_status",
					"appointment_id": appointment_id,
					"appointment_status": appointment_status},
					
					success: function(data){					
												
						//reload appointment list						
						
						jQuery.ajax({
								type: 'POST',
								url: ajaxurl,
								data: {"action": "bup_get_appointments_quick",
								"status": bup_status,
								"type": bup_type},
								
								success: function(data){					
															
									jQuery("#bup-appointment-list" ).html( data );
									//$fullCalendar.fullCalendar( 'refetchEvents' );													
															
									
								}
							});
						
												
						
						}
				});
			
			
			 // Cancel the default action
			
    		e.preventDefault();
			 
				
    });
	
	
	
	
	
	jQuery(document).on("click", ".bup-appointment-edit-module", function(e) {
			
			e.preventDefault();	
			jQuery("#bup-spinner").show();				
			
			var appointment_id = jQuery(this).attr("appointment-id");		
			bup_edit_appointment_inline(appointment_id,null,'no');		
			
    		e.preventDefault();
			 
				
    });
	
	jQuery(document).on("click", ".bup-appointment-delete-module", function(e) {
			
			e.preventDefault();	
			
			
			if (confirm(BuproL10n.are_you_sure)) {
				
				jQuery("#bup-spinner").show();				
				
				var appointment_id = jQuery(this).attr("appointment-id");	
					
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_delete_appointment",
						"appointment_id": appointment_id},
						
						success: function(data){	
						
						window.location.reload();				
													
												
													
							
							}
					});	
				
				
				}	
			
    		e.preventDefault();
			 
				
    });
	
	
	jQuery(document).on("click", ".ubp-payment-change-status", function(e) {
			
			e.preventDefault();	
			jQuery("#bup-spinner").show();				
			
			var payment_id = jQuery(this).attr("payment-id");			
			var order_status =  jQuery(this).attr("order-status");
			var bup_type =  jQuery(this).attr("bup-type");	
			var bup_status = jQuery(this).attr("bup-status");		
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_update_payment_status_inline",
					"payment_id": payment_id,
					"order_status": order_status},
					
					success: function(data){					
												
						jQuery.ajax({
								type: 'POST',
								url: ajaxurl,
								data: {"action": "bup_get_appointments_quick",
								"status": bup_status,
								"type": bup_type},
								
								success: function(data){				
															
									jQuery("#bup-appointment-list" ).html( data );
									//$fullCalendar.fullCalendar( 'refetchEvents' );								
									
								}
							});
						
												
						
						}
				});
			
			
    		e.preventDefault();
			 
				
    });
	
	
	jQuery(document).on("click", ".bup-adm-see-appoint-list-quick", function(e) {
			
			e.preventDefault();	
			jQuery("#bup-spinner").show();	
			
			
			var bup_status = jQuery(this).attr("bup-status");			
			var bup_type =  jQuery(this).attr("bup-type");	
			
			if(bup_type=='bystatus' && bup_status==0){jQuery('#bup-appointment-list').dialog('option', 'title', BuproL10n.msg_quick_list_pending_appointments);}
			
			if(bup_type=='bystatus' && bup_status==2){jQuery('#bup-appointment-list').dialog('option', 'title', BuproL10n.msg_quick_list_cancelled_appointments);}
			
			if(bup_type=='bystatus' && bup_status==3){jQuery('#bup-appointment-list').dialog('option', 'title', BuproL10n.msg_quick_list_noshow_appointments);}
			
			if(bup_type=='byunpaid'){jQuery('#bup-appointment-list').dialog('option', 'title', BuproL10n.msg_quick_list_unpaid_appointments);}
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_get_appointments_quick",
					"status": bup_status,
					"type": bup_type},
					
					success: function(data){					
												
						jQuery("#bup-appointment-list" ).html( data );	
						jQuery("#bup-appointment-list" ).dialog( "open" );	
						jQuery("#bup-spinner").hide();	
						
												
						
						}
				});
			
			
			
    		e.preventDefault();
			 
				
    });
	
		
	/* check appointments */	
	jQuery( "#bup-appointment-list" ).dialog({
			autoOpen: false,			
			width: '400', //   			
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
	
	jQuery(document).on("click", "#bup-adm-confirm-reschedule-btn", function(e) {
			
			e.stopPropagation();	
			
			var date_to_book =  jQuery("#bup_booking_date").val();
			var notify_client =  jQuery("#bup_notify_client_reschedule").val();
			var service_and_staff_id =  jQuery("#bup_service_staff").val();
			var time_slot =  jQuery("#bup_time_slot").val();
			var booking_id =  jQuery("#bup_appointment_id").val();		
			
			if(time_slot==''){alert(err_message_time_slot); return;}			
			if(jQuery("#bup-category").val()==''){alert(err_message_service); return;}
			if(jQuery("#bup-start-date").val()==''){alert(err_message_start_date); return;}
			
			jQuery("#bup-steps-cont-res").html(message_wait_availability);
					
				
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_appointment_confirm_reschedule", 
						   "bup_booking_date": date_to_book,
						   "bup_service_staff": service_and_staff_id,
						   "bup_time_slot": time_slot,
						   "booking_id": booking_id,
						   "notify_client": notify_client},
					
					success: function(data){						
						
						var res = data;							
						jQuery("#bup-steps-cont-res-edit").html(res);						
						//$fullCalendar.fullCalendar( 'refetchEvents' );
						
						jQuery("#bup-confirmation-cont" ).html( gen_message_rescheduled_conf );
						jQuery("#bup-confirmation-cont" ).dialog( "open" );
						
										
											

						}
				});				
			
				
			
    		e.stopPropagation(); 
				
    });
	
	jQuery(document).on("click", "#bup-adm-update-appoint-status-btn", function(e) {
			
			e.preventDefault();		
				
			var appointment_id =  jQuery(this).attr("appointment-id");
			jQuery("#bup-spinner").show();						
				
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_appointment_status_options", 
						   "appointment_id": appointment_id
						  },
					
					success: function(data){						
						
												
						jQuery("#bup-appointment-change-status" ).html( data );
						jQuery("#bup-appointment-change-status" ).dialog( "open" );						
						jQuery("#bup-spinner").hide();
						
										
											

						}
				});				
			
				
			
    		e.stopPropagation(); 
				
    });
	
	
	
	
	jQuery(document).on("click", ".bup-adm-change-appoint-status-opt", function(e) {
			
			e.preventDefault();		
				
			var appointment_id =  jQuery(this).attr("appointment-id");
			var appointment_status =  jQuery(this).attr("appointment-status");
			
			jQuery("#bup-spinner").show();						
				
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_update_appo_status_ed", 
						   "appointment_id": appointment_id,
						   "appointment_status": appointment_status
						  },
					
					success: function(data){			
							
					    jQuery("#bup-app-status" ).html( data );
						jQuery("#bup-spinner").hide();	
						//$fullCalendar.fullCalendar( 'refetchEvents' );	
						jQuery("#bup-appointment-change-status" ).dialog( "close" );											
										
										
											

						}
				});				
			
				
			
    		e.stopPropagation(); 
				
    });	
	/* open new appointment */	
	jQuery( "#bup-appointment-new-box" ).dialog({
			autoOpen: false,			
			width: '780', // overcomes width:'auto' and maxWidth bug
   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Cancel": function() {	
			
				jQuery("#bup-appointment-new-box" ).html( '' );								
				jQuery( this ).dialog( "close" );
			},
			
			"Create": function() {				
				
				var bup_time_slot=   jQuery("#bup_time_slot").val();
				var bup_booking_date=   jQuery("#bup_booking_date").val();
				var bup_client_id=   jQuery("#bup_client_id").val();
				var bup_service_staff=   jQuery("#bup_service_staff").val();
				var bup_notify_client=   jQuery("#bup_notify_client").val();
				
				
				if(jQuery("#bup-category").val()==''){alert(err_message_service); return;}
				if(jQuery("#bup-start-date").val()==''){alert(err_message_start_date); return;}
				if(bup_client_id==''){alert(err_message_client); return;}	
				if(bup_time_slot==''){alert(err_message_time_slot); return;}					
				
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_admin_new_appointment_confirm", 
						       "bup_time_slot": bup_time_slot,
							   "bup_booking_date": bup_booking_date,
							   "bup_client_id": bup_client_id,
							   "bup_service_staff": bup_service_staff,
							   "bup_notify_client": bup_notify_client },
						
						success: function(data){
							
							//$fullCalendar.fullCalendar( 'refetchEvents' );
							
							jQuery("#bup-appointment-new-box" ).html( '' );										
							jQuery("#bup-appointment-new-box" ).dialog( "close" );
							
							//edit 
							
							var res =jQuery.parseJSON(data);				
							
							bup_edit_appointment_inline(res.booking_id, res.content, 'yes');
							
					
							}
					});
					
					
				
			
			}
			
			
			},
			close: function() {
				
				jQuery("#bup-appointment-new-box" ).html( '' );	
			
			
			}
	});
	
	
	/* appointment status */	
	jQuery( "#bup-appointment-change-status" ).dialog({
			autoOpen: false,			
			width: '400', // overcomes width:'auto' and maxWidth bug
   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Cancel": function() {	
			
				jQuery("#bup-appointment-change-status" ).html( '' );								
				jQuery( this ).dialog( "close" );
			}			
			
			},
			close: function() {
				
				jQuery("#bup-appointment-new-box" ).html( '' );	
			
			
			}
	});

	

});