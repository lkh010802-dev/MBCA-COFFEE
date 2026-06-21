<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

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

</head>
<body class="admin-list-page">
<?php include __DIR__ . '/../includes/admin_nav.php'; ?>

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
        src="<?= e(image_url($event['image'], 'event')) ?>"
        class="admin-table-thumbnail"
        alt="<?= e($event['title']) ?>"
    >
</td>

<td>
    <?= $event['id'] ?>
</td>

<td>
    <?= e($event['badge']) ?>
</td>

<td>
    <?= e($event['title']) ?>
</td>

<td>
    <?= e($event['period']) ?>
</td>

<td>

    <a href="event_edit.php?id=<?= $event['id'] ?>">
        수정
    </a>

    |

    <form class="inline-delete-form" method="post" action="/coffee/actions/event_delete.php" onsubmit="return confirm('삭제하시겠습니까?');">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int)$event['id'] ?>">
        <button class="delete-link" type="submit">삭제</button>
    </form>

</td>

</tr>

<?php endwhile; ?>

</table>

</body>
</html>
