<?php
session_start();
include "../db.php";

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo "<script>alert('Invalid access.'); location.href='board.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$board_id = (int)$_GET['id'];

$stmt = $db_conn->prepare("SELECT user_id FROM free_board WHERE board_id = ?");
$stmt->bind_param("i", $board_id);
$stmt->execute();
$result = $stmt->get_result();
$board = $result->fetch_assoc();

if (!$board || $board['user_id'] !== $user_id) {
    echo "<script>alert('You do not have permission.'); location.href='index.php';</script>";
    exit;
}

// 게시글 삭제
$stmt_delete = $db_conn->prepare("DELETE FROM free_board WHERE board_id = ?");
$stmt_delete->bind_param("i", $board_id);

if ($stmt_delete->execute()) {
    unset($_SESSION['view_allowed_' . $board_id]);
    echo "<script>alert('The post has been deleted.'); location.href='index.php';</script>";
} else {
    echo "<script>alert('Error occurred while deleting.'); history.back();</script>";
}
$stmt->close();
$stmt_delete->close();
$db_conn->close();
?>
