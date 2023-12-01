<?php
session_start();
require('library.php');

// ユーザーがログインしているかどうかを確認
if (!isset($_SESSION['id'])) {
    // ログインしていない場合はログインページにリダイレクト
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせフォーム</title>
    <link rel="stylesheet" href="./style/contact.css"/>
</head>
<body>
    <div id="wrap">
        <h1>お問い合わせフォーム</h1>
        <form action="送信先スクリプトのURL" method="post">
            <div class="form-group">
                <label for="name">お名前<span class="required">（必須）</span></label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">メールアドレス<span class="required">（必須）</span></label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="subject">件名<span class="required"></label>
                <input type="text" id="subject" name="subject">
            </div>
            <div class="form-group">
                <label for="message">お問い合わせ内容><span class="required">（必須）</span></label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <input type="submit" value="送信">
            </div>
        </form>
    </div>
</body>
</html>


