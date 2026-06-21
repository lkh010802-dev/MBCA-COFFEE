<?php

session_start();

include __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['userid'])) {
        die('로그인이 필요합니다.');
    }

    $userid = $_SESSION['userid'];

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    $sql = "INSERT INTO qna
            (userid, title, content)
            VALUES
            ('$userid', '$title', '$content')";

    $result = mysqli_query($db, $sql);

    if (!$result) {
        die(mysqli_error($db));
    }

    header('Location: /coffee/pages/news.php?type=qna');
    exit;
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<link rel="stylesheet" href="/coffee/assets/css/admin.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Q&A 작성 | MBCA COFFEE</title>

    <link rel="stylesheet" href="/coffee/assets/css/header.css">
    <link rel="stylesheet" href="/coffee/assets/css/nav.css">
    <link rel="stylesheet" href="/coffee/assets/css/forms.css">
</head>

<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<main class="auth-page">

    <section class="auth-card">

        <h1>문의 등록</h1>

        <form method="post">

            <label>문의 제목</label>

            <input
                type="text"
                name="title"
                required
            >

            <label>문의 내용</label>

            <textarea
                name="content"
                rows="10"
                required
            ></textarea>

            <button type="submit">
                문의 등록
            </button>

        </form>

    </section>

</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
<script src="/coffee/assets/js/nav.js"></script>

</body>
</html>