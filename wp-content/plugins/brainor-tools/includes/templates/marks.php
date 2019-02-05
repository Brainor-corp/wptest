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

    <div class="tab">
        <button class="tablinks active" data-tab-id="br-tools-params-tab">Подбор по параметрам</button>
        <button class="tablinks" data-tab-id="br-tools-search-tab">Поиск по коду или названию</button>
    </div>

    <div id="br-tools-params-tab" class="tabcontent row br-tools-mx-0 active">
        <!--  Список марок авто -->
        <div class="col-xs-12">
            <strong>Марка</strong>
        </div>
        <div class="br-marks col-xs-12">
            <div class="row">
                <?php foreach ($cars as $key => $car): ?>
                    <div class="mark-point col-xs-12 col-sm-6 col-lg-3">
                        <input type="radio" class="br-mark-radio br-tools-input" value="<?php echo $car->brand ?>" id="br-mark-<?php echo $key ?>" name="br-mark">
                        <label for="br-mark-<?php echo $key ?>"><?php echo $car->brand ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!--  Список моделей авто (подгружается аяксом при выборе марки) -->
        <div class="col-xs-12 br-tools-mt-10">
            <strong>Модели</strong>
        </div>
        <div class="br-models col-xs-12">
            <small>Выберите марку</small>
        </div>
    </div>

    <div id="br-tools-search-tab" class="tabcontent">
        <div class="br-search-block">
            <form action="" id="br-search-form">
                <div class="br-form-group">
                    <label for="br-search-name">Название</label>
                    <input type="text" class="br-tools-input" id="br-search-name" name="name">
                </div>
                <div class="br-form-group">
                    <label for="br-search-code">Код</label>
                    <input type="text" class="br-tools-input" id="br-search-code" name="code">
                </div>
                <button type="submit" class="br-tools-search">Показать</button>
            </form>
        </div>
    </div>
    
    <!--  Список подобранных комплектаций (подгружается аяксом при моделей) -->
    <div class="br-products row br-tools-mx-0 br-tools-mt-10"></div>

    <div style="display: none;" id="br-product-order-modal">
        <form action="http://wptest.local/wp-content/themes/rejki/mail.php" method="post">
            Форма
        </form>
    </div>
</div>
