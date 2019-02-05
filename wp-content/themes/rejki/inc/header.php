<header>
    <div style="display: none;" id="hidden-content">
        <form action="<?php echo get_template_directory_uri(); ?>/mail.php" method="post">
            <p>оставьте заявку<br>
                и мы вам перезвоним</p>
            <span>Уже сегодня Вы сможете забыть
о проблеме с рулевой рейкой.</span>
            <input type="text" class="hidden" value="Санкт-Петербург" name="city">
            <input type="text" name="name" placeholder="Ваше имя">
            <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" class="phone">
            <button class="send">перезвоните мне</button>
        </form>
    </div>

    <div style="display: none;" id="hidden-content-map">
        <img src="<?php echo get_field('sity', 53)[0]['map']; ?>" alt="">
    </div>
    <?php $logo = get_field('logo_group', 'option'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12 no-gutter">
                <div class="hole_logo">
                    <div class="f_block">
                        <a href="<?php echo get_home_url(); ?>/"><img src="<?php echo $logo['logo']['url']; ?>" alt="">

                            <p><?php echo $logo['logo_txt']; ?></p></a>

                        <p><?php echo get_field("small_description", "option"); ?></p>
                    </div>
                    <div class="s_block">
                        <div class="top">
                            <p>Выберите город: </p>
                            <ul class="list-unstyled v1">

                                <li class="init"><?php echo get_field('sity', 53)[0]['sity_name']; ?></li>

                                 <?php if( have_rows('sity', 53) ):
                                        while ( have_rows('sity', 53) ) : the_row();
                                    ?>
                                        <li data-img="<?php the_sub_field('map'); ?>" 
                                            data-value="<?php the_sub_field('office_name'); ?>" 
                                            data-phone="<?php the_sub_field('number'); ?>" 
                                            data-utm-key="<?php the_sub_field('utm_key'); ?>"><?php the_sub_field('sity_name'); ?></li>
                                    <?php
                                            endwhile;
                                        endif;
                                   ?>
                            </ul>
                        </div>
                        <p class="gorod"><span><?php echo get_field('sity', 53)[0]['office_name']; ?></span><a data-fancybox data-src="#hidden-content-map" href="javascript:;">Показать на карте</a></p>
                    </div>
                    <div class="third_block">
                        <div class="text">
                            <a href="tel:<?php echo get_field('sity', 53)[0]['number']; ?>"><?php echo get_field('sity', 53)[0]['number']; ?></a>
                            <p><?php echo get_field('work_time', 53); ?></p>
                        </div>
                        <a data-fancybox data-src="#hidden-content" href="javascript:;" class="btn" data-city="Санкт-Петербург">Перезвоните мне</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid fon_menu">

        
        <div class="container">
            <div class="row">
                <div class="col-md-12 no-gutter">
                    <div class="mob_menu">
                        <?php wp_nav_menu( 
                                array( 
                                'container'=> false,
                                'menu_id' => 'top-nav-ul-menu_mob', // id для ul
                                'items_wrap' => '<ul id="%1$s" class="header-nav__links %2$s">%3$s</ul>',
                                'menu_class' => 'top-menu_mob',)
                                );
                                ?>
                        <div id="nav-icon4">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        
                    </div>
                    <div class="menu">
                        <?php wp_nav_menu( 
                                array( 
                                'container'=> false,
                                'menu_id' => 'top-nav-ul-menu', // id для ul
                                'items_wrap' => '<ul id="%1$s" class="header-nav__links %2$s">%3$s</ul>',
                                'menu_class' => 'top-menu',)
                                );
                                ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


</header>