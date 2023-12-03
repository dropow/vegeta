<?php
session_start();
require('library.php');

if (isset($_SESSION['name']) && isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
    $loggedIn = true;
    
    session_regenerate_id(true);
} else {
    $name = 'ゲスト';
    $loggedIn = false;
}

function h($value) {
    return htmlspecialchars($value, ENT_QUOTES);
}

$pdo = dbconnect();
$err_msgs = array();
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;


// 検索語句の取得
$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search !== '') {
    $searchWords = explode(' ', $search);
    $searchQuery = "SELECT p.id, p.title, p.message, p.image_path, m.name, p.member_id, p.created, COUNT(l.id) AS likes_count FROM posts p JOIN users m ON m.id = p.member_id LEFT JOIN likes l ON l.post_id = p.id WHERE";
    foreach ($searchWords as $index => $word) {
        if ($index > 0) {
            $searchQuery .= " AND";
        }
        $searchQuery .= " (p.message LIKE :search$index OR p.title LIKE :search$index)";
    }
    $searchQuery .= " GROUP BY p.id ORDER BY p.id DESC LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($searchQuery);
    foreach ($searchWords as $index => $word) {
        $likeWord = '%' . $word . '%';
        $stmt->bindValue(":search$index", $likeWord, PDO::PARAM_STR);
    }
} else {
    $stmt = $pdo->prepare("SELECT p.id, p.title, p.message, p.image_path, m.name, p.member_id, p.created, COUNT(l.id) AS likes_count FROM posts p JOIN users m ON m.id = p.member_id LEFT JOIN likes l ON l.post_id = p.id GROUP BY p.id ORDER BY p.id DESC LIMIT :limit OFFSET :offset");
}

$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

if ($search !== '') {
    $stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM posts p WHERE p.message LIKE :search");
    $searchParam = '%' . $search . '%';
    $stmtTotal->bindParam(':search', $searchParam, PDO::PARAM_STR);
} else {
    $stmtTotal = $pdo->query("SELECT COUNT(*) FROM posts");
}
$stmtTotal->execute();
$totalPosts = $stmtTotal->fetchColumn();

$totalPages = ceil($totalPosts / $limit);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $loggedIn) {
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);

    // 画像アップロード処理
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file = $_FILES['image'];
        $filename = basename($file['name']);
        $tmp_path = $file['tmp_name'];
        $file_err = $file['error'];
        $filesize = $file['size'];
        $upload_dir = '/home/xs359758/gokinzyoyasai.com/public_html/uploads/';
        $save_filename = date('YmdHis') . $filename;
        $save_path = $upload_dir . $save_filename;

        // ファイルサイズのバリデーション
        if ($filesize > 1048576 || $file_err == 2) {
            array_push($err_msgs, 'ファイルサイズを1MB未満にしてください。');
        }

        // ファイル形式のバリデーション
        $allow_ext = array('jpg', 'jpeg', 'png');
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array(strtolower($file_ext), $allow_ext)) {
            array_push($err_msgs, '画像ファイルを添付してください。');
        }

        // ファイルアップロード
        if (count($err_msgs) === 0 && is_uploaded_file($tmp_path)) {
            if (move_uploaded_file($tmp_path, $save_path)) {
                // データベースに画像のパスを保存
                // Webアクセス可能なパスを生成
                $web_path = 'uploads/' . $save_filename; // サーバー内部のパスではなく、Webからアクセス可能なパス

                $stmt = $pdo->prepare('INSERT INTO posts (title, message, member_id, image_path) VALUES (?, ?, ?, ?)');
                $stmt->bindParam(1, $title);
                $stmt->bindParam(2, $message);
                $stmt->bindParam(3, $id, PDO::PARAM_INT);
                $stmt->bindParam(4, $web_path);
                if (!$stmt->execute()) {
                    die($pdo->errorInfo()[2]);
                }
            } else {
                array_push($err_msgs, 'ファイルが保存できませんでした。');
            }
        }
    }

    // 画像アップロードに関するエラーメッセージの表示
    if (!empty($err_msgs)) {
        foreach ($err_msgs as $msg) {
            echo $msg;
            echo '<br>';
        }
    } else {
        // エラーがなければページをリダイレクト
        header('Location: index.php');
        exit;
    }
    
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>野菜掲示板</title>
    <link rel="stylesheet" href="./style/index.css"/>
