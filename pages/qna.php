<?php

session_start();

include __DIR__ . '/../config/database.php';

if (!isset($_SESSION['userid'])) {
    header('Location: /coffee/pages/login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userid = $_SESSION['userid'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    $sql = "INSERT INTO qna
            (userid, title, content)
            VALUES
            ('$userid', '$title', '$content')";

    if (mysqli_query($db, $sql)) {
        $message = '문의가 등록되었습니다.';
    } else {
        $message = '등록 실패';
    }
}

$listSql = "SELECT *
            FROM qna
            WHERE userid='{$_SESSION['userid']}'
            ORDER BY id DESC";

$listResult = mysqli_query($db, $listSql);

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

<form method="post">

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
<h3><?= htmlspecialchars($row['title']) ?></h3>

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
