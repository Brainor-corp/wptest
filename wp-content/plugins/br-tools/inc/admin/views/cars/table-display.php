<?php

/**
 * The admin area of the plugin to load the User List Table
 */
?>

<div class="wrap">    
    <h2>Машины</h2>
        <div id="nds-wp-list-table-demo">			
            <div id="nds-post-body">		
				<form id="nds-user-list-form" method="get">
					<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
					<?php 
						$this->cars_list_table->search_box( __( 'Поиск'), 'nds-user-find');
						$this->cars_list_table->display();
					?>					
				</form>
            </div>			
        </div>
</div>