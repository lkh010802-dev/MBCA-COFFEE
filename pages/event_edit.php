<?php

session_start();

if (
    !isset($_SESSION['role'])
    || $_SESSION['role'] !== 'admin'
) {
    die('관리자만 접근 가능합니다.');
}

require_once __DIR__ . '/../config/database.php';

$id = (int)$_GET['id'];

$result = mysqli_query(
    $db,
    "SELECT * FROM events WHERE id=$id"
);

$event = mysqli_fetch_assoc($result);

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
    $thumbnailPath = $event['thumbnail'];
    $imagePath = $event['image'];
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
        UPDATE events
        SET
            badge='$badge',
            title='$title',
            description='$description',
            thumbnail='$thumbnailPath',
            period='$period',
            start_date='$start_date',
            end_date='$end_date',
            image='$imagePath'
        WHERE id=$id
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
<title>이벤트 수정</title>
</head>
<body>

<p>
<a href="/coffee/pages/admin_events.php">
← 이벤트 관리
</a>
</p>

<h1>이벤트 수정</h1>

<form method="post" enctype="multipart/form-data">
<p>
뱃지<br>

<input
    type="text"
    name="badge"
    value="<?= htmlspecialchars($event['badge']) ?>"
    required
>

</p>
<p>
제목<br>

<input
    type="text"
    name="title"
    value="<?= htmlspecialchars($event['title']) ?>"
    required
>

</p>
<p>
설명<br>

<textarea name="description"><?= htmlspecialchars($event['description']) ?></textarea>

</p>
<p>
시작일<br>

<input
    type="date"
    name="start_date"
    value="<?= $event['start_date'] ?>"
    required
>

</p>

<p>
종료일<br>

<input
    type="date"
    name="end_date"
    value="<?= $event['end_date'] ?>"
    required
>

</p>

<br>
<h3>현재 썸네일</h3>

<img
    src="<?= $event['thumbnail'] ?>"
    style="
        width:250px;
        border:1px solid #ddd;
        margin-bottom:20px;
    "
>
</p>
<hr style="margin:30px 0;">
<h3>현재 상세 이미지</h3>

<img
    id="preview"
    src="<?= $event['image'] ?>"
    style="
        width:300px;
        border:1px solid #ddd;
        margin-bottom:20px;
    "
>


</p>
<p>

새 썸네일

<br>

<input
    type="file"
    name="thumbnail"
    accept="image/*"
>

</p>
<p>

새 이미지

<br>

<input
    type="file"
    name="image"
    accept="image/*"
>

</p>
<button type="submit">
수정하기
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

    };

    reader.readAsDataURL(file);

});

</script>

</body>
</html>