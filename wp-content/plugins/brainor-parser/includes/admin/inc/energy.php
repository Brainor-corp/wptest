<?php
/**
 * Энергетическая ценность
 */

add_action('add_meta_boxes', 'priprava_energy_metabox'); 
function priprava_energy_metabox() {
	add_meta_box( 'energy', 'Энергетическая ценность, количество порций и время приготовления', 'priprava_energy', 'post', 'normal', 'high' ); 
}

function priprava_energy( $post ) {
	$energy_prep_time = get_post_meta( $post->ID, 'energy_prep_time', true );
	$energy_cook_time = get_post_meta( $post->ID, 'energy_cook_time', true );
	$energy_ready_in_time = get_post_meta( $post->ID, 'energy_ready_in_time', true );
	$energy_ccal = get_post_meta( $post->ID, 'energy_ccal', true );
	$energy_portion = get_post_meta( $post->ID, 'energy_portion', true );
	$energy_protein = get_post_meta( $post->ID, 'energy_protein', true );
	$energy_fat = get_post_meta( $post->ID, 'energy_fat', true );
	$energy_carbohydrates = get_post_meta( $post->ID, 'energy_carbohydrates', true ); ?>
	<div class="energy_block">
		<label>Время подготовки<br><input type="text" name="energy_prep_time" placeholder="Время, мин" value="<?php echo $energy_prep_time; ?>"></label>
		<label>Время приготовления<br><input type="text" name="energy_cook_time" placeholder="Время, мин" value="<?php echo $energy_cook_time; ?>"></label>
		<label>Время общее<br><input type="text" name="energy_ready_in_time" placeholder="Время, мин" value="<?php echo $energy_ready_in_time; ?>"></label>
		<label>Энерг. ценность<br><input type="text" name="energy_ccal" placeholder="Энерг. ценность, ккал" value="<?php echo $energy_ccal; ?>"></label>
		<label>Количество порций<br><input type="number" name="energy_portion" placeholder="Количество порций" value="<?php echo $energy_portion; ?>"></label>
	</div>
	<div class="energy_block">
		<label>Белки<br><input type="number" name="energy_protein" placeholder="Белки" value="<?php echo $energy_protein; ?>" step="0.01"></label>
		<label>Жиры<br><input type="number" name="energy_fat" placeholder="Жиры" value="<?php echo $energy_fat; ?>" step="0.01"></label>
		<label>Углеводы<br><input type="number" name="energy_carbohydrates" placeholder="Углеводы" value="<?php echo $energy_carbohydrates; ?>" step="0.01"></label>
	</div>
	<?php
}

add_action( 'save_post', 'priprava_save_energy' );
function priprava_save_energy( $post_id ) {
	if ( ! isset( $_POST['energy_time'] ) || ! isset($_POST['energy_ccal']) || ! isset($_POST['energy_portion']) ) {
		return;
	}

	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return;
	}

	if( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$energy_time = sanitize_text_field( $_POST['energy_time'] );
	$energy_ccal = sanitize_text_field( $_POST['energy_ccal'] );
	$energy_portion = sanitize_text_field( $_POST['energy_portion'] );
	$energy_protein = sanitize_text_field( $_POST['energy_protein'] );
	$energy_fat = sanitize_text_field( $_POST['energy_fat'] );
	$energy_carbohydrates = sanitize_text_field( $_POST['energy_carbohydrates'] );

	update_post_meta( $post_id, 'energy_time', $energy_time );
	update_post_meta( $post_id, 'energy_ccal', $energy_ccal );
	update_post_meta( $post_id, 'energy_portion', $energy_portion );
	update_post_meta( $post_id, 'energy_protein', $energy_protein );
	update_post_meta( $post_id, 'energy_fat', $energy_fat );
	update_post_meta( $post_id, 'energy_carbohydrates', $energy_carbohydrates );
}

