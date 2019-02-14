<?php
/*
Машины
*/

?>
<div class="wrap">
    <h2><?php echo get_admin_page_title() ?></h2>

<?php

    $wp_list_table = new Links_List_Table();
    $wp_list_table->prepare_items();

    $wp_list_table->display();
    ?>
</div>

