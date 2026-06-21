<?php

require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_post('/coffee/pages/store_write.php');
verify_csrf_or_fail();

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $hours = trim($_POST['hours']);
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];

    $stmt = mysqli_prepare($db, 'INSERT INTO stores (name, address, phone, hours, lat, lng) VALUES (?, ?, ?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'ssssss', $name, $address, $phone, $hours, $lat, $lng);
    mysqli_stmt_execute($stmt);

    header('Location: /coffee/pages/admin_stores.php');
    exit;
}
