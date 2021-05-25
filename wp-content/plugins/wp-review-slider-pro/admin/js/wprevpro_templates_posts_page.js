(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 * $( document ).ready(function() same as
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 
	 //document ready
	$(function(){
		
		//type select box
		$("#type_multiple_select").select2({
			width: 'resolve',
			placeholder: adminjs_script_vars.choose_site_types
		});
		
		//warning when scroll one review is on and fade transition is on.
		$( 'input[name="wprevpro_sliderdirection"]:radio' ).change(function() {
			if (this.value == 'fade') {
				if($("#wprevpro_sli_slidestoscroll").is(':checked')) {
					alert('The Fade Slide Animation does not work with the "Scroll One Review" checked. Please uncheck it.');
					$("#wprevpro_sliderdirection1-radio").prop("checked", true);
				}
			}
		});
		$("#wprevpro_sli_slidestoscroll").change(function() {
			if(this.checked) {
				if($("input[name='wprevpro_sliderdirection']:checked").val()=='fade'){
					alert('This does not work with the "Fade" Slide Animation. Please change it to "Horizontal".');
					$( "#wprevpro_sli_slidestoscroll" ).prop( "checked", false );
				}
			}
		});


		
		var prestyle = "";
		//color picker
		var myOptions = {
			// a callback to fire whenever the color changes to a valid color
			change: function(event, ui){
				//var color = ui.color.toString();
				var color = ui.color.toCSS( 'rgb' );
				var element = event.target;
				var curid = $(element).attr('id');
				$( element ).val(color)
				//manuall change after css. hack since jquery can't access before and after elements    border-top: 30px solid #943939;
				if(curid=='wprevpro_template_misc_bgcolor1'){
					prestyle = "<style>.wprevpro_t1_DIV_2::after{ border-top: 30px solid "+color+"; }</style>";
				}
				changepreviewhtml();
				//for updating pagination btn style
				changebtnstylepreview();
			},
			// a callback to fire when the input is emptied or an invalid color
			clear: function() {}
		};
		 
		$('.my-color-field').wpColorPicker(myOptions);
		
		//for updating the form without closing it, sending via ajax
		$( "#wprevpro_addnewtemplate_update" ).click(function() {
			$('#savingformimg').show();
			//get all the form values. newtemplateform
			event.preventDefault();

			var formArray = $( "#newtemplateform" ).serializeArray();
			//console.log(formArray);
			  var returnArray = {};
			  var pagefilterarray = [];
			  var choosetypearray = [];
			  for (var i = 0; i < formArray.length; i++){
				  if(formArray[i]['name']=='wprevpro_t_rpage[]'){
					  pagefilterarray.push(formArray[i]['value']);
				  } else if(formArray[i]['name']=='wprevpro_choosetypes[]'){
					  choosetypearray.push(formArray[i]['value']);
				  } else {
					returnArray[formArray[i]['name']] = formArray[i]['value'];
				  }
			  }
			  //now add pagefilter array since this is a multi-checkbox
			  returnArray.wprevpro_t_rpage = pagefilterarray;
			  returnArray.wprevpro_choosetypes = choosetypearray;
			 //console.log(returnArray);
  
			var jsonfields = JSON.stringify(returnArray);
			//console.log(jsonfields);
			var senddata = {
					action: 'wprp_save_template',	//required
					wpfb_nonce: adminjs_script_vars.wpfb_nonce,
					data: jsonfields,
					};
				//send to ajax to update db
				var jqxhr = jQuery.post(ajaxurl, senddata, function (response){
					console.log(response);
					$('#savingformimg').hide();
					$('#update_form_msg').html(response);
					//hide message after 3 seconds
					setTimeout(function(){ $('#update_form_msg').html(''); }, 3000);
				});

		});
		
		//for hiding and showing the different tab pageselectclass
		var currenttab = 1;
		$( ".gotopage1" ).click(function() {
			//hide everything but page 1
			$( "#settingtable1" ).fadeIn();
			$( "#settingtable2" ).hide();
			$( "#settingtable3" ).hide();
			$( "#settingtable4" ).hide();
			currenttab = 1;
			changecurrenttab(currenttab);
		});
		$( ".gotopage2" ).click(function() {
			$( "#settingtable1" ).hide();
			$( "#settingtable2" ).fadeIn();
			$( "#settingtable3" ).hide();
			$( "#settingtable4" ).hide();
			currenttab = 2;
			changecurrenttab(currenttab);
		});
		$( ".gotopage3" ).click(function() {
			$( "#settingtable1" ).hide();
			$( "#settingtable2" ).hide();
			$( "#settingtable3" ).fadeIn();
			$( "#settingtable4" ).hide();
			currenttab = 3;
			changecurrenttab(currenttab);
		});
		$( ".gotopage4" ).click(function() {
			$( "#settingtable1" ).hide();
			$( "#settingtable2" ).hide();
			$( "#settingtable3" ).hide();
			$( "#settingtable4" ).fadeIn();
			currenttab = 4;
			changecurrenttab(currenttab);
		});
		function changecurrenttab(ctab){
			//remove all classes
			$( ".settingtab" ).removeClass( "nav-tab-active" );
			if(ctab==1){
				$( "#settingtab1" ).addClass("nav-tab-active");
			}
			if(ctab==2){
				$( "#settingtab2" ).addClass("nav-tab-active");
			}
			if(ctab==3){
				$( "#settingtab3" ).addClass("nav-tab-active");
			}
			if(ctab==4){
				$( "#settingtab4" ).addClass("nav-tab-active");
			}
		}

		//for style preview changes.-------------
		//var starhtml = '<span class="wprevpro_star_imgs"><img src="'+adminjs_script_vars.pluginsUrl + '/public/partials/imgs/stars_5_yellow.png" alt="" >&nbsp;&nbsp;</span>';
		var starhtml = '<span id="starloc1" class="wprevpro_star_imgs"><span class="wprsp-star"></span><span class="wprsp-star"></span><span class="wprsp-star"></span><span class="wprsp-star"></span><span class="wprsp-star-o"></span></span>';

		
		var sampltext = 'This is a sample review. Hands down the best experience we have had in the southeast! Awesome accommodations, great staff. We will gladly drive four hours for this gem!';
		var datehtml = '<span id="wprev_showdate">1/12/2017</span>';
		
		var imagehref = adminjs_script_vars.pluginsUrl + '/admin/partials/sample_avatar.jpg';
		var imagehrefmystery = adminjs_script_vars.pluginsUrl + '/admin/partials/fb_profile.jpg';
		
		var avatarimg = imagehref;
		var quoteimg = adminjs_script_vars.pluginsUrl + '/public/partials/imgs/testimonial_quote.png';
		
		var googlelogo = adminjs_script_vars.pluginsUrl + '/public/partials/imgs/google_small_icon.png';
		
		var displayname = '<span id="wprev_showname"><span id="fname">John</span>&nbsp;<span id="lname">Smith</span></span>';
		var displayname3 = '<div id="wprev_showname"><span id="fname">John</span>&nbsp;<span id="lname">Smith</span></div>';
		
		var style1html ='<div class="wprevpro_t1_outer_div w3_wprs-row-padding">	\
							<div class="wprevpro_t1_DIV_1 w3_wprs-col">	\
								<div class="wprevpro_t1_DIV_2 wprev_preview_bg1 wprev_preview_bradius">	\
									<p class="wprevpro_t1_P_3 wprev_preview_tcolor1">	\
										'+starhtml+''+sampltext+'		</p>	\
									<img src="'+googlelogo+'" alt="Google Logo" class="wprevpro_t1_site_logo siteicon">	\
								</div><span class="wprevpro_t1_A_8"><img src="'+avatarimg+'" alt="thumb" class="wprev_avatar_opt wprevpro_t1_IMG_4"></span> <span class="wprevpro_t1_SPAN_5 wprev_preview_tcolor2">'+displayname+'<br>'+datehtml+' </span>	\
							</div>	\
							</div>';
		var style2html = '<div class="wprevpro_t2_outer_div w3_wprs-row-padding">	\
							<div class="wpproslider_t2_DIV_1 w3_wprs-col l12" "="">	\
								<div class="wpproslider_t2_DIV_2 wprev_preview_bg1 wprev_preview_bradius">	\
								<img src="'+avatarimg+'" class="wprev_avatar_opt wpproslider_t2_IMG_2">	\
								<div class="wpproslider_t2_DIV_3">	\
									<p class="wpproslider_t2_P_4 wprev_preview_tcolor1">	\
										'+starhtml+''+sampltext+'		</p> <strong class="wpproslider_t2_STRONG_5 wprev_preview_tcolor2">'+displayname+'</strong> <span class="wpproslider_t2_SPAN_6 wprev_preview_tcolor2">'+datehtml+'</span>	\
										<img src="'+googlelogo+'" alt="TripAdvisor Logo" class="wprevpro_t2_site_logo siteicon">	\
								</div></div></div></div>';	
								
		var style3html = '<style>.wpproslider_t3_P_3{ font: italic normal normal normal 16px / 21px Georgia, serif; }.wpproslider_t3_DIV_2{ font: italic normal normal normal 16px / 21px Georgia, serif; }</style><div class="wprevpro_t3_outer_div w3_wprs-row-padding">	\
				<div class="wpproslider_t3_DIV_1 w3_wprs-col l12">	\
			<div class="wpproslider_t3_DIV_1a wprev_preview_bg2 wprev_preview_bradius">	\
				<div class="wpproslider_t3_DIV_2 wprev_preview_bg1 wprev_preview_tcolor2 wprev_preview_tcolor3">	\
					<div class="wpproslider_t3_avatar_div">	\
					<img src="'+avatarimg+'" class="wprev_avatar_opt wpproslider_t3_avatar">	\
					</div>	\
					'+displayname3+'</div>	\
				<p class="wpproslider_t3_P_3 wprev_preview_tcolor1"><img src="'+quoteimg+'" alt="" class="wpproslider_t3_quote">'+starhtml+''+sampltext+' '+datehtml+'</p>	\
				<img src="'+googlelogo+'" alt="Google Logo" class="wprevpro_t3_site_logo siteicon">	\
			</div>	\
		</div>	\
		</div>';
		
		var style4html = '<div class="wprevpro_t4_outer_div w3_wprs-row-padding">	\
			<div class="wpproslider_t4_DIV_1 w3_wprs-col l12">	\
		<div class="wpproslider_t4_DIV_1a wprev_preview_bg1 wprev_preview_bradius">	\
			<div class="wpproslider_t4_avatar_div">	\
			<img src="'+avatarimg+'" class="wprev_avatar_opt wpproslider_t4_IMG_2">	\
			</div>	\
			<h3 class="wpproslider_t4_H3_3 wprev_preview_tcolor1">'+displayname+'</h3>	\
			<span class="wpproslider_t4_SPAN_4">'+starhtml+'</span>	\
			<p class="wpproslider_t4_P_5 wprev_preview_tcolor2">'+sampltext+'</p>	\
			<span class="wpproslider_t4_date wprev_preview_tcolor3">'+datehtml+'</span>	\
			<div><img src="'+googlelogo+'" alt="TripAdvisor Logo" class="wprevpro_t4_site_logo siteicon"></div>	\
		</div></div></div>';
		
		var style5html = '<div class="wprevpro_t5_outer_div w3_wprs-row-padding">	\
							<div class="wpproslider_t5_DIV_1 w3_wprs-col l12" "="">	\
								<div class="wpproslider_t5_DIV_2 wprev_preview_bg1 wprev_preview_bradius">	\
								<div class="wpproslider_t5_DIV_3L"><img src="'+avatarimg+'" class="wprev_avatar_opt wpproslider_t5_IMG_2"><span class="wpproslider_t5_STRONG_5 wprev_preview_tcolor2">'+displayname+'</span></div>	\
								<div class="wpproslider_t5_DIV_3">	\
									<p class="wpproslider_t5_P_4 wprev_preview_tcolor1" style="margin: 8px 8px 8px;">	\
										'+starhtml+''+sampltext+'<span class="wpproslider_t5_SPAN_6 wprev_preview_tcolor2"><span class=" wprev_preview_tcolor2 uname2" style="display:none;"> - '+displayname+'</span> - '+datehtml+'</span></p> 	\
								</div>	\
								<div class="wpproslider_t5_DIV_3_logo"><img src="'+googlelogo+'" alt="Google Logo" class="wprevpro_t5_site_logo siteicon"></div>	\
								</div></div></div>';	
								
		var style6html = '<div class="wprevpro_t6_outer_div w3_wprs-row-padding">	\
							<div class="wpproslider_t6_DIV_1 w3_wprs-col l12" "="">	\
								<div class="wpproslider_t6_DIV_2 wprev_preview_bg1 wprev_preview_bradius">	\
									<div class="wpproslider_t6_DIV_2_top" style="line-height:24px;">	\
										<div class="wpproslider_t6_DIV_3L"><img src="'+avatarimg+'" class="wprev_avatar_opt wpproslider_t6_IMG_2"></div>	\
										<div class="wpproslider_t6_DIV_3">	\
											<div class="wpproslider_t6_STRONG_5 wprev_preview_tcolor2 t6displayname">'+displayname+'</div>	\
											<div class="wpproslider_t6_star_DIV">'+starhtml+'</div>	\
											<div class="wpproslider_t6_SPAN_6 wprev_preview_tcolor2 t6datediv">'+datehtml+'</div>	\
										</div>	\
									</div>	\
									<div class="wpproslider_t6_DIV_4"><p class="wpproslider_t6_P_4 wprev_preview_tcolor1">	\
											'+sampltext+'</p> 	\
									</div>	\
									<div class="wpproslider_t6_DIV_3_logo"><img src="'+googlelogo+'" alt="Google Logo" class="wprevpro_t6_site_logo siteicon"></div>	\
								</div></div></div>';	
		
		var style7html = '<div class="wprevpro_t7_outer_div w3_wprs-row-padding">	\
							<div class="wpproslider_t7_DIV_1 w3_wprs-col l12" "="">	\
								<div class="wpproslider_t7_DIV_2 wprev_preview_bg1 wprev_preview_bradius">	\
								<div class="wpproslider_t7_DIV_2_top">	\
									<div class="wpproslider_t7_DIV_3L">	\
										<div class="wpproslider_t7_DIV_3_logo"><img src="'+googlelogo+'" alt="Google Logo" class="wprevpro_t7_site_logo siteicon"></div>	\
										<div class="wpproslider_t7_star_DIV">'+starhtml+'</div>	\
									</div>	\
								</div>	\
								<div class="wpproslider_t7_DIV_4">	\
									<div class="wpproslider_t7_DIV_3">	\
										<p class="wpproslider_t7_P_4 wprev_preview_tcolor1">"'+sampltext+'"</p> 	\
									</div>	\
									<div class="wpproslider_t7_STRONG_5 wprev_preview_tcolor2 t7displayname">'+displayname+'</div>	\
									<div class="wpproslider_t7_SPAN_6 wprev_preview_tcolor2 t7datediv">'+datehtml+'</div>	\
								</div>	\
								</div></div></div>';	
								
		var style8html = '<div class="wprevpro_t8_outer_div w3_wprs-row-padding">	\
							<div class="wpproslider_t8_DIV_1 w3_wprs-col l12" "="">	\
								<div class="wpproslider_t8_DIV_2 wprev_preview_bg1 wprev_preview_bradius">	\
									<div class="wpproslider_t8_DIV_2_top" style="line-height:24px;">	\
										<div class="wpproslider_t8_DIV_3">	\
											<div class="wpproslider_t8_STRONG_5 wprev_preview_tcolor2 t8displayname">'+displayname+'<span class="wpproslider_t8_SPAN_6 wprev_preview_tcolor2 t8datediv">'+datehtml+'</span></div>	\
											<div class="wpproslider_t8_star_DIV">'+starhtml+'</div>	\
											<div class="wpproslider_t8_DIV_4"><p class="wpproslider_t8_P_4 wprev_preview_tcolor1">	\
											'+sampltext+'</p> 	\
										</div>	\
										</div>	\
									</div>	\
									<div class="wpproslider_t8_DIV_3_logo"><img src="'+googlelogo+'" alt="Google Logo" class="wprevpro_t8_site_logo siteicon"></div>	\
								</div></div></div>';	
								
		var style9html = '<div class=" wprevpro_t9_DIV_1 w3_wprs-col">	\
								<div class="wpproslider_t9_DIV_1a">	\
									<div class="wpproslider_t9_DIV_2 wprev_preview_bg1 wprev_preview_bradius">	\
									<div class="wpproslider_t9_DIV_2_top">	\
										<div class="wpproslider_t9_DIV_3L"><img src="'+avatarimg+'" alt="Avatar" class="wprev_avatar_opt wpproslider_t9_IMG_2 wprevpro_avatarimg"></div>	\
										<div class="wpproslider_t9_star_DIV wprevpro_star_imgs_T9">'+starhtml+'</div>	\
										<div class="wpprooutoffive">5 out of 5 stars</div><div class="wpproslider_t9_DIV_3_logo"><img src="'+googlelogo+'" alt="Google Logo" class="wprevpro_t9_site_logo siteicon"></div>	\
									</div>	\
									<div class="wpproslider_t9_DIV_3">	\
											<div class="t9displayname wpproslider_t9_STRONG_5 wprev_preview_tcolor2">'+displayname+'</div>	\
											<div class="wpproslider_t9_SPAN_6 wprev_preview_tcolor2_T9"><span class="wprev_showdate_T9 wprev_preview_tcolor2">'+datehtml+'<span class="wppro_viatext wprev_preview_tcolor2"> - Google</span></span></div>	\
									</div>	\
									<div class="indrevdiv wpproslider_t9_DIV_4">	\
											<p class="wpproslider_t9_P_4 wprev_preview_tcolor1">'+sampltext+'</p>	\
									</div></div></div></div>';	
		
		var style10html = '<div class="wprevpro_t10_outer_div w3_wprs-row-padding">	\
							<div class="wpproslider_t10_DIV_1 w3_wprs-col l12" "="">	\
								<div class="wpproslider_t10_DIV_2 wprev_preview_bg1 wprev_preview_bradius">	\
									<div class="wpproslider_t10_DIV_2_top" style="line-height:24px;">	\
										<div class="wpproslider_t10_DIV_3L"><img src="'+avatarimg+'" class="wprev_avatar_opt wpproslider_t10_IMG_2"></div>	\
										<div class="wpproslider_t10_DIV_3">	\
											<div class="wpproslider_t10_STRONG_5 wprev_preview_tcolor2 t10displayname"><span class="t10_revname wprev_preview_tcolor1">'+displayname+'</span> left us a 5 star review</div>	\
											<div class="wpproslider_t10_star_DIV">'+starhtml+' <span class="t10_onsite"> on Google</span></div>	\
											<div class="wpproslider_t10_SPAN_6 t10datediv">'+datehtml+'</div>	\
										</div>	\
									</div>	\
									<div class="wpproslider_t10_DIV_3_logo"><img src="'+googlelogo+'" alt="Google Logo" class="wprevpro_t10_site_logo siteicon"></div>	\
								</div></div></div>';	
		
		changepreviewhtml();
		
		//reset colors to default
		$( "#wprevpro_pre_resetbtn" ).click(function() {
			resetcolors();
		});
		function resetcolors(){
				var templatenum = $( "#wprevpro_template_style" ).val();
				//reset colors to default
				if(templatenum=='1'){
					
					$( "#wprevpro_template_misc_bradius" ).val('0');
					$( "#wprevpro_template_misc_bgcolor1" ).val('#ffffff');
					$( "#wprevpro_template_misc_bgcolor2" ).val('#ffffff');
					$( "#wprevpro_template_misc_tcolor1" ).val('#777777');
					$( "#wprevpro_template_misc_tcolor2" ).val('#555555');
					prestyle="";
					//reset color picker
					$('#wprevpro_template_misc_bgcolor1').iris('color', '#ffffff');
					$('#wprevpro_template_misc_bgcolor2').iris('color', '#ffffff');
					$( "#wprevpro_template_misc_tcolor1" ).iris('color','#777777');
					$( "#wprevpro_template_misc_tcolor2" ).iris('color','#555555');
					
				} else if(templatenum=='2' || templatenum=='5' || templatenum=='6' || templatenum=='7' || templatenum=='8' || templatenum=='9' || templatenum=='10'){
					$( "#wprevpro_template_misc_bradius" ).val('0');
					if(templatenum=='10'){
						$( "#wprevpro_template_misc_bradius" ).val('20');
					}
					$( "#wprevpro_template_misc_bgcolor1" ).val('#fdfdfd');
					$( "#wprevpro_template_misc_bgcolor2" ).val('#eeeeee');
					$( "#wprevpro_template_misc_tcolor1" ).val('#555555');
					$( "#wprevpro_template_misc_tcolor2" ).val('#555555');
					//reset color picker
					$('#wprevpro_template_misc_bgcolor1').iris('color', '#fdfdfd');
					$('#wprevpro_template_misc_bgcolor2').iris('color', '#eeeeee');
					$( "#wprevpro_template_misc_tcolor1" ).iris('color','#555555');
					$( "#wprevpro_template_misc_tcolor2" ).iris('color','#555555');
				} else if(templatenum=='3'){
					$( "#wprevpro_template_misc_bradius" ).val('8');
					$( "#wprevpro_template_misc_bgcolor1" ).val('#f8fafa');
					$( "#wprevpro_template_misc_bgcolor2" ).val('#ffffff');
					$( "#wprevpro_template_misc_tcolor1" ).val('#454545');
					$( "#wprevpro_template_misc_tcolor2" ).val('#b2b2b2');
					$( "#wprevpro_template_misc_tcolor3" ).val('#ffffff');
					//reset color picker
					$('#wprevpro_template_misc_bgcolor1').iris('color', '#f8fafa');
					$('#wprevpro_template_misc_bgcolor2').iris('color', '#ffffff');
					$( "#wprevpro_template_misc_tcolor1" ).iris('color','#454545');
					$( "#wprevpro_template_misc_tcolor2" ).iris('color','#b2b2b2');
					$('#wprevpro_template_misc_tcolor3').iris('color', '#ffffff');
				} else if(templatenum=='4'){
					$( "#wprevpro_template_misc_bradius" ).val('5');
					$( "#wprevpro_template_misc_bgcolor1" ).val('rgba(140, 140, 140, 0.15)');
					$( "#wprevpro_template_misc_bgcolor2" ).val('#ffffff');
					$( "#wprevpro_template_misc_tcolor1" ).val('rgb(128, 128, 128)');
					$( "#wprevpro_template_misc_tcolor2" ).val('rgb(121, 121, 121)');
					$( "#wprevpro_template_misc_tcolor3" ).val('rgb(76, 76, 76)');
					//reset color picker
					$('#wprevpro_template_misc_bgcolor1').iris('color', 'rgba(140, 140, 140, 0.15)');
					$('#wprevpro_template_misc_bgcolor2').iris('color', '#ffffff');
					$( "#wprevpro_template_misc_tcolor1" ).iris('color','rgb(128, 128, 128)');
					$( "#wprevpro_template_misc_tcolor2" ).iris('color','rgb(121, 121, 121)');
					$('#wprevpro_template_misc_tcolor3').iris('color', 'rgb(76, 76, 76)');
				}
		}

		
		//for hiding and showing file upload form
		$( "#wprevpro_importtemplates" ).click(function() {
			$("#importform").slideToggle();
		});
		
		//on display order change
		$( "#wprevpro_t_display_order" ).change(function() {
				if($( "#wprevpro_t_display_order" ).val()=="random"){
					$( "#span_display_order_limit" ).show();
					$( "#span_display_order_second" ).hide();
				} else {
					$( "#span_display_order_limit" ).hide();
					$( "#span_display_order_second" ).show();
				}
				if($( "#wprevpro_t_display_order" ).val()=="sortweight"){
					$( "#sortweightdescription" ).show();
				} else {
					$( "#sortweightdescription" ).hide();
				}
		});
		
		//hide or show load more button text 
		$( "#wprevpro_t_load_more_porb" ).change(function() {
				if($( "#wprevpro_t_load_more_porb" ).val()=="pagenums"){
					$( ".lmt" ).hide();
				} else {
					$( ".lmt" ).show();
				}
		});
		
		//on template num change
		$( "#wprevpro_template_style" ).change(function() {
				//reset colors if not editing, otherwise leave alone
				if($( "#edittid" ).val()==""){
				resetcolors();
				}
				changepreviewhtml();
				//change star location back to default
				$( "#wprevpro_template_misc_starlocation" ).val('1');
				
				//hide or show avatar dropdown
				if($( this ).val()=='7'){
					$( ".displayavatar" ).hide('3000');
				} else {
					$( ".displayavatar" ).show('3000');
				}
				
				//hide title option if this is template 10
				if($( this ).val()=='10'){
					$( ".reviewtitle" ).hide('3000');
				} else {
					$( ".reviewtitle" ).show('3000');
				}
		});
		
		$( "#wprevpro_template_misc_showstars" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_template_misc_showdate" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_template_misc_dateformat" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_template_misc_lastname" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_template_misc_firstname" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_template_misc_avataropt" ).change(function() {
				changepreviewhtml();
				var tempval = $(this).val();
				if(tempval == 'init'){
					$( ".displayavatarcolor" ).show('slow');
				} else {
					$( ".displayavatarcolor" ).hide('slow');
				}
		});
		$( "#wprevpro_template_misc_bradius" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_template_misc_bgcolor1" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_template_misc_tcolor1" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_template_misc_starlocation" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_t_facebook_icon" ).change(function() {
			if ($(this).val() != 'cho') {
				$( ".divsiteiconchoose" ).hide('slow');
			} else {
				$( ".divsiteiconchoose" ).show('slow');
			}
				changepreviewhtml();
		});
		$( "#wprevpro_template_misc_tfont1" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_template_misc_tfont2" ).change(function() {
				changepreviewhtml();
		});
		
		
		//custom css change preview
		var lastValue = '';
		$("#wprevpro_template_css").on('change keyup paste mouseup', function() {
			if ($(this).val() != lastValue) {
				lastValue = $(this).val();
				changepreviewhtml();
			}
		});
		
		function changepreviewhtml(){
			var templatenum = $( "#wprevpro_template_style" ).val();
			var bradius = $( "#wprevpro_template_misc_bradius" ).val();
			var bg1 = $( "#wprevpro_template_misc_bgcolor1" ).val();
			var bg2 = $( "#wprevpro_template_misc_bgcolor2" ).val();
			var tcolor1 = $( "#wprevpro_template_misc_tcolor1" ).val();
			var tcolor2 = $( "#wprevpro_template_misc_tcolor2" ).val();
			var tcolor3 = $( "#wprevpro_template_misc_tcolor3" ).val();
			var dateformat = $( "#wprevpro_template_misc_dateformat" ).val();
			var lastnameformat = $( "#wprevpro_template_misc_lastname" ).val();
			var firstnameformat = $( "#wprevpro_template_misc_firstname" ).val();
			var starcolor = $( "#wprevpro_template_misc_starcolor" ).val();
			var starlocation = $( "#wprevpro_template_misc_starlocation" ).val();
			var avataropt = $( "#wprevpro_template_misc_avataropt" ).val();
			var siteicondisplay = $( "#wprevpro_t_facebook_icon" ).val();
			
			var tfont1 = $( "#wprevpro_template_misc_tfont1" ).val();
			var tfont2 = $( "#wprevpro_template_misc_tfont2" ).val();
			
			if($( "#wprevpro_template_css" ).val()!=""){
				var customcss = '<style>'+$( "#wprevpro_template_css" ).val()+'</style>';
				prestyle =  prestyle + customcss;
			}
			
				var temphtml;
				if(templatenum=='1'){
					$( "#wprevpro_template_preview" ).html(prestyle+style1html);
					//hide background 2 select
					$( ".wprevpre_bgcolor2" ).hide();
					$( ".wprevpre_tcolor3" ).hide();
				} else if(templatenum=='2'){
					$( "#wprevpro_template_preview" ).html(prestyle+style2html);
					$( ".wprevpre_bgcolor2" ).show();
					$( ".wprevpre_tcolor3" ).hide();
					$( '.wprev_preview_bg1' ).css( "border-bottom", '3px solid '+bg2 );
				} else if(templatenum=='3'){
					$( "#wprevpro_template_preview" ).html(prestyle+style3html);
					$( ".wprevpre_bgcolor2" ).show();
					$( ".wprevpre_tcolor3" ).show();
					$( '.wprev_preview_tcolor3' ).css('textShadow', tcolor3+' 1px 1px 0px');
				} else if(templatenum=='4'){
					$( "#wprevpro_template_preview" ).html(prestyle+style4html);
					$( ".wprevpre_bgcolor2" ).hide();
					$( ".wprevpre_tcolor3" ).show();
					$( '.wprev_preview_tcolor3' ).css('color', tcolor3);
				}else if(templatenum=='5'){
					$( "#wprevpro_template_preview" ).html(prestyle+style5html);
					$( ".wprevpre_bgcolor2" ).show();
					$( ".wprevpre_tcolor3" ).hide();
					$( '.wprev_preview_bg1' ).css( "border-bottom", '3px solid '+bg2 );
				}else if(templatenum=='6'){
					$( "#wprevpro_template_preview" ).html(prestyle+style6html);
					$( ".wprevpre_bgcolor2" ).show();
					$( ".wprevpre_tcolor3" ).hide();
					$( '.wprev_preview_bg1' ).css( "border", '1px solid '+bg2 );
				}else if(templatenum=='7'){
					$( "#wprevpro_template_preview" ).html(prestyle+style7html);
					$( ".wprevpre_bgcolor2" ).show();
					$( ".wprevpre_tcolor3" ).hide();
					$( '.wprev_preview_bg1' ).css( "border", '1px solid '+bg2 );
				}else if(templatenum=='8'){
					$( "#wprevpro_template_preview" ).html(prestyle+style8html);
					$( ".wprevpre_bgcolor2" ).show();
					$( ".wprevpre_tcolor3" ).hide();
					$( '.wprev_preview_bg1' ).css( "border", '1px solid '+bg2 );
				}else if(templatenum=='9'){
					$( "#wprevpro_template_preview" ).html(prestyle+style9html);
					$( ".wprevpre_bgcolor2" ).show();
					$( ".wprevpre_tcolor3" ).hide();
					$( '.wprev_preview_bg1' ).css( "border", '1px solid '+bg2 );
				}else if(templatenum=='10'){
					$( "#wprevpro_template_preview" ).html(prestyle+style10html);
					$( ".wprevpre_bgcolor2" ).show();
					$( ".wprevpre_tcolor3" ).hide();
					$( '.wprev_preview_bg1' ).css( "border", '1px solid '+bg2 );
				}
			//now hide and show things based on values in select boxes
			if($( "#wprevpro_template_misc_showstars" ).val()=="no"){
				$( ".wprevpro_star_imgs" ).hide();
			} else {
				$( ".wprevpro_star_imgs" ).show();
			}
			if($( "#wprevpro_template_misc_showdate" ).val()=="no"){
				$( "#wprev_showdate" ).hide();
			} else {
				$( "#wprev_showdate" ).show();
			}
			if(siteicondisplay=="no"){
				$( ".siteicon" ).hide();
			} else {
				$( ".siteicon" ).show();
			}
			
			//set colors and bradius by changing css via jQuery     border-radius: 10px 10px 10px 10px;
			console.log(bg1);
			$( '.wprev_preview_bradius' ).css( "border-radius", bradius+'px' );
			$( '.wprev_preview_bg1' ).css( "background", bg1 );
			$( '.wprev_preview_bg2' ).css( "background", bg2 );
			$( '.wprev_preview_tcolor1' ).css( "color", tcolor1 );
			$( '.wprev_preview_tcolor2' ).css( "color", tcolor2 );
			
			if(tfont1>0){
			$( '.wprev_preview_tcolor1' ).css( "font-size", tfont1+"px" );
			}
			if(tfont2>0){
			$( '.wprev_preview_tcolor2' ).css( "font-size", tfont2+"px" );
			}
			
			
			
			//star changes, color and type, and sometimes location, change the span .wprevpro_star_imgs
			var newstarhtml = '';
			var fulliconclass = $( '#fullstaricon' ).find('span').attr('class');
			var emptyiconclass = $( '#emptystaricon' ).find('span').attr('class');
			newstarhtml = '<span class="'+fulliconclass+'"></span><span class="'+fulliconclass+'"></span><span class="'+fulliconclass+'"></span><span class="'+fulliconclass+'"></span><span class="'+emptyiconclass+'"></span></span>';
			$( '.wprevpro_star_imgs' ).html( newstarhtml);
			$( '.wprevpro_star_imgs' ).css( "color", starcolor );
			//star location
			//hide if not template 3, only changing template 3 at this timesince
			if(templatenum=='3'){
				$( ".starlocationdiv" ).show();
				//move stars if needed
				if(starlocation=='2'){
					//remove current stars
					$( "#starloc1" ).hide();
					$( '<span id="starloc2" class="wprevpro_star_imgsloc1">'+newstarhtml+'</span>' ).insertAfter( "#wprev_showname" );
					$( '.wprevpro_star_imgsloc1' ).css( "color", starcolor );
				}
			} else {
				$( ".starlocationdiv" ).hide();
			}
			
			
			//dateformat change
			if(dateformat=='DD/MM/YY'){
				$( "#wprev_showdate" ).html('12/01/17');
			} else if(dateformat=='YYYY-MM-DD'){
				$( "#wprev_showdate" ).html('2017-12-01');
			} else if(dateformat=='DD/MM/YYYY'){
				$( "#wprev_showdate" ).html('12/01/2017');
			} else if(dateformat=='d M Y'){
				$( "#wprev_showdate" ).html('12 Jan 2017');
			} else if(dateformat=='timesince'){
				$( "#wprev_showdate" ).html('- 3 weeks ago');
			} else {
				$( "#wprev_showdate" ).html('1/12/2017');
			}
			
			//last name display change
			if(lastnameformat=='show'){
				$( "#lname" ).html('Smith');
			} else if(lastnameformat=='hide'){
				$( "#lname" ).html('');
			} else {
				$( "#lname" ).html('S.');
			}
			//firstnameformat
			if(firstnameformat=='show'){
				$( "#fname" ).html('John');
			} else if(firstnameformat=='hide'){
				$( "#fname" ).html('');
			} else {
				$( "#fname" ).html('J.');
			}
			
			//avatar options wprev_avatar_opt
			//var imagehref = adminjs_script_vars.pluginsUrl + '/admin/partials/sample_avatar.jpg';
			//var imagehrefmystery = adminjs_script_vars.pluginsUrl + '/admin/partials/fb_profile.jpg';
			if(avataropt=='hide'){
				//set to display none
				$( ".wprev_avatar_opt" ).hide();
				if(templatenum=='6'){
					$( ".wpproslider_t6_DIV_3L" ).hide();
				}
				if(templatenum=='5'){
					$( ".wpproslider_t5_DIV_3L" ).hide();
					//move last name
					$( ".uname2" ).show();
				}
				if(templatenum=='10'){
					$( ".wpproslider_t10_DIV_3L" ).hide();
				}
			} else if(avataropt=='mystery'){
				//set img src
				$(".wprev_avatar_opt").attr("src",imagehrefmystery);
			} else if(avataropt=='init'){
				//get color
				//console.log('https://avatar.oxro.io/avatar.svg?name=John+Smith&background='+s2inicolor+'&color=fff');
				//set img src
				$(".wprev_avatar_opt").attr("src",'https://avatar.oxro.io/avatar.svg?name=John+Smith');
			} else {
				$(".wprev_avatar_opt").attr("src",imagehref);
				$( ".wprev_avatar_opt" ).show();
				if(templatenum=='6'){
					$( ".wpproslider_t6_DIV_3L" ).show();
				}
				if(templatenum=='10'){
					$( ".wpproslider_t10_DIV_3L" ).show();
				}
				if(templatenum=='5'){
					$( ".wpproslider_t5_DIV_3L" ).show();
					$( ".uname2" ).hide();
				}
			}
		}
		

		//help button clicked
		$( "#wprevpro_helpicon_posts" ).click(function() {
		  openpopup(adminjs_script_vars.popuptitle, '<p>'+adminjs_script_vars.popupmsg+'</p>', "");
		});
		//display shortcode button click 
		$( ".wprevpro_displayshortcode" ).click(function() {
			//get id and template type
			var tid = $( this ).parent().attr( "templateid" );
			var ttype = $( this ).parent().attr( "templatetype" );
			
		  if(ttype=="widget"){
			openpopup(adminjs_script_vars.popuptitle1, '<p>'+adminjs_script_vars.popupmsg1+'</p>', "");
		  } else {
			openpopup(adminjs_script_vars.popuptitle2, '<p>'+adminjs_script_vars.popupmsg2a+' </br></br>[wprevpro_usetemplate tid="'+tid+'"] </br></br>'+adminjs_script_vars.or+'</br></br>[wprevpro_usetemplate tid="'+tid+'" pageid="" langcode="" tag=""]</br><a href="https://wpreviewslider.userecho.com/knowledge-bases/2/articles/552-shortcode-parameter-to-filter-by-page-id" target="_blank">'+adminjs_script_vars.more_info+'</a><br></p><p>'+adminjs_script_vars.popupmsg2b+' </br></br><code> do_action( \'wprev_pro_plugin_action\', '+tid+' ); </code></p>', '');
		  }

		});
		
		$("#wp_pro_template_settings").on('click','#wprevpro_helpicon_sli_infinite',function(){
			openpopup(adminjs_script_vars.popuptitle, '<p>The first slide will show again after the last slide without the slider rewinding.</p>', "");
		});
		$("#wp_pro_template_settings").on('click','#wprevpro_helpicon_sli_onereview',function(){
			openpopup(adminjs_script_vars.popuptitle, '<p>Will only slide one review at a time.</p>', "");
		});
		$("#wp_pro_template_settings").on('click','#wprevpro_helpicon_sli_avatar',function(){
			openpopup(adminjs_script_vars.popuptitle, '<p>Will change reviews to avatar only and add a large review above them.</p><p>Does not work if you have the same review template twice on the page.</p>', "");
		});
		$("#wp_pro_template_settings").on('click','#wprevpro_helpicon_sli_centermode',function(){
			openpopup(adminjs_script_vars.popuptitle, '<p>Will highlight the center review and add some partial reviews to the side.</p>', "");
		});

		
		//launch pop-up windows code--------
		function openpopup(title, body, body2){

			//set text
			jQuery( "#popup_titletext").html(title);
			jQuery( "#popup_bobytext1").html(body);
			jQuery( "#popup_bobytext2").html(body2);
			
			var popup = jQuery('#popup_review_list').popup({
				width: 400,
				offsetX: -100,
				offsetY: 0,
			});
			
			popup.open();
			//set height
			var bodyheight = Number(jQuery( ".popup-content").height()) + 10;
			jQuery( "#popup_review_list").height(bodyheight);

		}
		//--------------------------------
		//get the url parameter-----------
		function getParameterByName(name, url) {
			if (!url) {
			  url = window.location.href;
			}
			name = name.replace(/[\[\]]/g, "\\$&");
			var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
				results = regex.exec(url);
			if (!results) return null;
			if (!results[2]) return '';
			return decodeURIComponent(results[2].replace(/\+/g, " "));
		}
		//---------------------------------
		
		//hide or show new template form ----------
		var checkedittemplate = getParameterByName('taction'); // "lorem"
		if(checkedittemplate=="edit"){
			jQuery("#wprevpro_new_template").show("slow");
			checkwidgetradio();
		} else {
			jQuery("#wprevpro_new_template").hide();
		}
		
		$( "#wprevpro_addnewtemplate" ).click(function() {
		  jQuery("#wprevpro_new_template").show("slow");
		});	
		$( "#wprevpro_addnewtemplate_cancel" ).click(function() {
		  jQuery("#wprevpro_new_template").hide("slow");
		  //reload page without taction and tid
		  setTimeout(function(){ 
			window.location.href = "?page=wp_pro-templates_posts"; 
		  }, 500);
		  
		});	
		
		//-------------------------------
		
		//form validation
		$("#newtemplateform").submit(function(){   
			if(jQuery( "#wprevpro_template_title").val()==""){
				alert("Please enter a title.");
				$( "#wprevpro_template_title" ).focus();
				return false;
			} else if(jQuery( "#wprevpro_t_display_num_total").val()<1){
				alert("Please enter a 1 or greater.");
				$( "#wprevpro_t_display_num_total" ).focus();
				return false;
			} else {
			return true;
			}

		});
		
		//widget radio clicked
		$('input[type=radio][name=wprevpro_template_type]').change(function() {
			checkwidgetradio();
		});
		
		//check widget radio----------------------
		function checkwidgetradio() {
			$('.slickdivpluswidget').hide();
			var widgetvalue = $("input[name=wprevpro_template_type]:checked").val();
			if (widgetvalue == 'widget') {
				//change how many per a row to 1
				$('#wprevpro_t_display_num').val("1");
				$('#wprevpro_t_display_num').hide();
				$('#wprevpro_t_display_num').prev().hide();
				$('#wprevpro_t_display_masonry').val("no");
				$('#wprevpro_t_display_masonry').hide();
				$('#wprevpro_t_display_masonry').prev().hide();
				
				if($( "#wprevpro_t_createslider" ).val()=='sli'){
					$('.slickdivpluswidget').show('slow');
				}
				
				
				//hide the one per row mobile settings---------------
				//$('.onepermobilerow').hide();
				
				//force hide arrows and do not allow horizontal scroll on slideshow
				//$('input:radio[name=wprevpro_sliderdirection]').val(['vertical']);
				//$('input[id=wprevpro_sliderdirection1-radio]').attr("disabled",true);
				//$('input:radio[name=wprevpro_sliderarrows]').val(['no']);
				//$('input[id=wprevpro_sliderarrows1-radio]').attr("disabled",true);
			}
			else if (widgetvalue == 'post') {
				//alert("post type");
				if($('#edittid').val()==""){
				$('#wprevpro_t_display_num').val("3");
				}
				$('#wprevpro_t_display_num').show();
				$('#wprevpro_t_display_num').prev().show();
				$('input[id=wprevpro_sliderdirection1-radio]').attr("disabled",false);
				
				$('#wprevpro_t_display_masonry').show();
				$('#wprevpro_t_display_masonry').prev().show();
				//$('.onepermobilerow').show();
				//$('input[id=wprevpro_sliderarrows1-radio]').attr("disabled",false);
			}
		}
		
		//wprevpro_btn_pickpages open thickbox----------------
		$( "#wprevpro_btn_pickpages" ).click(function() {
			var url = "#TB_inline?width=600&height=600&inlineId=tb_content_page_select";
			tb_show("Only Show Reviews From These Pages", url);
			$( "#selectrevstable" ).focus();
			$( "#TB_window" ).css({ "width":"830px","margin-left": "-415px" });
			$( "#TB_ajaxContent" ).css({ "width":"800px" });
		});
		
		var fulloremptyclick = '';
		//btnstaricon open thickbox for star icon selection-------
		$( "#fullstaricon" ).click(function() {
			var url = "#TB_inline?width=auto&height=auto&inlineId=tb_content_sicons";
			tb_show("Select Full Star Icon", url);
			$( "#TB_window" ).css({ "width":"250px","margin-left": "-50px" });
			$( "#TB_ajaxContent" ).css({ "width":"auto","height":"auto" });
			$( "#TB_window" ).focus();
			fulloremptyclick = 'full';
		});
		$( "#emptystaricon" ).click(function() {
			var url = "#TB_inline?width=auto&height=auto&inlineId=tb_content_sicons";
			tb_show("Select Empty Star Icon", url);
			$( "#TB_window" ).css({ "width":"250px","margin-left": "-50px" });
			$( "#TB_ajaxContent" ).css({ "width":"auto","height":"auto" });
			$( "#TB_window" ).focus();
			fulloremptyclick = 'empty';
		});
		
		//when selecting a star icon need to set this wprevpro_template_misc_stariconfull to the correct value
		$( ".stariconlist" ).click(function() {
			//get the icon that was clicked
			var iconclass = $( this ).find('span').attr('class');
			if(fulloremptyclick=="full"){
				$( "#wprevpro_template_misc_stariconfull" ).val(iconclass);
				$( "#fullstaricon" ).find('span').removeClass();
				$( "#fullstaricon" ).find('span').addClass(iconclass);
			} else if(fulloremptyclick=="empty"){
				$( "#wprevpro_template_misc_stariconempty" ).val(iconclass);
				$( "#emptystaricon" ).find('span').removeClass();
				$( "#emptystaricon" ).find('span').addClass(iconclass);
			}
			tb_remove();
			changepreviewhtml();
		});
		//--------------------------------------------
		
		
		//when checking a page check box. update number selected
		$( ".pageselectclass" ).click(function() {
			var totalselected = $('input.pageselectclass:checked').length;
			if(Number(totalselected)<2){
				var newhtml = " ("+totalselected+" Page Selected)";
			} else {
				var newhtml = " ("+totalselected+" Pages Selected)";
			}
			$('#wprevpro_selectedpagesspan').html(newhtml);
			
		});
		
	
		//wprevpro_btn_pickreviews open thickbox----------------
		$( "#wprevpro_btn_pickreviews" ).click(function() {
		  sendtoajax('','','',"");
			var url = "#TB_inline?width=600&height=600&inlineId=tb_content";
			tb_show("Select Reviews to Display", url);
			$( "#wprevpro_filter_table_name" ).focus();
			$( "#TB_window" ).css({ "width":"730px","height":"700px","margin-left": "-415px" });
			$( "#TB_ajaxContent" ).css({ "width":"auto","height":"650px","overflow": "scroll" });

		});

		//hide or show rich snippet settings---------------
		$( "#wprevpro_t_google_snippet_add" ).change(function() {
			//if no then hide
			var tempval = $( "#wprevpro_t_google_snippet_add" ).val();
			if(tempval!="yes"){
				$('#snippetsettings').hide('slow');
			} else {
				$('#snippetsettings').show('slow');
			}
			var tempval2 = $( "#wprevpro_t_google_snippet_type" ).val();
			if(tempval2=="Product"){
				$('#businessrichsnippetfields').hide();
				$('#productrichsnippetfields').show();
				
			} else {
				$('#productrichsnippetfields').hide();
				$('#businessrichsnippetfields').show();
			}
		});
		$( "#wprevpro_t_google_snippet_irm" ).change(function() {
			//if no then hide
			var tempval = $( "#wprevpro_t_google_snippet_irm" ).val();
			if(tempval!="yes"){
				$('#irmwarning').hide();
			} else {
				$('#irmwarning').show('slow');
			}
		});
		
		//hide or show slider settings---------------
		$( "#wprevpro_t_createslider" ).change(function() {
			//if no then hide
			var tempval = $( "#wprevpro_t_createslider" ).val();
			if(tempval!="yes" && tempval!="sli"){
				$('#slidersettingsrow').hide();
				$('.searchsorttr').show('slow');
				$('#wprevpro_t_numslides_label').hide();
				$('#wprevpro_t_numslides').hide();
			} else {
				$('.searchsorttr').hide();
				$('#slidersettingsrow').show('slow');
				$('#wprevpro_t_numslides_label').show();
				$('#wprevpro_t_numslides').show();
				if(tempval=="sli"){
					$('.onepermobilerow').hide('slow');
					$('.slickdiv').show('slow');
					if ($("input[name=wprevpro_template_type]:checked").val() == 'widget') {
						$('.slickdivpluswidget').show('slow');
					} else {
						$('.slickdivpluswidget').hide('slow');
					}
				} else {
					$('.onepermobilerow').show('slow');
					$('.slickdiv').hide('slow');
					$('.slickdivpluswidget').hide('slow');
				}
			}

		});
		
		//hide or show local business settings---------------
		$( "#wprevpro_t_google_snippet_type" ).change(function() {
			//if no then hide
			var tempval = $( "#wprevpro_t_google_snippet_type" ).val();
			if(tempval=="Product"){
				$('#businessrichsnippetfields').hide();
				$('#productrichsnippetfields').show();
			} else {
				$('#businessrichsnippetfields').show();
				$('#productrichsnippetfields').hide();
			}
		});
		
		
		//for search box------------------------------
		$('#wprevpro_filter_table_name').on('input', function() {
			// do something
			var myValue = $("#wprevpro_filter_table_name").val();
			var myLength = myValue.length;
			if(myLength>1 || myLength==0){
			//search here
				sendtoajax('','','',"");
			}
		});
		
		//for search select box------------------------------
		$( "#wprevpro_filter_table_min_rating" ).change(function() {
				sendtoajax('','','',"");
		});
		//for search select box------------------------------
		$( "#wprevpro_filter_table_type" ).change(function() {
				sendtoajax('','','',"");
		});
		
		//for pagination bar-----------------------------------
		$("#wprevpro_list_pagination_bar").on("click", "span", function (event) {
			var pageclicked = $(this).text();
			sendtoajax(pageclicked,'','',"");
		});
		
		//for sorting table--------------wprevpro_sortname, wprevpro_sorttext, wprevpro_sortdate
		$( ".wprevpro_tablesort" ).click(function() {
			//remove all green classes
			$(this).parent().find('i').removeClass("text_green");

			//add back on this one
			$(this).children( "i" ).addClass("text_green");
			
			var sortdir = $(this).attr("sortdir");
			var sorttype = $(this).attr("sorttype");
			if(sortdir=="DESC"){
				$(this).attr("sortdir","ASC");
			} else {
				$(this).attr("sortdir","DESC");
			}
			if(sorttype=="name"){
				sorttype="reviewer_name";
			} else if(sorttype=="rating") {
				sorttype="rating";
			} else if(sorttype=="stext") {
				sorttype="review_length";
			} else if(sorttype=="stime") {
				sorttype="created_time_stamp";
			}
		  sendtoajax('1',sorttype,sortdir,"");
		});
		
		//=====for only displaying the ones selected so far========
		$('#wprevpro_selectedrevsdiv').click(function() {
			//find the currently selected
			var currentlyselected = $('#wprevpro_t_showreviewsbyid').val();
			if(currentlyselected==""){
				var temparray =  Array();
			} else {
				var temparray = currentlyselected.split("-");
			}
			//convert to object
			var temparrayobj = temparray.reduce(function(acc, cur, i) {acc[i] = cur;return acc;}, {});
			sendtoajax('1','','',temparrayobj);
			var url = "#TB_inline?width=600&height=600&inlineId=tb_content";
			tb_show("Currenlty Selected", url);
			$( "#wprevpro_filter_table_name" ).focus();
			$( "#TB_window" ).css({ "width":"830px","margin-left": "-415px" });
			$( "#TB_ajaxContent" ).css({ "width":"800px" });
		});
		
		//============for clearing all currently selected============
		$('#wprevpro_clearselectedrevsbtn').click(function() {
			$('#wprevpro_t_showreviewsbyid').val("");
			$('#wprevpro_selectedrevsdiv').hide();
			$('#wprevpro_t_showreviewsbyid').hide();
			//show the filters again
			//$('.revselectedhide').slideDown(3000);
			$('.revselectedhide').css("background-color","#ffffff");
		});
		//for changing background on selecting reviews
		$('#wprevpro_t_showreviewsbyid_sel').click(function() {
			if($('#wprevpro_t_showreviewsbyid_sel').val()=='theseplus'){
				$('.revselectedhide').css("background-color","#ffffff");
			} else {
				if($('#wprevpro_t_showreviewsbyid').val()!=""){
					$('.revselectedhide').css("background-color","#d4d4d4");
				} else {
					$('.revselectedhide').css("background-color","#ffffff");
				}
			}
		});
		//======send to ajax to retrieve reviews==========
		function sendtoajax(pageclicked,sortbyval,sortd,selrevs){
			var filterbytext = $("#wprevpro_filter_table_name").val();
			var filterbyrating = $("#wprevpro_filter_table_min_rating").val();
			var filterbytype = $("#wprevpro_filter_table_type").val();
			//clear list and pagination bar
			$( "#review_list_select" ).html("");
			$( "#wprevpro_list_pagination_bar" ).html("");
			var senddata = {
					action: 'wpfb_find_reviews',	//required
					wpfb_nonce: adminjs_script_vars.wpfb_nonce,
					sortby: sortbyval,
					sortdir: sortd,
					filtertext: filterbytext,
					filterrating: filterbyrating,
					filtertype: filterbytype,
					pnum:pageclicked,
					curselrevs:selrevs
					};

				jQuery.post(ajaxurl, senddata, function (response){
					//console.log(response);
					var object = JSON.parse(response);
				//console.log(object);

				var htmltext;
				var userpic;
				var reviewtext;

				
					$.each(object, function(index) {
						if(object[index]){
						if(object[index].reviewer_name){
							//check to see if this one should be checked
							//get currently selected
							var currentlyselected = $('#wprevpro_t_showreviewsbyid').val();
							if(currentlyselected==""){
								var temparray =  Array();
							} else {
								var temparray = currentlyselected.split("-");
							}
							//see if id is in array
							var prevselected="";
							if(jQuery.inArray( object[index].id, temparray )>-1){
								prevselected = 'checked="checked"';
							}
							
							//userpic
							userpic="";
							if(object[index].type=="Facebook"){
								if(object[index].userpiclocal!=''){
									userpic = '<img style="-webkit-user-select: none;width: 50px;" src="'+object[index].userpiclocal+'">';
								} else {
									if(object[index].userpic==''){
									userpic = '<img style="-webkit-user-select: none;width: 50px;" src="https://graph.facebook.com/'+object[index].reviewer_id+'/picture?type=square">';
									} else {
										userpic = '<img style="-webkit-user-select: none;width: 50px;" src="'+object[index].userpic+'">';
									}
								}
								
							} else {
								if(object[index].userpiclocal!=''){
									userpic = '<img style="-webkit-user-select: none;width: 50px;" src="'+object[index].userpiclocal+'">';
								} else {
								userpic = '<img style="-webkit-user-select: none;width: 50px;" src="'+object[index].userpic+'">';
								}
							}
							//stripslashes
							reviewtext = String(object[index].review_text);
							reviewtext = reviewtext.replace(/\\'/g,'\'').replace(/\"/g,'"').replace(/\\\\/g,'\\').replace(/\\0/g,'\0');
						
							htmltext = htmltext + '<tr id="wprev_id_'+object[index].id+'">	\
								<th scope="col" class="manage-column"><input type="checkbox" name="wprevpro_selected_revs[]" value="'+object[index].id+'" '+prevselected+'></th>	\
								<th scope="col">'+userpic+'</th>	\
								<th scope="col" class="manage-column">'+object[index].reviewer_name+'</th>	\
								<th scope="col" class="manage-column"><b>'+object[index].rating+'</b></th>	\
								<th scope="col" class="manage-column">'+reviewtext+'</th>	\
								<th scope="col" class="manage-column">'+object[index].created_time+'</th>	\
								<th scope="col" class="manage-column">'+object[index].type+'</th>	\
							</tr>';
							reviewtext ='';
						}
						}
					});
					
					$( "#review_list_select" ).html(htmltext);
					
					//pagination bar
					var numpages = Number(object['totalpages']);
					var reviewtotalcount = Number(object['reviewtotalcount']);
					if(numpages>1){
						var pagebarhtml="";
						var blue_grey;
						var i;
						var numpages = Number(object['totalpages']);
						var curpage = Number(object['pagenum']);
						for (i = 1; i <= numpages; i++) {
							if(i==curpage){blue_grey = " blue_grey";} else {blue_grey ="";}
							pagebarhtml = pagebarhtml + '<span class="button'+blue_grey+'">'+i+'</span>';
						}
					}
						$( "#wprevpro_list_pagination_bar" ).html(pagebarhtml);
					//hide sort arrows and search bar if totalcount is zero
					if(reviewtotalcount==0){
						//$("#wprevpro_searchbar").hide();
						$(".dashicons-sort").hide();
						$("#wprevpro_list_pagination_bar").hide();
					} else {
						//$("#wprevpro_searchbar").show();
						$(".dashicons-sort").show();
						$("#wprevpro_list_pagination_bar").show();
					}
					if(numpages==0){
						//$("#wprevpro_searchbar").hide();
						//$(".dashicons-sort").hide();
						//$("#wprevpro_list_pagination_bar").hide();
					} else {
						//$("#wprevpro_searchbar").show();
						//$(".dashicons-sort").show();
						//$("#wprevpro_list_pagination_bar").show();
					}
					
				});
		}
	
		
		//========when selecting a review add it to top so we can easily select or unselect it.==========
		$("#review_list_select").on("click", "input", function (event) {
			var revid = $(this).val();
			
			//get currently selected
			var currentlyselected = $('#wprevpro_t_showreviewsbyid').val();
			if(currentlyselected==""){
				var temparray =  Array();
			} else {
				var temparray = currentlyselected.split("-");
			}
			
			//check to see if unchecking or checking
			if($(this).is(':checked')){
				//add revid to hidden input field
				temparray.push(revid);
			} else {
				//remove from array
				temparray = jQuery.grep(temparray, function(value) {
				  return value != revid;
				});
			}

			//html number currently selected
			if (temparray[0] != null && temparray[0]!="") {
				if(temparray.length==1){
					$('#wprevpro_selectedrevsdiv').html('<b>'+temparray.length + '</b> '+adminjs_script_vars.Review_Selected+' (<span class="dashicons dashicons-search" style="font-size: 16px;vertical-align: middle;"></span>'+adminjs_script_vars.Show+')');
					$('#wprevpro_selectedrevsdiv').show();
					//hide other filters because they are overwritten
					//$('.revselectedhide').hide(3000);
					if($('#wprevpro_t_showreviewsbyid_sel').val()=="these"){
						$('.revselectedhide').css("background-color","#d4d4d4");
					}
				} else if(temparray.length>1){
					$('#wprevpro_selectedrevsdiv').html('<b>'+temparray.length + '</b> '+adminjs_script_vars.Reviews_Selected+' (<span class="dashicons dashicons-search" style="font-size: 16px;vertical-align: middle;"></span>'+adminjs_script_vars.Show+')');
					$('#wprevpro_selectedrevsdiv').show();
					//$('.revselectedhide').hide(3000);
					if($('#wprevpro_t_showreviewsbyid_sel').val()=="these"){
						$('.revselectedhide').css("background-color","#d4d4d4");
					}
				} else {
					$('#wprevpro_selectedrevsdiv').html('');
					$('#wprevpro_selectedrevsdiv').hide();
					//$('.revselectedhide').slideDown(3000);
					$('.revselectedhide').css("background-color","#ffffff");
				}
			} else {
				$('#wprevpro_selectedrevsdiv').html('');
				$('#wprevpro_selectedrevsdiv').hide();
				//$('.revselectedhide').show(3000);
				$('.revselectedhide').css("background-color","#ffffff");
			}
			
			//convert array back to string and input it to field
			var stringtemparray = temparray.join('-');
			$('#wprevpro_t_showreviewsbyid').val(stringtemparray);
		});
		
		
		//change pagination style thickbox
		$( "#wprevpro_btn_paginationstyle" ).click(function() {
			var url = "#TB_inline?width=600&height=510&inlineId=tb_content_paginationstyle";
			tb_show("Modify Pagination Button/Page Number Style", url);
			$( "#wprevpro_t_ps_bw" ).focus();
			$( "#TB_window" ).css({ "width":"630px","margin-left": "-300px" });
			$( "#TB_ajaxContent" ).css({ "width":"600px" });
			changebtnstylepreview();
		});
		//updating button preview when something changes
		$( ".updatebtnstyle" ).change(function() {
				changebtnstylepreview();
		});
		function changebtnstylepreview(){
			
			var btnorpagenums = $( "#wprevpro_t_load_more_porb" ).val();
			
			var borderwidth = $( "#wprevpro_t_ps_bw" ).val();
			var borderradius = $( "#wprevpro_t_ps_br" ).val();
			var bordercolor = $( "#wprevpro_t_ps_bcolor" ).val();
			var bgcolor = $( "#wprevpro_t_ps_bgcolor" ).val();
			var fontcolor = $( "#wprevpro_t_ps_fontcolor" ).val();
			var fontsize = $( "#wprevpro_t_ps_fsize" ).val();
			var paddingtop = $( "#wprevpro_t_ps_paddingt" ).val();
			var paddingbottom = $( "#wprevpro_t_ps_paddingb" ).val();
			var paddingleft = $( "#wprevpro_t_ps_paddingl" ).val();
			var paddingright = $( "#wprevpro_t_ps_paddingr" ).val();
			var margintop = $( "#wprevpro_t_ps_margint" ).val();
			var marginbottom = $( "#wprevpro_t_ps_marginb" ).val();
			var marginleft = $( "#wprevpro_t_ps_marginl" ).val();
			var marginright = $( "#wprevpro_t_ps_marginr" ).val();
			
			var prestyle = "";
			
			//add styles
			if(borderwidth!=''){prestyle = prestyle + ".brnprevclass{border-width:"+borderwidth+"px !important}";} 
			if(borderradius!=''){prestyle = prestyle + ".brnprevclass{border-radius:"+borderradius+"px !important}";} 
			if(bordercolor!=''){prestyle = prestyle + ".brnprevclass{border-color:"+bordercolor+" !important}";} 
			if(bgcolor!=''){prestyle = prestyle + ".brnprevclass{background-color:"+bgcolor+" !important}";}
			if(fontcolor!=''){prestyle = prestyle + ".brnprevclass{color:"+fontcolor+" !important}";}
			if(fontsize!=''){prestyle = prestyle + ".brnprevclass{font-size:"+fontsize+"px !important}";}
			
			if(paddingtop!=''){prestyle = prestyle + ".brnprevclass{padding-top:"+paddingtop+"px !important}";}
			if(paddingbottom!=''){prestyle = prestyle + ".brnprevclass{padding-bottom:"+paddingbottom+"px !important}";}
			if(paddingleft!=''){prestyle = prestyle + ".brnprevclass{padding-left:"+paddingleft+"px !important}";}
			if(paddingright!=''){prestyle = prestyle + ".brnprevclass{padding-right:"+paddingright+"px !important}";}
			
			if(marginleft!=''){prestyle = prestyle + ".brnprevclass{margin-left:"+marginleft+"px !important}";}
			if(marginright!=''){prestyle = prestyle + ".brnprevclass{margin-right:"+marginright+"px !important}";}
			
			
			var btnhtml='';
			
			if(btnorpagenums=='pagenums'){
				if(margintop!=''){prestyle = prestyle + ".wppro_page_numbers_ul{margin-top:"+margintop+"px !important}";}
				if(marginbottom!=''){prestyle = prestyle + ".wppro_page_numbers_ul{margin-bottom:"+marginbottom+"px !important}";}
				 btnhtml = '<div id="wppro_review_pagination1" class="wppro_pagination"><ul class="wppro_page_numbers_ul">	\
							<li><span class="brnprevclass wppro_page_numbers current">1</span></li>	\
							<li><span class="brnprevclass wppro_page_numbers">2</span></li>	\
							<li><span class="brnprevclass wppro_page_dots">…</span></li>	\
							<li><span class="brnprevclass wppro_page_numbers">8</span></li>	\
							<li><span class="brnprevclass wppro_page_numbers">&gt;</span></li>	\
						</ul></div>';
			} else {
				if(margintop!=''){prestyle = prestyle + ".brnprevclass{margin-top:"+margintop+"px !important}";}
				if(marginbottom!=''){prestyle = prestyle + ".brnprevclass{margin-bottom:"+marginbottom+"px !important}";}
				 btnhtml ='<button class="brnprevclass wprevpro_load_more_btn" id="wprev_load_more_btn_1">Load More</button>';
			}
			var insertstyle = "<style>"+prestyle+"</style>";

			var inserthtml = insertstyle + btnhtml;
			
			$( "#paginationstylepreviewdiv" ).html(inserthtml);
		}
		
		
		
		
		
		//when forcing all slides same height set autoheight to no, this fixes problem with autoheight
		/*
		$( "#wprevpro_t_review_same_height" ).change(function() {
				//reset colors if not editing, otherwise leave alone
				if($( "#wprevpro_t_review_same_height" ).val()=="yes"){
					$('#wprevpro_sliderheight2-radio').prop('checked',true);
				}
		});
		*/
		
		
		//------------when clicking row in review table, check or uncheck the check box-----------------------------------
		/*
		$("#review_list_select").on("click", "tr", function (event) {
			var rcheckbox = $(this).find("input[type='checkbox']");
			rcheckbox.trigger('click');
		});
		*/
		
	});

})( jQuery );