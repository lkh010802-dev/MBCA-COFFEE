<?php include __DIR__ . '/../includes/admin_nav.php'; ?>
<?php

session_start();

if(
    !isset($_SESSION['role'])
    || $_SESSION['role'] !== 'admin'
){
    die('관리자만 접근 가능합니다.');
}

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

<td><?= htmlspecialchars($user['userid']) ?></td>

<td><?= htmlspecialchars($user['name']) ?></td>

<td><?= htmlspecialchars($user['email']) ?></td>

<td><?= htmlspecialchars($user['role']) ?></td>

<td><?= substr($user['created_at'],0,10) ?></td>

</tr>

<?php endwhile; ?>

</table>

</body>
</html>