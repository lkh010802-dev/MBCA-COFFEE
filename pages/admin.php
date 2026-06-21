<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

include __DIR__ . '/../config/database.php';
$userCount = mysqli_fetch_row(
    mysqli_query(
        $db,
        "SELECT COUNT(*) FROM users"
    )
)[0];

$noticeCount = mysqli_fetch_row(
    mysqli_query(
        $db,
        "SELECT COUNT(*) FROM notices"
    )
)[0];

$qnaCount = mysqli_fetch_row(
    mysqli_query(
        $db,
        "SELECT COUNT(*) FROM qna"
    )
)[0];

$waitingCount = mysqli_fetch_row(
    mysqli_query(
        $db,
        "SELECT COUNT(*) FROM qna
         WHERE status='waiting'"
    )
)[0];

$answeredCount = mysqli_fetch_row(
    mysqli_query(
        $db,
        "SELECT COUNT(*) FROM qna
         WHERE status='answered'"
    )
)[0];
$menuCount = mysqli_fetch_row(
    mysqli_query(
        $db,
        "SELECT COUNT(*) FROM menus"
    )
)[0];

$eventCount = mysqli_fetch_row(
    mysqli_query(
        $db,
        "SELECT COUNT(*) FROM events"
    )
)[0];
$recentQna = mysqli_query(
    $db,
    "SELECT
        id,
        title,
        status,
        created_at
     FROM qna
     ORDER BY id DESC
     LIMIT 5"
);
$recentUsers = mysqli_query(
    $db,
    "SELECT
        userid,
        name,
        created_at
     FROM users
     ORDER BY id DESC
     LIMIT 5"
);
$page = isset($_GET['page'])
    ? (int)$_GET['page']
    : 1;

$perPage = 10;

$page = isset($_GET['page'])
    ? (int)$_GET['page']
    : 1;

$perPage = 10;

$offset =
    ($page - 1) * $perPage;

    $waitingQna = mysqli_query(
    $db,
    "SELECT
        id,
        title,
        userid,
        created_at
     FROM qna
     WHERE status='waiting'
     ORDER BY id DESC
     LIMIT $offset, $perPage"
);

$totalWaiting = mysqli_fetch_row(
    mysqli_query(
        $db,
        "SELECT COUNT(*)
         FROM qna
         WHERE status='waiting'"
    )
)[0];

$totalPages =
    ceil($totalWaiting / $perPage);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<link rel="stylesheet" href="/coffee/assets/css/admin.css">
<meta charset="UTF-8">
<title>관리자 대시보드</title>
</head>
<body>
<?php include __DIR__ . '/../includes/admin_nav.php'; ?>

<h1>관리자 대시보드</h1>

<hr>

<div class="dashboard-cards">

    <div class="card">
        <h3>회원</h3>
        <strong><?= $userCount ?></strong>
    </div>

    <div class="card">
        <h3>공지</h3>
        <strong><?= $noticeCount ?></strong>
    </div>

    <div class="card">
        <h3>문의</h3>
        <strong><?= $qnaCount ?></strong>
    </div>

    <div class="card">
        <h3>답변대기</h3>
        <strong><?= $waitingCount ?></strong>
    </div>

    <div class="card">
        <h3>메뉴</h3>
        <strong><?= $menuCount ?></strong>
    </div>

    <div class="card">
        <h3>이벤트</h3>
        <strong><?= $eventCount ?></strong>
    </div>

</div>
<h2>최근 문의</h2>
<table border="1">

<tr>
    <th>제목</th>
    <th>상태</th>
    <th>작성일</th>
</tr>

<?php while($qna = mysqli_fetch_assoc($recentQna)): ?>

<tr>

<td>
<a href="/coffee/pages/news_view.php?id=<?= $qna['id'] ?>&type=qna">
<?= e($qna['title']) ?>
</a>
</td>

<td>
<?= $qna['status'] ?>
</td>

<td>
<?= substr($qna['created_at'],0,10) ?>
</td>

</tr>

<?php endwhile; ?>


</table>
<h2 id="waiting">답변 대기 문의</h2>

<?php if(mysqli_num_rows($waitingQna) > 0): ?>

<table border="1">

<tr>
    <th>번호</th>
    <th>작성자</th>
    <th>제목</th>
    <th>작성일</th>
</tr>

<?php while($qna = mysqli_fetch_assoc($waitingQna)): ?>

<tr>

<td>
<?= $qna['id'] ?>
</td>

<td>
<?= e($qna['userid']) ?>
</td>

<td>

<a href="/coffee/pages/news_view.php?id=<?= $qna['id'] ?>&type=qna">

<?= e($qna['title']) ?>

</a>

</td>

<td>
<?= substr($qna['created_at'],0,10) ?>
</td>

</tr>

<?php endwhile; ?>

</table>

<?php else: ?>

<p>답변 대기 문의가 없습니다.</p>

<?php endif; ?>
<div class="pagination">

<?php for($i=1; $i<=$totalPages; $i++): ?>

    <a
        href="?page=<?= $i ?>#waiting"
        class="<?= $page == $i ? 'active' : '' ?>"
    >
        <?= $i ?>
    </a>

<?php endfor; ?>

</div>
<div class="dashboard-box">
<h2>최근 가입 회원</h2>

<table border="1">

<tr>
    <th>아이디</th>
    <th>이름</th>
    <th>가입일</th>
</tr>

<?php while($member = mysqli_fetch_assoc($recentUsers)): ?>

<tr>

<td>
<?= e($member['userid']) ?>
</td>

<td>
<?= e($member['name']) ?>
</td>

<td>
<?= substr($member['created_at'],0,10) ?>
</td>

</tr>

<?php endwhile; ?>

</table>
</div>
</body>
</html>
