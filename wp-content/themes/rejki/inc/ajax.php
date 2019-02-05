<?php 

$req = $_GET['quest'];
if ($req) {
$post_per_pages = $req;
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
				<div class="img"><?php echo get_the_post_thumbnail(); ?></div>
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
}
die();