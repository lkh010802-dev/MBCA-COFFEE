<?php
require_once __DIR__ . '/../includes/auth.php';
ensure_session_started();

include __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);
$type = $_GET['type'] ?? 'notice';

if (!in_array($type, ['notice', 'qna'], true)) {
    $type = 'notice';
}

if (!$id) {
    die('잘못된 접근입니다.');
}

$table = $type === 'notice' ? 'notices' : 'qna';
$stmt = mysqli_prepare($db, "UPDATE $table SET views = views + 1 WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

$stmt = mysqli_prepare($db, "SELECT * FROM $table WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$post = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$post) {
    die('게시글이 존재하지 않습니다.');
}

$stmt = mysqli_prepare($db, "SELECT id, title FROM $table WHERE id < ? ORDER BY id DESC LIMIT 1");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$prevResult = mysqli_stmt_get_result($stmt);

$stmt = mysqli_prepare($db, "SELECT id, title FROM $table WHERE id > ? ORDER BY id ASC LIMIT 1");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$nextResult = mysqli_stmt_get_result($stmt);

$prevPost = mysqli_fetch_assoc($prevResult);
$nextPost = mysqli_fetch_assoc($nextResult);

$reply = null;

if ($type === 'qna') {
    $stmt = mysqli_prepare($db, 'SELECT * FROM qna_reply WHERE qna_id = ? ORDER BY id DESC LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $replyResult = mysqli_stmt_get_result($stmt);

    $reply = mysqli_fetch_assoc($replyResult);
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($post['title']) ?> | MBCA COFFEE</title>

    <link rel="stylesheet" href="/coffee/assets/css/header.css">
    <link rel="stylesheet" href="/coffee/assets/css/nav.css">
    <link rel="stylesheet" href="/coffee/assets/css/notice.css">
</head>
<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<main class="post-page">

    <section class="post-wrap">

        <p class="post-kicker">
            <?= $type === 'notice' ? 'MBCA NOTICE' : 'MBCA Q&A' ?>
        </p>

        <h1 class="post-title">
            <?= e($post['title']) ?>
        </h1>

        <div class="post-meta">
            <span>조회수 <?= e($post['views'] ?? 0) ?></span>
            <span>작성일 <?= e($post['created_at']) ?></span>
        </div>

        <div class="post-content">
            <?= nl2br(e($post['content'])) ?>
        </div>

        <?php if($type === 'qna' && $reply): ?>
            <div class="reply-box">
                <h3>관리자 답변</h3>
                <p><?= nl2br(e($reply['content'])) ?></p>
            </div>
        <?php endif; ?>

        <?php if(
            $type === 'qna' &&
            isset($_SESSION['role']) &&
            $_SESSION['role'] === 'admin'
        ): ?>
            <div class="reply-write-box">
                <h3>관리자 답변 작성</h3>

                <form method="post" action="/coffee/actions/qna_reply.php">
<?= csrf_field() ?>
                    <input type="hidden" name="qna_id" value="<?= $id ?>">
                    <textarea
                        name="reply"
                        rows="6"
                        required
                        placeholder="답변 내용을 입력하세요."
                    ></textarea>

                    <button type="submit">
                        답변 등록
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <div class="post-navigation">
            <?php if($prevPost): ?>
                <a href="/coffee/pages/news_view.php?id=<?= $prevPost['id'] ?>&type=<?= $type ?>">
                    <strong>이전글</strong>
                    <span><?= e($prevPost['title']) ?></span>
                </a>
            <?php endif; ?>

            <?php if($nextPost): ?>
                <a href="/coffee/pages/news_view.php?id=<?= $nextPost['id'] ?>&type=<?= $type ?>">
                    <strong>다음글</strong>
                    <span><?= e($nextPost['title']) ?></span>
                </a>
            <?php endif; ?>
        </div>

        <div class="post-actions">
            <a href="/coffee/pages/news.php?type=<?= $type ?>">
                목록으로
            </a>

            <?php if(
                $type === 'notice' &&
                isset($_SESSION['role']) &&
                $_SESSION['role'] === 'admin'
            ): ?>
                <a href="/coffee/pages/notice_edit.php?id=<?= $id ?>">
                    수정
                </a>

                <form class="inline-delete-form" method="post" action="/coffee/actions/notice_delete.php" onsubmit="return confirm('삭제하시겠습니까?');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <button class="delete-link" type="submit">삭제</button>
                </form>
            <?php endif; ?>

            <?php if(
                $type === 'qna' &&
                isset($_SESSION['userid']) &&
                $_SESSION['userid'] === ($post['userid'] ?? '')
            ): ?>
                <a href="/coffee/pages/qna_edit.php?id=<?= $id ?>">
                    수정
                </a>
            <?php endif; ?>

            <?php if(
                $type === 'qna' &&
                isset($_SESSION['userid']) &&
                (
                    $_SESSION['userid'] === ($post['userid'] ?? '') ||
                    (
                        isset($_SESSION['role']) &&
                        $_SESSION['role'] === 'admin'
                    )
                )
            ): ?>
                <form class="inline-delete-form" method="post" action="/coffee/actions/qna_delete.php" onsubmit="return confirm('삭제하시겠습니까?');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <button class="delete-link" type="submit">문의 삭제</button>
                </form>
            <?php endif; ?>
        </div>

    </section>

</main>

<script src="/coffee/assets/js/nav.js"></script>
<?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
