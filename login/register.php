<?php 
 session_start(); 
 include "../db.php"; 
?> 
<!DOCTYPE html> 
<html lang="ko"> 
<head> 
  <meta charset="utf-8" /> 
  <meta name="viewport" content="width=device-width, initial-scale=1" /> 
  <title>hackingcamp platform</title> 
  <link rel="stylesheet" href="login.css" /> 
</head> 
<body> 
<nav class="navbar navbar-dark bg-dark authbar"> 
  <a class="navbar-brand" href="../index.php">hackingcamp Platform</a> 
</nav> 
<div class="auth-wrap"> 
  <div class="auth-card"> 
    <h1 class="auth-title">register</h1> 
    <p class="auth-sub">Create your account to join hackingcamp.</p> 
    
    <form id="registerForm" method="post" action="register_proc.php" autocomplete="off"> 
      <div class="form-group"> 
        <label for="user_id">ID</label> 
        <input id="user_id" name="user_id" type="text" class="form-control" placeholder="ID" required> 
        <div id="id_check_message" style="margin-top: 5px; font-size: 14px;"></div>
      </div> 

      <div class="form-group"> 
        <label for="user_pw">Password</label> 
        <input id="user_pw" name="user_pw" type="password" class="form-control" placeholder="Password" required> 
      </div> 

      <div class="form-group"> 
        <label for="user_name">Name</label> 
        <input id="user_name" name="user_name" type="text" class="form-control" placeholder="Name" required> 
      </div> 

      <div class="auth-actions"> 
        <button type="submit" class="btn btn-primary">register</button> 
        <a class="btn btn-link" href="login.php">return to login</a> 
      </div> 
    </form> 
  </div> 
</div> 

<script>
const userIdInput = document.getElementById('user_id');
const messageDiv = document.getElementById('id_check_message');
const registerForm = document.getElementById('registerForm');

let isIdAvailable = false;

userIdInput.addEventListener('keyup', function() {
    const userId = this.value;

    if (userId.length === 0) {
        messageDiv.innerHTML = '';
        isIdAvailable = false; // 아이디입력 필수
        return;
    }

    fetch('check_id.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'user_id=' + encodeURIComponent(userId)
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            messageDiv.textContent = 'already exists. Please choose a different ID.';
            messageDiv.style.color = 'red';
            isIdAvailable = false;
        } else {
            messageDiv.textContent = 'This ID is available.';
            messageDiv.style.color = 'green';
            isIdAvailable = true; 
        }
    })
    .catch(error => {
        console.error('Error occurred while checking for ID duplication:', error);
        isIdAvailable = false; 
    });
});

registerForm.addEventListener('submit', function(event) {
    if (!isIdAvailable) {
        alert('The ID is duplicated or unavailable.');
        event.preventDefault(); 
        userIdInput.focus(); 
    }
});
</script>

</body> 
</html>
