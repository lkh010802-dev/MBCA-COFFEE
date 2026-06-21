<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_post('/coffee/pages/news.php?type=notice');
verify_csrf_or_fail();
require_once __DIR__ . '/../config/database.php';

$id = (int)($_POST['id'] ?? 0);
$stmt = mysqli_prepare($db, 'DELETE FROM notices WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

header('Location: /coffee/pages/news.php?type=notice');
exit;
