<?php
// check.php

session_start();
require('../library');

// ログイン確認
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームの値をセッションに格納
    $_SESSION['form'] = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'subject' => $_POST['subject'],
        'message' => $_POST['message']
    ];

    // バリデーションなどの処理...

    // 確認画面へリダイレクト
    header('Location: thanks.php');
    exit;
}

// ここにHTMLコード（確認画面）
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせ内容の確認</title>
    <link rel="stylesheet" href="./style/contact.css"/>
</head>
<body>
    <div id="wrap">
        <h1>お問い合わせ内容の確認</h1>
        <form action="thanks.php" method="post">
            <div class="form-group">
                <label>お名前</label>
                <p><?php echo htmlspecialchars($_SESSION['form']['name'], ENT_QUOTES); ?></p>
            </div>
            <div class="form-group">
                <label>メールアドレス</label>
                <p><?php echo htmlspecialchars($_SESSION['form']['email'], ENT_QUOTES); ?></p>
            </div>
            <div class="form-group">
                <label>件名</label>
                <p><?php echo htmlspecialchars($_SESSION['form']['subject'], ENT_QUOTES); ?></p>
            </div>
            <div class="form-group">
                <label>お問い合わせ内容</label>
                <p><?php echo nl2br(htmlspecialchars($_SESSION['form']['message'], ENT_QUOTES)); ?></p>
            </div>
            <div class="form-group">
                <a href="index.php">修正する</a>
                <input type="submit" value="送信">
            </div>
        </form>
    </div>
</body>
</html>