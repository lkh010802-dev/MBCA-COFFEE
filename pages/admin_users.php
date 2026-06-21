<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

include __DIR__ . '/../config/database.php';
$userResult = mysqli_query(
    $db,
    "SELECT
        id,
        userid,
        name,
        email,
        role,
        created_at
     FROM users
     ORDER BY id DESC"
);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<link rel="stylesheet" href="/coffee/assets/css/admin.css">
<meta charset="UTF-8">
<title>회원 관리</title>
</head>
<body>
<?php include __DIR__ . '/../includes/admin_nav.php'; ?>

<h1>회원 관리</h1>

<table border="1">

<tr>
    <th>번호</th>
    <th>아이디</th>
    <th>이름</th>
    <th>이메일</th>
    <th>권한</th>
    <th>가입일</th>
</tr>

<?php while($user = mysqli_fetch_assoc($userResult)): ?>

<tr>

<td><?= $user['id'] ?></td>

<td><?= e($user['userid']) ?></td>

<td><?= e($user['name']) ?></td>

<td><?= e($user['email']) ?></td>

<td><?= e($user['role']) ?></td>

<td><?= substr($user['created_at'],0,10) ?></td>

</tr>

<?php endwhile; ?>

</table>

</body>
</html>
