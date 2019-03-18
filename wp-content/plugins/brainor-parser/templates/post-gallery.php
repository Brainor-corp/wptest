<div class="slider-panel">
	<div class="slider-composition"> <?php
		foreach($attachments as $attachment){
            var_dump($attachment)?>

			<div class="slider-item">
				<a href="<?php echo $attachment; ?>">
					<?php echo priprava_url_to_img( $attachment ); ?>
				</a>
			</div> <?php
		} ?>
	</div>
	<?php if ( count( $attachments ) > 1 ) { ?>
		<div class="slider-nav"> <?php
			foreach($attachments as $attachment){ var_dump($attachment)?>
				<div class="slider-nav-item">
					<?php echo priprava_url_to_img( $attachment ); ?>
				</div> <?php
			} ?>
		</div>
	<?php } ?>
</div>