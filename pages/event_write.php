<?php

session_start();

if (
    !isset($_SESSION['role'])
    || $_SESSION['role'] !== 'admin'
) {
    die('관리자만 접근 가능합니다.');
}

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

    $thumbnailPath = '';
    $imagePath = '';

    if (
    isset($_FILES['thumbnail'])
    && $_FILES['thumbnail']['error'] === 0
) {

    $fileName =
        'thumb_' .
        time() .
        '_' .
        basename($_FILES['thumbnail']['name']);

    $uploadDir =
        $_SERVER['DOCUMENT_ROOT']
        . '/coffee/assets/images/event/';

    move_uploaded_file(
        $_FILES['thumbnail']['tmp_name'],
        $uploadDir . $fileName
    );

    $thumbnailPath =
        '/coffee/assets/images/event/' . $fileName;
}

    if (
        isset($_FILES['image'])
        && $_FILES['image']['error'] === 0
    ) {

        $fileName =
            time() . '_' .
            basename($_FILES['image']['name']);

        $uploadDir =
            $_SERVER['DOCUMENT_ROOT']
            . '/coffee/assets/images/event/';

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $uploadDir . $fileName
        );

        $imagePath =
            '/coffee/assets/images/event/' . $fileName;
    }

    mysqli_query(
        $db,
        "
        INSERT INTO events
        (
            badge,
            title,
            description,
            period,
            thumbnail,
            start_date,
            end_date,
            image
        )
        VALUES
        (
            '$badge',
            '$title',
            '$description',
            '$period',
            '$thumbnailPath',
            '$start_date',
            '$end_date',
            '$imagePath'
        )
        "
    );

    header('Location: admin_events.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<link rel="stylesheet" href="/coffee/assets/css/admin.css">
<meta charset="UTF-8">
<title>이벤트 등록</title>
</head>
<body>

<p>
<a href="/coffee/pages/admin_events.php">
← 이벤트 관리
</a>
</p>

<h1>이벤트 등록</h1>

<form method="post" enctype="multipart/form-data">

<p>
뱃지<br>
<input type="text" name="badge" required>
</p>

<p>
제목<br>
<input type="text" name="title" required>
</p>

<p>
설명<br>
<textarea name="description"></textarea>
</p>

<p>
시작일<br>
<input
    type="date"
    name="start_date"
    required
>
</p>

<p>
종료일<br>
<input
    type="date"
    name="end_date"
    required
    
>
</p>

<p>
썸네일 이미지<br>
<input
    type="file"
    name="thumbnail"
    accept="image/*"
    required
>
</p>

<p>
상세 이미지<br>
<input
    type="file"
    name="image"
    accept="image/*"
    required
>
</p>

<img
    id="preview"
    src=""
    style="
        width:200px;
        display:none;
        margin-top:10px;
    "
>

<br><br>

<button type="submit">
등록하기
</button>

</form>

<script>

const imageInput =
    document.querySelector('input[name="image"]');

const preview =
    document.getElementById('preview');

imageInput.addEventListener('change', function(){

    const file = this.files[0];

    if(!file) return;

    const reader = new FileReader();

    reader.onload = function(e){

        preview.src = e.target.result;
        preview.style.display = 'block';

    };

    reader.readAsDataURL(file);

});

</script>

</body>
</html>