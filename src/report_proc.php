<?php
// report_proc.php
session_start();
include "db.php";

$user_id   = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['user_name'] ?? null;
$title     = trim($_POST['report_title'] ?? '');
$endpoint  = trim($_POST['endpoint'] ?? '');
$url       = trim($_POST['url'] ?? '');
$vuln_type = $_POST['vuln_type'] ?? '';
$poc_md    = $_POST['poc'] ?? '';


if (!$user_id) { echo "<script>alert('You must log in.'); history.back();</script>"; exit; }
if ($title==='' || $endpoint==='' || $url==='' || $vuln_type==='' || $poc_md==='') {
  echo "<script>alert('All fields are required.'); history.back();</script>"; exit;
}
if (!filter_var($url, FILTER_VALIDATE_URL)) {
  echo "<script>alert('Invalid URL format.'); history.back();</script>"; exit;
}

$allow_types = ['xss', 'sqli', 'openredirection', 'idor', 'csrf', 'file_upload'];
if (!in_array($vuln_type, $allow_types, true)) {
  echo "<script>alert('Invalid vulnerability type.'); history.back();</script>"; exit;
}


$sql = "INSERT INTO report
        (user_id, user_name, report_title, endpoint, url, vuln_type, poc, report_date)
        VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE())";
$stmt = $db_conn->prepare($sql);
$stmt->bind_param("sssssss", $user_id, $user_name, $title, $endpoint, $url, $vuln_type, $poc_md);
$ok = $stmt->execute();
if (!$ok) { echo "<script>alert('DB save failed'); history.back();</script>"; exit; }

$rid = $db_conn->insert_id;


header("Location: ./poc.php?id=".$rid);
exit;