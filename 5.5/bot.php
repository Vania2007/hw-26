<?php
include "./credits.php";

function sendMessage($response)
{
    $url = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage?chat_id=" . $chat_id . "&text=" . urlencode($response);
    file_get_contents($url);
}

function getWeather($city)
{
    $url = "http://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&appid=" . OPENWEATHER_API . "&units=metric";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

$update = json_decode(file_get_contents("php://input"), true);
$chat_id = $update['message']['chat']['id'];
$message = $update['message']['text'];

if (preg_match('/\/weather (.+)/', $message, $matches)) {
    $city = $matches[1];
    $weather = getWeather($city);

    if (isset($weather['main']['temp'])) {
        $temp = $weather['main']['temp'];
        $response = "У місті $city температура: $temp °C.";
    } else {
        $response = "Не вдалося знайти погоду для міста $city.";
    }
} elseif (preg_match('/\/programming/', $message)) {
    $response = "Я можу допомогти з такими мовами програмування: PHP, JavaScript, Java, C#, Python.\n";
    $response .= "Якщо вас цікавить конкретна мова, просто запитайте про неї, наприклад:\n";
    $response .= "/php - інформація про PHP\n";
    $response .= "/javascript - інформація про JavaScript\n";
    $response .= "/java - інформація про Java\n";
    $response .= "/csharp - інформація про C#\n";
    $response .= "/python - інформація про Python";
} elseif (preg_match('/\/php/', $message)) {
    $response = "PHP - це серверна мова програмування, яка широко використовується для веб-розробки. Вона дозволяє створювати динамічні веб-сторінки.";
} elseif (preg_match('/\/javascript/', $message)) {
    $response = "JavaScript - це мова програмування, яка використовується для створення інтерактивних елементів на веб-сторінках. Вона виконується на стороні клієнта.";
} elseif (preg_match('/\/java/', $message)) {
    $response = "Java - це об'єктно-орієнтована мова програмування, яка використовується для створення різноманітних додатків, від мобільних до веб-додатків.";
} elseif (preg_match('/\/csharp/', $message)) {
    $response = "C# - це мова програмування, розроблена Microsoft, яка використовується для створення різноманітних додатків, зокрема для Windows.";
} elseif (preg_match('/\/python/', $message)) {
    $response = "Python - це високорівнева мова програмування, яка відома своєю простотою та читабельністю. Вона використовується в науці про дані, веб-розробці та автоматизації.";
} else {
    $response = "Вітаю! Використовуйте /weather [місто] для отримання погоди або /programming для отримання інформації про мови програмування.";
}
sendMessage($response);