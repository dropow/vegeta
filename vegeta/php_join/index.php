<?php
session_start();

require('../library.php'); // library.php ファイルを読み込む
function h($value) {    
    return htmlspecialchars($value, ENT_QUOTES);
}
if (isset($_GET['action']) && $_GET['action'] === 'rewrite' && isset($_SESSION['form'])) {
    $form = $_SESSION['form'];
} else {
    $form = [
        'name' => '',
        'email' => '',
        'password' => '',
    ];
}
$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームの値を取得
    $form['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    // パスワードはセッションに保存しない
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // 名前の検証
    if ($form['name'] === '') {
        $error['name'] = 'blank';
    }

    // メールアドレスの検証
    if ($form['email'] === '') {
        $error['email'] = 'blank';
    } else {
        $pdo = dbconnect();
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
        if (!$stmt) {
            die($pdo->errorInfo());
        }
        $stmt->execute([$form['email']]);
        $cnt = $stmt->fetchColumn();
        
        if ($cnt > 0) {
            $error['email'] = 'duplicate';
            
        }
        
    }

    // パスワードの検証
    if ($password === '') {
        $error['password'] = 'blank';
    } else if (strlen($password) < 8) {
        $error['password'] = 'length';
    }

    // エラーがない場合、check.phpにリダイレクト
    if (empty($error)) {
        $_SESSION['form'] = $form;
        // パスワードはセッションに含めない
        $_SESSION['form']['password'] = $password;
        header('Location: check.php');
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

    <link rel="stylesheet" href="../style/joinindex.css"/>
</head>

<body>
<div id="wrap">
    <div id="head">
        <h1>会員登録</h1>
    </div>

    <div id="content">
        <p>次のフォームに必要事項をご記入ください。</p>
        <form action="" method="post" enctype="multipart/form-data">
            <dl>
                <dt>名前<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="name" value="<?php echo h($form['name']); ?>"/>
                    <?php if(isset($error['name']) && $error['name'] === 'blank'): ?>
                    <p class="error">* 名前を入力してください</p>
                    <?php endif; ?>
                </dd>
                <dt>メールアドレス<span class="required">必須</span></dt>
                    <dd>
                    <input type="text" name="email" value="<?php echo h($form['email']); ?>"/>
                    <?php if(isset($error['email']) && $error['email'] === 'blank'): ?>
                    <p class="error">* メールアドレスを入力してください</p>
                    <?php endif; ?>
                    <?php if(isset($error['email']) && $error['email'] === 'duplicate'): ?>
                    <p class="error">* 指定されたメールアドレスはすでに登録されています</p>
                    <?php endif; ?>
                </dd>
                <dt>パスワード<span class="required">必須</span></dt>
                <dd>
                    <!-- パスワードフィールドのvalueを常に空にする -->
                    <input type="password" name="password" value="" />
                    <?php if (isset($error['password']) && $error['password'] === 'blank') : ?>
                    <p class="error">* パスワードを入力してください</p>
                    <?php endif; ?>
                    <?php if (isset($error['password']) && $error['password'] === 'length') : ?>
                    <p class="error">* パスワードは8文字以上で入力してください</p>
                    <?php endif; ?>
                </dd>
            </dl>
            <div><input type="submit" value="入力内容を確認する"/></div>
        </form>
    </div>
</body>

</html>
