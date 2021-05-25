<?php

/**
 * Provide a public-facing view for the form style
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/public/partials
 */
 //html code for the template style
$plugin_dir = WP_PLUGIN_DIR;
$imgs_url = esc_url( plugins_url( 'imgs/', __FILE__ ) );
//all values are in $formarray

//loop form fields and build output.

//$formfieldsarray = $formarray['form_fields'];
$formfieldsarray= json_decode($formarray['form_fields'], true);

$formfieldshtml = '';
$ratingrequired = '';

$form_misc_array = json_decode($formarray['form_misc'], true);

$btnwprevdefault = '';
//add btn style if set to default .btnwprevdefault
if(isset($form_misc_array['btnstyle'])){
	if($form_misc_array['btnstyle']=='default'){
		$btnwprevdefault = 'btnwprevdefault';
	}
}

//find post id and the current categories
$currentpostid = get_the_ID();
$catidarray = array();
$categories = get_the_category();
if(is_array($categories)){
	$arrlength = count($categories);
} else {
	$arrlength=0;
}
//check if this is a custom taxonomy like woocommerce
if($arrlength<1){
	//woocommerce check 
	if(taxonomy_exists('product_cat')){
	$categories = get_the_terms( $currentpostid, 'product_cat' );
		if(is_array($categories)){
		$arrlength = count($categories);
		}
	}
}
if($arrlength>0){
	for($x = 0; $x < $arrlength; $x++) {
		if(isset($categories[$x]->term_id)){
		$catidarray[] = $categories[$x]->term_id;	//array containing just the cat_IDs that this post belongs to, dashes added so we can use like search
		}
	}
}
//print_r($catidarray);

$jsoncatidarray = json_encode($catidarray);

//first find out what global logic values are if they are set
$globhiderest = '';
$globshowval = '';
$hideformhtml = '';

for ($x = 0; $x < count($formfieldsarray); $x++) {
	if($formfieldsarray[$x]['input_type']=='social_links'){
		if($formfieldsarray[$x]['hiderest']=='hide'){
			$globhiderest = 'hide';
		}
		if($formfieldsarray[$x]['showval']!=''){
			$globshowval = $formfieldsarray[$x]['showval'];
		}
	}
}

