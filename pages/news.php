<!-- 
MBCA COFFEE COMMUNITY PAGE
기능
- 공지사항 목록
- Q&A 목록
- 게시글 상세페이지 이동
-->

<?php


require_once __DIR__ . '/../includes/auth.php';
ensure_session_started();

$boardType = $_GET['type'] ?? 'notice';
if (!in_array($boardType, ['notice', 'qna'], true)) {
    $boardType = 'notice';
}
$keyword = trim(
    $_GET['keyword'] ?? ''
);
$page = (int)(
    $_GET['page'] ?? 1
);

if($page < 1){
    $page = 1;
}

$perPage = 10;

$offset =
    ($page - 1)
    * $perPage;

include __DIR__ . '/../config/database.php';
$searchKeyword = '%' . $keyword . '%';
if ($boardType === 'notice') {
$stmt = mysqli_prepare(
    $db,
    "SELECT
        id,
        title,
        views,
        created_at,
        is_pinned
     FROM notices
     WHERE title LIKE ?
ORDER BY
is_pinned DESC,
id DESC
LIMIT ?, ?"
);
mysqli_stmt_bind_param($stmt, 'sii', $searchKeyword, $offset, $perPage);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

    $rows = [];

while ($row = mysqli_fetch_assoc($result)) {

    $row['type'] = 'notice';

    $row['date'] = substr(
        $row['created_at'],
        0,
        10
    );

    $rows[] = $row;
}
}

elseif ($boardType === 'qna') {

$stmt = mysqli_prepare(
    $db,
    "SELECT
        id,
        userid,
        title,
        status,
        views,
        created_at
FROM qna
WHERE title LIKE ?
ORDER BY id DESC
LIMIT ?, ?"
);
mysqli_stmt_bind_param($stmt, 'sii', $searchKeyword, $offset, $perPage);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {

        $row['type'] = 'qna';
        $row['date'] = substr(
            $row['created_at'],
            0,
            10
        );
        $row['masked_userid'] =
    substr($row['userid'], 0, 3)
    . str_repeat(
        '*',
        max(strlen($row['userid']) - 3, 0)
    );

        $rows[] = $row;
    }
}

else {

    $rows = [];

    $noticeResult = mysqli_query(
        $db,
        "SELECT
            id,
            title,
            views,
            created_at,
            is_pinned
         FROM notices"
    );

    while ($row = mysqli_fetch_assoc($noticeResult)) {

        $row['type'] = 'notice';
        $row['date'] = substr(
            $row['created_at'],
            0,
            10
        );

        $rows[] = $row;
    }

    $qnaResult = mysqli_query(
        $db,
        "SELECT
            id,
            title,
            status,
            created_at
         FROM qna"
    );

    while ($row = mysqli_fetch_assoc($qnaResult)) {

        $row['type'] = 'qna';
        $row['views'] = '-';
        $row['date'] = substr(
            $row['created_at'],
            0,
            10
        );

        $rows[] = $row;
    }

    usort(
        $rows,
        fn($a, $b) => $b['id'] <=> $a['id']
    );
}
if ($boardType === 'notice') {

    $stmt = mysqli_prepare(
        $db,
        "SELECT COUNT(*)
         FROM notices
         WHERE title LIKE ?"
    );
    mysqli_stmt_bind_param($stmt, 's', $searchKeyword);
    mysqli_stmt_execute($stmt);
    $countResult = mysqli_stmt_get_result($stmt);

} else {

    $stmt = mysqli_prepare(
        $db,
        "SELECT COUNT(*)
         FROM qna
         WHERE title LIKE ?"
    );
    mysqli_stmt_bind_param($stmt, 's', $searchKeyword);
    mysqli_stmt_execute($stmt);
    $countResult = mysqli_stmt_get_result($stmt);
}

$totalRows =
    mysqli_fetch_row(
        $countResult
    )[0];

$totalPages = max(
    1,
    ceil($totalRows / $perPage)
);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>공지사항 | MBCA COFFEE</title>
  <link rel="stylesheet" href="/coffee/assets/css/header.css">
  <link rel="stylesheet" href="/coffee/assets/css/nav.css">
  <link rel="stylesheet" href="/coffee/assets/css/notice.css">
</head>
<body>
  <?php include __DIR__ . '/../includes/header.php'; ?>

  <main class="news-page">

    <section class="board-wrap">

    <div class="community-head">
        <div>
            <p>MBCA COMMUNITY</p>
            <h1>공지사항 & Q&A</h1>
        </div>

        <form method="get" class="community-search">
            <input
                type="hidden"
                name="type"
                value="<?= $boardType ?>"
            >

            <input
                type="text"
                name="keyword"
                value="<?= e($keyword) ?>"
                placeholder="검색어를 입력하세요."
            >

            <button type="submit">⌕</button>
        </form>
    </div>

    <nav class="board-tabs" aria-label="board category">
        <a class="<?= $boardType === 'notice' ? 'is-active' : '' ?>"
           href="/coffee/pages/news.php?type=notice">
            공지사항
        </a>

        <a class="<?= $boardType === 'qna' ? 'is-active' : '' ?>"
           href="/coffee/pages/news.php?type=qna">
            Q&A
        </a>
    </nav>

    <div class="board-count">
        전체 <strong><?= $totalRows ?></strong>건
        <span>|</span>
        현재 페이지 <strong><?= $page ?>/<?= $totalPages ?></strong>
    </div>

    <div class="board-actions">
        <?php if(
            $boardType === 'notice' &&
            isset($_SESSION['role']) &&
            $_SESSION['role'] === 'admin'
        ): ?>
            <a href="/coffee/pages/notice_write.php" class="write-btn">
                공지 작성
            </a>
        <?php endif; ?>

        <?php if($boardType === 'qna'): ?>
            <a class="write-btn" href="/coffee/pages/qna_write.php">
                문의 등록
            </a>
        <?php endif; ?>
    </div>

    <div class="board-list">
        <?php foreach ($rows as $row): ?>

            <a
                class="board-item"
                href="/coffee/pages/news_view.php?id=<?= $row['id'] ?>&type=<?= $row['type'] ?>"
            >
                <div class="board-left">
                    <?php if($row['type'] === 'notice'): ?>
                        <span class="board-badge">공지</span>
                    <?php else: ?>
                        <span class="board-badge qna">Q&A</span>
                    <?php endif; ?>

                    <h3>
                        <?php if(
                            isset($row['is_pinned']) &&
                            $row['is_pinned']
                        ): ?>
                            📌
                        <?php endif; ?>

                        <?= e($row['title']) ?>
                    </h3>
                </div>

                <div class="board-meta">
                    <span>
                        <?= $row['type'] === 'qna'
                            ? e($row['masked_userid'])
                            : '관리자'
                        ?>
                    </span>
                    <span><?= e($row['date']) ?></span>
                    <span>조회 <?= e($row['views']) ?></span>

                    <?php if ($boardType === 'qna'): ?>
                        <span><?= e($row['status']) ?></span>
                    <?php endif; ?>

                    <strong>›</strong>
                </div>
            </a>

        <?php endforeach; ?>
    </div>

    <div class="pagination">
        <?php for($i = 1; $i <= $totalPages; $i++): ?>
            <a
                class="<?= $page === $i ? 'active-page' : '' ?>"
                href="?type=<?= $boardType ?>&keyword=<?= urlencode($keyword) ?>&page=<?= $i ?>"
            >
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

</section>
  </main>

  <script src="/coffee/assets/js/nav.js"></script>
  <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
