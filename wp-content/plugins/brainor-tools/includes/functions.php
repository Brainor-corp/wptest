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
                    require_once(BR_TOOLS_DIR.'includes/templates/marks.php');
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

    $carsTable = 'cars';
    $query = $wpdb->prepare(
        "SELECT model, modification, id FROM $carsTable where $carsTable.brand = %s",
        $_POST['mark']
    );

    $models = $wpdb->get_results($query, OBJECT);
    $returnElements = '';

    foreach ($models as $key => $model):
        $returnElements .= '
            <div class="br-model-point"> 
                <input type="checkbox" value="' . $model->id . '" id="br-model-' . $key . '" name="br-model" class="br-model-checkbox br-tools-input">
                <label for="br-model-' . $key . '">' . $model->model . ' (' . $model->modification . ')</label>
            </div>
        ';
    endforeach;

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
            <div class="br-good-row">
                <div>
                    <span class="br-good-name">' . $good->art . '</span>
                </div>
                <div>
                    <span>' . $good->name . '</span>
                </div>
                <div>
                    <span> Производитель: ' . $good->brand . '</span>
                </div>
                <div>
                    <span> Доступно: ' . $good->quant . ' шт.</span>
                </div>
                <div>
                    <span> Город: ' . get_city_name($good->city) . '</span>
                </div>
                <div>
                    <span> Цена: ' . $good->price . ' руб. </span>
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

    $pivotTable = 'car_good';
    $modelsIdsStr = implode(',', $_POST['models']);
    $query = $wpdb->prepare(
        "SELECT good_id FROM $pivotTable WHERE $pivotTable.car_id IN ($modelsIdsStr)",
        null
    );
    $goodsIdsFromPivot = $wpdb->get_results($query, OBJECT);

    $goodsIdsArray = [];
    foreach ($goodsIdsFromPivot as $item):
        array_push($goodsIdsArray, $item->good_id);
    endforeach;

    $goodsTable = 'goods';
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
    $query = $wpdb->prepare(
        "SELECT * FROM $goodsTable WHERE ($goodsTable.art = %s OR $goodsTable.orgnl_id LIKE %s OR $goodsTable.cross LIKE %s OR $goodsTable.name LIKE %s)",
        [
            $params['code'],
            '% ; ' . $wpdb->esc_like($params['code']) . '%',
            '% ; ' . $wpdb->esc_like($params['code']) . '%',
            '% ; ' . $wpdb->esc_like($params['name'] . '%')
        ]
    );
    $goods = $wpdb->get_results($query, OBJECT);

//    if(!count($goods)) {
//        echo 'Товары не найдены';
//        wp_die();
//    }

    $returnElements = showProducts($goods);

    echo $returnElements;
    wp_die();
}

add_action('wp_ajax_br_tools_search_products', 'br_tools_search_products');
add_action('wp_ajax_nopriv_br_tools_search_products', 'br_tools_search_products');