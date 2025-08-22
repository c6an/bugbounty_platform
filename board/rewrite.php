<?php
session_start();
include "../db.php";

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('need login');location.href='../login/login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name']; 


if (!isset($_GET['id'])) {
    echo "<script>alert('Please select a post.'); location.href='board.php';</script>";
    exit;
}
$board_id = (int)$_GET['id'];

$sql = $db_conn->prepare("SELECT * FROM free_board WHERE board_id = ?");
$sql->bind_param("i", $board_id);
$sql->execute();
$result = $sql->get_result();
$board = $result->fetch_assoc();

if (!$board || $board['user_id'] != $user_id) {
    echo "<script>alert('The post does not exist or you do not have permission.'); location.href='board.php';</script>";
    exit;
}
if ($board['board_locked'] == 1 && (!isset($_SESSION['view_allowed_' . $board_id]) || $_SESSION['view_allowed_' . $board_id] !== true)) {
    header("Location: check_pw.php?action=edit&id=" . $board_id);
    exit;
}

$title = $board['board_title'];
$content = $board['board_content'];
$is_secret_checked = !empty($board['secret_pw']) ? 'checked' : '';
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Edit Freeboard</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
<script src="../resource/ckeditor/ckeditor.js"></script>
<link rel="stylesheet" href="./board_style.css" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="../index.php">hackingcamp Platform</a>
</nav>

<div class="container">
<h1>Edit Freeboard</h1>
<form action="rewrite_ok.php" method="post">
    <input type="hidden" name="board_id" value="<?php echo $board_id; ?>">
    <div class="form-group">
        <label for="user_name">Writer</label>
        <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user_name); ?>" readonly />
    </div>
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required />
    </div>
    <div class="form-group">
        <label for="content">Content</label>
        <textarea class="form-control" id="content" name="content" rows="10" required><?php echo htmlspecialchars($content); ?></textarea>
        <script>
            CKEDITOR.replace("content", {
                filebrowserUploadUrl: "upload.php",
                filebrowserUploadMethod: "form",
            });
        </script>
    </div>

    <!-- 비밀글 기능 -->
    <div class="custom-checkbox">
        <input type="checkbox" id="is_secret" name="is_secret" value="1" onclick="togglePasswordField()" <?= $is_secret_checked ?>>
        <label for="is_secret">Secret Post</label>
    </div>
    <div class="form-group mt-2" id="secret_pw_group" style="display: <?php echo $is_secret_checked ? 'block' : 'none'; ?>;">
        <label for="secret_pw">Password (Enter to change)</label>
        <input type="password" class="form-control" id="secret_pw" name="secret_pw">
    </div>
    <div class="button-group">
        <a href="view.php?id=<?php echo $board_id; ?>" class="btn">Cancel</a> 
        <button type="submit" class="btn">Update</button> 
    </div>
</form>
</div>

<script>
function togglePasswordField() {
    const checkBox = document.getElementById("is_secret");
    const pwField = document.getElementById("secret_pw_group");
    pwField.style.display = checkBox.checked ? "block" : "none";
}
</script>
</body>
</html>
