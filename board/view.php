<?php
session_start();
include "../db.php";

if (!isset($_GET['id'])) {
    echo "<script>alert('There are no posts.'); location.href='board.php';</script>";
    exit;
}
$board_id = (int)$_GET['id'];


$sql = "
    SELECT f.*, u.user_name 
    FROM free_board f
    JOIN user u ON f.user_id = u.user_id
    WHERE f.board_id = ?
";
$stmt = $db_conn->prepare($sql);
$stmt->bind_param("i", $board_id);
$stmt->execute();
$result = $stmt->get_result();
$board = $result->fetch_assoc();

if (!$board) {
    echo "<script>alert('There is no post.'); location.href='board.php';</script>";
    exit;
}
if ($board['board_locked'] == 1 && (!isset($_SESSION['view_allowed_' . $board_id]) || $_SESSION['view_allowed_' . $board_id] !== true)) {
    header("Location: check_pw.php?action=view&id=" . $board_id);
    exit;
}

if (!isset($_SESSION['viewed_post_' . $board_id])) {
    $stmt_update = $db_conn->prepare("UPDATE free_board SET board_views = board_views + 1 WHERE board_id = ?");
    $stmt_update->bind_param("i", $board_id);
    $stmt_update->execute();
    $_SESSION['viewed_post_' . $board_id] = true; 
    $board['board_views']++;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>hackingcamp Platform</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="./board_style.css" />
</head>
<body>
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="../index.php">hackingcamp Platform</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active"><a class="nav-link" href="board.php">Board</a></li>
                <?php if(!isset($_SESSION['user_id'])){ ?>
                    <li class="nav-item"><a class="nav-link" href="../login/login.php">login</a></li>
                <?php } else { ?>
                    <li class="nav-item"><a class="nav-link" href="../login/logout.php">logout</a></li>
                <?php } ?>
                <li class="nav-item"><a class="nav-link" href="../report.php">report</a></li>
                <?php if(isset($_SESSION['user_name'])): ?>
                <li class="nav-item"><span class="navbar-text text-light ml-2"><?= htmlspecialchars($_SESSION['user_name']) ?></span></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div id="board_read">
            <h2><?= htmlspecialchars($board['board_title']); ?></h2>
            
            <div id="user_info">
                <div class="meta-item">
                    <span class="meta-label">WRITER:</span>
                    <span><?= htmlspecialchars($board['user_name']); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">DATE:</span>
                    <span><?= htmlspecialchars($board['board_date']); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">VIEWS:</span>
                    <span><?= htmlspecialchars($board['board_views']); ?></span>
                </div>
            </div>
            <div id="bo_content">
                <?php echo $board['board_content']; ?>
            </div>
            
            <div class="button-group">
                <a href="board.php" class="btn">List</a>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $board['user_id']): ?>
                    <a href="rewrite.php?id=<?= $board['board_id']; ?>" class="btn">Edit</a>
                    <a href="delete.php?id=<?= $board['board_id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure?');">Delete</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>