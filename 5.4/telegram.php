<?php
include("./credits.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST['message'];
    $group_ids = GROUPS_IDS;
    $bot_token = BOT_TOKEN;

    foreach ($group_ids as $group_id) {
        $group_id = trim($group_id);
        $url = "https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$group_id&text=" . urlencode($message);
        file_get_contents($url);
    }

    echo "Повідомлення надіслано в групи!";
}
?>