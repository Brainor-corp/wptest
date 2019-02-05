<div class="sidebar">
				<div class="nav_sidebar"></div>
				<div class="closer"></div>
				<div class="inner_content">
					<?php include_once 'inc/blocks/breadcrumps.php'; ?>
					<?php if (get_the_ID() == 38 || get_the_ID() == 425){
						include_once 'inc/blocks/complited_works.php';
					}else if(get_the_ID() == 51){
							echo '<ul class="mini_nav_sidebar">';
							if(have_posts()) : query_posts("showposts=8&cat=5");
							while(have_posts()) : the_post();
						?>
							<li><a href="<?php the_permalink(); ?> "><?php the_title(); ?></a></li>
						<?php
							endwhile;
							endif;
							wp_reset_query();
						echo "</ul>";
					}else{
						include_once 'inc/blocks/sidebar_change_part.php';
					}; ?>

					<div class="block_vk">
							<div id="vk_groups"></div>
							<script type="text/javascript">
							VK.Widgets.Group("vk_groups", {mode: 0, width: "234", no_cover: 1, height: "444"}, 36293346);
							</script>
							<!-- <img src="<?php echo get_template_directory_uri(); ?>/img/Untitled-1.jpg"> -->
					</div>

						<ul class="block_soc">

							<li class="youtube"><a href="<?php echo get_field('soc_seti', 'option')['youtube']; ?>"></a></li>
							<li class="facebook"><a href="<?php echo get_field('soc_seti', 'option')['facebook']; ?>"></a></li>
							<li class="insta"><a href="<?php echo get_field('soc_seti', 'option')['facebook']; ?>"></a></li>
						</ul>
						<div class="repair_any_car">
							<h3>Ремонтируем любые марки <br>автомобилей</h3>
						<?php
						$count = 0;
							if(have_posts()) : query_posts("showposts=8&cat=6");
							while(have_posts()) : the_post();
						?>
							<div class="item <?php echo ($count+1)%2==0? 'col-2' : ''; ?> "><img class="t-<?php echo $count+1; ?>" src="<?php echo get_the_post_thumbnail_url(); ?>"></div>
						<?php
						$count+=1;
							endwhile;
							endif;
							wp_reset_query();
						?>					

				
						</div>
						<div class="services_is_sertificated" onclick="location.href='/sertifikaty/';" >
							<h3>услуги<br>сертифицированны</h3>
						</div>
				</div>
	</div>