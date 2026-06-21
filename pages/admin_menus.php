<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

require_once __DIR__ . '/../config/database.php';

$sql = "
SELECT *
FROM menus
ORDER BY id DESC
";

$result = mysqli_query($db, $sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<link rel="stylesheet" href="/coffee/assets/css/admin.css">
<meta charset="UTF-8">
<title>메뉴 관리</title>

</head>
<body class="admin-list-page">
<?php include __DIR__ . '/../includes/admin_nav.php'; ?>

<p>
    <a href="/coffee/pages/admin.php">
        ← 관리자 대시보드
    </a>
</p>

<h1>메뉴 관리</h1>

<a href="menu_write.php" class="add-btn">
메뉴 등록
</a>

<table>

<tr>
    <th>이미지</th>    
    <th>ID</th>
    <th>이름</th>
    <th>카테고리</th>
    <th>가격</th>
    <th>상태</th>
    <th>관리</th>
</tr>

<?php while($menu = mysqli_fetch_assoc($result)): ?>

<tr>
    <td>
        <img
            src="<?= e(image_url($menu['image'], 'menu')) ?>"
            width="80"
                    width:80px;
                    height:80px;
                    object-fit:cover;
                    border-radius:10px; >
    </td>
    <td><?= $menu['id'] ?></td>
    <td><?= $menu['name'] ?></td>
    <td><?= $menu['category'] ?></td>
    <td><?= number_format($menu['price']) ?>원</td>
    <td>

    <?php if($menu['is_best']): ?>
        <div>⭐ BEST</div>
    <?php endif; ?>

    <?php if($menu['is_season']): ?>
        <div>🌞 SEASON</div>
    <?php endif; ?>

    <?php if(
        !$menu['is_best']
        && !$menu['is_season']
    ): ?>
        일반 메뉴
    <?php endif; ?>

    </td>
        <td>

        <a href="menu_edit.php?id=<?= $menu['id'] ?>">
            수정
        </a>

        |

        <form class="inline-delete-form" method="post" action="/coffee/actions/menu_delete.php" onsubmit="return confirm('삭제하시겠습니까?');">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int)$menu['id'] ?>">
            <button class="delete-link" type="submit">삭제</button>
        </form>

    </td>
</tr>

<?php endwhile; ?>

</table>

</body>
</html>
