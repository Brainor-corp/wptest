 <?php wp_nav_menu( 
                                array( 
                                'container'=> false,
                                'menu'=> 'сайдбар',
                                'menu_id' => 'sidebar_menu', // id для ul
                                'items_wrap' => '<ul id="%1$s" class="mini_nav_sidebar">%3$s</ul>',
                                'menu_class' => 'mini_nav_sidebar',)
                                );
                                ?>
