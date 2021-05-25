<?php
/**
 * @package WordPress
 * @subpackage Business Meeting
 * @since Business Meeting 1.0
 */
?>
<?php get_header(); ?>

<!-- BEGIN content -->
<div class="posts">
	<div class="archives_post">
		<div class="buffer">
			<?php 
			if (have_posts()) :
			while (have_posts()) : the_post();
			?>
			
			<!-- begin post -->
			<div class="single">
				<div style="padding:0 0 40px">
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="postmetadata">
					Date: <span class="blue"><?php the_time('j') ?> <?php the_time('M Y') ?></span> | Author: <?php the_author_posts_link() ?> | Category: <?php the_category(', ') ?><span class="comm"><?php comments_popup_link('no comments', '1 comment', '% comments'); ?></span><?php the_tags(' | Tags: ', ', ', ''); ?><div class="clr"></div>
					</div>
				
					<div class="entry">
						<?php the_excerpt('Read more...'); ?>
					</div>
				</div>
			</div>
			<!-- end post -->
			
			<?php endwhile; ?> 
			<div class="postnav">
				<?php next_posts_link('&laquo; Previous Entries'); ?> &nbsp; 
				<?php previous_posts_link('Next Entries &raquo;'); ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php get_sidebar(); get_footer(); ?>