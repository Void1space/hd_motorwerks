<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/admin/partials
 */
 
     // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

	    // wordpress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('tripadvisor-radio', 'wprevpro_message', __('Settings Saved', 'wp-review-slider-pro'), 'updated');
    }

	if(isset($this->errormsg)){
		add_settings_error('tripadvisor-radio', 'wprevpro_message', __($this->errormsg, 'wp-review-slider-pro'), 'error');
	}
	
	//default values for new notifications
	$dbmsg = "";
	$html="";
	$currenttemplate= new stdClass();
	$currenttemplate->id="";
	$currenttemplate->title="";
	$currenttemplate->source_page="";
	$currenttemplate->site_type="";
	$currenttemplate->rate_op="<";
	$currenttemplate->rate_val="3";
	$currenttemplate->email=get_option('admin_email');
	$currenttemplate->email_subject=__('New Reviews Notification - WP Review Slider Pro', 'wp-review-slider-pro');
	$currenttemplate->email_first_line=__('<b>WP Review Slider Pro</b> found the following reviews that match your notification settings.', 'wp-review-slider-pro');
	$currenttemplate->enable="";

	global $wpdb;
	$table_name_notify = $wpdb->prefix . 'wpfb_nofitifcation_forms';
	
	//form deleting and updating here---------------------------
	if(isset($_GET['taction'])){
		if(isset($_GET['tid'])){
			$tid = htmlentities($_GET['tid']);
			//for deleting
			if($_GET['taction'] == "del" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tdel_');
				//delete
				$wpdb->delete( $table_name_notify, array( 'id' => $tid ), array( '%d' ) );
			}
			//for updating
			if($_GET['taction'] == "edit" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tedit_');
				//get form array
				$currenttemplate = $wpdb->get_row( "SELECT * FROM ".$table_name_notify." WHERE id = ".$tid );
			}
			//for copying
			if($_GET['taction'] == "copy" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tcopy_');
				//get form array
				$currenttemplate = $wpdb->get_row( "SELECT * FROM ".$table_name_notify." WHERE id = ".$tid );
				//add new template
				$array = (array) $currenttemplate;
				$array['title'] = $array['title'].'_copy';
				
				unset($array['id']);
				//print_r($array);
				//remove the id so it can be generated.
				$wpdb->insert( $table_name_notify, $array );
				//$wpdb->show_errors();
				//$wpdb->print_error();
			}
		}
		
	}

	//form posting here--------------------------------
	//check to see if form has been posted.
	//if template id present then update database if not then insert as new.
	//db function variables
	if (isset($_POST['wprevpro_submittemplatebtn'])){
		//verify nonce wp_nonce_field( 'wprevpro_save_template');
		check_admin_referer( 'wprevpro_save_template');
		//get form submission values and then save or update
		$t_id = sanitize_text_field($_POST['edittid']);
		$title = sanitize_text_field($_POST['wprevpro_template_title']);
		$source_page = array();
		$souce_page_json ='';
		if(isset($_POST['source_pages'])){
			$source_page = $_POST['source_pages'];
			$souce_page_json = json_encode($source_page);
		}
		$site_type = array();
		$site_type_json ='';
		if(isset($_POST['site_types'])){
			$site_type = $_POST['site_types'];
			$site_type_json = json_encode($site_type);
		}
		$rate_op = $_POST['wprevpro_rate_op'];
		$rate_val = sanitize_text_field($_POST['wprevpro_rate_val']);
		$rate_val = intval($rate_val);
		$email = sanitize_text_field($_POST['wprevpro_email']);
		$email_subject = sanitize_text_field($_POST['wprevpro_email_subject']);
		$email_first_line = htmlentities($_POST['wprevpro_email_first_line']);
		$enable = sanitize_text_field($_POST['wprevpro_enable']);

		$timenow = time();
		
		//+++++++++need to sql escape using prepare+++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++++++++++
		//insert or update
			$data = array( 
				'title' => "$title",
				'source_page' => "$souce_page_json",
				'site_type' => "$site_type_json",
				'created_time_stamp' => "$timenow",
				'rate_op' => "$rate_op",
				'rate_val' => "$rate_val",
				'email' => "$email",
				'email_subject' => "$email_subject",
				'email_first_line' => "$email_first_line",
				'enable' => "$enable",
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
			$insertrow = $wpdb->insert( $table_name_notify, $data, $format );
			if(!$insertrow){
			//$wpdb->show_errors();
			//$wpdb->print_error();
			$dbmsg = $dbmsg.'<div id="setting-error-wprevpro_message" class="error settings-error notice is-dismissible">'.__('<p><strong>Oops! This form could not be inserted in to the database.</br> -'.$wpdb->show_errors().' -'.$wpdb->print_error().' </strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>', 'wp-review-slider-pro').'</div>';
			}
			//die();
		} else {
			//update
			//print_r($data);
			$updatetempquery = $wpdb->update($table_name_notify, $data, array( 'id' => $t_id ), $format, array( '%d' ));
			//$wpdb->show_errors();
			//$wpdb->print_error();
			if($updatetempquery>0){
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Settings Updated!', 'wp-review-slider-pro').'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			} else {
				$wpdb->show_errors();
				$wpdb->print_error();
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Error', 'wp-review-slider-pro').':</strong> '.__('Unable to update. Please contact support.', 'wp-review-slider-pro').'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			}
		}
		
		
	}
	
	//update admin url here
	//
	//if (isset($_POST['wprevpro_submitadmbinurlbtn'])){
	//	$newadminurl = sanitize_text_field($_POST['wprevpro_admin_url']);
	//	update_option( 'wprevpro_admin_url',$newadminurl);
	//}
	
	
	//Get list of all current forms--------------------------
	$currentforms = $wpdb->get_results("SELECT * FROM $table_name_notify ORDER BY id DESC");
	//-------------------------------------------------------	
	

	
	
