<?php

header('Content-Type: application/json; charset=utf-8');

$lat = $_GET['lat'] ?? '';
$lng = $_GET['lng'] ?? '';

if(!$lat || !$lng){

    echo json_encode([
        'success' => false
    ]);

    exit;
}

$apiKey = '460362e75c2e3a7e5dc1cf10cadec57f';

$url =
    "https://api.openweathermap.org/data/2.5/weather"
    . "?lat={$lat}"
    . "&lon={$lng}"
    . "&appid={$apiKey}"
    . "&units=metric"
    . "&lang=kr";

$ch = curl_init();

curl_setopt(
    $ch,
    CURLOPT_URL,
    $url
);

curl_setopt(
    $ch,
    CURLOPT_RETURNTRANSFER,
    true
);

$response =
    curl_exec($ch);

curl_close($ch);

echo $response;