function priprava_get_energy_widjet( $post_id ) {
	$energy_portion = priprava_get_energy_meta( $post_id, 'energy_portion' );
	if ( empty( $energy_portion ) || $energy_portion < 1 ) {
		$energy_portion = 1;
	}
	$energy_prep_time = priprava_get_energy_meta( $post_id, 'energy_prep_time' );
	$energy_cook_time = priprava_get_energy_meta( $post_id, 'energy_cook_time' );
	$energy_ready_in_time= priprava_get_energy_meta( $post_id, 'energy_ready_in_time' );
	$energy_ccal = priprava_get_energy_meta( $post_id, 'energy_ccal' );
	$energy_protein = priprava_get_energy_meta( $post_id, 'energy_protein' );
	$portions = priprava_get_energy_meta( $post_id, 'energy_portion' );
	$energy_fat = priprava_get_energy_meta( $post_id, 'energy_fat' );
	$energy_carbohydrates = priprava_get_energy_meta( $post_id, 'energy_carbohydrates' );

	$view = '';

	if ( ! empty( $energy_prep_time ) ) {
		ob_start(); ?>
		<div class="energy-item energy-value-time inner" data-value="<?php echo priprava_get_energy_meta( $post_id, 'energy_prep_time', $energy_portion ); ?>">Время подготовки: <span><?php echo $energy_prep_time; ?></span></div><?php
		$view .= ob_get_clean();
	}
    if ( ! empty( $energy_cook_time ) ) {
        ob_start(); ?>
        <div class="energy-item energy-value-time inner" data-value="<?php echo priprava_get_energy_meta( $post_id, 'energy_cook_time', $energy_portion ); ?>">Время приготовления: <span><?php echo $energy_cook_time; ?></span></div><?php
        $view .= ob_get_clean();
    }
    if ( ! empty( $energy_ready_in_time ) ) {
        ob_start(); ?>
        <div class="energy-item energy-value-time inner" data-value="<?php echo priprava_get_energy_meta( $post_id, 'energy_ready_in_time', $energy_portion ); ?>">Время общее: <span><?php echo $energy_ready_in_time; ?></span></div><?php
        $view .= ob_get_clean();
    }

	if ( ! empty( $energy_ccal ) ) {
		ob_start(); ?>
		<div class="energy-item energy-value-kkal inner">
			Энергетическая ценность: <span data-value="<?php echo priprava_get_energy_meta( $post_id, 'energy_ccal', $energy_portion ); ?>"><?php echo $energy_ccal; ?></span><?php
			if ( ! empty( $energy_protein ) || ! empty( $energy_fat ) || ! empty( $energy_carbohydrates ) ) { ?>
				<div class="energy-value-count-portion-bzhy"><?
					if ( ! empty( $energy_protein ) ) { ?>
						<div class="energy-item energy-protein">Белки, г: <span data-value="<?php echo priprava_get_energy_meta( $post_id, 'energy_protein', $energy_portion ); ?>"><?php echo $energy_protein; ?></span></div><?php
					}

					if ( ! empty( $energy_fat ) ) { ?>
						<div class="energy-item energy-fat">Жиры, г: <span data-value="<?php echo priprava_get_energy_meta( $post_id, 'energy_fat', $energy_portion ); ?>"><?php echo $energy_fat; ?></span></div><?php
					}

					if ( ! empty( $energy_carbohydrates ) ) { ?>
						<div class="energy-item energy-carbohydrates">Углеводы, г: <span data-value="<?php echo priprava_get_energy_meta( $post_id, 'energy_carbohydrates', $energy_portion ); ?>"><?php echo $energy_carbohydrates; ?></span></div><?php
					} ?>
					<div class="close-button"></div>
				</div><?php
			} ?>
			</div><?php
		$view .= ob_get_clean();
	}

	if ( ! empty( $view ) ) { ?>
		<div class="energy-value outer">
			<?php echo $view; ?>
			<div class="energy-value-portion inner">
				<div class="energy-item energy-value-portion-name">Порций: <span><?php echo $portions ?></span></div>
			</div>
		</div><?php
	}
}

function priprava_get_energy_meta( $post_id, $meta_name, $energy_portion = 1 ) {
	$energy_meta = get_post_meta( $post_id, $meta_name, true );
	$energy_meta = ( ! empty( $energy_meta ) ) ? $energy_meta : 0;

	if ( $energy_portion > 1 ) {
		$energy_meta = round( floatval( $energy_meta / $energy_portion ), 2 );
	}

	return $energy_meta;
}