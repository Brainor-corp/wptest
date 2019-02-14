<?php

//Tools templates include
function br_tools_output ($attributes,  $content = null ) {
    ob_start();

    $params = shortcode_atts( array( // в массиве укажите значения параметров по умолчанию
        'type' => 'list',
        'slug' => null,
    ), $attributes );
    if($params['type'] == 'list'){

    }
    elseif ($params['type'] == 'single'){
        if(null !== $params['slug']){
            switch ($params['slug']) {
                case "br-tools":
                    require_once( PLUGIN_NAME_DIR . 'inc/frontend/views/marks.php' );

                    break;
                default: echo '[tools_error: неизвестный слаг]';
            }
        }else{
            echo '[tools_error: не задан слаг]';
        }

    }

    return ob_get_clean();

}

add_shortcode ( 'br_tools', 'br_tools_output' );

//END----Tools templates include


/**
 * Получение моделей авто по маркам
 */
function br_tools_get_car_models() {
    global $wpdb;

    $carsTable = 'wp_br_tools_cars';
    $query = $wpdb->prepare(
        "SELECT model, modification, id FROM $carsTable where $carsTable.brand = %s GROUP BY(model)",
        $_POST['mark']
    );

    $models = $wpdb->get_results($query, OBJECT);

    $returnElements = '<div class="row">';

    foreach ($models as $key => $model):

        $returnElements .= '
        <div class="br-model-point col-xs-12 col-sm-6 col-lg-3 br-tools-mb-5"> 
            <input type="checkbox" value="' . $model->id . '<||>'.$_POST['mark'].'<||>'.$model->model.'" id="br-model-' . $key . '" name="br-model" class="br-model-checkbox br-tools-input">
            <label for="br-model-' . $key . '">' . $model->model . '</label>
        </div>
    ';
    endforeach;

    $returnElements .= '</div>';

    echo $returnElements;
    wp_die();
}

add_action('wp_ajax_br_tools_get_car_models', 'br_tools_get_car_models');
add_action('wp_ajax_nopriv_br_tools_get_car_models', 'br_tools_get_car_models');


/**
 * @param $goods
 * Строит HTML вывод списка подобранных товаров
 * @return string
 */
function showProducts($goods) {
    $returnElements = '<div class="br-wrapper">';

    foreach ($goods as $good):
        $returnElements .= '
            <div class="br-good-row row br-tools-mx-0">
                <div class="col-sm-4 col-xs-12">
                    <img class="br-tools-img" src="wp-content/plugins/br-tools/assets/images/' . $good->art . '.jpg" alt="">
                </div>
                <div class="col-sm-8 col-xs-12">
                    <div>
                        <span class="br-good-name"><strong>' . $good->art . '</strong></span>
                    </div>
                    <div>
                        <span><strong>' . $good->name . '</strong></span>
                    </div>
                    <div>
                        <span> Производитель: <strong>' . $good->brand . '</strong></span>
                    </div>
                    <div>
                        <span> Доступно: <strong>' . $good->quant . ' шт.</strong></span>
                    </div>
                    <div>
                        <span> Город: <strong>' . get_city_name($good->city) . '</strong></span>
                    </div>
                    <div>
                        <span> Цена: <strong>' . $good->price . ' руб. </strong> </span>
                    </div>
                    <div>
                        <span> <a data-fancybox="" data-src="#hidden-content" href="javascript:;" class="btn br-tools-modal-btn">Оставить заявку</a> </span>
                    </div>
                </div>
            </div>
        ';
    endforeach;

    $returnElements .= '</div>';

    return $returnElements;
}

/**
 * @param $code
 * Возвращает русское представление названия города по его коду
 * @return string
 */
function get_city_name($code) {
    $name = $code;

    switch ($code) {
        case 'msk': $name = 'Москва'; break;
        case 'spb': $name = 'Санкт Петербург'; break;
    }

    return $name;
}

