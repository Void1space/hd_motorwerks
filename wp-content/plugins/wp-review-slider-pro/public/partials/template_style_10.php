<?php

/**
 * Provide a public-facing view for the plugin
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
require_once("template_class.php");
$templateclass = new Template_Functions();
$revcount = 1;

//loop if more than one row
for ($x = 0; $x < count($rowarray); $x++) {
	if(	$currentform[0]->display_masonry!="yes" && $currentform[0]->createslider != "sli"){
if(	$currentform[0]->template_type=="widget"){
	$iswidget=true;
?>
<div class="wprevpro_t10_outer_div_widget w3_wprs-row wprevprodiv">
<?php
	} else {
	$iswidget=false;
?>
<div class="wprevpro_t10_outer_div w3_wprs-row wprevprodiv">
<?php
}
	}

//loop 
foreach ( $rowarray[$x] as $review ) 
{
	//fix for slickslider more than one row loading on ajax
			$closediv=false;
			if($ajaxsliload == true && $currentform[0]->createslider == "sli"){
				if($nrows>1){
					if($revcount==1 || $revcount == $nrows+1 || $revcount == 2*$nrows+1 || $revcount == 3*$nrows+1 || $revcount == 4*$nrows+1){
						echo "<div loop='".$looper."' revcount='".$revcount."' nrows='".$nrows."'>";
					}
					if($revcount == $nrows || $revcount == $nrows*2 || $revcount == $nrows*3 || $revcount == $nrows*4){
						$closediv=true;
					}
				}
				echo "<div class='ajaxsli'><div>";
			}
			
		//add to reviews number array
		$reviewratingsarray[] = $review->rating;
				//get userpic, functions in the class-wp-review-slider-pro-public.php file
		//$imagecachedir = plugin_dir_path( __FILE__ ).'cache/';
		$userpic = $templateclass->wprevpro_get_user_pic($review,'142','142',$currentform[0]);
	
		//star number --------------------------------------
		$starfile_burl_logo_array = $templateclass->wprevpro_get_star_logo_burl($review,$imgs_url,$currentform[0],"t10",$template_misc_array);
		$starfile = $starfile_burl_logo_array['starfile'];
		$logo =  $starfile_burl_logo_array['logo'];
		$burl = $starfile_burl_logo_array['burl'];
		
	
	$reviewtext = "";
		//review text --------------------------
		if(!isset($template_misc_array['length_type'])){
			$template_misc_array['length_type']='words';
		}
		$reviewtext = $templateclass->wprevpro_get_reviewtext($review,$currentform[0],$template_misc_array['length_type']);	
			
	//per a row
	if($currentform[0]->display_num>0){
		$perrow = 12/$currentform[0]->display_num;
	} else {
		$perrow = 4;
	}
		//date format MM/DD/YYYY, DD/MM/YY, DD/MM/YYYY, YYYY-MM-DD
		$datestring = $templateclass->wprevpro_get_datestring($review,$template_misc_array);
		
		//========company format   Owner, Dental Practice Website
		$companyhtml = $templateclass->wprevpro_get_companyhtml($review,$template_misc_array,"t10");
		
		//last name display options
		$tempreviewername = $templateclass->wprevpro_get_reviewername($review,$template_misc_array);
		
		//link to author url if turned on in template, use reviewer_id from db and create url for different types
		$profilelink = $templateclass->wprevpro_get_profilelink($review,$currentform,$userpic,$tempreviewername,$template_misc_array,$burl);

		//userpic html, this could change to nothing if userpic turned off.
		//$userpichtml = $profilelink['userpichtml'];
		//userpic html
		if(!isset($template_misc_array['default_avatar'])){
			$template_misc_array['default_avatar']='none';
		}
		//if the review does not have a image we hide it or show mystery depending on setting
		if($userpic==""){
			//use setting to determine default mystery man if set
			if($template_misc_array['default_avatar']=='fb'){
				$userpic = $imgs_url.'fb_mystery_man_small.png';
			} else if($template_misc_array['default_avatar']=='trip'){
				$userpic = $imgs_url.'trip_mystery_man_small.png';
			} else if($template_misc_array['default_avatar']=='google'){
				$userpic = $imgs_url.'google_mystery_man_small.png';
			} else if($template_misc_array['default_avatar']=='yelp'){
				$userpic = $imgs_url.'yelp_mystery_man_small.png';
			} else if($template_misc_array['default_avatar']=='airbnb'){
				$userpic = $imgs_url.'airbnb_mystery_man_small.png';
			}
		}
		$tempuserpicnone='';
		if($userpic==""){
			$userpichtml = '';
			$tempuserpicnone = 'style="display:none;"';
		} else {
			$userpichtml = $profilelink['start'].'<img src="'.$userpic.'" alt="'.$tempreviewername.' Avatar" class="wpproslider_t10_IMG_2 wprevpro_avatarimg" />'.$profilelink['end'];
			if(isset($template_misc_array['avataropt'])){
				if($template_misc_array['avataropt']=='hide'){
					$userpichtml = '';
					$tempuserpicnone = 'style="display:none;"';
				} else if($template_misc_array['avataropt']=='mystery'){
					if($review->type=="Yelp"){
						$tempuserpic = $imgs_url.'yelp_mystery_man_small.png';
					} else if ($review->type=="TripAdvisor"){
						$tempuserpic = $imgs_url.'trip_myster_man_small.png';
					} else if ($review->type=="Google"){
						$tempuserpic = $imgs_url.'google_mystery_man_small.png';
					} else if ($review->type=="Airbnb"){
						$tempuserpic = $imgs_url.'airbnb_mystery_man_small.png';
					} else {
						$tempuserpic = $imgs_url.'fb_mystery_man_small.png';
						//use setting to determine default mystery man if set
						if($template_misc_array['default_avatar']=='trip'){
							$tempuserpic = $imgs_url.'trip_mystery_man_small.png';
						} else if($template_misc_array['default_avatar']=='google'){
							$tempuserpic = $imgs_url.'google_mystery_man_small.png';
						} else if($template_misc_array['default_avatar']=='yelp'){
							$tempuserpic = $imgs_url.'yelp_mystery_man_small.png';
						} else if($template_misc_array['default_avatar']=='airbnb'){
							$tempuserpic = $imgs_url.'airbnb_mystery_man_small.png';
						}
					}
					$userpichtml = $profilelink['start'].'<img src="'.$tempuserpic.'" alt="'.$tempreviewername.' Avatar" class="wpproslider_t10_IMG_2 wprevpro_avatarimg" />'.$profilelink['end'];
				}
			}
		}

		//title option for tripadvisor and manual
		$title = '';
		if(isset($template_misc_array['showtitle'])){
			if(isset($review->review_title)){
				if($template_misc_array['showtitle']=="hide"){
					$title = '';
				} else if ($review->review_title!='') {
					$title = '<span class="wprevpro_t10_DIV_title">'.stripslashes(strip_tags($review->review_title)).' </span>';
				}
			}
		}
		
		//masonry style
		$tempwidget = '';
		if(	$currentform[0]->template_type=="widget"){
			$tempwidget =' marginb10';
		}
		$tempmasonrydiv =' wprevpro_t10_DIV_1'.$tempwidget.' w3_wprs-col l'.$perrow;
		if($ajaxsliload && $currentform[0]->createslider == "sli"){
			$tempmasonrydiv =' wprevpro_t1_DIV_1'.$tempwidget.' w3_wprs-col ';
		}
		if(	$currentform[0]->display_masonry=="yes" && $currentform[0]->createslider != "sli"){
			$tempmasonrydiv =$masonryclass_item.' wprevpro_t10_DIV_1'.$tempwidget.' ';
		}
		
		//starhtlm
		$starhtmlarray = $templateclass->wprevpro_get_starhtml($review,$template_misc_array,$currentform,$starfile);
		$starhtml = $starhtmlarray[0];
		$starhtml2 = $starhtmlarray[1];
		
		//miscpichtml, for woocommerce
		$miscpichtml = $templateclass->wprevpro_get_miscpichtml($review,$currentform[0]);
		
		$tempreviewernamehtml = '<span class="t10_revname wprev_preview_tcolor1_T'.$currentform[0]->style.'">'.$tempreviewername.'</span> '.__('left us a ', 'wp-review-slider-pro').$review->rating.' star review';
		if($review->rating<1){
			//change 
			if($review->recommendation_type=='positive' || $review->recommendation_type=='negative'){
				$tempreviewernamehtml = '<span class="t10_revname wprev_preview_tcolor1_T'.$currentform[0]->style.'">'.$tempreviewername.'</span> '.__('left us a ', 'wp-review-slider-pro').$review->recommendation_type.' recommendation';
			}
		}
		$onsitehtml = __('on ', 'wp-review-slider-pro').$review->type;
		
		
		//load template from child theme if found
		$custometheme= get_stylesheet_directory()."/wprevpro/template".$currentform[0]->style.".php";
		if (file_exists($custometheme)) {
			include($custometheme);
		} else {

?>
<div class="<?php echo $tempmasonrydiv; ?> outerrevdiv">
<div class="wpproslider_t10_DIV_1a">
	<div class="wpproslider_t10_DIV_2 wprev_preview_bg1_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?> wprev_preview_bradius_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>">
	<div class="wpproslider_t10_DIV_2_top">
		<div class="wpproslider_t10_DIV_3L" <?php echo $tempuserpicnone; ?>><?php echo $userpichtml; ?></div>
		<div class="wpproslider_t10_DIV_3">
			<div class="t10displayname wpproslider_t10_STRONG_5 wprev_preview_tcolor2_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>"><?php echo $tempreviewernamehtml; ?></div>
			<div class="wpproslider_t10_star_DIV"><span class="wprevpro_star_imgs_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>"><?php echo $starhtml; ?></span><span class="t10_onsite"><?php echo $onsitehtml;?></span></div>
			<div class="wpproslider_t10_SPAN_6 t10datediv"><span class="wprev_showdate_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>"><?php echo $datestring; ?></span></div>
		</div>
	</div>
	<div class="wpproslider_t10_DIV_3_logo"><?php echo $logo; ?></div>
	</div>
	
</div>
</div>
<?php
		}
		//fix for slickslider more than one row loading on ajax
			if($ajaxsliload == true && $currentform[0]->createslider == "sli"){
				echo "</div></div>";
				if($nrows>1){
					if($closediv){
						echo "</div>";
					}
				}
				
			}
	$revcount++;
}
//end loop
if(	$currentform[0]->display_masonry!="yes" && $currentform[0]->createslider != "sli"){
?>
</div>
<?php
}
}
?>
