<?php
session_start();
include("config.php");

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

// --- Delete item if requested ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete_query = "DELETE FROM reports WHERE id = $id";
    mysqli_query($conn, $delete_query);
    header("Location: admin.php");
    exit();
}

// --- Fetch all reports ---
$query = "SELECT reports.*, users.username AS reporter 
          FROM reports 
          JOIN users ON reports.user_id = users.id 
          ORDER BY reports.id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel | Lost & Found Portal</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<style>
/* ----- General ----- */
*{margin:0;padding:0;box-sizing:border-box;font-family:'Outfit',sans-serif;}
body{background:#f2f4f7;padding-top:120px;} /* space for fixed navbar */

/* ----- Navbar ----- */
nav{
  display:flex;justify-content:space-between;align-items:center;
  background:white;color:black;padding:19px 40px;
  position:fixed;width:100%;top:0;box-shadow:0px 0px 10px gray;z-index:100;
}
nav h2{letter-spacing:1px;color:black;}
nav ul{list-style:none;display:flex;gap:20px;}
nav ul li a{text-decoration:none;color:rgb(90,88,88);position:relative;font-weight:500;padding-bottom:5px;transition:0.3s;}
nav ul li a::after{content:'';position:absolute;width:0;height:2px;left:0;bottom:-4px;background-color:rgb(90,88,88);transition:width 0.3s;}
nav ul li a:hover::after{width:100%;}

/* ----- Container ----- */
.container{width:90%;max-width:1200px;margin:0 auto;}
h1{text-align:center;margin-bottom:25px;color:#222;}

/* ----- Items Grid ----- */
.items-grid{
  display:grid;grid-template-columns:repeat(auto-fit,minmax(270px,1fr));gap:20px;
}
.item-card{
  background:white;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.1);
  padding:20px;transition:0.3s ease,box-shadow 0.3s ease;overflow:hidden;
}
.item-card:hover{transform:translateY(-5px);box-shadow:0 5px 15px rgba(0,0,0,0.2);}
.item-card img{width:100%;height:200px;object-fit:cover;border-radius:10px;filter:brightness(95%);transition:0.3s;}
.item-card img:hover{filter:brightness(100%);}
.item-card h3{margin:10px 0;color:#333;}
.item-card p{font-size:14px;color:#555;}

/* ----- Delete Button ----- */
.delete-btn{
  display:inline-block;background:#e74c3c;color:white;text-decoration:none;
  padding:8px 12px;border-radius:5px;cursor:pointer;font-size:14px;margin-top:10px;
  transition:0.3s;
}
.delete-btn:hover{background:#c0392b;}
</style>
</head>
<body>

<nav>
  <h2>Admin Panel | Lost & Found Portal</h2>
  <ul>
    <li><a href="admin.php">Dashboard</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</nav>

<div class="container">
  <h1>All User Reports</h1>
  <div class="items-grid">
    <?php if(mysqli_num_rows($result) > 0): ?>
      <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="item-card">
          <?php if(!empty($row['image'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Item Image">
          <?php else: ?>
            <img src="https://via.placeholder.com/300x200?text=No+Image" alt="No Image">
          <?php endif; ?>

          <h3><?php echo htmlspecialchars($row['item_name']); ?></h3>
          <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
          <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
          <p><strong>Reported by:</strong> <?php echo htmlspecialchars($row['reporter']); ?></p>

          <a href="admin.php?delete=<?php echo $row['id']; ?>" class="delete-btn" 
             onclick="return confirm('Are you sure you want to delete this item?');">
             <i class="fa fa-trash"></i> Delete
          </a>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align:center;color:#555;">No reports found.</p>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
