<?php
/**
 * Состав
 */

// Создаем таблицы в базе
add_action('init', 'priprava_install_compositions_table');
function priprava_install_compositions_table() {
	global $wpdb; 
	$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}compositions (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(255) NOT NULL,
		`status` varchar(255) NOT NULL DEFAULT 'publish',
		PRIMARY KEY (`id`)
	)	ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

	$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}post_compositions (
		`post_id` int(11) NOT NULL,
		`composition_id` int(11) NOT NULL,
		`notice` varchar(255) DEFAULT '',
		`count` varchar(255) NOT NULL,
		`units` varchar(255) NOT NULL,
		`main` tinyint(1) NOT NULL
	)	ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
}

add_action('add_meta_boxes', 'priprava_compositions_meta_init'); 
function priprava_compositions_meta_init() {
	add_meta_box('compositions', 'Состав', 'priprava_compositions_meta_content', 'post', 'side', 'default'); 
}

function priprava_compositions_meta_content( $post ) {
	$post_composition = get_post_meta( $post->ID, 'post_composition', true ); ?>
	<div class="compositions_tooltip">
		<span>?</span>
		<div class="compositions_tooltip_modal">
			Вставьте список продуктов, каждый продукт с новой строки.<br>
			Формат: <span class="red">«[продукт]:[количество] [ед. изм.]»</span><br>
			<span class="underline">Пример:</span><br>
			<span class="gray">Крабовые палочки: 240г.</span><br>
			<span class="gray">Соль: (по вкусу)</span><br>
			Затем нажмите кнопку <span class="red">«Парсить состав»</span>
		</div>
	</div>
	<div class="loading_block"></div><textarea placeholder=
"Пример:
Крабовые палочки: 240г.
Соль: (по вкусу)" name="post_composition" cols="60" rows="10" style="width: 100%; min-height: 100px;"><?php print_r($post_composition); ?></textarea>
	<div class="button button_parse">Парсить состав</div>
	<div class="ajax_response">
		<div class="parse_log"></div>
		<div id="composition_parsed"><?php
			if ( priprava_isset_post_compositions( $post->ID ) ) {
				$attr = array(
					'button_remove' => true
				);
				echo priprava_get_post_compositions_table( $post->ID, $attr );
			} else {
				// Для автоматического парсинга расскоментировать
				/*if ( ! empty( $post_composition ) ) {
					echo priprava_create_table_compositions( $post_composition ); ?>
					<div class="button button_save">Сохранить состав</div><?php
				}*/
			} ?>
		</div>
	</div><?php
}

/**
 * Возвращает состав рецепта в виде массива или таблицы, или списка
 * @param int $post_id - идентификатор рецета (записи)
 * @param array $attr - ( 'type' => 'тип возвращаемого', 'title' => 'Заголовок списка', 'class' => 'Класс списка' )
 */
function priprava_get_post_compositions( $post_id, $attr = array() ) {
	$post_composition = get_post_meta( $post_id, 'post_composition', true );

	if ( ! empty( $post_composition ) ) {
		if ( isset( $attr['type'] ) ) {
			if ( $attr['type'] == 'table' ) {
				return priprava_create_table_compositions( $post_composition );
			}

			if ( $attr['type'] == 'array' ) {
				// Если есть сохраненные, то возвращаем их или парсим
				if ( priprava_isset_post_compositions( $post_id ) ) {
					return priprava_get_saved_ingredients( $post_id );
				} else {
					return priprava_parse_compositions( $post_composition );
				}
			}

			if ( $attr['type'] == 'list' ) {
				if ( priprava_isset_post_compositions( $post_id ) ) {
					return priprava_get_list_compositions( priprava_get_saved_ingredients( $post_id ), $attr );
				} else {
					return priprava_get_list_compositions( $post_composition, $attr );
				}
			}
		}
	}
}

// Парсим состав
function priprava_parse_compositions( $compositions_string, $return_status = false ) {
	$compositions = '';

	$parse_status = array();

	$pattern_name  = '/^.+?(?=\:)/';
	$pattern_count = '/(?<=\:)[0-9\s\-\–\,\.]{0,}/';
	$pattern_units = '/(?<=\:)[0-9\s\-\–\,\.]{0,}(.*)/';

	$compositions_array = array();

	if ( ! empty( $compositions_string ) ) {
		$compositions = explode('<br />', nl2br( $compositions_string ) );
	}

	if ( is_array( $compositions ) && ! empty( $compositions ) ) {

		if( $return_status == 'status' ) {
			$parse_status['count'] = count( $compositions );
			$parse_status['recognized'] = 0;
		}

		foreach ($compositions as $key => $composition) {
			if ( ! empty( trim( $composition ) ) ) {
				preg_match($pattern_name, trim( $composition ), $match_name);
				preg_match($pattern_count, trim( $composition ), $match_count);
				preg_match($pattern_units, trim( $composition ), $match_units);

				if ( ! empty( $match_name ) ) {
					$names = explode( ' ', $match_name[0] );
					if ( count( $names ) > 1 ) {
						$compositions_array[$key]['name'] = mb_ucfirst( array_shift( $names ) );
						$compositions_array[$key]['notice'] = implode( ' ', $names );
					} else {
						$compositions_array[$key]['name'] = mb_ucfirst( trim( $match_name[0] ) );
						$compositions_array[$key]['notice'] = '';
					}
					$composition_count = ( ! empty( $match_count ) ) ? trim( $match_count[0] ) : '';
					$compositions_array[$key]['count'] = ( ! empty( $composition_count ) ) ? $composition_count : '';
					$compositions_array[$key]['units'] = ( ! empty( $match_units ) ) ? str_replace(';', '', trim( $match_units[1] ) ) : '';
					if ( $return_status == 'status' ) {
						$parse_status['recognized']++;
					}
				} elseif( $return_status == 'status' ) {
					$parse_status['error'][$key + 1] = $composition;
				}
			}

		}
	}

	if ( ! empty( $parse_status ) ) {
		return array( 'array' => $compositions_array, 'status' => $parse_status );
	}

	return $compositions_array;
}

