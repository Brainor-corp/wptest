<?php

/**
 * The admin area of the plugin to load the User List Table
 */
?>

<div class="wrap">    
    <h2>Товары</h2>
        <a href="/wp-admin/admin.php?page=<?php echo $_REQUEST['page'] ?>&action=add_file"><button class="button">Добавить файл</button></a>
        <div id="nds-wp-list-table-demo">			
            <div id="nds-post-body">		
				<form id="nds-user-list-form" method="get">
					<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
					<?php 
						$this->file_list_table->search_box( __( 'Поиск'), 'nds-user-find');
						$this->file_list_table->display();
					?>					
				</form>
            </div>			
        </div>
    <div class="alert alert-warning" role="alert">
        <h3>ВНИМАНИЕ!!!</h3>
        <p>Будут обработаны все файлы формата .csv. Если в таблице есть лишние - удалите их</p>
        <p>Файл дожен иметь название вида {Произвольное название}_{префикс города}.csv</p>
        <p>Например: Price Msk_msk.csv</p>
        <p>Возможные префиксы городов:</p>
        <ol>
            <li>msk - Москва</li>
            <li>spb - Санкт-Петербург</li>
        </ol>
        <p>Файл должен иметь разделители полей - точка с запятой (;)</p>
        <p>Содержимое полей должно быть погружено в двойные кавычки ("пример")</p>
        <p>Первая строка файла - заголовок (названиея полей)</p>
        <p>Порядок полей:</p>
        <p>br_id;brand;art;orgnl;orgnl_id;name;cross;quant;price</p>
        <p>Кодировка файла - utf8</p>
    </div>
        <a href="/wp-admin/admin.php?page=<?php echo $_REQUEST['page'] ?>&action=upload_from_files&_wpnonce=<?php echo wp_create_nonce( 'upload_from_files_nonce' ); ?>"><button class="button">Загрузить данные из файлов</button></a>
</div>