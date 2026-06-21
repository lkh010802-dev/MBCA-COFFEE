<?php

require_once __DIR__ . '/../includes/auth.php';
require_admin();
$uploadError = pull_flash('upload_error', '');

require_once __DIR__ . '/../config/database.php';

$id = (int)$_GET['id'];
$stmt = mysqli_prepare($db, 'SELECT * FROM menus WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$menu = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$menu) {
    die('존재하지 않는 메뉴입니다.');
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    
<link rel="stylesheet" href="/coffee/assets/css/admin.css">
<meta charset="UTF-8">
<title>메뉴 수정</title>
</head>
<body>

<p>
<a href="/coffee/pages/admin_menus.php">
← 메뉴 관리
</a>
</p>

<h1>메뉴 수정</h1>
<?php if ($uploadError): ?><p class="form-message error"><?= e($uploadError) ?></p><?php endif; ?>

<form method="post" action="/coffee/actions/menu_update.php" enctype="multipart/form-data">
<?= csrf_field() ?>
<input type="hidden" name="id" value="<?= $id ?>">
    <p>
메뉴명<br>
<input
    type="text"
    name="name"
    value="<?= e($menu['name']) ?>"
    required
>
</p>
<p>
카테고리<br>

<select name="category">

<option
value="coffee"
<?= $menu['category']=='coffee' ? 'selected' : '' ?>>
커피
</option>

<option
value="drink"
<?= $menu['category']=='drink' ? 'selected' : '' ?>>
음료
</option>

<option
value="food"
<?= $menu['category']=='food' ? 'selected' : '' ?>>
푸드
</option>

<option
value="goods"
<?= $menu['category']=='goods' ? 'selected' : '' ?>>
상품
</option>

</select>

</p>

<p>
온도 타입<br>

<select name="temperature_type">

<option
value="ice"
<?= $menu['temperature_type']=='ice' ? 'selected' : '' ?>>
ICE
</option>

<option
value="hot"
<?= $menu['temperature_type']=='hot' ? 'selected' : '' ?>>
HOT
</option>

</select>

</p>
<p>
가격<br>
<input
type="number"
name="price"
value="<?= $menu['price'] ?>"
required
>
</p>
<p>
설명<br>

<textarea name="description"><?= e($menu['description']) ?></textarea>

</p>
<p>
영양정보<br>
<p>

<label>
<input
type="checkbox"
name="is_best"
value="1"
<?= $menu['is_best'] ? 'checked' : '' ?>
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
<?= $menu['is_season'] ? 'checked' : '' ?>
>

시즌 메뉴

</label>

</p>

<input
type="text"
name="nutrition"
value="<?= e($menu['nutrition']) ?>"
>

</p>
<p>

현재 이미지

<br>

<img
id="preview"
src="<?= e(image_url($menu['image'], 'menu')) ?>"
width="150"
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

</body>
</html>
