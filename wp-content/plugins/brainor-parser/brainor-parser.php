<?php
/*
Plugin Name: Brainor Parser
Description: Парсинг данных со сторонних ресурсов
Version: 1.0
Author: Brainor
Author URI: http://brainor.ru/
Plugin URI: http://brainor.ru/
*/
define('BR_PARSER_DIR', plugin_dir_path(__FILE__));
define('BR_PARSER_URL', plugin_dir_url(__FILE__));
function br_parser_load(){
 
    if(is_admin()) // подключаем файлы администратора, только если он авторизован
        require_once(BR_PARSER_DIR.'includes/admin/menu.php');
    add_action('admin_enqueue_scripts', 'add_br_parser_scripts'); // приклеем ф-ю на добавление скриптов в футер
    if (!function_exists('add_br_parser_scripts')) { // если ф-я уже есть в дочерней теме - нам не надо её определять
        function add_br_parser_scripts() { // добавление скриптов
            wp_enqueue_script('bootstrap', BR_PARSER_URL.'assets/js/bootstrap.min.js','','',true); // бутстрап
            wp_enqueue_script( 'priprava-admin-script', BR_PARSER_URL . 'assets/js/admin-script.js', array( 'jquery' ), time(), true );
            wp_enqueue_script('imagefield', BR_PARSER_URL.'assets//js/multiupload.js', array( 'jquery' ), time(), true);
        }
    }
    add_action('admin_print_styles', 'add_br_parser_styles'); // приклеем ф-ю на добавление скриптов в футер
    if (!function_exists('add_br_parser_styles')) { // если ф-я уже есть в дочерней теме - нам не надо её определять
        function add_br_parser_styles() { // добавление скриптов
            wp_enqueue_style( 'priprava-admin-style', BR_PARSER_URL . 'assets//css/admin-style.css', time() );
        }
    }



    require_once(BR_PARSER_DIR.'includes/core.php');
}
br_parser_load();

register_activation_hook(__FILE__, 'br_parser_activation');
register_deactivation_hook(__FILE__, 'br_parser_deactivation');
 
function br_parser_activation() {
    $receiptTerm = term_exists('receipts');
    if(false == $receiptTerm){
        $cat_data = array(
            'cat_ID' => 0,                // ID категории, которую нужно обновить. 0 - добавит новую категорию.
            'cat_name' => 'Рецепты',             // название категории. Обязательный.
            'category_description' => 'Категория для рецептов', // описание категории
            'category_nicename' => 'receipts',      // слаг категории
            'taxonomy' => 'category'      // таксономия. Измените, чтобы добавить элемент другой таксономии. Например для меток будет post_tag
        );
        wp_insert_category( $cat_data );
    }

	// регистрируем действие при удалении
	register_uninstall_hook(__FILE__, 'br_parser_uninstall');
}
 
function br_parser_deactivation() {
    // при деактивации
}

function br_parser_uninstall(){
 
    //действие при удалении
}
require_once(BR_PARSER_DIR.'includes/admin/functions.php');