<?php

session_start();

include __DIR__ . '/../config/database.php';

if(
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
){
    die('관리자만 가능합니다.');
}

$id = (int)($_GET['id'] ?? 0);

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $isPinned =
    isset($_POST['is_pinned'])
    ? 1
    : 0;

    mysqli_query(
        $db,
"UPDATE notices
 SET
    title='$title',
    content='$content',
    is_pinned=$isPinned
 WHERE id=$id"
    );

    header(
        "Location: news_view.php?id=$id&type=notice"
    );
    exit;
}

$result = mysqli_query(
    $db,
    "SELECT *
     FROM notices
     WHERE id=$id"
);

$post = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<link rel="stylesheet" href="/coffee/assets/css/admin.css">

<meta charset="UTF-8">

<title>공지 수정</title>

<link rel="stylesheet" href="/coffee/assets/css/header.css">
<link rel="stylesheet" href="/coffee/assets/css/nav.css">
<link rel="stylesheet" href="/coffee/assets/css/forms.css">

</head>

<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<main class="auth-page">

<section class="auth-card">

<h1>공지 수정</h1>

<form method="post">

<input
type="text"
name="title"
value="<?= htmlspecialchars($post['title']) ?>"
required
>

<textarea
name="content"
rows="10"
required
><?= htmlspecialchars($post['content']) ?></textarea>
<label>

<input
    type="checkbox"
    name="is_pinned"
    value="1"
    <?= $post['is_pinned'] ? 'checked' : '' ?>
>

중요공지 고정

</label>

<button type="submit">
수정 완료
</button>

</form>

</section>

</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>