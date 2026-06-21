<?php

require_once __DIR__ . '/../includes/auth.php';
require_admin();
$uploadError = pull_flash('upload_error', '');

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
<?php if ($uploadError): ?><p class="form-message error"><?= e($uploadError) ?></p><?php endif; ?>

<form method="post" action="/coffee/actions/event_create.php" enctype="multipart/form-data">
<?= csrf_field() ?>

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
    class="upload-preview upload-preview-spaced"
    alt="이벤트 이미지 미리보기"
>

<br><br>

<button type="submit">
등록하기
</button>

</form>

<script src="/coffee/assets/js/image-preview.js"></script>

</body>
</html>
