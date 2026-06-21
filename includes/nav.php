<nav class="global-menu" aria-label="Full menu">
  <div class="menu-panel">
    <section>
      <h2>MENU</h2>
      <a href="/coffee/pages/menu.php?category=drink">음료</a>
      <a href="/coffee/pages/menu.php?category=food">푸드</a>
      <a href="/coffee/pages/menu.php?category=goods">상품</a>
      <a class="<?= $category === 'coffee' ? 'is-active' : '' ?>"href="/coffee/pages/menu.php?category=coffee">커피</a>
    </section>

    <section>
      <h2>STORE</h2>
      <a href="/coffee/pages/stores.php">매장 찾기</a>
    </section>

    <section>
      <h2>BRAND</h2>
      <a href="/coffee/pages/brand.php">브랜드 소개</a>
    </section>

    <section>
      <h2>COMMUNITY</h2>
      <a href="/coffee/pages/news.php">공지사항</a>
      <a href="/coffee/pages/event.php">이벤트</a>
    </section>

    <section>
      <h2>MEMBER</h2>
      <a href="/coffee/pages/login.php">로그인</a>
      <a href="/coffee/pages/signup.php">회원가입</a>
      <a href="/coffee/pages/mypage.php">마이 페이지</a>
    </section>
  </div>
</nav>