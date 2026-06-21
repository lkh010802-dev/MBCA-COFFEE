<?php
require_once __DIR__ . '/../includes/auth.php';
$errorMessage = pull_flash('login_error', '');
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>로그인 | MBCA COFFEE</title>
  <link rel="stylesheet" href="/coffee/assets/css/header.css">
  <link rel="stylesheet" href="/coffee/assets/css/nav.css">
  <link rel="stylesheet" href="/coffee/assets/css/forms.css">
</head>
<body>
  <?php include __DIR__ . '/../includes/header.php'; ?>

  <main class="auth-page">
    <section class="auth-card">
      <div class="auth-head">
        <p>MBCA MEMBER</p>
        <h1>로그인</h1>
        <span>MBCA COFFEE의 다양한 혜택을 만나보세요.</span>
      </div>

      <?php if ($errorMessage): ?>
        <p class="auth-error"><?= e($errorMessage) ?></p>
      <?php endif; ?>

      <form class="auth-form" action="/coffee/actions/login.php" method="post">
<?= csrf_field() ?>
        <label for="userId">아이디</label>
        <input id="userId" name="user_id" type="text" placeholder="아이디를 입력하세요" required>

        <label for="password">비밀번호</label>
        <input id="password" name="password" type="password" placeholder="비밀번호를 입력하세요" required>

        <button type="submit">로그인</button>
      </form>

      <div class="auth-links">
        <a href="#">아이디 찾기</a>
        <a href="#">비밀번호 찾기</a>
        <a href="/coffee/pages/signup.php">회원가입</a>
      </div>
    </section>
  </main>

  <script src="/coffee/assets/js/nav.js"></script>
  <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
