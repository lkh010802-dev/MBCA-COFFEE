<?php

session_start();

include __DIR__ . '/../config/database.php';

if (!isset($_SESSION['userid'])) {

    header('Location: /coffee/pages/login.php');
    exit;
}

$userid = $_SESSION['userid'];

$sql = "SELECT * FROM users
        WHERE userid='$userid'";

$result = mysqli_query($db, $sql);

$user = mysqli_fetch_assoc($result);

$qnaResult = mysqli_query(
    $db,
    "SELECT
        id,
        title,
        status,
        created_at
     FROM qna
     WHERE userid = '$userid'
     ORDER BY id DESC
     LIMIT 3"
);

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>MY PAGE | MBCA COFFEE</title>

<link rel="stylesheet" href="/coffee/assets/css/header.css">
<link rel="stylesheet" href="/coffee/assets/css/nav.css">
<link rel="stylesheet" href="/coffee/assets/css/mypage.css">
</head>

<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<main class="mypage">

<section class="mypage-grid">

    <article class="mypage-box">
        <h2>내 정보</h2>

        <div class="info-row">
            <strong>이름</strong>
            <span><?= $user['name'] ?></span>
        </div>

        <div class="info-row">
            <strong>이메일</strong>
            <span><?= $user['email'] ?></span>
        </div>

        <div class="mypage-actions">
            <a href="/coffee/pages/mypage_edit.php">
                정보 수정
            </a>

            <a href="/coffee/pages/mypage_password.php">
                비밀번호 변경
            </a>
            <?php if($user['role'] === 'admin'): ?>
        <a href="/coffee/pages/admin.php" class="admin-link">
            관리자 대시보드
        </a>
    <?php endif; ?>
        </div>
    </article>

    <article class="mypage-box">
        <h2>내 문의내역</h2>

        <?php if(mysqli_num_rows($qnaResult) > 0): ?>

            <?php while($qna = mysqli_fetch_assoc($qnaResult)): ?>

                <div class="qna-item">
                    <strong>
                        <a href="/coffee/pages/news_view.php?id=<?= $qna['id'] ?>&type=qna">
                            <?= htmlspecialchars($qna['title']) ?>
                        </a>
                    </strong>

                    <span>
                        <?= $qna['status'] === 'answered'
                            ? '답변완료'
                            : '답변대기' ?>
                    </span>

                    <small>
                        <?= date('Y-m-d', strtotime($qna['created_at'])) ?>
                    </small>
                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <p>등록된 문의가 없습니다.</p>

        <?php endif; ?>
    </article>

</section>

<script src="/coffee/assets/js/nav.js"></script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>