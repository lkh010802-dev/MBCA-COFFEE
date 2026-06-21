<?php

/*
=========================================
MBCA COFFEE MY PAGE EDIT

기능
- 회원 정보 수정
=========================================
*/

session_start();

include __DIR__ . '/../config/database.php';

if (!isset($_SESSION['userid'])) {

    header('Location: /coffee/pages/login.php');
    exit;
}

$userid = $_SESSION['userid'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    mysqli_query(
        $db,
        "UPDATE users
         SET
            name='$name',
            email='$email',
            phone='$phone'
         WHERE userid='$userid'"
    );

    header(
        'Location: /coffee/pages/mypage.php'
    );
    exit;
}

$result = mysqli_query(
    $db,
    "SELECT *
     FROM users
     WHERE userid='$userid'"
);

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

        <form method="post">

            <div class="form-row">
                <label>이름</label>

                <input
                    type="text"
                    name="name"
                    value="<?= htmlspecialchars($user['name']) ?>"
                    required
                >
            </div>

            <div class="form-row">
                <label>이메일</label>

                <input
                    type="email"
                    name="email"
                    value="<?= htmlspecialchars($user['email']) ?>"
                    required
                >
            </div>

            <div class="form-row">
                <label>전화번호</label>

                <input
                    type="text"
                    name="phone"
                    value="<?= htmlspecialchars($user['phone']) ?>"
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