// Таблица с составом
function priprava_create_table_compositions( $compositions_string, $attr = array( 'parse_status' => false ) ) {
	$compositions = priprava_parse_compositions( $compositions_string, $attr['parse_status'] );
	$return_parse_status = array();
	$composition_html = '';

	if ( is_array( $compositions ) && ! empty( $compositions ) ) {

		if ( isset( $compositions['status'] ) ) {
			$return_parse_status = $compositions['status'];
			$compositions = $compositions['array'];
		}

		if ( is_array( $compositions ) && ! empty( $compositions ) ) {
			$count_tooltip = '<div class="table_composition_tooltip_modal">Через тире, если нужен диапазон<div><span class="underline">Например</span>: <span class="gray">1-3</span></div></div>';
			$composition_html = "<table class='table'>";
			$composition_html .= "<tr><td>Название</td><td>Примечание</td><td class='table_composition_tooltip'>Количество{$count_tooltip}</td><td>Ед. измерения</td><td>Основной ингредиент</td><td></td></tr>";
			$composition_html .= '<tr class="template"><td><input type="text" class="ing_name"></td><td><input type="text" class="ing_notice"></td><td><input type="text" class="ing_count"></td><td><input type="text" class="ing_units"></td><td><label><input type="checkbox" class="ing_main"></label></td>';
			if ( isset( $attr['button_remove'] ) && $attr['button_remove'] === true ) {
				$composition_html .= '<td><div class="button button_remove">X</div></td>';
			}
			$composition_html .= '</tr>';

			foreach ($compositions as $key => $composition) {
				ob_start(); ?>
				<tr>
					<td>
						<input type='text' class="ing_name" value='<?php echo $composition['name'], ' ', $composition['notice']; ?>'>
					</td>
					<td>
						<input type='text' class="ing_notice">
					</td>
					<td>
						<input type='text' class="ing_count" value='<?php echo $composition['count']; ?>'>
					</td>
					<td>
						<input type='text' class="ing_units" value='<?php echo $composition['units']; ?>'>
					</td>
					<td>
						<label><input type='checkbox' class="ing_main"></label>
					</td>
					<?php if ( isset( $attr['button_remove'] ) && $attr['button_remove'] === true ) { ?>
						<td>
							<div class="button button_remove">X</div>
						</td>
					<?php } ?>
				</tr><?php
				$composition_html .= ob_get_clean();
			}
			$composition_html .= '</table><div class="button button_add_row">Добавить строку</div><div class="button button_save">Сохранить ингредиенты для этого рецепта</div>';
		}
	}

	if ( ! empty( $return_parse_status ) ) {
		return array( 'table' => $composition_html, 'status' => $return_parse_status );
	}

	return $composition_html;
}

// Список с составом
function priprava_get_list_compositions( $compositions_string, $attr = array() ) {

	if ( is_string( $compositions_string ) ) {
		$compositions = priprava_parse_compositions( $compositions_string );
	}

	if ( is_array( $compositions_string ) ) {
		$compositions = $compositions_string;
	}

	if ( is_array( $compositions ) && ! empty( $compositions ) ) {
		$title = ( isset( $attr['title'] ) ) ? $attr['title'] : 'Состав:';
		$class = ( isset( $attr['class'] ) ) ? " {$attr['class']}" : '';
		$composition_html = "<div class='composition{$class}'><div class='composition-title'>{$title}</div>";
		$composition_html .= '<ul class="composition-list">';

		foreach ($compositions as $key => $composition) {
			$composition_html .= "<li class='composition-list-item'>{$composition['name']} {$composition['notice']}</li>";

		}
		$composition_html .= '</ul></div>';
	}
	return $composition_html;
}

// Виджет с составом
function priprava_get_composition_widjet( $post_id ) {
	$compositions = priprava_get_post_compositions( $post_id, array( 'type' => 'array' ) );

	$energy_portion = get_post_meta( $post_id, 'energy_portion', true );

	$response = '';

	if ( is_array( $compositions ) && ! empty( $compositions ) ) {
		$response = '<div class="composition_widjet">';
//		$response .= '<div class="composition_fade">Скрыть</div>';
		$response .= '<div class="composition_widjet_list">';
		foreach ($compositions as $key => $item) {
			$response .= '<div class="composition_widjet_item">';
			$response .= priprava_generate_custom_composition_checkbox( $key, mb_ucfirst( $item['name'] ) . ' ' . $item['notice'], $item['count'], setEndPoint( $item['units'] ), $energy_portion );
			$response .= '</div>';
		}
		$response .= '</div></div>';
	}

	return $response;
}

