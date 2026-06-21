<?php

require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_post('/coffee/pages/admin_events.php');
verify_csrf_or_fail();

require_once __DIR__ . '/../config/database.php';

$id = (int)$_POST['id'];

$stmt = mysqli_prepare($db, 'SELECT * FROM events WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$event = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$event) {
    die('존재하지 않는 이벤트입니다.');
}

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
            $event['thumbnail']
        );
        $imagePath = store_uploaded_image(
            $_FILES['image'] ?? null,
            'event',
            $event['image']
        );
    } catch (RuntimeException $exception) {
        set_flash('upload_error', $exception->getMessage());
        header("Location: /coffee/pages/event_edit.php?id=$id");
        exit;
    }

    $stmt = mysqli_prepare(
        $db,
        'UPDATE events SET badge = ?, title = ?, description = ?, thumbnail = ?, period = ?,
         start_date = ?, end_date = ?, image = ? WHERE id = ?'
    );
    mysqli_stmt_bind_param(
        $stmt,
        'ssssssssi',
        $badge,
        $title,
        $description,
        $thumbnailPath,
        $period,
        $start_date,
        $end_date,
        $imagePath,
        $id
    );
    mysqli_stmt_execute($stmt);

    header('Location: /coffee/pages/admin_events.php');
    exit;
}
