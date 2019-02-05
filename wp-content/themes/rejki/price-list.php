<?php
/**
 * Template Name: price-list
 */
get_header(); ?>

<div class="price_list">
	<?php get_sidebar(); ?>
<div class="cage">
	<div class="wrapper">
	<h1><?php echo get_field('title_main'); ?></h1>
	<img class="img_1" src="<?php echo get_field('img'); ?>">
	<div class="atention">
		<p>
		<?php echo get_field('atention'); ?>
		</p>

	</div>

	<div class="tabs">
<div class="wrapp_for_table">
		<table class="active">
			<tr><td>ремонт рулевых реек</td><td>стоимость</td><td> </td></tr>
			<?php
			$service_r = get_field('service_r');
			foreach ($service_r as $key => $value): ?>
				<tr><td><?php echo $value['type'] ?></td><td>от <?php echo $value['price_standart'] ?> руб.</td><!--<td>от <?php echo $value['price_premium'] ?> руб.</td>--></tr>
			<?php endforeach ?>
		</table>
</div>
<div class="wrapp_for_table">
		<table>
			<tr><td>дополнительные услуги</td><td>стоимость</td><!--<td>премиум</td>--></tr>
			<?php
			$addition_serv = get_field('addition_serv');
			foreach ($addition_serv as $key => $value): ?>
				<tr><td><?php echo $value['type'] ?></td><td>от <?php echo $value['price_standart'] ?> руб.</td><!--<td>от <?php echo $value['price_premium'] ?> руб.</td>--></tr>
			<?php endforeach ?>
		</table>
</div>
	</div>
	<?php include_once 'inc/blocks/adition_services.php'; ?>
	</div>
</div>
</div>

<?php get_footer(); ?>
