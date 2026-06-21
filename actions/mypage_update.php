<?php

require_once __DIR__ . '/../includes/auth.php';
require_login();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /coffee/pages/mypage_edit.php');
    exit;
}
verify_csrf_or_fail();

$userid = $_SESSION['userid'];
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');

$stmt = mysqli_prepare($db, 'UPDATE users SET name = ?, email = ?, phone = ? WHERE userid = ?');
mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $phone, $userid);
mysqli_stmt_execute($stmt);

header('Location: /coffee/pages/mypage.php');
exit;
