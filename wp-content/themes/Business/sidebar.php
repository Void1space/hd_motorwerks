<?php
/**
 * @package WordPress
 * @subpackage Business Meeting
 * @since Business Meeting 1.0
 */
?>

<!-- END content -->

<!-- BEGIN sidebar -->
<div id="sidebar">

<?php if ( !function_exists('dynamic_sidebar')
|| !dynamic_sidebar(1) ) : ?>

	<!-- begin categories -->
	<h2>Categories</h2>
	<ul><?php wp_list_categories(); ?></ul>
	<!-- end categories -->

	<!-- begin archives -->
	<h2>Archive</h2>
	<ul><?php wp_get_archives('type=monthly'); ?></ul>
	<!-- end archives -->
	
	<!-- begin blogroll -->
	<?php wp_list_bookmarks('category_before=&category_after=&title_before=<h2>&title_after=</h2>'); ?>
	<!-- end blogroll -->
<?php endif; ?>

</div>
<div class="clr"></div>
<!-- END sidebar -->