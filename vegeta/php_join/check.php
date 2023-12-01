<?php
session_start();
require('../library.php');
function h($value) {    
    return htmlspecialchars($value, ENT_QUOTES);
}
$form = $_SESSION['form'];

if (!isset($_SESSION['form'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// データベース接続
	$pdo = dbconnect();
	$password = password_hash($form['password'], PASSWORD_DEFAULT);
	$stmt = $pdo->prepare('INSERT INTO users(name, email, password) VALUES(?, ?, ?)');
	$stmt->bindParam(1, $form['name']);
	$stmt->bindParam(2, $form['email']);
	$stmt->bindParam(3, $password);

$success = $stmt->execute();

if (!$success) {
    $error = $stmt->errorInfo();
    echo 'エラー: ' . $error[2];
}

if ($success) {
    unset($_SESSION['form']);
    header('Location: registration.php');
    exit();
}
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>会員登録</title>
    <link rel="stylesheet" href="../style/login.css" />
</head>
<body>
<div id="wrap">
    <div id="head">
        <h1>会員登録</h1>
    </div>

    <div id="content">
        <p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
        <form action="" method="post">
            <dl>
                <dt>ニックネーム</dt>
                <dd><?php echo h($form['name']); ?></dd>
                <dt>メールアドレス</dt>
                <dd><?php echo h($form['email']); ?></dd>
                <dt>パスワード</dt>
                <dd>
                    表示されません
                </dd>
            </dl>
            <div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
        </form>
    </div>
</div>
</body>
</html>