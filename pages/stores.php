<?php

require_once __DIR__ . '/../functions/helpers.php';
require_once __DIR__ . '/../config/database.php';

$result = mysqli_query(
    $db,
    "
    SELECT *
    FROM stores
    ORDER BY id DESC
    "
);

$stores = [];

while($row = mysqli_fetch_assoc($result)){
    $stores[] = $row;
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>STORE | MBCA COFFEE</title>
  <link rel="stylesheet" href="/coffee/assets/css/header.css">
  <link rel="stylesheet" href="/coffee/assets/css/nav.css">
  <link rel="stylesheet" href="/coffee/assets/css/store.css">
  <script src="//dapi.kakao.com/v2/maps/sdk.js?appkey=189902bbe93b113649d2eeeb9e4b90e9"></script>
</head>
<body>
  <?php include __DIR__ . '/../includes/header.php'; ?>

  <main class="store-page">
    <section class="store-hero">
      <p>MBCA STORE</p>
      <h1>매장 찾기</h1>
    </section>

<section class="store-search">

  <input
    type="search"
    id="store-search-input"
    placeholder="지역명 또는 매장명을 입력하세요"
>
<button
    type="button"
    id="store-search-btn"
>
    검색
</button>

  <button
    type="button"
    id="current-location-btn"
  >
    현재 위치
  </button>

</section>

<div id="nearest-store-box"></div>

    <section class="store-layout">
      <div class="store-map">
       <div id="map"></div>
      </div>
      <div class="store-list">
        <?php foreach ($stores as $store): ?>
          <article
          class="store-card"
          data-lat="<?= $store['lat'] ?>"
          data-lng="<?= $store['lng'] ?>"
          data-name="<?= e($store['name']) ?>"
          >
            <h2><?= e($store['name']) ?></h2>
            <p><?= e($store['address']) ?></p>
            <span><?= e($store['phone']) ?></span>
            <small><?= e($store['hours']) ?></small>
            <a
    class="direction-btn"
    href="https://map.kakao.com/link/to/<?= urlencode($store['name']) ?>,<?= $store['lat'] ?>,<?= $store['lng'] ?>"
    target="_blank"
    rel="noopener noreferrer"
>
    길찾기
</a>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  </main>
  <script src="/coffee/assets/js/nav.js"></script>
  <script type="application/json" id="stores-data"><?= json_encode(
    $stores,
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
  ) ?></script>
  <script src="/coffee/assets/js/stores.js"></script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
