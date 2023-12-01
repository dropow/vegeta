<?php
    
function dbconnect() {

try {
    $pdo = new PDO(DB_HOST, DB_USER, DB_PASSWORD);
    //echo '接続成功';
} catch (PDOException $e) {
    //echo '接続失敗' . $e->getMessage() . "\n";
    exit();
}
return $pdo;
}

?>
