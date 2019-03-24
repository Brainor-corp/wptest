<?php

namespace BR_Tools\Inc\Core;

/**
 * Fired during plugin activation
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @author     Your Name or Your Company
 **/
class Activator {

	/**
	 * Short Description.
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

			$min_php = '5.6.0';

		// Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
		if ( version_compare( PHP_VERSION, $min_php, '<' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( 'This plugin requires a minmum PHP Version of ' . $min_php );
		}

        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); # обращение к функциям wordpress для работы с БД
        //Create table
        $table_name = $wpdb->get_blog_prefix() . 'br_tools_cars';
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
        if($wpdb->get_var("SHOW TABLES LIKE ".$table_name."") != $table_name) { # если таблица настроек плагина еще не создана - создаём

            $sql = "CREATE TABLE {$table_name} (
            `id` INTEGER (20) NOT NULL AUTO_INCREMENT,
            `brand` VARCHAR (255),
            `series` VARCHAR (255),
            `model` VARCHAR (255),
            `body_type` VARCHAR (255),
            `modification` VARCHAR (255),
            `year_of_issue` VARCHAR (255),
            `created_at` TIMESTAMP,
            `updated_at` TIMESTAMP,
            UNIQUE KEY id (id)
        ){$charset_collate}";

            dbDelta($sql); # . создаём новую таблицу
        }
        //END --- Create table

        //Create table
        $table_name = $wpdb->get_blog_prefix() . 'br_tools_car_good';
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
        if($wpdb->get_var("SHOW TABLES LIKE ".$table_name."") != $table_name) { # если таблица настроек плагина еще не создана - создаём

            $sql = "CREATE TABLE {$table_name} (
            `car_id` INTEGER (20) NOT NULL,
            `good_id` INTEGER (20) NOT NULL
        ){$charset_collate}";

            dbDelta($sql); # . создаём новую таблицу
        }
        //END --- Create table

        //Create table
        $table_name = $wpdb->get_blog_prefix() . 'br_tools_car_product';
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
        if($wpdb->get_var("SHOW TABLES LIKE ".$table_name."") != $table_name) { # если таблица настроек плагина еще не создана - создаём

            $sql = "CREATE TABLE {$table_name} (
            `car_id` INTEGER (20) NOT NULL,
            `product_id` INTEGER (20) NOT NULL
        ){$charset_collate}";

            dbDelta($sql); # . создаём новую таблицу
        }
        //END --- Create table

        //Create table
        $table_name = $wpdb->get_blog_prefix() . 'br_tools_goods';
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
        if($wpdb->get_var("SHOW TABLES LIKE ".$table_name."") != $table_name) { # если таблица настроек плагина еще не создана - создаём

            $sql = "CREATE TABLE {$table_name} (
            `id` INTEGER (20) NOT NULL AUTO_INCREMENT,
            `product_id` VARCHAR (255),
            `br_id` VARCHAR (255),
            `name` VARCHAR (255),
            `brand` VARCHAR (255),
            `art` VARCHAR (255),
            `orgnl` TEXT,
            `orgnl_id` TEXT,
            `cross` TEXT,
            `quant` INTEGER (11),
            `price` DECIMAL (9,2),
            `city` VARCHAR (255),
            `created_at` TIMESTAMP,
            `updated_at` TIMESTAMP,
            UNIQUE KEY id (id)
        ){$charset_collate}";

            dbDelta($sql); # . создаём новую таблицу
        }
        //END --- Create table

        //Create table
        $table_name = $wpdb->get_blog_prefix() . 'br_tools_products';
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
        if($wpdb->get_var("SHOW TABLES LIKE ".$table_name."") != $table_name) { # если таблица настроек плагина еще не создана - создаём

            $sql = "CREATE TABLE {$table_name} (
            `id` INTEGER (20) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR (255),
            `barcode` VARCHAR (255),
            `oe_codes` TEXT,
            `producer_codes` TEXT,
            `models` TEXT,
            `url` VARCHAR (1024),
            `created_at` TIMESTAMP,
            `updated_at` TIMESTAMP,
            UNIQUE KEY id (id)
        ){$charset_collate}";

            dbDelta($sql); # . создаём новую таблицу
        }
        //END --- Create table
	}

}
