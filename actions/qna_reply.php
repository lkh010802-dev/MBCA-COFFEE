<?php

require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /coffee/pages/news.php?type=qna');
    exit;
}
verify_csrf_or_fail();

$id = (int)($_POST['qna_id'] ?? 0);
$reply = trim($_POST['reply'] ?? '');
$adminId = $_SESSION['userid'];

$stmt = mysqli_prepare($db, 'INSERT INTO qna_reply (qna_id, admin_id, content) VALUES (?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'iss', $id, $adminId, $reply);
mysqli_stmt_execute($stmt);
$stmt = mysqli_prepare($db, "UPDATE qna SET status = 'answered' WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

header("Location: /coffee/pages/news_view.php?id=$id&type=qna");
exit;
