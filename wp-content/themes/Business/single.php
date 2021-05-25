<?php
/**
 * @package Business Meeting
 * @subpackage Team
 * @since Business Meeting 1.0
 */
?>
<?php get_header(); ?>
<!-- BEGIN content -->

<div class="posts">
	<div class="archives_post">
		<div class="buffer">
		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
		<?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="title">Archive for the <strong><?php single_cat_title(); ?></strong> Category</h2>
		<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="title">Posts Tagged <strong><?php single_tag_title(); ?></strong></h2>
		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="title">Archive for <?php the_time('F jS, Y'); ?></h2>
		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="title">Archive for <?php the_time('F, Y'); ?></h2>
		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="title">Archive for <?php the_time('Y'); ?></h2>
		<?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="title">Author Archive</h2>
		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="title">Blog Archives</h2>
		<?php } ?>
	
		<?php if (have_posts()) : the_post();?>
		<!-- begin post -->
		<div class="single">
			<div style="padding:0 0 10px">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="postmetadata">
				Date: <span class="blue"><?php the_time('j') ?> <?php the_time('M Y') ?></span> | Author: <?php the_author_posts_link() ?> | Category: <?php the_category(', ') ?><span class="comm"><?php comments_popup_link('no comments', '1 comment', '% comments'); ?></span><?php the_tags(' | Tags: ', ', ', ''); ?><div class="clr"></div>
				</div>
			
				<div class="entry">
					<?php the_content('Read more...'); ?>
				</div>
			</div>
		</div>
		<!-- end post -->
		
		<!-- begin comments -->
		<div id="comments" class="box">
			<?php comments_template(); ?>
		</div>
		<!-- end comments -->
	
		<?php endif; ?>
		</div>
	</div>
</div>

<?php get_sidebar(); get_footer(); ?>
