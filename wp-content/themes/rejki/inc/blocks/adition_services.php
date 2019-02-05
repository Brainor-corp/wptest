<div class="adition_services">
		<h2><?php echo get_field('addition_title'); ?></h2>
		<span><?php echo get_field('addition_text'); ?></span>
		<ul>
			<?php
			$add_serv = get_field('addition_links');
			foreach ($add_serv as $key => $value): ?>
				<li><a href="<?php echo get_permalink($value['link']) ?>"><?php echo get_the_title($value['link']); ?></a></li>
			<?php endforeach ?>
		</ul>
	</div>