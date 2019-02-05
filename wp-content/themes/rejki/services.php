<?php 
/** 
 * Template Name: Services

 */
 get_header(); ?>
 <div class="services">
	
<?php get_sidebar(); ?>
	<div class="cage">

		<div class="wrapper">
			<h1><?php the_title(); ?></h1>
			<span class="simple-text">
				<?php the_field('descr'); ?>
			</span>
		<div class="main_serv">
			<h3>Основные услуги</h3>
			<section id="generic-tabs">

                <ul id="tabs">
                    <li class="active">
                        <a title="Home" href="#first-tab"> Санкт Петербург</a>
                    </li>
                    <li>
                        <a title="Photos" href="#second-tab"> Великий Новгород</a>
                    </li>
                    <li>
                        <a title="About" href="#third-tab"> Мурманск</a>
                    </li>

                </ul>
                <?php $main_serv = get_field('main_serv'); ?>
                <div id="first-tab" class="tab-content" style="display: block;">
            <span class="main">
           <?php foreach ($main_serv['spb'] as $key => $value): ?>
             <a href="<?php echo get_post_permalink($value['link_to_page']); ?>" class="btn item">
            <span class="filter"><img src="<?php echo $value['img'] ?>"></span>
             <p><?php echo $value['text']; ?></p>
             <span class="price">от <span><?php echo $value['ot']; ?></span></span>
         </a>
           <?php endforeach ?>
       
              
      </span>
    </div>
    <div id="second-tab" class="tab-content" style="display: none;">
<span class="main">
         <?php foreach ($main_serv['spb'] as $key => $value): //$main_serv['vn']  ?>
             <a href="<?php echo get_post_permalink($value['link_to_page']); ?>" class="btn item">
            <span class="filter"><img src="<?php echo $value['img'] ?>"></span>
             <p><?php echo $value['text']; ?></p>
             <span class="price">от <span><?php echo $value['ot']; ?></span></span>
         </a>
           <?php endforeach ?>
          
      </span>
    </div>
    <div id="third-tab" class="tab-content" style="display: none;">
<span class="main">
        <?php foreach ($main_serv['spb'] as $key => $value): //$main_serv['mr'] ?>
              <a href="<?php echo get_post_permalink($value['link_to_page']); ?>" class="btn item">
            <span class="filter"><img src="<?php echo $value['img'] ?>"></span>
             <p><?php echo $value['text']; ?></p>
             <span class="price">от <span><?php echo $value['ot']; ?></span></span>
         </a>
           <?php endforeach ?>
        
      </span>
    </div>
            </section>
		</div>
		<?php include_once 'inc/blocks/adition_services.php'; ?>

		</div>
	</div>
	<div class="container proud" id="n_5">
    <div class="row">
        <div class="col-md-12 no-gutter">
           <h4><?php the_field('our_works_title', 5); ?></h4>
            <p><?php the_field('our_works_short_descp', 5); ?></p>
            <div class="examples">
                <?php 
                $examples = get_field('examples', 5);
                foreach ($examples as $key => $value): ?>
                <div class="example">
                    <a>
                        <div class="for_after">
                            <img src="<?php echo $value['img'] ?> " alt="">
                        </div>
                        <span><?php echo $value['txt'] ?></span>
                    </a>

                </div>
                <?php endforeach ?>
            </div>
             <?php include_once 'inc/blocks/reviews.php'; ?>
            <a href="https://vk.com/topic-36293346_26988643" class="hole_otzz">Читать все отзывы Вконтакте</a>
        </div>
    </div>
</div>
<?php include_once "inc/blocks/free_consultation.php"; ?>
</div>
<?php get_footer(); ?>