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

    $imagePath = '';

    if (
        isset($_FILES['image'])
        && $_FILES['image']['error'] === 0
    ) {

        $fileName = time() . '_' . basename($_FILES['image']['name']);

        $uploadDir =
            $_SERVER['DOCUMENT_ROOT']
            . '/coffee/assets/images/menu/';

        $uploadPath = $uploadDir . $fileName;

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $uploadPath
        );

        $imagePath =
            '/coffee/assets/images/menu/' . $fileName;
    }
$temperature_type =
    $_POST['temperature_type'] ?? null;

$temperatureValue =
    $temperature_type
    ? "'$temperature_type'"
    : "NULL";
$sql = "
INSERT INTO menus
(
    name,
    category,
    price,
    description,
    nutrition,
    image,
    is_best,
    is_season,
    temperature_type
)
VALUES
(
    '$name',
    '$category',
    '$price',
    '$description',
    '$nutrition',
    '$imagePath',
    '$is_best',
    '$is_season',
    $temperatureValue
)
";

    mysqli_query($db, $sql);

    header('Location: admin_menus.php');
    exit;
}
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

<form method="post" enctype="multipart/form-data">

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
    style="
        width:200px;
        display:none;
        border:1px solid #ddd;
    "
>

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