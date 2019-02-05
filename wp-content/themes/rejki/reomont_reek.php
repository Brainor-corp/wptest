<?php
/** 
 * Template Name: remontRulevychReek
 */
get_header(); ?>


<div class="container-fluid big_fon no-gutter">
    <div class="container">
        <div class="row">
            <div class="col-md-12 no-gutter">
                <div class="block_new_form">
                    <div class="title">
                        <h1><?=  the_field("title_h1"); ?></h1>
                        <p><?php the_field('short_descr') ?></p>
                    </div>
                    <div class="block_form">
                        <form action="<?php echo get_template_directory_uri(); ?>/mail.php" method="post">
                            <p>Записаться на бесплатную диагностику</p>
                            <span>Уже сегодня Вы сможете забыть <br>о проблеме с рулевой рейкой.</span>
                            <input type="text" name="name" placeholder="Ваше имя">
                            <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" class="phone">
                            <input type="hidden" name="data" value="">
                            <input type="hidden" name="time" value="">
                            <div class="ul_listed" style="display:none;">
                                <ul class="list-unstyled v2">
                                    <li class="init">Дата</li>
                                    <li data-value="value 1">Option 1</li>
                                    <li data-value="value 2">Option 2</li>
                                    <li data-value="value 3">Option 3</li>
                                </ul>
                                <ul class="list-unstyled v3">
                                    <li class="init">Время</li>
                                    <li data-value="value 1">Option 1</li>
                                    <li data-value="value 2">Option 2</li>
                                    <li data-value="value 3">Option 3</li>
                                </ul>
                            </div>
                            <button>записаться</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hole_block">
    	<?php $mass_mark = get_field('model_of_cars', get_the_ID()); ?>
    	<?php
    		foreach ($mass_mark as $key => $value) {
    			echo '<div class="item"><a href="#'.'"><img src="'.$value['image'].'" alt=""></a></div>';
    		}
    	 ?>

    </div>
</div>



<div class="container third_section" id="n_1">
    <div class="row">
        <div class="col-md-12 no-gutter">
            <div class="t_block">
                <h2><?php the_field('title_block_2') ?></h2>
                <div class="inside_t_block_hole">
                    <div class="f_t_block">
                        <p>Вам нужно обратиться <br>
                            в сервис, если:</p>
                        <img src="<?= get_template_directory_uri(); ?>/img/trans.png" alt="">
                    </div>
                    <div class="s_t_block">
<?php 
$your_problems = get_field("your_problems");
foreach ($your_problems as $key => $value) {
$key = $key+1;
echo <<<BLOCK
<div class="item_icon">
<div class="empty" style="background-image: url({$value['problem']['img']})"></div>
<p><span>{$key}.</span>{$value['problem']['txt']}</p>
</div>
BLOCK;
}
?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 no-gutter">
            <p class="dex_text">
                Какие <span>последствия</span> Вас
                могут ждать, если Вы
                не отремонтируете рулевую
                рейку вовремя:
            </p>
        </div>
        <div class="col-md-8 no-gutter">
            <a class="show_effects">
                <img src="<?= get_template_directory_uri(); ?>/img/show_effects.png" alt="item">
                <span>Показать последствия</span>
            </a>
            <div class="last_icon_pack" style="display: none;">
                <div class="closer"></div>
