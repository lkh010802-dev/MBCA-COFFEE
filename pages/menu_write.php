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
<title>메뉴 등록</title>
</head>
<body>

<p>
    <a href="/coffee/pages/admin_menus.php">
        ← 메뉴 관리로 돌아가기
    </a>
</p>

<h1>메뉴 등록</h1>
<?php if ($uploadError): ?><p class="form-message error"><?= e($uploadError) ?></p><?php endif; ?>

<form method="post" action="/coffee/actions/menu_create.php" enctype="multipart/form-data">
<?= csrf_field() ?>

<p>
메뉴명<br>
<input type="text" name="name" required>
</p>

<p>
카테고리<br>
<select name="category">

<option value="coffee">
커피
</option>

<option value="drink">
음료
</option>

<option value="food">
푸드
</option>

<option value="goods">
상품
</option>

</select>
</p>
<p id="temp-wrap">
온도 타입<br>

<select name="temperature_type">
    <option value="">선택 안함</option>
    <option value="ice">ICE</option>
    <option value="hot">HOT</option>
</select>

</p>

<p>
가격<br>
<input type="number" name="price" required>
</p>

<p>
설명<br>
<textarea name="description"></textarea>
</p>

<p>
영양정보<br>
<input type="text" name="nutrition">
</p>
<p>

<label>
    <input
        type="checkbox"
        name="is_best"
        value="1"
    >
    베스트 메뉴
</label>

</p>

<p>

<label>
    <input
        type="checkbox"
        name="is_season"
        value="1"
    >
    시즌 메뉴
</label>

</p>
<p>
이미지<br>
<input type="file" name="image" accept="image/*">
</p>
<br><br>

<img
    id="preview"
    src=""
    class="upload-preview upload-preview-bordered"
    alt="메뉴 이미지 미리보기"
>

<button type="submit">
등록하기
</button>

</form>
<script src="/coffee/assets/js/image-preview.js"></script>
</body>
</html>
