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
    "SELECT * FROM stores WHERE id=$id"
);

$store = mysqli_fetch_assoc($result);

if (!$store) {
    die('존재하지 않는 매장입니다.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $hours = trim($_POST['hours']);

    mysqli_query(
        $db,
        "
        UPDATE stores
        SET
            name='$name',
            address='$address',
            phone='$phone',
            hours='$hours'
        WHERE id=$id
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

<title>매장 수정</title>

<link
    rel="stylesheet"
    href="/coffee/assets/css/admin.css"
>

</head>
<body>

<?php include __DIR__ . '/../includes/admin_nav.php'; ?>

<h1>매장 수정</h1>

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
    value="<?= htmlspecialchars($store['name']) ?>"
    required
>
</p>

<p>
주소<br>
<input
    type="text"
    name="address"
    value="<?= htmlspecialchars($store['address']) ?>"
    required
>
</p>

<p>
전화번호<br>
<input
    type="text"
    name="phone"
    value="<?= htmlspecialchars($store['phone']) ?>"
>
</p>

<p>
영업시간<br>
<input
    type="text"
    name="hours"
    value="<?= htmlspecialchars($store['hours']) ?>"
>
</p>

<button type="submit">
수정하기
</button>

</form>

</body>
</html>