<?php
session_start();
include "../db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Invalid access');
}

header('Content-Type: application/json');

$board_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$input_pw = $_POST['secret_pw'] ?? '';

if ($board_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid post ID.']);
    exit;
}

$stmt = $db_conn->prepare("SELECT secret_pw FROM free_board WHERE board_id = ?");
$stmt->bind_param("i", $board_id);
$stmt->execute();
$result = $stmt->get_result();
$board = $result->fetch_assoc();

if (!$board || empty($board['secret_pw'])) {
    echo json_encode(['success' => false, 'message' => 'This is not a secret post.']);
    exit;
}

if (password_verify($input_pw, $board['secret_pw'])) {
    $_SESSION['view_allowed_' . $board_id] = true;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
}
?>
