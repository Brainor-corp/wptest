<?php

// Прячем сайдбар от всех, кроме админа
add_action( 'after_setup_theme', 'remove_admin_bar' );
function remove_admin_bar() {
	if ( ! current_user_can( 'administrator' ) && ! is_admin() ) {
		show_admin_bar(false);
	}
}

// Эта функция, которая подключается к фильтру author_rewrite_rules и заменяет правила перезаписи автора.
add_filter('author_rewrite_rules', 'no_author_base_rewrite_rules');
function no_author_base_rewrite_rules($author_rewrite) { 
    global $wpdb;
    $author_rewrite = array();
    $authors = $wpdb->get_results("SELECT user_nicename AS nicename from $wpdb->users");    
    foreach($authors as $author) {
        $author_rewrite["({$author->nicename})/page/?([0-9]+)/?$"] = 'index.php?author_name=$matches[1]&paged=$matches[2]';
        $author_rewrite["({$author->nicename})/?$"] = 'index.php?author_name=$matches[1]';
    }   
    return $author_rewrite;
}

// Эта функция, которая перехватывает фильтр author_link и удаляет базу "автор" из возвращаемого URL.
add_filter('author_link', 'no_author_base', 1000, 2);
function no_author_base($link, $author_id) {
    $link_base = trailingslashit(get_option('home'));
    $link = preg_replace("|^{$link_base}author/|", '', $link);
    return $link_base . $link;
}

/* Методы ниже для изменения url не подходят, т.к. ломают ссылки на статьи */

/**
 * Plugin Name: Add "/profile" URL rewrite rule.
 * Version: 1.0.0
 * Author: Matty
 * Description: Add a rewrite rule to redirect "/profile" URLs to the appropriate author archive screen.
 */
/*new Matty_Profile_Rewrite();
class Matty_Profile_Rewrite {
	public function __construct () {
		add_filter( 'author_rewrite_rules', array( 'Matty_Profile_Rewrite', 'modify_author_base' ) );
	} // End __construct()
	public function modify_author_base ( $rules ) {
		$new_rules = array();
		foreach ( (array)$rules as $k => $v ) {
			$key = str_replace( 'author/', '', $k );
			$new_rules[$key] = $v;
		}
		$rules = $new_rules;
		return $rules;
	} // End modify_author_base()
} // End Class*/

// Удаляем префикс в url на странице автора
/*add_action('init', 'priprava_author_base');
function priprava_author_base() {
    global $wp_rewrite;
    if ( is_author() ) {
    	$wp_rewrite->author_base = '';
    }
}*/