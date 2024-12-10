<?php
include "token.php";
$data = file_get_contents('php://input');
$data = json_decode($data, true);
file_put_contents(__DIR__ . '/message.txt', print_r($data, true));
if (empty($data['message']['chat']['id'])) {
    exit();
}

function sendTelegram($method, $response)
{
    $ch = curl_init('https://api.telegram.org/bot' . TOKEN . '/' . $method);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

function getWeather($city)
{
    $url = "http://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&appid=" . OPENWEATHER_API . "&units=metric";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

if (!empty($data['message']['text'])) {
    $text = $data['message']['text'];
    $chat_id = $data['message']['chat']['id'];

    if (preg_match('/\/weather (.+)/', $text, $matches)) {
        $city = $matches[1];
        $weather = getWeather($city);

        if (isset($weather['main']['temp'])) {
            $temp = $weather['main']['temp'];
            $response = "У місті $city температура: $temp °C.";
        } else {
            $response = "Не вдалося знайти погоду для міста $city.";
        }
        sendTelegram('sendMessage', array('chat_id' => $chat_id, 'text' => $response));
        exit();
    }

    if (preg_match('/\/(php|javascript|python|java|csharp)/i', $text, $matches)) {
        $language = strtolower($matches[1]);
        $info = '';

        switch ($language) {
            case 'php':
                $info = "PHP - це серверна мова програмування, яка використовується для створення веб-додатків.";
                break;
            case 'javascript':
                $info = "JavaScript - це мова програмування, яка дозволяє створювати інтерактивні елементи на веб-сторінках.";
                break;
            case 'python':
                $info = "Python - це універсальна мова програмування, відома своєю простотою та читабельністю.";
                break;
            case 'java':
                $info = "Java - це об'єктно-орієнтована мова програмування, яка використовується для створення платформо-незалежних додатків.";
                break;
            case 'csharp':
                $info = "C# - це мова програмування, розроблена Microsoft, яка використовується для створення різноманітних додатків, зокрема для Windows.";
                break;
            default:
                $info = "Не вдалося знайти інформацію про цю мову програмування.";
        }

        sendTelegram('sendMessage', array('chat_id' => $chat_id, 'text' => $info));
        exit();
    }
}
