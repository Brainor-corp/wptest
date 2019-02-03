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
                case "first":
                    //require_once(BR_TOOLS_DIR.'includes/templates/');
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