<?php

namespace BR_Tools\Inc\Admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @author    Your Name or Your Company
 */
class Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The text domain of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_text_domain    The text domain of this plugin.
	 */
	private $plugin_text_domain;

    /**
     * WP_List_Table object
     *
     * @since    1.0.0
     * @access   private
     * @var      goods_list_table    $goods_list_table
     */
    private $goods_list_table;

    /**
     * WP_List_Table object
     *
     * @since    1.0.0
     * @access   private
     * @var      cars_list_table    $cars_list_table
     */
    private $cars_list_table;

    /**
     * WP_List_Table object
     *
     * @since    1.0.0
     * @access   private
     * @var      file_list_table    $file_list_table
     */
    private $file_list_table;

    /**
	 * Initialize the class and set its properties.
	 *
	 * @since       1.0.0
	 * @param       string $plugin_name        The name of this plugin.
	 * @param       string $version            The version of this plugin.
	 * @param       string $plugin_text_domain The text domain of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_text_domain ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_text_domain = $plugin_text_domain;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-plugin-name-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/*
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'popper', plugin_dir_url( __FILE__ ) . 'js/popper.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'bootstrap', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Callback for the user sub-menu in define_admin_hooks() for class Init.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {
        //главная страница
        $main_page_hook = add_menu_page(
            'Товары', //page title
            $this->plugin_text_domain , //menu title
            'manage_options', //capability
            $this->plugin_name, //menu_slug,
            array( $this, 'load_user_list_table' )
        );
        if ( isset($_GET['page']) ) {
            if ($_GET['page'] == 'br-tools' ) {
                if ( isset($_GET['action']) ) {
                        wp_enqueue_style('bs', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version);

                        wp_enqueue_script('jquery_custom', 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js', '', '', false);
                }
            }
        }
        add_action( 'load-'.$main_page_hook, array( $this, 'load_user_list_table_screen_options' ) );
        //-- конец -- главная страница

        //машины
        $sub_page_1_hook = add_submenu_page(
            $this->plugin_name, //parent slug
            'Машины', //page title
            'Машины', //menu title
            'manage_options', //capability
            'br_tools_cars', //menu_slug,
            array( $this, 'load_cars_list_table' )
        );
        if ( isset($_GET['page']) ) {
            if ($_GET['page'] == 'br_tools_cars' ) {
                if ( isset($_GET['action']) ) {
                        wp_enqueue_style('bs', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version);

                        wp_enqueue_script('jquery_custom', 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js', '', '', false);
                }
            }
        }
        add_action( 'load-'.$sub_page_1_hook, array( $this, 'load_cars_list_table_screen_options' ) );
        //-- конец -- машины

        //Загрузка каталога
        $sub_page_2_hook = add_submenu_page(
            $this->plugin_name, //parent slug
            'Загрузка каталога', //page title
            'Загрузка каталога', //menu title
            'manage_options', //capability
            'br_tools_catalog_update', //menu_slug,
            array( $this, 'br_tools_catalog_update_page' )
        );
        if ( isset($_GET['page']) ) {
            if ($_GET['page'] == 'br_tools_catalog_update' ) {
                if ( isset($_GET['action']) ) {
                    wp_enqueue_style('bs', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version);

                    wp_enqueue_script('jquery_custom', 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js', '', '', false);
                }
            }
        }
        add_action( 'load-'.$sub_page_2_hook, array( $this, 'br_tools_catalog_update_page_screen_options' ) );
        //-- конец -- Загрузка каталога
    }

    /**
     * Screen options for the List Table
     *
     * Callback for the load-($page_hook_suffix)
     * Called when the plugin page is loaded
     *
     * @since    1.0.0
     */
    public function load_user_list_table_screen_options() {

        $arguments	=	array(
            'label'		=>	$this->plugin_text_domain ,
            'default'	=>	5,
            'option'	=>	'users_per_page'
        );

        add_screen_option( 'per_page', $arguments );

        // instantiate the User List Table
        $this->goods_list_table = new Goods_List_Table( $this->plugin_text_domain );

    }


    /*
     * Display the User List Table
     *
     * Callback for the add_users_page() in the add_plugin_admin_menu() method of this class.
     *
     * @since	1.0.0
     */
    public function load_user_list_table(){
        // query, filter, and sort the data
        $this->goods_list_table->prepare_items();

        // render the List Table
        include_once( 'views/goods/table-display.php' );
    }

    public function load_cars_list_table_screen_options() {

        $arguments	=	array(
            'label'		=>	$this->plugin_text_domain ,
            'default'	=>	5,
            'option'	=>	'users_per_page'
        );

        add_screen_option( 'per_page', $arguments );

        // instantiate the User List Table
        $this->cars_list_table = new Cars_List_Table( $this->plugin_text_domain );

    }
    public function load_cars_list_table(){
        // query, filter, and sort the data
        $this->cars_list_table->prepare_items();

        // render the List Table
        include_once( 'views/cars/table-display.php' );
    }


    public function br_tools_catalog_update_page_screen_options() {

        $arguments	=	array(
            'label'		=>	$this->plugin_text_domain ,
            'default'	=>	5,
            'option'	=>	'users_per_page'
        );

        add_screen_option( 'per_page', $arguments );

        // instantiate the User List Table
        $this->file_list_table = new File_List_Table( $this->plugin_text_domain );

    }
    public function br_tools_catalog_update_page(){
        // query, filter, and sort the data
        $this->file_list_table->prepare_items();

        // render the List Table
        include_once( 'views/catalog/file-list.php' );

    }
}