?>
<div class="wrap wp_pro-settings" id="">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
<?php 


include("tabmenu.php");
?>
<div class="wprevpro_margin10">


<?php
if ( wrsp_fs()->can_use_premium_code() ) {
?>

<div class="notifications_sections">

	<h3><?php _e('Review Notifications', 'wp-review-slider-pro'); ?></h3>
	<p><?php _e('Allows you to get email notifications of new reviews based on certain rules. This is only for downloaded reviews. For submitted reviews use the setting on the review Form page.', 'wp-review-slider-pro'); ?></p>

	<div class="wprevpro_margin10">
		<a id="wprevpro_addnewtemplate" class="button dashicons-before dashicons-plus-alt"><?php _e('Add New Notification', 'wp-review-slider-pro'); ?></a>
	</div>

	  <div class="wprevpro_margin10" id="wprevpro_new_template">
	<form name="newtemplateform" id="newtemplateform" action="?page=wp_pro-notifications" method="post">
		<table class="wprevpro_margin10 form-table ">
			<tbody>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Title:', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<input id="wprevpro_template_title" data-custom="custom" type="text" name="wprevpro_template_title" placeholder="" value="<?php echo $currenttemplate->title; ?>" required>
						<p class="description">
						<?php _e('Enter a unique name for this notification.', 'wp-review-slider-pro'); ?>		</p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Filter by Source Page(s):', 'wp-review-slider-pro'); ?>
					</th>
					<td><div id="divsitetype">
					<select id="wprevpro_source_page" class="js-example-basic-multiple" name="source_pages[]" multiple="multiple" style="width: 100%">
					<?php
					//if editing then we need to add selected attribute if there is a match.
					$pageidarray = array();
					if($currenttemplate->source_page!=''){
						$pageidarray = json_decode($currenttemplate->source_page);
					}
					//var_dump($pageidarray);
					//get current locations
					$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
					$tempquery = "select * from ".$reviews_table_name." group by pageid";
					$fbpagesrows = $wpdb->get_results($tempquery);
					//var_dump($fbpagesrows);
					foreach ( $fbpagesrows as $fbpage ) 
					{
						//check for previous values
						$isselectedtext = '';
						$temppageid = $fbpage->pageid;
						$temppageidhtmlentities = htmlspecialchars_decode($fbpage->pageid);
						//echo $temppageidhtmlentities;
						if(in_array($temppageid, $pageidarray) || in_array($temppageidhtmlentities, $pageidarray)){
							$isselectedtext = 'selected="selected"';
						}
						echo '<option value="'.$fbpage->pageid.'" '.$isselectedtext.'>'.$fbpage->pagename.' ('.$fbpage->type.')</option>';
					}
					?>
					</select>

						</div>
						<p class="description">
						<?php _e('The original location of the reviews. Leave blank for all reviews.', 'wp-review-slider-pro'); ?></p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Filter by Review Type:', 'wp-review-slider-pro'); ?>
					</th>
					<td><div id="divsitetype">

						<select id="wprevpro_site_type" class="js-example-basic-multiple" name="site_types[]" multiple="multiple" style="width: 100%">
						<?php
						//if editing then we need to add selected attribute if there is a match.
						$savedtypearray = array();
						if($currenttemplate->site_type!=''){
							$savedtypearray = json_decode($currenttemplate->site_type);
						}
						$tempquery = "select type from ".$reviews_table_name." group by type";
						$typearray = $wpdb->get_col($tempquery);
						
						for($x=0;$x<count($typearray);$x++)
						{
							$typelowercase = strtolower($typearray[$x]);
							//check for previous values
							$isselectedtext = '';
							if(in_array($typelowercase, $savedtypearray)){
								$isselectedtext = 'selected="selected"';
							}
							echo '<option value="'.$typelowercase.'" '.$isselectedtext.'>'.__($typearray[$x], 'wp-review-slider-pro').'</option>';
						}
						?>
						</select>

						</div>
						<p class="description">
						<?php _e('The type of review. Leave blank for all reviews.', 'wp-review-slider-pro'); ?></p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Rating Rule:', 'wp-review-slider-pro'); ?>
					</th>
					<td><div id="divsitetype">
						<?php _e('If Review Rating is', 'wp-review-slider-pro'); ?>
						<select name="wprevpro_rate_op" id="wprevpro_rate_op">
						  <option value=">" <?php if($currenttemplate->rate_op=='>'){echo "selected";} ?>><?php _e('>', 'wp-review-slider-pro'); ?></option>
						  <option value="=" <?php if($currenttemplate->rate_op=='='){echo "selected";} ?>><?php _e('=', 'wp-review-slider-pro'); ?></option>
						  <option value="<" <?php if($currenttemplate->rate_op=='<'){echo "selected";} ?>><?php _e('<', 'wp-review-slider-pro'); ?></option>
						</select>
						<select name="wprevpro_rate_val" id="wprevpro_rate_val">
						<option value="0" <?php if($currenttemplate->rate_val=='0'){echo "selected";} ?>><?php _e('0', 'wp-review-slider-pro'); ?></option>
						  <option value="1" <?php if($currenttemplate->rate_val=='1'){echo "selected";} ?>><?php _e('1', 'wp-review-slider-pro'); ?></option>
						  <option value="2" <?php if($currenttemplate->rate_val=='2'){echo "selected";} ?>><?php _e('2', 'wp-review-slider-pro'); ?></option>
						  <option value="3" <?php if($currenttemplate->rate_val=='3'){echo "selected";} ?>><?php _e('3', 'wp-review-slider-pro'); ?></option>
						  <option value="4" <?php if($currenttemplate->rate_val=='4'){echo "selected";} ?>><?php _e('4', 'wp-review-slider-pro'); ?></option>
						  <option value="5" <?php if($currenttemplate->rate_val=='5'){echo "selected";} ?>><?php _e('5', 'wp-review-slider-pro'); ?></option>
						  <option value="6" <?php if($currenttemplate->rate_val=='6'){echo "selected";} ?>><?php _e('6', 'wp-review-slider-pro'); ?></option>
						</select>
						.
						</div>
						<p class="description">
						<?php _e('If the rating is greater, equal, or less than this value, send the notification.', 'wp-review-slider-pro'); ?></p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Email Address:', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<input class="wprevpro_not_input" id="wprevpro_email" data-custom="custom" type="text" name="wprevpro_email" placeholder="" value="<?php echo $currenttemplate->email; ?>">
						<p class="description">
						<?php _e('Email address of where you would like the notifications sent. This can also be a comma separated list of email addresses.', 'wp-review-slider-pro'); ?>		</p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Email Subject Title:', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<input class="wprevpro_not_input" id="wprevpro_email_subject" data-custom="custom" type="text" name="wprevpro_email_subject" placeholder="" value="<?php echo $currenttemplate->email_subject; ?>">
						<p class="description">
						<?php _e('Customize the email subject.', 'wp-review-slider-pro'); ?>		</p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Email Text Before Reviews:', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<textarea class="wprevpro_not_input" name="wprevpro_email_first_line" id="wprevpro_email_first_line" cols="60" rows="4" spellcheck="false"><?php echo stripslashes(html_entity_decode($currenttemplate->email_first_line)); ?></textarea>
						<p class="description">
						<?php _e('Customize the text in the email that appears before the list of reviews. It can be plain text or HTML.', 'wp-review-slider-pro'); ?></p>
					</td>
				</tr>
				<tr class="wprevpro_row" >
					<th scope="row">
						<?php _e('Turn On/Off:', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<input type="radio" name="wprevpro_enable" value="yes" <?php if($currenttemplate->enable=='yes' || $currenttemplate->enable==''){echo "checked";} ?>><?php _e('On', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="wprevpro_enable" value="no" <?php if($currenttemplate->enable=='no' ){echo "checked";} ?>><?php _e('Off', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
						<p class="description">
						<?php _e('Turn this notification on or off. Allows you to pause a notification without deleting it.', 'wp-review-slider-pro'); ?></p>
					</td>
				</tr>
				


			</tbody>
		</table>
		<?php 
		//security nonce
		wp_nonce_field( 'wprevpro_save_template');
		?>
		<input type="hidden" name="edittid" id="edittid"  value="<?php echo $currenttemplate->id; ?>">
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
						<th scope="col" class="manage-column">'.__('Title', 'wp-review-slider-pro').'</th>
						<th scope="col" width="" class="manage-column">'.__('Email', 'wp-review-slider-pro').'</th>
						<th scope="col" width="" class="manage-column">'.__('Updated', 'wp-review-slider-pro').'</th>
						<th scope="col" width="" class="manage-column">'.__('Enabled', 'wp-review-slider-pro').'</th>
						<th scope="col" width="" class="manage-column"></th>
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
			
			$enabledhtml = '';
			if($currentform->enable!='yes'){
				$enabledhtml = "<span style='color:red;'>".$currentform->enable."</span>";
			} else {
				$enabledhtml = "<span style='color:green;'>".$currentform->enable."</span>";
			}
				
			$lastupdated = '';
			if($currentform->created_time_stamp>0){$lastupdated = date("M j, Y",$currentform->created_time_stamp);}
				
			$html .= '<tr id="'.$currentform->id.'">
					<th scope="col" class=" manage-column">'.$currentform->id.'</th>
					<th scope="col" class=" manage-column" style="min-width: 150px;"><b><span class="titlespan">'.$currentform->title.'</span></b></th>
					<th scope="col" class=" manage-column">'.$currentform->email.'</th>
					<th scope="col" class=" manage-column">'.$lastupdated.'</th>
					<th scope="col" class=" manage-column"><b>'.$enabledhtml.'</b></th>
					<th scope="col" class="manage-column" templateid="'.$currentform->id.'" templatetype="'.$currentform->site_type.'"><a href="'.$url_tempeditbtn.'" class="rfbtn button button-secondary dashicons-before dashicons-admin-generic">'.__('Edit', 'wp-review-slider-pro').'</a> <a href="'.$url_tempdelbtn.'" class="rfbtn button button-secondary dashicons-before dashicons-trash">'.__('Delete', 'wp-review-slider-pro').'</a> <a href="'.$url_tempcopybtn.'" class="rfbtn button button-secondary dashicons-before dashicons-admin-page">'.__('Copy', 'wp-fb-reviews').'</a></th>
				</tr>';
		}
		} else {
			$html .= '<tr><td colspan="8">'.esc_html__('You can create a Notification rule so that you get an email when the plugin downloads a new review. Click the "Add New Notification" button above. It helps if you already have reviews on the Review List page.', 'wp-review-slider-pro').' </td></tr>';
		}
			$html .= '</tbody></table>';
	echo $html;


	?>

</div>
<?php
/*
//may need this, not sure, going to try the admin_url() function first when sending emails through forms and notifications.
?>
<div class="notifications_sections">
	<h3><?php _e('WordPress Admin URL', 'wp-review-slider-pro'); ?></h3>
	<p><?php _e('Allows you to get email notifications of new reviews based on certain rules. This is only for downloaded reviews. For submitted reviews use the setting on the review Form page.', 'wp-review-slider-pro'); ?></p>

	<form name="updateadminurl" id="updateadminurl" action="?page=wp_pro-notifications" method="post">
	
	<input id="wprevpro_admin_url" data-custom="custom" type="text" name="wprevpro_admin_url" placeholder="" value="<?php echo get_option( 'wprevpro_admin_url', '' ); ?>">
	<input type="submit" name="wprevpro_submitadmbinurlbtn" id="wprevpro_submitadmbinurlbtn" class="button button-primary" value="<?php _e('Save', 'wp-review-slider-pro'); ?>">
	
	</form>

</div>
<?php
*/
?>
<div class="sections">
	<form action="options.php" method="post">

		<?php
		
		$options = get_option('wprevpro_notifications_settings');
		//print_r($options);
		
		// output security fields for the registered setting "wp_pro-notifications"
		settings_fields('wp_pro-notifications');
		// output setting sections and their fields
		// (sections are registered for "wp_pro-notifications", each field is registered to a specific section)
		do_settings_sections('wp_pro-notifications');
		// output save settings button
		?></div><?php 
		submit_button('Save Settings');
		?>
	
	</form>
	<p>The plugin uses the wp_mail() function to send the emails. If they don't come through then try one of the SMTP email plugins.</p>
	</div>
	<div id="popup" class="popup-wrapper wprevpro_hide">
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
	<div id="tb_content_popup" style="display:none;">
				<div id="lang_progress_div">
				

				</div>
				<div class="loadingspinner"></div>
	</div>
				
	<?php 
// show error/update messages
		settings_errors('tripadvisor-radio');
} else {
?>
<p><strong><?php _e('Upgrade to the Pro Version of this plugin to get notifications! Get the Pro Version <a href="' . wrsp_fs()->get_upgrade_url() . '">here</a>!', 'wp-fb-reviews'); ?></strong></p>
<?php
}
?>
</div>

</div>

	

