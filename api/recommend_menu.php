<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';

$type =
    $_GET['type'] ?? 'ice';

$result = mysqli_query(
    $db,
    "
    SELECT *
    FROM menus
    WHERE temperature_type='$type'
    ORDER BY RAND()
    LIMIT 1
    "
);

$menu =
    mysqli_fetch_assoc($result);

echo json_encode($menu);