<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: home.html");
  exit;
}

include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = $_POST['title'] ?? '';
  $type = $_POST['type'] ?? '';
  $description = $_POST['description'] ?? '';
  $location = $_POST['location'] ?? '';
  $email = $_POST['email'] ?? '';
  $user_id = $_SESSION['user_id'] ?? 0;

  if (!empty($title) && !empty($type) && !empty($description) && !empty($location) && !empty($email)) {
    $sql = "INSERT INTO reports (user_id, title, type, description, location, email) 
            VALUES ('$user_id', '$title', '$type', '$description', '$location', '$email')";
    if (mysqli_query($conn, $sql)) {
      echo "<script>alert('✅ Report submitted successfully!'); window.location='home.html';</script>";
    } else {
      echo "Database Error: " . mysqli_error($conn);
    }
  } else {
    echo "<script>alert('⚠️ Please fill in all required fields.');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Report Lost or Found Item</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Antic+Slab&family=Outfit:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">
  <style>
    header { height: 60px }
    h2 { margin-top: 5px; }

    nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: black;
      padding: 12px 40px;
    }

    .nav-left {
      display: flex;
      align-items: center;
      gap: 30px;
    }

    .nav-left h2 {
      margin: 0;
      color: black;
      letter-spacing: 1px;
      margin-left: -40px;
    }

    .menu {
      display: flex;
      gap: 20px;
      margin-top: 20px;
    }

    .menu a {
      text-decoration: none;
      color: rgb(90, 88, 88);
      position: relative;
      font-weight: 500;
      transition: 0.3s;
      margin-top: -10px;
    }

    .menu a::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      left: 0;
      bottom: -4px;
      background-color: rgb(90, 88, 88);
      transition: width 0.3s;
    }

    .menu a:hover::after { width: 100%; }

    .nav-right {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .user-icon {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      overflow: hidden;
    }

    .user-icon i {
      margin-top: 8px;
      font-size: 20px;
    }

    .user-icon img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .username {
      font-weight: 500;
      margin-right: -30px;
    }
  </style>
</head>

<body>
  <header>
    <nav>
      <div class="nav-left">
        <h2>📝 Report Lost or Found Item</h2>
        <div class="menu">
          <a href="home.html">Home</a>
          <a href="report.php">Report Items</a>
        </div>
      </div>

      <div class="nav-right">
        <div class="user-icon">
          <i class="fa-solid fa-user-circle"></i>
        </div>
        <span class="username">Welcome </span>
      </div>
    </nav>
  </header>

  <main>
    <form id="reportForm" class="report-form" method="POST" action="report.php">
      <label>Item Title:</label>
      <input type="text" name="title" id="title" required>

      <label>Type:</label>
      <select name="type" id="type" required>
        <option value="Lost">Lost</option>
        <option value="Found">Found</option>
      </select>

      <label>Description:</label>
      <textarea name="description" id="description" rows="3" required></textarea>

      <label>Location:</label>
      <input type="text" name="location" id="location" required>

      <label>Your Email:</label>
      <input type="email" name="email" id="email" required>

      <label>Image:</label>
      <input type="file" name="image" id="image" accept="image/*">

      <button type="submit">Submit Report</button>
    </form>
  </main>
</body>
</html>
