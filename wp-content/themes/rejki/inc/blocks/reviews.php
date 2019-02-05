
<?php
$ids = get_the_ID();

 if ($ids == 5||$ids == 51) {
    $limit = 3;
  echo "<div class='otz'>";

}else{
     $limit = -1;
echo <<<BLOCK
<div class="rew_block">
    <h3>отзывы наших клиентов</h3>
<p class="simple-text">Наши клиенты не вспоминают о ремонте рулевых реек более 10 лет</p>
    <div class="otz slider_rew">
BLOCK;
} ?>
    <?php 
$args = array(
    'posts_per_page'=> $limit,
    'cat' => '7',
    'order' => 'DESC',
);
$lastposts = get_posts( $args );
foreach( $lastposts as $post ){ setup_postdata($post);

?>
                <div class="hole_ot">
                    <div class="ot">
                        <img src="<?php the_field('photo'); ?>" alt="">
                        <div class="text">
                            <p><?php echo get_the_title(); ?></p>
                            <a  href=" <?php the_field('vk_link'); ?> " target='blank' >Вконтакте</a>
                        </div>

                    </div>
                    <p class="open"><?php the_field('rew_txt'); ?></p><span class="cl">Читать отзыв полностью</span>
                </div>

    <?php 
}
wp_reset_postdata();
?>
<?php if ($ids != 5||$ids !=51) {
echo "</div></div>";
}else{
echo "</div>";
}; ?>
