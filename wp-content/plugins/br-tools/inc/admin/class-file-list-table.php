<?php

namespace BR_Tools\Inc\Admin;
use BR_Tools\Inc\Libraries;

/**
 * Class for displaying registered WordPress Users
 * in a WordPress-like Admin Table with row actions to
 * perform user meta opeations
 *
 *
 * @link       http://nuancedesignstudio.in
 * @since      1.0.0
 *
 * @author     Karan NA Gupta
 */
class File_List_Table extends Libraries\WP_List_Table  {

    /**
     * The text domain of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_text_domain    The text domain of this plugin.
     */
    protected $plugin_text_domain;

    /*
	 * Call the parent constructor to override the defaults $args
	 *
	 * @param string $plugin_text_domain	Text domain of the plugin.
	 *
	 * @since 1.0.0
	 */
    public function __construct( $plugin_text_domain ) {

        $this->plugin_text_domain = $plugin_text_domain;

        parent::__construct( array(
            'plural'	=>	'cars',	// Plural value used for labels and the objects being listed.
            'singular'	=>	'car',		// Singular label for an object being listed, e.g. 'post'.
            'ajax'		=>	false,		// If true, the parent class will call the _js_vars() method in the footer
        ) );
    }

    /**
     * Prepares the list of items for displaying.
     *
     * Query, filter data, handle sorting, and pagination, and any other data-manipulation required prior to rendering
     *
     * @since   1.0.0
     */
    public function prepare_items() {

        // check if a search was performed.
        $user_search_key = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : '';

        $this->_column_headers = $this->get_column_info();

        // check and process any actions such as bulk actions.
        $this->handle_table_actions();

        // fetch table data
        $table_data = $this->fetch_table_data();

        // filter the data in case of a search.
        if( $user_search_key ) {
            $table_data = $this->filter_table_data( $table_data, $user_search_key );
        }

        // required for pagination
        $per_page = $this->get_items_per_page( 'per_page' );
        $table_page = $this->get_pagenum();

        // provide the ordered data to the List Table.
        // we need to manually slice the data based on the current pagination.
        $this->items = array_slice( $table_data, ( ( $table_page - 1 ) * $per_page ), $per_page );

        // set the pagination arguments
        $total = count( $table_data );
        $this->set_pagination_args( array (
            'total_items' => $total,
            'per_page'    => $per_page,
            'total_pages' => ceil( $total/$per_page )
        ) );
    }

    /**
     * Get a list of columns. The format is:
     * 'internal-name' => 'Title'
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function get_columns() {

        $table_columns = array(
            'cb'				=> '<input type="checkbox" />', // to display the checkbox.
            'name'		        =>	__( 'Имя', $this->plugin_text_domain ),
            'size'		        =>	__( 'Размер', $this->plugin_text_domain ),
            'lastmod'		=>	__( 'Обновлен Дата', $this->plugin_text_domain ),
            'time'		=>	__( 'Обновлено Время', $this->plugin_text_domain ),
        );

        return $table_columns;

    }

    /**
     * Get a list of sortable columns. The format is:
     * 'internal-name' => 'orderby'
     * or
     * 'internal-name' => array( 'orderby', true )
     *
     * The second format will make the initial sorting order be descending
     *
     * @since 1.1.0
     *
     * @return array
     */
    protected function get_sortable_columns() {

        /*
         * actual sorting still needs to be done by prepare_items.
         * specify which columns should have the sort icon.
         *
         * key => value
         * column name_in_list_table => columnname in the db
         */
        $sortable_columns = array (
            'name'=>'name',
            'size'=>'size',
            'lastmod'=>'lastmod',
            'time'=>'time',
        );

        return $sortable_columns;
    }

    /**
     * Text displayed when no user data is available
     *
     * @since   1.0.0
     *
     * @return void
     */
    public function no_items() {
        _e( 'No cars avaliable.', $this->plugin_text_domain );
    }

    /*
     * Fetch table data from the WordPress database.
     *
     * @since 1.0.0
     *
     * @return	Array
     */

    public function fetch_table_data() {

        global $wp_filesystem;
        // Initialize the WP filesystem, no more using 'file-put-contents' function
        if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }
        $files = $wp_filesystem->dirlist( PLUGIN_NAME_DIR.'assets/catalog-files' );

