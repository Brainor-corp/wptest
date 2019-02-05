<footer>
    <div class="container-fluid fon_footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12 no-gutter">
                    <div class="hole_footer">
                        <?php $logo = get_field('logo_group', 'option'); ?>
                        <a href="<?= get_home_url() ?>/" class="logo"><img src="<?php echo $logo['logo_footer']['url']; ?>" alt=""></a>
                        <div class="three_adresses">
           
            <?php if( have_rows('sity', 53) ):
                $check = 0;
                  while ( have_rows('sity', 53) ) : the_row();
                $cls =  $check ? '' : 'not_hidden';
            ?>
        <div class="one_adress <?php echo $cls; ?> ">
         <p><?php the_sub_field('sity_name'); ?></p>
         <a href="<?php the_sub_field('number'); ?>"><?php the_sub_field('number'); ?></a>
         <a data-img="<?php the_sub_field('map'); ?>" class="no"><?php the_sub_field('office_name'); ?></a>
        </div>

  <?php

  $check = 1;
          endwhile;
      endif;

 ?>
                        </div>
                        <div class="button_footer">
                            <span><?php echo get_field('work_time', 'option'); ?></span>
                            <a data-fancybox data-src="#hidden-content" href="javascript:;">Перезвоните мне</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Здесь пишем код -->

<div class="hidden"></div>

<div class="loader">
    <div class="loader_inner"></div>
</div>
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter49336915 = new Ya.Metrika2({
                    id:49336915,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/tag.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks2");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/49336915" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<!--[if lt IE 9]>
<script src="libs/html5shiv/es5-shim.min.js"></script>
<script src="libs/html5shiv/html5shiv.min.js"></script>
<script src="libs/html5shiv/html5shiv-printshiv.min.js"></script>
<script src="libs/respond/respond.min.js"></script>
<![endif]-->

<script src="<?= get_template_directory_uri() ?>/libs/jquery/jquery-1.11.2.min.js"></script>
<script src="<?= get_template_directory_uri() ?>/libs/waypoints/waypoints.min.js"></script>
<script src="<?= get_template_directory_uri() ?>/libs/animate/animate-css.js"></script>
<script src="<?= get_template_directory_uri() ?>/libs/plugins-scroll/plugins-scroll.js"></script>
<script src="<?= get_template_directory_uri() ?>/js/jquery.fancybox.min.js"></script>
<script src="<?= get_template_directory_uri() ?>/js/inputmask/jquery.mask.min.js"></script>
<script src="<?= get_template_directory_uri() ?>/libs/slick/slick.min.js"></script>
<script src="<?= get_template_directory_uri() ?>/libs/Magnific-Popup-master/dist/jquery.magnific-popup.min.js"></script>

<script src="<?= get_template_directory_uri() ?>/js/common.js"></script>
<script src="<?= get_template_directory_uri() ?>/js/validator.js"></script>

</body>
</html>