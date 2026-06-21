<?php

/*
=========================================
MBCA COFFEE MY PAGE EDIT

기능
- 회원 정보 수정
=========================================
*/

require_once __DIR__ . '/../includes/auth.php';
require_login();
include __DIR__ . '/../config/database.php';

$userid = $_SESSION['userid'];

$stmt = mysqli_prepare($db, 'SELECT * FROM users WHERE userid = ?');
mysqli_stmt_bind_param($stmt, 's', $userid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">

<title>정보 수정</title>

<link rel="stylesheet" href="/coffee/assets/css/header.css">
<link rel="stylesheet" href="/coffee/assets/css/nav.css">
<link rel="stylesheet" href="/coffee/assets/css/notice.css">

</head>

<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<main class="write-page">

    <section class="write-wrap">

        <h1>회원 정보 수정</h1>

        <form method="post" action="/coffee/actions/mypage_update.php">
<?= csrf_field() ?>

            <div class="form-row">
                <label>이름</label>

                <input
                    type="text"
                    name="name"
                    value="<?= e($user['name']) ?>"
                    required
                >
            </div>

            <div class="form-row">
                <label>이메일</label>

                <input
                    type="email"
                    name="email"
                    value="<?= e($user['email']) ?>"
                    required
                >
            </div>

            <div class="form-row">
                <label>전화번호</label>

                <input
                    type="text"
                    name="phone"
                    value="<?= e($user['phone']) ?>"
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
                    수정 완료 →
                </button>

            </div>

        </form>

    </section>

</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