for ($x = 0; $x < count($formfieldsarray); $x++) {
	//only for non hidden fields
	if($formfieldsarray[$x]['hide_field']==''){
		//add required symbol
		$reqhtml="";
		$reqinput="";
		$hidesslinkshtml = '';
		$hideroformhtml ='';
		$checkreqinput='';
		if($formfieldsarray[$x]['input_type']=='review_rating' || $formfieldsarray[$x]['input_type']=='social_links'){
			$restclass = '';
		} else {
			if($globhiderest == 'hide'){
				//hide entire form and then show what we need to with javascript
				$hideroformhtml = 'style="display:none;"';
			}
			$restclass = 'rofform';
		}
		if($formfieldsarray[$x]['required']=='on'){
			$reqhtml='<span class="required symbol"></span>';
			$checkreqinput="required";
			//$reqinput="required oninvalid=\"this.setCustomValidity('".__('Please fill out this field.', 'wp-review-slider-pro')."')\" oninput=\"setCustomValidity('')\"";
			$reqinput="required";
		}
		if($globshowval!='' && $formfieldsarray[$x]['input_type']=='social_links'){
			//hide the social links and then show via javascript
			$hidesslinkshtml = 'style="display:none;"';
		}
		$formfieldshtml = $formfieldshtml . '<div '.$hidesslinkshtml.' '.$hideroformhtml.' class="wprevform-field wprevpro-field-'.esc_attr($formfieldsarray[$x]['name']).' '.$restclass.'">
					<label for="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'">'.esc_html($formfieldsarray[$x]['label']).'</label>
					'.$reqhtml.'<span class="before">'.esc_html($formfieldsarray[$x]['before']).'</span>';
					
		//find type and change based on type
		if($formfieldsarray[$x]['input_type']=='review_rating'){
			if($checkreqinput=="required"){
				$ratingrequired = 'yes';
			}
			$ratetype = '';
			if(isset($formfieldsarray[$x]['starornot']) && $formfieldsarray[$x]['starornot'] && $formfieldsarray[$x]['starornot']=='updown'){
				$ratetype = $formfieldsarray[$x]['starornot'];
			}
						
			$formfieldshtml = $formfieldshtml . '<div class="wprevpro-rating-wrapper field-wrap in-form">
							<fieldset contenteditable="false" id="wprevpro_review_rating" name="review_rating" class="wprevpro-rating" data-field-type="rating" tabindex="0">';
							
			//check rating type and add thumbs up or stars
			$hidestars = '';
			if($ratetype=='updown'){
				$hidestars = 'style=display:none;';
				$rateiconup = 'wprsp-thumbs-o-up';
				$rateicondown = 'wprsp-thumbs-o-down';
				if($formfieldsarray[$x]['star_icon'] && $formfieldsarray[$x]['star_icon']!=''){
					if($formfieldsarray[$x]['star_icon']=='smileys'){
						$rateiconup = 'wprsp-smile-o';
						$rateicondown = 'wprsp-frown-o';
					}
				}
				//updown html here
				$formfieldshtml = $formfieldshtml . '<span id="wppro_fvoteup" class="'.$rateiconup.' wppro_updown"></span><span id="wppro_fvotedown" class="'.$rateicondown.' wppro_updown"></span>';
			}
			//add stars, hiding if we are doing a thumbs up
			$maxrating = 5;
			if(isset($formfieldsarray[$x]['maxrating']) && $formfieldsarray[$x]['maxrating']>0){
				$maxrating = $formfieldsarray[$x]['maxrating'];
			}
			for ($k = 0; $k <= $maxrating; $k++) {
				$tempchecked='';
				if($formfieldsarray[$x]['default_form_value']==$k){
					$tempchecked='checked="checked"';
				}
				$formfieldshtml = $formfieldshtml . '<input '.$hidestars.' type="radio" id="wprevpro_review_rating-star'.$k.'" name="wprevpro_review_rating" value="'.$k.'" '.$tempchecked.'><label '.$hidestars.' for="wprevpro_review_rating-star'.$k.'" title="'.$k.' stars" class="wprevpro-rating-radio-lbl"></label>';
			}
			
			$formfieldshtml = $formfieldshtml . '</fieldset></div>';		
		
		} else if($formfieldsarray[$x]['input_type']=='text'){
			$tempautocomplete = "";
			if($formfieldsarray[$x]['name']=='reviewer_name'){
				$tempautocomplete = 'autocomplete="name"';
			} else if($formfieldsarray[$x]['name']=='company_name'){
				$tempautocomplete = 'autocomplete="organization"';
			}
			$formfieldshtml = $formfieldshtml . '<input id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" type="text" class="text" name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" value="'.esc_attr($formfieldsarray[$x]['default_form_value']).'" tabindex="0" placeholder="'.esc_attr($formfieldsarray[$x]['placeholder']).'" '.$reqinput.'>';
		
		} else if($formfieldsarray[$x]['input_type']=='textarea'){
			$formfieldshtml = $formfieldshtml . '<textarea id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" class="" '.$reqinput.' tabindex="0" placeholder="">'.esc_textarea($formfieldsarray[$x]['default_form_value']).'</textarea>';
		
		} else if($formfieldsarray[$x]['input_type']=='email'){
			$formfieldshtml = $formfieldshtml . '<input id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" type="email" class="text email" name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" value="'.esc_attr($formfieldsarray[$x]['default_form_value']).'" '.$reqinput.' tabindex="0" placeholder="'.esc_attr($formfieldsarray[$x]['placeholder']).'" autocomplete="email">';
		
		} else if($formfieldsarray[$x]['input_type']=='url'){
			$formfieldshtml = $formfieldshtml . '<input id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" type="url" class="text url" name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" value="'.esc_attr($formfieldsarray[$x]['default_form_value']).'" tabindex="0" placeholder="'.esc_attr($formfieldsarray[$x]['placeholder']).'" '.$reqinput.'>';
		
		} else if($formfieldsarray[$x]['input_type']=='review_avatar'){
			$formfieldshtml = $formfieldshtml . '<input name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'"type="file" accept="image/x-png,image/gif,image/jpeg" '.$reqinput.' tabindex="0">';
		
		}  else if($formfieldsarray[$x]['input_type']=='review_consent'){
			$formfieldshtml = $formfieldshtml . '<input name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'"type="checkbox" value="yes" '.$reqinput.'>';
			
		}  else if($formfieldsarray[$x]['input_type']=='social_links'){
			
			$btnclass = 'btnwprevdefault button';
			if($formfieldsarray[$x]['displaytype']=='sicon'){
				$btnclass = 'btnwprevdefault_sicon';
			} else if($formfieldsarray[$x]['displaytype']=='licon'){
				$btnclass = 'btnwprevdefault_licon';
			} 
			for ($k = 1; $k < 6; $k++) {
				if($formfieldsarray[$x]['lurl'.$k] && $formfieldsarray[$x]['lname'.$k]){
										
					$formfieldshtml = $formfieldshtml . '<a href="'.esc_attr($formfieldsarray[$x]['lurl'.$k]).'" target="_blank" class="'.$btnclass.'">';
					$lname = $formfieldsarray[$x]['lname'.$k];
					$linkurl = $formfieldsarray[$x]['lurl'.$k];
					//check to see if displaying icon, if so then need to search and return img html
					$formfieldshtml = $formfieldshtml . $this->wppro_returniconhtml($linkurl,$formfieldsarray[$x]['displaytype'],$lname);
					
					$formfieldshtml = $formfieldshtml .'</a>';
				}
			}
		}
	
		//after span changes for consent
		if($formfieldsarray[$x]['input_type']=='review_consent'){
			$formfieldshtml = $formfieldshtml . '<span class="wprev_consent">'.$formfieldsarray[$x]['after'].'</span> </div>';
		} else {
			$formfieldshtml = $formfieldshtml . '<span class="after">'.$formfieldsarray[$x]['after'].'</span> </div>';
		}
	}
}

