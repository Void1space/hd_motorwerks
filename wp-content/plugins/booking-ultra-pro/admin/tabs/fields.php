<?php 
$fields = get_option('bup_profile_fields');
ksort($fields);

global $bookingultrapro, $bup_form,  $bupcomplement;

if(isset($bupcomplement))
{

	$forms = $bup_form->get_all();

}


$last_ele = end($fields);
$new_position = $last_ele['position']+1;

$meta_custom_value = "";
$qtip_classes = 'qtip-light ';
?>
<h3>
	<?php _e('Fields Customizer','booking-ultra-pro'); ?>
</h3>
<p>
	<?php _e('Organize profile fields, add custom fields to profiles, control privacy of each field, and more using the following customizer. You can drag and drop the fields to change the order in which they are displayed on profiles and the registration form.','booking-ultra-pro'); ?>
</p>


<p >
<div class='bup-ultra-success bup-notification' id="fields-mg-reset-conf"><?php _e('Fields have been restored','booking-ultra-pro'); ?></div>

</p>
<a href="#bup-add-field-btn" class="button button-secondary"  id="bup-add-field-btn"><i
	class="bup-icon-plus"></i>&nbsp;&nbsp;<?php _e('Click here to add new field','booking-ultra-pro'); ?>
</a>


<a href="#bup-add-field-btn" class="button button-secondary bup-ultra-btn-red"  id="bup-restore-fields-btn"><i
	class="bup-icon-plus"></i>&nbsp;&nbsp;<?php _e('Click here to restore default fields','booking-ultra-pro'); ?>
</a> 


<?php if(isset($bupcomplement))
{?>

<div class="bup-ultra-sect" >



<label for="bup__custom_form"><?php _e('Custom Form:','booking-ultra-pro'); ?> </label>



<select name="uultra__custom_registration_form" id="uultra__custom_registration_form">

				<option value="" selected="selected">

					<?php _e('Default Registration Form','booking-ultra-pro'); ?>

				</option>

                

                <?php foreach ( $forms as $key => $form )

				{?>

				<option value="<?php echo $key?>">

					<?php echo $form['name']; ?>

				</option>

                

                <?php }?>

		</select>

        

        <input type="text" id="bup_custom_registration_form_name" name="uultra_custom_registration_form_name" />

        <a href="#bup-duplicate-form-btn" class="button button-secondary"  id="bup-duplicate-form-btn"><i

	class="uultra-icon-plus"></i>&nbsp;&nbsp;<?php _e('Duplicate Current Form','booking-ultra-pro'); ?>

</a>





</div>

<?php }?>

<div class="bup-ultra-sect bup-ultra-rounded" id="bup-add-new-custom-field-frm" >