<?php
$aftermaths = get_field('aftermaths');
foreach ($aftermaths as $key => $value) {
echo <<<BLOCK
<div class="l_icon">
    <div class="empty_3" style="background-image: url({$value['aftermath']['img']})"></div>
    <p>{$value["aftermath"]["txt"]}</p>
</div>
BLOCK;
}
?>
                <div class="last_block">
                    <p><span>Для человека:</span><?php the_field("for_person"); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid dex_form">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12 no-gutter">
                <div class="text_block">
                    <p>Оставьте заявку или <br>
                        звоните <a href="tel:<?php echo get_field('sity', 53)[0]['number']; ?>"><?php echo get_field('sity', 53)[0]['number']; ?></a></p>
                    <span>Позаботьтесь о своей безопасности и безопасности Ваших попутчиков</span>
                </div>

            </div>
            <div class="col-md-8 col-sm-8 col-xs-12 no-gutter fon_for_mob">
                <div class="dex_form_block">
                    <form action="<?php echo get_template_directory_uri(); ?>/mail.php" method="post">
                        <input type="text" name="name" placeholder="Ваше имя">
                        <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" class="phone">
                        <button>оставить заявку</button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="recover" id="n_2">
                <h3><?php the_field('price_title'); ?></h3>
                <p><?php the_field('price_short_descp'); ?></p>
                <div class="tog_recover">
                    <div class="f_recover">
                        <div class="inside_big_block">
                            <p>Ремонт рулевой рейки: от <?php the_field('service_low_price_1'); ?> руб.</p>
                        </div>
                        <div class="inside_text">
                            <span>Этапы ремонта рулевой рейки: </span>
                            <ul>
                                <?php
                                $list_stages = get_field("list_repair_stages");
                                 foreach ($list_stages['stages'] as $key => $value): ?>
                                    <li><?php echo $value['text']; ?></li>
                                <?php endforeach ?>
                            </ul>
                             <div class="new_text">
                                <?php echo get_field('list_repair_stages')['last_child']; ?>
                            </div>
                            <a data-fancybox data-src="#hidden-content" href="javascript:;">Перезвоните мне</a>
                        </div>
                    </div>
                    <div class="s_recover">
                        <div class="inside_big_block">
                            <p>Замена рулевой рейки: от <?php the_field('service_low_price_2'); ?> руб.</p>
                        </div>
                        <div class="inside_text">
                            <span>Этапы работ по замене рулевой рейки: </span>
                            <ul>
                                <?php
                                $list_stages2 = get_field("list_repair_stages_2");
                                 foreach ($list_stages2['stages'] as $key => $value): ?>
                                    <li><?php echo $value['text']; ?></li>
                                <?php endforeach ?>
                            </ul>
                            <div class="new_text">
                               <?php echo get_field('list_repair_stages_2')['last_child']; ?>
                            </div>
                            <a data-fancybox data-src="#hidden-content" href="javascript:;">Перезвоните мне</a>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid benefits" id="n_3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 no-gutter">
                <h3>преимущества</h3>
                <div class="hole_item_i">

                    <?php
                    $advantages = get_field('advantage');
                     foreach ($advantages as $key => $value): ?>
                    <div class="item_i">
                        <div class="empty_2" style="background-image: url(<?php echo $value['img']; ?>)"></div>
                        <p><?php echo $value['txt']; ?></p>
                    </div>    
                    <?php endforeach ?>
                    

                </div>
            </div>
        </div>
    </div>
</div>



<div class="container dif" id="n_4">
    <div class="row">
        <div class="col-md-12">
            <h3>Производим ремонт и замену рулевых реек для любых
                автомобилей и грузовиков
            </h3>
            <p>Работая с 2005 года мы восстановили более 5000 рулевых реек</p>
            <div class="hole_car_items">
                <?php
                    $kind_of_car = get_field('kind_of_cars');
                     foreach ($kind_of_car as $key => $value): ?>
                    <div class="item_car">
                    <a data-fancybox="" data-src="#hidden-content" href="javascript:;">
                        <img src="<?php echo $value['img'] ?> " alt="">
                        <p><?php echo $value['txt'] ?></p>
                        </a>
                    </div>
                    <?php endforeach ?>

                

                <div class="item_car form">
                    <form action="<?php echo get_template_directory_uri(); ?>/mail.php" method="post">
                        <div class="n_b">
                            <span>оставьте заявку</span>
                            <input type="text" name="name" placeholder="Ваше имя">
                            <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" class="phone">
                        </div>
                        <button>оставить заявку</button>
                    </form>
                </div>


            </div>
        </div>
    </div>
</div>




<div class="container proud" id="n_5">
    <div class="row">
        <div class="col-md-12 no-gutter">
            <h4><?php the_field('our_works_title'); ?></h4>
            <p><?php the_field('our_works_short_descp'); ?></p>
            <div class="examples">

                <?php 
                $examples = get_field('examples');
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
            <a target="blank" href="https://vk.com/topic-36293346_26988643" class="hole_otzz">Читать все отзывы Вконтакте</a>
        </div>
    </div>
</div>



<div class="container dop" id="n_6">
    <div class="row">
        <div class="col-md-12 no-gutter">
            <h4>Дополнительные услуги доступные в нашем автосервисе</h4>
            <p>Все услуги сертифицированы и подтверждены наличием документов в автосервисе</p>


            <section id="generic-tabs">
            
                <ul id="tabs">
                    <li class="active">
                        <a title="Home" href="#first-tab"><?php echo get_field_object('spb')['label']; ?></a>
                    </li>
                    <li>
                        <a title="Photos" href="#second-tab"><?php echo get_field_object('big_newsity')['label'];//big_newsity ?></a>
                    </li>
                    <li>
                        <a title="About" href="#third-tab"><?php echo get_field_object('murmansk')['label'];//murmansk ?></a>
                    </li>

                </ul>
                <div id="first-tab" class="tab-content">
            <span class="main">
    


    <?php 
                $add_serv1 = get_field('spb')['services'];
                foreach ($add_serv1 as $key => $value): ?>
                    <?php if ($key == 9): ?>
            <a data-fancybox="" data-src="#hidden-content" href="javascript:;" class="btn tab_item unique"
                   style="background-image: url(<?php echo get_template_directory_uri() ?>/img/all_u.png);">
             <p>Все услуги</p></a>
                    <?php endif ?>
                     <a data-fancybox="" data-src="#hidden-content" href="javascript:;" class="btn tab_item"
                        style="background-image: url('<?php echo $value['img']['url'] ?> ');">
                         <p><?php echo $value['txt'] ?></p>
                     </a>
                <?php endforeach ?>
             

         
      </span>
                </div>

                <div id="second-tab" class="tab-content">
