<?php

require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_post('/coffee/pages/admin_stores.php');
verify_csrf_or_fail();

require_once __DIR__ . '/../config/database.php';

$id = (int)$_POST['id'];

$stmt = mysqli_prepare($db, 'SELECT * FROM stores WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$store = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$store) {
    die('존재하지 않는 매장입니다.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $hours = trim($_POST['hours']);

    $stmt = mysqli_prepare($db, 'UPDATE stores SET name = ?, address = ?, phone = ?, hours = ? WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'ssssi', $name, $address, $phone, $hours, $id);
    mysqli_stmt_execute($stmt);

    header('Location: /coffee/pages/admin_stores.php');
    exit;
}
