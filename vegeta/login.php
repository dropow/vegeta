<?php
require('library.php');
session_start();
$error = [];
$email = '';
$password = '';

function h($value) {    
    return htmlspecialchars($value, ENT_QUOTES);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if ($email === '' || $password === '') {
        $error['login'] = 'blank';
    } else {
        $pdo = dbconnect();

        try {
            $stmt = $pdo->prepare('SELECT id, password, name FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $member = $stmt->fetch(PDO::FETCH_ASSOC);

            // パスワードを検証
            if ($member && password_verify($password, $member['password'])) {
                // ログイン成功時の処理
                $_SESSION['loggedin'] = true; // ログイン状態を表す
                $_SESSION['id'] = $member['id']; // ユーザーIDをセッションに保存
                $_SESSION['name'] = $member['name']; // ユーザー名もセッションに保存

                header('Location: index.php');
                exit;
            } else {
                $error['login'] = 'failed';
            }
        } catch (PDOException $e) {
            $error['login'] = 'failed';
            // エラーメッセージをログに記録するなどの処理をここに追加
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./style/login.css"/>
    <title>ログイン</title>
</head>

<body>
<div id="wrap">
    <div id="head">
        <h1>ログインする</h1>
    </div>
    <div id="content">
        <!-- トップページへ戻るリンク -->
        <p><a href="index.php">トップページに戻る</a></p>

        <div id="lead">
            <p>会員登録がまだの方はこちらからどうぞ。</p>
            <p>&raquo;<a href="./php_join/index.php">会員登録をする</a></p>
            <p>メールアドレスとパスワードを記入してログインしてください。</p>
            <p>お問い合わせの場合もログインしてください。</p>
        </div>
        <form action="" method="post">
            <dl>
                <dt>メールアドレス</dt>
                <dd>
                    <input type="text" name="email" value="<?php echo h($email); ?>"/>
                    <?php if (isset($error['login']) && $error['login'] === 'blank'):?>
                    <p class="error">* メールアドレスとパスワードをご記入ください</p>
                    <?php endif; ?>
                    <?php if (isset($error['login']) && $error['login'] === 'failed'): ?>
                    <p class="error">* ログインに失敗しました。正しくご記入ください。</p>
                    <?php endif; ?>
                </dd>
                <dt>パスワード</dt>
                <dd>
                    <input type="password" name="password" value="<?php echo h($password); ?>"/>
                </dd>
            </dl>
            <div>
                <input type="submit" value="ログインする"/>
            </div>
        </form>
    </div>
</div>
</body>
</html>
