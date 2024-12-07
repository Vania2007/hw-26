<?php
include "./credits.php";

$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$txt = $_POST['text'];

$arr = [
    "Им'я користувача: " => $name,
    "Телефон: " => $phone,
    "Email: " => $email,
    "Сповіщення: " => $txt,
];

$text = "";

foreach ($arr as $key => $value) {
    $text .= rawurlencode("<b>") . rawurlencode($key) . rawurlencode("</b> ") . rawurlencode($value) . "%0A";
}
$text .= "На сайті була помічена нова заявка";

$str = "https://api.telegram.org/bot" . TOKEN . "/sendMessage?chat_id=" . CHAT_ID . "}&parse_mode=html&text={$text}";

$sendToTelegram = fopen($str, "r");

if ($sendToTelegram) {
    echo "OK";
} else {
    echo "Error";
}