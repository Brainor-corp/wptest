<?php
/*
Plugin Name: Brainor Tools
Description: Плагин для авто-товаров
Version: 1.0
Author: Brainor
Author URI: http://brainor.ru/
Plugin URI: http://brainor.ru/
*/
define('BR_TOOLS_DIR', plugin_dir_path(__FILE__));
define('BR_TOOLS_URL', plugin_dir_url(__FILE__));
function br_tools_load(){
 
    if(is_admin()) // подключаем файлы администратора, только если он авторизован
        require_once(BR_TOOLS_DIR.'includes/admin/menu.php');
        require_once(BR_TOOLS_DIR.'includes/admin/adminTableClass.php');
    add_action('admin_enqueue_scripts', 'add_br_tools_scripts'); // приклеем ф-ю на добавление скриптов в футер
    if (!function_exists('add_br_tools_scripts')) { // если ф-я уже есть в дочерней теме - нам не надо её определять
        function add_br_tools_scripts() { // добавление скриптов
            wp_enqueue_script('popper', BR_TOOLS_URL.'assets/js/popper.min.js','','',true);
            wp_enqueue_script('bootstrap', BR_TOOLS_URL.'assets/js/bootstrap.min.js','','',true);
        }
    }
    add_action('admin_print_styles', 'add_br_tools_styles'); // приклеем ф-ю на добавление скриптов в футер
    if (!function_exists('add_br_tools_styles')) { // если ф-я уже есть в дочерней теме - нам не надо её определять
        function add_br_tools_styles() { // добавление скриптов

        }
    }
}
br_tools_load();

register_activation_hook(__FILE__, 'br_tools_activation');
register_deactivation_hook(__FILE__, 'br_tools_deactivation');
 
function br_tools_activation() {
 
   

	// регистрируем действие при удалении
	register_uninstall_hook(__FILE__, 'br_tools_uninstall');
}
 
function br_tools_deactivation() {
    // при деактивации
}

function br_tools_uninstall(){
 
    //действие при удалении
}
require_once(BR_TOOLS_DIR.'includes/functions.php');