</head>
<body>

<div id="head">
    <h1>野菜掲示板</h1>
    <div class="header-links">
        <a href="index.php">お問い合わせ</a>
        <a href="index.php">サイトについて</a>
        <?php if (!$loggedIn): ?>
            <div class="login-button">
                <a href="login.php"><button>ログインする</button></a>
            </div>
            <div class="register-button">
                <a href="./php_join/index.php"><button>会員登録する</button></a>
            </div>
        <?php else: ?>
            <div class="logout-button">
                <a href="logout.php"><button>ログアウト</button></a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="wrap">
    <div id="sidebar">
        <ul>
            <li><a href="index.php">ホーム</a></li>
            <li><a href="index.php">サイトについて</a></li>
            <li><a href="index.php">お問い合わせ</a></li>
            <?php if ($loggedIn): ?>
                <li><a href="logout.php">ログアウト</a></li>
            <?php else: ?>
                <li><a href="login.php">ログインする</a></li>
                <li><a href="./php_join/index.php">会員登録する</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div id="content">
        
        
        <?php if ($page > 1 || !empty($search)): ?>
            <div class="back-link">
                <a href="index.php">← メインページに戻る</a>
            </div>
        <?php endif; ?>
        
        <div class=keizi>
            <p>野菜掲示板一覧</p>
        </div>
        
        <!-- 検索フォーム -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
            <input type="text" name="search" placeholder="検索..." value="<?php echo h($search); ?>">
            <input type="submit" value="検索">
        </form>

        <!-- 検索結果がない場合の表示 -->
        <?php if ($search !== '' && $totalPosts === 0): ?>
            <p>該当する結果はありません。</p>
        <?php endif; ?>

        <?php if ($loggedIn): ?>
            <form action="" method="post" enctype="multipart/form-data">
                <dl>
                    <dt><?php echo h($name); ?>さん、メッセージをどうぞ</dt>
                    <dd><input type="text" name="title" placeholder="タイトル" required /></dd>
                    <dd><textarea name="message" cols="50" rows="5"></textarea></dd>
                    <dd><input type="file" name="image" size="35" value="" /></dd>
                </dl>
                <div>
                    <!-- Include CSRF token in the form -->
                    <input type="hidden" name="token" value="<?php echo h($token); ?>">
                    <input type="submit" value="投稿する"/>
                </div>
            </form>
        <?php endif; ?>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="msg">
                <div class="image">
                    <!-- 画像の表示 -->
                    <a href="detail.php?id=<?php echo h($row['id']); ?>">
                        <img src="<?php echo h($row['image_path']); ?>" alt="投稿画像" width="200">
                    </a>
                </div>
                <div class="content">
                    <?php if (isset($row['title'])): ?>
                        <p><strong>タイトル:</strong> <?php echo h($row['title']); ?></p>
                    <?php endif; ?>
                    <p><strong>投稿者:</strong> <?php echo h($row['name']); ?></p>
                    
                    <?php
                    // 投稿内容の表示処理
                    $message = strlen($row['message']) > 300 ? substr(h($row['message']), 0, 300) . "..." : h($row['message']);
                    echo "<p>$message</p>";
                    ?>
                    <p class="daytime">
                        <a href="detail.php?id=<?php echo h($row['id']); ?>"><?php echo h($row['created']); ?></a>
                        <?php if ($loggedIn && $id === $row['member_id']): ?>
                            [<a href="delete.php?id=<?php echo h($row['id']); ?>" style="color: #F33;">削除</a>]
                        <?php endif; ?>
                    </p>
                    <!-- いいね数といいねボタンの表示 -->
                <p class="likes">いいね数: <?php echo h($row['likes_count']); ?></p>
                <?php if ($loggedIn): ?>
                    <p class="like-button"><a href="like.php?post_id=<?php echo h($row['id']); ?>">いいね</a></p>
                <?php endif; ?>
            </div> <!-- いいねセクションの終了 -->
        </div>
    <?php endwhile; ?>

        <div id="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">前へ</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php echo $i == $page ? "<span>$i</span>" : "<a href='?page=$i'>$i</a> "; ?>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>">次へ</a>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
