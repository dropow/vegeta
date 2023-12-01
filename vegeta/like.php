<?php
session_start();
require('library.php');

session_regenerate_id(true);

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$post_id = $_GET['post_id'];
$user_id = $_SESSION['id'];

$pdo = dbconnect();

// いいねの状態をチェック
$stmt = $pdo->prepare("SELECT * FROM likes WHERE post_id = :post_id AND user_id = :user_id");
$stmt->execute(['post_id' => $post_id, 'user_id' => $user_id]);
$like = $stmt->fetch();

if ($like) {
    // いいねを削除
    $stmt = $pdo->prepare("DELETE FROM likes WHERE post_id = :post_id AND user_id = :user_id");
    $stmt->execute(['post_id' => $post_id, 'user_id' => $user_id]);
} else {
    // いいねを追加
    $stmt = $pdo->prepare("INSERT INTO likes (post_id, user_id) VALUES (:post_id, :user_id)");
    $stmt->execute(['post_id' => $post_id, 'user_id' => $user_id]);
}

header('Location: index.php');
exit;
?>

