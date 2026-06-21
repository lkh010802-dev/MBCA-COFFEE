<?php

require_once __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);

$result = mysqli_query(
    $db,
    "SELECT * FROM events WHERE id=$id"
);

$event = mysqli_fetch_assoc($result);

if (!$event) {
    die('존재하지 않는 이벤트입니다.');
}
$prevResult = mysqli_query(
    $db,
    "
    SELECT id, title
    FROM events
    WHERE id < $id
    ORDER BY id DESC
    LIMIT 1
    "
);

$nextResult = mysqli_query(
    $db,
    "
    SELECT id, title
    FROM events
    WHERE id > $id
    ORDER BY id ASC
    LIMIT 1
    "
);

$prevEvent = mysqli_fetch_assoc($prevResult);
$nextEvent = mysqli_fetch_assoc($nextResult);

function e($value){
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= e($event['title']) ?></title>

<link rel="stylesheet" href="/coffee/assets/css/header.css">
<link rel="stylesheet" href="/coffee/assets/css/nav.css">

<style>

.event-view{
    max-width:1200px;
    margin:120px auto 80px;
    padding:0 20px;
}

.event-view h1{
    margin-bottom:12px;
}

.event-view .period{
    color:#666;
    font-weight:700;
    margin-bottom:30px;
}

.event-view img{
    width:100%;
    display:block;
}

.back-btn{
    display:inline-block;
    margin-bottom:30px;
    color:#222;
    text-decoration:none;
    font-weight:700;
}
.event-nav{
    margin-top:40px;

    border-top:1px solid #ddd;

    border-bottom:1px solid #ddd;
}

.nav-item{
    padding:20px 0;
}

.nav-item + .nav-item{
    border-top:1px solid #eee;
}

.nav-item strong{
    display:block;
    margin-bottom:8px;
    color:#666;
}

.nav-item a{
    color:#222;
    text-decoration:none;
    font-size:18px;
    font-weight:700;
}

.nav-item a:hover{
    text-decoration:underline;
}

</style>

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
    src="<?= e($event['image']) ?>"
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