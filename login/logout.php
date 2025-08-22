<?php
	include "../db.php";
	session_start();
	session_destroy();
?>
<meta charset="utf-8">
<script>alert("Logged out successfully."); location.href="../index.php"; </script>