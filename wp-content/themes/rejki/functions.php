<?php 
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Опции сайта',
		'menu_title' 	=> 'Опции',
		'menu_slug' 	=> 'Опции сайта',
		'capability' 	=> 'edit_posts',
		'update_button'		=> __('Обновить', 'acf'),
		'redirect' 	=> false
	));
}
register_nav_menus(array(
	'main_menu' => 'Главное меню',
	'sidebar_menu' => 'Сайдбар'

));




add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);

function special_nav_class ($classes, $item) {
    if (in_array('current-menu-item', $classes) ){
    if (!in_array('menu-item-type-custom', $classes)) {
     $classes[] = 'active';
    }
    }
    return $classes;
}
add_theme_support( 'post-thumbnails' );
function d($el){
	echo "<pre style='background-color: #fff'>";
	var_dump($el);
	echo "</pre>";
}

/**
 * Определим константу, которая будет хранить путь к папке single
 */
define( 'SINGLE_PATH', TEMPLATEPATH . '/single' );
 
/**
 * Добавим фильтр, который будет запускать функцию подбора шаблонов
 */
add_filter( 'single_template', 'my_single_template' );
 
/**
 * Функция для подбора шаблона
 */
function my_single_template( $single ) {
    global $wp_query, $post;
 
    /**
     * Проверяем наличие шаблонов по ID поста.
     * Формат имени файла: single-ID.php
     */
    if ( file_exists( SINGLE_PATH . '/single-' . $post->ID . '.php' ) ) {
        return SINGLE_PATH . '/single-' . $post->ID . '.php';
    }
 
    /**
     * Проверяем наличие шаблонов для категорий, ищем по ID категории или слагу
     * Формат имени файла: single-cat-SLUG.php или single-cat-ID.php
     */
    foreach ( (array) get_the_category() as $cat ) :
 
        if ( file_exists( SINGLE_PATH . '/single-cat-' . $cat->slug . '.php' ) ) {
            return SINGLE_PATH . '/single-cat-' . $cat->slug . '.php';
        } elseif ( file_exists( SINGLE_PATH . '/single-cat-' . $cat->term_id . '.php' ) ) {
            return SINGLE_PATH . '/single-cat-' . $cat->term_id . '.php';
        }
 
    endforeach;
 
    /**
     * Проверяем наличие шаблонов для тэгов, ищем по ID тэга или слагу
     * Формат имени файла: single-tag-SLUG.php или single-tag-ID.php
     */
    $wp_query->in_the_loop = true;
    foreach ( (array) get_the_tags() as $tag ) :
 
        if ( file_exists( SINGLE_PATH . '/single-tag-' . $tag->slug . '.php' ) ) {
            return SINGLE_PATH . '/single-tag-' . $tag->slug . '.php';
        } elseif ( file_exists( SINGLE_PATH . '/single-tag-' . $tag->term_id . '.php' ) ) {
            return SINGLE_PATH . '/single-tag-' . $tag->term_id . '.php';
        }
 
    endforeach;
    $wp_query->in_the_loop = false;
 
    /**
     * Если ничего не найдено открываем стандартный single.php
     */
    if ( file_exists( SINGLE_PATH . '/single.php' ) ) {
        return SINGLE_PATH . '/single.php';
    }
 
    return $single;
}
function remove_admin_login_header() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'remove_admin_login_header');