<?php

require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /coffee/pages/notice_write.php');
    exit;
}
verify_csrf_or_fail();

$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$isPinned = isset($_POST['is_pinned']) ? 1 : 0;
$writer = $_SESSION['userid'];
$stmt = mysqli_prepare($db, 'INSERT INTO notices (title, content, writer, is_pinned) VALUES (?, ?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'sssi', $title, $content, $writer, $isPinned);

if (!mysqli_stmt_execute($stmt)) {
    exit(mysqli_error($db));
}

header('Location: /coffee/pages/news.php?type=notice');
exit;
