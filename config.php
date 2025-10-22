<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "lost_found_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
  die("Database connection failed: " . mysqli_connect_error());
}
?>
