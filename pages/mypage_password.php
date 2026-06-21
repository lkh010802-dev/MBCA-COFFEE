<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();
$errorMessage = pull_flash('password_error', '');
$successMessage = pull_flash('password_success', '');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>비밀번호 변경 | MBCA COFFEE</title>

    <link rel="stylesheet" href="/coffee/assets/css/header.css">
    <link rel="stylesheet" href="/coffee/assets/css/nav.css">
    <link rel="stylesheet" href="/coffee/assets/css/notice.css">
</head>
<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<main class="write-page">

    <section class="write-wrap">

        <h1>비밀번호 변경</h1>

        <?php if($errorMessage): ?>
            <p class="form-message error">
                <?= e($errorMessage) ?>
            </p>
        <?php endif; ?>

        <?php if($successMessage): ?>
            <p class="form-message success">
                <?= e($successMessage) ?>
            </p>
        <?php endif; ?>

        <form method="post" action="/coffee/actions/password_update.php">
<?= csrf_field() ?>

            <div class="form-row">
                <label>현재 비밀번호</label>

                <input
                    type="password"
                    name="current_password"
                    placeholder="현재 비밀번호를 입력하세요."
                    required
                >
            </div>

            <div class="form-row">
                <label>새 비밀번호</label>

                <input
                    type="password"
                    name="new_password"
                    placeholder="영문, 숫자, 특수문자 포함 8자 이상"
                    required
                >
            </div>

            <div class="form-row">
                <label>새 비밀번호 확인</label>

                <input
                    type="password"
                    name="new_password_check"
                    placeholder="새 비밀번호를 한 번 더 입력하세요."
                    required
                >
            </div>

            <div class="form-buttons">

                <a
                    href="/coffee/pages/mypage.php"
                    class="cancel-btn"
                >
                    취소
                </a>

                <button
                    type="submit"
                    class="submit-btn"
                >
                    비밀번호 변경 →
                </button>

            </div>

        </form>

    </section>

</main>

<script src="/coffee/assets/js/nav.js"></script>
<?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
