<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://ljapps.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/admin/partials
 */
 
 //freemius license function https://freemius.com/help/documentation/wordpress-sdk/software-licensing/
 
 //add thickbox
 add_thickbox();
 
 //global variables for using freemius api
	//$frlicenseid = get_option( 'wprev_fr_siteid' );
	//$frsiteurl = get_option( 'wprev_fr_url' );
 
     // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
	
	$dbmsg = "";
	$html="";
	$currentgetappform= new stdClass();
	$currentgetappform->id="";
	$currentgetappform->title="";
	$currentgetappform->page_id="";
	$currentgetappform->site_type="";
	$currentgetappform->url="";
	$currentgetappform->cron="";
	$currentgetappform->blocks="100";
	$currentgetappform->last_name="full";
	$currentgetappform->profile_img="";
	$currentgetappform->categories="";
	$currentgetappform->posts="";
	$maxnumcandownload = "";
	$hidelastnameoption = false;
	$hideimageoption = false;
	$hidenumtodownload = false;
	
	$rtype='';
	$exurls ='';
	
	//find this review source type, iTunes, GetYourGuide
		$rtype=$_GET['rtype'];
		if($rtype=='iTunes'){
			$hideimageoption = true;
			$exurls = '<br>'.__('Please make sure the URL is visible by a browser. In other words, it doesn\'t open in iTunes.', 'wp-review-slider-pro').'<br><i>https://podcasts.apple.com/us/podcast/id1462192400<br>https://books.apple.com/us/audiobook/where-the-crawdads-sing-unabridged/id1428290134<br>https://apps.apple.com/us/app/pok%C3%A9mon-go/id1094591345</i>';
		} else if($rtype=='GetYourGuide'){
			$hideimageoption = true;
			$exurls = ' Ex:<br><i>https://www.getyourguide.com/new-york-city-l59/priority-statue-of-liberty-ticket-with-pedestal-option-t120169/</i>';
		}  else if($rtype=='HousecallPro'){
			$hideimageoption = true;
			$exurls = ' Ex:<br><i>https://client.housecallpro.com/reviews/Wellmann-Plumbing/de5f6b5d-23a0-4467-89fe-f793c431470d/</i>';
		} else if($rtype=='Nextdoor'){
			$hidenumtodownload = true;
			$exurls = ' Ex:<br><i>https://nextdoor.com/pages/tortoras-owens-cross-roads-al/</i>';
		} else if($rtype=='Zillow'){
			$hideimageoption = true;
			$exurls = ' Ex:<br><i>https://www.zillow.com/profile/ahoward3/</i><br>'.__('This should be your Profile URL.', 'wp-review-slider-pro').'<br>'.__('<b>Use the Get Reviews > Review Funnel to download Lender Reviews.</b>', 'wp-review-slider-pro');
		}  else if($rtype=='AngiesList'){
			$exurls = ' Ex:<br><i>https://www.angieslist.com/companylist/us/nj/belleville/visentin-plumbing-and-heating-reviews-228768.htm</i>'.'<br>'.__('<b>AngiesList download is limited to 30 reviews.</b>', 'wp-review-slider-pro');
			$currentgetappform->blocks="30";
			$maxnumcandownload = "30";
			$hidelastnameoption = true;
			$hideimageoption = true;
			$hidenumtodownload = false;
		} else if($rtype=='Qualitelis'){			//Qualitelis
			$hideimageoption = true;
			$hidenumtodownload = true;
		} else if($rtype=='Freemius'){
			$hidenumtodownload = true;
		}  else if($rtype=='Feefo'){
			$hideimageoption = true;
		}  else if($rtype=='Realtor'){
			$hideimageoption = true;
			$hidenumtodownload = true;
		}
	
	
	//db function variables
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpfb_getapps_forms';
	
	//form deleting and updating here---------------------------
	if(isset($_GET['taction'])){
		if(isset($_GET['tid'])){
			$tid = htmlentities($_GET['tid']);
			//for deleting
			if($_GET['taction'] == "del" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tdel_');
				//delete
				$wpdb->delete( $table_name, array( 'id' => $tid ), array( '%d' ) );
			}
			//for updating
			if($_GET['taction'] == "edit" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tedit_');
				//get form array
				$currentgetappform = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$tid );
			}
			//for copying
			if($_GET['taction'] == "copy" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tcopy_');
				//get form array
				$currentgetappform = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$tid );
				//add new template
				$array = (array) $currentgetappform;
				$array['title'] = $array['title'].'_copy';
				
				unset($array['id']);
				//print_r($array);
				//remove the id so it can be generated.
				$wpdb->insert( $table_name, $array );
				//$wpdb->show_errors();
				//$wpdb->print_error();
			}
		}
		
	}
	//------------------------------------------
	
	//template importing from CSV file--------------------
	 if(isset($_POST["Import"])){
		//print_r($_FILES);
		$filename=$_FILES["file"]["tmp_name"];		
		 if($_FILES["file"]["size"] > 0)
		 {
		  	$file = fopen($filename, "r");
			$c = 0; //use line one for column names
	        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
	         {
				$c++;
				if($c == 1){
				//print_r($getData);
					$colarray = $getData;
				} else {
					$insertdata = array_combine($colarray, $getData);
					//remove id so it will assign another on insert
					unset($insertdata['id']);
					//insert to db here
					$wpdb->insert( $table_name, $insertdata );
				}
	         }
	         fclose($file);	
		 }
	}
	//---------------------------------------------------------------
	
	

	//form posting here--------------------------------
	//for nextdoor save cookie
	if (isset($_POST['wprevpro_savecookie']) && $rtype=="Nextdoor"){
		//verify nonce wp_nonce_field( 'wprevpro_save_template');
		check_admin_referer( 'wprevpro_save_cookie');
		$cookieval = sanitize_textarea_field($_POST['wprevpro_cookie']);
		update_option( 'wprevpro_cookieval', $cookieval );
	}
	

	//check to see if form has been posted.
	//if template id present then update database if not then insert as new.

	if (isset($_POST['wprevpro_submittemplatebtn'])){
		//verify nonce wp_nonce_field( 'wprevpro_save_template');
		check_admin_referer( 'wprevpro_save_template');
		//get form submission values and then save or update
		$t_id = sanitize_text_field($_POST['edittid']);
		$title = sanitize_text_field($_POST['wprevpro_template_title']);
		if(isset($_POST['wprevpro_template_page_id']) && $rtype=='Nextdoor'){
			$page_id= sanitize_text_field($_POST['wprevpro_template_page_id']);
		} else {
			$page_id='';
		}
		$site_type = sanitize_text_field($_POST['wprevpro_site_type']);
		$url = sanitize_text_field($_POST['wprevpro_url']);
		//$url=urlencode($url);
		$cron = sanitize_text_field($_POST['wprevpro_cron_setting']);
		$blocks = sanitize_text_field($_POST['wprevpro_blocks']);
		$blocks = intval($blocks);
		
		$last_name = sanitize_text_field($_POST['wprevpro_last_name']);
		$profile_img = sanitize_text_field($_POST['wprevpro_profile_img']);
		

		$timenow = time();
		
		//convert to json, function in class-wp-review-slider-pro-admin-common.php
		require_once WPREV_PLUGIN_DIR . 'admin/class-wp-review-slider-pro-admin-common.php';
		$plugin_admin_common = new Common_Admin_Functions();
		
		$catids = sanitize_text_field($_POST['wprevpro_nr_categories']);
		$catidsarrayjson ='';
		if($catids!=''){
		$catidsarrayjson = $plugin_admin_common->wprev_commastrtojson($catids,true);
		}
 
		$postid = sanitize_text_field($_POST['wprevpro_nr_postid']);
		$postidsarrayjson ='';
		if($postid!=''){
		$postidsarrayjson = $plugin_admin_common->wprev_commastrtojson($postid,true);
		}
		
		//+++++++++need to sql escape using prepare+++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++++++++++
		//insert or update
			$data = array( 
				'title' => "$title",
				'page_id' => "$page_id",
				'site_type' => "$site_type",
				'created_time_stamp' => "$timenow",
				'url' => "$url",
				'cron' => "$cron",
				'blocks' => "$blocks",
				'last_name' => "$last_name",
				'profile_img' => "$profile_img",
				'categories' => "$catidsarrayjson",
				'posts' => "$postidsarrayjson",
				);
				//print_r($data);
			$format = array( 
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
				); 

		if($t_id==""){
			//print_r($data);
			//insert
			$insertrow = $wpdb->insert( $table_name, $data, $format );
			if(!$insertrow){
			//$wpdb->show_errors();
			//$wpdb->print_error();
			$dbmsg = $dbmsg.'<div id="setting-error-wprevpro_message" class="error settings-error notice is-dismissible">'.__('<p><strong>Oops! This form could not be inserted in to the database. Please try de-activating and re-activating the plugin to force the database tables to update.</br> -'.$wpdb->show_errors().' -'.$wpdb->print_error().' </strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>', 'wp-review-slider-pro').'</div>';
			}
			//die();
		} else {
			//update
			//print_r($data);
			$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $t_id ), $format, array( '%d' ));
			//$wpdb->show_errors();
			//$wpdb->print_error();
			if($updatetempquery>0){
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Form Updated!', 'wp-review-slider-pro').'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			} else {
				$wpdb->show_errors();
				$wpdb->print_error();
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Error', 'wp-review-slider-pro').':</strong> '.__('Unable to update. Please contact support.', 'wp-review-slider-pro').'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			}
		}
		
		
	}

	//Get list of all current forms--------------------------
	$currentforms = $wpdb->get_results("SELECT * FROM $table_name where site_type = '".$rtype."' ORDER BY id DESC");
	//-------------------------------------------------------

	
