<?php

require_once __DIR__ . '/../includes/auth.php';
require_login();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /coffee/pages/mypage_password.php');
    exit;
}
verify_csrf_or_fail();

$userid = $_SESSION['userid'];
$currentPassword = trim($_POST['current_password'] ?? '');
$newPassword = trim($_POST['new_password'] ?? '');
$newPasswordCheck = trim($_POST['new_password_check'] ?? '');
$stmt = mysqli_prepare($db, 'SELECT * FROM users WHERE userid = ?');
mysqli_stmt_bind_param($stmt, 's', $userid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user || !password_verify($currentPassword, $user['password'])) {
    set_flash('password_error', '현재 비밀번호가 올바르지 않습니다.');
} elseif ($newPassword !== $newPasswordCheck) {
    set_flash('password_error', '새 비밀번호가 일치하지 않습니다.');
} elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/', $newPassword)) {
    set_flash('password_error', '영문, 숫자, 특수문자를 포함한 8자 이상 입력해주세요.');
} else {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($db, 'UPDATE users SET password = ? WHERE userid = ?');
    mysqli_stmt_bind_param($stmt, 'ss', $hashedPassword, $userid);
    mysqli_stmt_execute($stmt);
    set_flash('password_success', '비밀번호가 변경되었습니다.');
}

header('Location: /coffee/pages/mypage_password.php');
exit;
