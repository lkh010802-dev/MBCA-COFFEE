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
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $hours = trim($_POST['hours']);
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];

    mysqli_query(
        $db,
        "
        INSERT INTO stores
        (
            name,
            address,
            phone,
            hours,
            lat,
            lng
        )
        VALUES
        (
            '$name',
            '$address',
            '$phone',
            '$hours',
            '$lat',
            '$lng'
        )
        "
    );

    header('Location: admin_stores.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">

<title>매장 등록</title>

<link
    rel="stylesheet"
    href="/coffee/assets/css/admin.css"
>

</head>
<body>

<?php include __DIR__ . '/../includes/admin_nav.php'; ?>

<h1>매장 등록</h1>

<p>
<a href="/coffee/pages/admin_stores.php">
← 매장 관리
</a>
</p>

<form method="post">

<p>
매장명<br>
<input
    type="text"
    name="name"
    required
>
</p>

<p>
주소<br>
<input
    type="text"
    name="address"
    required
>
</p>

<p>
전화번호<br>
<input
    type="text"
    name="phone"
>
</p>

<p>
영업시간<br>
<input
    type="text"
    name="hours"
    placeholder="08:00 - 22:00"
>
</p>
<p>
위도<br>
<input
    type="text"
    name="lat"
>
</p>

<p>
경도<br>
<input
    type="text"
    name="lng"
>
</p>

<button type="submit">
등록하기
</button>

</form>

</body>
</html>