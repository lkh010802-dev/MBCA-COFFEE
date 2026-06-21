<?php
session_start();

include __DIR__ . '/../config/database.php';

if (!isset($_SESSION['userid'])) {
    header('Location: /coffee/pages/login.php');
    exit;
}

$errorMessage = '';
$successMessage = '';

$userid = $_SESSION['userid'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $currentPassword = trim($_POST['current_password']);
    $newPassword = trim($_POST['new_password']);
    $newPasswordCheck = trim($_POST['new_password_check']);

    $result = mysqli_query(
        $db,
        "SELECT *
         FROM users
         WHERE userid='$userid'"
    );

    $user = mysqli_fetch_assoc($result);

    if (
        !password_verify(
            $currentPassword,
            $user['password']
        )
    ) {
        $errorMessage = '현재 비밀번호가 올바르지 않습니다.';
    } elseif ($newPassword !== $newPasswordCheck) {
        $errorMessage = '새 비밀번호가 일치하지 않습니다.';
    } elseif (
        !preg_match(
            '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/',
            $newPassword
        )
    ) {
        $errorMessage = '영문, 숫자, 특수문자를 포함한 8자 이상 입력해주세요.';
    } else {

        $hashedPassword = password_hash(
            $newPassword,
            PASSWORD_DEFAULT
        );

        mysqli_query(
            $db,
            "UPDATE users
             SET password='$hashedPassword'
             WHERE userid='$userid'"
        );

        $successMessage = '비밀번호가 변경되었습니다.';
    }
}
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
                <?= htmlspecialchars($errorMessage) ?>
            </p>
        <?php endif; ?>

        <?php if($successMessage): ?>
            <p class="form-message success">
                <?= htmlspecialchars($successMessage) ?>
            </p>
        <?php endif; ?>

        <form method="post">

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