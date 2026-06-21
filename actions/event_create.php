<?php

require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_post('/coffee/pages/event_write.php');
verify_csrf_or_fail();

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $badge = trim($_POST['badge']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $period =
        $start_date .
        ' ~ ' .
        $end_date;

    try {
        $thumbnailPath = store_uploaded_image(
            $_FILES['thumbnail'] ?? null,
            'event',
            ''
        );
        $imagePath = store_uploaded_image(
            $_FILES['image'] ?? null,
            'event',
            ''
        );
    } catch (RuntimeException $exception) {
        set_flash('upload_error', $exception->getMessage());
        header("Location: /coffee/pages/event_write.php");
        exit;
    }

    $stmt = mysqli_prepare(
        $db,
        'INSERT INTO events (badge, title, description, period, thumbnail, start_date, end_date, image)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    mysqli_stmt_bind_param(
        $stmt,
        'ssssssss',
        $badge,
        $title,
        $description,
        $period,
        $thumbnailPath,
        $start_date,
        $end_date,
        $imagePath
    );
    mysqli_stmt_execute($stmt);

    header('Location: /coffee/pages/admin_events.php');
    exit;
}
