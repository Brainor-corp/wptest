<?php
/*
ПОдключение пунктов меню и страниц админки
*/
add_action('admin_menu', function(){
    $mainPage = add_menu_page( 'Товары', 'Товары', 'manage_categories', 'br_tools_goods', 'br_tools_goods_page', '', 30 );
    if ( isset($_GET['page']) ) {
        if ($_GET['page'] == 'br_tools_goods' ) {
            if (!function_exists('add_br_tools_goods_page_styles')) { // если ф-я уже есть в дочерней теме - нам не надо её определять
                function add_br_tools_goods_page_styles()
                { // добавление стилей
                    wp_enqueue_style('bs2', BR_TOOLS_URL . 'assets/css/bootstrap.min.css'); // бутстрап;
                }

                add_action('admin_print_styles-' . $mainPage, 'add_br_tools_goods_page_styles');
            }
            if (!function_exists('add_br_tools_goods_page_scripts')) { // если ф-я уже есть в дочерней теме - нам не надо её определять
                function add_br_tools_goods_page_scripts()
                {
                    wp_enqueue_script('jquery_custom', 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js', '', '', false);
                }

                add_action('admin_enqueue_scripts', 'add_br_tools_goods_page_scripts');
            }
        }
    }
    //Страница Элементов гаданий
    $page = add_submenu_page( 'br_divination_list', 'Элементы гаданий', 'Элементы гаданий', 'manage_categories', 'br_tools_cars', 'br_tools_cars_page' );
    if ( isset($_GET['page']) ) {
        if ($_GET['page'] == 'br_tools_cars' ) {
            if (!function_exists('add_br_tools_cars_page_styles')) { // если ф-я уже есть в дочерней теме - нам не надо её определять
                function add_br_tools_cars_page_styles()
                { // добавление стилей
                    wp_enqueue_style('bs', BR_TOOLS_URL . 'assets/css/bootstrap.min.css'); // бутстрап
                }

                add_action('admin_print_styles-' . $page, 'add_br_tools_cars_page_styles');
            }
            if (!function_exists('add_br_tools_cars_page_scripts')) { // если ф-я уже есть в дочерней теме - нам не надо её определять
                function add_br_tools_cars_page_scripts()
                { // добавление стилей
                    wp_enqueue_script('jquery_custom', 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js', '', '', false);
                }

                add_action('admin_enqueue_scripts', 'add_br_tools_cars_page_scripts');
            }
        }
    }
} );

function br_tools_goods_page(){require_once(BR_TOOLS_DIR.'includes/admin/goods.php');}
function  br_tools_cars_page(){require_once(BR_TOOLS_DIR.'includes/admin/cars.php');}