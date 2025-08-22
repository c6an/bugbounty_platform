<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>hackingcamping platform</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css"/>
</head>
<body>
<?php
session_start();

$s_id   = $_SESSION["user_id"]   ?? "";
$s_name = $_SESSION["user_name"] ?? "";
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">hackingcamp Platform</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
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

<main class="main-content">
    <div class="container">
        <h1>Welcome to the hackingcamp Platform</h1>
        <p>This is a platform for reporting and managing bugs.</p>
    </div>
    <div id="search_box">
        <form action="search.php" method="get">
            <select name="catgo">
                <option value="board_title">Title</option>
                <option value="board_content">Content</option>
            </select>
            <input type="text" name="search" size="40" required="required" /> <button>Search</button>
        </form>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
