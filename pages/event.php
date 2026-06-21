<?php

require_once __DIR__ . '/../config/database.php';

$isAdmin = true;

$sql = "
SELECT *
FROM events
ORDER BY id DESC
";

$result = mysqli_query($db, $sql);

function e($value) {
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
  <title>이벤트 | MBCA COFFEE</title>
  <link rel="stylesheet" href="/coffee/assets/css/header.css">
  <link rel="stylesheet" href="/coffee/assets/css/nav.css">
  <link rel="stylesheet" href="/coffee/assets/css/event.css">
</head>
<body>
  <?php include __DIR__ . '/../includes/header.php'; ?>

  <main class="event-page">
    <section class="event-hero">
      <p>MBCA EVENT</p>
      <h1>이벤트</h1>
      <span>MBCA COFFEE의 다양한 이벤트와 혜택을 확인해보세요.</span>
    </section>

    <section class="event-search">
      <input id="eventSearchInput" type="search" placeholder="검색어 입력">
      <button id="eventSearchButton" type="button">검색</button>
    </section>

    <section class="event-grid" id="eventGrid">
      <?php while($event = mysqli_fetch_assoc($result)): ?>
        <?php

          $isEnded =
              strtotime(date('Y-m-d'))
              >
              strtotime($event['end_date']);

          ?>
        <article
          class="event-card <?= $isEnded ? 'is-ended' : '' ?>"
          data-title="<?= e($event['title']) ?>"
          data-description="<?= e($event['description']) ?>"
        >
          <a
              href="/coffee/pages/event_view.php?id=<?= $event['id'] ?>"
              aria-label="<?= e($event['title']) ?> 상세 보기"
          >
            <div class="event-image">
              <img src="<?= e($event['thumbnail']) ?>" alt="<?= e($event['title']) ?>">
              <?php if ($isEnded): ?>
                <div class="ended-overlay">
                  <strong>이벤트 종료</strong>
                </div>
              <?php endif; ?>
            </div>

            <div class="event-info">
              <?php if(!$isEnded): ?>

              <span class="event-status active">
                  진행중
              </span>

              <?php endif; ?>
              <h2><?= e($event['title']) ?></h2>
              <p><?= e($event['description']) ?></p>
              <small><?= e($event['start_date']) ?> - <?= e($event['end_date']) ?></small>
            </div>
          </a>
        </article>
      <?php endwhile; ?>
    </section>

    <p class="event-empty" id="eventEmpty">검색 결과가 없습니다.</p>
  </main>

  <script src="/coffee/assets/js/nav.js"></script>
  <script src="/coffee/assets/js/event.js"></script>
  <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>