<?php

require_once __DIR__ . '/../includes/auth.php';
require_admin();
$uploadError = pull_flash('upload_error', '');

require_once __DIR__ . '/../config/database.php';

$id = (int)$_GET['id'];

$stmt = mysqli_prepare($db, 'SELECT * FROM events WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$event = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$event) {
    die('존재하지 않는 이벤트입니다.');
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
<?php if ($uploadError): ?><p class="form-message error"><?= e($uploadError) ?></p><?php endif; ?>

<form method="post" action="/coffee/actions/event_update.php" enctype="multipart/form-data">
<?= csrf_field() ?>
<input type="hidden" name="id" value="<?= $id ?>">
<p>
뱃지<br>

<input
    type="text"
    name="badge"
    value="<?= e($event['badge']) ?>"
    required
>

</p>
<p>
제목<br>

<input
    type="text"
    name="title"
    value="<?= e($event['title']) ?>"
    required
>

</p>
<p>
설명<br>

<textarea name="description"><?= e($event['description']) ?></textarea>

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
    src="<?= e(image_url($event['thumbnail'], 'event')) ?>"
    class="current-image current-image-thumbnail"
    alt="현재 이벤트 썸네일"
>
</p>
<hr class="form-divider">
<h3>현재 상세 이미지</h3>

<img
    id="preview"
    src="<?= e(image_url($event['image'], 'event')) ?>"
    class="current-image current-image-detail"
    alt="현재 이벤트 상세 이미지"
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
<script src="/coffee/assets/js/image-preview.js"></script>

</body>
</html>
