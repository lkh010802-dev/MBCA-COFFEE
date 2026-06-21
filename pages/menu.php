<?php
require_once __DIR__ . '/../config/database.php';

$category = $_GET['category'] ?? 'drink';
$categoryNames = [
    'coffee' => '커피',
    'drink'  => '음료',
    'food'   => '푸드',
    'goods'  => '상품'
];

$sql = "
SELECT *
FROM menus
WHERE category='$category'
ORDER BY id DESC
";

$result = mysqli_query($db, $sql);

$menus = [];

while ($row = mysqli_fetch_assoc($result)) {
    $menus[] = $row;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <link rel="stylesheet" href="/coffee/assets/css/admin.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MENU | MBCA COFFEE</title>
  <link rel="stylesheet" href="/coffee/assets/css/header.css">
  <link rel="stylesheet" href="/coffee/assets/css/nav.css">
  <link rel="stylesheet" href="/coffee/assets/css/menu.css">
  <link rel="stylesheet" href="/coffee/assets/css/modal.css">
</head>
<body>
  <?php include __DIR__ . '/../includes/header.php'; ?>
<section class="menu-hero">
    <p>MBCA MENU</p>

    <h1><?= $categoryNames[$category] ?></h1>

    <span>
        좋은 사람, 좋은 커피 MBCA COFFEE
    </span>

    <nav class="menu-tabs" aria-label="Menu category">

        <a class="<?= $category === 'coffee' ? 'is-active' : '' ?>"
           href="/coffee/pages/menu.php?category=coffee">
            커피
        </a>

        <a class="<?= $category === 'drink' ? 'is-active' : '' ?>"
           href="/coffee/pages/menu.php?category=drink">
            음료
        </a>

        <a class="<?= $category === 'food' ? 'is-active' : '' ?>"
           href="/coffee/pages/menu.php?category=food">
            푸드
        </a>

        <a class="<?= $category === 'goods' ? 'is-active' : '' ?>"
           href="/coffee/pages/menu.php?category=goods">
            상품
        </a>

    </nav>
</section>
  <main class="menu-page">

    <section class="menu-grid">
      <?php foreach ($menus as $menu): ?>
        <article
          class="menu-item"
          data-name="<?= $menu['name'] ?>"
          data-price="<?= number_format($menu['price']) ?>원"
          data-desc="<?= $menu['description'] ?>"
          data-nutrition="<?= $menu['nutrition'] ?>"
          data-image="<?= $menu['image'] ?>"
        >
          <img src="<?= $menu['image'] ?>" alt="<?= $menu['name'] ?>">
          <?php if($menu['is_best']): ?>
              <span class="best-badge">
                  BEST
              </span>
          <?php endif; ?>

          <?php if($menu['is_season']): ?>
    <span class="season-badge">
        SEASON
    </span>
<?php endif; ?>

<?php if(
    ($menu['category'] === 'coffee'
    || $menu['category'] === 'drink')
    && !empty($menu['temperature_type'])
): ?>

    <span class="temp-badge <?= $menu['temperature_type'] ?>">
        <?= strtoupper($menu['temperature_type']) ?>
    </span>

<?php endif; ?>
          <h2><?= $menu['name'] ?></h2>
          <p><?= number_format($menu['price']) ?>원</p>
        </article>
      <?php endforeach; ?>
    </section>
  </main>

  <div class="menu-modal" aria-hidden="true">
    <div class="modal-panel">
      <button class="modal-close" type="button">×</button>
      <img class="modal-image">

<h2 class="modal-name"></h2>

<p class="modal-price"></p>

<hr>

<p class="modal-desc"></p>

<hr>

<div class="nutrition-title">
    📋 영양정보
</div>

<p class="modal-nutrition"></p>
      <small class="modal-nutrition"></small>
    </div>
  </div>

  <script src="/coffee/assets/js/nav.js"></script>
  <script src="/coffee/assets/js/menu-modal.js"></script>
  <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>