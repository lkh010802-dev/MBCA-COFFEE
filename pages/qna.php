<?php

require_once __DIR__ . '/../includes/auth.php';
require_login();
include __DIR__ . '/../config/database.php';

$message = pull_flash('qna_message', '');


$userid = $_SESSION['userid'];
$stmt = mysqli_prepare($db, 'SELECT * FROM qna WHERE userid = ? ORDER BY id DESC');
mysqli_stmt_bind_param($stmt, 's', $userid);
mysqli_stmt_execute($stmt);
$listResult = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>

<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Q&A | MBCA COFFEE</title>

<link rel="stylesheet" href="/coffee/assets/css/header.css">
<link rel="stylesheet" href="/coffee/assets/css/nav.css">
<link rel="stylesheet" href="/coffee/assets/css/notice.css">
</head>

<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<main class="news-page">

<section class="board-title-bar">
    <h1>Q&A</h1>
</section>

<section class="board-wrap">

<nav class="board-tabs">
    <a href="/coffee/pages/news.php">공지사항</a>
    <a class="is-active" href="/coffee/pages/qna.php">Q&A</a>
</nav>

<?php if($message): ?>

<p><?= $message ?></p>
<?php endif; ?>

<form method="post" action="/coffee/actions/qna_create.php">
<?= csrf_field() ?>
<input type="hidden" name="return_to" value="qna_page">

```
<input
    type="text"
    name="title"
    placeholder="문의 제목"
    required
>

<textarea
    name="content"
    rows="8"
    placeholder="문의 내용을 입력하세요"
    required
></textarea>

<button type="submit">
    문의 등록
</button>
```

</form>

<hr>

<?php while($row = mysqli_fetch_assoc($listResult)): ?>

<div class="qna-card">

```
<h3><?= e($row['title']) ?></h3>

<p>
    <?= $row['status'] === 'waiting'
        ? '답변대기'
        : '답변완료'; ?>
</p>

<small><?= $row['created_at'] ?></small>
```

</div>

<hr>

<?php endwhile; ?>

</section>

</main>

<script src="/coffee/assets/js/nav.js"></script>
<?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
