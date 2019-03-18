<?php
/** 
 * Template Name: sinple for posts
 * Template Post Type: post, page
 */
 get_header(); ?>
<?php get_sidebar(); ?>
<div class="cage mwForPosts">
		<div class="wrapper simple-text" style="line-height: 22px !important;">
            <?php do_action('show_beautiful_filters'); ?>
<?php echo do_shortcode( '[searchandfilter fields="category,post_tag" types="checkbox,checkbox"]' ); ?>
			<h1><?php the_title(); ?></h1>
<?php the_content(); ?>
</div>
</div>
<?php get_footer(); ?>