<?php

$prefix = "reiki";
//$sends = array('rsr178@yandex.ru');

$sends = array('9119138573@mail.ru', 'rsr178@yandex.ru');
$data = array(
    'name' => "Имя",
    'phone' => "Телефон",
    'email' => "Email",
    'date' => 'Дата',
    'time' => 'Время',
    'city' => 'Город'
);
$mess = "";
$mess = "<table>";
foreach ($data as $key => $title) {
    if (isset($_POST[$key])) {
        $value = (is_array($_POST[$key])) ? json_encode($_POST[$key]) : $_POST[$key];
        $mess .= "
        <tr>
            <td>{$title}</td>
            <td>{$value}</td>
        </tr>";
    }
}

$mess .= "<table>";
$_POST["email"] = (isset($_POST["email"])) ? $_POST["email"] : "admin@email.com";
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
$sub = "Новое сообщения от " . strip_tags(trim($_SERVER['SERVER_NAME'])) . " ({$prefix})";
foreach ($sends as $key => $to) {
	echo $to;
    echo mail($to, $sub, $mess, $headers);
}
?>