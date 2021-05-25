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
 
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
 
?>
    
<div class="wrap wp_pro-settings" id="">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>

<?php 
include("tabmenu.php");
?>
<div class="w3-row">
<div class="w3-col m2 sidemenucontainer">
<?php
include("getrevs_sidemenu.php");
?>	
</div>
<div class="w3-col m8 welcomediv">

	<h3><?php _e('Welcome!', 'wp-review-slider-pro'); ?> </h3>
	<p><?php _e('Thank you for being an awesome WP Review Slider Pro customer! If you have trouble, or need a feature added, please don\'t hesitate to contact me. I\'m always looking for ways to improve the plugin.', 'wp-review-slider-pro'); ?> </p>
	<h3><?php _e("Getting Started:", 'wp-review-slider-pro'); ?> </h3>
	<p><?php _e("1) If you haven't done so, de-activate the Free versions of this plugin and delete them. Then go ahead and de-activate and re-activate the Pro version. That will force all the database tables to update.", 'wp-review-slider-pro'); ?> </p>
	<p><?php 
echo sprintf(__( '2) Use the buttons to the left to Download your reviews from different sites and save them to your database. The "%1$sReview Funnels%2$s" page allows you to scrape all your reviews from many more sites along with the ones listed on the left. Manual reviews can be inserted on the "%3$sReview List%2$s" page.', 'wp_fb-reviews' ), 
						'<a href="'.$urlget['reviewfunnel'].'">', 
						'</a>', 
						'<a href="'.$urlget['reviews'].'">'
						);
	?> </p>
	<p>	
	<?php 
echo sprintf(__( '3) Once downloaded, all the reviews should show up on the "%1$sReview List%2$s" page of the plugin.', 'wp_fb-reviews' ), 
						'<a href="'.$urlget['reviews'].'">', 
						'</a>'
						);
	?>
	</p>
	<p>	
		<?php 
echo sprintf(__( '4) Create a Review Slider or Grid for your site on the "%1$sTemplates%2$s" page. By default the review template will show all your reviews, you can use the filters to only show the reviews you want.', 'wp_fb-reviews' ), 
						'<a href="'.$urlget['templates_posts'].'">', 
						'</a>'
						);
	?>
	</p>
	<p>	
	<?php 
echo sprintf(__( '5) You can also create badges on the "%1$sBadges%2$s" page and even a review submission form on the "%3$sForms%2$s" page.', 'wp_fb-reviews' ), 
						'<a href="'.$urlget['badges'].'">', 
						'</a>', 
						'<a href="'.$urlget['forms'].'">'
						);
	?>
	</p>
	<p>
	<?php 
echo sprintf(__( '6) Finally, you can use the "%1$sFloats%2$s" page to make a badge or review template float on your site. ', 'wp_fb-reviews' ), 
						'<a href="'.$urlget['float'].'">', 
						'</a>'
						);
	?>
	</p>
	<p>
	<?php 
	echo sprintf(__( 'If you have any trouble please check the %1$sSupport Forum%2$s first. If you want to contact me privately you can either enter your question in the forum as a ticket or use this %3$sform%2$s. I\'m always happy to help!', 'wp_fb-reviews' ), 
						'<a href="'.$urlget['forum'].'">', 
						'</a>', 
						'<a href="'.$urlget['getrevs-contact'].'">'
						);

	?>
	</p>
	<h3><?php _e("Future Roadmap:", 'wp-review-slider-pro'); ?> </h3>
	<p><?php echo sprintf(__( 'I believe that the best way to improve this plugin is by listening to feedback from you! Please feel free to suggest new features and follow development on this %1$sTrello board%2$s. ', 'wp-review-slider-pro'),'<a href="https://trello.com/b/NdGyfLSq/wp-review-slider-pro-roadmap" target="_blank">', 
						'</a>'); ?> </p>
	<p><?php echo sprintf(__( 'Thanks!<br>Josh<br>Developer/Creator<br>%1$sLJ Apps%2$s', 'wp-review-slider-pro'),'<a href="https://ljapps.com" target="_blank">','</a>'); ?> </p>

</div>

</div>




