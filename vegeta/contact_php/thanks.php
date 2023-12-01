<?php
// thanks.php

session_start();
require('../library');

// ログイン確認
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // メール送信の処理...
    // mail($_SESSION['form']['email'], $_SESSION['form']['subject'], $_SESSION['form']['message']);

    // セッションのフォームデータをクリア
    unset($_SESSION['form']);

    // 以下はHTMLコード（完了画面）
    ?>
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>お問い合わせありがとうございます</title>
        <link rel="stylesheet" href="./style/contact.css"/>
    </head>
    <body>
        <div id="wrap">
            <h1>お問い合わせありがとうございます</h1>
            <p>お問い合わせ内容を受け付けました。</p>
            <a href="index.php">トップページに戻る</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// 直接アクセスされた場合は入力画面にリダイレクト
header('Location: index.php');
exit;
?>
