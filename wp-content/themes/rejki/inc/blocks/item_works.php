<div class="comp_works">
	<h3>выполненные работы</h3>
	
<?php 
$args = array(
    'posts_per_page'=> 3,
    'cat' => '8',
    'order' => 'DESC',
);
$lastposts = get_posts( $args );
$ch = 1;
foreach( $lastposts as $post ){ setup_postdata($post);
?>
<div class="item_works <?php echo $ch%3 == 0 ? 'col-3' : ''; ?> ">
				<a href="<?php the_permalink(); ?>" class="img"><?php echo get_the_post_thumbnail(); ?></a>
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