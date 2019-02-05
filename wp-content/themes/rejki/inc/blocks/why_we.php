<div class="why_we">
	<h3><?php echo get_field('why_we_title', 'option'); ?></h3>
	<?php
		$masP = get_field('reason', 'option');

	 foreach ($masP as $key => $value) {
echo <<<BLOCK
	<div class="item">
		<div class="img" style="background-image: url({$value['img_icon']})"></div>
		<p class="simple-text">{$value['txt_icon']}</p>
	</div>
BLOCK;
	} ?>

</div>