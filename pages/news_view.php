<?php
session_start();

include __DIR__ . '/../config/database.php';

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$id = (int)($_GET['id'] ?? 0);
$type = $_GET['type'] ?? 'notice';

if (!$id) {
    die('잘못된 접근입니다.');
}

if ($type === 'notice') {
    mysqli_query($db, "UPDATE notices SET views = views + 1 WHERE id = $id");
    $sql = "SELECT * FROM notices WHERE id = $id";
} else {
    mysqli_query($db, "UPDATE qna SET views = views + 1 WHERE id = $id");
    $sql = "SELECT * FROM qna WHERE id = $id";
}

$result = mysqli_query($db, $sql);
$post = mysqli_fetch_assoc($result);

if (!$post) {
    die('게시글이 존재하지 않습니다.');
}

$table = $type === 'notice' ? 'notices' : 'qna';

$prevResult = mysqli_query(
    $db,
    "SELECT id, title FROM $table WHERE id < $id ORDER BY id DESC LIMIT 1"
);

$nextResult = mysqli_query(
    $db,
    "SELECT id, title FROM $table WHERE id > $id ORDER BY id ASC LIMIT 1"
);

$prevPost = mysqli_fetch_assoc($prevResult);
$nextPost = mysqli_fetch_assoc($nextResult);

if (
    $type === 'qna' &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'admin' &&
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    $reply = trim($_POST['reply']);
    $adminId = $_SESSION['userid'];

    mysqli_query(
        $db,
        "INSERT INTO qna_reply (qna_id, admin_id, content)
         VALUES ($id, '$adminId', '$reply')"
    );

    mysqli_query($db, "UPDATE qna SET status='answered' WHERE id=$id");

    header("Location: news_view.php?id=$id&type=qna");
    exit;
}

$reply = null;

if ($type === 'qna') {
    $replyResult = mysqli_query(
        $db,
        "SELECT *
         FROM qna_reply
         WHERE qna_id = $id
         ORDER BY id DESC
         LIMIT 1"
    );

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

                <form method="post">
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

                <a
                    href="/coffee/pages/notice_delete.php?id=<?= $id ?>"
                    onclick="return confirm('삭제하시겠습니까?');"
                >
                    삭제
                </a>
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
                <a
                    href="/coffee/pages/qna_delete.php?id=<?= $id ?>"
                    onclick="return confirm('삭제하시겠습니까?');"
                >
                    문의 삭제
                </a>
            <?php endif; ?>
        </div>

    </section>

</main>

<script src="/coffee/assets/js/nav.js"></script>
<?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>