<?php

include __DIR__ . '/../config/database.php';
$errorMessage = '';
$errors = [];                               //회원가입 처리구역

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userId = trim($_POST['user_id']);
if(
    !preg_match(
        '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d_]{6,}$/',
        $userId
    )
){
    $errors['user_id']
        = '영문과 숫자를 포함한 6자 이상';
}
else
{
    $checkResult = mysqli_query(
        $db,
        "SELECT userid
         FROM users
         WHERE userid='$userId'"
    );

    if(mysqli_num_rows($checkResult) > 0){

        $errors['user_id']
            = '이미 사용중인 아이디';
    }
}
    $userName = trim($_POST['user_name']);
    $email = trim($_POST['email']);
    if(
    !filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
    )
){
    $errors['email']
        = '올바른 이메일 형식이 아닙니다';
}
$phone = trim($_POST['phone']);

if(
    !preg_match(
        '/^010-\d{4}-\d{4}$/',
        $phone
    )
){
    $errors['phone']
        = '010-0000-0000 형식';
}

$password = trim($_POST['password']);

if(
    !preg_match(
        '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/',
        $password
    )
){
    $errors['password']
        = '영문, 숫자, 특수문자 포함 8자 이상';
}
    $passwordCheck = trim($_POST['password_check']);
    if(
    !isset($_POST['agree_terms'])
){
    $errors['agree_terms']
        = '약관 동의 필수';
}

 if ($password !== $passwordCheck) {

    $errors['password_check']
        = '비밀번호 불일치';
}

if (empty($errors)) {

$hashedPassword =
    password_hash(
        $password,
        PASSWORD_DEFAULT
    );

$sql = "INSERT INTO users
        (userid, password, name, email, phone)
        VALUES
        ('$userId',
         '$hashedPassword',
         '$userName',
         '$email',
         '$phone')";
    $result = mysqli_query($db, $sql);

    if ($result) {

        echo "<script>
                alert('회원가입 완료');
                location.href='/coffee/pages/login.php';
              </script>";
        exit;

    } else {

        $errorMessage = '회원가입 실패';
    }
}
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>회원가입 | MBCA COFFEE</title>
  <link rel="stylesheet" href="/coffee/assets/css/header.css">
  <link rel="stylesheet" href="/coffee/assets/css/nav.css">
  <link rel="stylesheet" href="/coffee/assets/css/forms.css">
</head>
<body>
  <?php include __DIR__ . '/../includes/header.php'; ?>

  <main class="auth-page">
    <section class="auth-card signup-card">
      <div class="auth-head">
        <p>MBCA MEMBER</p>
        <h1>회원가입</h1>
        <span>MBCA COFFEE의 멤버가 되어 다양한 소식을 받아보세요.</span>
      </div>

      <?php if ($errorMessage): ?>
        <p class="auth-error"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
      <?php endif; ?>

      <form class="auth-form" action="#" method="post">
        <label for="userId">

아이디

<?php
if(isset($errors['user_id']))
{
    echo '<small>'
        . $errors['user_id']
        . '</small>';
}
?>

</label>
        <input id="userId" name="user_id" type="text" placeholder="아이디를 입력하세요" required>

        <label for="userName">이름</label>
        <input id="userName" name="user_name" type="text" placeholder="이름을 입력하세요" required>

        <label for="email">

이메일

<?php
if(isset($errors['email']))
{
    echo '<small>'
        . $errors['email']
        . '</small>';
}
?>

</label>
        <input id="email" name="email" type="email" placeholder="example@mbca.com" required>

        <label for="phone">

휴대폰 번호

<?php
if(isset($errors['phone']))
{
    echo '<small>'
        . $errors['phone']
        . '</small>';
}
?>

</label>
        <input
    id="phone"
    name="phone"
    type="tel"
    maxlength="13"
    placeholder="010-0000-0000"
>

        <label for="password">

비밀번호

<?php
if(isset($errors['password']))
{
    echo '<small>'
        . $errors['password']
        . '</small>';
}
?>

</label>
        <input id="password" name="password" type="password" placeholder="비밀번호를 입력하세요" required>

<label for="passwordCheck">

비밀번호 확인

<?php
if(isset($errors['password_check']))
{
    echo '<small>'
        . $errors['password_check']
        . '</small>';
}
?>

</label>

<input
    id="passwordCheck"
    name="password_check"
    type="password"
    placeholder="비밀번호를 다시 입력하세요"
    required
>

<?php
if(isset($errors['agree_terms']))
{
    echo '<small>'
        . $errors['agree_terms']
        . '</small>';
}
?>

<label class="check-label">

    <input
        name="agree_terms"
        type="checkbox"
    >

    이용약관 및 개인정보 처리방침에 동의합니다.

</label>

        <button type="submit">회원가입</button>
      </form>

      <div class="auth-links">
        <a href="/coffee/pages/login.php">이미 계정이 있나요? 로그인</a>
      </div>
    </section>
  </main>
<script>

const phoneInput =
    document.getElementById('phone');

phoneInput.addEventListener(
    'input',
    function () {

        let value =
            this.value.replace(/\D/g, '');

        if(value.length > 11){

            value =
                value.substring(0, 11);
        }

        if(value.length < 4){

            this.value = value;

        }else if(value.length < 8){

            this.value =
                value.replace(
                    /(\d{3})(\d+)/,
                    '$1-$2'
                );

        }else{

            this.value =
                value.replace(
                    /(\d{3})(\d{4})(\d+)/,
                    '$1-$2-$3'
                );
        }
    }
);

</script>

  <script src="/coffee/assets/js/nav.js"></script>
  <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>