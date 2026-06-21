<?php

require_once __DIR__ . '/../functions/helpers.php';
require_once __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = mysqli_prepare($db, 'SELECT * FROM events WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$event = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$event) {
    die('존재하지 않는 이벤트입니다.');
}
$stmt = mysqli_prepare($db, 'SELECT id, title FROM events WHERE id < ? ORDER BY id DESC LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$prevResult = mysqli_stmt_get_result($stmt);

$stmt = mysqli_prepare($db, 'SELECT id, title FROM events WHERE id > ? ORDER BY id ASC LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$nextResult = mysqli_stmt_get_result($stmt);

$prevEvent = mysqli_fetch_assoc($prevResult);
$nextEvent = mysqli_fetch_assoc($nextResult);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= e($event['title']) ?></title>

<link rel="stylesheet" href="/coffee/assets/css/header.css">
<link rel="stylesheet" href="/coffee/assets/css/nav.css">
<link rel="stylesheet" href="/coffee/assets/css/event.css">

</head>
<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<main class="event-view">

<a
    href="/coffee/pages/event.php"
    class="back-btn"
>
← 이벤트 목록
</a>

<h1><?= e($event['title']) ?></h1>

<p class="period">
<?= e($event['period']) ?>
</p>

<img
    src="<?= e(image_url($event['image'], 'event')) ?>"
    alt="<?= e($event['title']) ?>"
>
<div class="event-nav">

    <?php if($prevEvent): ?>
    <div class="nav-item">
        <strong>← 이전 이벤트</strong>

        <a href="/coffee/pages/event_view.php?id=<?= $prevEvent['id'] ?>">
            <?= e($prevEvent['title']) ?>
        </a>
    </div>
    <?php endif; ?>

    <?php if($nextEvent): ?>
    <div class="nav-item">
        <strong>다음 이벤트 →</strong>

        <a href="/coffee/pages/event_view.php?id=<?= $nextEvent['id'] ?>">
            <?= e($nextEvent['title']) ?>
        </a>
    </div>
    <?php endif; ?>

</div>

</main>

<script src="/coffee/assets/js/nav.js"></script>
<?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
