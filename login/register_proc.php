<?php
include "../db.php";

$userid = $_POST['user_id'];
$userpw = $_POST['user_pw'];
$username = $_POST['user_name'];
$role = "user";

$check_sql = "SELECT user_id FROM user WHERE user_id = ?";
$check_stmt = $db_conn->prepare($check_sql);
$check_stmt->bind_param("s", $userid);
$check_stmt->execute();
$result = $check_stmt->get_result();

//ID 조회  1개 이상
if ($result->num_rows > 0) {
    $check_stmt->close(); 
   
    echo "
    <meta charset='utf-8' />
    <script type='text/javascript'>
        alert('already exists. Please choose a different ID.');
        history.back();
    </script>
    ";
    exit(); 
}
$check_stmt->close(); 

$hashed_pw = password_hash($userpw, PASSWORD_DEFAULT);

$sql = "INSERT INTO user (user_id, user_pw, user_name, role) VALUES (?, ?, ?, ?)";
$stmt = $db_conn->prepare($sql);
$stmt->bind_param("ssss", $userid, $hashed_pw, $username, $role);
$stmt->execute();

$stmt->close();
$db_conn->close();
?>
<meta charset="utf-8" />
<script type="text/javascript">alert('registration completed successfully.');</script>
<meta http-equiv="refresh" content="0; url=../login/login.php">
