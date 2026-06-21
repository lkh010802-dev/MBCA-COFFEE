<?php

require_once __DIR__ . '/../includes/auth.php';
require_login();
include __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = mysqli_prepare($db, 'SELECT * FROM qna WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$qna = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if(!$qna){
    die('문의가 존재하지 않습니다.');
}

if(
    $_SESSION['userid'] !== $qna['userid']
){
    die('수정 권한이 없습니다.');
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<link rel="stylesheet" href="/coffee/assets/css/admin.css">
<meta charset="UTF-8">
<title>문의 수정</title>
</head>
<body>

<h1>문의 수정</h1>

<form method="post" action="/coffee/actions/qna_update.php">
<?= csrf_field() ?>
<input type="hidden" name="id" value="<?= $id ?>">

<input
    type="text"
    name="title"
    value="<?= e($qna['title']) ?>"
    required
>

<br><br>

<textarea
    name="content"
    rows="10"
    cols="80"
    required
><?= e($qna['content']) ?></textarea>

<br><br>

<button type="submit">
수정 완료
</button>

</form>

</body>
</html>
