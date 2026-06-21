<?php
session_start();

include __DIR__ . '/../config/database.php';

if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
) {
    die('관리자만 접근 가능합니다.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    $isPinned =
        isset($_POST['is_pinned'])
        ? 1
        : 0;

    $writer = $_SESSION['userid'];

    $sql = "INSERT INTO notices
            (title, content, writer, is_pinned)
            VALUES
            ('$title', '$content', '$writer', $isPinned)";

    $result = mysqli_query($db, $sql);

    if (!$result) {
        die(mysqli_error($db));
    }

    header('Location: /coffee/pages/news.php?type=notice');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공지 작성 | MBCA COFFEE</title>

    <link rel="stylesheet" href="/coffee/assets/css/header.css">
    <link rel="stylesheet" href="/coffee/assets/css/nav.css">
    <link rel="stylesheet" href="/coffee/assets/css/notice.css">
    
</head>
<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<main class="write-page">

    <section class="write-wrap">

        <h1>공지사항 작성</h1>

        <form method="post">

            <div class="form-row">
                <label>공지 제목</label>

                <input
                    type="text"
                    name="title"
                    placeholder="제목을 입력하세요."
                    required
                >
            </div>

            <div class="form-row">
                <label>공지 내용</label>

                <textarea
                    name="content"
                    rows="10"
                    placeholder="공지 내용을 입력하세요."
                    required
                ></textarea>
            </div>

            <label class="checkbox-row">
                <input
                    type="checkbox"
                    name="is_pinned"
                    value="1"
                >
                중요공지로 고정
            </label>

            <div class="form-buttons">
                <a
                    href="/coffee/pages/news.php?type=notice"
                    class="cancel-btn"
                >
                    취소
                </a>

                <button
                    type="submit"
                    class="submit-btn"
                >
                    공지 등록 →
                </button>
            </div>

        </form>

    </section>

</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<script src="/coffee/assets/js/nav.js"></script>

</body>
</html>