<?php

session_start();

if (
    !isset($_SESSION['role'])
    || $_SESSION['role'] !== 'admin'
) {
    die('관리자만 접근 가능합니다.');
}

require_once __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);

mysqli_query(
    $db,
    "DELETE FROM stores WHERE id=$id"
);

header('Location: admin_stores.php');
exit;