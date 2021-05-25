<?php
//used for itunes, and etc.. rtype
if (isset($_GET['rtype'])) {
    $rtype=$_GET['rtype'];
} else {
    // Fallback behaviour goes here
	$rtype='';
}

?>
<div class="w3-bar-block w3-light-grey getrevssidemenu">
	<a href="<?php echo $urlget['getrevs']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-getrevs'){echo 'w3-bluewp';} ?>"><?php _e('Welcome', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['get_airbnb']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-get_airbnb'){echo 'w3-bluewp';} ?>"><?php _e('Airbnb', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['get_apps_angieslist']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-get_apps' && $rtype=='AngiesList'){echo 'w3-bluewp';} ?>"><?php _e('AngiesList', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['settings']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-settings'){echo 'w3-bluewp';} ?>"><?php _e('Facebook', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['get_apps_feefo']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-get_apps' && $rtype=='Feefo'){echo 'w3-bluewp';} ?>"><?php _e('Feefo', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['get_apps_fr']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-get_apps' && $rtype=='Freemius'){echo 'w3-bluewp';} ?>"><?php _e('Freemius', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['get_apps_gyg']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-get_apps' && $rtype=='GetYourGuide'){echo 'w3-bluewp';} ?>"><?php _e('Get Your Guide', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['googlesettings']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-googlesettings'){echo 'w3-bluewp';} ?>"><?php _e('Google Places API', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['get_apps_hcp']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-get_apps' && $rtype=='HousecallPro'){echo 'w3-bluewp';} ?>"><?php _e('Housecall Pro', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['get_apps']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-get_apps' && $rtype=='iTunes'){echo 'w3-bluewp';} ?>"><?php _e('iTunes', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['get_apps_nd']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-get_apps' && $rtype=='Nextdoor'){echo 'w3-bluewp';} ?>"><?php _e('Nextdoor', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['get_apps_qu']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-get_apps' && $rtype=='Qualitelis'){echo 'w3-bluewp';} ?>"><?php _e('Qualitelis', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['get_tripadvisor']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-get_tripadvisor'){echo 'w3-bluewp';} ?>"><?php _e('TripAdvisor', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['get_twitter']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-get_twitter'){echo 'w3-bluewp';} ?>"><?php _e('Twitter', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['get_vrbo']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-get_vrbo'){echo 'w3-bluewp';} ?>"><?php _e('VRBO', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['get_woo']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-get_woo'){echo 'w3-bluewp';} ?>"><?php _e('WooCommerce', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['get_yelp']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-get_yelp'){echo 'w3-bluewp';} ?>"><?php _e('Yelp', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['get_apps_zillow']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-get_apps' && $rtype=='Zillow'){echo 'w3-bluewp';} ?>"><?php _e('Zillow', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['reviewfunnel']; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wp_pro-reviewfunnel'){echo 'w3-bluewp';} ?>"><b><?php _e('Review Funnels<br><small>(more sites)</small>', 'wp-review-slider-pro'); ?></b></a>
	
</div>