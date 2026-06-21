<?php
require_once __DIR__ . '/auth.php';
ensure_session_started();
?>

<header class="site-header">
  <div class="header-inner">
    <a class="brand-logo" href="/coffee/index.php" aria-label="MBCA COFFEE home">
      <img
        class="brand-logo-image"
        src="/coffee/assets/images/MBCA-logo.png"
        alt="MBCA COFFEE"
      >
    </a>

    <nav class="header-nav" aria-label="Quick menu">
      <a href="/coffee/pages/menu.php?category=drink">MENU</a>
      <a href="/coffee/pages/stores.php">STORE</a>
      <a href="/coffee/pages/event.php">EVENT</a>
      <a href="/coffee/pages/brand.php">BRAND</a>
      <a href="/coffee/pages/news.php">COMMUNITY</a>
    </nav>

    <div class="member-nav">
      <span class="divider"></span>
<?php if(isset($_SESSION['userid'])): ?>

<a href="/coffee/pages/mypage.php">마이페이지</a>
<a href="/coffee/pages/logout.php">로그아웃</a>

<?php else: ?>

<a href="/coffee/pages/login.php">로그인</a>
<a href="/coffee/pages/signup.php">회원가입</a>

<?php endif; ?>
    </div>

    <button class="menu-button" type="button" aria-label="Open menu" aria-expanded="false">
      <span></span>
      <span></span>
      <span></span>
    </button>
  </div>
  
</header>

<?php include __DIR__ . '/nav.php'; ?>
