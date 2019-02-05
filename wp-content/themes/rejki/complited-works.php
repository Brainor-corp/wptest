<?php 

/** 
 * Template Name: compiled works
 */


get_header();
?>
<div class="complited_works ">
			<?php get_sidebar(); ?>
		<div class="cage">
			<div class="block_complited_works wrapper">
			<h1><?php the_title(); ?></h1>
			<div class="wr">

<?php 
$ids = get_the_ID();
if ($ids == 262) {
	$post_per_pages = -1;
}else{
$post_per_pages = 3;
}
$args = array(
    'posts_per_page'=> $post_per_pages,
    'cat' => '8',
    'order' => 'DESC',
);
$lastposts = get_posts( $args );
$ch = 1;
foreach( $lastposts as $post ){ setup_postdata($post);
?>
<div class="item_works <?php echo $ch%3 == 0 ? 'col-3' : ''; ?> ">
				<a href="<?php echo get_permalink(); ?>" class="img"><?php echo get_the_post_thumbnail(); ?></a>
				<ul>
					<li><?php echo get_the_title(); ?><span>,</span> <span><?php the_field('year_of'); ?></span></li>
					<li><?php echo get_field('type_of_serv')->post_title; ?></li>
					<li><?php the_field('short_descr_complit'); ?></li>
					<li><a href="<?php echo get_permalink(); ?>">Подробнее</a></li>
				</ul>
			</div>

 <?php 
 $ch+=1;
}
wp_reset_postdata();
?>
</div>


<!-- <a href="<?php echo get_template_directory_uri(); ?>" class="wide_button">Показать еще</a>
	</div> -->
</div>
</div>





<?php 
include_once 'inc/blocks/free_consultation.php';
 ?>
<?php 
get_footer(); ?>