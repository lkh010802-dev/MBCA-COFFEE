<?php

require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /coffee/pages/news.php?type=notice');
    exit;
}
verify_csrf_or_fail();

$id = (int)($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$isPinned = isset($_POST['is_pinned']) ? 1 : 0;

$stmt = mysqli_prepare($db, 'UPDATE notices SET title = ?, content = ?, is_pinned = ? WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'ssii', $title, $content, $isPinned, $id);
mysqli_stmt_execute($stmt);

header("Location: /coffee/pages/news_view.php?id=$id&type=notice");
exit;
