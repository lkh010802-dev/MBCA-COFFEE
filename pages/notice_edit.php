<?php

require_once __DIR__ . '/../includes/auth.php';
require_admin();
include __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = mysqli_prepare($db, 'SELECT * FROM notices WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$post = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
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

<form method="post" action="/coffee/actions/notice_update.php">
<?= csrf_field() ?>
<input type="hidden" name="id" value="<?= $id ?>">

<input
type="text"
name="title"
value="<?= e($post['title']) ?>"
required
>

<textarea
name="content"
rows="10"
required
><?= e($post['content']) ?></textarea>
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
