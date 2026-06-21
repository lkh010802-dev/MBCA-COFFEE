<?php
$brandValues = [
  [
    'title' => '학생을 위한 커피',
    'desc' => 'MBCA COFFEE는 부담 없는 가격과 밝은 에너지로 학생들의 하루를 응원합니다.'
  ],
  [
    'title' => '가볍지만 선명한 맛',
    'desc' => '매일 마시기 좋은 커피와 시즌 메뉴를 쉽고 즐겁게 제공합니다.'
  ],
  [
    'title' => '일상에 가까운 브랜드',
    'desc' => '학교 앞, 거리, 일상 속에서 편하게 만날 수 있는 커피 브랜드를 지향합니다.'
  ]
];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BRAND | MBCA COFFEE</title>
  <link rel="stylesheet" href="/coffee/assets/css/header.css">
  <link rel="stylesheet" href="/coffee/assets/css/nav.css">
  <link rel="stylesheet" href="/coffee/assets/css/brand.css">
</head>
<body>
  <?php include __DIR__ . '/../includes/header.php'; ?>

  <main class="brand-page">
    <section class="brand-hero">
      <p>MBCA BRAND</p>
      <h1>학생들의 일상에<br>작은 활력을 더합니다</h1>
    </section>

    <section class="brand-intro">
      <div>
        <p>
          MBCA COFFEE는 빠르게 지나가는 하루 속에서도
          누구나 부담 없이 즐길 수 있는 커피를 만듭니다.
        </p>
        <p>
          노랑과 검정의 선명한 에너지처럼, MBCA는 학생들의 일상에
          밝고 가벼운 여유를 더하는 브랜드를 목표로 합니다.
        </p>
      </div>
    </section>

    <section class="brand-values">
      <?php foreach ($brandValues as $value): ?>
        <article>
          <h2><?= $value['title'] ?></h2>
          <p><?= $value['desc'] ?></p>
        </article>
      <?php endforeach; ?>
    </section>
  </main>

  <script src="/coffee/assets/js/nav.js"></script>
  <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>