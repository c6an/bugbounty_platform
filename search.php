<?php
// search.php
include "db.php";
session_start();

$s_id   = $_SESSION["user_id"]   ?? "";
$s_name = $_SESSION["user_name"] ?? "";

$search_term = $_GET['search'] ?? '';
$category    = $_GET['catgo'] ?? 'board_title';

$allowed_categories = ['board_title', 'board_content'];
if (!in_array($category, $allowed_categories)) {
    $category = 'board_title'; 
}

$results = [];
if (!empty($search_term)) {

    $like_term = "%" . $search_term . "%";
    
    
    $sql = "SELECT board_id, board_title, board_date, user_name 
            FROM free_board 
            JOIN user ON free_board.user_id = user.user_id
            WHERE {$category} LIKE ? 
            ORDER BY board_id DESC";
    
    $stmt = $db_conn->prepare($sql);
    $stmt->bind_param("s", $like_term);
    $stmt->execute();
    $result_set = $stmt->get_result();
    
    while ($row = $result_set->fetch_assoc()) {
        $results[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>hackingcamp Platform</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">hackingcamp Platform</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="./board/index.php">board</a></li>
            <?php if(!$s_id){ ?>
                <li class="nav-item"><a class="nav-link" href="./login/login.php">login</a></li>
            <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="./login/logout.php">logout</a>
                </li>
            <?php } ?>
            <li class="nav-item"><a class="nav-link" href="./report.php">report</a></li>
            <li class="nav-item"><span class="navbar-text text-light ml-2"><?= htmlspecialchars($s_name) ?></span></li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h1>Search Results</h1>

    <div id="search_box" class="mb-4">
        <form action="search.php" method="get">
            <select name="catgo">
                <option value="board_title" <?= $category === 'board_title' ? 'selected' : '' ?>>Title</option>
                <option value="board_content" <?= $category === 'board_content' ? 'selected' : '' ?>>Content</option>
            </select>
            <input type="text" name="search" size="40" value="<?= htmlspecialchars($search_term) ?>" required="required" />
            <button>Search</button>
        </form>
    </div>

    <?php if (!empty($search_term)): ?>
        <?php if (count($results) > 0): ?>
            <p class="search-summary">
                Search results for <span>"<?= htmlspecialchars($search_term) ?>"</span> (Total: <?= count($results) ?>)
            </p>
            <ul class="search-results-list">
                <?php foreach ($results as $row): ?>
                    <li>
                        <a href="./board/view.php?id=<?= $row['board_id'] ?>">
                            <h5><?= htmlspecialchars($row['board_title']) ?></h5>
                        </a>
                        <small>Writer: <?= htmlspecialchars($row['user_name']) ?> | Date: <?= $row['board_date'] ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="not-found-container">
                <h4>404 Not Found</h4>
                <p>No search results found for the requested term: <span>"<?= htmlspecialchars($search_term) ?>"</span></p>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <p>Please enter a search term.</p>
    <?php endif; ?>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>