?>

<div class="wrap wp_pro-settings" style="min-height: 900px;">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
	
<?php 
include("tabmenu.php");

//query args for export and import
$url_tempdownload = admin_url( 'admin-post.php?action=print_reviewfunnel.csv' );
if ( wrsp_fs()->can_use_premium_code() ) {

	
?>
<div class="w3-col m2 sidemenucontainer">
<?php
include("getrevs_sidemenu.php");
?>	
</div>
<div class="w3-col m10">
<div class="wprevpro_margin10">
	<a id="wprevpro_helpicon_posts" class="wprevpro_btnicononly button dashicons-before dashicons-editor-help"></a>
	<a id="wprevpro_addnewtemplate" class="button dashicons-before dashicons-plus-alt"><?php _e('Add New Source Page', 'wp-review-slider-pro'); ?></a>

<?php
/*
	<a href="<?php echo $url_tempdownload;?>" id="wprevpro_exporttemplates" class="button dashicons-before dashicons-download"><?php _e('Export Review Funnels', 'wp-review-slider-pro'); ?></a>
	<a id="wprevpro_importtemplates" class="button dashicons-before dashicons-upload"><?php _e('Import Review Funnels', 'wp-review-slider-pro'); ?></a>
	*/
?>
</div>
<?php
if($rtype=='Nextdoor'){
?>
<div class="wprevpro_margin10 bordered_form" id="login_cookie">
	    <form  action="?page=wp_pro-get_apps&rtype=Nextdoor" method="post" name="logincookie" enctype="multipart/form-data">
		<b>Nextdoor Cookie:</b>
		<table class="wprevpro_margin10 ">
		<tbody>
			<tr class="wprevpro_row">
				<td scope="row">
				<textarea name="wprevpro_cookie" id="wprevpro_cookie" cols="50" rows="4" spellcheck="false"><?php echo get_option('wprevpro_cookieval'); ?></textarea>
				</td>
				<td scope="row" style="padding-left:10px;">
			<p class="description">
			<?php _e('Nextdoor requires you to be logged in to see recommendations. The plugin will use this cookie value to identify your account with Nextdoor. Follow the steps in the video instructions to obtain the cookie value.', 'wp-review-slider-pro'); ?></p>
			<p class="description">
			<?php _e('<b>Note:</b> This cookie may expire. If it does, then you\'ll need to enter a new one.', 'wp-review-slider-pro'); ?></p>
			</td>
			</tr>
			</tbody>
			</table>
				<?php 
	//security nonce
	wp_nonce_field( 'wprevpro_save_cookie');
	?>
			<input type="submit" name="wprevpro_savecookie" id="wprevpro_savecookie" class="button button-primary" value="<?php _e('Save', 'wp-review-slider-pro'); ?>">
&nbsp;&nbsp;<a href="https://ljapps.com/wp-content/uploads/2019/07/nextdoorcookie.mp4" target="_blank" id="instr" name="instr" class="button-secondary "><?php _e('Video Instructions', 'wp-review-slider-pro'); ?></a>
        </form>
</div>
<?php
}
?>


<?php

} else {
	echo '<div class="wprevpro_margin10"> ';
	printf( __( '%s reviews are a Premium feature. Please upgrade.', 'wp-review-slider-pro' ) , $rtype );
	echo '</div>';
}



