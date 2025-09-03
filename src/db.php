<?php
// db.php
$host = "localhost";
$user = "root";
$pass = "ixa956mv6M6(K.wH";
$dbname = "bugbounty";

$db_conn = new mysqli($host, $user, $pass, $dbname);

if ($db_conn->connect_error) {
    die("DB 연결 실패: " . $db_conn->connect_error);
}
?>