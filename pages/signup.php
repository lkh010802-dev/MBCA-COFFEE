<?php
require_once __DIR__ . '/../includes/auth.php';
$errorMessage = pull_flash('signup_error', '');
$errors = pull_flash('signup_errors', []);
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
        <p class="auth-error"><?= e($errorMessage) ?></p>
      <?php endif; ?>

      <form class="auth-form" action="/coffee/actions/signup.php" method="post">
<?= csrf_field() ?>
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
  <script src="/coffee/assets/js/signup.js"></script>

  <script src="/coffee/assets/js/nav.js"></script>
  <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
