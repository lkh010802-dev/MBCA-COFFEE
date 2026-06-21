<?php

session_start();

include __DIR__ . '/../config/database.php';

if(
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
){
    die('관리자만 가능합니다.');
}

$id = (int)($_GET['id'] ?? 0);

mysqli_query(
    $db,
    "DELETE FROM notices
     WHERE id=$id"
);

header(
    'Location: /coffee/pages/news.php?type=notice'
);
exit;