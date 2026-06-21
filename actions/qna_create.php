<?php

require_once __DIR__ . '/../includes/auth.php';
require_login();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /coffee/pages/news.php?type=qna');
    exit;
}
verify_csrf_or_fail();

$userid = $_SESSION['userid'];
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$stmt = mysqli_prepare($db, 'INSERT INTO qna (userid, title, content) VALUES (?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'sss', $userid, $title, $content);
$saved = mysqli_stmt_execute($stmt);

if (($_POST['return_to'] ?? '') === 'qna_page') {
    set_flash('qna_message', $saved ? '문의가 등록되었습니다.' : '등록 실패');
    header('Location: /coffee/pages/qna.php');
    exit;
}

header('Location: /coffee/pages/news.php?type=qna');
exit;
