<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 04.02.2019
 * Time: 8:57
 */
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
    if(!document.getElementById('br-tools-general-css')) {
        let style = document.createElement( 'link' );
        style.setAttribute('id', 'br-tools-general-css');
        style.setAttribute('rel', 'stylesheet');
        style.setAttribute('type', 'text/css');
        style.setAttribute('href', '/wp-content/plugins/brainor-tools/assets/css/br-tools-general.css');

        document.getElementsByTagName( 'head' )[ 0 ].appendChild(style);
    }

    if(!document.getElementById('br-tools-general-js')) {
        let script = document.createElement( 'script' );
        script.src = '/wp-content/plugins/brainor-tools/assets/js/br-tools-general.js';
        script.id = 'br-tools-general-js';
        script.type = 'text/javascript';
        document.getElementsByTagName( 'head' )[ 0 ].appendChild(script);
    }
</script>

<?php
    global $wpdb;
    $carsTable = 'cars';

    $cars = $wpdb->get_results('
        SELECT DISTINCT(brand) FROM ' . $carsTable . '
    ', OBJECT);
?>

<div class="br-tools">
    <input type="hidden" name="br-wp-admin-ajax-url" value="<?php echo admin_url('admin-ajax.php'); ?>">

    <!--  Список марок авто -->
    <div class="br-marks">
        <?php foreach ($cars as $key => $car): ?>
            <div class="mark-point">
                <input type="radio" class="br-mark-radio br-tools-input" value="<?php echo $car->brand ?>" id="br-mark-<?php echo $key ?>" name="br-mark">
                <label for="br-mark-<?php echo $key ?>"><?php echo $car->brand ?></label>
            </div>
        <?php endforeach; ?>
    </div>

    <!--  Список моделей авто (подгружается аяксом при выборе марки) -->
    <div class="br-models"></div>

    <!--  Список подобранных комплектаций (подгружается аяксом при моделей) -->
    <div class="br-products"></div>
</div>
