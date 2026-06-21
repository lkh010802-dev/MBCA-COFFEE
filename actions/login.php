<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /coffee/pages/login.php');
    exit;
}
verify_csrf_or_fail();

$userId = trim($_POST['user_id'] ?? '');
$password = trim($_POST['password'] ?? '');
$stmt = mysqli_prepare($db, 'SELECT * FROM users WHERE userid = ?');
mysqli_stmt_bind_param($stmt, 's', $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['userid'] = $user['userid'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];

    header('Location: /coffee/index.php');
    exit;
}

set_flash('login_error', '아이디 또는 비밀번호가 일치하지 않습니다.');
header('Location: /coffee/pages/login.php');
exit;
