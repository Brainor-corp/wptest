<?php
/*
Услуги
*/

?>
<div class="wrap">
    <h2><?php echo get_admin_page_title() ?></h2>
    <form class="url-parsing-form" role="form" method="post" action="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=br_parser_parsing_result">
        <div class="form-group">
            <label for="url">URL</label>
            <textarea class="form-control" id="url" name="url" placeholder="Введите URL для парсинга"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Парсить</button>
    </form>
</div>

