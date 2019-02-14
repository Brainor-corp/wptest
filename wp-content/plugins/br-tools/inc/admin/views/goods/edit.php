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
                        <label for="product_id">Ассоц.</label>
                        <input type="text" class="form-control" id="product_id" name="product_id" placeholder="Ассоц" <?php if(isset($data['product_id'])){ echo "value='$data[product_id]'"; }?>>
                    </div>
                    <div class="form-group">
                        <label for="br_id">br_id</label>
                        <input type="text" class="form-control" id="br_id" name="br_id" placeholder="br_id" <?php if(isset($data['br_id'])){ echo "value='$data[br_id]'"; }?>>
                    </div>
                    <div class="form-group">
                        <label for="name">Название</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Название" <?php if(isset($data['name'])){ echo "value='$data[name]'"; }?>>
                    </div>
                    <div class="form-group">
                        <label for="brand">Марка</label>
                        <input type="text" class="form-control" id="brand" name="brand" placeholder="Марка" <?php if(isset($data['brand'])){ echo "value='$data[brand]'"; }?>>
                    </div>
                    <div class="form-group">
                        <label for="art">Артикул</label>
                        <input type="text" class="form-control" id="art" name="art" placeholder="Артикул" <?php if(isset($data['art'])){ echo "value='$data[art]'"; }?>>
                    </div>
                    <div class="form-group">
                        <label for="orgnl">orgnl</label>
                        <textarea type="text" class="form-control" id="orgnl" name="orgnl" placeholder="orgnl" >
                            <?php if(isset($data['orgnl'])){ echo $data['orgnl']; }?>
                        </textarea>
                    </div>
                    <div class="form-group">
                        <label for="orgnl_id">orgnl_id</label>
                        <textarea type="text" class="form-control" id="orgnl_id" name="orgnl_id" placeholder="orgnl_id" >
                            <?php if(isset($data['orgnl_id'])){ echo $data['orgnl_id']; }?>
                        </textarea>
                    </div>
                    <div class="form-group">
                        <label for="cross">cross</label>
                        <textarea type="text" class="form-control" id="cross" name="cross" placeholder="cross" >
                            <?php if(isset($data['cross'])){ echo $data['cross']; }?>
                        </textarea>
                    </div>
                    <div class="form-group">
                        <label for="quant">Кол-во</label>
                        <input type="text" class="form-control" id="quant" name="quant" placeholder="Кол-во" <?php if(isset($data['quant'])){ echo "value='$data[quant]'"; }?>>
                    </div>
                    <div class="form-group">
                        <label for="price">Цена</label>
                        <input type="text" class="form-control" id="price" name="price" placeholder="Цена" <?php if(isset($data['price'])){ echo "value='$data[price]'"; }?>>
                    </div>
                    <div class="form-group">
                        <label for="city">Город</label>
                        <input type="text" class="form-control" id="city" name="city" placeholder="Город" <?php if(isset($data['city'])){ echo "value='$data[city]'"; }?>>
                    </div>
                    <button type="submit" class="btn btn-success">Сохранить</button>
                </form>
            </div>			
        </div>
</div>