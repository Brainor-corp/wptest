<div class="popular_services_block">
			<h3>Популярные услуги</h3>
			<div class="tab-content">
		
<?php 
$args = array(
    'posts_per_page'=> 4,
    'cat' => '9',
    'order' => 'DESC',
);
$lastposts = get_posts( $args );
foreach( $lastposts as $post ){ setup_postdata($post);

?>
<a href="<?php echo get_permalink(); ?> " class="btn item">
             <span class="filters"><?php echo get_the_post_thumbnail(); ?></span><p><?php echo get_the_title(); ?></p>
             <span class="price">от <span><?php echo get_field('price-from'); ?></span></span>
         </a>

    <?php 
}
wp_reset_postdata();
?>
     
		</div>
		</div>