<?php

require_once __DIR__ . '/../includes/auth.php';
require_login();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /coffee/pages/news.php?type=qna');
    exit;
}
verify_csrf_or_fail();

$id = (int)($_POST['id'] ?? 0);
$stmt = mysqli_prepare($db, 'SELECT * FROM qna WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$qna = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$qna || $_SESSION['userid'] !== $qna['userid']) {
    http_response_code(403);
    exit('수정 권한이 없습니다.');
}

$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$stmt = mysqli_prepare($db, 'UPDATE qna SET title = ?, content = ? WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'ssi', $title, $content, $id);
mysqli_stmt_execute($stmt);

header("Location: /coffee/pages/news_view.php?id=$id&type=qna");
exit;
