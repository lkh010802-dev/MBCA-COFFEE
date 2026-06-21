<?php

require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_post('/coffee/pages/admin_menus.php');
verify_csrf_or_fail();

require_once __DIR__ . '/../config/database.php';

$id = (int)$_POST['id'];
$stmt = mysqli_prepare($db, 'SELECT * FROM menus WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$menu = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$menu) {
    die('존재하지 않는 메뉴입니다.');
}
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
    $temperature_type =
    $_POST['temperature_type'];

    try {
        $imagePath = store_uploaded_image(
            $_FILES['image'] ?? null,
            'menu',
            $menu['image']
        );
    } catch (RuntimeException $exception) {
        set_flash('upload_error', $exception->getMessage());
        header("Location: /coffee/pages/menu_edit.php?id=$id");
        exit;
    }
    $stmt = mysqli_prepare(
        $db,
        'UPDATE menus SET name = ?, category = ?, temperature_type = ?, price = ?, description = ?,
         nutrition = ?, image = ?, is_best = ?, is_season = ? WHERE id = ?'
    );
    mysqli_stmt_bind_param(
        $stmt,
        'sssisssiii',
        $name,
        $category,
        $temperature_type,
        $price,
        $description,
        $nutrition,
        $imagePath,
        $is_best,
        $is_season,
        $id
    );
    mysqli_stmt_execute($stmt);

    header('Location: /coffee/pages/admin_menus.php');
    exit;
}
