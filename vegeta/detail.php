<?php
session_start();
require('library.php');

if (isset($_SESSION['name']) && isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
    // セッションIDを再生成
    session_regenerate_id(true);
} else {
    $name = 'ゲスト';
}
$postId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT); 
if (!$postId) {
    header('Location: index.php');
    exit();
}
function h($value) {    
    return htmlspecialchars($value, ENT_QUOTES);
}

$pdo = dbconnect();
$stmt = $pdo->prepare("SELECT p.id, p.message, p.image_path, m.name FROM posts p JOIN users m ON m.id = p.member_id WHERE p.id = :postId");
$stmt->bindValue(':postId', $postId, PDO::PARAM_INT);
$stmt->execute();
// 投稿が存在しない場合の処理
if ($stmt->rowCount() === 0) {
    $error = 'その投稿は削除されたか、URLが間違っています。';
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>野菜掲示板</title>

    <link rel="stylesheet" href="./style/view.css"/>
</head>

<body>
    <div id="wrap">
        <div id="head">
            <h1>野菜掲示板</h1>
        </div>
        <div id="content">
            <p>&laquo;<a href="index.php">一覧にもどる</a></p>

            <?php if (!empty($error)): ?>
                <p><?php echo $error; ?></p>
            <?php else: ?>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="msg">
                        <p><?php echo h($row['message']); ?><span class="name">(<?php echo h($row['name']); ?>)</span></p>
                        <!-- 画像がある場合に表示 -->
                        <?php if (!empty($row['image_path'])): ?>
                            <img src="<?php echo h($row['image_path']); ?>" alt="投稿画像" style="max-width: 200; height: auto;">
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>