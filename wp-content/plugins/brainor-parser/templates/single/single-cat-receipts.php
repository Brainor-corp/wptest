
<?php get_header() ;?>

<div id="main_content" class="post_page">


    <div id="main">

        <div id="breadcrumbs">
            <div class="breadcrumbs_block">
                <?php if (function_exists('dimox_breadcrumbs')) {dimox_breadcrumbs();} ?>
            </div>
        </div>

        <div id="article">
            <div class="content_text receipt-content">
                <div class="title firm-title">
                    <?php if($tssuat_settings['articles']){ ?>
                        <div class="post_rating">Текущий рейтинг статьи: <?=comment_percent($post->ID)?></div>
                    <?php } ?>
                    <div class="text">Статьи</div>
                </div>

                <index>
                    <!-- google_ad_section_start -->
                    <h1><?=the_title()?></h1>

                    <!-- Yandex.RTB R-A-134725-1 -->
                    <div id="yandex_rtb_R-A-134725-1"></div>
                    <script type="text/javascript">
                        (function(w, d, n, s, t) {
                            w[n] = w[n] || [];
                            w[n].push(function() {
                                Ya.Context.AdvManager.render({
                                    blockId: "R-A-134725-1",
                                    renderTo: "yandex_rtb_R-A-134725-1",
                                    async: true
                                });
                            });
                            t = d.getElementsByTagName("script")[0];
                            s = d.createElement("script");
                            s.type = "text/javascript";
                            s.src = "//an.yandex.ru/system/context.js";
                            s.async = true;
                            t.parentNode.insertBefore(s, t);
                        })(this, this.document, "yandexContextAsyncCallbacks");
                    </script>

                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                    <div class="receipt-thumb"><?php the_post_thumbnail(); ?></div>

                    <div class="energy-wrapper">
                        <span class="wrapper-title">Особенности:</span>
                        <?php priprava_get_energy_widjet( $post->ID ); ?>
                    </div>
                    <div class="composition-wrapper">
                        <span class="wrapper-title">Ингридиенты:</span>
                        <?php echo priprava_get_composition_widjet( $post->ID ); ?>
                    </div>
                    <div style="clear: both"></div>

                    <?php echo priprava_get_post_excerpt( $post->ID ); ?>
                    <br>
                    <span class="wrapper-title">Пошаговое описание:</span>
                    <?php the_content(__('')); ?>

                    <?php echo do_shortcode(priprava_get_post_gallery( $post->ID )); ?>

                    <br>

                    <?php if($tssuat_settings['articles']){ comments_template(); }?>

                    <br>

                    <?php if( $out = kama_recent_comments(6, 80, 0, 0, 50) ){ ?>

                        <div id="last_comments">
                            <div class="title-side ico_comments">Сейчас обсуждают:</div>
                            <div class="blm">
                                <ul class="recom">
                                    <?= $out ?>
                                </ul>
                                <div class="show_all_comments"><a href="/recent-comments" onclick="yaCounter20585053.reachGoal('all_recent_comments'); return true;">Комментарии »</a></div>
                            </div>
                        </div>
                    <?php } ?>
                    <br>


                    <!-- google_ad_section_end -->
                </index>
                <?php endwhile; else: ?>
                    <p>Нет новостей!</p>
                <?php endif; ?>
                <?php
                if (function_exists('wp_pagenavi')) {
                    wp_pagenavi();
                }
                ?>
                <div class="bottom_post_buttons">
                    <a class="addFavorite" onclick="Bookmark()">Добавить в избранное</a>
                    <a class="printButton" onclick="print()">Версия для печати</a>
                </div>
            </div>
        </div>
        <?php bottom_post_block(); ?>


        <div class="similar_posts inside">
            <h3>Статьи по теме</h3>
            <div class="posts">
                <?php
                $categories = get_the_category($post->ID);
                if ($categories) {
                    $category_ids = array();
                    foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
                    $args=array(
                        'category__in' => $category_ids,
                        'post__not_in' => array($post->ID),
                        'showposts'=>3,
                        'caller_get_posts'=>1);
                    $my_query = new wp_query($args);
                    if( $my_query->have_posts() ) {
                        while ($my_query->have_posts()) {
                            $my_query->the_post();


                            ?>
                            <div class="similar_post">
                                <a href="<?php the_permalink()?>"><div class="post_image" style="background-image: url(<?php echo catch_that_image(); ?>);"></div></a>
                                <div class="post_body">
                                    <a href="<?php the_permalink()?>"><div class="post_date"><?php echo rdate('d M Y', strtotime(($post->post_date))); ?></div></a>
                                    <!--div class="post_author">Автор: <a href="/author/<?= get_userdata($post->post_author)->nickname ?>"><?php the_author(); ?></a></div-->
                                    <a href="<?php the_permalink()?>" class="title"><?php echo $post->post_title; ?></a>
                                    <?php if($tssuat_settings['articles']){ ?>
                                        <div class="new_posts_comments_count"><a href="<?php the_permalink() ?>">комментарии</a> (<?php comments_number('0', '1', '%') ?>)</div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    wp_reset_query();
                }
                ?>
            </div>
            <div class="all_posts_link"><a href="<?php echo get_term_link($categories[0]->term_id, 'category'); ?>">Все материалы по теме</a></div>
        </div>
        <br/>







    </div>

    <?php get_sidebar(); ?>
    <div class="clear"></div>
</div>

<?php get_footer(); ?>