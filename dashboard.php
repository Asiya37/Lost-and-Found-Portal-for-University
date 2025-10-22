<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
  header("Location: login.html");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard</title>
</head>
<body>
  <h2>Welcome User</h2>
  <p>You are logged in as a regular user.</p>
  <a href="logout.php">Logout</a>
</body>
</html>
