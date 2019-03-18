<?php
/*
Пакеты
*/
?>
<div class="wrap">
    <h2><?php echo get_admin_page_title() ?></h2>

    <?php if(isset($result_message)): ?>
        <div class="alert alert-<?php echo $result_message['class']; ?> alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $result_message['content']; ?>
        </div>
    <?php endif; ?>

    <form class="url-parsing-form" role="form" method="post" action="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=br_parser_parsing_result">
        <div class="form-group">
            <label for="url">URL</label>
            <textarea class="form-control" id="url" name="url" placeholder="Введите URL для парсинга"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Парсить</button>
    </form>
    <?php
    if(isset($_POST['action'])){
        if($_POST['action'] == 'save'){
            $cur_user_id = get_current_user_id();

            $content = '<ol>';
            foreach ($_POST['stages'] as $stage){
                $content .= '<li>';
                $content .= $stage;
                $content .= '</li>';
            }
            $content .= '</ol>';
            $ingredients = '';
            $ingredientTags = [];
            foreach ($_POST['ingredients'] as $ingredient){
                $ingredients .= $ingredient['all'] .PHP_EOL;
                $ingredientTags[] = $ingredient['name'];
            }
            $receiptsCat    = get_term_by( 'slug', 'receipts', 'category' );
            $post_id = wp_insert_post( array(
                'post_status'   => 'publish',
                'post_type'     => 'post',
                'post_author'   => $cur_user_id,
                'ping_status'   => 'open',
                'post_title'    => $_POST['title'],
                'post_excerpt'  => $_POST['subtitle'],
                'post_content'  => $content,
                'post_category' => [$receiptsCat->term_id],
                'meta_input'    => array(
                    'post_composition'=>$ingredients,
                    'energy_prep_time'=>$_POST['prepTime'],
                    'energy_cook_time'=>$_POST['cookTime'],
                    'energy_ready_in_time'=>$_POST['readyInTime'],
                    'energy_ccal'=>$_POST['cals'],
                    'energy_portion'=>$_POST['serving'],
                    'energy_protein'=>$_POST['nutrition']['proteinContent']['value'],
                    'energy_fat'=>$_POST['nutrition']['fatContent']['value'],
                    'energy_carbohydrates'=>$_POST['nutrition']['carbohydrateContent']['value'],
                ),
            ),true );

            $tagsSave = wp_set_post_tags( $post_id, $ingredientTags, true );

            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
//
            $slug = sanitize_title($_POST['title']);
            $galleryArray=[];
            foreach ($_POST['photos'] as $key=>$foto){
                if($foto['checked'] == true){
                    if(preg_match('/\.(?:jp(?:e?g|e|2)|gif|png|tiff?|bmp|ico)$/i',$foto['photo'])){//если это фотка
                        $content = file_get_contents($foto['photo']);//считываем фотку
                        $extension = substr(strrchr($foto['photo'], '.'), 1);//получаем расширение файла
                        //копируем файл в папку загрузок
                        $new_file_name = $slug.'-'.$key.'.'.$extension;//формируем название нового файла
                        $upload = wp_upload_bits( $new_file_name, null, $content );//загружаем в папку вп
                        //добавляем инфо о файле в БД
                        $fileType = wp_check_filetype( basename( $upload['file'] ), null );//получаем mime тип файла
                        $wp_upload_dir = wp_upload_dir();//путь до папки с загрузками вп
                        //Задаем парметры для фотки для добавления в БД
                        $attachment = array(
                            'guid'           => $wp_upload_dir['url'] . '/' . basename( $upload['file'] ),
                            'post_mime_type' => $fileType['type'],
                            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $upload['file'] ) ),
                            'post_content'   => '',
                            'post_status'    => 'inherit'
                        );
                        //добавляем данные о фото в БД
                        $attach_id = wp_insert_attachment( $attachment, $upload['file'], $post_id, false );
                        // Создадим метаданные для вложения и обновим запись в базе данных.
                        $attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
                        wp_update_attachment_metadata( $attach_id, $attach_data );

                        if($key == 0){//если это первая фотка - задаем миниатюру
                            set_post_thumbnail($post_id, $attach_id);
                        }
    //                    else{//остальные фигачим в галлрею
                        $galleryArray[] = $attachment['guid'];
    //                    }
                    }
                }
            }
            if ( ! empty( $galleryArray ) ) {
                update_post_meta( $post_id, 'multiupload', $galleryArray );
            }

        }
    }
    ?>
    <?php
    if(isset($_POST['url'])):
	
        include_once('simple_html_dom.php');
		//var_dump('11');
        include_once('ya_translate.php');
		
        $translateArgs = [
            'key' => 'trnsl.1.1.20190116T071507Z.079688fef0befa5f.cb6d0e60e003fd1ae303450474c7a18e36c0a71f',
            'lang' => 'ru',
            'format' => 'html',
            'options' => 1,
        ];

        function rounding($int){

            $int = round($int);
            $count = strlen((string)$int);

            if($count == 0){
                $int = 1;
            }
            elseif($count == 1){
            }
            elseif($count == 2 OR $count == 3){
                $int = (round($int/10)*10);
            }
            elseif($count > 3){
                $int = (round($int/100)*100);
            }

            return $int;
        }

        function stringPrepare($string){

            $replacePattern = ['/\A([^A-Za-zА-Яа-я0-9.,:;()]*)(?=[[A-Za-z0-9-])/mui','/([^A-Za-zА-Яа-я0-9.,:;()]*)(?=\Z)/mui'];
            $string = preg_replace($replacePattern,'', $string);
            $string = trim($string);

            return $string;
        }
        $html = file_get_html($_POST['url']);
		
        $parsing_result = [];


        $parsing_result['title']['source'] = stringPrepare($html->find('h1[id=recipe-main-content]')[0]->plaintext);
        $parsing_result['title']['ru'] = yaTranslateDefault($parsing_result['title']['source'])['text'][0];

        $parsing_result['subtitle']['source'] = trim(stringPrepare($html->find('div[class=submitter__description]')[0]->plaintext),'&#34;" /r/n/t');
        $parsing_result['subtitle']['ru'] = yaTranslateDefault($parsing_result['subtitle']['source'])['text'][0];

        $ingredients = $html->find('span[itemprop=recipeIngredient]');

        foreach ($ingredients as $key=>$ingredient){
            if(!strpos(':', $ingredient)){
                $ingredientData = stringPrepare($ingredient->plaintext);

                preg_match('/((\d+\/\d+|\d+)\s(\(.*\)|\S*))\s(.*)/', $ingredientData, $matches);
                if(count($matches)<1){
                    preg_match('/((\d+\/\d+|\d+)(\(.*\)|\S*))\s(.*)/', $ingredientData, $matches);
                }
                if(count($matches)>0) {
                    if (preg_match('/(ounce)/ui', $matches[3]) OR preg_match('/(pound)/ui', $matches[3])) {
                        if (preg_match('/(ounce)/ui', $matches[3])) {
                            $multiplication = 28.35;
                        }
                        if (preg_match('/(pound)/ui', $matches[3])) {
                            $multiplication = 453.592;
                        }
                        $subMatches = explode(' ', $matches[3]);
                        if (count($subMatches) > 1) {
                            $before = '';
                            $after = '';
                            if (preg_match('/(\()/ui', $subMatches[0])) {
                                $before = '(';
                                $after = ')';
                            }
                            $grams = (float)trim($subMatches[0], '()') * $multiplication;
                            $rounded = rounding($grams);
                            $matches[3] = $before . $rounded . ' г.' . $after;
                        } else {
                            if (!is_numeric($matches[2])) {
                                switch ($matches[2]) {
                                    case '1/5':
                                        $matches[2] = 0.2;
                                        break;
                                    case '1/4':
                                        $matches[2] = 0.25;
                                        break;
                                    case '1/3':
                                        $matches[2] = 0.33;
                                        break;
                                    case '1/2':
                                        $matches[2] = 0.5;
                                        break;
                                    case '2/3':
                                        $matches[2] = 0.66;
                                        break;
                                    case '3/4':
                                        $matches[2] = 0.75;
                                        break;
                                    default:
                                        $matches[2] = 1;
                                }
                            }
                            $grams = (float)trim($matches[2], '()') * $multiplication;
                            $matches[2] = rounding($grams);
                            $matches[3] = ' г.';
                        }
                    }
                    $parsing_result['ingredients'][$key]['source'] = $ingredientData;
                    $resultIngredient = $matches[4] . ':' . $matches[2] . ' ' . $matches[3];
                    $parsing_result['ingredients'][$key]['all']['ru'] = yaTranslateDefault($resultIngredient)['text'][0];
                    $parsing_result['ingredients'][$key]['name']['ru'] = yaTranslateDefault($matches[4])['text'][0];
                }
            }
        }

        $stages = $html->find('span[class=recipe-directions__list--item]');
        foreach ($stages as $key=>$stage){
            $content = $stage->plaintext;
            if($content != ''){
                $parsing_result['stages'][$key]['source'] = stringPrepare($stage->plaintext);
                $parsing_result['stages'][$key]['ru'] = yaTranslateDefault($parsing_result['stages'][$key]['source'])['text'][0];
            }
        }
        //Times
        $parsing_result['prepTime']['source'] = stringPrepare($html->find('time[itemprop=prepTime] span span')[0]->plaintext);
        $timeParts = explode(' ', $parsing_result['prepTime']['source']);
        if($timeParts[1] == 'h'){
            $time = ($timeParts[0] * 60) + $timeParts[2];
        }else{
            $time = $timeParts[0];
        }
        $parsing_result['prepTime']['ru'] = $time;

        $parsing_result['cookTime']['source'] = stringPrepare($html->find('time[itemprop=cookTime] span span')[0]->plaintext);
        $timeParts = explode(' ', $parsing_result['cookTime']['source']);
        if($timeParts[1] == 'h'){
            $time = ($timeParts[0] * 60) + $timeParts[2];
        }else{
            $time = $timeParts[0];
        }
        $parsing_result['cookTime']['ru'] = $time;

        $parsing_result['readyInTime']['source'] = stringPrepare($html->find('time[itemprop=totalTime] span span')[0]->plaintext);
        $timeParts = explode(' ', $parsing_result['readyInTime']['source']);
        if($timeParts[1] == 'h'){
            $time = ($timeParts[0] * 60) + $timeParts[2];
        }else{
            $time = $timeParts[0];
        }
        $parsing_result['readyInTime']['ru'] = $time;
        //Times END
        $parsing_result['serving']['source'] = stringPrepare($html->find('input[name=servings]')[0]->{'data-original'});
        $parsing_result['serving']['ru'] = $parsing_result['serving']['source'];

        $parsing_result['cals']['source'] = stringPrepare($html->find('span[class=calorie-count]')[0]->plaintext);
        $parsing_result['cals']['ru'] = stringPrepare(trim($parsing_result['cals']['source'],'cals'));

        $nutritionWrapper = $html->find('div[class=nutrition-summary-facts]')[0];
        $nutritionFacts = $nutritionWrapper->find('span');

        $pattern = '/(.*?[a-z]{1})([A-Z]{1}.*?)/';
        $replace = '${1} ${2}';
        foreach ($nutritionFacts as $nutritionFact){
            if(isset($nutritionFact->itemprop)){
                $parsing_result['nutrition'][$nutritionFact->itemprop]['key']['source']=stringPrepare(preg_replace($pattern, $replace, $nutritionFact->itemprop));
                $parsing_result['nutrition'][$nutritionFact->itemprop]['key']['ru'] = yaTranslateDefault($parsing_result['nutrition'][$nutritionFact->itemprop]['key']['source'])['text'][0];
                $name = $nutritionFact->find('span')[0];
                if(isset($name->{'aria-label'})){
                    $parsing_result['nutrition'][$nutritionFact->itemprop]['name']['source']=stringPrepare($name->{'aria-label'});
                    $parsing_result['nutrition'][$nutritionFact->itemprop]['name']['ru'] = yaTranslateDefault($parsing_result['nutrition'][$nutritionFact->itemprop]['name']['source'])['text'][0];
                }
                $parsing_result['nutrition'][$nutritionFact->itemprop]['value']['source']=stringPrepare($nutritionFact->plaintext);
                $parsing_result['nutrition'][$nutritionFact->itemprop]['value']['ru'] = trim($parsing_result['nutrition'][$nutritionFact->itemprop]['value']['source'],'calories;');

            }
        }

        $photosWrapper = $html->find('ul[class=photo-strip__items]')[0];
        $photos = $photosWrapper->find('img');
        array_pop($photos);
        foreach ($photos as $key=>$photo){
//            if($key == 0){ continue;}
            $img = $photo->src;
            $imgUrlParts = explode('/',$img);
            $img = $imgUrlParts[0].'//'.$imgUrlParts[2].'/'.$imgUrlParts[3].'/'.$imgUrlParts[5];
            $parsing_result['photos'][] = $img;
        }
        ?>
        <form class="save-parsing-result-form" role="form" method="post" action="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=br_parser_parsing_result">
            <input type="hidden" hidden="hidden" id="action" name="action" value="save">

            <div class="form-group row">
                <label class="col-xs-12" for="title">Заголовок</label>
                <div class="col-xs-12 col-sm-6">
                    <div class="source-site-wrapper">
                        <span><?php echo $parsing_result['title']['source']; ?></span>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-save-wrapper">
                        <input type="text" class="form-control" id="title" name="title" <?php if(isset($parsing_result['title']['ru'])):?> value="<?php echo $parsing_result['title']['ru']; ?>" <?php endif;?> placeholder="Заголовок">
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-xs-12" for="title">Подзаголовок</label>
                <div class="col-xs-12 col-sm-6">
                    <div class="source-site-wrapper">
                        <span><?php echo $parsing_result['subtitle']['source']; ?></span>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-save-wrapper">
                        <textarea class="form-control" id="subtitle" name="subtitle" placeholder="Подзаголовок"><?php if(isset($parsing_result['subtitle']['ru'])):?><?php echo $parsing_result['subtitle']['ru']; ?><?php endif;?></textarea>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-xs-12" for="title">Время подготовки</label>
                <div class="col-xs-12 col-sm-6">
                    <div class="source-site-wrapper">
                        <span><?php echo $parsing_result['prepTime']['source']; ?></span>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-save-wrapper">
                        <input type="text" class="form-control" id="prepTime" name="prepTime" <?php if(isset($parsing_result['prepTime']['ru'])):?> value="<?php echo $parsing_result['prepTime']['ru']; ?>" <?php endif;?> placeholder="Время приготовления">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-xs-12" for="title">Время приготовления</label>
                <div class="col-xs-12 col-sm-6">
                    <div class="source-site-wrapper">
                        <span><?php echo $parsing_result['cookTime']['source']; ?></span>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-save-wrapper">
                        <input type="text" class="form-control" id="cookTime" name="cookTime" <?php if(isset($parsing_result['cookTime']['ru'])):?> value="<?php echo $parsing_result['cookTime']['ru']; ?>" <?php endif;?> placeholder="Время приготовления">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-xs-12" for="title">Общее время</label>
                <div class="col-xs-12 col-sm-6">
                    <div class="source-site-wrapper">
                        <span><?php echo $parsing_result['readyInTime']['source']; ?></span>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-save-wrapper">
                        <input type="text" class="form-control" id="readyInTime" name="readyInTime" <?php if(isset($parsing_result['readyInTime']['ru'])):?> value="<?php echo $parsing_result['readyInTime']['ru']; ?>" <?php endif;?> placeholder="Время приготовления">
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-xs-12" for="title">Кол-во порций</label>
                <div class="col-xs-12 col-sm-6">
                    <div class="source-site-wrapper">
                        <span><?php echo $parsing_result['serving']['source']; ?></span>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-save-wrapper">
                        <input type="text" class="form-control" id="serving" name="serving" <?php if(isset($parsing_result['serving']['ru'])):?> value="<?php echo $parsing_result['serving']['ru']; ?>" <?php endif;?> placeholder="Кол-во порций">
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-xs-12" for="title">Калории</label>
                <div class="col-xs-12 col-sm-6">
                    <div class="source-site-wrapper">
                        <span><?php echo $parsing_result['cals']['source']; ?></span>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-save-wrapper">
                        <input type="text" class="form-control" id="cals" name="cals" <?php if(isset($parsing_result['cals']['ru'])):?> value="<?php echo $parsing_result['cals']['ru']; ?>" <?php endif;?> placeholder="Калории">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-xs-12" for="title">Этапы приготовления</label>
                <?php foreach ($parsing_result['stages'] as $key=>$stage): ?>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="source-site-wrapper">
                                    <span><?php echo $stage['source']; ?></span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-save-wrapper">
                                    <textarea class="form-control" id="stages" name="stages[<?php echo $key; ?>]" placeholder="Этап"><?php if(isset($stage['ru'])):?><?php echo trim($stage['ru']); ?><?php endif;?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                <?php endforeach; ?>
            </div>

            <div class="form-group row">
                <label class="col-xs-12" for="ingredients">Ингредиенты</label>
                <?php foreach ($parsing_result['ingredients'] as $key=>$ingredient): ?>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="source-site-wrapper">
                                    <span><?php echo $ingredient['source']; ?></span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-save-wrapper">
                                    <input type="text" class="form-control" id="ingredients" name="ingredients[<?php echo $key; ?>][all]" <?php if(isset($ingredient['all']['ru'])):?> value="<?php echo $ingredient['all']['ru']; ?>" <?php endif;?> placeholder="Ингредиенты">
                                    <input type="hidden" hidden name="ingredients[<?php echo $key; ?>][name]" <?php if(isset($ingredient['name']['ru'])):?> value="<?php echo $ingredient['name']['ru']; ?>" <?php endif;?>>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                <?php endforeach; ?>
            </div>

            <div class="form-group row">
                <label class="col-xs-12" for="nutrition">Энергетическая ценность</label>
                <?php foreach ($parsing_result['nutrition'] as $key=>$nutrition): ?>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="source-site-wrapper">
                                    <span><?php echo $key; ?> | <?php echo $nutrition['name']['source']; ?> | <?php echo $nutrition['value']['source']; ?></span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-save-wrapper row">
                                    <div class="col-xs-4">
                                        <input type="text" class="form-control" id="nutrition_key" name="nutrition[<?php echo $key; ?>][key]" <?php if(isset($nutrition['key']['ru'])):?> value="<?php echo $nutrition['key']['ru']; ?>" <?php endif;?> placeholder="Ключ">
                                    </div>
                                    <div class="col-xs-4">
                                        <input type="text" class="form-control" id="nutrition_name" name="nutrition[<?php echo $key; ?>][name]" <?php if(isset($nutrition['name']['ru'])):?> value="<?php echo $nutrition['name']['ru']; ?>" <?php endif;?> placeholder="Название">
                                    </div>
                                    <div class="col-xs-4">
                                        <input type="text" class="form-control" id="nutrition_value" name="nutrition[<?php echo $key; ?>][value]" <?php if(isset($nutrition['value']['ru'])):?> value="<?php echo $nutrition['value']['ru']; ?>" <?php endif;?> placeholder="Значение">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                <?php endforeach; ?>
            </div>

            <div class="form-group row">
                <label class="col-xs-12" for="photos">Фото</label>
                <?php foreach ($parsing_result['photos'] as $key=>$photo): ?>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="source-site-wrapper">
                                    <a href="<?php echo $photo; ?>" target="_blank">
                                        <img src="<?php echo $photo; ?>" style="width: 100px; height: auto">
                                    </a>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-save-wrapper row">
                                    <div class="col-xs-2">
                                        <input type="checkbox" class="form-control" id="photos" name="photos[<?php echo $key; ?>][checked]" checked>
                                    </div>
                                    <div class="col-xs-10">
                                        <input type="text" class="form-control" id="photos" name="photos[<?php echo $key; ?>][photo]" <?php if(isset($photo)):?> value="<?php echo $photo; ?>" <?php endif;?> placeholder="Фото">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn btn-success">Создать пост</button>
        </form>

    <?php else:?>
    
        <div class="alert alert-warning">Не задан URL.</div>
    <?php endif; ?>
</div>