?>

  <div class="wprevpro_margin10" id="wprevpro_new_template">
<form name="newtemplateform" id="newtemplateform" action="?page=wp_pro-get_apps&rtype=<?php echo $rtype; ?>" method="post">
	<table class="wprevpro_margin10 form-table ">
		<tbody>
			<tr class="wprevpro_row">
				<th scope="row">
				
					<?php 
					if($rtype=='Freemius'){
						_e('Plugin or Theme Name:', 'wp-review-slider-pro');
					} else {
						_e('Place or Location Name:', 'wp-review-slider-pro'); 
					}
					?>
				</th>
				<td>
					<input id="wprevpro_template_title" data-custom="custom" type="text" name="wprevpro_template_title" placeholder="" value="<?php echo $currentgetappform->title; ?>" required>
					<p class="description">
					<?php
					printf( __( 'Enter a unique name for these %s reviews. This would normally be the name of what the reviews are talking about.', 'wp-review-slider-pro' ), $rtype );
					?>		</p>
				</td>
			</tr>
			<?php
			if($rtype=='Nextdoor'){
			?>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Nextdoor Page_ID:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input id="wprevpro_template_page_id" data-custom="custom" type="text" name="wprevpro_template_page_id" placeholder="" value="<?php echo $currentgetappform->page_id; ?>" required>&nbsp;&nbsp;<a href="https://ljapps.com/wp-content/uploads/2019/12/nextdoor_page_id.mp4" target="_blank" id="instr" name="instr" class="button-secondary "><?php _e('Video Instructions', 'wp-review-slider-pro'); ?></a>
					<p class="description">
					<?php
					printf( __( 'Follow the video instructions to find the Page_ID.', 'wp-review-slider-pro' ), $rtype );
					?>		</p>
				</td>
			</tr>
			<?php
			}
			?>
			<tr class="wprevpro_row" style='display:none;'>
				<th scope="row">
					<?php _e('Choose Review Site:', 'wp-review-slider-pro'); ?>
				</th>
				<td><div id="divsitetype">
						<select name="wprevpro_site_type" id="wprevpro_site_type">
						<option value="<?php echo $rtype;?>" <?php if($currentgetappform->site_type==$rtype){echo "selected";} ?>><?php echo $rtype;?></option>
						</select>
					</div>
					<p class="description">
					<?php _e('This is the app store you are downloading the reviews from.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php 
					if($rtype=='Freemius'){
						_e('Plugin ID, Public Key, Secret Key', 'wp-review-slider-pro');
					} else if($rtype=='Qualitelis'){
						_e('Token, IdContractor, CycleId, SurveyId, Langue', 'wp-review-slider-pro');
					} else if($rtype=='Feefo'){
						_e('Merchant Identifier', 'wp-review-slider-pro');
					} else {
						_e('Review URL', 'wp-review-slider-pro'); 
					}
					?>
				</th>
				<td>
					<?php
					if($rtype=='Freemius'){
					?>
						<input class="yelp_business_url" id="wprevpro_url" data-custom="custom" type="text" name="wprevpro_url" placeholder="" value="<?php echo urldecode($currentgetappform->url); ?>" required>
						<p class="description">
					
					<?php
						_e('Enter a comma separated list of your Plugin ID, Public Key, Secret Key in that order. They can be found by logging in to Freemius, click the plugin, go to Settings > Keys.', 'wp-review-slider-pro');
						
					} else if($rtype=='Qualitelis'){
					?>
						<input class="yelp_business_url" id="wprevpro_url" data-custom="custom" type="text" name="wprevpro_url" placeholder="" value="<?php echo urldecode($currentgetappform->url); ?>" required>
						<p class="description">
					
					<?php
						_e('Enter a comma separated list of your Token, IdContractor, CycleId, SurveyId, Langue in that order. They can be found by logging in to https://www.qualitelis-survey.com/. The CycleID, SurveyId, and Langue are optional. If you leave them out do so like this: xxxxtokenxxxx, xxxxIdContractorxxxx,,,xxxLanguexxx', 'wp-review-slider-pro');


					} else if($rtype=='Feefo'){
					?>
						<input class="yelp_business_url" id="wprevpro_url" data-custom="custom" type="text" name="wprevpro_url" placeholder="" value="<?php echo urldecode($currentgetappform->url); ?>" required>
						<p class="description">
					<?php	
						echo sprintf(__( 'Enter your merchant_identifier. Go %1$shere%2$s for more info.', 'wp_fb-reviews' ), 
						'<a href="https://support.feefo.com/support/solutions/articles/8000041003-reviews-api-parameter-merchant-identifier" target="_blank">', 
						'</a>'
						);
					} else {
					?>
						<input class="yelp_business_url" id="wprevpro_url" data-custom="custom" type="url" name="wprevpro_url" placeholder="" value="<?php echo urldecode($currentgetappform->url); ?>" required>
						<p class="description">
					<?php
						_e('The URL of the page where the reviews or recommendations are located.'.$exurls.'', 'wp-review-slider-pro'); 
					}
					?>

					</p>
				</td>
			</tr>
			<tr class="wprevpro_row" <?php if($hidenumtodownload){echo "style='display:none;'";}?>>
				<th scope="row">
					<?php _e('Number of Reviews to Download', 'wp-review-slider-pro'); ?>
				</th>
				<td><div id="divsitetype">
						<input class="" style="width: 70px;" id="wprevpro_blocks" data-custom="custom" type="number" name="wprevpro_blocks" placeholder="" max="<?php echo $maxnumcandownload; ?>" value="<?php echo $currentgetappform->blocks; ?>" >
					</div>
					<p class="description">
					<?php _e('The number of reviews you wish to download.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<tr class="wprevpro_row" <?php if($hidelastnameoption){echo 'style="display:none;"';} ?>>
				<th scope="row">
					<?php _e('Last Name Save Option', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input type="radio" name="wprevpro_last_name" value="full" <?php if($currentgetappform->last_name=='full' || $currentgetappform->last_name==''){echo "checked";} ?>><?php _e('Full Last Name', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_last_name" value="initial" <?php if($currentgetappform->last_name=='initial' ){echo "checked";} ?>><?php _e('Initial Only', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_last_name" value="nothing" <?php if($currentgetappform->last_name=='nothing' ){echo "checked";} ?>><?php _e('Nothing', 'wp-review-slider-pro'); ?>
					<p class="description">
					<?php _e('Set this to change the way the last name is saved in your database. You can also hide the last name when creating a review template.', 'wp-review-slider-pro'); ?>		</p>
				</td>
			</tr>
			<tr class="wprevpro_row" <?php if($hideimageoption){echo "style='display:none;'";}?>>
				<th scope="row">
					<?php _e('Local Profile Images', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input type="radio" name="wprevpro_profile_img" value="no" <?php if($currentgetappform->profile_img=='no' || $currentgetappform->profile_img==''){echo "checked";} ?>><?php _e('No', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_profile_img" value="yes" <?php if($currentgetappform->profile_img=='yes' ){echo "checked";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
					<p class="description">
					<?php _e('By default, avatar images are referenced from the original review site. Set this to yes if you would like the plugin to try and save the profile images locally. This may not always work as the remote site might block the download. The avatar must exists on the review source page.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>

			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Auto Download Reviews', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<select name="wprevpro_cron_setting" id="wprevpro_cron_setting">
					<option value="" <?php if($currentgetappform->cron==''){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
					<option value="672" <?php if($currentgetappform->cron=='672'){echo "selected";} ?>><?php _e('Once a Month', 'wp-review-slider-pro'); ?></option>
					<option value="336" <?php if($currentgetappform->cron=='336'){echo "selected";} ?>><?php _e('Every 14 Days', 'wp-review-slider-pro'); ?></option>
					<option value="168" <?php if($currentgetappform->cron=='168'){echo "selected";} ?>><?php _e('Every 7 Days', 'wp-review-slider-pro'); ?></option>
					<option value="48" <?php if($currentgetappform->cron=='48'){echo "selected";} ?>><?php _e('Every Other Day', 'wp-review-slider-pro'); ?></option>
					<option value="24" <?php if($currentgetappform->cron=='24'){echo "selected";} ?>><?php _e('Once a Day', 'wp-review-slider-pro'); ?></option>
					</select>
					<p class="description">
					<?php _e('Automatically request a new scrape job and download the reviews.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Review Categories:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input class="wprevpro_nr_categories" id="wprevpro_nr_categories" data-custom="custom" type="text" name="wprevpro_nr_categories" placeholder="" value="<?php echo $this->wprev_jsontocommastr($currentgetappform->categories); ?>">
					<span class="description"><a id="wprevpro_btn_pickcats" class="button dashicons-before dashicons-yes "><?php _e('Select Categories', 'wp-review-slider-pro'); ?></a>
					<?php _e('Single or comma separated list of post category IDs. Allows you to associate the reviews with post categories as they are downloaded. You can then use the Category filter for the template. ex: 1,3,5', 'wp-review-slider-pro'); ?>		</span>
					<div id="tb_content_cat_select" style="display:none;">
						<div id="tb_content_cat_search"><input id="tb_content_cat_search_input" data-custom="custom" type="text" name="tb_content_cat_search_input" placeholder="Type here to search..." value=""></div>
						<div class="wprev_loader_catlist" style="display:none;"></div>
						<table id="selectcatstable" class="wp-list-table widefat striped posts">
						</table>
					</div>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Review Post IDs:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input class="wprevpro_nr_postid" id="wprevpro_nr_postid" data-custom="custom" type="text" name="wprevpro_nr_postid" placeholder="" value="<?php echo $this->wprev_jsontocommastr($currentgetappform->posts); ?>" >
					<span class="description"><a id="wprevpro_btn_pickpostids" class="button dashicons-before dashicons-yes "><?php _e('Select Post IDs', 'wp-review-slider-pro'); ?></a>
					<?php _e('Single or comma separated list of post IDs. Allows you to associate the reviews with multiple posts or page IDs when they are downloaded. You can then use the Post filter for the template. ex: 11', 'wp-review-slider-pro'); ?>		</span>
				</td>
			</tr>

		</tbody>
	</table>
	<?php 
	//security nonce
	wp_nonce_field( 'wprevpro_save_template');
	?>
	<input type="hidden" name="edittid" id="edittid"  value="<?php echo $currentgetappform->id; ?>">
	<input type="submit" name="wprevpro_submittemplatebtn" id="wprevpro_submittemplatebtn" class="button button-primary" value="<?php _e('Save', 'wp-review-slider-pro'); ?>">
	<a id="wprevpro_addnewtemplate_cancel" class="button button-secondary"><?php _e('Cancel', 'wp-review-slider-pro'); ?></a>
	</form>
</div>
  

<?php

//display message
echo $dbmsg;
		$html .= '
		<table class="wp-list-table widefat striped posts">
			<thead>
				<tr>
					<th scope="col" width="40px" class="manage-column">'.__('ID', 'wp-review-slider-pro').'</th>
					<th scope="col" class="manage-column">'.__('Title <br>URL or Query', 'wp-review-slider-pro').'</th>
					<th scope="col" width="115px" class="manage-column">'.__('Number', 'wp-review-slider-pro').'</th>
					<th scope="col" width="125px" class="manage-column">'.__('Cron', 'wp-review-slider-pro').'</th>
					<th scope="col" width="115px" class="manage-column">'.__('Last Ran', 'wp-review-slider-pro').'</th>
					<th scope="col" width="390px" class="manage-column">'.__('Action', 'wp-review-slider-pro').'</th>
				</tr>
				</thead>
			<tbody id="appformstable">';
	if(count($currentforms)>0){
	foreach ( $currentforms as $currentform ) 
	{
	//remove query args we just used
	$urltrimmed = remove_query_arg( array('taction', 'id') );
		$tempeditbtn =  add_query_arg(  array(
			'taction' => 'edit',
			'tid' => "$currentform->id",
			),$urltrimmed);
			
		$url_tempeditbtn = wp_nonce_url( $tempeditbtn, 'tedit_');
			
		$tempdelbtn = add_query_arg(  array(
			'taction' => 'del',
			'tid' => "$currentform->id",
			),$urltrimmed) ;
			
		$url_tempdelbtn = wp_nonce_url( $tempdelbtn, 'tdel_');
		
						//for copying
		$tempcopybtn = add_query_arg(  array(
			'taction' => 'copy',
			'tid' => "$currentform->id",
			),$urltrimmed) ;
		$url_tempcopybtn = wp_nonce_url( $tempcopybtn, 'tcopy_');
		$tempblocks = '';
		if($currentform->blocks>0){
			$tempblocks = ($currentform->blocks);
		}
			
		$tempurlhtml = '';
		if($currentform->url!=''){
			$tempurlhtml = substr(urldecode($currentform->url),0,90);
			if(strlen(urldecode($currentform->url))>100){
				$tempurlhtml = $tempurlhtml ."...";
			}
		}
		$lastranon = '';
		if($currentform->last_ran>0){$lastranon = date("M j, Y",$currentform->last_ran);}
			
		$html .= '<tr id="'.$currentform->id.'">
				<th scope="col" class=" manage-column">'.$currentform->id.'</th>
				<th scope="col" class=" manage-column" style="min-width: 200px;"><b><span class="titlespan">'.$currentform->title.'</span></b><br><span style="font-size:10px;">'.$tempurlhtml.'</span></th>
				<th scope="col" class=" manage-column"><b>'.$tempblocks.'</b></th>
				<th scope="col" class=" manage-column"><b>'.$currentform->cron.'</b></th>
				<th scope="col" class=" manage-column">'.$lastranon.'</th>
				<th scope="col" class="manage-column" templateid="'.$currentform->id.'" templatetype="'.$currentform->site_type.'"><a href="'.$url_tempeditbtn.'" class="rfbtn button button-secondary dashicons-before dashicons-admin-generic">'.__('Edit', 'wp-review-slider-pro').'</a> <a href="'.$url_tempdelbtn.'" class="rfbtn button button-secondary dashicons-before dashicons-trash">'.__('Delete', 'wp-review-slider-pro').'</a> <a href="'.$url_tempcopybtn.'" class="rfbtn button button-secondary dashicons-before dashicons-admin-page">'.__('Copy', 'wp-fb-reviews').'</a> <span class="rfbtn button button-primary dashicons-before dashicons-star-filled retreviewsbtn"> '.__('Get Reviews', 'wp-fb-reviews').'</span></th>
			</tr>';
	}
	} else {
		//$html .= '<tr><td colspan="7">You can create a Review Form to download reviews from '.$rtype.'! Once downloaded, they will show up on the Review List page of the plugin and you can display them on your website with a Review Template. Click the "Add New Source Page" button above to get started.</td></tr>';
		$html .= '<tr><td colspan="7">';
		$html .= sprintf(__('You can create a Review Form to download reviews from %s! Once downloaded, they will show up on the Review List page of the plugin and you can display them on your website with a Review Template. Click the "Add New Source Page" button above to get started.', 'wp-review-slider-pro'),$rtype);
		if($rtype=="Zillow"){
			$html .= "<br>".sprintf(__('This does not work with Lender reviews.', 'wp-review-slider-pro'),$rtype);
		}		
		$html .= '</td></tr>';
	}
		$html .= '</tbody></table>';
echo $html;
//echo "<div></br>Coming Soon! Review Funnels will give you a way to download reviews from more than 40 different sites!</br></br></div>"; 

?>

<div id="retreivewspopupdiv" class="wprevpro_hide">
<div class="ajaxmessagediv"></div>
<div class="loadingspinner downloadrevsbtnspinner"></div>
</div>

	<div id="popup_review_list" class="popup-wrapper wprevpro_hide">
	  <div class="popup-content">
		<div class="popup-title">
		  <button type="button" class="popup-close">&times;</button>
		  <h3 id="popup_titletext"></h3>
		</div>
		<div class="popup-body">
		  <div id="popup_bobytext1"></div>
		  <div id="popup_bobytext2"></div>
		</div>
	  </div>
	</div>
	

</div>
<?php
//echo "<br><br><br>";
//print_r($licensecheckarray);
?>
</div>
