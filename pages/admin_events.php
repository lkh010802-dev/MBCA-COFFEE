<?php include __DIR__ . '/../includes/admin_nav.php'; ?>
<?php

session_start();

if (
    !isset($_SESSION['role'])
    || $_SESSION['role'] !== 'admin'
) {
    die('관리자만 접근 가능합니다.');
}

require_once __DIR__ . '/../config/database.php';

$result = mysqli_query(
    $db,
    "SELECT * FROM events ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<link rel="stylesheet" href="/coffee/assets/css/admin.css">
<meta charset="UTF-8">
<title>이벤트 관리</title>

<style>

body{
    font-family:sans-serif;
    padding:40px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,
td{
    border:1px solid #ddd;
    padding:10px;
}

th{
    background:#f5f5f5;
}

.add-btn{
    display:inline-block;
    margin-bottom:20px;
    padding:10px 16px;
    background:#333;
    color:#fff;
    text-decoration:none;
}

img{
    border-radius:10px;
}

</style>

</head>
<body>

<p>
    <a href="/coffee/pages/admin.php">
        ← 관리자 대시보드
    </a>
</p>

<h1>이벤트 관리</h1>

<a href="event_write.php" class="add-btn">
    이벤트 등록
</a>

<table>

<tr>
    <th>이미지</th>
    <th>ID</th>
    <th>뱃지</th>
    <th>제목</th>
    <th>기간</th>
    <th>관리</th>
</tr>

<?php while($event = mysqli_fetch_assoc($result)): ?>

<tr>

<td>
    <img
        src="<?= $event['image'] ?>"
        style="
            width:80px;
            height:80px;
            object-fit:cover;
        "
    >
</td>

<td>
    <?= $event['id'] ?>
</td>

<td>
    <?= htmlspecialchars($event['badge']) ?>
</td>

<td>
    <?= htmlspecialchars($event['title']) ?>
</td>

<td>
    <?= htmlspecialchars($event['period']) ?>
</td>

<td>

    <a href="event_edit.php?id=<?= $event['id'] ?>">
        수정
    </a>

    |

    <a
        href="event_delete.php?id=<?= $event['id'] ?>"
        onclick="return confirm('삭제하시겠습니까?');"
    >
        삭제
    </a>

</td>

</tr>

<?php endwhile; ?>

</table>

</body>
</html>