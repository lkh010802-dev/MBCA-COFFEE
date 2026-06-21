<?php

require_once __DIR__ . '/../includes/auth.php';
require_admin();

require_once __DIR__ . '/../config/database.php';

$id = (int)$_GET['id'];

$stmt = mysqli_prepare($db, 'SELECT * FROM stores WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$store = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$store) {
    die('존재하지 않는 매장입니다.');
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

<form method="post" action="/coffee/actions/store_update.php">
<?= csrf_field() ?>
<input type="hidden" name="id" value="<?= $id ?>">

<p>
매장명<br>
<input
    type="text"
    name="name"
    value="<?= e($store['name']) ?>"
    required
>
</p>

<p>
주소<br>
<input
    type="text"
    name="address"
    value="<?= e($store['address']) ?>"
    required
>
</p>

<p>
전화번호<br>
<input
    type="text"
    name="phone"
    value="<?= e($store['phone']) ?>"
>
</p>

<p>
영업시간<br>
<input
    type="text"
    name="hours"
    value="<?= e($store['hours']) ?>"
>
</p>

<button type="submit">
수정하기
</button>

</form>

</body>
</html>