//captcha settings
$formcaptchahtml = '';
if($form_misc_array['captchaon']=='v2' && $form_misc_array['captchasite']!='' && $form_misc_array['captchasecrete']!=''){
	$formcaptchahtml = '<script src="https://www.google.com/recaptcha/api.js"></script>
	<div class="g-recaptcha wprevform-field rofform" '.$hideroformhtml.' data-sitekey="'.esc_html($form_misc_array['captchasite']).'"></div>';
}

//required note at top of form
$requiredtext = "Required field";
if($form_misc_array['requiredlabelshow']!='hide'){
	if($form_misc_array['requiredlabeltext']!=""){
		$requiredtext = esc_html( $form_misc_array['requiredlabeltext'] );
	}
	$formrequiredtext = '<p class="wprevpro_required_notice"><span class="required symbol"></span>'.$requiredtext.'</p>';
} else {
	$formrequiredtext ='';
}

//button text
$btntext = "Submit Review";
if($form_misc_array['btntext']!=''){
	$btntext = esc_html($form_misc_array['btntext']);
}
$btnclass = "";
if($form_misc_array['btnclass']!=''){
	$btnclass = esc_html($form_misc_array['btnclass']);
}

//hide form behind button
$formhtml = "";
$showonclick = "no";
$showonclicktext = "Leave a Review";
$formhidestyle = '';
$showbtnhtml='';
$hidebtn ='';
if(!isset($form_misc_array['autopopup'])){
	$form_misc_array['autopopup']='no';
}

if($form_misc_array['showonclick']=='yes' || $form_misc_array['showonclick']=='popup'){
	$formhidestyle = 'style="display: none;"';
	$popupbtn = '';
	if($form_misc_array['showonclick']=='popup'){
		$popupbtn = 'ispopup="yes"';
	}
	if($form_misc_array['showonclicktext']!=''){
		$showonclicktext = esc_html($form_misc_array['showonclicktext']);
	}
	if($form_misc_array['autopopup']=='yeshide'){
		$hidebtn = 'style="display: none;"';
	}
	$showbtnhtml = '<button '.$hidebtn.' '.$popupbtn.' formid="'.esc_html($formarray['id']).'" class="button wprevpro_btn_show_form '.$btnclass.' '.$btnwprevdefault.'">'.$showonclicktext.'</button>';
}


//doing ajax or regular default submit
$submitbuttonhtml = '<input type="hidden" name="action" value="wprev_submission_form">
					<input type="submit" id="wprevpro_submit_review" name="wprevpro_submit_review" value="'.$btntext.'" class="button btnwprevsubmit '.$btnwprevdefault.' '.$btnclass.'" tabindex="0">';
$ajaxmsgdiv ='';
if(isset($form_misc_array['useajax'])){
	if($form_misc_array['useajax']=='yes'){
		$submitbuttonhtml = '<input type="submit" id="wprevpro_submit_ajax" name="wprevpro_submit_ajax" value="'.$btntext.'" class="button btnwprevsubmit '.$btnwprevdefault.' '.$btnclass.'" tabindex="0">';
		$ajaxmsgdiv = '<div id="wprevpro_div_form_'.esc_html($formarray['id']).'_ajaxmsg" class="wprevpro_form_msg" style="display: none;"></div>';
	}
}

//add headerhtml if set
$headerhtmlval = '';
if(isset($currentform[0]->form_html) && $currentform[0]->form_html!=''){
	$headerhtmlval ='<div class="wprevform-headerhtml">'.stripslashes($currentform[0]->form_html).'</div>';
}

//if we are using auto-popup
$popupvar = '';
$popuppadding = '';
$popupmodalstart = '<div>';
$popupmodalend = '</div>';

