<?php
add_action('init', 'do_output_buffer');
function do_output_buffer() { ob_start(); }

function requires() {
require_once( 'inc/taxonomy-images.php' );
require_once( 'inc/compositions.php' );
require_once( 'inc/energy.php' );
require_once( 'inc/encodes.php' );
//require_once( 'inc/rating.php' );
require_once( 'inc/custom-post-gallery.php' );
}
requires();

add_action('wp_print_styles', 'add_styles_br_parser_receipts'); // приклеем ф-ю на добавление стилей в хедер
if (!function_exists('add_styles_br_parser_receipts')) { // если ф-я уже есть в дочерней теме - нам не надо её определять

    function add_styles_br_parser_receipts() { // добавление стилей
        if(is_admin()) return false; // если мы в админке - ничего не делаем
        wp_enqueue_style( 'general', BR_PARSER_URL.'assets/css/general.css' ); // основные стили шаблона
    }
}



// создаем новую колонку
add_filter('manage_post_posts_columns', 'add_kbju_column', 4);
function add_kbju_column( $columns ){
    // удаляем колонку Автор
    //unset($columns['author']);

    // вставляем в нужное место - 3 - 3-я колонка
    $out = array();
    foreach($columns as $col=>$name){
        if(++$i==3)
            $out['kbju'] = 'КБЖУ';
        $out[$col] = $name;
    }

    return $out;
}
// заполняем колонку данными -  wp-admin/includes/class-wp-posts-list-table.php
add_filter('manage_post_posts_custom_column', 'fill_kbju_column', 5, 2);
function fill_kbju_column( $colname, $post_id ){
    if( $colname === 'kbju' ){
        $energy_time = get_post_meta( $post_id, 'energy_time', true );
        $energy_ccal = get_post_meta( $post_id, 'energy_ccal', true );
        $energy_portion = get_post_meta( $post_id, 'energy_portion', true );
        $energy_protein = get_post_meta( $post_id, 'energy_protein', true );
        $energy_fat = get_post_meta( $post_id, 'energy_fat', true );
        $energy_carbohydrates = get_post_meta( $post_id, 'energy_carbohydrates', true );

        echo ( $energy_time ) ? "Время приготовления {$energy_time} минут<br>" : '';
        echo ( $energy_ccal ) ? "{$energy_ccal} ккал<br>" : '';
        echo ( $energy_portion ) ? "{$energy_portion} порций<br>" : '';
        echo ( $energy_protein ) ? "Протеины: {$energy_protein}<br>" : '';
        echo ( $energy_fat ) ? "Жиры: {$energy_fat}<br>" : '';
        echo ( $energy_carbohydrates ) ? "Углеводы: {$energy_carbohydrates}<br>" : '';
    }
}
if( ! function_exists( 'mb_ucfirst' ) ) {
    function mb_ucfirst( $string, $enc = 'UTF-8' ) {
        return mb_strtoupper( mb_substr( $string, 0, 1, $enc ), $enc ) .
            mb_substr( $string, 1, mb_strlen( $string, $enc ), $enc );
    }
}
// Получаем обрезанное превью поста
function priprava_get_post_excerpt( $post_id ) {
    if ( has_excerpt( $post_id ) ) {
        $excerpt = wpautop( get_the_excerpt( $post_id ) );
    } else {
        $excerpt = priprava_content_preview( $post_id );
    }

    return $excerpt;
}
function priprava_get_post_gallery( $post_id ) {

    $attachments = get_posts( array(
        'post_type' => 'attachment',
        'posts_per_page' => -1,
        'post_parent' => $post_id,
//        'exclude'     => get_post_thumbnail_id($post_id)
    ) );
    $attachmentIds = '';
    foreach ($attachments as $key=>$attachment){
        if($key !== 0){$attachmentIds .=',';}
        $attachmentIds .= $attachment->ID;
    }
    $shortcode = '[gallery size="gallery-thumb" link="file" include="'.$attachmentIds.'"]';

    return ($shortcode);


}
function priprava_replace_attachment_thumbnail_url( $attachment_url ) {
    $tmpUrl = preg_replace('~-[0-9]+x[0-9]+(?=\..{2,6})~', '', $attachment_url );
    $attachment_id = attachment_url_to_postid( $tmpUrl );
    if ( $attachment_id ) {
        $attachment_url = wp_get_attachment_image_url( $attachment_id, 'full' );
    }
    return $attachment_url;
}
// Возвращает image из url и атрибутов
function priprava_url_to_img( $url, array $attrs = array() ) {
    $attributes = '';
    if ( ! empty( $attrs ) ) {
        foreach ($attrs as $k => $attr) {
            $attributes .= " {$k}='{$attr}'";
        }
    }
    return "<img src='{$url}'{$attributes}>";
}