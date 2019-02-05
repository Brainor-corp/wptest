<?php 
/** 
 * Template Name: Contacts
 */
get_header(); ?>

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