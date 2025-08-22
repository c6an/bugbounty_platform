<?php
session_start();
include "../db.php";
require_once 'sign.php';


$signin = new Signin_submit();
$signin->make();
$redirect = $signin->get('redirect') ?? '../index.php';
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login | hackingcamp platform</title>
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

</body>
</html>
