<?php

/**
 * The admin area of the plugin to load the User List Table
 */
?>
<br>
<a href="<?php echo esc_url( add_query_arg( array( 'page' => wp_unslash( $_REQUEST['page'] ) ) , admin_url( 'admin.php' ) ) ); ?>"><?php _e( '<-- Назад', $this->plugin_text_domain ) ?></a>
<div class="wrap">    
    <h2>Добавить файл</h2>
        <?php if(isset($fileDel)): ?>
            <div class="alert alert-success" role="alert">
                Файлы успешно удалены
            </div>
        <?php endif; ?>
        <div id="nds-wp-list-table-demo">
            <div id="nds-post-body">
                <form action="<?php echo $form_link; ?>" method="post" enctype="multipart/form-data">
                    <p>Удалить файл <?php echo $file; ?>?</p>
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
            </div>
        </div>
</div>