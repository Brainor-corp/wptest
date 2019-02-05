<?php
/** 
 * Template Name: photos
 */
 get_header(); ?>
 <div class="photos">
<?php get_sidebar(); ?>

<div class="cage mwForPosts">
	<div class="wrapper">
		<h1><?php the_title(); ?></h1>

<?php $obj = get_field('links_to_posts'); 


?>

<div class="wrap_l comp_works">
	<?php $ch = 1; ?>
	<?php foreach( $obj as $post ){ setup_postdata($post); ?>
		<div class="item_works <?php echo $ch%3 == 0 ? 'col-3' : ''; ?> ">
				<a href="<?php echo the_permalink(); ?>" class="img"><?php echo get_the_post_thumbnail(); ?></a>
				<ul>
					<li><?php echo get_the_title(); ?><span>,</span> <span><?php the_field('year_of'); ?></span></li>
						<li><?php echo get_field('type_of_serv')->post_title; ?></li>
				</ul>
			</div>
	<?php $ch+=1;};
wp_reset_postdata();
	?>
	
</div>

	<div class="wrap_video">
		<h3>ВИДЕО</h3>
		<?php $ch = 1; ?>
<?php foreach (get_field('video') as $key => $value): ?>
	<div class="item <?php echo $ch%3 == 0 ? 'col-3' : ''; ?>">
			<a href='<?php echo $value['vid']; ?>' class="item_video">
				<img src="<?php echo $value['preview']; ?>">
			</a>
				<ul>
					<li><?php echo $value['name']; ?></li>
					<li><?php  echo $value['descr']; ?></li>
				</ul>
		</div>
		<?php $ch += 1; ?>
<?php endforeach ?>

		
	</div>

<div class="from_to">
	<h3>до и после</h3>
	<?php foreach (get_field('beforeAfter') as $key => $value): ?>
		<div class="rowr">
			<div class="item"><div class="img"><img src="<?php echo $value['do']['img']; ?>"></div>
			<ul>
				<li><?php echo $value['do']['name']; ?></li>
				<li><?php echo $value['do']['descr']; ?></li>
			</ul>
			</div>

			<div class="item col-2"><div class="img"><img src="<?php echo $value['after']['img']; ?>"></div>
			<ul>
				<li><?php echo $value['after']['name']; ?></li>
				<li><?php echo $value['after']['descr']; ?></li>
			</ul>
			</div>
</div>
	<?php endforeach ?>
	
</div>



	</div>
</div>
	<?php include_once 'inc/blocks/free_consultation.php'; ?>
	</div>
<?php get_footer(); ?>