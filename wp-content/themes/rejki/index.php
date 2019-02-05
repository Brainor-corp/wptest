<?php
/**
 * Template Name: mainNew
 */
get_header(); ?>

<div class="service">
	<?php get_sidebar(); ?>
	<div class="cage">
		<div class="wrapper">

            <?php echo do_shortcode('[br_tools type="single" slug="br-tools"]') ?>

			<h1>Ремонт рулевого управления
</h1>

			<?php if (get_field('check_show')): ?>
				<div class="block_2_imgs">
				<div><a href="<?php echo get_page_link(51); ?>"><p><?php the_field('first_block'); ?></p></a></div>
				<div><a href="<?php echo get_page_link(5); ?>"><p><?php the_field('second_block'); ?></p></a>
					</div>
			</div>
			<?php endif ?>

		</div>
		<div class="form_porm_10_p">
			<div class="wrapper">
				<h3>Записаться online со скидкой 10% и БЕСПЛАТНОЙ диагностикой</h3>
			</div>
			<form action="mail.php" method="post">
					 <input type="text" name="name" placeholder="Ваше имя">
					<input type="tel" name="phone" placeholder="+7 (___) ___-__-__" class="phone">
					<button>оставить заявку</button>
        </form>
		</div>
		<div class="wrapper">
			<span class="simple-text"><?php the_field('txt_info'); ?></span>

<?php include_once 'inc/blocks/reviews.php'; ?>

<?php include 'inc/blocks/item_works.php'; ?>
<?php include 'inc/blocks/why_we.php'; ?>
<div class="questions">
	<h3>У Вас есть вопросы?</h3>
	<div class="tabs">


		<?php $tabs = get_field('questions');
		$v = 0;
		foreach ($tabs as $key => $value) {
			$clas = $v==0 ? 'active': '';
echo <<<BLOCK
<div class="wrapp_for_table">
		<table class="{$clas}">
			<tr><td>{$value['quest']}</td></tr>
			<tr>
				<td>
					{$value['resp']}
				</td>
			</tr>
		</table>
		</div>
BLOCK;
		$v+=1;
		}
		?>





	</div>
</div>
<?php include_once 'inc/blocks/about-company.php'; ?>
		<?php include_once 'inc/blocks/popular-services.php'; ?>

		</div>
	</div>
	<?php include_once 'inc/blocks/free_consultation.php'; ?>
</div>
<?php get_footer(); ?>