/**
 * Получение продуктов
 */
function br_tools_get_products() {
    global $wpdb;

    $pivotTable = 'wp_br_tools_car_good';
    $carsTable = 'wp_br_tools_cars';
    $modelsArray = [];
    foreach ($_POST['models'] as $model){
        $modelData = explode('<||>', $model);
        $modelsArray['brands'][$modelData[1]]= $modelData[1];
        $modelsArray['models'][$modelData[2]]= $modelData[2];
    }
    $modelsStr = implode("','", $modelsArray['models']);
    $brandsStr = implode("','", $modelsArray['brands']);

    $query = $wpdb->prepare(
        "SELECT id FROM $carsTable WHERE $carsTable.brand IN ('$brandsStr') AND $carsTable.model IN ('$modelsStr')",
        null
    );
    $carsIds = $wpdb->get_results($query, ARRAY_N);
    $result = [];
    array_walk_recursive($carsIds, function ($item, $key) use (&$result) {
        $result[] = $item;
    });
    $carsIdsStr = implode("','", $result);
    $query = $wpdb->prepare(
        "SELECT good_id FROM $pivotTable WHERE $pivotTable.car_id IN ('$carsIdsStr')",
        null
    );
    $goodsIdsFromPivot = $wpdb->get_results($query, OBJECT);

    $goodsIdsArray = [];
    foreach ($goodsIdsFromPivot as $item):
        array_push($goodsIdsArray, $item->good_id);
    endforeach;

    $goodsTable = 'wp_br_tools_goods';
    $goodsIdsStr = implode(',', $goodsIdsArray);
    $query = $wpdb->prepare(
        "SELECT * FROM $goodsTable WHERE $goodsTable.id IN ($goodsIdsStr)",
        null
    );
    $goods = $wpdb->get_results($query, OBJECT);

    if(!count($goods)) {
        echo 'Товары не найдены';
        wp_die();
    }

    $returnElements = showProducts($goods);

    echo $returnElements;
    wp_die();
}

add_action('wp_ajax_br_tools_get_products', 'br_tools_get_products');
add_action('wp_ajax_nopriv_br_tools_get_products', 'br_tools_get_products');

/**
 * Поиск продуктов по коду или названию
 */
function br_tools_search_products() {
    global $wpdb;

    parse_str($_POST['params'], $params);

    $goodsTable = 'goods';
    $code = empty(trim($params['code'])) ? 'empty_code' : trim($params['code']);
    $name = empty(trim($params['name'])) ? 'empty_name' : trim($params['name']);
    $query = $wpdb->prepare(
        "SELECT * FROM $goodsTable WHERE ($goodsTable.art = %s OR ($goodsTable.orgnl_id LIKE %s OR $goodsTable.orgnl_id LIKE %s OR $goodsTable.orgnl_id LIKE %s) OR ($goodsTable.cross LIKE %s OR $goodsTable.cross LIKE %s OR $goodsTable.cross LIKE %s) OR $goodsTable.name LIKE %s)",
        [
            $code,
            '% ; ' . $wpdb->esc_like($code) . '%',
            '%' . $wpdb->esc_like($code) . ' ; %',
            '%' . $wpdb->esc_like($code) . '%',
            '% ; ' . $wpdb->esc_like($code) . '%',
            '%' . $wpdb->esc_like($code) . ' ; %',
            '%' . $wpdb->esc_like($code) . '%',
            '%' . $wpdb->esc_like($name) . '%'
        ]
    );
    $goods = $wpdb->get_results($query, OBJECT);

    if(!count($goods)) {
        echo 'Товары не найдены';
        wp_die();
    }

    $returnElements = showProducts($goods);

    echo $returnElements;
    wp_die();
}

add_action('wp_ajax_br_tools_search_products', 'br_tools_search_products');
add_action('wp_ajax_nopriv_br_tools_search_products', 'br_tools_search_products');