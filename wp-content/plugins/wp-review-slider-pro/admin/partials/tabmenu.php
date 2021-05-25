<?php
$urltrimmedtab = remove_query_arg( array('page', 'forcerecal','_wpnonce', 'rtype', 'taction', 'tid', 'sortby', 'sortdir', 'opt','settings-updated','revfilter') );

$urlget['getrevs'] = esc_url( add_query_arg( 'page', 'wp_pro-getrevs',$urltrimmedtab ) );
$urlget['review_funnel'] = esc_url( add_query_arg( 'page', 'wp_pro-reviewfunnel',$urltrimmedtab ) );
$urlget['settings'] = esc_url( add_query_arg( 'page', 'wp_pro-settings',$urltrimmedtab ) );
$urlget['googlesettings'] = esc_url( add_query_arg( 'page', 'wp_pro-googlesettings',$urltrimmedtab ) );
$urlget['reviews'] = esc_url( add_query_arg( 'page', 'wp_pro-reviews',$urltrimmedtab ) );
$urlget['templates_posts'] = esc_url( add_query_arg( 'page', 'wp_pro-templates_posts',$urltrimmedtab ) );
$urlget['badges'] = esc_url( add_query_arg( 'page', 'wp_pro-badges',$urltrimmedtab ) );
$urlget['forms'] = esc_url( add_query_arg( 'page', 'wp_pro-forms',$urltrimmedtab ) );
$urlget['float'] = esc_url( add_query_arg( 'page', 'wp_pro-float',$urltrimmedtab ) );
$urlget['analytics'] = esc_url( add_query_arg( 'page', 'wp_pro-analytics',$urltrimmedtab ) );
$urlget['get_yelp'] = esc_url( add_query_arg( 'page', 'wp_pro-get_yelp',$urltrimmedtab ) );
$urlget['get_tripadvisor'] = esc_url( add_query_arg( 'page', 'wp_pro-get_tripadvisor',$urltrimmedtab ) );
$urlget['get_twitter'] = esc_url( add_query_arg( 'page', 'wp_pro-get_twitter',$urltrimmedtab ) );
$urlget['forum'] = esc_url( add_query_arg( 'page', 'wp_pro-forum',$urltrimmedtab ) );
$urlget['notifications'] = esc_url( add_query_arg( 'page', 'wp_pro-notifications',$urltrimmedtab ) );
$urlget['getrevs-contact'] = esc_url( add_query_arg( 'page', 'wp_pro-getrevs-contact',$urltrimmedtab ) );
$urlget['get_airbnb'] = esc_url( add_query_arg( 'page', 'wp_pro-get_airbnb',$urltrimmedtab ) );
$urlget['get_vrbo'] = esc_url( add_query_arg( 'page', 'wp_pro-get_vrbo',$urltrimmedtab ) );
$urlget['get_woo'] = esc_url( add_query_arg( 'page', 'wp_pro-get_woo',$urltrimmedtab ) );
$urlget['reviewfunnel'] = esc_url( add_query_arg( 'page', 'wp_pro-reviewfunnel',$urltrimmedtab ) );
$urlget['get_apps'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'iTunes',
),$urltrimmedtab ) );
$urlget['get_apps_gyg'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'GetYourGuide',
),$urltrimmedtab ) );
$urlget['get_apps_hcp'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'HousecallPro',
),$urltrimmedtab ) );
$urlget['get_apps_nd'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Nextdoor',
),$urltrimmedtab ) );
$urlget['get_apps_fr'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Freemius',
),$urltrimmedtab ) );
$urlget['get_apps_qu'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Qualitelis',
),$urltrimmedtab ) );
$urlget['get_apps_zillow'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Zillow',
),$urltrimmedtab ) );
$urlget['get_apps_angieslist'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'AngiesList',
),$urltrimmedtab ) );
$urlget['get_apps_feefo'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Feefo',
),$urltrimmedtab ) );


//Notifications
?>	
	<h2 class="nav-tab-wrapper">
	<div style="position: relative;">
	<a href="<?php echo $urlget['getrevs']; ?>" class="getrevshiddenmenu nav-tab <?php if($_GET['page']=='wp_pro-getrevs'){echo 'nav-tab-active';} ?>"><?php _e('Get Reviews', 'wp-review-slider-pro'); ?></a>
	<div id="getrevshiddenmenu_inner">
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-getrevs" class="ahiddengetrevs"><i>Welcome</i></a>
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-get_airbnb" class="ahiddengetrevs">Airbnb</a>
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-get_apps&rtype=AngiesList" class="ahiddengetrevs">Angie's List</a>
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-settings" class="ahiddengetrevs">Facebook</a>
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-get_apps&rtype=Feefo" class="ahiddengetrevs">Feefo</a>
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-get_apps&rtype=Freemius" class="ahiddengetrevs">Freemius</a>
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-get_apps&rtype=GetYourGuide" class="ahiddengetrevs">Get Your Guide</a>
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-googlesettings" class="ahiddengetrevs">Google Places API</a>
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-get_apps&rtype=HousecallPro" class="ahiddengetrevs">Housecall Pro</a>
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-get_apps&rtype=iTunes" class="ahiddengetrevs">iTunes</a>
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-get_apps&rtype=Nextdoor" class="ahiddengetrevs">Nextdoor</a>
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-get_apps&rtype=Qualitelis" class="ahiddengetrevs">Qualitelis</a>
		
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-get_tripadvisor" class="ahiddengetrevs">TripAdvisor</a>
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-get_twitter" class="ahiddengetrevs">Twitter</a>
				<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-get_vrbo" class="ahiddengetrevs">VRBO</a>
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-get_woo" class="ahiddengetrevs">WooCommerce</a>
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-get_yelp" class="ahiddengetrevs">Yelp</a>
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-get_apps&rtype=Zillow" class="ahiddengetrevs">Zillow</a>
		<a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_pro-reviewfunnel" class="ahiddengetrevs">Review Funnels</a>
	</div>
	</div>
	<a href="<?php echo $urlget['review_funnel']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-reviewfunnel'){echo 'nav-tab-active';} ?>"><?php _e('More Reviews', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['reviews']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-reviews'){echo 'nav-tab-active';} ?>"><?php _e('Review List', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['templates_posts']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-templates_posts'){echo 'nav-tab-active';} ?>"><?php _e('Templates', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['badges']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-badges'){echo 'nav-tab-active';} ?>"><?php _e('Badges', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['forms']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-forms'){echo 'nav-tab-active';} ?>"><?php _e('Forms', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['float']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-float'){echo 'nav-tab-active';} ?>"><?php _e('Floats', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['analytics']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-analytics'){echo 'nav-tab-active';} ?>"><?php _e('Analytics', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['notifications']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-notifications'){echo 'nav-tab-active';} ?>"><?php _e('Settings', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['forum']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-forum'){echo 'nav-tab-active';} ?>"><?php _e('Support', 'wp-review-slider-pro'); ?></a>
	</h2>
<div class="getrevshiddenmenu">

</div>