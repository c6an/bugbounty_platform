<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    echo "<script>
            alert('Already logged in.');
            location.href = '../index.php';
          </script>";
    exit;
}

include "../db.php";
require_once 'sign.php';



$signin = new Signin_submit();
$signin->make();
$redirect = $_GET['redirect'] ?? '../index.php';
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>hackingcamp Platform</title>
  <link rel="stylesheet" href="login.css" />
</head>
<body>
<nav class="navbar navbar-dark bg-dark authbar">
  <a class="navbar-brand" href="../index.php">hackingcamp Platform</a>
</nav>
<div class="auth-wrap">
  <div class="auth-card">
    <h1 class="auth-title">Login</h1>
    <p class="auth-sub">Welcome back. Please sign in to continue.</p>

    <form method="post" action="login_ok.php?redirect=<?= urlencode($redirect) ?>">
      <div class="form-group">
        <label for="userid">ID</label>
        <input id="userid" name="userid" type="text" class="form-control" placeholder="ID" required autofocus>
      </div>

      <div class="form-group">
        <label for="userpw">Password</label>
        <input id="userpw" name="userpw" type="password" class="form-control" placeholder="Password" required>
      </div>

      <div class="auth-actions">
        <button type="submit" class="btn btn-primary">login</button>
        <a class="btn btn-link" href="../login/register.php">register</a>
      </div>
    </form>
  </div>
</div>
<script>
(function () {
  const url = new URL(window.location.href);
  if (!url.searchParams.has('redirect')) {
    url.searchParams.set('redirect', <?= json_encode($redirect) ?>);
    history.replaceState(null, '', url.toString());
  }
})();
</script>
<script>
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        //ㅏ강제 새로고침
        window.location.reload();
    }
});
</script>

</body>
</html>