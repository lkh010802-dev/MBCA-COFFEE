<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../functions/helpers.php';
require_once __DIR__ . '/../config/database.php';

$type =
    $_GET['type'] ?? 'ice';

if (!in_array($type, ['ice', 'hot'], true)) {
    $type = 'ice';
}

$stmt = mysqli_prepare(
    $db,
    'SELECT * FROM menus WHERE temperature_type = ? ORDER BY RAND() LIMIT 1'
);
mysqli_stmt_bind_param($stmt, 's', $type);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$menu =
    mysqli_fetch_assoc($result);

if ($menu) {
    $menu['image'] = image_url($menu['image'], 'menu');
}

echo json_encode($menu);
