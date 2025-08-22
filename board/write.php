<?php
session_start();
include "../db.php";

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('need login');location.href='../login/login.php';</script>";
    exit;
}
$s_id   = $_SESSION["user_id"]   ?? "";
$s_name = $_SESSION["user_name"] ?? "";

$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>hackingcamp Platform</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"/>
  <script type="text/javascript" src="../resource/ckeditor/ckeditor.js"></script>
  <link rel="stylesheet" href="./board_style.css" />
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="./index.php">hackingcamp Platform</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="./index.php">board</a></li>
            <?php if(!$s_id){ ?>
                <li class="nav-item"><a class="nav-link" href="./login/login.php">login</a></li>
            <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="../login/logout.php">logout</a>
                </li>
            <?php } ?>
            <li class="nav-item"><a class="nav-link" href="/bugbounty/report.php">report</a></li>
            <li class="nav-item"><span class="navbar-text text-light ml-2"><?= htmlspecialchars($s_name) ?></span></li>
        </ul>
    </div>
    </nav>

 <div class="container">
    <h1>freeboard</h1>
    <form action="write_ok.php" method="post">
      <div class="form-group">
        <label for="user_name">writer</label>
        <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user_name); ?>" readonly />
      </div>
      <div class="form-group">
        <label for="title">title</label>
        <input type="text" class="form-control" id="title" name="title" required />
      </div>
      <div class="form-group">
        <label for="content">content</label>
        <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
        <script>
          CKEDITOR.replace("content", {
            filebrowserUploadUrl: "upload.php",
            filebrowserUploadMethod: "form",
          });
        </script>
      </div>

      <!-- 비밀글 기능 -->
      <div class="custom-checkbox">
        <input type="checkbox" id="is_secret" name="is_secret" value="1" onclick="togglePasswordField()">
        <label for="is_secret">Secret Post</label>
      </div>
      <div class="form-group mt-2" id="secret_pw_group" style="display: none;">
        <label for="secret_pw">Password</label>
        <input type="password" class="form-control" id="secret_pw" name="secret_pw">
      </div>
    <div class="button-group">
        <a href="board.php" class="btn">Cancel</a>
        <button type="submit" class="btn">Submit</button>
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

