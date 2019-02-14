<?php

/**
 * The admin area of the plugin to load the User List Table
 */
?>
<br>
<a href="<?php echo esc_url( add_query_arg( array( 'page' => wp_unslash( $_REQUEST['page'] ) ) , admin_url( 'admin.php' ) ) ); ?>"><?php _e( '<-- Назад', $this->plugin_text_domain ) ?></a>
<div class="wrap">    
    <h2>Добавить файл</h2>
        <?php if(isset($fileSave)): ?>
            <div class="alert alert-success" role="alert">
                Файлы успешно сохранены
            </div>
        <?php endif; ?>
        <div id="nds-wp-list-table-demo">			
            <div id="nds-post-body">
                <form action="<?php echo $save_link; ?>" method="post" enctype="multipart/form-data">
                    <input type="file" name="files[]" multiple="multiple">
                    <button type="submit" class="btn btn-success">Сохранить</button>
                </form>
            </div>			
        </div>
</div>