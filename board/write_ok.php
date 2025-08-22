<?php
session_start();
include "../db.php";

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('need login');location.href='../login/login.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $board_title   = $_POST['title'];
    $board_content = $_POST['content'];
    $user_id       = $_SESSION['user_id'];
    $user_name     = $_SESSION['user_name'];

    $board_views  = 0;

    // 비밀글 체크
    $board_locked = isset($_POST['is_secret']) ? 1 : 0;
    $secret_pw    = isset($_POST['secret_pw']) && $_POST['secret_pw'] !== "" 
                    ? password_hash($_POST['secret_pw'], PASSWORD_DEFAULT) 
                    : NULL;

    $stmt = $db_conn->prepare("INSERT INTO free_board 
    (board_locked, user_id, board_title, board_content, board_date, board_views, secret_pw) 
    VALUES (?, ?, ?, ?, NOW(), ?, ?)");
    $stmt->bind_param("isssis", $board_locked, $user_id, $board_title, $board_content, $board_views, $secret_pw);

    if ($stmt->execute()) {
        echo "<script>alert('ok');location.href='../board/index.php';</script>";
    } else {
        echo "<script>alert('error: " . $db_conn->error . "');history.back();</script>";
    }
}
?>
