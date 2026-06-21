<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();
require_post('/coffee/pages/news.php?type=qna');
verify_csrf_or_fail();
require_once __DIR__ . '/../config/database.php';

$id = (int)($_POST['id'] ?? 0);
$stmt = mysqli_prepare($db, 'SELECT userid FROM qna WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$qna = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$qna || ($_SESSION['userid'] !== $qna['userid'] && !is_admin())) {
    http_response_code(403);
    exit('삭제 권한이 없습니다.');
}

$stmt = mysqli_prepare($db, 'DELETE FROM qna WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

header('Location: /coffee/pages/news.php?type=qna');
exit;