function setEndPoint( $str ) {
	$str = trim( $str );

	if( empty( $str ) ) {
		return;
	}

	$substr = mb_substr( $str, -2, 2, 'UTF-8' );

	if( 'шт' === $substr ) {
		$str .= '.';
	}

	return $str;
}

// Кастомный чекбокс для состава
function priprava_generate_custom_composition_checkbox( $uniq_id, $name, $count, $units, $default_count = 1 ) {

	preg_match_all( '/[\d\.]+/', $count, $counts );

	if ( ! empty( $counts ) && is_array( $counts ) ) {
		foreach ($counts[0] as $key => $value) {
			$full_value = $value;
			$one_value = $full_value;
			if ( ! empty( $default_count ) ) {
				$default_count = intval( $default_count );
				if ( $default_count > 1 ) {
					$one_value = round( floatval( $full_value / $default_count ), 2 );
				}
			}
			$count = str_replace( $value, "<span data-value='{$one_value}'>{$full_value}</span>", $count );
		}
	}

	$uniq_id = "checkbox_composition_{$uniq_id}_" . uniqid();
	ob_start();
	?>
	<div class="composition-item">
        <div class="composition-item-name"><span><?php echo $name; ?>: </span></div>
        <div class="composition-item-value"><span><?php echo $count; ?> <?php echo $units; ?></span></div>
		<div class="clear"></div>
	</div>
	<?php
	return ob_get_clean();
}

/** 
 * Добавляем строку состава, если не существует
 * @return integer Всегда возвращает идентификатор ингредиента
 */
function priprava_add_composition( $composition_name ) {
	global $wpdb;

	if ( ! empty( $composition_name ) ) {
		$isset_composition = priprava_isset_composition( $composition_name );
		if( ! $isset_composition ) {
			$result = $wpdb->insert(
				"{$wpdb->prefix}compositions",
				array( 'name' => $composition_name ),
				array( '%s' )
			);
			if ( $result ) {
				return $wpdb->insert_id;
			}
		}
		return $isset_composition;
	}
}

/**
 * Проверяем существование ингредиента
 * @param  string $composition_name - название ингредиента
 * @return integer/boolean - идентификатор ингредиента или false
 */
