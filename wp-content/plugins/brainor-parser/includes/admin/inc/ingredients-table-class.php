<?php

if( class_exists( 'WP_List_Table' ) == FALSE ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class IngredientsTable extends WP_List_Table {

	private $status = 'all';

	function __construct(){
		$this->setStatus();
		
		parent::__construct(array(
			'singular' => 'ingredient',
			'plural'   => 'ingredients',
			'ajax'     => false,
		));

		$this->prepare_items();

	}

	private function setStatus() {
		if(isset($_REQUEST['status'])) {
			switch ($_REQUEST['status']) {
				case 'publish':
					$this->status = 'publish';
					break;
				case 'trash':
					$this->status = 'trash';
					break;
			}
		}
	}

	private function getNavMenuItems() {
		global $wpdb;

		$result = $wpdb->get_results( "
			SELECT c.status FROM {$wpdb->prefix}compositions c LEFT JOIN {$wpdb->prefix}post_compositions pc ON c.id = pc.composition_id GROUP BY c.id
		", OBJECT );
		$trash = $publish = 0;
		if( ! empty($result) ) {
			foreach ($result as $key => $v) {
				if('publish' == $v->status) $publish++;
				if('trash' == $v->status) $trash++;
			}
			$ret = array();

			if( ! empty( $publish ) ) {
				$ret['all'] = $publish;
				$ret['publish'] = $publish;
			}

			if( ! empty( $trash ) ) {
				$ret['trash'] = $trash;
			}

			return $ret;
		}
		return false;
	}

	private function showNavMenu() {
		$views = $this->getNavMenuItems();

		if ( empty( $views ) )
			return;

		$links = array();

		if ( isset( $views['all'] ) && !empty( $views['all'] ) ) {
			$class = ('all' == $this->status) ? 'class="current"' : '';
			$links['all'] = "<a href='edit.php?page=ingredients'{$class}>Все ({$views['all']})</a>";
		}

		if ( isset( $views['publish'] ) && !empty( $views['publish'] ) ) {
			$class = ('publish' == $this->status) ? 'class="current"' : '';
			$links['publish'] = "<a href='edit.php?page=ingredients&status=publish'{$class}>Опубликованные ({$views['publish']})</a>";
		}

		if ( isset( $views['trash'] ) && !empty( $views['trash'] ) ) {
			$class = ('trash' == $this->status) ? 'class="current"' : '';
			$links['trash'] = "<a href='edit.php?page=ingredients&status=trash'{$class}>Удаленные ({$views['trash']})</a>";
		}

		echo "<ul class='subsubsub'>\n";
		
		foreach ( $links as $class => $view ) {
			$currentClass = ($class == $this->status) ? ' current' : '';
			$views[ $class ] = "\t<li class='{$class}{$currentClass}'>$view";
		}
		echo implode( " |</li>\n", $views ) . "</li>\n";
		echo "</ul>";
	}

	// Создает элементы таблицы
	function prepare_items(){
		global $wpdb;

		$columns = $this->get_columns();

		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);

		$order = isset( $_GET['orderby'] ) ? ' ORDER BY c.' . sanitize_text_field( $_GET['orderby'] ) : '';
		$order .= isset( $_GET['order'] ) ? ' ' . sanitize_text_field( strtoupper( $_GET['order'] ) ) : '';
		$order = ( ! empty( $order ) ) ? $order : 'ORDER BY c.id DESC';

		$search = isset( $_GET['s'] ) ? sprintf(" WHERE c.name LIKE '%%%s%%'", sanitize_text_field( $_GET['s'] )) : '';

		$status = '';
		switch ( $this->status ) {
			case 'trash':
				$status = ( empty( $search ) ) ? ' WHERE c.status = "trash"' : ' AND c.status = "trash"';
				break;

			default:
				$status = ( empty( $search ) ) ? ' WHERE c.status = "publish"' : ' AND c.status = "publish"';
				break;
		}

		$ingredients = $wpdb->get_results( "
			SELECT c.*, COUNT(pc.post_id) AS count FROM {$wpdb->prefix}compositions c LEFT JOIN {$wpdb->prefix}post_compositions pc ON c.id = pc.composition_id {$search}{$status} GROUP BY c.id $order
		" );

		$array = array();
		foreach ($ingredients as $key => $ingredient) {
			$array[$key]['id'] = $ingredient->id;
			$array[$key]['name'] = $ingredient->name;
			$array[$key]['image'] = wp_get_attachment_image( $ingredient->image_id, 'ingredients_list' );
			$array[$key]['count'] = $ingredient->count;
			$array[$key]['kbju'] = $this->getIngredientOptionsView( $ingredient->id );
		}
		$this->items = $array;

		$per_page     = $this->get_items_per_page('ingredients_per_page', 15);
		$current_page = $this->get_pagenum();
		$total_items  = count($this->items);
		
		$this->items = array_slice( $this->items, ( ( $current_page - 1 ) * $per_page ), $per_page );
		
		$this->set_pagination_args( array(
		  'total_items' => $total_items,
		  'per_page'    => $per_page
		) );
		$this->items = $this->items;

	}

	private function getIngredientOptionsView( $ingredient_id ) {
		$kkal          = $this->getIngredientOption( $ingredient_id, 'kkal' );
		$proteins      = $this->getIngredientOption( $ingredient_id, 'proteins' );
		$fats          = $this->getIngredientOption( $ingredient_id, 'fats' );
		$carbohydrates = $this->getIngredientOption( $ingredient_id, 'carbohydrates' );
		
		$view = '';

		$view .= ( $kkal ) ? "{$kkal} ккал<br>" : '';
		$view .= ( $proteins ) ? "Протеины: {$proteins}<br>" : '';
		$view .= ( $fats ) ? "Жиры: {$fats}<br>" : '';
		$view .= ( $carbohydrates ) ? "Углеводы: {$carbohydrates}<br>" : '';

		return $view;
	}

	// Колонки таблицы
	function get_columns(){
		return array(
			// 'cb'     => '<input type="checkbox" />',
			// 'id'     => 'ID',
			'name'   => 'Имя',
			'image'  => 'Изображение',
			'kbju'   => 'КБЖУ',
			'count'  => 'Использован раз',
		);
	}

	// Сортируемые колонки
	function get_sortable_columns(){
		return array(
			// 'id' => array( 'id', false ),
			'name' => array( 'name', false ),
		);
	}

	// Вывод каждой ячейки таблицы
	function column_default( $item, $colname ){
		return ( isset( $item[$colname] ) ) ? print_r( $item[$colname], true ) : print_r( $item, true ) ;
	}

	// Добавляем колонку с чекбоксами для массового выделения элементов
	function column_cb( $item ) {
		return false;
        return sprintf(
            '<input type="checkbox" name="ingredients[]" value="%s" />', $item['id']
        );
    }

    // Для колонки с именем name добавляем кнопки действий
    function column_name($item) {
		$actions = array();

		if('trash' == $this->status) {
			$actions['untrash'] = sprintf('<a href="?page=%s&action=%s&ingredient=%s">Восстановить</a>', $_REQUEST['page'], 'untrash', $item['id']);
			$actions['delete'] = sprintf('<a href="?page=%s&action=%s&ingredient=%s">Удалить</a>', $_REQUEST['page'], 'finaldelete', $item['id']);
		} else {
			$actions['edit'] = sprintf('<a href="?page=%s&action=%s&ingredient=%s">Редактировать</a>', $_REQUEST['page'], 'edit', $item['id']);
			$actions['delete'] = sprintf('<a href="?page=%s&action=%s&ingredient=%s">Удалить</a>', $_REQUEST['page'], 'delete', $item['id']);
		}

		return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions) );
	}
	
	// Для колонки с именем count добавляем кнопки действий
    function column_count($item) {
		$actions = array();
		
		if($item['count'] > 0) {
			$actions['detail'] = sprintf('<a href="?page=%s&action=%s&ingredient=%s">Подробнее</a>', $_REQUEST['page'], 'detail', $item['id']);
		}

		return sprintf('%1$s %2$s', $item['count'], $this->row_actions($actions) );
	}

	// Помещаем выпадающее меню с массовыми действиями и кнопку «применить» вверху и внизу таблицы
	function get_bulk_actions() {
		return false;
		$actions = array(
			'delete'    => 'Удалить'
		);
		return $actions;
	}

	function display_tablenav( $which ) {
		if ( 'top' === $which ) {
			wp_nonce_field( 'bulk-' . $this->_args['plural'] );
		} ?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">

			<?php if ( $this->has_items() ): ?>
			<div class="alignleft actions bulkactions">
				<?php $this->bulk_actions( $which ); ?>
			</div>
			<?php endif;
			$this->extra_tablenav( $which );
			$this->pagination( $which ); ?>

			<br class="clear" />
		</div><?php
	}

	function current_action() {
	    return parent::current_action();
	}

	/**
	 * Отправка ингредиента в корзину
	 */
	private function delete() {
		if( isset( $_REQUEST['ingredient'] ) && ! empty( $_REQUEST['ingredient'] ) ) {
			global $wpdb;

			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}compositions SET status = '%s' WHERE id = %d", 'trash', intval( $_REQUEST['ingredient'] ) ) );

			$this->redirectReferer();
		}
	}

	/**
	 * Полное удаление ингредиента
	 */
	private function finalDelete() {
		if( isset( $_REQUEST['ingredient'] ) && ! empty( $_REQUEST['ingredient'] ) ) {
			global $wpdb;

			$id = intval( $_REQUEST['ingredient'] );

			$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}post_compositions WHERE composition_id = %d", $id ) );
			$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}compositions WHERE id = %d", $id ) );

			if( $this->checkTrashCount() === false ) {
				$this->redirect( array('ingredient', 'status', 'paged') );
			}

			$this->redirectReferer();
		}
	}

	private function untrash() {
		if( isset( $_REQUEST['ingredient'] ) && ! empty( $_REQUEST['ingredient'] ) ) {
			global $wpdb;

			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}compositions SET status = '%s' WHERE id = %d", 'publish', intval( $_REQUEST['ingredient'] ) ) );

			if( $this->checkTrashCount() === false ) {
				$this->redirect( array('ingredient', 'status', 'paged') );
			}

			$this->redirectReferer();
		}
	}

	/**
	 * Редирект на реферера
	 */
	private function redirectReferer() {
		$ref = wp_get_referer() ? wp_get_referer() : $_SERVER['HTTP_REFERER'];
		$ref = remove_query_arg( array('trashed', 'untrashed', 'deleted', 'ids', 'ingredient'), $ref );
		wp_redirect( $ref );
		exit;
	}

	private function redirect( array $remove ) {
		$ref = wp_get_referer() ? wp_get_referer() : $_SERVER['HTTP_REFERER'];
		$ref = remove_query_arg( $remove, $ref );
		wp_redirect( $ref );
		exit;
	}

	private function checkTrashCount() {
		global $wpdb;
		$count = $wpdb->get_var( "SELECT COUNT(id) FROM {$wpdb->prefix}compositions WHERE status = 'trash'" );
		return $count > 0 ? true : false;
	}

	private function edit() {
		$ingredient_id = $this->getIngredientId();
		$this->saveIngredientOptions();
		wp_enqueue_media();
		?>
		<div class="loading_block"></div>
		<input type="hidden" name="ingredient" value="<?php echo $ingredient_id; ?>">
		<input type="hidden" name="action" value="edit">
		<?php
		echo $this->getIngredientForm( $ingredient_id );
	}

	private function saveIngredientOptions() {
		global $wpdb;
		if ( isset( $_REQUEST['ingredient'] ) && ! empty( $_REQUEST['ingredient'] ) ) {
			if ( isset( $_REQUEST['ingredient_description'] ) ) {
				$wpdb->update( $wpdb->prefix . 'compositions',
					array(
						'name' => $_REQUEST['ingredient_name'],
						'description' => $_REQUEST['ingredient_description'],
						'kkal' => $_REQUEST['ingredient_kkal'],
						'proteins' => $_REQUEST['ingredient_proteins'],
						'fats' => $_REQUEST['ingredient_fats'],
						'carbohydrates' => $_REQUEST['ingredient_carbohydrates'],
						'weight' => $_REQUEST['ingredient_weight'],
						'count' => $_REQUEST['ingredient_count'],
					),
					array(
						'id' => intval( $_REQUEST['ingredient'] )
					)
				);
				$this->redirect( array('ingredient_description', 'submit', 'ingredient_name') );
			}
		} else {
			if ( isset( $_REQUEST['ingredient_description'] ) && isset( $_REQUEST['ingredient_name'] ) && isset( $_REQUEST['image_id'] ) ) {
				$wpdb->insert( $wpdb->prefix . 'compositions',
					array(
						'name' => $_REQUEST['ingredient_name'],
						'description' => $_REQUEST['ingredient_description'],
						'image_id' => intval( $_REQUEST['image_id'] ),
						'status' => 'publish',
						'kkal' => $_REQUEST['ingredient_kkal'],
						'proteins' => $_REQUEST['ingredient_proteins'],
						'fats' => $_REQUEST['ingredient_fats'],
						'carbohydrates' => $_REQUEST['ingredient_carbohydrates'],
						'weight' => $_REQUEST['ingredient_weight'],
						'count' => $_REQUEST['ingredient_count'],
					),
					array('%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s')
				);
				$this->redirect( array('ingredient_description', 'image_id', 'submit', 'ingredient_name', 'action') );
			}
		}
	}

	private function getIngredientOption($ingredient_id, $key) {
		if ( empty( $ingredient_id ) ) return;
		global $wpdb;
		return $wpdb->get_var("
			SELECT {$key} FROM
				{$wpdb->prefix}compositions
			WHERE
				id = {$ingredient_id}
		");
	}
	
	private function getIngredientDescription( $ingredient_id ) {
		if ( empty( $ingredient_id ) ) return;
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( "SELECT description FROM {$wpdb->prefix}compositions WHERE id = %d", intval( $_REQUEST['ingredient'] ) ) );
	}

	private function getIngredientId() {
		return ( ! empty( $_REQUEST['ingredient'] ) ) ? intval( $_REQUEST['ingredient'] ) : false;
	}

	private function getIngredienImage( $ingredient_id = '' ) {
		global $wpdb;
		
		$image_id = '';
		
		if( ! empty( $ingredient_id ) ) {
			$image_id = $wpdb->get_var( $wpdb->prepare( "SELECT image_id FROM {$wpdb->prefix}compositions WHERE id = %d", $ingredient_id ) );
		}
		
		if( ! empty( $image_id ) ) {
			$img = '<img src="' . wp_get_attachment_image_url( $image_id, 'thumbnail' ) . '">';
		} else {
			$img = '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkAQMAAABKLAcXAAAABlBMVEUAAAC7u7s37rVJAAAAAXRSTlMAQObYZgAAACJJREFUOMtjGAV0BvL/G0YMr/4/CDwY0rzBFJ704o0CWgMAvyaRh+c6m54AAAAASUVORK5CYII=">';
		}

		return $img;
	}

	private function getIngredientName( $ingredient_id ) {
		if ( empty( $ingredient_id ) ) return;
		global $wpdb;

		return $wpdb->get_var( $wpdb->prepare( "SELECT name FROM {$wpdb->prefix}compositions WHERE id = %d", $ingredient_id ) );
	}
	
	private function detail() {
		global $wpdb;
		
		$ingredient_id = $this->getIngredientId();
		
		$post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->prefix}post_compositions WHERE composition_id = %d ", $ingredient_id ) );
		
		$link = array(); ?>
		
		<h2 class="title"><?php echo $this->getIngredientName( $ingredient_id ); ?> в статьях:</h2><?php
		
		foreach ( $post_ids as $key => $post_id ) {
			if ( get_post_status( $post_id ) == 'publish' ) {
				printf( "<a id='{$post_id}' href='%s' target='_blank'>%s</a> / <a href='%s'>Редактировать статью</a><br>", get_post_permalink( $post_id ), get_the_title( $post_id ), get_edit_post_link( $post_id ) );
			}
		}
	}
	private function addIngredient() {
		$this->saveIngredientOptions();
		wp_enqueue_media();
		?>
		<h2 class="title">Добавление нового ингредиента</h2>
		<div class="loading_block"></div>
		<input type="hidden" name="action" value="add">
		<?php
		echo $this->getIngredientForm();
	}

	private function getIngredientForm( $ingredient_id = '' ) {
		ob_start();
		?>
		<div id="col-left">
			<h3 class="title">Название ингредиента</h3>
			<input type="text" name="ingredient_name" id="ingredient_name" value="<?php echo $this->getIngredientName( $ingredient_id ); ?>">
			<h3 class="title">Фото ингредиента</h3>
			<div class="image_block"><?php echo $this->getIngredienImage( $ingredient_id ); ?></div>
			<div class="form-field">
				<input type="hidden" id="image_id" name="image_id" value="">
				<input type="button" class="button button-secondary image_remove" value="Удалить">
			</div>
			<h3 class="title">Описание ингредиента</h3>
			<div class="form-field">
				<?php wp_editor( $this->getIngredientDescription( $ingredient_id ) , 'ingredient_description', $settings = array('textarea_name' => 'ingredient_description') ); ?>
			</div>
			<h3 class="title">КБЖУ</h3>
			<div class="form-field">
				<label>Килокалории</label>
				<input type="number" step="0.01" name="ingredient_kkal" id="ingredient_kkal" placeholder="Килокалории" value="<?php echo $this->getIngredientOption( $ingredient_id, 'kkal' ); ?>">
			</div>
			<div class="form-field">
				<label>Белки</label>
				<input type="number" step="0.01" name="ingredient_proteins" id="ingredient_proteins" placeholder="Белки" value="<?php echo $this->getIngredientOption( $ingredient_id, 'proteins' ); ?>">
			</div>
			<div class="form-field">
				<label>Жиры</label>
				<input type="number" step="0.01" name="ingredient_fats" id="ingredient_fats" placeholder="Жиры" value="<?php echo $this->getIngredientOption( $ingredient_id, 'fats' ); ?>">
			</div>
			<div class="form-field">
				<label>Углеводы</label>
				<input type="number" step="0.01" name="ingredient_carbohydrates" id="ingredient_carbohydrates" placeholder="Углеводы" value="<?php echo $this->getIngredientOption( $ingredient_id, 'carbohydrates' ); ?>">
			</div>
			<h3 class="title">Единицы измерения</h3>
			<div class="form-field">
				<label>Гр</label>
				<input type="text" step="0.01" name="ingredient_weight" id="ingredient_weight" placeholder="Гр" value="<?php echo $this->getIngredientOption( $ingredient_id, 'weight' ); ?>">
			</div>
			<div class="form-field">
				<label>Шт</label>
				<input type="text" step="0.01" name="ingredient_count" id="ingredient_count" placeholder="Шт" value="<?php echo $this->getIngredientOption( $ingredient_id, 'count' ); ?>">
			</div><?php
			submit_button('Сохранить', 'primary'); ?>
		</div><?php
		return ob_get_clean();
	}

	private function controller() {
		$action = $this->current_action();

		switch ($action) {
			case 'edit':
				$this->edit();
				break;
			case 'add':
				$this->addIngredient();
				break;
			case 'detail':
				$this->detail();
				break;
			case 'delete':
				$this->showNavMenu();
				$this->search_box( __('Search'), 'compositions_search_button');
				$this->delete();
				break;
			case 'finaldelete':
				$this->showNavMenu();
				$this->search_box( __('Search'), 'compositions_search_button');
				$this->finalDelete();
			case 'untrash':
				$this->showNavMenu();
				$this->search_box( __('Search'), 'compositions_search_button');
				$this->untrash();
			
			default:
				$this->showNavMenu();
				$this->search_box( __('Search'), 'compositions_search_button');
				$this->display();
				break;
		}
	}

	public function view() {
		$this->controller();
	}

}