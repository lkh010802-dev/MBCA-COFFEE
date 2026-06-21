<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_post('/coffee/pages/admin_events.php');
verify_csrf_or_fail();
require_once __DIR__ . '/../config/database.php';

$id = (int)($_POST['id'] ?? 0);
$stmt = mysqli_prepare($db, 'DELETE FROM events WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

header('Location: /coffee/pages/admin_events.php');
exit;
