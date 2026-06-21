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
    "SELECT * FROM menus WHERE id=$id"
);

$menu = mysqli_fetch_assoc($result);

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

    $imagePath = $menu['image'];
        if (
        isset($_FILES['image'])
        && $_FILES['image']['error'] === 0
    ) {

        $fileName =
            time() . '_' .
            basename($_FILES['image']['name']);

        $uploadDir =
            $_SERVER['DOCUMENT_ROOT']
            . '/coffee/assets/images/menu/';

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $uploadDir . $fileName
        );

        $imagePath =
            '/coffee/assets/images/menu/' . $fileName;
    }
        mysqli_query(
        $db,
        "
        UPDATE menus
        SET
            name='$name',
            category='$category',
            temperature_type='$temperature_type',
            price='$price',
            description='$description',
            nutrition='$nutrition',
            image='$imagePath',
            is_best='$is_best',
            is_season='$is_season'
        WHERE id=$id
        "
    );

    header('Location: admin_menus.php');
    exit;
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

<form method="post" enctype="multipart/form-data">
    <p>
메뉴명<br>
<input
    type="text"
    name="name"
    value="<?= htmlspecialchars($menu['name']) ?>"
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

<textarea name="description"><?= htmlspecialchars($menu['description']) ?></textarea>

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
value="<?= htmlspecialchars($menu['nutrition']) ?>"
>

</p>
<p>

현재 이미지

<br>

<img
id="preview"
src="<?= $menu['image'] ?>"
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