//also hiding if $wppl is equal to yes and then the URL contains the wppl variable set to yes
$hideformonpagestart = '';
$hideformonpageend = '';
$hideformonpagejs = '';
if($wppl=='yes'){
	//now see if the URL has it set
	if (isset($_GET['wppl'])) {
	  $wppl_url = $_GET['wppl'];
	} else {
	  //Handle the case where there is no parameter
	  $wppl_url = 'no';
	}
	if($wppl == $wppl_url){
		//looking for variable and found it, so show form and auto-popup
	} else {
		//looking for vairable, did not find it, hide form on page
		$hideformonpagestart = '<div style="display: none;">';
		$hideformonpageend ='</div>';
		$hideformonpagejs = 'hideformonpagejs';
	}
}

if($form_misc_array['showonclick']=='popup' || $form_misc_array['autopopup']!='no'){
	if($form_misc_array['autopopup']=='yesshow' || $form_misc_array['autopopup']=='yeshide'){
		$popupvar = 'autopopup="yes"';
	} else {
		$popupvar = 'autopopup=""';
	}

	$formhidestyle = 'style="display: none;"';
	if($showbtnhtml==''){
		//not showing button so get rid of form padding
		$popuppadding='padding: 0px;';
	}
	$popupmodalstart ='<div id="wprevmodal_myModal_'.esc_html($formarray['id']).'" class="wprevmodal_modal">
				  <div class="wprevmodal_modal-content">
					<span class="wprevmodal_close">&times;</span>';
	$popupmodalend = '</div></div>';
}


$formhtml = $formhtml . '<style>'.esc_html( $formarray['form_css'] ).'</style>
				'.$hideformonpagestart.'<div id="wprevpro_div_form_'.esc_html($formarray['id']).'" class="wprevpro_form" '.$popupvar.' style="display: block;'.$popuppadding.'">
				'.$showbtnhtml.$popupmodalstart.'
					<div id="wprevpro_div_form_inner_'.esc_html($formarray['id']).'" class="wprevpro_form_inner" '.$formhidestyle.'>
						<form class="wprev_review_form" name="wprevpro_form_'.esc_html($formarray['id']).'" id="wprevpro_form_'.esc_html($formarray['id']).'" enctype="multipart/form-data" action="'.esc_url( admin_url('admin-post.php') ).'" method="post" autocomplete="off">
						'.$headerhtmlval.$formrequiredtext.'
							'.$formfieldshtml.'
							'.$formcaptchahtml.'
							<input id="wprevpro_fid" name="wprevpro_fid" value="'.esc_attr($formarray['id']).'" type="hidden">
							<div class="wpreveprohme"><input type="text" id="name" name="name" value=""></div>
							<div class="wprevform-field wprevpro_submit rofform" '.$hideroformhtml.'>
								<label>
								'.wp_nonce_field( 'post_nonce', 'post_nonce_field' ).'
								'.$submitbuttonhtml.'
								<input id="wprevpro_rating_req" name="wprevpro_rating_req" value="'.esc_html($ratingrequired).'" type="hidden">
								<input type="hidden" id="wprev_catids" name="wprev_catids" value="'.$jsoncatidarray.'">
								<input type="hidden" id="wprev_postid" name="wprev_postid" value="'.$currentpostid.'">
								<input type="hidden" id="wprev_globhiderest" name="wprev_globhiderest" value="'.$globhiderest.'">
								<input type="hidden" id="wprev_globshowval" name="wprev_globshowval" value="'.$globshowval.'">
								<input type="hidden" name="submitted" id="submitted" value="true" />
								</label>
							</div>
							<div class="wprev_loader" style="display:none;"></div>
						</form>
					</div>
					'.$ajaxmsgdiv.$popupmodalend.'
				</div>'.$hideformonpageend;

//check if this form was just submitted by testing url. Display message if it was.$globhiderest = '';$globshowval = '';
if(!isset($_GET["wprevfs"])){
	$_GET["wprevfs"]='';
}
if(!isset($_GET["raid"])){
	$_GET["raid"]='';
}
if($_GET["wprevfs"]=="no"){
	//success message
	$sucmsg = "Thank you for your feedback!";
	if($form_misc_array['successmsg']!=''){
		$sucmsg = esc_html($form_misc_array['successmsg']);
	}
	echo '<div id="wprevpro_div_form_'.esc_html($formarray['id']).'" class="wprevpro_form wprevpro_submitsuccess" style="display: block;">
				'.$sucmsg.'
				</div>';
} else if($_GET["wprevfs"]=="yes"){
	//display errors, must get from option in db
	$erroroptions = get_option('wprevpro_form_errors');
	$raid = $_GET["raid"];
	$sucmsg = $erroroptions[$raid];
	echo '<div id="wprevpro_div_form_'.esc_html($formarray['id']).'" class="wprevpro_form wprevpro_submiterror" style="display: block;">
				'.$sucmsg.'
				</div>';
} else {
	//display form

	echo $formhtml;
}

?>


<?php
//print_r($formarray);
?>