<?php


// mysqlのDSNの書き方
// https://www.php.net/manual/ja/ref.pdo-mysql.connection.php

// PHPでデータベースに接続するときのまとめ >>@mpyw
// https://qiita.com/mpyw/items/b00b72c5c95aac573b71


// DSNの書き方
// ==========================================
$dsn = "mysql:";

// データベースサーバーが存在するホスト名
$host = "127.0.0.1";
$dsn .= "host={$host};";

// データベースサーバーが待機しているポート
$port = "3306";
$dsn .= "port={$port};";

// データベース名
$dbname = "user_table";
$dsn .= "dbname={$dbname};";

// 文字セット
$charset = "utf8";
$dsn .= "charset={$charset};";

// ==========================================

$username = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $username, $password);

    // PDOExceptionを発生させるようにする
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // トランザクション開始
    $pdo->beginTransaction();

    
    $sql = "INSERT INTO m_person ";
    $sql .= "(id,name,gender,address,dm) ";
    $sql .= "VALUES(:id, :name, :gender, :address, :dm)";

    // プリペアドステートメントを用意
    $stmt = $pdo->prepare($sql);

    // プリペアドステートメントにセットする
    // 第三引数は代入する値の型を指定する、これによってSQLインジェクション対策が出来る
    // 第三引数に設定する値: https://www.php.net/manual/ja/pdo.constants.php
    $stmt->bindValue(":id", 6, PDO::PARAM_INT);
    $stmt->bindValue(":name", "太郎", PDO::PARAM_STR);
    $stmt->bindValue(":gender", "F", PDO::PARAM_STR);
    $stmt->bindValue(":address", "東京都千代田区", PDO::PARAM_STR);
    $stmt->bindValue(":dm", "0", PDO::PARAM_STR);
    
    // SQLを実行する
    $stmt->execute();

    // 操作を反映させる
    $pdo->commit();
    echo "追加成功"; echo "<br>";
} catch (PDOException $e) {

    echo "エラー発生"; echo "<br>";

    // トランザクションが開始されていたら
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
        echo "ロールバック実行"; echo "<br>";
    }
    
} finally {
    $pdo = null;
    echo "PDO接続終了";
}