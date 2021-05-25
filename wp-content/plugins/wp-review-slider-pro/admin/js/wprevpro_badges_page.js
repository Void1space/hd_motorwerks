
		
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
		var formid = 'new';
		
		var setlargiconurl = $('#wprevpro_badge_misc_liconurl').val();
		if(setlargiconurl==''){
			var customicon = false;
		} else {
			if(setlargiconurl.includes("-badge_50.png")){
				var customicon = false;
			} else {
				var customicon = true;
			}
		}
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
				changepreviewhtml();
				//alert('here');
			},
			// a callback to fire when the input is emptied or an invalid color
			clear: function() {}
		};
		 
		$('.my-color-field').wpColorPicker(myOptions);
		
		//show hide custom ratings rows 
		if($( "#wprevpro_badge_misc_customratingfrom" ).val()!=='input' || $( "#wprevpro_template_style" ).val()=='2'){
			$('.customratingsrow').hide('slow');
			//change value back to 4.5 and 17
			$('.wppro_badge1_SPAN_13').html("4.5");
			$('.wppro_badge1_SPAN_15').html("17");
		} else {
			$('.customratingsrow').show('slow');
			var tempavg = $("#wprevpro_badge_misc_cratingavg").val();
			$('.wppro_badge1_SPAN_13').html(tempavg);
			var temptotal = $( "#wprevpro_badge_misc_cratingtotal").val();
			$('.wppro_badge1_SPAN_15').html(temptotal);
		}
		$( "#wprevpro_badge_misc_customratingfrom" ).change(function() {
			if($( "#wprevpro_badge_misc_customratingfrom" ).val()!=='input' || $( "#wprevpro_template_style" ).val()=='2'){
				$('.customratingsrow').hide('slow');
				//change value back to 4.5 and 17
				$('.wppro_badge1_SPAN_13').html("4.5");
				$('.wppro_badge1_SPAN_15').html("17");
				//check to see if this is a Google only snippet and warn about total  
				//if($( "#wprevpro_badge_orgin" ).val()=="google" && $( "#wprevpro_badge_misc_customratingfrom" ).val()=="table"){
				//	if($( "#wprevpro_t_google_snippet_add" ).val()=='yes'){
				//	$( "#googlewarning" ).show();
				//	}
				//} else {
					$( "#googlewarning" ).hide();
				//}
			} else {
				$('.customratingsrow').show('slow');
				var tempavg = $( "#wprevpro_badge_misc_cratingavg" ).val();
				$('.wppro_badge1_SPAN_13').html(tempavg);
				var temptotal = $( "#wprevpro_badge_misc_cratingtotal" ).val();
				$('.wppro_badge1_SPAN_15').html(temptotal);
				$( "#googlewarning" ).hide();
			}
		});
		//if manual ratings then update preview
		$( "#wprevpro_badge_misc_cratingavg" ).change(function() {
			var tempavg = $( this ).val();
			$('.wppro_badge1_SPAN_13').html(tempavg);
		});
		$( "#wprevpro_badge_misc_cratingtotal" ).change(function() {
			var temptotal = $( this ).val();
			$('.wppro_badge1_SPAN_15').html(temptotal);
		});
		
		
		changepreviewhtml();
		stylenumchanged();
		//resetcolors();
		
		//reset colors to default
		$( "#wprevpro_pre_resetbtn" ).click(function() {
			resetcolors();
		});

		//for hiding and showing file upload form
		$( "#wprevpro_importtemplates" ).click(function() {
			$("#importform").slideToggle();
		});
		$( "#wprevpro_recaltotals" ).click(function() {
			$("#recalform").slideToggle();
		});
	
		//on template num change
		$( "#wprevpro_template_style" ).change(function() {
			stylenumchanged();
		});
		function stylenumchanged(){
				//reset colors if not editing, otherwise leave alone
				if($( "#edittid" ).val()==""){
				resetcolors();
				}
				//hide the text color one if this val is 4 since we don't need
				if($("#wprevpro_template_style").val()=='4'){
					$('.tc1div').hide();
					$('.brdiv').hide();
					$('.bsdiv').hide();
					$('.bc1div').hide();
					$('.bc2div').hide();
					$('.bcdiv').hide();
				} else {
					$('.tc1div').show();
					$('.brdiv').show();
					$('.bsdiv').show();
					$('.bc1div').show();
					$('.bc2div').show();
					$('.bcdiv').show();
				}
				changepreviewhtml();
		}
		
		//on review orgin change
		$( "#wprevpro_badge_orgin" ).change(function() {
			var typearray = JSON.parse(adminjs_script_vars.globalwprevtypearray);
				resetcolors();
				changepreviewhtml();
				var curval = $( "#wprevpro_badge_orgin" ).val();

					//hide or show the correct types of rows
					if(curval=='custom'){

						for(var i=0; i<typearray.length; i++){
							var tempopt = '.bo_'+typearray[i].toLowerCase();
							$( tempopt ).show();
						}

					} else {
						for(var i=0; i<typearray.length; i++){
							var tempopt = '.bo_'+typearray[i].toLowerCase();
							$( tempopt ).hide();
						}
						
						$( '.bo_'+curval ).show();
						$( "#googlewarning" ).hide();
					}
				//unselect all current pages
				$('.pageselectclass').removeAttr('checked');
				$('#wprevpro_selectedpagesspan').html('');
				
				//show/hide google notice
				//if(curval=='google'){
				//	$( "#wprevpro_gbadge_notice" ).show();
				//} else {
					$( "#wprevpro_gbadge_notice" ).hide();
				//}
				
				//show/hide submitted notice
				if(curval=='submitted'){
					$( "#submittedbadgenotice" ).show();
				} else {
					$( "#submittedbadgenotice" ).hide();
				}
				if(curval=='postid'){
					$( "#postidbadgenotice" ).show();
				} else {
					$( "#postidbadgenotice" ).hide();
				}
				
		});
				
		$( "#wprevpro_badge_bname" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_showstars" ).change(function() {
				changepreviewhtml();
		});

		$( "#wprevpro_badge_misc_bradius" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_bgcolor1" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_bgcolor2" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_bgcolor3" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_bgcolor3" ).change(function() {
				changepreviewhtml();
		});
		
		$( "#wprevpro_badge_misc_starcolor" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_tcolor1" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_tcolor2" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_tcolor3" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_shadow" ).change(function() {
				changepreviewhtml();
		});

		
		//for small images
		$('.wprevpro_badge_sm_ck').change(function() {
			changepreviewhtml();
			//show link url
			if ($(this).is(":checked")){
				$(this).parent().nextUntil('.smi_iconchecks').show();
			} else {
				$(this).parent().nextUntil('.smi_iconchecks').hide();
			}
		});

		
		$('#wprevpro_badge_misc_show_licon').change(function() {
			changepreviewhtml();
		});
				
		//custom css change preview
		var lastValue = '';
		$("#wprevpro_badge_css").on('change keyup paste mouseup', function() {
			if ($(this).val() != lastValue) {
				lastValue = $(this).val();
				changepreviewhtml();
			}
		});
		
		$('#wprevpro_badge_misc_width').change(function() {
			changepreviewhtml();
		});
		$('#wprevpro_badge_misc_widtht').change(function() {
			changepreviewhtml();
		});
	
		
		//upload large icon button----------------------------------
		$('#upload_licon_button').click(function() {
			tb_show('Upload Icon', 'media-upload.php?referer=wp_pro-badges&type=image&TB_iframe=true&post_id=0', false);
			//store old send to editor function
			window.restore_send_to_editor = window.send_to_editor;
			
			window.send_to_editor = function(html) {
				var image_url = jQuery("<div>" + html + "</div>").find('img').attr('src');
				$('#wprevpro_badge_misc_liconurl').val(image_url);
				$(".wppro_badge1_IMG_3").attr("src",image_url);
				$(".wppro_badge1_IMG_3").show();
				customicon = true;
				tb_remove();
				//restore old send to editor function
				 window.send_to_editor = window.restore_send_to_editor;
			}
		
			return false;
		});
		//upload small icon button----------------------------------
		$('#upload_sicon_button').click(function() {
			tb_show('Upload Icon', 'media-upload.php?referer=wp_pro-badges&type=image&TB_iframe=true&post_id=0', false);
			//store old send to editor function
			window.restore_send_to_editor = window.send_to_editor;
			
			window.send_to_editor = function(html) {
				var image_url = jQuery("<div>" + html + "</div>").find('img').attr('src');
				$('#wprevpro_badge_misc_si_custom_imgurl').val(image_url);
				tb_remove();
				//restore old send to editor function
				 window.send_to_editor = window.restore_send_to_editor;
				 changepreviewhtml();
			}
		
			return false;
		});
		
		//---------
		
		function changepreviewhtml(){
			var templatenum = $( "#wprevpro_template_style" ).val();
			var bname = $( "#wprevpro_badge_bname" ).val();
			
			var borderradius = $( "#wprevpro_badge_misc_bradius" ).val();
			var bcolor1 = $( "#wprevpro_badge_misc_bgcolor1" ).val();
			var bcolor2 = $( "#wprevpro_badge_misc_bgcolor2" ).val();
			var bcolor3 = $( "#wprevpro_badge_misc_bgcolor3" ).val();
			var starcolor = $( "#wprevpro_badge_misc_starcolor" ).val();
			var tcolor1 = $( "#wprevpro_badge_misc_tcolor1" ).val();
			var tcolor2 = $( "#wprevpro_badge_misc_tcolor2" ).val();
			var tcolor3 = $( "#wprevpro_badge_misc_tcolor3" ).val();
			var showlargicon = $('#wprevpro_badge_misc_show_licon').val();
			var largiconurl = $('#wprevpro_badge_misc_liconurl').val();
			var badgeorgin = $( "#wprevpro_badge_orgin" ).val();
			var shadow = $( "#wprevpro_badge_misc_shadow" ).val();
			
			var bwidth = $( "#wprevpro_badge_misc_width" ).val();
			var bwidtht = $( "#wprevpro_badge_misc_widtht" ).val();
			
			if(bwidth=='' || bwidth==0){
				bwidth = '100';
			}
			if(bwidtht==''){
				bwidtht = '%';
			}
			
			//hide star color if yelp or tripadvisor
			if(badgeorgin=='yelp' || badgeorgin=='tripadvisor'){
				$( "#rowstarcolor" ).hide();
			} else {
				$( "#rowstarcolor" ).hide();
			}
			
			var prestyle = '<style>.wppro_badge1_DIV_1 {border-radius: '+borderradius+'px;}.wppro_badge1_DIV_1 {border-top-color: '+bcolor1+';background-color: '+bcolor2+';border-bottom-color: '+bcolor3+';}.wppro_badge1_DIV_stars {color: '+starcolor+';}.ratingRow__star{color: '+starcolor+';}.wppro_b2__ratingProgress__fill{color: '+starcolor+';}span.wppro_badge1_SPAN_4 {color: '+tcolor1+';}.wppro_dashboardReviewSummary__avgRating{color: '+tcolor1+';}span.wppro_badge1_SPAN_13 {color: '+tcolor2+';}			.wppro_dashboardReviewSummary__avgReviews{color: '+tcolor2+';}.wppro_badge1_DIV_12 {color: '+tcolor3+';}.wppro_dashboardReviewSummary__right{color: '+tcolor3+';}.wppro_badge1_DIV_1 {width: '+bwidth+bwidtht+';}</style>';
			
			//big icon-------------
			if(showlargicon=="yes"){
				//only change if customicon = false;
				if(customicon==false){
					var sourceiconurl = adminjs_script_vars.pluginsUrl + '/public/partials/imgs/branding-'+badgeorgin+'-badge_50.png';
					$( "#wprevpro_badge_misc_liconurl" ).val(sourceiconurl);
				} else {
					var sourceiconurl = $( "#wprevpro_badge_misc_liconurl" ).val();
				}
				var sourceicon = '<img src="'+sourceiconurl+'" alt="'+badgeorgin+' logo" class="wppro_badge1_IMG_3" />';
				if(templatenum=='3'){
					sourceicon = '<img src="'+sourceiconurl+'" alt="'+badgeorgin+' logo" class="wppro_badge1_IMG_3 b3i" />';
				}
			} else {
				var sourceiconurl = '';
				$( "#wprevpro_badge_misc_liconurl" ).val('');
				var sourceicon = '';
				customicon = false;
			}
			
			//for small icons------
			var smallhtmlicon = '';
			var typearray = JSON.parse(adminjs_script_vars.globalwprevtypearray);
			var imgclass = 'wppro_badge1_IMG_4';
			if(templatenum=='2'){
				imgclass = 'wppro_badge2_IMG_4';
			}
			
			for(var i=0; i<typearray.length; i++){
				if(typearray[i]){
				var temptype = typearray[i].toLowerCase();
				var stypelogo='';
				if ($('#wprevpro_badge_sm_'+temptype).is(":checked")){
					// it is checked
					stypelogo = adminjs_script_vars.pluginsUrl + '/public/partials/imgs/'+temptype+'_small_icon.png';
					smallhtmlicon = smallhtmlicon + '<img src="'+stypelogo+'" alt="'+temptype+' logo" class="'+imgclass+'" />';
					//yelp warning
					if(temptype=='yelp'){
						var syelp = stypelogo;
					}
				}
				}
			}
			//check for custom small icon
			if($('#wprevpro_badge_misc_si_custom_imgurl').val()!=""){
				//check if checkbox checked wprevpro_badge_sm_custom
				if($('#wprevpro_badge_sm_custom').is(":checked")){
					smallhtmlicon = smallhtmlicon +'<img src="'+$('#wprevpro_badge_misc_si_custom_imgurl').val()+'" alt="logo" class="'+imgclass+'" />';
				}
			}
			
			if(smallhtmlicon!=""){
				smallhtmlicon = '<div class="wppro_badge1_DIV_13">'+smallhtmlicon+'</div>';
			}
			//yelp warning
			if(syelp){
					$('#yelpwarningmsg').show();
			} else {
				$('#yelpwarningmsg').hide();
			}
			
			//----------------
			
			// change stars to images if yelp or tripadvisor------------
			var starhtmldiv = '<span class="wprsp-star-full"></span><span class="wprsp-star-full"></span><span class="wprsp-star-full"></span><span class="wprsp-star-full"></span><span class="wprsp-star-half"></span></span>';
			if(badgeorgin=='yelp'){
				 starhtmldiv = adminjs_script_vars.pluginsUrl + '/public/partials/imgs/yelp_stars_4.5.png';
				 starhtmldiv = '<img src="'+starhtmldiv+'" alt="yelp logo" class="wppro_badge1_IMG_5" />';
			}
			if(badgeorgin=='tripadvisor'){
				 starhtmldiv = adminjs_script_vars.pluginsUrl + '/public/partials/imgs/tripadvisor_stars_4.5.png';
				 starhtmldiv = '<img src="'+starhtmldiv+'" alt="tripadvisor logo" class="wppro_badge1_IMG_5" />';
			}
			//--------------

			//---custom text
			var tempvalctext = $( "#wprevpro_badge_misc_ctext" ).val();
			if(tempvalctext==''){
				//tempvalctext = adminjs_script_vars.msg1;
			}
			var tempvalctext2 = $( "#wprevpro_badge_misc_ctext2" ).val();
			if(tempvalctext2==''){
				//tempvalctext2 = adminjs_script_vars.User_Reviews;
			}
			var tempvalctextb2 = $( "#wprevpro_badge_misc_ctext_b2" ).val();
			if(tempvalctextb2==''){
				tempvalctextb2 =  adminjs_script_vars.reviews;
			}
			
			//remove total for google style
			var temptotalrevs = "17";
			//if(badgeorgin=='google'){
			//	temptotalrevs ="";
			//}
			var tempavgrevs = "4.5";
			if($( "#wprevpro_badge_misc_customratingfrom" ).val()=="input"){
				temptotalrevs = $( "#wprevpro_badge_misc_cratingtotal" ).val();
				tempavgrevs = $( "#wprevpro_badge_misc_cratingavg" ).val();
			}
			if(tempvalctext==''){
				tempavgrevs='';
			}
			if(tempvalctext2==''){
				temptotalrevs='';
			}
			
			var style1html ='<div class="wprevpro_badge wppro_badge1_DIV_1">	\
		<div class="wppro_badge1_DIV_2">	\
			'+sourceicon+'<span class="wppro_badge1_SPAN_4">'+bname+'</span>	\
			<div class="wppro_badge1_DIV_5">	\
				<div class="wppro_badge1_DIV_stars">'+starhtmldiv+'	\
				</div>	\
				<div class="wppro_badge1_DIV_12">	\
					<span class="wppro_badge1_SPAN_13">'+tempavgrevs+'</span> '+tempvalctext+' <a href="#" title="'+tempvalctext2+'" class="wppro_badge1_A_14"><span class="wppro_badge1_SPAN_15">'+temptotalrevs+'</span> '+tempvalctext2+'</a>	\
				</div>'+smallhtmlicon+'</div></div></div>';
				
			var style3html ='<div class="wprevpro_badge wppro_badge1_DIV_1 b3s1">	\
		<div class="wppro_badge1_DIV_2 b3s2">	\
			'+sourceicon+'<span class="wppro_badge1_SPAN_4 b3s4">'+bname+'</span>	\
			<div class="wppro_badge1_DIV_5 b3s5">	\
				<div class="wppro_badge1_DIV_stars b3s6">'+starhtmldiv+'	\
				</div>	\
				<div class="wppro_badge1_DIV_12 b3s12">	\
					<span class="wppro_badge1_SPAN_13 b3s13">'+tempavgrevs+'</span> '+tempvalctext+' <a href="#" title="'+tempvalctext2+'" class="wppro_badge1_A_14"><span class="wppro_badge1_SPAN_15">'+temptotalrevs+'</span> '+tempvalctext2+'</a>	\
				</div>'+smallhtmlicon+'</div></div></div>';
				
			var style4html ='<div class="wprevpro_badge wppro_badge4_DIV_1 b4s1">	\
				<span class="wppro_badge1_DIV_stars b4s2">'+starhtmldiv+'	\
				</span>	\
				<span class="wppro_badge1_DIV_12 b3s12">	\
					<span class="wppro_badge1_SPAN_13 b3s13">'+tempavgrevs+'</span> '+tempvalctext+' <a href="#" title="'+tempvalctext2+'" class="wppro_badge1_A_14"><span class="wppro_badge1_SPAN_15">'+temptotalrevs+'</span> '+tempvalctext2+'</a>	\
				</span></div>';
				
			var smallicont2 = smallhtmlicon;
			if(smallicont2==''){
				smallicont2 = '20 <span>'+tempvalctextb2+'</span>';
			}

			var style2html = '<div class="wprevpro_badge wppro_badge1_DIV_1" id="wprev-badge-">\
<div class="wppro_dashboardReviewSummary">\
      <div class="wppro_dashboardReviewSummary__left">\
        <div class="wppro_dashboardReviewSummary__avgRating">4.4</div>\
		<div class="wppro_b2__rating" data-rating-value="4.4">\
			<div class="wppro_badge1_DIV_stars bigstar">'+starhtmldiv+'	</div>	\
		</div>\
        <div class="wppro_dashboardReviewSummary__avgReviews">'+smallicont2+'</div>\
      </div>\
      <div class="wppro_dashboardReviewSummary__right">\
		<div class="wppro_b2__ratingRow">\
		  <span>5</span><span class="wprevicon-star-full ratingRow__star"></span>\
			<div class="wppro_b2__ratingProgress">\
			  <div class="wppro_b2__ratingProgress__fill" style="width: 75.00%;"></div>\
			</div>\
		  <span class="wppro_b2__ratingRow__avg">15</span>\
		</div>\
		<div class="wppro_b2__ratingRow">\
		  <span>4</span><span class="wprevicon-star-full ratingRow__star"></span>\
			<div class="wppro_b2__ratingProgress">\
			  <div class="wppro_b2__ratingProgress__fill" style="width: 15.00%;"></div>\
			</div>\
		  <span class="wppro_b2__ratingRow__avg">3</span>\
		</div>\
		<div class="wppro_b2__ratingRow">\
		  <span>3</span><span class="wprevicon-star-full ratingRow__star"></span>\
			<div class="wppro_b2__ratingProgress">\
			  <div class="wppro_b2__ratingProgress__fill" style="width: 10.00%;"></div>\
			</div>\
		  <span class="wppro_b2__ratingRow__avg">2</span>\
		</div>\
		<div class="wppro_b2__ratingRow">\
		  <span>2</span><span class="wprevicon-star-full ratingRow__star"></span>\
			<div class="wppro_b2__ratingProgress">\
			  <div class="wppro_b2__ratingProgress__fill" style="width: 0.00%;"></div>\
			</div>\
		  <span class="wppro_b2__ratingRow__avg">0</span>\
		</div>\
		<div class="wppro_b2__ratingRow">\
		  <span>1</span><span class="wprevicon-star-full ratingRow__star"></span>\
			<div class="wppro_b2__ratingProgress">\
			  <div class="wppro_b2__ratingProgress__fill" style="width: 0.00%;"></div>\
			</div>\
		  <span class="wppro_b2__ratingRow__avg">0</span>\
		</div>\
      </div>\
</div>\
</div>';

			//for showing or hiding stars_5_yellow
			if($( "#wprevpro_badge_misc_showstars" ).val()=="no"){
				var starcss = '<style>.wppro_badge1_DIV_stars{display: none;}.wppro_badge1_DIV_12 {display: inline-block;}</style>';
				prestyle =  prestyle + starcss;
			}
			//for border shadow
			if(shadow=="no"){
				prestyle =  prestyle + '<style>.wppro_badge1_DIV_1 {box-shadow: unset;}</style>';
			}
						
			if($( "#wprevpro_badge_css" ).val()!=""){
				var customcss = '<style>'+$( "#wprevpro_badge_css" ).val()+'</style>';
				prestyle =  prestyle + customcss;
			}
			
			var temphtml;
			$( ".lgicondiv" ).show();
			$( ".smallicondiv" ).show();
			if(templatenum=='1'){
				$( "#wprevpro_template_preview" ).html(prestyle+style1html);
				$( ".t1oneonly" ).show();
				$( ".t2oneonly" ).hide();
			} else if(templatenum=='2'){
				$( "#wprevpro_template_preview" ).html(prestyle+style2html);
				//hide stuff if this is template 2
				$( ".t1oneonly" ).hide();
				$( ".t2oneonly" ).show();
			} else if(templatenum=='3'){
				$( "#wprevpro_template_preview" ).html(prestyle+style3html);
				$( ".t1oneonly" ).show();
				$( ".t2oneonly" ).hide();
			} else if(templatenum=='4'){
				$( "#wprevpro_template_preview" ).html(prestyle+style4html);
				$( ".t1oneonly" ).show();
				$( ".t2oneonly" ).hide();
				//hide large an small icon divs
				$( ".lgicondiv" ).hide();
				$( ".smallicondiv" ).hide();
			}
			
			//hide and show based on badge orgin wprevpro_btn_pickpages
				var curval = $( "#wprevpro_badge_orgin" ).val();
				if(curval=='manual'){
					//hide the select button and div
					$( ".divpickpagesrow" ).hide('slow');
					$( "#wprevpro_btn_pickpostids" ).hide('slow');
					$( ".ratingsfrom" ).show('slow');
					$( "#wprevpro_btn_pickpages" ).show('slow');
					$( ".customratingsrow" ).show('slow');
					$( "#wprevpro_btn_pickpages" ).hide('slow');
					$( ".ratingsfrom" ).hide('slow');
					$( ".customratingsrow" ).hide('slow');
				} else if(curval=='postid'){
					//hide everything not using.
					$( "#wprevpro_btn_pickpostids" ).show('slow');
					$( "#wprevpro_btn_pickpages" ).hide('slow');
					$( ".ratingsfrom" ).hide('slow');
					$( ".customratingsrow" ).hide('slow');
				} else {
					$( "#wprevpro_btn_pickpostids" ).hide('slow');
					$( ".divpickpagesrow" ).show('slow');
					$( ".ratingsfrom" ).show('slow');
					$( "#wprevpro_btn_pickpages" ).show('slow');
					$( ".customratingsrow" ).show('slow');
				}
		}
		
		function resetcolors(){
				var templatenum = $( "#wprevpro_template_style" ).val();
				var orgin = $( "#wprevpro_badge_orgin" ).val();
				//reset colors to default
				if(templatenum=='1' && orgin=='facebook'){
					$( "#wprevpro_badge_misc_bradius" ).val('0');
					$( "#wprevpro_badge_misc_bgcolor1" ).val('#6988FE');
					$( "#wprevpro_badge_misc_bgcolor2" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor3" ).val('#ffffff');
					$( "#wprevpro_badge_misc_starcolor" ).val('#6988FE');
					$( "#wprevpro_badge_misc_tcolor1" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).val('#6988FE');
					$( "#wprevpro_badge_misc_tcolor3" ).val('#666666');
					prestyle="";
					//reset color picker
					$('#wprevpro_badge_misc_bgcolor1').iris('color', '#6988FE');
					$('#wprevpro_badge_misc_bgcolor2').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor3').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_starcolor').iris('color', '#6988FE');
					$( "#wprevpro_badge_misc_tcolor1" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).iris('color','#6988FE');
					$( "#wprevpro_badge_misc_tcolor3" ).iris('color','#666666');
				}
				if(templatenum=='1' && orgin=='google'){
					$( "#wprevpro_badge_misc_bradius" ).val('0');
					$( "#wprevpro_badge_misc_bgcolor1" ).val('#2EA756');
					$( "#wprevpro_badge_misc_bgcolor2" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor3" ).val('#ffffff');
					$( "#wprevpro_badge_misc_starcolor" ).val('#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).val('#4F81F3');
					$( "#wprevpro_badge_misc_tcolor3" ).val('#666666');
					prestyle="";
					//reset color picker
					$('#wprevpro_badge_misc_bgcolor1').iris('color', '#2EA756');
					$('#wprevpro_badge_misc_bgcolor2').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor3').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_starcolor').iris('color', '#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).iris('color','#4F81F3');
					$( "#wprevpro_badge_misc_tcolor3" ).iris('color','#666666');
				}
				if(templatenum=='1'){
					if(orgin=='yelp' || orgin=='airbnb'){
					$( "#wprevpro_badge_misc_bradius" ).val('0');
					$( "#wprevpro_badge_misc_bgcolor1" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor2" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor3" ).val('#ffffff');
					$( "#wprevpro_badge_misc_starcolor" ).val('#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).val('#666666');
					prestyle="";
					//reset color picker
					$('#wprevpro_badge_misc_bgcolor1').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor2').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor3').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_starcolor').iris('color', '#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).iris('color','#666666');
					}
				}
				if(templatenum=='1' && orgin=='tripadvisor'){
					$( "#wprevpro_badge_misc_bradius" ).val('0');
					$( "#wprevpro_badge_misc_bgcolor1" ).val('#30a57e');
					$( "#wprevpro_badge_misc_bgcolor2" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor3" ).val('#ffffff');
					$( "#wprevpro_badge_misc_starcolor" ).val('#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).val('#666666');
					prestyle="";
					//reset color picker
					$('#wprevpro_badge_misc_bgcolor1').iris('color', '#30a57e');
					$('#wprevpro_badge_misc_bgcolor2').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor3').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_starcolor').iris('color', '#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).iris('color','#666666');
				}
				if(templatenum=='1'){
					if(orgin=='manual' || orgin=='submitted'){
					$( "#wprevpro_badge_misc_bradius" ).val('0');
					$( "#wprevpro_badge_misc_bgcolor1" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor2" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor3" ).val('#ffffff');
					$( "#wprevpro_badge_misc_starcolor" ).val('#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).val('#666666');
					prestyle="";
					//reset color picker
					$('#wprevpro_badge_misc_bgcolor1').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor2').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor3').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_starcolor').iris('color', '#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).iris('color','#666666');
					}
				}
				if(templatenum=='2'){
					$( "#wprevpro_badge_misc_bradius" ).val('0');
					$( "#wprevpro_badge_misc_bgcolor1" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor2" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor3" ).val('#ffffff');
					$( "#wprevpro_badge_misc_starcolor" ).val('#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).val('#666666');
					prestyle="";
					//reset color picker
					$('#wprevpro_badge_misc_bgcolor1').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor2').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor3').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_starcolor').iris('color', '#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).iris('color','#666666');
				}
		}

		//help button clicked
		$( "#wprevpro_helpicon_posts" ).click(function() {
		  openpopup(adminjs_script_vars.popuptitle, '<p>'+adminjs_script_vars.popupmsg+'</p>', "");
		});
		//display shortcode button click 
		$( ".wprevpro_displayshortcode" ).click(function() {
			//get id and badge type
			var tid = $( this ).parent().attr( "templateid" );
			var ttype = $( this ).parent().attr( "templatetype" );
			
		  if(ttype=="widget"){
			openpopup(adminjs_script_vars.popuptitle1, '<p>'+adminjs_script_vars.popupmsg1+'</p>', '');
		  } else {
			openpopup(adminjs_script_vars.popuptitle2, '<p>'+adminjs_script_vars.popupmsg2+' </br></br>[wprevpro_usebadge tid="'+tid+'"] </br></br>'+adminjs_script_vars.popupmsg3+'</br></br> <code>do_action( \'wprev_pro_plugin_action_badge\', '+tid+' ); </code></br></br>'+adminjs_script_vars.popupmsg4+' <a href="https://wpreviewslider.userecho.com/knowledge-bases/2/articles/1334-access-total-and-averages-with-shortcode-or-function" target="_blank">'+adminjs_script_vars.popupmsg5+'</a>. </p>', '');
		  }
		  
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
			window.location.href = "?page=wp_pro-badges"; 
		  }, 500);
		  
		});	
		
		//-------------------------------
		//form validation 
		$("#wprevpro_submittemplatebtn").click(function(){
			if(jQuery( "#wprevpro_template_title").val()==""){
				alert(adminjs_script_vars.msg2);
				$( "#wprevpro_template_title" ).focus();
				return false;
			}
			if(jQuery('.selectrevstable input[type=checkbox]:checked').length<1) {
				if($( "#wprevpro_badge_misc_customratingfrom" ).val()!="input"){
					if($( "#wprevpro_badge_orgin" ).val()!="manual" && $( "#wprevpro_badge_orgin" ).val()!="submitted" && $( "#wprevpro_badge_orgin" ).val()!="custom" && $( "#wprevpro_badge_orgin" ).val()!="postid"){
						alert(adminjs_script_vars.msg3);
						return false;
					}
				}
			}
			return true;
		});
		

		
		//hide or show rich snippet settings---------------
		$( "#wprevpro_t_google_snippet_add" ).change(function() {
			//if no then hide
			var tempval = $( "#wprevpro_t_google_snippet_add" ).val();
			if(tempval!="yes"){
				$('#snippetsettings').hide('slow');
			} else {
				$('#snippetsettings').show('slow');
				//check to see if this is a Google only snippet and warn about total  
				//if($( "#wprevpro_badge_orgin" ).val()=="google" && $( "#wprevpro_badge_misc_customratingfrom" ).val()=="table"){
				//	if($( "#wprevpro_t_google_snippet_add" ).val()=='yes'){
				//	$( "#googlewarning" ).show();
				//	}
				//} else {
					$( "#googlewarning" ).hide();
				//}
				
			}
			var tempval2 = $( "#wprevpro_t_google_snippet_type" ).val();
			if(tempval2=="Product"){
				$('#businessrichsnippetfields').hide();
				$('#productrichsnippetfields').show('slow');
				
			} else {
				$('#productrichsnippetfields').hide();
				$('#businessrichsnippetfields').show('slow');
			}
		});
		
		
		//hide or show local business settings---------------
		$( "#wprevpro_t_google_snippet_type" ).change(function() {
			//if no then hide
			var tempval = $( "#wprevpro_t_google_snippet_type" ).val();
			if(tempval=="Product"){
				$('#businessrichsnippetfields').hide();
				$('#productrichsnippetfields').show('slow');
				
			} else {
				$('#productrichsnippetfields').hide();
				$('#businessrichsnippetfields').show('slow');
			}
		});
		
		//wprevpro_btn_pickpages open thickbox----------------
		$( "#wprevpro_btn_pickpages" ).click(function() {
			var url = "#TB_inline?width=600&height=600&inlineId=tb_content_page_select";
			tb_show(adminjs_script_vars.msg4, url);
			$( "#selectrevstable" ).focus();
			$( "#TB_window" ).css({ "width":"730px","height":"700px","margin-left": "-415px" });
			$( "#TB_ajaxContent" ).css({ "width":"auto","height":"650px" });
		});
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
		
		//when changing custom text  wppro_badge1_DIV_12
		$( "#wprevpro_badge_misc_ctext" ).change(function() {
			changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_ctext2" ).change(function() {
			changepreviewhtml();
		});
		
		//when selecting overrall badge click options
		$( "#wprevpro_badge_misc_onclickaction" ).change(function() {
			var clickstyle = '<style>.wprevpro_badge{cursor: pointer;}</style>';
			if($(this).val()=="url"){
				$( ".linktourl" ).show('slow');
				$( ".slidouttr" ).hide();
			} else if($(this).val()=="slideout"){
				$( ".slidouttr" ).show('slow');
				$( ".linktourl" ).hide();
				$( ".hideforpopup" ).show();
				getslideoutdata();
			} else if($(this).val()=="popup"){
				$( ".slidouttr" ).show('slow');
				$( ".linktourl" ).hide();
				$( ".hideforpopup" ).hide();
				getslideoutdata();
			} else {
				$( ".slidouttr" ).hide('slow');
				$( ".linktourl" ).hide('slow');
				$( ".hideforpopup" ).show();
				clickstyle = '';
			}
			//add style css for cursor if we are adding click event to overall
			$( "#wprevpro_template_preview" ).append(clickstyle);
			
		});
		//get slide out data and add to preview div----------------
		if($('.wprevpro_slideout_container_body').html()==''){
			if($( "#wprevpro_badge_misc_sliderevtemplate" ).val()>0){
				getslideoutdata();
			}
		}
		//get popup data if needed
		if($('.wprevpro_popup_container_body').html()=='' && $( "#wprevpro_badge_misc_onclickaction" ).val()=='popup'){
			if($( "#wprevpro_badge_misc_sliderevtemplate" ).val()>0){
				getslideoutdata();
			}
		}
		$( "#wprevpro_badge_misc_sliderevtemplate" ).change(function() {
			getslideoutdata();
		});
		function getslideoutdata(){
			$( ".loading-image2" ).show();
			var revtemplateid='';
			revtemplateid = $( "#wprevpro_badge_misc_sliderevtemplate" ).val();

			var senddata = {
				action: 'wprp_get_slideout_revs',	//required
				wpfb_nonce: adminjs_script_vars.wpfb_nonce,
				fid: formid,
				rtid: revtemplateid,
				};
			//send to ajax to update db
			var jqxhr = jQuery.post(ajaxurl, senddata, function (response){
				$( ".loading-image2" ).hide();
				//console.log(response);

				if (!$.trim(response)){
					alert(adminjs_script_vars.msg5);
				} else {
					//add to preview div
					if($( "#wprevpro_badge_misc_onclickaction" ).val()=='slideout'){
						$( ".wprevpro_slideout_container_body" ).html(response);
					} else if($( "#wprevpro_badge_misc_onclickaction" ).val()=='popup'){
						$( ".wprevpro_popup_container_body" ).html(response);
					}
				}
				// on success refresh from preview
			});
			jqxhr.fail(function() {
			  alert( adminjs_script_vars.msg5 );
			});
		}
		
		//update slide style when changing
		 changeslideoutstyle();
		$( "#wprevpro_badge_misc_slidelocation" ).change(function() {
			changeslideoutstyle();
			//hide inputs based on value
			if($(this).val()=="top" || $(this).val()=="bottom"){
				$( ".slwidthdiv" ).hide();
				$( ".slheightdiv" ).show();
			} else {
				$( ".slheightdiv" ).hide();
				$( ".slwidthdiv" ).show();
			}
		});
		$( ".updatesliderinput" ).change(function() {
			changeslideoutstyle();
		});
		$('#wprevpro_badge_misc_slideheader').bind('input propertychange', function() {
		  changeslideoutstyle();
		});
		$('#wprevpro_badge_misc_slidefooter').bind('input propertychange', function() {
		  changeslideoutstyle();
		});

		function changeslideoutstyle(){
			
			//is this a popup or slideout
			var onclickaction = $( "#wprevpro_badge_misc_onclickaction" ).val();
			// onclickaction will equal no, url, slideout, or popup
			
			var bname = $( "#wprevpro_float_bname" ).val();
			var slidelocation = $( "#wprevpro_badge_misc_slidelocation" ).val();
			
			var slideheight = $( "#wprevpro_badge_misc_slheight" ).val();
			if(slideheight==""){
				slideheight='auto;';
			} else {
				slideheight=slideheight+'px;';
			}
			var slidewidth = $( "#wprevpro_badge_misc_slwidth" ).val();
			if(slidewidth==""){slidewidth=350;}
			
			//background color
			var lochtml='';
			var bgcolor1 = $( "#wprevpro_badge_misc_slbgcolor1" ).val();
			if(bgcolor1!=''){
				lochtml = lochtml + 'background: '+bgcolor1+';';
			}
			var bgbordercolor1 = $( "#wprevpro_badge_misc_slbordercolor1" ).val();
			if(bgbordercolor1!=''){
				lochtml = lochtml + 'border: 1px solid '+bgbordercolor1+';';
			}

			if(onclickaction=='popup'){
				//var bodystyle = '.wprevpro_popup_container_body {'+tempstyletext+'}';
				
				//lochtml = 'width: '+slidewidth+'px;height: '+slideheight;
				
				var locstyle = '.wprevpro_popup_container_inner {'+lochtml+'}';
				var formstyle = '<style>'+locstyle+'</style>';
				$( ".wprevpro_popup_container_style" ).html(formstyle);
				
				//add the header and footer html
				var headerhtml = $( "#wprevpro_badge_misc_slideheader" ).val();
				$( ".wprevpro_popup_container_header" ).html(headerhtml);
				var footerhtml = $( "#wprevpro_badge_misc_slidefooter" ).val();
				$( ".wprevpro_popup_container_footer" ).html(footerhtml);
			} else {
				
				if(slidelocation=="right"){
					lochtml = 'bottom: 0px;right: 0px;height: 100%;width: '+slidewidth+'px;';
				} else if(slidelocation=="left"){
					lochtml = 'bottom: 0px;left: 0px;height: 100%;width: '+slidewidth+'px;';
				} else if(slidelocation=="top"){
					lochtml = 'top: 0px;bottom:unset;width: 100%;height: '+slideheight;
				} else if(slidelocation=="bottom"){
					lochtml = 'top:unset;bottom: 0px;width: 100%;height: '+slideheight;
				}
			
				//slide padding
				var slidepaddingarray = [$( "#wprevpro_badge_misc_slpadding-top" ).val(), $( "#wprevpro_badge_misc_slpadding-right" ).val(), $( "#wprevpro_badge_misc_slpadding-bottom" ).val(),$( "#wprevpro_badge_misc_slpadding-left" ).val()];
				var arrayLength = slidepaddingarray.length;
				var tempstyletext='';
				for (var i = 0; i < arrayLength; i++) {
					if(slidepaddingarray[i]!=''){
						if(i==0){
							tempstyletext = tempstyletext + 'padding-top:' + slidepaddingarray[i] + 'px; ';
						} else if(i==1){
							tempstyletext = tempstyletext + 'padding-right:' + slidepaddingarray[i] + 'px; ';
						} else if(i==2){
							tempstyletext = tempstyletext + 'padding-bottom:' + slidepaddingarray[i] + 'px; ';
						} else if(i==3){
							tempstyletext = tempstyletext + 'padding-left:' + slidepaddingarray[i] + 'px; ';
						}
					}
				}
			
				var bodystyle = '.wprevpro_slideout_container_body {'+tempstyletext+'}';
				var locstyle = '.wprevpro_slideout_container {'+lochtml+'}';
				var formstyle = '<style>'+locstyle+bodystyle+'</style>';
				$( ".wprevpro_slideout_container_style" ).html(formstyle);
				//add the header and footer html
				var headerhtml = $( "#wprevpro_badge_misc_slideheader" ).val();
				$( ".wprevpro_slideout_container_header" ).html(headerhtml);
				var footerhtml = $( "#wprevpro_badge_misc_slidefooter" ).val();
				$( ".wprevpro_slideout_container_footer" ).html(footerhtml);
			}
			
			
		}		
		
		//if we are using the overall click then we demo it here
		$( "#wprevpro_template_preview" ).click(function() {
			var tempclickval = $( "#wprevpro_badge_misc_onclickaction" ).val();
			if(tempclickval=='url'){
				if(onclickurl!=""){
					var win = window.open(onclickurl, '_blank');
					if (win) {
						//Browser has allowed it to be opened
						win.focus();
					} else {
						//Browser has blocked it
						alert(adminjs_script_vars.msg1);
					}
				} else {
					alert(adminjs_script_vars.msg2);
				}
			} else if(tempclickval=='slideout'){
				//we need to do something here
				changeslideoutstyle();
				$('.wprevpro_slideout_container').toggle();
			} else if (tempclickval=='popup'){
				changeslideoutstyle();
				$('.wprevpro_popup_container').show();
			}
		});

		//close slideout onclick on everything but it
		$(document).click(function(event) { 
			if(!$(event.target).closest('.wprevpro_slideout_container').length && !$(event.target).closest('.updatesliderinput').length && !$(event.target).closest('#wprevpro_template_preview').length) {
				if($('.wprevpro_slideout_container').is(":visible")) {
					$('.wprevpro_slideout_container').hide();
				}
			}        
		});
		
		
	});

})( jQuery );

