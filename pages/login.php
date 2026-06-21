<?php

session_start();

include __DIR__ . '/../config/database.php';

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$userId = trim($_POST['user_id']);
$password = trim($_POST['password']);

$sql = "SELECT *
        FROM users
        WHERE userid='$userId'";

$result = mysqli_query($db, $sql);

$user = mysqli_fetch_assoc($result);

if (
    $user &&
    password_verify(
        $password,
        $user['password']
    )
) {

    $_SESSION['userid'] = $user['userid'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];

    echo "<script>
            alert('로그인 성공');
            location.href='/coffee/index.php';
          </script>";
    exit;

} else {

    $errorMessage =
        '아이디 또는 비밀번호가 일치하지 않습니다.';
}
}
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
        <p class="auth-error"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
      <?php endif; ?>

      <form class="auth-form" action="#" method="post">
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