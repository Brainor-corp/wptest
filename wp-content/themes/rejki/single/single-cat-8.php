<?php
/** 
 * Template Name: sinple for posts
 * Template Post Type: post
 */
 get_header(); ?>
<?php get_sidebar(); ?>
<div class="cage mwForPosts">
		<div class="wrapper simple-text">
			<h1><?php the_title(); ?></h1>
<?php the_content(); ?>
<?php echo get_field('cont'); ?>
</div>
</div>
<?php get_footer(); ?>