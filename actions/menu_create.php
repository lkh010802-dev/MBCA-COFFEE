<?php

require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_post('/coffee/pages/menu_write.php');
verify_csrf_or_fail();

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = (int)$_POST['price'];
    $description = trim($_POST['description']);
    $nutrition = trim($_POST['nutrition']);
    $is_best =
    isset($_POST['is_best'])
    ? 1
    : 0;

$is_season =
    isset($_POST['is_season'])
    ? 1
    : 0;

    try {
        $imagePath = store_uploaded_image($_FILES['image'] ?? null, 'menu');
    } catch (RuntimeException $exception) {
        set_flash('upload_error', $exception->getMessage());
        header('Location: /coffee/pages/menu_write.php');
        exit;
    }
$temperature_type =
    $_POST['temperature_type'] ?? null;

    $stmt = mysqli_prepare(
        $db,
        'INSERT INTO menus (name, category, price, description, nutrition, image, is_best, is_season, temperature_type)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    mysqli_stmt_bind_param(
        $stmt,
        'ssisssiis',
        $name,
        $category,
        $price,
        $description,
        $nutrition,
        $imagePath,
        $is_best,
        $is_season,
        $temperature_type
    );
    mysqli_stmt_execute($stmt);

    header('Location: /coffee/pages/admin_menus.php');
    exit;
}
