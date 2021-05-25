<?php
//When the post is saved, saves our custom data
function theme_save_postdata($post_id) 
{
	global $themename;
	// verify if this is an auto save routine. 
	// If it is our form has not been submitted, so we dont want to do anything
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		return;

	// Check permissions
	if(!current_user_can('edit_post', $post_id))
		return;
		
	//OK, we're authenticated: we need to find and save the data
	//sidebars
	update_post_meta($post_id, "page_sidebar_header", (isset($_POST["page_sidebar_header"]) ? $_POST["page_sidebar_header"] : ''));
	update_post_meta($post_id, "page_sidebar_top", (isset($_POST["page_sidebar_top"]) ? $_POST["page_sidebar_top"] : ''));
	update_post_meta($post_id, "page_sidebar_right", (isset($_POST["page_sidebar_right"]) ? $_POST["page_sidebar_right"] : ''));
	update_post_meta($post_id, "page_sidebar_footer_top", (isset($_POST["page_sidebar_footer_top"]) ? $_POST["page_sidebar_footer_top"] : ''));
	update_post_meta($post_id, "page_sidebar_footer_bottom", (isset($_POST["page_sidebar_footer_bottom"]) ? $_POST["page_sidebar_footer_bottom"] : ''));
	update_post_meta($post_id, $themename . "_page_sidebars", array_values(array_filter(array(
		(!empty($_POST["page_sidebar_footer_top"]) ? $_POST["page_sidebar_footer_top"] : NULL),
		(!empty($_POST["page_sidebar_footer_bottom"]) ? $_POST["page_sidebar_footer_bottom"] : NULL)
	))));
}
add_action("save_post", "theme_save_postdata");
?>