<span class="main">
          <?php 
                $add_serv2 = get_field('spb')['services'];//big_newsity
                foreach ($add_serv2 as $key => $value): ?>
                    <?php if ($key == 9): ?>
            <a data-fancybox="" data-src="#hidden-content" href="javascript:;" class="btn tab_item unique"
                   style="background-image: url(<?php echo get_template_directory_uri() ?>/img/all_u.png);">
             <p>Все услуги</p></a>
                    <?php endif ?>
                     <a data-fancybox="" data-src="#hidden-content" href="javascript:;" class="btn tab_item"
                        style="background-image: url('<?php echo $value['img']['url'] ?> ');">
                         <p><?php echo $value['txt'] ?></p>
                     </a>
                <?php endforeach ?>
      </span>
                </div>

                <div id="third-tab" class="tab-content">
<span class="main">
       <?php 
                $add_serv3 = get_field('spb')['services']; //murmansk
                foreach ($add_serv3 as $key => $value): ?>
                    <?php if ($key == 9): ?>
            <a data-fancybox="" data-src="#hidden-content" href="javascript:;" class="btn tab_item unique"
                   style="background-image: url(<?php echo get_template_directory_uri() ?>/img/all_u.png);">
             <p>Все услуги</p></a>
                    <?php endif ?>
                     <a data-fancybox="" data-src="#hidden-content" href="javascript:;" class="btn tab_item"
                        style="background-image: url('<?php echo $value['img']['url'] ?> ');">
                         <p><?php echo $value['txt'] ?></p>
                     </a>
                <?php endforeach ?>
      </span>
                </div>


            </section>


        </div>
    </div>
</div>



<div class="container contacts" id="n_7">
    <div class="row">
        <div class="col-md-12 no-gutter">
            <h3><?php echo get_field('sogt_descr_contacts'); ?></h3>
            <section id="generic-tabss">

            <ul id="tabs">
        <?php if( have_rows('sity', 53) ):
                $check = 0;
                $id = 1;
                  while ( have_rows('sity', 53) ) : the_row();
                $cls =  $check ? '' : 'active';
            ?>
             <li class="<?php echo $cls; ?>">
                 <a href="#tab_<?php echo $id; ?>"> <?php the_sub_field('sity_name'); ?></a>
            </li>
            <?php
                $check = 1;
                  $id+=1;
                     endwhile;
                  endif;
             ?>
            </ul>
        <?php if( have_rows('sity', 53) ):
                $id = 1;
                  while ( have_rows('sity', 53) ) : the_row();?>
             
                <div id="tab_<?php echo $id; ?>" class="tab-content">
                    <div class="hole_block">
                        <div class="left_part">
                            <p class="adress" style="background-image: url(<?php echo get_template_directory_uri(); ?>/img/map_2.png);">
                                <strong>Адрес: </strong>
                              <?php the_sub_field('office_name'); ?>
                            </p>
                            <p class="adress" style="background-image: url(<?php echo get_template_directory_uri(); ?>/img/tel_2.png);">
                                <strong>Телефон: </strong>

                                <a href="<?php the_sub_field('number'); ?>"><?php the_sub_field('number'); ?></a>
                            </p>
                            <p class="adress" style="background-image: url(<?php echo get_template_directory_uri(); ?>/img/cal_2.png);">
                                <strong>График работы: </strong>
                               <?php echo get_field('work_time', 53); ?> <br>
                                Сб-Вс: По записи
                            </p>
                            <a data-fancybox data-src="#hidden-content" href="javascript:;" class="btn">оставить
                                заявку</a>
                        </div>
                        <div id="map">
                            <a data-fancybox data-src="#hidden-content-map" href="javascript:;"><img
                                    src=" <?php the_sub_field('map_full'); ?>" alt=""></a>
                        </div>
                    </div>
                </div>
            <?php
                  $id+=1;
                     endwhile;
                  endif;
             ?>
            </section>
        </div>
    </div>
</div>

<?php get_footer(); ?>
