<?php

session_start();

include __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);

$result = mysqli_query(
    $db,
    "SELECT *
     FROM qna
     WHERE id=$id"
);

$qna = mysqli_fetch_assoc($result);

if(!$qna){
    die('문의가 존재하지 않습니다.');
}

if(
    !isset($_SESSION['userid'])
    ||
    $_SESSION['userid'] !== $qna['userid']
){
    die('수정 권한이 없습니다.');
}
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    mysqli_query(
        $db,
        "UPDATE qna
         SET
            title='$title',
            content='$content'
         WHERE id=$id"
    );

    header(
        "Location: news_view.php?id=$id&type=qna"
    );

    exit;
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

<form method="post">

<input
    type="text"
    name="title"
    value="<?= htmlspecialchars($qna['title']) ?>"
    required
>

<br><br>

<textarea
    name="content"
    rows="10"
    cols="80"
    required
><?= htmlspecialchars($qna['content']) ?></textarea>

<br><br>

<button type="submit">
수정 완료
</button>

</form>

</body>
</html>