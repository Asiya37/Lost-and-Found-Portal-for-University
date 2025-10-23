<?php
session_start();
include("config.php");

// --- Delete item if requested ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete_query = "DELETE FROM reports WHERE id = $id";
    mysqli_query($conn, $delete_query);
    header("Location: items.php");
    exit();
}

// --- Search functionality ---
$search = $_GET['search'] ?? '';

if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $query = "SELECT reports.*, users.username 
              FROM reports 
              JOIN users ON reports.user_id = users.id
              WHERE reports.status='approved'
              AND (reports.title LIKE '%$search%' 
                OR reports.type LIKE '%$search%'
                OR reports.location LIKE '%$search%'
                OR users.username LIKE '%$search%')
              ORDER BY reports.id DESC";
} else {
    $query = "SELECT reports.*, users.username 
              FROM reports 
              JOIN users ON reports.user_id = users.id
              WHERE reports.status='approved'
              ORDER BY reports.id DESC";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Items | Lost & Found Portal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
  <style>
    /* ---------- General Styles ---------- */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Outfit', sans-serif;
    }

    body {
      background: #f2f4f7;
    }

    /* ---------- Navbar ---------- */
    nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: white;
      color: black;
      padding: 19px 40px;
      position: fixed;
      width: 100%;
      top: 0;
      left: 0;
      right: 0;
      box-shadow: 0px 0px 10px gray;
      z-index: 100;
    }

    nav h2 {
      margin: 0;
      color: black;
      letter-spacing: 1px;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
    }

    nav ul li a {
      text-decoration: none;
      color: rgb(90, 88, 88);
      position: relative;
      font-weight: 500;
      transition: 0.3s;
    }

    nav ul li a::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      left: 0;
      bottom: -4px;
      background-color: rgb(90, 88, 88);
      transition: width 0.3s;
    }

    nav ul li a:hover::after {
      width: 100%;
    }

    /* ---------- Container ---------- */
    .container {
      width: 90%;
      max-width: 1200px;
      margin: 120px auto 30px;
    }

    h1 {
      text-align: center;
      margin-bottom: 25px;
      color: #222;
    }

    /* ---------- Search Bar ---------- */
    .search-form {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
      margin: 0 auto 30px;
      max-width: 600px;
    }

    .search-form input[type="text"] {
      width: 75%;
      padding: 10px 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
      outline: none;
      transition: 0.3s;
    }

    .search-form input[type="text"]:focus {
      border-color: #333;
    }

    .search-form button {
      background: linear-gradient(90deg, #00264d, #5e17eb);
      color: white;
      border: none;
      padding: 10px 16px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 15px;
      transition: 0.3s;
    }

    .search-form button:hover {
      background-color: #555;
    }

    /* ---------- Items Grid ---------- */
    .items-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
      gap: 20px;
    }

    .item-card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      padding: 20px;
      transition: 0.3s ease;
    }

    .item-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .item-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 10px;
    }

    .item-card h3 {
      margin: 10px 0;
      color: #333;
    }

    .item-card p {
      font-size: 14px;
      color: #555;
    }

    /* ---------- Delete Button ---------- */
    .delete-btn {
      background: #e74c3c;
      color: white;
      text-decoration: none;
      border: none;
      padding: 8px 12px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
      display: inline-block;
      margin-top: 10px;
      transition: 0.3s;
    }

    .delete-btn:hover {
      background: #c0392b;
    }
  </style>
</head>

<body>
  <nav>
    <h2>🎓 University Lost & Found Portal</h2>
    <ul>
      <li><a href="home.html">Home</a></li>
      <li><a href="report.php">Report Item</a></li>
      <li><a href="items.php">All Items</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>

  <div class="container">
    <h1>All Reported Items</h1>

    <!-- 🔍 Search Bar -->
    <form method="GET" action="items.php" class="search-form">
      <input type="text" name="search" placeholder="Search by title, type, location, or username..." 
             value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
      <button type="submit"><i class="fa fa-search"></i> Search</button>
    </form>

    <div class="items-grid">
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <div class="item-card">
            <?php if (!empty($row['image'])): ?>
              <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Item Image">
            <?php else: ?>
              <img src="https://via.placeholder.com/300x200?text=No+Image" alt="No Image">
            <?php endif; ?>

            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
            <p><strong>Posted by:</strong> <?php echo htmlspecialchars($row['username']); ?></p>

            <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this item?');">
              <i class="fa fa-trash"></i> Delete
            </a>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p style="text-align:center; color:#555;">No items found.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
