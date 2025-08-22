<?php
session_start();
include "../db.php";

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='../login/login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['board_id'])) {
    echo "<script>alert('Invalid access.'); location.href='board.php';</script>";
    exit;
}

$board_id = (int)$_POST['board_id'];
$title = trim($_POST['title']);
$content = trim($_POST['content']);
$is_secret = isset($_POST['is_secret']) ? 1 : 0;
$secret_pw = isset($_POST['secret_pw']) && !empty($_POST['secret_pw']) ? password_hash($_POST['secret_pw'], PASSWORD_DEFAULT) : null;

$sql_check = $db_conn->prepare("SELECT user_id FROM free_board WHERE board_id = ?");
$sql_check->bind_param("i", $board_id);
$sql_check->execute();
$result_check = $sql_check->get_result();
$board = $result_check->fetch_assoc();

if (!$board || $board['user_id'] != $user_id) {
    echo "<script>alert('You do not have permission.'); location.href='board.php';</script>";
    exit;
}

// 업데이트 쿼리
if ($is_secret && $secret_pw) {

    $sql_update = $db_conn->prepare("UPDATE free_board SET board_title = ?, board_content = ?, board_locked = 1, secret_pw = ? WHERE board_id = ?");
    $sql_update->bind_param("sssi", $title, $content, $secret_pw, $board_id);
} elseif ($is_secret && !$secret_pw) {
    $sql_update = $db_conn->prepare("UPDATE free_board SET board_title = ?, board_content = ?, board_locked = 1 WHERE board_id = ?");
    $sql_update->bind_param("ssi", $title, $content, $board_id);
} else {
    // 비밀글 해제
    $sql_update = $db_conn->prepare("UPDATE free_board SET board_title = ?, board_content = ?, board_locked = 0, secret_pw = NULL WHERE board_id = ?");
    $sql_update->bind_param("ssi", $title, $content, $board_id);
}

if ($sql_update->execute()) {
    echo "<script>alert('The post has been updated.'); location.href='view.php?id={$board_id}';</script>";
} else {
    echo "<script>alert('Error occurred while updating.'); history.back();</script>";
}
?>
