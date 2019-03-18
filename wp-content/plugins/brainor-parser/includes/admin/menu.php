<?php
/*
ПОдключение пунктов меню и страниц админки
*/
add_action('admin_menu', function(){
    $mainPage = add_menu_page( 'Парсер', 'BR_PARSER', 'manage_categories', 'br_parser_main', 'br_parser_main_page', '', 30 );
	if ( isset($_GET['page']) ) {
        if ($_GET['page'] == 'br_parser_main' ) {
			if (!function_exists('add_br_parser_main_page_styles')) {
				function add_br_parser_main_page_styles() { // добавление стилей
					wp_enqueue_style('bs', BR_PARSER_URL . 'assets/css/bootstrap.min.css'); // бутстрап
				}
				add_action('admin_print_styles-'. $mainPage, 'add_br_parser_main_page_styles');
			}
			if (!function_exists('add_br_parser_main_page_scripts')) {
				function add_br_parser_main_page_scripts() { // добавление скриптов
					wp_enqueue_script('jquery_custom', 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js', '', '', false);
					}
				add_action('admin_enqueue_scripts', 'add_br_parser_main_page_scripts');
			}
		}
	}
    //Страница пакетов
    $page = add_submenu_page( 'br_parser_main', 'Результат парсинга', 'Результат парсинга', 'manage_categories', 'br_parser_parsing_result', 'br_parser_parsing_result_page' );
    if ( isset($_GET['page']) ) {
        if ($_GET['page'] == 'br_parser_parsing_result' ) {
            if (!function_exists('add_br_parser_parsing_result_page_styles')) {
                function add_br_parser_parsing_result_page_styles()
                { // добавление стилей
                    wp_enqueue_style('bs', BR_PARSER_URL . 'assets/css/bootstrap.min.css'); // бутстрап
                }

                add_action('admin_print_styles-' . $page, 'add_br_parser_parsing_result_page_styles');
            }
            if (!function_exists('add_br_parser_parsing_result_page_scripts')) {
                function add_br_parser_parsing_result_page_scripts()
                { // добавление стилей
                    wp_enqueue_script('jquery_custom', 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js', '', '', false);
                }

                add_action('admin_enqueue_scripts', 'add_br_parser_parsing_result_page_scripts');
            }
        }
    }
} );

function br_parser_main_page(){require_once(BR_PARSER_DIR.'includes/admin/main.php');}
function br_parser_parsing_result_page(){require_once(BR_PARSER_DIR.'includes/admin/parsing_result.php');}