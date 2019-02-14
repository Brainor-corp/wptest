<?php
global $wpdb;

$dir = PLUGIN_NAME_DIR.'assets/catalog-files';
$nowTimestamp = date("Y-m-d H:i:s");



$carsTable = $wpdb->prefix . 'br_tools_cars';
$goodsTable = $wpdb->prefix . 'br_tools_goods';
$productsTable = $wpdb->prefix . 'br_tools_products';
$carGoodPivotTable = $wpdb->prefix . 'br_tools_car_good';
$carProductTable = $wpdb->prefix . 'br_tools_car_product';


$truncated = false;
if ($handle = opendir($dir)) {
    $file_name_check = null;
    while (false !== ($file = readdir($handle))) {

        if(preg_match("|.csv$|",$file) OR preg_match("|.CSV$|",$file)){
            if($file_name_check !== $file){

                if($truncated == false){
                    $wpdb->query("TRUNCATE TABLE $goodsTable");
                    $wpdb->query("TRUNCATE TABLE $carGoodPivotTable");
                    $truncated = true;
                }

                $fileName = explode('.',basename($file))[0];
                $city =explode('_',$fileName);
                $city =end($city);
                $filePath = $dir.'/'.$file;
                $handle2 = fopen('php://memory', 'w+');

                $file_read = file_get_contents($filePath);
                $csv = explode("\n", $file_read);
                $data = [];
                foreach ($csv as $key => $line) {
                    $row = str_getcsv($line, ';', '"');
                    if ($key == 0) {
                        continue;
                    } else {//не пишем первую строку
                        if (isset($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8])) {
                            $br_id = trim($row[0]);
                            $brand = trim($row[1]);
                            $art = trim($row[2]);
                            $orgnl = trim($row[3]);
                            $orgnl_id = trim($row[4]);
                            $name = trim($row[5]);
                            $cross = trim($row[6]);
                            $quant = trim($row[7]);
                            $price = trim($row[8]);

                            $equalProduct = $wpdb->get_row( "SELECT id FROM $productsTable WHERE barcode = '$art'", OBJECT );

                            if (!$equalProduct OR $equalProduct == NULL) {
                                $orgnl_id_array = explode(';', $orgnl_id);
                                foreach ($orgnl_id_array as $value) {
                                    $value = trim($value);
                                    if(mb_strlen($value)>1){
                                        $equalProduct = $wpdb->get_row( "SELECT id FROM $productsTable WHERE barcode = '$value'", OBJECT );
                                        if ($equalProduct) {
                                            break;
                                        }
                                    }
                                }
                            }
                            if (!$equalProduct OR $equalProduct == NULL) {
                                $cross_array = explode(';', $cross);
                                foreach ($cross_array as $value) {
                                    $value = trim($value);
                                    if(mb_strlen($value)>1){
                                        $equalProduct = $wpdb->get_row( "SELECT id FROM $productsTable WHERE barcode = '$value'", OBJECT );
                                        if ($equalProduct) {
                                            break;
                                        }
                                    }
                                }
                            }
                            if ($equalProduct) {
                                $equalProductCarsIds = $wpdb->get_results( "SELECT * FROM $carProductTable WHERE product_id = '$equalProduct->id'", OBJECT );

                                $newGood = $wpdb->insert($goodsTable, array(
                                    'product_id' => $equalProduct->id,
                                    'br_id' => $br_id,
                                    'brand' => $brand,
                                    'art' => $art,
                                    'orgnl' => $orgnl,
                                    'orgnl_id' => $orgnl_id,
                                    'name' => $name,
                                    'cross' => $cross,
                                    'quant' => $quant,
                                    'price' => preg_replace("/[^x\d|*\.]/", "", str_replace(',', '.', $price)),
                                    'city' => $city,
                                    'created_at' => $nowTimestamp,
                                    'updated_at' => $nowTimestamp,
                                ));
                                $insertedGoodId = $wpdb->insert_id;

//                                $priceFormated = preg_replace("/[^x\d|*\.]/", "", str_replace(',', '.', $price));
//                                $sql = "INSERT INTO `$goodsTable`
//                                  (`product_id`, `br_id`, `name`, `brand`, `art`, `orgnl`, `orgnl_id`, `cross`, `quant`, `price`, `city`, `created_at`, `updated_at`)
//                                  VALUES
//                                  ('$equalProduct->id', '$br_id', '$brand', '$art', '$orgnl', '$orgnl_id', '$name', '$cross', '$quant', '$priceFormated', '$city', '$nowTimestamp', '$nowTimestamp')";
//                                $wpdb->query($sql);
//                                $insertedGoodId = $wpdb->get_var( "SELECT max(id) FROM $goodsTable" );
//
//                                var_dump($insertedGoodId); echo '<br>';
//                                var_dump($sql); echo '<br>';
                                if (count($equalProductCarsIds) > 0) {
                                    $carGoodPivotQuery = "INSERT INTO $carGoodPivotTable (good_id, car_id) VALUES ";
                                    $n=0;
                                    foreach ($equalProductCarsIds as $equalProductCarsId){
                                        if($n !== 0){
                                            $carGoodPivotQuery .= ",";
                                        }
                                        $carGoodPivotQuery .= "('$insertedGoodId','$equalProductCarsId->car_id')";
                                        $n++;
                                    }
                                    $wpdb->query($carGoodPivotQuery);
                                }
                                unset($equalProduct);
                            } else {
                                $data['unfound'][] = $row;
                            }
                        }
                    }
                    unset($row);

                }
                if(isset($data['unfound'])){
                    $csv .= '"br_id";"brand";"art";"orgnl";"orgnl_id";"name";"cross";"quant";"price"'."\r\n";
                    foreach ($data['unfound'] as $row) {
                        $csv .= '"' . $row[0] . '";"' . $row[1] . '";"' . $row[2] . '";"' . $row[3] . '";"' . $row[4] . '";"' . $row[5] . '";"' . $row[6] . '";"' . $row[7] . '";"' . $row[8] . '"' . "\r\n";
                    }
                    $nowDate = date("Y-m-d-H-i-s");
                    $file_name = $fileName.'_unfound_'.$nowDate.'.csv'; // название файла
                    $fullPath = $dir.'/'.$file_name;
                    $file = fopen($fullPath,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт
                    fwrite($file, "\xEF\xBB\xBF", 3);
                    fwrite($file,$csv); // записываем в файл строки
                }
                unset($data);
                $file_name_check = $file;
            }
        }
    }
    closedir($handle);
}
else{echo "Нет доступа к папке.\n";}