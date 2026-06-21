<?php

session_start();

if (
    !isset($_SESSION['role'])
    || $_SESSION['role'] !== 'admin'
) {
    die('관리자만 접근 가능합니다.');
}

require_once __DIR__ . '/../config/database.php';

$result = mysqli_query(
    $db,
    "
    SELECT *
    FROM stores
    ORDER BY id DESC
    "
);

?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">

<link
    rel="stylesheet"
    href="/coffee/assets/css/admin.css"
>

<title>매장 관리</title>
</head>
<body>

<?php include __DIR__ . '/../includes/admin_nav.php'; ?>

<h1>매장 관리</h1>

<p>
<a href="/coffee/pages/store_write.php">
매장 등록
</a>
</p>

<table border="1">

<tr>
    <th>ID</th>
    <th>매장명</th>
    <th>주소</th>
    <th>전화번호</th>
    <th>영업시간</th>
    <th>관리</th>
</tr>

<?php while($store = mysqli_fetch_assoc($result)): ?>

<tr>

<td>
<?= $store['id'] ?>
</td>

<td>
<?= htmlspecialchars($store['name']) ?>
</td>

<td>
<?= htmlspecialchars($store['address']) ?>
</td>

<td>
<?= htmlspecialchars($store['phone']) ?>
</td>

<td>
<?= htmlspecialchars($store['hours']) ?>
</td>

<td>

<a href="store_edit.php?id=<?= $store['id'] ?>">
수정
</a>

|

<a
    href="store_delete.php?id=<?= $store['id'] ?>"
    onclick="return confirm('삭제하시겠습니까?')"
>
삭제
</a>

</td>

</tr>

<?php endwhile; ?>

</table>

</body>
</html>