function priprava_isset_composition( $composition_name ) {
	global $wpdb;

	if ( ! empty( $composition_name ) ) {
		$composition_id = $wpdb->get_var( $wpdb->prepare( "
			SELECT id FROM {$wpdb->prefix}compositions WHERE name = %s AND status = 'publish'
		", $composition_name ) );
		if ( ! empty( $composition_id ) ) {
			return $composition_id;
		}
	}
	return false;
}

/**
 * Возвращаем таблицу с ингредиентами
 * @return html
 */
function priprava_admin_table_load() {
	$ret = new stdClass;
	ob_start();
	$result = priprava_create_table_compositions( $_POST['composition'], array( 'parse_status' => true, 'button_remove' => true ) );
	$error_string = '';
	if ( isset( $result['status']['error'] ) ) {
		$error_count = count( $result['status']['error'] );
		$error_string = implode('; ', $result['status']['error'] );
		$row = '';
		$current = 0;
		foreach ($result['status']['error'] as $error_key => $error) {
			$row .= ( $current ) ? '<hr>' : '';
			$current++;
			$row .= '<div>Ошибка парсинга: «' . $error . '» в строке ' . $error_key . '; Строка должна быть в формате «[продукт]:[количество] [ед. изм.]» - пример: «Крабовые палочки: 240г»;</div>';
		}
		$error_string = '<div class="error"><div class="bold">' . $error_count . ' ' . declension_words( $error_count, array( 'строка', 'строки', 'строк' ) ) . ' ' . declension_words( $error_count, array( 'вызвала', 'вызвали', 'вызвали' ) ) . ' ошибку:</div><br>' . $row . '</div>';
	}
	?>
		<div class="parse_log">
			<div><div class="updated"><span class="bold">Найдено:</span> <?php echo $result['status']['count']; ?> <?php echo declension_words( $result['status']['count'], array( 'строка', 'строки', 'строк' ) ) ?>.
			<span class="bold">Распознано:</span> <?php echo $result['status']['recognized']; ?> <?php echo declension_words( $result['status']['recognized'], array( 'строка', 'строки', 'строк' ) ) ?>.</div> <?php echo $error_string; ?></div>
		</div>
		<div id="composition_parsed">
			<?php echo $result['table']; ?>
		</div><?php
	$ret->success = ob_get_clean();
	echo json_encode( $ret );
	wp_die();
}
add_action( 'wp_ajax_priprava_admin_table_load', 'priprava_admin_table_load' );

// Сохраняем данные, когда пост сохраняется
add_action( 'save_post', 'priprava_save_postdata' );
function priprava_save_postdata( $post_id ) {
	// Убедимся что поле установлено.
	if ( ! isset( $_POST['post_composition'] ) ) {
		return;
	}

	// проверяем nonce нашей страницы, потому что save_post может быть вызван с другого места.
	// if ( ! wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename(__FILE__) ) )
	// 	return;

	// если это автосохранение ничего не делаем
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return;
	}

	// проверяем права юзера
	if( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Все ОК. Теперь, нужно найти и сохранить данные
	// Очищаем значение поля textarea.
	$post_composition = sanitize_textarea_field( $_POST['post_composition'] );

	// Обновляем данные в базе данных.
	update_post_meta( $post_id, 'post_composition', $post_composition );
}

/**
 * Обрабатываем данные из таблицы с составом
 */
function priprava_save_compositions_table() {
	$ret = new stdClass();
	$compositions = priprava_transform_data( $_POST['compositions'] );
	$post_id = intval( sanitize_text_field( $_POST['post_id'] ) );
	$upgrade = ( isset( $_POST['upgrade'] ) ) ? 'Y' : 'N';
	$upgrade_data = ( isset( $_POST['upgrade_data'] ) ) ? $_POST['upgrade_data'] : '';
	$connect_ingredients = ( isset( $upgrade_data['con_ing'] ) ) ? $upgrade_data['con_ing'] : '';
	$new_ingredients_remove = ( isset( $upgrade_data['new_ing_rm'] ) ) ? $upgrade_data['new_ing_rm'] : '';

	if ( $upgrade == 'N' ) {
		/**
		 * Сначала мне нужно проверить существование каждого из ингредиентов
		 * Иначе возвращаем форму подтверждения добавления нового и переноса части названия в примечание
		 */

		$test_composition = priprava_test_compositions( $compositions );

		if ( ! empty( $test_composition ) ) {
			ob_start();
			priprava_generate_modal_confirm_compositions( $test_composition );
			$ret->modal = ob_get_clean();

			echo json_encode( $ret );
			wp_die();
		}
		
	}

	// Удаляем ингредиенты, если их пометили (не добавлять в базу)
	if ( ! empty( $new_ingredients_remove ) ) {
		$new_ingredients_remove = array_map( 'sanitize_text_field', $new_ingredients_remove );
		foreach ( $compositions as $key => $composition ) {
			if ( in_array( $composition['name'], $new_ingredients_remove ) ) {
				unset( $compositions[$key] );
			}
		}
	}
	
	// Если указали, что используем похожий ингредиент
	if ( ! empty( $connect_ingredients ) ) {
		foreach ( $connect_ingredients as $key => $ingredient ) {
			$ingredient = explode( '***', $ingredient );
			$notice = array_pop( $ingredient );
			$ingredient = array_shift( $ingredient );
			$key_search = priprava_array_ingredient_search( $ingredient, $compositions );
			$compositions[ $key_search ]['name'] = priprava_parse_ingredient_name( $ingredient );
			$compositions[ $key_search ]['notice'] = $notice;
			// $compositions[ $key_search ]['notice'] = priprava_parse_ingredient_name( $ingredient, 'second' ) . ' ' . $compositions[ $key_search ]['notice'];
		}
	}

	/**
	 * И только после проверки я буду сохранять
	 */
	priprava_remove_post_compositions( $post_id );

	foreach ( $compositions as $key => $composition ) {
		$composition_id = priprava_add_composition( $composition['name'] );

		if ( ! empty( $composition_id ) ) {
			priprava_save_post_compositions( $post_id, $composition_id, $composition );
		}
	}
	$ret->success = priprava_get_post_compositions_table( $post_id, array( 'button_remove' => true ) );

// $ret->error = print_r( $compositions, true ); $ret->error .= '|'; echo json_encode( $ret ); wp_die();
	echo json_encode( $ret );
	wp_die();
}
add_action('wp_ajax_priprava_save_compositions_table', 'priprava_save_compositions_table');

function priprava_array_ingredient_search( $var, $array, $type = false ){
	foreach ($array as $key => $value) {
		if( ( $type && $value["name"] === $var ) || ( ! $type && $value["name"] == $var ) ) {
			return $key;
		}
	}
	return false;
}

function priprava_generate_modal_confirm_compositions( $test_composition ) {
	?>
	<div class="compositions-overlay">
		<div class="theme-overlay">
			<!-- <div class="theme-backdrop"></div> -->
			<div class="theme-wrap wp-clearfix">
				<div class="theme-header">
					<div class="button button-primary button_save pre_comp_save">Подтвердить</div>
					<span class="modal-header"></span>
					<div class="close dashicons dashicons-no"><span class="screen-reader-text">Закрыть окно с информацией</span></div>
				</div>
				<div class="theme-about wp-clearfix"> <?php
					if ( isset( $test_composition['new'] ) ) {
						echo '<div class="composition_table_questions">В базе не обнаружены такие ингредиенты, добавить новые?</div>';
						echo '<table class="composition_table new_ing">';
						foreach ($test_composition['new'] as $key => $item) { ?>
							<tr>
								<td>
									<span class="ingredients_name"><?php echo $item['name']; ?></span>
								</td>
								<td>
									<label>Да<input type="radio" class="ing_radio" data-type="Y" name="new_ing_<?php echo $key; ?>" checked></label>
									<label>Нет<input type="radio" class="ing_radio" data-type="N" name="new_ing_<?php echo $key; ?>"></label>
								</td>
							</tr><?php
						}
						echo '</table>';
						echo '<div class="notice_block"><div class="notice_block_title">Примечание:</div><div>Да - ингредиент будет содан</div><div>Нет - ингредиент будет проигнорирован</div><div>Если этот инредиент есть в базе под другим менем, поставьте нет, закройте окно и добавте его в таблицу вручную (выбрать из списка)</div></div>';
					}
					if ( isset( $test_composition['connect'] ) ) {
						echo '<div class="composition_table_questions">В базе найдены похожие ингредиенты, использовать их с примечанием или создать новые?</div>';
						echo '<table class="composition_table con_ing">';
						echo '<tr><td class="composition_table_title">Примечание</td></tr>';
						foreach ($test_composition['connect'] as $key => $item) { ?>
							<tr>
								<td>
									<span class="ingredients_oldname"><?php echo $item['oldname']; ?></span> <span class="ingredients_separator">---></span> <span class="ingredients_name"><?php echo $item['name']; ?></span> <input type="text" class="ingredients_notice" value="<?php echo priprava_parse_ingredient_name( $item['oldname'], 'second' ), ' ', $item['notice']; ?>">
								</td>
								<td>
									<label>Да (Использовать имеющиеся)<input type="radio" class="ing_radio" data-type="Y" name="con_ing_<?php echo $key; ?>" checked></label>
									<label>Нет (Создать новые)<input type="radio" class="ing_radio" data-type="N" name="con_ing_<?php echo $key; ?>"></label>
								</td>
							</tr><?php
						}
						echo '</table>';
					} ?>
				</div>
			</div>
			
			
		</div>
	</div>
	<?php
}

function priprava_parse_ingredient_name( $string, $part = 'first' ) {
	$string = explode( ' ', $string );
	$string_one = array_shift( $string );
	$string_other = implode( ' ', $string );

	if ( $part == 'first' ) {
		return $string_one;
	}

	if ( $part == 'second' ) {
		return $string_other;
	}
	return false;
}

function priprava_test_compositions( $compositions ) {
	$response = array();
	foreach ( $compositions as $key => $composition ) {
		// Если не существует такого ингредиента
		if( ! priprava_isset_composition( $composition['name'] ) ) {

			// Разберем на составляющие
			$names = explode( ' ', $composition['name'] );

			// Проверка по первому слову
			if ( count( $names ) > 1 ) {
				$name = array_shift( $names );

				// Если существует, то предлагаем связать с этой сущностью
				if ( priprava_isset_composition( $name ) ) {
					$response['connect'][$key]['name'] = $name;
					$response['connect'][$key]['oldname'] = $composition['name'];
					$response['connect'][$key]['notice'] = $composition['notice'];
				} else {
					// Или предлагаем создать
					$response['new'][$key]['name'] = $composition['name'];
				}
			} else {
				// Предлагаем создать
				$response['new'][$key]['name'] = $composition['name'];
			}
		}
	}
	return $response;
}

function priprava_save_post_compositions( $post_id, $composition_id, array $composition ) {
	global $wpdb; 
	return $wpdb->insert(
		"{$wpdb->prefix}post_compositions",
		array(
			'post_id' => $post_id,
			'composition_id' => $composition_id,
			'notice' => $composition['notice'],
			'count' => $composition['count'],
			'units' => $composition['units'],
			'main' => $composition['main']
		),
		array( '%d', '%d', '%s', '%s', '%s', '%d' )
	);
}

function priprava_remove_post_compositions( $post_id ) {
	global $wpdb;
	return $wpdb->delete(
		"{$wpdb->prefix}post_compositions",
		array( 'post_id' => $post_id ),
		array( '%d' )
	);
}

function priprava_transform_data( array $data ) {
	$ret = array();

	foreach ($data as $key => $item) {
		$ret[$key]['name'] = ( isset( $item['name'] ) ) ? sanitize_text_field( $item['name'] ) : '';
		$ret[$key]['notice'] = ( isset( $item['notice'] ) ) ? sanitize_text_field( $item['notice'] ) : '';
		$ret[$key]['count'] = ( isset( $item['count'] ) ) ? sanitize_text_field( $item['count'] ) : '';
		$ret[$key]['units'] = ( isset( $item['units'] ) ) ? sanitize_text_field( $item['units'] ) : '';
		$main = ( isset( $item['main'] ) ) ? sanitize_text_field( $item['main'] ) : '';
		$ret[$key]['main'] = ( $main === 'true' ) ? true : false;
		if ( empty( $ret[$key]['name'] ) ) {
			unset( $ret[$key] );
		}
	}
	return $ret;
}

/**
 * Возвращает количество ингредиентов записи или false
 * @param  integer $post_id
 * @return integer/boolean
 */
function priprava_isset_post_compositions( $post_id ) {
	global $wpdb;
	if ( ! empty( $post_id ) ) {
		return $wpdb->get_var( $wpdb->prepare( "
			SELECT COUNT(post_id) FROM {$wpdb->prefix}post_compositions WHERE post_id = %d 
		", $post_id ) );
	}
	return false;
}

/**
 * Таблица с ингредиентами
 */
function priprava_get_post_compositions_table( $post_id, $attr = array() ) {
	$ingredients = priprava_get_saved_ingredients( $post_id );

	if ( ! empty( $ingredients ) ) {
		$count_tooltip = '<div class="table_composition_tooltip_modal">Через тире, если нужен диапазон<div><span class="underline">Например</span>:<br><span class="gray">1-3</span><br><span class="underline">Или, например:</span><br><span class="gray">(по вкусу)</span></div></div>';
		$composition_html = "<table class='table'>";
		$composition_html .= "<tr><td>ID продукта</td><td>Название</td><td>Примечание</td><td class='table_composition_tooltip'>Количество{$count_tooltip}</td><td>Ед. измерения</td><td>Основной ингредиент</td><td></td></tr>";
		$composition_html .= '<tr class="template"><td></td><td><input type="text" class="ing_name"></td><td><input type="text" class="ing_notice"></td><td><input type="text" class="ing_count"></td><td><input type="text" class="ing_units"></td><td><label><input type="checkbox" class="ing_main"></label></td>';
		if ( isset( $attr['button_remove'] ) && $attr['button_remove'] === true ) {
			$composition_html .= '<td><div class="button button_remove">X</div></td>';
		}
		$composition_html .= '</tr>';

		foreach ( $ingredients as $key => $ingredient ) {
			$checkbox_status = ( $ingredient['main'] ) ? ' checked' : '';
			ob_start(); ?>
			<tr>
				<td>
					<?php echo $ingredient['id']; ?>
				</td>
				<td>
					<input type='text' class="ing_name" value='<?php echo $ingredient['name']; ?>'>
				</td>
				<td>
					<input type='text' class="ing_notice" value='<?php echo $ingredient['notice']; ?>'>
				</td>
				<td>
					<input type='text' class="ing_count" value='<?php echo $ingredient['count']; ?>'>
				</td>
				<td>
					<input type='text' class="ing_units" value='<?php echo $ingredient['units']; ?>'>
				</td>
				<td>
					<label><input type='checkbox'<?php echo $checkbox_status; ?> class="ing_main"></label>
				</td>
				<?php if ( isset( $attr['button_remove'] ) && $attr['button_remove'] === true ) { ?>
					<td>
						<div class="button button_remove">X</div>
					</td>
				<?php } ?>
			</tr><?php
			$composition_html .= ob_get_clean();
		}
		$composition_html .= '</table><div class="button button_add_row">Добавить строку</div><div class="button button_save">Сохранить состав</div>';
	}
	return $composition_html;
}

/**
 * Возвращает массив ингредиентов, установленных по умолчанию для записи
 */
function priprava_get_main_ingredients( $post_id ) {
	global $wpdb;
	if ( ! empty( $post_id ) ) {
		$ingredients = $wpdb->get_results( $wpdb->prepare( "
			SELECT pc.*, c.name FROM {$wpdb->prefix}post_compositions pc LEFT JOIN {$wpdb->prefix}compositions c ON c.id = pc.composition_id WHERE pc.post_id = %d AND pc.main = 1 AND c.status = 'publish'
		", $post_id ) );
	}

	$return = array();

	if ( ! empty( $ingredients ) ) {
		foreach ( $ingredients as $key => $ingredient ) {
			$return[] = $ingredient->name;
		}
	}
	
	return $return;
}

/**
 * Шаблон вывода основных ингредиентов
 */
function priprava_get_main_ingredients_html( $post_id ) {
	$ingredients = priprava_get_main_ingredients( $post_id );

	if ( ! empty( $ingredients ) ) { ?>
		<div class="single-content-tags-item">
			<div class="single-content-tags-item-name">Основной ингредиент:</div><?php
			foreach ( $ingredients as $key => $ingredient ) { 
				if ( $key > 0 ) { ?>
					<div class="single-content-tags-item-delemiter">/</div><?php
				} ?>
				<div class="single-content-tags-item-label green-link"><?php echo $ingredient; ?></div><?php
			} ?>
		</div><?php
	}
}

/**
 * Возвращает массив сохраненных ингредиентов записи
 */
function priprava_get_saved_ingredients( $post_id ) {
	global $wpdb;
	if ( ! empty( $post_id ) ) {
		$ingredients = $wpdb->get_results( $wpdb->prepare( "
			SELECT pc.*, c.name FROM {$wpdb->prefix}post_compositions pc LEFT JOIN {$wpdb->prefix}compositions c ON c.id = pc.composition_id WHERE pc.post_id = %d AND c.status = 'publish'
		", $post_id ) );
	}

	$return = array();

	if ( ! empty( $ingredients ) ) {
		foreach ( $ingredients as $key => $ingredient ) {
			$return[$key]['id'] = $ingredient->composition_id;
			$return[$key]['name'] = $ingredient->name;
			$return[$key]['notice'] = $ingredient->notice;
			$return[$key]['count'] = $ingredient->count;
			$return[$key]['units'] = $ingredient->units;
			$return[$key]['main'] = $ingredient->main;
		}
	}

	return $return;
}







// Добавляем пункт подменю, страницы ингредиентов, для записи
add_action( 'admin_menu', 'priprava_add_submenu_page' );
function priprava_add_submenu_page() {
	$hook = add_submenu_page( 'edit.php', 'Ингредиенты', 'Ингредиенты', 'read', 'ingredients', 'priprava_ingredients_page' );
	add_action( "load-{$hook}", 'priprava_add_page_options' );
}

// Для сохранения настроек
add_filter( 'set-screen-option', function( $status, $option, $value ){
	return ( $option == 'ingredients_per_page' ) ? (int) $value : $status;
}, 10, 3 );

// Настройки страницы с ингредиентами
function priprava_add_page_options() {
	$option = 'per_page';
	$args = array(
		'label' => 'Ингредиенты',
		'default' => 15,
		'option' => 'ingredients_per_page'
	);
	add_screen_option( $option, $args );
}

// Генерируем содержмое страницы с ингрединтеми
function priprava_ingredients_page() {
	require_once 'ingredients-table-class.php';
	$IngredientsTable = new IngredientsTable(); ?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php echo get_admin_page_title(); ?></h1>
		<?php printf('<a href="?page=%s&action=%s" class="page-title-action add_ingredient">Добавить новый</a>', $_REQUEST['page'], 'add'); ?>
		<hr class="wp-header-end">
		<div id="ajax-response"></div>
	</div>
	<form id="composition-filter" method="GET">
		<div class="wrap">
	        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
			<?php $IngredientsTable->view(); ?>
		</div>
	</form><?php
}

/**
 * Предиктивный поиск
 */
add_action('wp_ajax_priprava_predictive_search', 'priprava_predictive_search');
function priprava_predictive_search() {
	if ( ! isset( $_POST['search'] ) && empty( $_POST['search'] ) ) {
		wp_die();
	}
	global $wpdb;
	$ingredients = $wpdb->get_results( $wpdb->prepare( "
		SELECT name FROM {$wpdb->prefix}compositions WHERE name LIKE %s AND status = 'publish'
	", '%' . $_POST['search'] . '%' ) );

	if ( ! empty( $ingredients ) ) { ?>
		<div class="modal predictive_search_modal"><?php
			foreach ($ingredients as $key => $ingredient) { ?>
				<div><?php echo $ingredient->name; ?></div>
				<?php
			} ?>
		</div><?php
	}
	wp_die();
}

/**
 * Микроразметка
 */
function priprava_generate_schema( $post_id ) {
	$post = get_post( $post_id );

	$post_link = get_permalink( $post->ID );

	$post_author = get_userdata( $post->post_author )->display_name;

	$post_date = date('Y-m-d', strtotime( $post->post_date ) );

	$categoryes = get_the_category( $post->ID );
	$category_name = $categoryes[0]->cat_name;

	$post_excerpt = get_the_excerpt( $post->ID );

	$energy_portion = priprava_get_energy_meta( $post->ID, 'energy_portion' );

	$cookTime = priprava_get_energy_meta( $post->ID, 'energy_time', $energy_portion ); // В формате ISO 8601
	priprava_formating_time( $cookTime );

	$energy_calories = priprava_get_energy_meta( $post->ID, 'energy_ccal' );
	$energy_protein = priprava_get_energy_meta( $post->ID, 'energy_protein' );
	$energy_fat = priprava_get_energy_meta( $post->ID, 'energy_fat' );
	$energy_carbohydrates = priprava_get_energy_meta( $post->ID, 'energy_carbohydrates' );

	$ingredients = priprava_get_post_compositions( $post->ID, array( 'type' => 'array' ) );

	$rating = priprava_get_raiting( $post->ID, array( 'type' => 'array' ) );

	$images = priprava_get_all_post_images( $post->ID, array( 'type' => 'meta' ) );

	$recipeInstructions = ''; ?>
	<div itemscope itemtype="http://schema.org/Recipe">
		<? /* Ссылка на рецепт */ ?>
		<?php if ( ! empty( $post_link ) ) : ?>
			<link itemprop="url" href="<?php echo $post_link; ?>">
			<meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="<?php echo $post_link; ?>">
		<?php endif; ?>
		
		<? /* Название рецепта */ ?>
		<?php if ( isset( $post->post_title ) && ! empty( $post->post_title ) ) : ?>
			<meta itemprop="name" content="<?php echo $post->post_title; ?>">
		<?php endif; ?>
		
		<? /* Автор рецепта */ ?>
		<?php if ( ! empty( $post_author ) ) : ?>
			<meta itemprop="author" content="<?php echo $post_author; ?>">
		<?php endif; ?>
		
		<? /* Дата публикации рецепта в формате Y-m-d */ ?>
		<?php if ( ! empty( $post_date ) ) : ?>
			<meta itemprop="datePublished" content="<?php echo $post_date; ?>">
		<?php endif; ?>
		
		<? /* Изображения рецепта */ ?>
		<?php if ( ! empty( $images ) ) : ?>
			<?php echo $images; ?>
		<?php endif; ?>
		
		<? /* Категория рецепта */ ?>
		<?php if ( ! empty( $category_name ) ) : ?>
			<meta itemprop="recipeCategory" content="<?php echo $category_name; ?>">
		<?php endif; ?>
		
		<? /* Описание (превью) рецепта */ ?>
		<?php if ( ! empty( $post_excerpt ) ) : ?>
			<meta itemprop="description" content="<?php echo $post_excerpt; ?>">
		<?php endif; ?>

		<? /* Описание (превью) рецепта */ ?>
		<?php if ( ! empty( $cookTime ) ) : ?>
			<meta itemprop="totalTime" content="<?php echo $cookTime; ?>">
		<?php endif; ?>
		
		<? /* Количество порций рецепта */ ?>
		<?php if ( ! empty( $energy_portion ) ) : ?>
			<meta itemprop="recipeYield" content="<?php echo $energy_portion; ?>">
		<?php endif; ?>
		
		<? /* Энергетическая ценность */ ?>
		<?php if ( ! empty( $energy_calories ) || ! empty( $energy_protein ) || !empty( $energy_fat ) || !empty( $energy_carbohydrates ) ) : ?>
			<div itemprop="nutrition" itemscope itemtype="http://schema.org/NutritionInformation">
				<?php if ( ! empty( $energy_calories ) ) : ?>
			    	<meta itemprop="calories" content="<?php echo $energy_calories; ?>">
			    <?php endif; ?>
			    <?php if ( ! empty( $energy_protein ) ) : ?>
			    	<meta itemprop="proteinContent" content="<?php echo $energy_protein; ?>">
			    <?php endif; ?>
			    <?php if ( ! empty( $energy_fat ) ) : ?>
			    	<meta itemprop="fatContent" content="<?php echo $energy_fat; ?>">
			    <?php endif; ?>
			    <?php if ( ! empty( $energy_carbohydrates ) ) : ?>
			    	<meta itemprop="carbohydrateContent" content="<?php echo $energy_carbohydrates; ?>">
			    <?php endif; ?>
			</div>
		<?php endif; ?>

		<? /* Ингредиенты */ ?>
		<?php if ( ! empty( $ingredients ) ) : ?>
			<?php foreach ($ingredients as $ingredient) : ?>
				<meta itemprop="recipeIngredient" content="<?php echo "{$ingredient['name']} {$ingredient['notice']} {$ingredient['count']} {$ingredient['units']}"; ?>">
			<?php endforeach; ?>
		<?php endif; ?>

		<? /* Рейтинг рецепта */ ?>
		<?php if ( isset( $rating['value'] ) && ! empty( $rating['value'] ) ) : ?>
			<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
				<meta itemprop="ratingValue" content="<?php echo $rating['value']; ?>">
				<meta itemprop="reviewCount" content="<?php echo $rating['count']; ?>">
			</div>
		<?php endif; ?>

		<? /* Пошаговая инструкция приголовления рецепта */ ?>
		<?php if ( ! empty( $recipeInstructions ) ) : ?>
			<meta itemprop="recipeInstructions" content="<?php echo $recipeInstructions; ?>">
		<?php endif; ?>
	</div><?php
}

/**
 * Форматируем время для микроразметки по формату ISO 6801
 * @param &$time - время в минутах
 */
function priprava_formating_time( &$time ) {
	$time = intval( $time ) * 60;
	$prefix = 'PT';
	$oneDay = 86400;
	$totalTime = '';

	if ( $time >= $oneDay ) {
		$days = floor( $time / $oneDay );
		if ( $days >= 1 ) {
			$prefix = 'P' . $days . 'DT';
		}
	}

	$timeHourses = date('G', $time );
	$timeMinutes = date('i', $time );

	if ( ! empty( $timeHourses ) ) {
		$totalTime .= $timeHourses . 'H';
	}
	if ( ! empty( $timeMinutes ) && $timeMinutes != '00' ) {
		$totalTime .= $timeMinutes . 'M';
	}

	if ( ! empty( $totalTime ) ) {
		$time = $prefix . $totalTime;
	}
}

add_action('wp_ajax_saveIngredientImage', 'saveIngredientImage');
function saveIngredientImage() {

	if ( ! isset( $_POST['ingredient_id'] ) || empty( $_POST['ingredient_id'] ) ) {
		wp_die();
	}
	
	if ( ! isset( $_POST['image_id'] ) || empty( $_POST['image_id'] ) ) {
		wp_die();
	}

	$ingredient_id = intval( $_POST['ingredient_id'] );
	$image_id = intval( $_POST['image_id'] );

	global $wpdb;

	$wpdb->update( $wpdb->prefix . 'compositions', array( 'image_id' => $image_id ), array( 'id' => $ingredient_id ) );
	
	echo $image_id;

	wp_die();
}

add_action('wp_ajax_removeIngredientImage', 'removeIngredientImage');
function removeIngredientImage() {
	
	if ( ! isset( $_POST['ingredient_id'] ) || empty( $_POST['ingredient_id'] ) ) {
		wp_die();
	}
	
	$ingredient_id = intval( $_POST['ingredient_id'] );
	
	global $wpdb;

	$result = $wpdb->update( $wpdb->prefix . 'compositions', array('image_id' => ''), array( 'id' => $ingredient_id ) );
	
	echo $result;
	
	wp_die();
}

/**
 * Этот хук удаляет ингредиенты связанные с удаляемой записью
 */
add_action( 'after_delete_post', 'after_delete_post_rm_composition' );
function after_delete_post_rm_composition( $postid ){
	global $wpdb;
	
	$result = $wpdb->delete( $wpdb->prefix . 'post_compositions', array( 'post_id' => $postid ) );
}