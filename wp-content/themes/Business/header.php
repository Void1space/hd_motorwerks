<?php
/**
 * @package WordPress
 * @subpackage Business Meeting
 * @since Business Meeting 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php if (get_option('sg_enable_slider')=='') { ?> <?php } ?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_directory_uri(); ?>/js/galleria.dots.css" />
	<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/galleria.js"></script>
	<?php $number_of_slides = get_option('sg_slidenum'); $slider_enabled = get_option('sg_enable_slider');?>
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body class="page_bg">
	<header>
		<div class="sitename">
			<h1><a href="<?php echo get_option('home'); ?>" class="logo"><?php bloginfo('name'); ?></a></h1>
			<h2><?php bloginfo('description');?></h2>
		</div>
		<div class="top-menu">
			<div id="sgmenu">
				<ul class="menu">
					
					<?php wp_list_pages('sort_column=menu_order&title_li='); ?>
				</ul>
			</div>
		</div>
		<div id="slidewrap">
			<div id="slideshow">
				<div id="galleria">
				
				<?php if (is_single() or is_page()): ?> 
					<?php if (has_post_thumbnail()): ?>
						<?php the_post_thumbnail('full', array('class' => 'featuredslide')); ?>
					<?php else: ?>	
						<?php
						if ($slider_enabled == 'Yes'):
						for ($i = 1; $i <= $number_of_slides; $i++) { ?>	  
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/image<? echo $i; ?>.jpg" alt="Slideshow Image" />
						<?php }; ?>
						</div>
						<script>
							    Galleria.loadTheme('<?php echo get_stylesheet_directory_uri(); ?>/js/galleria.dots.js'); 
								$('#galleria').galleria();
						</script>
						<?php else: ?>
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/image1.jpg" alt="Slideshow Image" />
						</div>
						<?php endif; ?>
					<?php endif; ?>
				<?php else: ?>
					<?php
					if ($slider_enabled == 'Yes'):
					for ($i = 1; $i <= $number_of_slides; $i++) { ?>	  
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/image<? echo $i; ?>.jpg" alt="Slideshow Image" />
					<?php }; ?>
					</div>
					<script>
						    Galleria.loadTheme('<?php echo get_stylesheet_directory_uri(); ?>/js/galleria.dots.js'); 
							$('#galleria').galleria();
					</script>
					<?php else: ?>
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/image1.jpg" alt="Slideshow Image" />
					</div>
					<?php endif; ?>
				<?php endif;?>
				</div>
			</div>
		</header>
	<section id="content">