<?php
session_start();
include "../db.php";
require_once 'sign.php';

$signin = new Signin_submit();
$req = $signin->init();

if (empty($req['userid']) || empty($req['userpw'])) {
    echo "<script>alert('Enter ID or Password'); history.back();</script>";
    exit;
}

$user_id = $req['userid'];
$user_pw = $req['userpw'];
$returnUrl = $_GET['redirect'] ?? ($req['redirect'] ?? '../index.php');

$sql = "SELECT user_no, user_id, user_pw, user_name, role FROM user WHERE user_id=?";
$stmt = $db_conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    if (password_verify($user_pw, $row['user_pw'])) {
        if (password_needs_rehash($row['user_pw'], PASSWORD_DEFAULT)) {
            $newHash = password_hash($user_pw, PASSWORD_DEFAULT);
            $upd = $db_conn->prepare("UPDATE user SET user_pw=? WHERE user_no=?");
            $upd->bind_param("si", $newHash, $row['user_no']);
            $upd->execute();
        }

        $_SESSION['user_no']   = $row['user_no'];
        $_SESSION['user_id']   = $row['user_id'];
        $_SESSION['user_name'] = $row['user_name'];
        $_SESSION['role'] = $row['role'] ?? 'user';

        header("Location: " . $returnUrl);
        exit;
    }
}

echo "<script>alert('Incorrect User ID or PW'); history.back();</script>";
exit;