<table class="form-table uultra-add-form">

	

	<tr valign="top">
		<th scope="row"><label for="uultra_type"><?php _e('Type','booking-ultra-pro'); ?> </label>
		</th>
		<td><select name="uultra_type" id="uultra_type">
				<option value="usermeta">
					<?php _e('Profile Field','booking-ultra-pro'); ?>
				</option>
				<option value="separator">
					<?php _e('Separator','booking-ultra-pro'); ?>
				</option>
		</select> <i class="uultra-icon-question-sign uultra-tooltip2"
			title="<?php _e('You can create a separator or a usermeta (profile field)','booking-ultra-pro'); ?>"></i>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row"><label for="uultra_field"><?php _e('Editor / Input Type','booking-ultra-pro'); ?>
		</label></th>
		<td><select name="uultra_field" id="uultra_field">
				<?php  foreach($bookingultrapro->allowed_inputs as $input=>$label) { ?>
				<option value="<?php echo $input; ?>">
					<?php echo $label; ?>
				</option>
				<?php } ?>
		</select> <i class="uultra-icon-question-sign uultra-tooltip2"
			title="<?php _e('When user edit profile, this field can be an input (text, textarea, image upload, etc.)','booking-ultra-pro'); ?>"></i>
		</td>
	</tr>

	<tr valign="top" >
		<th scope="row"><label for="uultra_meta_custom"><?php _e('New Custom Meta Key','booking-ultra-pro'); ?>
		</label></th>
		<td><input name="uultra_meta_custom" type="text" id="uultra_meta_custom"
			value="<?php echo $meta_custom_value; ?>" class="regular-text" /> <i
			class="uultra-icon-question-sign uultra-tooltip2"
			title="<?php _e('Enter a custom meta key for this profile field if do not want to use a predefined meta field above. It is recommended to only use alphanumeric characters and underscores, for example my_custom_meta is a proper meta key.','booking-ultra-pro'); ?>"></i>
		</td>
	</tr>
    
   
	<tr valign="top">
		<th scope="row"><label for="uultra_name"><?php _e('Label','booking-ultra-pro'); ?> </label>
		</th>
		<td><input name="uultra_name" type="text" id="uultra_name"
			value="<?php if (isset($_POST['uultra_name']) && isset($this->errors) && count($this->errors)>0) echo $_POST['uultra_name']; ?>"
			class="regular-text" /> <i
			class="uultra-icon-question-sign uultra-tooltip2"
			title="<?php _e('Enter the label / name of this field as you want it to appear in front-end (Profile edit/view)','booking-ultra-pro'); ?>"></i>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row"><label for="uultra_tooltip"><?php _e('Tooltip Text','booking-ultra-pro'); ?>
		</label></th>
		<td><input name="uultra_tooltip" type="text" id="uultra_tooltip"
			value="<?php if (isset($_POST['uultra_tooltip']) && isset($this->errors) && count($this->errors)>0) echo $_POST['uultra_tooltip']; ?>"
			class="regular-text" /> <i
			class="uultra-icon-question-sign uultra-tooltip2"
			title="<?php _e('A tooltip text can be useful for social buttons on profile header.','booking-ultra-pro'); ?>"></i>
		</td>
	</tr>
    
    
     <tr valign="top">
                <th scope="row"><label for="uultra_help_text"><?php _e('Help Text','booking-ultra-pro'); ?>
                </label></th>
                <td>
                    <textarea class="uultra-help-text" id="uultra_help_text" name="uultra_help_text" title="<?php _e('A help text can be useful for provide information about the field.','booking-ultra-pro'); ?>" ><?php if (isset($_POST['uultra_help_text']) && isset($this->errors) && count($this->errors)>0) echo $_POST['uultra_help_text']; ?></textarea>
                    <i class="uultra-icon-question-sign uultra-tooltip2"
                                title="<?php _e('Show this help text under the profile field.','booking-ultra-pro'); ?>"></i>
                </td>
            </tr>

	
  

	<tr valign="top">
		<th scope="row"><label for="uultra_can_edit"><?php _e('User can edit','booking-ultra-pro'); ?>
		</label></th>
		<td><select name="uultra_can_edit" id="uultra_can_edit">
				<option value="1">
					<?php _e('Yes','booking-ultra-pro'); ?>
				</option>
				<option value="0">
					<?php _e('No','booking-ultra-pro'); ?>
				</option>
		</select> <i class="uultra-icon-question-sign uultra-tooltip2"
			title="<?php _e('Users can edit this profile field or not.','booking-ultra-pro'); ?>"></i>
		</td>
	</tr>

	
	


	<tr valign="top">
		<th scope="row"><label for="uultra_private"><?php _e('This field is required','booking-ultra-pro'); ?>
		</label></th>
		<td><select name="uultra_required" id="uultra_required">
				<option value="0">
					<?php _e('No','booking-ultra-pro'); ?>
				</option>
				<option value="1">
					<?php _e('Yes','booking-ultra-pro'); ?>
				</option>
		</select> <i class="uultra-icon-question-sign uultra-tooltip2"
			title="<?php _e('Selecting yes will force user to provide a value for this field at registration and edit profile. Registration or profile edits will not be accepted if this field is left empty.','booking-ultra-pro'); ?>"></i>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row"><label for="uultra_show_in_register"><?php _e('Show on Registration form','booking-ultra-pro'); ?>
		</label></th>
		<td><select name="uultra_show_in_register" id="uultra_show_in_register">
				<option value="0">
					<?php _e('No','booking-ultra-pro'); ?>
				</option>
				<option value="1">
					<?php _e('Yes','booking-ultra-pro'); ?>
				</option>
		</select> <i class="uultra-icon-question-sign uultra-tooltip2"
			title="<?php _e('Show this field on the registration form? If you choose no, this field will be shown on edit profile only and not on the registration form. Most users prefer fewer fields when registering, so use this option with care.','booking-ultra-pro'); ?>"></i>
		</td>
        
        
	</tr>
    
    
     
    
            
   

	<tr valign="top" class="uultra-icons-holder">
		<th scope="row"><label><?php _e('Icon for this field','booking-ultra-pro'); ?> </label>
		</th>
		<td><label class="uultra-icons"><input type="radio" name="uultra_icon"
				value="0" /> <?php _e('None','booking-ultra-pro'); ?> </label> 
				<?php foreach($this->fontawesome as $icon) { ?>
			<label class="uultra-icons"><input type="radio" name="uultra_icon"
				value="<?php echo $icon; ?>" />
                <i class="fa fa-<?php echo $icon; ?> uultra-tooltip3" title="<?php echo $icon; ?>"></i> </label>            <?php } ?>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row"></th>
		<td>
          <div class="bup-ultra-success bup-notification" id="bup-sucess-add-field"><?php _e('Success ','booking-ultra-pro'); ?></div>
        <input type="submit" name="bup-add" 	value="<?php _e('Submit New Field','booking-ultra-pro'); ?>"
			class="button button-primary" id="bup-btn-add-field-submit" /> 
            <input type="button"class="button button-secondary " id="bup-close-add-field-btn"	value="<?php _e('Cancel','booking-ultra-pro'); ?>" />
		</td>
	</tr>

</table>


</div>


<!-- show customizer -->
<ul class="bup-ultra-sect bup-ultra-rounded" id="uu-fields-sortable" >
		
  </ul>
  
           <script type="text/javascript">  
		
		      var custom_fields_del_confirmation ="<?php _e('Are you totally sure that you want to delete this field?','booking-ultra-pro'); ?>";
			  
			  var custom_fields_reset_confirmation ="<?php _e('Are you totally sure that you want to restore the default fields?','booking-ultra-pro'); ?>";
			   
			  var custom_fields_duplicate_form_confirmation ="<?php _e('Please input a name','booking-ultra-pro'); ?>";
		 
		 bup_reload_custom_fields_set();
		 </script>
         
         <div id="bup-spinner" class="bup-spinner" style="display:">
            <span> <img src="<?php echo bookingup_url?>admin/images/loaderB16.gif" width="16" height="16" /></span>&nbsp; <?php echo __('Please wait ...','booking-ultra-pro')?>
	</div>
         
        