<?php

/**
 * The admin area of the plugin to load the User List Table
 */
?>
<br>
<a href="<?php echo esc_url( add_query_arg( array( 'page' => wp_unslash( $_REQUEST['page'] ) ) , admin_url( 'admin.php' ) ) ); ?>"><?php _e( '<-- Назад', $this->plugin_text_domain ) ?></a>
<div class="wrap">    
    <h2>Машины</h2>
        <div id="nds-wp-list-table-demo">			
            <div id="nds-post-body">
                <form action="<?php echo $save_link; ?>" method="post">
                    <div class="form-group">
                        <label for="brand">Марка</label>
                        <input type="text" class="form-control" id="brand" name="brand" placeholder="Название" <?php if(isset($data['brand'])){ echo "value='$data[brand]'"; }?>>
                    </div>
                    <div class="form-group">
                        <label for="model">Модель</label>
                        <input type="text" class="form-control" id="model" name="model" placeholder="Модель" <?php if(isset($data['model'])){ echo "value='$data[model]'"; }?>>
                    </div>
                    <div class="form-group">
                        <label for="series">Серия</label>
                        <input type="text" class="form-control" id="series" name="series" placeholder="Серия" <?php if(isset($data['series'])){ echo "value='$data[series]'"; }?>>
                    </div>
                    <div class="form-group">
                        <label for="body_type">Тип кузова</label>
                        <input type="text" class="form-control" id="body_type" name="body_type" placeholder="Тип кузова" <?php if(isset($data['body_type'])){ echo "value='$data[body_type]'"; }?>>
                    </div>
                    <div class="form-group">
                        <label for="modification">Модификация</label>
                        <input type="text" class="form-control" id="modification" name="modification" placeholder="Модификация" <?php if(isset($data['modification'])){ echo "value='$data[modification]'"; }?>>
                    </div>
                    <div class="form-group">
                        <label for="year_of_issue">Год выпуска</label>
                        <input type="text" class="form-control" id="year_of_issue" name="year_of_issue" placeholder="Год выпуска" <?php if(isset($data['year_of_issue'])){ echo "value='$data[year_of_issue]'"; }?>>
                    </div>
                    <button type="submit" class="btn btn-success">Сохранить</button>
                </form>
            </div>			
        </div>
</div>