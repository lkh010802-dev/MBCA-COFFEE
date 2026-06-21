<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /coffee/pages/signup.php');
    exit;
}
verify_csrf_or_fail();

$errors = [];
$userId = trim($_POST['user_id'] ?? '');
$userName = trim($_POST['user_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = trim($_POST['password'] ?? '');
$passwordCheck = trim($_POST['password_check'] ?? '');

if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d_]{6,}$/', $userId)) {
    $errors['user_id'] = '영문과 숫자를 포함한 6자 이상';
} else {
    $stmt = mysqli_prepare($db, 'SELECT userid FROM users WHERE userid = ?');
    mysqli_stmt_bind_param($stmt, 's', $userId);
    mysqli_stmt_execute($stmt);
    $checkResult = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($checkResult) > 0) {
        $errors['user_id'] = '이미 사용중인 아이디';
    }
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = '올바른 이메일 형식이 아닙니다';
}
if (!preg_match('/^010-\d{4}-\d{4}$/', $phone)) {
    $errors['phone'] = '010-0000-0000 형식';
}
if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/', $password)) {
    $errors['password'] = '영문, 숫자, 특수문자를 포함한 8자 이상';
}
if ($password !== $passwordCheck) {
    $errors['password_check'] = '비밀번호가 일치하지 않습니다';
}
if (!isset($_POST['agree_terms'])) {
    $errors['agree_terms'] = '약관에 동의해주세요';
}

if ($errors) {
    set_flash('signup_errors', $errors);
    header('Location: /coffee/pages/signup.php');
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = mysqli_prepare($db, 'INSERT INTO users (userid, password, name, email, phone) VALUES (?, ?, ?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'sssss', $userId, $hashedPassword, $userName, $email, $phone);

if (!mysqli_stmt_execute($stmt)) {
    set_flash('signup_error', '회원가입 처리 중 오류가 발생했습니다.');
    header('Location: /coffee/pages/signup.php');
    exit;
}

header('Location: /coffee/pages/login.php');
exit;