        return $files;
    }

    /*
     * Filter the table data based on the user search key
     *
     * @since 1.0.0
     *
     * @param array $table_data
     * @param string $search_key
     * @returns array
     */
    public function filter_table_data( $table_data, $search_key ) {
        $filtered_table_data = array_values( array_filter( $table_data, function( $row ) use( $search_key ) {
            foreach( $row as $row_val ) {
                if( stripos( $row_val, $search_key ) !== false ) {
                    return true;
                }
            }
        } ) );

        return $filtered_table_data;

    }

    /**
     * Render a column when no column specific method exists.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default( $item, $column_name ) {

        switch ( $column_name ) {
            case 'id':
                return $item[$column_name];
            default:
                return $item[$column_name];
        }
    }

    /**
     * Get value for checkbox column.
     *
     * The special 'cb' column
     *
     * @param object $item A row's data
     * @return string Text to be placed inside the column <td>.
     */
    protected function column_cb( $item ) {
        return sprintf(
            '<label class="screen-reader-text" for="rows_' . $item['id'] . '">' . sprintf( __( 'Select %s' ), $item['id'] ) . '</label>'
            . "<input type='checkbox' name='rows[]' id='rows_{$item['id']}' value='{$item['id']}' />"
        );
    }


    /*
     * Method for rendering the user_login column.
     *
     * Adds row action links to the user_login column.
     *
     * @param object $item A singular item (one full row's worth of data).
     * @return string Text to be placed inside the column <td>.
     *
     */
    protected function column_name( $item ) {

        /*
         *  Build usermeta row actions.
         *
         * e.g. /users.php?page=nds-wp-list-table-demo&action=view_usermeta&user=18&_wpnonce=1984253e5e
         */

        $admin_page_url =  admin_url( 'admin.php' );


        // row actions to add usermeta.
        $query_args_delete = array(
            'page'		=>  wp_unslash( $_REQUEST['page'] ),
            'action'	=> 'delete',
            'name'		=>  $item['name'],
            '_wpnonce'	=> wp_create_nonce( 'delete_item_nonce' ),
        );
        $delete_link = esc_url( add_query_arg( $query_args_delete, $admin_page_url ) );
        $actions['delete'] = '<a href="' . $delete_link . '" style="color:#a00" target="_blank">' . __( 'Удалить', $this->plugin_text_domain ) . '</a>';


        $row_value = '<strong>' . $item['name'] . '</strong>';
        return $row_value . $this->row_actions( $actions );
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @since    1.0.0
     *
     * @return array
     */
    public function get_bulk_actions() {

        /*
         * on hitting apply in bulk actions the url paramas are set as
         * ?action=bulk-download&paged=1&action2=-1
         *
         * action and action2 are set based on the triggers above or below the table
         *
         */
        $actions = array(
            'bulk-download' => 'Download Usermeta'
        );

        return $actions;
    }

    /**
     * Process actions triggered by the user
     *
     * @since    1.0.0
     *
     */
    public function handle_table_actions() {

        /*
         * Note: Table bulk_actions can be identified by checking $_REQUEST['action'] and $_REQUEST['action2']
         *
         * action - is set if checkbox from top-most select-all is set, otherwise returns -1
         * action2 - is set if checkbox the bottom-most select-all checkbox is set, otherwise returns -1
         */

        // check for individual row actions
        $the_table_action = $this->current_action();

        if ( 'add_file' === $the_table_action ) {
            $this->page_add();
            $this->graceful_exit();
        }

        if ( 'save_file' === $the_table_action ) {
            $nonce = wp_unslash( $_REQUEST['_wpnonce'] );
            // verify the nonce.
            if ( ! wp_verify_nonce( $nonce, 'save_file_nonce' ) ) {
                $this->invalid_nonce_redirect();
            }
            else {
                $this->save_file();
                $this->graceful_exit();
            }
        }

        if ( 'delete' === $the_table_action ) {
            $this->page_del($_REQUEST['name']);
            $this->graceful_exit();
        }

        if ( 'delete_action' === $the_table_action ) {
            $nonce = wp_unslash( $_REQUEST['_wpnonce'] );
            // verify the nonce.
            if ( ! wp_verify_nonce( $nonce, 'delete_file_nonce' ) ) {
                $this->invalid_nonce_redirect();
            }
            else {
                $this->delete_action($_REQUEST['file']);
                $this->graceful_exit();
            }
        }
        if ( 'upload_from_files' === $the_table_action ) {
            $nonce = wp_unslash( $_REQUEST['_wpnonce'] );
            // verify the nonce.
            if ( ! wp_verify_nonce( $nonce, 'upload_from_files_nonce' ) ) {
                $this->invalid_nonce_redirect();
            }
            else {
                $this->upload_from_files();
                $this->graceful_exit();
            }
        }

        // check for table bulk actions
        if ( ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'bulk-download' ) || ( isset( $_REQUEST['action2'] ) && $_REQUEST['action2'] === 'bulk-download' ) ) {

            $nonce = wp_unslash( $_REQUEST['_wpnonce'] );
            // verify the nonce.
            /*
             * Note: the nonce field is set by the parent class
             * wp_nonce_field( 'bulk-' . $this->_args['plural'] );
             *
             */
            if ( ! wp_verify_nonce( $nonce, 'bulk-users' ) ) {
                $this->invalid_nonce_redirect();
            }
            else {
                $this->page_bulk_download( $_REQUEST['users']);
                $this->graceful_exit();
            }
        }

    }

    public function page_add() {
        $admin_page_url =  admin_url( 'admin.php' );
        $query_args_edit = array(
            'page'		=>  wp_unslash( $_REQUEST['page'] ),
            'action'	=> 'save_file',
            '_wpnonce'	=> wp_create_nonce( 'save_file_nonce' ),
        );

        $save_link = esc_url( add_query_arg( $query_args_edit, $admin_page_url ) );

        include_once( 'views/catalog/add-file.php' );
    }

    public function save_file() {

        global $wp_filesystem;
        // Initialize the WP filesystem, no more using 'file-put-contents' function
        if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }
        $dir = PLUGIN_NAME_DIR.'assets/catalog-files';

        // Create main folder within upload if not exist
        if(!$wp_filesystem->is_dir($dir) )
        {
            $wp_filesystem->mkdir( $dir );
        }
        $uploadedfiles = $_FILES['files'];
        $count=0;
        foreach ($_FILES['files']['name'] as $filename)
        {
            $source      = $_FILES['files']['tmp_name'][$count];
            $destination = trailingslashit( $dir ) . basename($filename);
            $fileSave =  move_uploaded_file( $source, $destination );
            $count++;
        }
        $admin_page_url =  admin_url( 'admin.php' );
        $query_args_edit = array(
            'page'		=>  wp_unslash( $_REQUEST['page'] ),
            'action'	=> 'save_file',
            '_wpnonce'	=> wp_create_nonce( 'save_file_nonce' ),
        );

        $save_link = esc_url( add_query_arg( $query_args_edit, $admin_page_url ) );

        include_once( 'views/catalog/add-file.php' );
    }

    public function page_del($file) {

        $admin_page_url =  admin_url( 'admin.php' );
        $query_args_edit = array(
            'page'		=>  wp_unslash( $_REQUEST['page'] ),
            'file'		=>  $file,
            'action'	=> 'delete_action',
            '_wpnonce'	=> wp_create_nonce( 'delete_file_nonce' ),
        );

        $form_link = esc_url( add_query_arg( $query_args_edit, $admin_page_url ) );

        include_once( 'views/catalog/del-file.php' );
    }

    public function delete_action($file) {

        $fileDel = unlink(PLUGIN_NAME_DIR.'assets/catalog-files/'.$file);


        include_once( 'views/catalog/del-success.php' );
    }
    public function upload_from_files() {
        include_once( 'views/catalog/upload.php' );
    }


    /**
     * Bulk process users.
     *
     * @since   1.0.0
     *
     * @param array $bulk_user_ids
     */
    public function page_bulk_download( $bulk_user_ids ) {

        include_once( 'views/partials-wp-list-table-demo-bulk-download.php' );
    }

    /**
     * Stop execution and exit
     *
     * @since    1.0.0
     *
     * @return void
     */
    public function graceful_exit() {
        exit;
    }

    /**
     * Die when the nonce check fails.
     *
     * @since    1.0.0
     *
     * @return void
     */
    public function invalid_nonce_redirect() {
        wp_die( __( 'Invalid Nonce', $this->plugin_text_domain ),
            __( 'Error', $this->plugin_text_domain ),
            array(
                'response' 	=> 403,
                'back_link' =>  esc_url( add_query_arg( array( 'page' => wp_unslash( $_REQUEST['page'] ) ) , admin_url( 'users.php' ) ) ),
            )
        );
    }


}
