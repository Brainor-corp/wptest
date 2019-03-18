<?php

// Добавляем метабокс с галереей
add_action( 'add_meta_boxes', 'priprava_custom_post_gallery_metabox' ); 
function priprava_custom_post_gallery_metabox() {
	add_meta_box( 'custom_post_gallery', 'Галерея изображений', 'priprava_custom_post_gallery', 'post', 'normal', 'high' ); 
}

// Глобальные поля
global $multiupload_fields;
$multiupload_fields = array(
	array(
		'label' => 'Галерея',
		'desc'  => 'Загрузите нужные изображения',
		'id'    => 'multiupload',
		'type'  => 'multiupload'
	)
);

//Работа с галереей в админке
function priprava_custom_post_gallery( $post ) {
	global $multiupload_fields; ?>
	<input type="hidden" name="custom_meta_box_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
	<table class="form-table"><?php
		priprava_gen_custom_image_row( 'multiupload', 'for_clone' );
		foreach ($multiupload_fields as $field) {
			$meta = get_post_meta( $post->ID, $field['id'], true ); ?>
			<tr>
				<!--<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>-->
				<td><?php
					switch($field['type']) {
						case 'multiupload':?>
							<a class="repeatable-add button" href="#">Добавить изображение</a>
							<ul id="<?php echo $field['id']; ?>-repeatable" class="custom_repeatable"><?php
								$i = 0;
								if ($meta) {
									foreach ( $meta as $row ) {
										if ( empty( $row ) ) {
											continue;
										}
										priprava_gen_custom_image_row( $field['id'], '', $row );
										$i++;
									}
								} else {
									// priprava_gen_custom_image_row( $field['id'], '' );
								} ?>
							</ul>
							<div class="clear"></div>
							<div class="description"><?php echo $field['desc']; ?></div><?php
						break;
					} ?>
				</td>
			</tr><?php 
		} ?>
	</table><?php
}

// Сохраняем поля
function priprava_save_metaimage_meta_box($post_id) {
	global $multiupload_fields;
 
	// проверяем наш проверочный код
	if ( ! isset( $_POST['custom_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['custom_meta_box_nonce'], basename(__FILE__) ) ) {
		return $post_id;
	}

	// Проверяем авто-сохранение
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	// Проверяем права доступа  
	if ('image_meta_box_book' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}

	foreach ( $multiupload_fields as $field ) {
		$old = get_post_meta( $post_id, $field['id'], true );
		$image_meta_box = $_POST[$field['id']];

		if ( $field['type'] == 'multiupload' ) {
			$image_meta_box = array_values( $image_meta_box );
			$image_meta_box = array_diff( $image_meta_box, array('') );
		}

		// Если данные новые, то обновляем
		if ( ! empty( $image_meta_box ) && $image_meta_box != $old ) {
			update_post_meta( $post_id, $field['id'], $image_meta_box );
		}

		if ( empty( $image_meta_box ) ) {
			delete_post_meta( $post_id, $field['id'] );
		}
	}
}
add_action('save_post', 'priprava_save_metaimage_meta_box');

// Меняем надпись на кнопке в мультимедиа библиотеке
function priprava_options_setup() {
	global $pagenow;

	if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
		add_filter( 'gettext', 'replace_thickbox_text'  , 1, 3 );
	}
}
add_action( 'admin_init', 'priprava_options_setup' );

function replace_thickbox_text( $translated_text, $text, $domain ) {
	if ( 'Insert into Post' == $text ) {
		$referer = strpos( wp_get_referer(), 'priprava_gallery' );
		if ( $referer != '' ) {
			return 'Вставить в галерею';
		}
	}
	return $translated_text;
}

/**
 * Возвращает массив с url изображений галереи записи
 * @param $post_id
 * @return array
 */
function priprava_get_custom_gallery( $post_id ) {
	$return = array();
	$custom_gallery = get_post_meta( $post_id, 'multiupload', true );
	if ( ! empty( $custom_gallery ) ) {
		$custom_gallery = array_map( 'priprava_replace_attachment_thumbnail_url', $custom_gallery );
		foreach ($custom_gallery as $key => $value) {
			if ( ! empty( $value ) ) {
				array_push( $return,  $value );
			}
		}
	}
	return $return;
}

// Строка с полями изображения
function priprava_gen_custom_image_row( $input_name, $class, $src = '' ) { ?>
	<li class="added-image-block <?php echo $class; ?>">
		<img class="custom_preview_image sort hndle" <?php echo ( ! empty( $src ) ) ? "src='{$src}'" : '' ; ?> />
			<div class="added-image-block-remove"></div>
		<div class="added-image-block-section">
			<input name="<?php echo $input_name; ?>[]" type="hidden" class="custom_upload_image" value="<?php echo $src; ?>" />
		</div>
	</li><?php
}