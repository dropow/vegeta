<?php
session_start();
require('library.php');

$pdo = dbconnect();
$postId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$postId) {
    header('Location: index.php');
    exit();
}
// 投稿の作者を確認
$stmt = $pdo->prepare("SELECT member_id FROM posts WHERE id = :postId");
$stmt->bindValue(':postId', $postId, PDO::PARAM_INT);
$stmt->execute();
$post = $stmt->fetch();

if ($post && $_SESSION['id'] === $post['member_id']) {
    // 削除処理
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :postId");
    $stmt->bindValue(':postId', $postId, PDO::PARAM_INT);
    $stmt->execute();

    // セッションIDを再生成
    session_regenerate_id(true);
}

header('Location: index.php');
exit();
?>