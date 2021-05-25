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
		
		//constants
		var pageclicked = 1;	//for pagination click
		var currentwindowpos = 0;	//used to set window position back when editing a review.
		
		//load reviews
		sendtoajaxreview('','','',"",'yes');
		
		//open a pop-up for editing the review========================
		//need to do
		//===============================
		
		//owner response pop-up
		$( "#wprevpro_bulkedit" ).click(function() {
			var url = "#TB_inline?width=700&height=500&inlineId=tb_content_bulkedit";
			tb_show('Bulk Edit Reviews', url);
			$( "#TB_window" ).css({ "width":"700px","height":"460px","margin-left": "-350px","margin-top": "150px" });
			$( "#TB_ajaxContent" ).css({ "width":"auto","height":"430px","max-height":"430px" });
			
		});
		
		//bulk edit tags------------------
		$( "#wprevpro_nr_tags_bulk_submit" ).click(function() {
			var tags = $("#wprevpro_nr_tags_bulk").val();
			var selopt = $("#wprevpro_nr_tags_bulk_sel").val();
			var editwhat = 'tags';
			if(tags!='' || selopt=='delete'){
				bulkeditajax(tags,'','',selopt,editwhat);
			}
		});
		$( "#wprevpro_nr_tags_bulk_sel" ).change(function() {
			var selopt = $("#wprevpro_nr_tags_bulk_sel").val();
			if(selopt=='delete'){
				$("#wprevpro_nr_tags_bulk" ).prop( "disabled", true );
			} else {
				$("#wprevpro_nr_tags_bulk" ).prop( "disabled", false );
			}
		});
		//-------------------------------
		//bulk edit cats------------------
		$( "#wprevpro_nr_categories_bulk_submit" ).click(function() {
			var cats = $("#wprevpro_nr_categories_bulk").val();
			var selopt = $("#wprevpro_nr_categories_bulk_sel").val();
			var editwhat = 'cats';
			if(cats!='' || selopt=='delete'){
				bulkeditajax('','',cats,selopt,editwhat);
			}
		});
		$( "#wprevpro_nr_categories_bulk_sel" ).change(function() {
			var selopt = $("#wprevpro_nr_categories_bulk_sel").val();
			if(selopt=='delete'){
				$("#wprevpro_nr_categories_bulk" ).prop( "disabled", true );
			} else {
				$("#wprevpro_nr_categories_bulk" ).prop( "disabled", false );
			}
		});
		//-------------------------------
		//bulk edit posts------------------
		$( "#wprevpro_nr_postid_bulk_submit" ).click(function() {
			var posts = $("#wprevpro_nr_postid_bulk").val();
			var selopt = $("#wprevpro_nr_postid_bulk_sel").val();
			var editwhat = 'posts';
			if(posts!='' || selopt=='delete'){
				bulkeditajax('',posts,'',selopt,editwhat);
			}
		});
		$( "#wprevpro_nr_postid_bulk_sel" ).change(function() {
			var selopt = $("#wprevpro_nr_postid_bulk_sel").val();
			if(selopt=='delete'){
				$("#wprevpro_nr_postid_bulk" ).prop( "disabled", true );
			} else {
				$("#wprevpro_nr_postid_bulk" ).prop( "disabled", false );
			}
		});
		//-------------------------------
		
		//ajax for bulk edit
		function bulkeditajax(tags,postids,categories,selopt,editwhat){
			var filterbytext = $("#wprevpro_filter_table_name").val();
			var filterbyrating = $("#wprevpro_filter_table_min_rating").val();
			var filterbytype = $("#wprevpro_filter_table_type").val();
			var filterbylang = $("#wprevpro_filter_table_lang").val();
			var filterbypageid = $("#wprevpro_filter_table_pageid").val();
			
			$("#savingformimg_"+editwhat).show();
			
			var senddata = {
					action: 'wpfb_bulk_edit',	//required
					wpfb_nonce: adminjs_script_vars.wpfb_nonce,
					tags: tags,
					postids: postids,
					categories: categories,
					selopt: selopt,
					editwhat: editwhat,
					filtertext: filterbytext,
					filterrating: filterbyrating,
					filtertype: filterbytype,
					filterlang: filterbylang,
					filterpageid: filterbypageid,
					};
					console.log(senddata);

				jQuery.post(ajaxurl, senddata, function (response){
					console.log(response);
					$("#savingformimg_"+editwhat).hide();
					$("#update_form_msg_"+editwhat).show();
					$("#update_form_msg_"+editwhat).html(response);
					setTimeout(function(){ 
						$("#update_form_msg_"+editwhat).hide();
					  }, 5000);
					//reload review list to reflect updates.
					sendtoajaxreview('','','',"");

				
				});
		}
		
		
		
		//for showing/hiding the media inputs
		$( "#wprevpro_addmedia" ).click(function() {
			$("#mediainputs").slideToggle();
		});
		
		//owner response pop-up
		$( "#wprevpro_btn_ownerresponse" ).click(function() {
			//var url = "#TB_inline?width=600&height=400&inlineId=tb_content_ownerresponse";
			//tb_show('', url);
			//$( "#TB_window" ).css({ "width":"600px","height":"310px","margin-left": "-300px","margin-top": "250px" });
			//$( "#TB_ajaxContent" ).css({ "width":"auto","height":"280px","max-height":"280px" });
			
			$("#tb_content_ownerresponse").toggle('slow');
		});
		
		//for adding default avater url to input rlimg
		$( ".rlimg" ).click(function() {
			var tempsrc = $(this).attr('src');
			$("#wprevpro_nr_avatar_url").val(tempsrc);
			$("#wprevpro_nr_avatar_url").select();
			$("#avatar_preview").attr("src",tempsrc);
		});
		
		//for hiding and showing file upload form
		$( "#wprevpro_importtemplates" ).click(function() {
			$("#importform").slideToggle();
		});
		
		//help button clicked
		$( "#wprevpro_helpicon" ).click(function() {
		  openpopup(adminjs_script_vars.popuptitle, adminjs_script_vars.popupmsg, "");
		});
		
		//remove all button
		$( "#wprevpro_removeallbtn" ).click(function() {
			console.log(adminjs_script_vars.globalwprevtypearray);
			var typearray = JSON.parse(adminjs_script_vars.globalwprevtypearray);
			var btnhtml = '';
			for(var i=0; i<typearray.length; i++){
				var tempopt = 'del_'+typearray[i].toLowerCase();
				btnhtml = btnhtml + '<a class="button rmrevbtn dashicons-before dashicons-no" href="?page=wp_pro-reviews&opt_type=type&opt='+tempopt+'">'+typearray[i]+'</a>';
			}
			var btnhtml2 = ''
			var pagearray = JSON.parse(adminjs_script_vars.pagenamearray);
			for(var i=0; i<pagearray.length; i++){
				var tempopt = encodeURIComponent(pagearray[i]);
				btnhtml2 = btnhtml2 + '<a class="button rmrevbtn dashicons-before dashicons-no" href="?page=wp_pro-reviews&opt_type=page&opt='+tempopt+'">'+pagearray[i]+'</a>';
			}
		  openpopup(adminjs_script_vars.popuptitle1, adminjs_script_vars.popupmsg1, '<a class="button rmrevbtn dashicons-before dashicons-no" href="?page=wp_pro-reviews&opt=delall">'+adminjs_script_vars.all_reviews+'</a>'+btnhtml+'<p>'+adminjs_script_vars.popupmsg3+'</p>'+btnhtml2);
		});	

		//upgrade to pro
		$( ".wprevpro_upgrade_needed" ).click(function() {
		  openpopup(adminjs_script_vars.popuptitle2, '<p>'+adminjs_script_vars.popupmsg2+'</p>', '<a class="button dashicons-before  dashicons-cart" href="?page=wp_pro-get_pro">'+adminjs_script_vars.upgrade_here+'</a>');
		});		

		//launch pop-up windows code--------
		function openpopup(title, body, body2){

			//set text
			jQuery( "#popup_titletext").html(title);
			jQuery( "#popup_bobytext1").html(body);
			jQuery( "#popup_bobytext2").html(body2);
			
			var popup = jQuery('#popup_review_list').popup({
				width: 450,
				offsetX: -125,
				offsetY: 0,
			});
			
			popup.open();
			//set height
			var bodyheight = Number(jQuery( ".popup-content").height()) + 10;
			jQuery( "#popup_review_list").height(bodyheight);

		}
		//--------------------------------
		//hide or show new review form ----------
		$( "#wprevpro_addnewreviewbtn" ).click(function() {
		  //jQuery("#wprevpro_new_review").show("slow");
		  	var winwidth = $(window).width();
			var popwidth = 1000;
			var popoffset = -125;
			if(winwidth<1000){
				popwidth = winwidth - 100;
				popoffset = -50;
			}
		  var popup = jQuery('#wprevpro_new_review').popup({
				width: popwidth,
				height: 700,
				offsetX: popoffset,
				offsetY: -50,
			});
			popup.open();
			jQuery("#new_review_overlay").show();
			clearformvalues();
			//hide update button.
			jQuery("#wprevpro_updatereviewbtn_ajax").hide();
			
			$( "#wprevpro_nr_title" ).focus();
			
			//change date format to date.
			$('#wprevpro_nr_date')[0].type = 'date';
			//set to today
			var now = new Date();
			var day = ("0" + now.getDate()).slice(-2);
			var month = ("0" + (now.getMonth() + 1)).slice(-2);
			var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
			$('#wprevpro_nr_date').val(today);
			
			
			
			//if we have a previously entered review then show duplicate link
			if(lastupdatedrevid>0){
				//check to see if the id is shown in the table, this wont work unless it is
				if($('#review_list').find('#'+lastupdatedrevid).length){
					jQuery("#wprevpro_btn_copyprevrev").show();
				} else {
					jQuery("#wprevpro_btn_copyprevrev").hide();
				}
			} else {
				jQuery("#wprevpro_btn_copyprevrev").hide();
			}

		});
		//for inserting previous values
		$( "#wprevpro_btn_copyprevrev" ).click(function() {
			
			var rid = lastupdatedrevid;
			var rowobject = $('#review_list').find('#'+lastupdatedrevid);
			
			editreview(rid,rowobject,'show');
			setTimeout(function(){ 
				$("#editrid").val('');
				$("#update_form_msg").val('');
		    }, 50);
		});
		
		
		$( "#wprevpro_addnewreview_cancel" ).click(function() {
		  jQuery("#wprevpro_new_review").hide();
		  jQuery("#new_review_overlay").hide();
		  //tb_remove();
		  //reload page without taction and tid
		  //setTimeout(function(){ 
			//window.location.href = "?page=wp_pro-reviews"; 
		  //}, 500);
		});
		$('body').click(function(evt){    
		   if(evt.target.id == "wprevpro_new_review" || $(evt.target).closest('#wprevpro_new_review').length || evt.target.id == "wprevpro_addnewreviewbtn" || $(evt.target).hasClass('reveditbtn')  || $(evt.target).hasClass('revcopybtn') || $(evt.target).closest('#TB_window').length){
			   //do nothing
		   } else {
			jQuery("#wprevpro_new_review").hide();
			jQuery("#new_review_overlay").hide();
		   }
			
		});
		
		//show form if rid hidden field has a value
		if(jQuery("#editrid").val()!=""){
			jQuery("#wprevpro_new_review").show("slow");
		}
		
		//upload avatar button----------------------------------
		$('#upload_avatar_button').click(function() {
			tb_show('Upload Reviewer Avatar', 'media-upload.php?referer=wp_pro-reviews&type=image&TB_iframe=true&post_id=0', false);
			return false;
		});
		window.send_to_editor = function(html) {
			var image_url = jQuery("<div>" + html + "</div>").find('img').attr('src');
			//var image_url = $('img',html).attr('src');
			$('#wprevpro_nr_avatar_url').val(image_url);
			$("#avatar_preview").attr("src",image_url);
			tb_remove();
		}
		
		//upload custom logo url button----------------------------------
		$('#upload_logo_button').click(function() {
			tb_show('Upload Review Logo', 'media-upload.php?referer=wp_pro-reviews&type=image&TB_iframe=true&post_id=0', false);
			
			//store old send to editor function
			window.restore_send_to_editor = window.send_to_editor;
			//overwrite send to editor function
			window.send_to_editor = function(html) {
				 var logo_image_url = jQuery("<div>" + html + "</div>").find('img').attr('src');
				 $('#wprevpro_nr_logo_url').val(logo_image_url);
				 $("#from_logo_preview").attr("src",logo_image_url);
				 
				 tb_remove();
				 //restore old send to editor function
				 window.send_to_editor = window.restore_send_to_editor;
			}
			
			
			return false;
		});
		
		//copy last custom values btn_copy_last_urls
		$('#btn_copy_last_urls').click(function() {
			var temp_logo_image_url = $('#from_logo_last').val();
			$('#wprevpro_nr_logo_url').val(temp_logo_image_url);
			
			var temp_from_url = $('#from_url_last').val();
			$('#wprevpro_nr_from_url').val(temp_from_url);
			
			$("#from_logo_preview").attr("src",temp_logo_image_url);
			
		});

		//ajax for hide or delete btn clicked for a review
		$("#review_list").on("click", ".revdelbtn", function (event) {
			//grab the id for this review
			var rid = $(this).closest('tr').prop("id");
			var rowobject = $(this).closest('tr');
				//post to server
			sendtoajax(rid,"deleterev",rowobject);
		});
		
		$("#review_list").on("click", ".hiderevbtn", function (event) {
			//grab the id for this review
			var rid = $(this).closest('tr').prop("id");
			var rowobject = $(this).closest('tr');
				//post to server
			sendtoajax(rid,"hideshow",rowobject);
		
		});
		
		//for edit sort weight btn click
		$("#review_list").on("click", ".sweditbtn", function (event) {
			//hide this
			$(this).hide();
			$(this).prev('.swcurrent').hide();
			//show div
			$(this).next('.sw_div').show();
			$(this).next('.sw_div').find('.swnewval').focus();
		});
		//update the sort weight then show updated value if success.swnewval
		$("#review_list").on("click", ".swname_save", function (event) {
			var newsw = $(this).prev('.swnewval').val();
			var rid = $(this).closest('tr').prop("id");
			var rowobject = $(this).closest('tr');
			//send to ajax to save
			sendtoajax(rid,"updatesw",rowobject,newsw);
		});
		//same as above for pressing enter key
		$("#review_list").on("keyup", ".swnewval", function (event) {
			if (event.keyCode === 13) {
			var newsw = $(this).val();
			var rid = $(this).closest('tr').prop("id");
			var rowobject = $(this).closest('tr');
			//send to ajax to save
			sendtoajax(rid,"updatesw",rowobject,newsw);
			}
		});
		
		//for copy review btn click
		$("#review_list").on("click", ".revcopybtn", function (event) {

			var rid = $(this).closest('tr').prop("id");
			var rowobject = $(this).closest('tr');
			editreview(rid,rowobject,'hide');
			//click the save button
			setTimeout(function(){ 
				$("#editrid").val('');
				$("#update_form_msg").val('');
				$("#wprevpro_savereviewbtn_ajax").trigger( "click" );
		    }, 50);
			
			
		});
		//for edit review btn click
		$("#review_list").on("click", ".reveditbtn", function (event) {
			//change date format to text.
			$('#wprevpro_nr_date')[0].type = 'text';
			
			jQuery("#wprevpro_updatereviewbtn_ajax").show();
			jQuery("#wprevpro_btn_copyprevrev").hide();
			var rid = $(this).closest('tr').prop("id");
			var rowobject = $(this).closest('tr');
			editreview(rid,rowobject,'show');
		});
		
		function editreview(rid,rowobject,showhide='show'){
			
			currentwindowpos = $([document.documentElement, document.body]).scrollTop();
						
			//grab the id for this review
			//var rid = $(thisclick).closest('tr').prop("id");
			//var rowobject = $(thisclick).closest('tr');
			
			var rtype = $(rowobject).attr("rtype");
			var name = rowobject.find('.wprev_row_reviewer_name').text();
			var wprev_row_userpic = rowobject.find('.wprev_row_userpic').find('img').attr('src');
			var wprev_row_rating = parseInt(rowobject.find('.wprev_row_rating').text());
			var wprev_row_review_title = rowobject.find('.wprev_row_review_text').attr( "rtitle" );
			var wprev_row_review_text = rowobject.find('.wprev_row_review_text_span').html();
			var wprev_row_created_time = rowobject.find('.wprev_row_created_time').html();
			var wprev_row_from_name = rowobject.find('.wprev_row_created_type').attr( "from_name" );
			var wprev_row_from_logo = rowobject.find('.wprev_row_created_type').attr( "from_logo" );
			var wprev_row_from_url = rowobject.find('.wprev_row_created_type').attr( "from_url" );
			var wprev_row_reviewer_email = rowobject.find('.wprev_row_created_type').attr( "remail" );
			var wprev_row_company_name = rowobject.find('.wprev_row_created_type').attr( "cname" );
			var wprev_row_company_title = rowobject.find('.wprev_row_created_type').attr( "ctitle" );
			var wprev_row_company_url = rowobject.find('.wprev_row_created_type').attr( "curl" );
			var wprev_row_consent = rowobject.find('.wprev_row_created_type').attr( "rconsent" );
			var wprev_row_cats = rowobject.find('.wprev_row_created_type').attr( "rcats" );
			var wprev_row_post = rowobject.find('.wprev_row_created_type').attr( "rpostid" );
			var wprev_row_pageid = rowobject.find('.wprev_row_created_type').attr( "pageid" );
			var wprev_row_pagename = rowobject.find('.wprev_row_created_type').attr( "pagename" );
			var wprev_row_hidestars = rowobject.find('.wprev_row_created_type').attr( "hidestars" );
			var wprev_language_code = rowobject.find('.wprev_row_created_type').attr( "language_code" );
			var wprev_tags = rowobject.find('.wprev_row_created_type').attr( "rtags" );
			
			var wppro_owners_id =  rowobject.find('.wppro_owners_id').html();
			var wppro_owners_name =  rowobject.find('.wppro_owners_name').html();
			var wppro_owners_date =  rowobject.find('.wppro_owners_date').html();
			var wppro_owners_comment =  rowobject.find('.wppro_owners_comment').html();
			
			$("#wprevpro_owner_id").val(wppro_owners_id);
			$("#wprevpro_owner_name").val(wppro_owners_name);
			$("#wprevpro_owner_text").val(wppro_owners_comment);
			$("#wprevpro_owner_date").val(wppro_owners_date);
			
			//media object
			//clear first
			$( "input[name='wprevpro_media[]']").val('');
			var wppro_mediaurls = rowobject.find('.wprev_row_created_type').attr( "mediaurls" );
			if(wppro_mediaurls){
				var mediaobjectarray = JSON.parse(wppro_mediaurls);
				//loop through array and set values
				$.each( mediaobjectarray, function( i, l ){
					$( "#wprevpro_media" + i ).val(l);
				 // console.log( "Index #" + i + ": " + l );
				});
			}
			
			//media thumb object
			var wppro_mediathumburls = rowobject.find('.wprev_row_created_type').attr( "mediathumburls" );
			$( "input[name='wprevpro_mediathumb[]']").val('');
			if(wppro_mediathumburls){
				var mediathumbobjectarray = JSON.parse(wppro_mediathumburls);
				//loop through array and set values
				$.each( mediathumbobjectarray, function( i, l ){
					$( "#wprevpro_mediathumb" + i ).val(l);
				  //console.log( "Index #" + i + ": " + l );
				});
			}

			//show edit form and focus
			if(showhide=='show'){
		  	var winwidth = $(window).width();
			var popwidth = 1000;
			var popoffset = -125;
			if(winwidth<1000){
				popwidth = winwidth - 100;
				popoffset = -50;
			}
			var popup = jQuery('#wprevpro_new_review').popup({
				width: popwidth,
				height: 700,
				offsetX: popoffset,
				offsetY: -50,
			});
			popup.open();
			jQuery("#new_review_overlay").show();
			}
			
			
			$( "#wprevpro_nr_name" ).focus();
			//find values from rowobject and fill in edit form wprevpro_nr_rating, wprevpro_nr_text, wprevpro_nr_name, wprevpro_nr_avatar_url, wprevpro_nr_date
			$("#editrid").val(rid);
			$("#wprevpro_nr_name").val(name.replace('\\',''));
			$("#wprevpro_nr_avatar_url").val(wprev_row_userpic);
			//for radio
			//alert(wprev_row_rating);
			if(wprev_row_rating>0){
				$("input[name='wprevpro_nr_rating'][value='"+wprev_row_rating+"']").prop('checked', true);
			} else {
				$("#wprevpro_nr_rating0-radio").prop('checked', true);
			}
			//for hide star radio
			if(wprev_row_hidestars=='yes' || wprev_row_rating==0){
				$("input[name='wprevpro_nr_hidestars'][value='yes']").prop('checked', true);
			} else {
				$("input[name='wprevpro_nr_hidestars'][value='']").prop('checked', true);
			}
			
			$("#wprevpro_nr_date").val(wprev_row_created_time);
			$("#wprevpro_nr_text").val(wprev_row_review_text.replace('\\',''));
			$("#wprevpro_nr_title").val(wprev_row_review_title);
			
			$("#wprevpro_nr_email").val(wprev_row_reviewer_email);
			
			$("#wprevpro_nr_company_name").val(wprev_row_company_name);
			$("#wprevpro_nr_company_title").val(wprev_row_company_title);
			$("#wprevpro_nr_company_url").val(wprev_row_company_url);
			
			$("#wprevpro_nr_consent").val(wprev_row_consent);
			$("#wprevpro_nr_categories").val(wprev_row_cats);
			$("#wprevpro_nr_postid").val(wprev_row_post);
			
			$("#wprevpro_nr_pagename").val(wprev_row_pagename);
			$("#wprevpro_nr_pageid").val(wprev_row_pageid);
			$("#wprevpro_nr_lang").val(wprev_language_code);
			
			$("#wprevpro_nr_tags").val(wprev_tags);
			
			//alert(wprev_row_pageid);

			$("#editrtype").val(rtype);
			//if type not manual then disable text rating and name
			if(rtype=='Manual' || rtype=='Submitted'){
				$("#wprevpro_nr_from").val(wprev_row_from_name);
				$("#wprevpro_nr_logo_url").val(wprev_row_from_logo);
				$("#from_logo_preview").attr("src",wprev_row_from_logo);
				$(':radio:not(:checked)[name="wprevpro_nr_rating"]').attr('disabled', false);
				$(':radio:not(:checked)[name="wprevpro_nr_hidestars"]').attr('disabled', false);
			} else {
				var resrtype = rtype.toLowerCase();
				//disable rating
				$(':radio:not(:checked)[name="wprevpro_nr_rating"]').attr('disabled', true);
				if(wprev_row_rating==0){
					//disable star display
					$(':radio:not(:checked)[name="wprevpro_nr_hidestars"]').attr('disabled', true);
				}
				//$('#wprevpro_nr_text').prop('readonly', true);
				//$('#wprevpro_nr_name').prop('readonly', true);
				//hide link and url
				$("#wprevpro_nr_from").val(resrtype);
				$('#wprevpro_nr_from option:not(:selected)').prop('disabled', true);
				//$('#wprevpro_nr_from_url').prop('readonly', true);
			}
			
			//hide or show certain fields if this is a submitted review
			$( "#showmorefields" ).show();
			if(rtype=='Submitted'){
				$(".socialreviewfield").hide();
				$(".submittedreviewfield").show();
			} else {
				$(".socialreviewfield").show();
				$(".submittedreviewfield").hide();
			}
			
			//set the from url
			$("#wprevpro_nr_from_url").val(wprev_row_from_url);
			
			//update src of avatar
			$("#avatar_preview").attr("src",wprev_row_userpic);
			
			//show hide custom logo url
			hideshowcustomlogo();
		}
		
		//for showing custom logo
		//hide or show local business settings---------------
		$( "#wprevpro_nr_from" ).change(function() {
			hideshowcustomlogo();
		});
		function hideshowcustomlogo(){
			var tempval = $( "#wprevpro_nr_from" ).val();
			if(tempval!="custom"){
				$('#div_customlogoupload').hide('slow');
			} else {
				$('#div_customlogoupload').show('slow');
			}
		}
		
		function clearformvalues(){
			$("#wprevpro_owner_id").val('');
			$("#wprevpro_owner_name").val('');
			$("#wprevpro_owner_text").val('');
			$("#wprevpro_owner_date").val('');

			$(':radio:not(:checked)[name="wprevpro_nr_rating"]').attr('disabled', false);
			$(':radio:not(:checked)[name="wprevpro_nr_rating"]').attr('disabled', false);
			$(':radio:not(:checked)[name="wprevpro_nr_hidestars"]').attr('disabled', false);
			$('#wprevpro_nr_from option:not(:selected)').prop('disabled', false);
			$("#wprevpro_nr_rating5-radio").prop('checked', true);
			$("#wprevpro_nr_hidestars0-radio").prop('checked', true);
			//set the from url
			$("#wprevpro_nr_from_url").val('');
			$("#wprevpro_nr_logo_url").val('');
			
			//update src of avatar
			$("#avatar_preview").attr("src",'');
			
			$("#wprevpro_nr_date").val('');
			$("#wprevpro_nr_title").val('');
			
			$("#wprevpro_nr_email").val('');
			
			$("#wprevpro_nr_company_name").val('');
			$("#wprevpro_nr_company_title").val('');
			$("#wprevpro_nr_company_url").val('');
			
			$("#wprevpro_nr_consent").val('');
			$("#wprevpro_nr_categories").val('');
			$("#wprevpro_nr_postid").val('');
			
			$("#wprevpro_nr_pagename").val('');
			$("#wprevpro_nr_pageid").val('');
			$("#wprevpro_nr_lang").val('');
			
			$("#wprevpro_nr_tags").val('');
			$("#wprevpro_nr_name").val('');
			$("#wprevpro_nr_text").val('');
			$('#wprevpro_nr_text').html('');
			$("#wprevpro_nr_from").val('');
			
			$("#wprevpro_nr_avatar_url").val('');
			//clear media values
			$('[name ^="wprevpro_media"]').val('');
			$("#editrid").val('');
			$("#editrtype").val('');
			$("#from_logo_preview").attr("src",'');
			
			
			
		}
		
		//to show all review edit fields
		$( "#showmorefields" ).click(function() {
			$(".socialreviewfield").show(3000);
			$(".submittedreviewfield").show(3000);
			$(this).hide();
		});
		
		//ajax for hiding and deleting
		function sendtoajax(rid,whattodo,rowobject,newsortweight=0){
			//if(Number(newsortweight)<1){
			//	newsortweight = 0;
			//} else {
				newsortweight = Number(newsortweight);
			//}
			var senddata = {
					action: 'wpfb_hide_review',	//required
					wpfb_nonce: adminjs_script_vars.wpfb_nonce,
					reviewid: rid,
					myaction: whattodo,
					sortweight: newsortweight
					};

				jQuery.post(ajaxurl, senddata, function (response){
				//console.log(response);
					var res = response.split("-");
					if(res[1]=="hideshow"){
						//change icon if hiding or showing
						if(res[2]=="yes"){
							//hiding this one
							rowobject.find('.hiderevbtn').removeClass('dashicons-visibility');
							rowobject.find('.hiderevbtn').removeClass('text_green');
							rowobject.find('.hiderevbtn').addClass('dashicons-hidden');
							rowobject.addClass('darkgreybg');
						} else {
							rowobject.find('.hiderevbtn').removeClass('dashicons-hidden');
							rowobject.find('.hiderevbtn').addClass('dashicons-visibility');
							rowobject.find('.hiderevbtn').addClass('text_green');
							rowobject.removeClass('darkgreybg');
						}
						if(res[2]=="fail"){
							alert(adminjs_script_vars.msg1);
						}
					}
					if(res[1]=="deleterev"){
						if(res[2]=="success"){
							//hide the row
							jQuery("#"+rid).hide("slow");
						} else {
							alert(adminjs_script_vars.msg2);
						}
					}
					if(res[1]=="updatesw"){
						if(res[2]=="success"){
							//update the current value with this one
							rowobject.find('.swcurrent').html(newsortweight);
							rowobject.find('.swcurrent').show();
							rowobject.find('.sweditbtn').show();
							rowobject.find('.sw_div').hide();
						} else {
							alert(adminjs_script_vars.msg4);
						}
					}
				
				});
		}
		
		//--------for searching--------------------------------------
		//for search box------------------------------
		$('#wprevpro_filter_table_name').on('input', function() {
			var myValue = $("#wprevpro_filter_table_name").val();
			var myLength = myValue.length;
			if(myLength>1 || myLength==0){
			//search here
				sendtoajaxreview('','','',"");
			}
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
			}  else if(sorttype=="rating_type") {
				sorttype="recommendation_type";
			}else if(sorttype=="stext") {
				sorttype="review_length";
			} else if(sorttype=="stime") {
				sorttype="created_time_stamp";
			} else if(sorttype=="company") {
				sorttype="company_name";
			}
		  sendtoajaxreview('1',sorttype,sortdir,"");
		});
		
		//for search select box------------------------------
		$( "#wprevpro_filter_table_min_rating" ).change(function() {
				sendtoajaxreview('','','',"");
		});
		//for fliter type select------------------------------
		$( "#wprevpro_filter_table_type" ).change(function() {
				sendtoajaxreview('','','',"");
		});
		//for fliter lang select------------------------------
		$( "#wprevpro_filter_table_lang" ).change(function() {
				sendtoajaxreview('','','',"");
		});
		$( "#wprevpro_filter_table_pageid" ).change(function() {
				sendtoajaxreview('','','',"");
		});
		//for pagination bar-----------------------------------
		$(".wpfb_review_list_pagination_bar").on("click", "span", function (event) {
			pageclicked = $(this).text();
			//console.log('page:'+pageclicked);
			var sortbyval = $(this).attr( 'sortbyval' );
			var sortd = $(this).attr( 'sortd' );
			sendtoajaxreview('',sortbyval,sortd,"");
		});
		//to get a URL parameter
		function getUrlVars() {
			var vars = {};
			var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
				vars[key] = value;
			});
			return vars;
		}
		
		//for clicking meta-data taglist
		$("#review_list").on("click", ".clmetadata", function (event) {
			$(this).next('.metadatavalues').toggle();
		});
		
		//to get the review list
		function sendtoajaxreview(notused,sortbyval,sortd,selrevs,firstload='no'){

			$("#reviewlistspinner").show();
			//console.log('cpage:'+pageclicked);
			var filterbytext = $("#wprevpro_filter_table_name").val();
			var filterbyrating = $("#wprevpro_filter_table_min_rating").val();
			var filterbytype = $("#wprevpro_filter_table_type").val();
			var filterbylang = $("#wprevpro_filter_table_lang").val();
			var filterbypageid = $("#wprevpro_filter_table_pageid").val();
			//used to check if coming from email link so we filter by type if so
			if(firstload=='yes'){
				var urlfiltertype = getUrlVars()["revfilter"];
				if(urlfiltertype!='' && typeof urlfiltertype != 'undefined'){
				filterbytype=urlfiltertype;
				$("#wprevpro_filter_table_type").val(filterbytype);
				}
			}
			if(filterbytype!='all'){
				$("#wprevpro_filter_table_type").addClass('greenbg');
			} else {
				$("#wprevpro_filter_table_type").removeClass('greenbg');
			}
			if(filterbylang!='all'){
				$("#wprevpro_filter_table_lang").addClass('greenbg');
			} else {
				$("#wprevpro_filter_table_lang").removeClass('greenbg');
			}
			if(filterbypageid!='all'){
				$("#wprevpro_filter_table_pageid").addClass('greenbg');
			} else {
				$("#wprevpro_filter_table_pageid").removeClass('greenbg');
			}
			
			//clear list and pagination bar
			$( "#review_list" ).html("");
			$( ".wpfb_review_list_pagination_bar" ).html("");
			var senddata = {
					action: 'wpfb_find_reviews',	//required
					wpfb_nonce: adminjs_script_vars.wpfb_nonce,
					sortby: sortbyval,
					sortdir: sortd,
					filtertext: filterbytext,
					filterrating: filterbyrating,
					filtertype: filterbytype,
					filterlang: filterbylang,
					filterpageid: filterbypageid,
					pnum:pageclicked,
					curselrevs:selrevs
					};
					console.log(senddata);
				jQuery.post(ajaxurl, senddata, function (response){
					$("#reviewlistspinner").hide();
					//console.log(response);
				var object = JSON.parse(response);
				console.log(object);

				var htmltext;
				var userpic;
				var editdellink;
				var hideicon;
				var url_tempeditbtn;
				var reviewtext = '';
				var reviewername = '';
				var userpicwarning = '';
				var reviewfound = false;
				
					$.each(object, function(index) {
						if(object[index]){
						if(object[index].id){
							
							reviewfound = true;

							//userpic
							userpic="";
							if(object[index].userpiclocal!=""){
								userpic = '<img wprevid="'+object[index].id+'" class="imgprofilepic" style="-webkit-user-select: none;width: 50px;" src="'+object[index].userpiclocal+'">';
								editdellink = '<span title="'+adminjs_script_vars.Edit+'" class="reveditbtn dashicons dashicons-edit"></span><span title="'+adminjs_script_vars.Delete+'" class="revdelbtn text_red dashicons dashicons-trash"></span><span title="'+adminjs_script_vars.Copy+'" class="revcopybtn dashicons dashicons-admin-page"></span>';
							} else {
								if(object[index].type=="Facebook"){
									//userpiclocal is blank and this is FB type so need warning.
									//userpicwarning = '<div id="userpicwarning">Notice: The PHP Copy function was not able to save the Facebook Profile Images to your server. First, try retrieving the FB reviews again. If that doesn\'t work then make sure the Copy function is enabled on your server. This plugin will still work, however Facebook now expires Profile Images after a while. When they expire the plugin will try to auto-update the images when you are on this page, if that doesn\'t work you will need to delete the FB reviews and re-download them.</div>';
								}
								userpic = '<img wprevid="'+object[index].id+'" class="imgprofilepic" style="-webkit-user-select: none;width: 50px;" src="'+object[index].userpic+'">';
								editdellink = '<span title="'+adminjs_script_vars.Edit+'" class="reveditbtn dashicons dashicons-edit"></span><span title="Delete" class="revdelbtn text_red dashicons dashicons-trash"></span><span title="'+adminjs_script_vars.Copy+'" class="revcopybtn dashicons dashicons-admin-page"></span>';
							}
							//hide link
							if(object[index].hide!="yes"){
								hideicon = '<i title="Shown" class="hiderevbtn dashicons dashicons-visibility text_green" aria-hidden="true"></i>';
								var greybgclass = '';
							} else {
								hideicon = '<i title="Hidden" class="hiderevbtn dashicons dashicons-hidden" aria-hidden="true"></i>';
								var greybgclass = 'darkgreybg';
							}
							//stripslashes
							reviewtext = String(object[index].review_text);
							reviewtext = reviewtext.replace(/\\'/g,'\'').replace(/\"/g,'"').replace(/\\\\/g,'\\').replace(/\\0/g,'\0');
							
							reviewername= String(object[index].reviewer_name);
							reviewername = reviewername.replace(/\\'/g,'\'').replace(/\"/g,'"').replace(/\\\\/g,'\\').replace(/\\0/g,'\0');
							
							//review title
							var reviewtitle ='';
							if(object[index].review_title){
								reviewtitle =''+object[index].review_title+'';
							}
							
							//company title
							var companytitle = '';
							var ctitle = '';
							if(object[index].company_title){
								ctitle = object[index].company_title;
								companytitle ='<br><small>'+ctitle+'</small>';
							}
							//company name
							var companyname = '';
							var companynamebr = '';
							if(object[index].company_name){
								companyname =object[index].company_name;
								companynamebr = '<br><i><small>'+companyname+'</small></i>';
							}
							//company url
							var companyurl = '';
							var curl = '';
							if(object[index].company_url){
								curl = object[index].company_url;
								companyurl ='<br><a href="'+object[index].company_url+'" target="_blank">'+companyname+'</a>';
							}
							//revieweremail
							var revieweremail = '';
							var revieweremailbr = '';
							if(object[index].reviewer_email){
								revieweremail =object[index].reviewer_email;
								revieweremailbr = '<br><i><small>'+revieweremail+'</small></i>';
							}
							
							//from_name, used when manually adding a review from another site
							var fromname = '';
							if(object[index].from_name){
								fromname ='-'+object[index].from_name;
							}
							//change cats and pages to comma seperated list
							var catslist ='';
							if(object[index].categories){
							var catslist = object[index].categories;
							catslist = catslist.replace(/"/g, '');
							catslist = catslist.replace("[", "");
							catslist = catslist.replace("]", "");
							catslist = catslist.replace(/-/g, "");
							catslist = catslist.trim();
							}
							var postslist ='';
							if(object[index].posts){
							var postslist = object[index].posts;
							postslist = postslist.replace(/"/g, '');
							postslist = postslist.replace("[", "");
							postslist = postslist.replace("]", "");
							postslist = postslist.replace(/-/g, "");
							postslist = postslist.trim();
							}
							var taglist='';
							if(object[index].tags){
							var tagsjsonstring = object[index].tags;
							var tagobj = jQuery.parseJSON( tagsjsonstring );
							var taglist=tagobj.join();
							}
							var metadatalist='';
							if(object[index].meta_data && object[index].meta_data!=''){
							var metadatajsonstring = object[index].meta_data;
							var metadataobj = JSON.parse( metadatajsonstring );
							$.each(metadataobj, function( index, value ) {
								if(value!='' && value!='null' && value){
									metadatalist = metadatalist + ( index + ": " + value+", " );
								}
							});
							}
							
							//for FB recommendations
							var rchtml="";
							if(typeof object[index].recommendation_type != 'undefined' || object[index].recommendation_type!="" || !object[index].recommendation_type){
								rchtml=object[index].recommendation_type;
							}
							//owner_response
							var ownerreshtml = '';
							var ownerres= '';
							if(object[index].owner_response!=""){
								ownerres=JSON.parse(object[index].owner_response);
								//console.log(object[index].owner_response);
								//console.log(ownerres);
								ownerreshtml = '<div class="wppro_owners_res_div"><div class="wppro_revres_title">'+adminjs_script_vars.Review_Response+':</div><div><span class="wppro_owners_id">'+ownerres.id+'</span><span class="wppro_owners_name">'+ownerres.name+'</span> - <span class="wppro_owners_date">'+ownerres.date+'</span></div><div><span class="wppro_owners_comment">'+ownerres.comment+'</span></div></div>'
							}
							//taglist
							var taglisthtml = '';
							if(taglist!=''){
								taglisthtml = '<div><small> (tags: '+taglist+')</small></div>';
							}
							//postlist
							var postslisthtml = '';
							if(postslist!=''){
								postslisthtml = '<span><small> (postIDs: '+postslist+')</small></span>';
							}
							//taglist
							var catlisthtml = '';
							if(catslist!=''){
								catlisthtml = '<span><small> (catIDs: '+catslist+')</small></span>';
							}
							//meta data
							var metadatahtml = '';
							if(metadatalist!=''){
								metadatahtml = '<div><small><i>(<span class="clmetadata">meta_data</span><span style="display:none;" class="metadatavalues">: '+metadatalist+'</span>)</i></small></div>';
							}
							var fromurllink = object[index].from_url;
							if(object[index].type=='Facebook' && fromurllink==''){
								fromurllink = "https://www.facebook.com/pg/"+object[index].pageid+"/reviews/";
							}
						
							htmltext = htmltext + '<tr id="'+object[index].id+'" class="'+greybgclass+'" rtype="'+object[index].type+'">	\
								<th scope="col" class="manage-column">'+hideicon+' '+editdellink+'</th>	\
								<th scope="col" class="wprev_row_userpic manage-column tcenter">'+userpic+'<br><span class="wprev_row_reviewer_name">'+reviewername+'</span>'+revieweremailbr+companynamebr+companytitle+'</th>	\
								<th scope="col" class="wprev_row_rating manage-column tcenter"><b>'+object[index].rating+'</b></br><b>'+rchtml+'</b></th>	\
								<th scope="col" rtitle="'+reviewtitle+'" class="wprev_row_review_text manage-column"><span class="wprev_row_review_title_span">'+reviewtitle+'</span><span class="wprev_row_review_text_span">'+reviewtext+'</span>'+ownerreshtml+taglisthtml+postslisthtml+catlisthtml+metadatahtml+'</th>	\
								<th scope="col" class="wprev_row_created_time manage-column tcenter">'+object[index].created_time+'</th>	\
								<th scope="col" class="manage-column tcenter">'+object[index].review_length+'<br>'+object[index].review_length_char+'<br>'+object[index].language_code+'</th>	\
								<th scope="col" class="manage-column tcenter"><a href="'+fromurllink+'" target="_blank">'+object[index].pagename+'</a></br><small><i>'+object[index].pageid+'</i></small></th>	\
								<th scope="col" class="manage-column wprev_row_created_type tcenter" from_logo="'+object[index].from_logo+'" from_name="'+object[index].from_name+'" from_url="'+object[index].from_url+'" rconsent="'+object[index].consent+'" rcats="'+catslist+'" rtags="'+taglist+'" hidestars="'+object[index].hidestars+'" language_code="'+object[index].language_code+'" rpostid="'+postslist+'" pageid="'+object[index].pageid+'" pagename="'+object[index].pagename+'" remail="'+revieweremail+'" cname="'+companyname+'" ctitle="'+ctitle+'"  curl="'+curl+'" mediaurls=\''+object[index].mediaurlsarrayjson+'\' mediathumburls=\''+object[index].mediathumburlsarrayjson+'\' ">'+object[index].type+fromname+'</th>	\
								<th scope="col" class="manage-column tcenter"><b class="swcurrent">'+object[index].sort_weight+'</b><span class="sweditbtn dashicons dashicons-edit cursorpointer" title="change" alt="change"></span><div class="sw_div" style="display:none;"><input type="text" class="swnewval" id="swname'+object[index].id+'"><span class="dashicons dashicons-yes swname_save cursorpointer" title="save" alt="save"></span></div></th>	\
							</tr>';
							reviewtext ='';
						}
						}
					});
					
					if(reviewfound==false){
						htmltext = '<tr><th colspan="10" scope="col" class="manage-column">'+adminjs_script_vars.msg3+'</th></tr>';
						
					}
					
					//display fb user image waring if set
					if(userpicwarning!=''){
						$( "#wprevpro_new_review" ).after(userpicwarning);
					}
					$( "#review_list" ).html(htmltext);
					//check images on this page for missing ones.
					wppro_checkimagexists();
					
					//pagination bar------------------
					var numpages = Number(object['totalpages']);
					var reviewtotalcount = Number(object['reviewtotalcount']);

					var pagebarhtml="";
					if(numpages>1){

						var blue_grey;
						var i;
						var numpages = Number(object['totalpages']);
						var curpage = Number(object['pagenum']);
						for (i = 1; i <= numpages; i++) {
							if(i==curpage){blue_grey = " blue_grey";} else {blue_grey ="";}
							pagebarhtml = pagebarhtml + '<span class="button'+blue_grey+'" sortbyval="'+sortbyval+'" sortd="'+sortd+'">'+i+'</span>';
						}
						
					}
					//pagebarhtml = pagebarhtml + " <span id='rl_totalrevcount'>" +reviewtotalcount+ " "+adminjs_script_vars.Total_Reviews+"</span>"
					$( "#rl_totalrevcount" ).html(reviewtotalcount+ " "+adminjs_script_vars.Total_Reviews);
						$( ".wpfb_review_list_pagination_bar" ).html(pagebarhtml);

					if(reviewtotalcount==0){
						$(".wpfb_review_list_pagination_bar").hide();
					} else {

						$(".wpfb_review_list_pagination_bar").show();
					}
					if(lastupdatedrevid>0 && currentwindowpos > 0){
						//try to scroll to correct id
						$([document.documentElement, document.body]).scrollTop(currentwindowpos);
					}
					
				});
		}

		//check if image is found
		function wppro_checkimagexists(){
			var imgarray = $("#review_list").find('img');
			var tempid = '';;
			//console.log(imgarray);
			imgarray.each(function() {
			  $( this ).error(function() {
				tempid = $( this ).attr('wprevid');
				var rtype = $( this ).closest('tr').attr('rtype');
				//console.log(rtype);
				//try to update it here with ajax call
				if(tempid>0 && rtype=='Facebook'){
				  var senddata = {
					action: 'wprp_update_profile_pic',	//required
					wpfb_nonce: adminjs_script_vars.wpfb_nonce,
					cache: false,
					processData : false,
					contentType : false,
					revid: tempid,
					};
					//send to ajax to update db
					var jqxhr = jQuery.post(adminjs_script_vars.wpfb_ajaxurl, senddata, function (data){
						console.log(data);
					});
				}
				});
			});
		}
		
		//form validation
		$("#newreviewform").submit(function(){ 

			  if ($('input[name=wprevpro_nr_rating]:checked').length) {
				   // at least one of the radio buttons was checked
				   //return true; // allow whatever action would normally happen to continue
				   
			  } else {
				   // no radio button was checked
				   alert("Please select review value.");
				   return false; // stop whatever action would normally happen
			  }
			 //if we are editing a review then make sure they didn't delete the source location  
			 if(jQuery( "#editrid").val()!="" && jQuery( "#wprevpro_nr_pagename").val()==""){	//
				 alert("Please enter a Source Location");
				$( "#wprevpro_nr_pagename" ).focus();
				return false;
			 }
		
			if(jQuery( "#wprevpro_nr_name").val()==""){
				alert("Please enter a name.");
				$( "#wprevpro_nr_name" ).focus();
				return false;
			} else {
				return true;
			}

		});
		
		//wprevpro_btn_pickpages open thickbox----------------
		$( "#wprevpro_btn_pickpages" ).click(function() {
			var url = "#TB_inline?width=600&height=600&inlineId=tb_content_page_select";
			tb_show("Locations", url);
			$( "#selectrevstable" ).focus();
			$( "#TB_window" ).css({ "width":"730px","height":"700px","margin-left": "-415px" });
			$( "#TB_ajaxContent" ).css({ "width":"auto","height":"670px" });
	
			//search through and check one if needed
			var pageid = $('#wprevpro_nr_pageid').val();
			if(pageid!=''){
				$("input[name='wprevpro_t_rpage'][value='" + pageid + "']").attr('checked', 'checked');
			}
		});
		//when checking a page check box. update number selected
		$( ".pageselectclass" ).click(function() {
			var pageid = $('input.pageselectclass:checked').val();
			var pagename = $('input.pageselectclass:checked').attr('pagename');
			if(pagename!=""){
				$('#wprevpro_nr_pagename').val(pagename);
			} else {
				$('#wprevpro_nr_pagename').val('');
			}
			$('#wprevpro_nr_pageid').val(pageid);
		});
		
		//for updating the form without closing it, sending via ajax
		$( "#wprevpro_updatereviewbtn_ajax" ).click(function() {
			saveupdatereview('update');
		});
		$( "#wprevpro_savereviewbtn_ajax" ).click(function() {
			saveupdatereview('save');
		});
		
		var lastupdatedrevid=0;
		
		function saveupdatereview(actiontype){
			$('#savingformimg').show();
			//get all the form values. newtemplateform
			//event.preventDefault();

			var formArray = $( "#newreviewform" ).serializeArray();
			//console.log(formArray);
			  var returnArray = {};
			  var mediaarray = [];
			  var mediathumbarray = [];

			  for (var i = 0; i < formArray.length; i++){
				 if(formArray[i]['name']=='wprevpro_media[]'){
					  mediaarray.push(formArray[i]['value']);
				  } else if(formArray[i]['name']=='wprevpro_mediathumb[]'){
					  mediathumbarray.push(formArray[i]['value']);
				  } else {
					returnArray[formArray[i]['name']] = formArray[i]['value'];
				  }
			  }
			  //now add pagefilter array since this is a multi-checkbox
			 returnArray.wprevpro_media = mediaarray;
			 returnArray.wprevpro_mediathumb = mediathumbarray;
			 //console.log(returnArray);
			 
  
			var jsonfields = JSON.stringify(returnArray);
			//console.log(jsonfields);
			var senddata = {
					action: 'wprp_save_review_admin',	//required
					wpfb_nonce: adminjs_script_vars.wpfb_nonce,
					data: jsonfields,
					};
				//send to ajax to update db
				var jqxhr = jQuery.post(ajaxurl, senddata, function (response){
					//console.log(response);
					var res = response.split("-");
					console.log(res);
					lastupdatedrevid = parseInt(res[0]);
					console.log(lastupdatedrevid);
					$('#savingformimg').hide();
					if(lastupdatedrevid>0){
						$('#update_form_msg').html(res[1]);
					} else {
						lastupdatedrevid = 0;
					}
					
					//console.log(lastupdatedrevid);
					//update review list
					sendtoajaxreview('','','','','');
					//hide message after 3 seconds
					setTimeout(function(){ 
						if(actiontype=='update'){
							$('#update_form_msg').html(''); 
						} else if(actiontype=='save'){
							$('#update_form_msg').html('');
							$( "#wprevpro_addnewreview_cancel" ).trigger( "click" );
						}
						}, 2000);
				});
		}
		


	});

})( jQuery );