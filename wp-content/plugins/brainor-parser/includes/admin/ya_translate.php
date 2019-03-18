<?php
function yaTranslateDefault($text){
    $data = array(
        'key' => 'trnsl.1.1.20190116T071507Z.079688fef0befa5f.cb6d0e60e003fd1ae303450474c7a18e36c0a71f',
        'lang' => 'en-ru',
        'format' => 'plain',
        'options' => 1,
        'text' => $text
    );

    $curlObject = curl_init();

    curl_setopt($curlObject, CURLOPT_URL, 'https://translate.yandex.net/api/v1.5/tr.json/translate');

    curl_setopt($curlObject, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curlObject, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt($curlObject, CURLOPT_POST, true);
    curl_setopt($curlObject, CURLOPT_POSTFIELDS, http_build_query($data,'','&'));

    curl_setopt($curlObject, CURLOPT_RETURNTRANSFER, true);

    $responseData = curl_exec($curlObject);

    curl_close($curlObject);

    if ($responseData === false) {
        return 'Ошибка обращения к яндекс переводчику';
    }else{
        return json_decode($responseData, true);
    }
}


function yaTranslate($args){
    $data = array(
        'key' => $args['key'],
        'text' => $args['text'],
        'lang' => $args['lang'],
        'format' => $args['format'],
        'options' => $args['options'],
    );

    $curlObject = curl_init();

    curl_setopt($curlObject, CURLOPT_URL, 'https://translate.yandex.net/api/v1.5/tr.json/translate');

    curl_setopt($curlObject, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curlObject, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt($curlObject, CURLOPT_POST, true);
    curl_setopt($curlObject, CURLOPT_POSTFIELDS, http_build_query($data,'','&'));

    curl_setopt($curlObject, CURLOPT_RETURNTRANSFER, true);

    $responseData = curl_exec($curlObject);

    curl_close($curlObject);

    if ($responseData === false) {
        return 'Ошибка обращения к яндекс переводчику';
    }else{
        return json_decode($responseData, true);
    }
}