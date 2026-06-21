<?php

session_start();

include __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);

$result = mysqli_query(
    $db,
    "SELECT *
     FROM qna
     WHERE id=$id"
);

$qna = mysqli_fetch_assoc($result);

if(
    !$qna
){
    die('게시글 없음');
}

if(
    $_SESSION['userid'] !== $qna['userid']
    &&
    $_SESSION['role'] !== 'admin'
){
    die('권한 없음');
}

mysqli_query(
    $db,
    "DELETE FROM qna
     WHERE id=$id"
);

header(
    'Location: /coffee/pages/news.php?type=qna'
);

exit;