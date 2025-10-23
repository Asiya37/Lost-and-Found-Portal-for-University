<?php
session_start();
include('config.php'); // Database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  if (empty($email) || empty($password)) {
    echo "<script>alert('⚠️ Please enter both email and password.');</script>";
  } else {
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
      die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
      $user = mysqli_fetch_assoc($result);

      $_SESSION['user_id'] = $user['id'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['role'] = $user['role'];

      // ✅ Redirect based on role
      if ($user['role'] === 'admin') {
        header("Location: admin-dashboard.php");
        exit;
      } else {
        header("Location: home.html");
        exit;
      }
    } else {
      echo "<script>alert('❌ Invalid email or password.');</script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Lost & Found Portal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap"
    rel="stylesheet"
  />
  <style>
    body {
      font-family: "Outfit", sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      position: relative;
      overflow: hidden;
    }

    body::before {
      content: "";
      position: absolute;
      inset: 0;
      background: url('img/bg2.jpg') no-repeat center center/cover;
      opacity: 0.3;
      z-index: -1;
      filter: blur(3px);
    }

    .login-box {
      background: rgba(255, 255, 255, 0.95);
      padding: 40px;
      border-radius: 12px;
      width: 380px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
      text-align: center;
      backdrop-filter: blur(10px);
      animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .login-box h2 {
      margin-bottom: 10px;
      color: #00264d;
    }

    .login-box p {
      color: #555;
      margin-bottom: 25px;
      font-size: 14px;
    }

    .input-group {
      position: relative;
      margin-bottom: 20px;
    }

    .input-group i {
      position: absolute;
      top: 50%;
      left: 15px;
      transform: translateY(-50%);
      color: #555;
      font-size: 15px;
    }

    input {
      width: 100%;
      padding: 10px 10px 10px 40px;
      border-radius: 25px;
      border: 1px solid #ccc;
      outline: none;
      transition: 0.3s;
      font-size: 14px;
    }

    input:focus {
      border-color: #5e17eb;
      box-shadow: 0 0 5px rgba(94, 23, 235, 0.3);
    }

    button {
      width: 100%;
      padding: 10px;
      background: linear-gradient(90deg, #00264d, #5e17eb);
      border: none;
      color: white;
      border-radius: 25px;
      cursor: pointer;
      font-weight: 600;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
    }

    button:hover {
      background: #0056b3;
      transform: scale(1.02);
    }

    .forget {
      margin-top: 15px;
      font-size: 14px;
    }

    .forget a {
      color: #5e17eb;
      text-decoration: none;
    }

    .forget a:hover {
      text-decoration: underline;
    }

  </style>
</head>
<body>

  <form action="login.php" method="POST">
    <div class="login-box">
      <h2>Welcome Back</h2>
      <p>Enter your credentials to access your account</p>

      <div class="input-group">
        <i class="fa-solid fa-envelope"></i>
        <input type="email" name="email" placeholder="Enter Email" required />
      </div>

      <div class="input-group">
        <i class="fa-solid fa-lock"></i>
        <input type="password" name="password" placeholder="Enter Password" required />
      </div>

      <button type="submit" name="login">Login</button>

      <div class="forget">
        Or <a href="forgot.html">Forgot Password?</a>
      </div>
    </div>
  </form>

</body>
</html>
