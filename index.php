<?php
require_once __DIR__ . '/includes/auth.php';
ensure_session_started();
$weatherRecommend = [
  'location' => '위치 확인중...',
  'temperature' => '--°C',
  'condition' => '날씨 확인중...',
  'summary' => '현재 날씨를 불러오는 중입니다.',
  'menuName' => '추천 음료',
  'description' => '잠시만 기다려주세요.',
  'image' => '/coffee/assets/images/cat.png'
];
require_once __DIR__ . '/config/database.php';

$eventsResult = mysqli_query(
    $db,
    "
    SELECT *
    FROM events
    WHERE end_date >= CURDATE()
    ORDER BY id DESC
    LIMIT 3
    "
);

$events = [];

while($row = mysqli_fetch_assoc($eventsResult)){
    $events[] = $row;
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MBCA COFFEE</title>
  <link rel="stylesheet" href="/coffee/assets/css/header.css">
  <link rel="stylesheet" href="/coffee/assets/css/nav.css">
  <link rel="stylesheet" href="/coffee/assets/css/home.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
</head>
<body>
  <?php include __DIR__ . '/includes/header.php'; ?>

<main class="snap-container">
  <section class="snap-section hero">
    <div class="hero-copy">
      <p class="hero-kicker">학생이라면 더 가볍게</p>

      <h1>
        <span>MBCA</span>
        <strong>COFFEE</strong>
      </h1>

      <p class="hero-label">STUDENTS FREE</p>

      <p class="hero-desc">
        MBCA COFFEE는 학생들의 일상에<br>
        작은 여유와 활력을 더합니다. 
      </p>

      <div class="hero-buttons">
        <a class="hero-button primary" href="/coffee/pages/menu.php">메뉴 보기</a>
        <a class="hero-button secondary" href="/coffee/pages/stores.php">매장 찾기</a>
      </div>
    </div>

    <div class="scroll-guide">
      <span>⌄</span>
      <small>SCROLL</small>
    </div>
  </section>
<section class="snap-section season-section">
  <div class="season-bg" aria-hidden="true"></div>

  <div class="swiper season-swiper">
    <div class="swiper-wrapper">
      <article class="swiper-slide season-slide"
        data-bg="/coffee/assets/images/MBCA-Watermelon Crush Smoothie-bg.png">
        <div class="season-copy">
          <span class="season-watermark">Crush!<br>Watermelon</span>
          <p class="season-label">수박 크러쉬 스무디</p>
          <h2>여름을 한 컵에 담다</h2>
          <p>시원한 수박의 청량함을 담은 <br>SUMMER시즌 메뉴</p>
          <strong>Watermelon Crush Smoothie</strong>
        </div>
        <div class="season-visual">
          <img src="/coffee/assets/images/MBCA-Watermelon Crush Smoothie.png" alt="Watermelon Smoothie">
        </div>
      </article>

      <article class="swiper-slide season-slide"
        data-bg="/coffee/assets/images/MBCA-mango-bg.png">
        <div class="season-copy">
          <span class="season-watermark">MANGO COCO</span>
          <p class="season-label">망고 코코 스무디</p>
          <h2>햇살처럼 달콤하게</h2>
          <p>망고의 달콤함이 부드럽게 퍼지는 시즌 스무디</p>
          <strong>Mango Coco Smoothie</strong>
        </div>
        <div class="season-visual">
          <img src="/coffee/assets/images/MBCA-mango.png" alt="Mango Coco Smoothie">
        </div>
      </article>

      <article class="swiper-slide season-slide"
        data-bg="/coffee/assets/images/MBCA-Lychee-bg.png">
        <div class="season-copy">
          <span class="season-watermark">Cool, Clean<br>Salted</span>
          <p class="season-label">솔티드 리치</p>
          <h2>한 모금의 짭짤한 청량감</h2>
          <p>달콤함 끝에 스치는 바다의 한 조각</p>
          <strong>Salted Lychee</strong>
        </div>
        <div class="season-visual">
          <img src="/coffee/assets/images/MBCA-Lychee.png" alt="Lime Ade">
        </div>
      </article>

      <article class="swiper-slide season-slide"
        data-bg="/coffee/assets/images/MBCA-Patbingsu Parfait-bg.png">
        <div class="season-copy">
          <span class="season-watermark">SWEET SUMMER<br>IN EVERY SPOON</span>
          <p class="season-label">팥빙 파르페</p>
          <h2>달콤함을 층층이 쌓다</h2>
          <p>추억은 달콤하게, 여름은 시원하게</p>
          <strong>Patbingsu Parfait</strong>
        </div>
        <div class="season-visual">
          <img src="/coffee/assets/images/MBCA-Patbingsu Parfait.png" alt="Blue Lemon">
        </div>
      </article>
    </div>
    <div class="season-pagination"></div>
  </div>
</section>

<section class="snap-section event-section">

  <div class="event-inner">

    <div class="event-head">
      <p>MBCA EVENT</p>
      <h2>지금 진행 중인 혜택</h2>
      <a href="/coffee/pages/event.php">
          전체 이벤트 보기
      </a>
    </div>

    <div class="event-grid">

      <?php foreach ($events as $index => $event): ?>
        <article class="event-card <?= $index === 0 ? 'event-card-main' : '' ?>">
          <a href="/coffee/pages/event_view.php?id=<?= $event['id'] ?>">

            <img src="<?= e(image_url($event['image'], 'event')) ?>" alt="<?= e($event['title']) ?>">

            <div class="event-info">
              <span><?= $event['badge'] ?></span>

              <h3><?= $event['title'] ?></h3>

              <p><?= $event['description'] ?></p>

              <small><?= $event['period'] ?></small>
            </div>

          </a>
        </article>
      <?php endforeach; ?>

    </div>

    <div class="event-benefits">

      <div class="event-benefit">
        <img src="/coffee/assets/images/event/event-gift.png" alt="다양한 혜택">
        <h3>다양한 혜택</h3>
        <p>매월 새로운 이벤트</p>
      </div>

      <div class="event-benefit">
        <img src="/coffee/assets/images/event/event-point.png" alt="포인트 적립">
        <h3>포인트 적립</h3>
        <p>결제할수록 쌓이는 혜택</p>
      </div>

      <div class="event-benefit">
        <img src="/coffee/assets/images/event/event-touch.png" alt="간편한 참여">
        <h3>간편한 참여</h3>
        <p>누구나 쉽게 참여 가능</p>
      </div>

      <div class="event-benefit">
        <img src="/coffee/assets/images/event/event-calendar.png" alt="기간 한정">
        <h3>기간 한정</h3>
        <p>놓치기 전에 확인하세요</p>
      </div>

    </div>

  </div>

</section>

<section
    class="snap-section weather-section"
    id="weather-section"
>
  <div class="weather-inner">
    <div class="weather-copy">
      <p class="weather-label">TODAY WEATHER PICK</p>
      <h2 id="weather-menu">
          오늘 날씨엔<br>
          추천 음료
      </h2>
      <p id="weather-summary">
          현재 날씨를 불러오는 중입니다.
      </p>
      <strong id="weather-description">
          잠시만 기다려주세요.
      </strong>
      <a
    id="weather-link"
    class="weather-button"
    href="/coffee/pages/menu.php"
>
    메뉴 자세히 보기
</a>
    </div>

    <div class="weather-card">
      <div>

    <h3 id="weather-temp">
        <?= $weatherRecommend['temperature'] ?>
    </h3>


</div>

      <img
    id="weather-image"
    src="<?= e(image_url($weatherRecommend['image'], 'menu')) ?>"
    alt="<?= e($weatherRecommend['menuName']) ?>"
>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
</main>

  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="/coffee/assets/js/season-menu.js"></script>
  <script src="/coffee/assets/js/lang.js"></script>
  <script src="/coffee/assets/js/nav.js"></script>
  <script src="/coffee/assets/js/weather.js"></script>
</body>
</html>
