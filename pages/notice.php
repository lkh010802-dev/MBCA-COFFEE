<?php
$isAdmin = true;

$notices = [
  [
    'id' => 1,
    'title' => '여름 시즌 메뉴 출시 안내',
    'author' => '관리자',
    'created_at' => '2026-06-01',
    'updated_at' => '2026-06-05',
    'views' => 123
  ],
  [
    'id' => 2,
    'title' => 'MBCA 신규 매장 오픈',
    'author' => '관리자',
    'created_at' => '2026-05-25',
    'updated_at' => '-',
    'views' => 87
  ],
  [
    'id' => 3,
    'title' => '시스템 점검 안내',
    'author' => '관리자',
    'created_at' => '2026-05-18',
    'updated_at' => '2026-05-19',
    'views' => 65
  ]
];

$qnas = [
  [
    'id' => 1,
    'title' => '신메뉴 출시 예정인가요?',
    'author' => '홍길동',
    'created_at' => '2026-06-10',
    'status' => '답변완료'
  ],
  [
    'id' => 2,
    'title' => '상품권 사용 가능한가요?',
    'author' => '김철수',
    'created_at' => '2026-06-11',
    'status' => '답변대기'
  ]
];

function e($value) {
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>공지사항 & 고객센터 | MBCA COFFEE</title>
  <link rel="stylesheet" href="/coffee/assets/css/header.css">
  <link rel="stylesheet" href="/coffee/assets/css/nav.css">
  <link rel="stylesheet" href="/coffee/assets/css/notice.css">
</head>
<body>
  <?php include __DIR__ . '/../includes/header.php'; ?>

  <main class="notice-page">
    <section class="notice-hero">
      <p>MBCA CUSTOMER CENTER</p>
      <h1>공지사항 & 고객센터</h1>
      <span>MBCA COFFEE의 소식과 문의사항을 확인할 수 있는 공간입니다.</span>
    </section>

    <section class="board-card">
      <div class="board-head">
        <div>
          <p>NOTICE</p>
          <h2>공지사항</h2>
        </div>

        <?php if ($isAdmin): ?>
          <button type="button">공지사항 작성</button>
        <?php endif; ?>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>번호</th>
              <th>제목</th>
              <th>작성자</th>
              <th>등록일</th>
              <th>수정일</th>
              <th>조회수</th>
              <?php if ($isAdmin): ?>
                <th>관리</th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($notices as $notice): ?>
              <tr>
                <td><?= e($notice['id']) ?></td>
                <td class="title-cell"><?= e($notice['title']) ?></td>
                <td><?= e($notice['author']) ?></td>
                <td><?= e($notice['created_at']) ?></td>
                <td><?= e($notice['updated_at']) ?></td>
                <td><?= e($notice['views']) ?></td>
                <?php if ($isAdmin): ?>
                  <td class="action-cell">
                    <button type="button">수정</button>
                    <button type="button">삭제</button>
                  </td>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section class="board-card">
      <div class="board-head">
        <div>
          <p>Q&A</p>
          <h2>문의 게시판</h2>
        </div>

        <?php if (!$isAdmin): ?>
          <button type="button">Q&A 작성</button>
        <?php endif; ?>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>번호</th>
              <th>제목</th>
              <th>작성자</th>
              <th>등록일</th>
              <th>상태</th>
              <?php if ($isAdmin): ?>
                <th>관리</th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($qnas as $qna): ?>
              <tr>
                <td><?= e($qna['id']) ?></td>
                <td class="title-cell"><?= e($qna['title']) ?></td>
                <td><?= e($qna['author']) ?></td>
                <td><?= e($qna['created_at']) ?></td>
                <td>
                  <span class="status <?= $qna['status'] === '답변완료' ? 'done' : 'waiting' ?>">
                    <?= e($qna['status']) ?>
                  </span>
                </td>
                <?php if ($isAdmin): ?>
                  <td class="action-cell">
                    <button type="button">답변</button>
                    <button type="button">삭제</button>
                  </td>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <script src="/coffee/assets/js/nav.js"></script>
  <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>