<?php
session_start();
include("config.php");

// 🔐 Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit;
}

// Approve report
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    mysqli_query($conn, "UPDATE reports SET status='approved' WHERE id=$id");
    header("Location: admin-dashboard.php");
    exit;
}

// Delete report
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM reports WHERE id=$id");
    header("Location: admin-dashboard.php");
    exit;
}

// Fetch all reports
$reports = mysqli_query($conn, "SELECT reports.*, users.username FROM reports JOIN users ON reports.user_id=users.id ORDER BY reports.id DESC");

// Fetch total users
$totalUsers = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$totalUsersRow = mysqli_fetch_assoc($totalUsers)['total'];

// Fetch total reports
$totalReports = mysqli_query($conn, "SELECT COUNT(*) as total FROM reports");
$totalReportsRow = mysqli_fetch_assoc($totalReports)['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | Lost & Found Portal</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<style>
/* Reuse your previous CSS for sidebar and main-content */
/* Add table styles */
table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border-radius: 10px;
  overflow: hidden;
}
th, td {
  padding: 12px;
  border-bottom: 1px solid #ddd;
  text-align: left;
}
th {
  background: #00264d;
  color: white;
}
.action-btn {
  padding: 5px 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  color: white;
}
.approve { background: #27ae60; }
.approve:hover { background: #1e8449; }
.delete { background: #e74c3c; }
.delete:hover { background: #c0392b; }

/* Reset & font */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Outfit", sans-serif;
}

body {
  display: flex;
  background: #f6f8fc;
  min-height: 100vh;
}

/* Sidebar */
.sidebar {
  width: 250px;
  background: linear-gradient(180deg, #00264d, #5e17eb);
  color: white;
  padding-top: 30px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.sidebar h2 {
  text-align: center;
  margin-bottom: 30px;
  font-size: 22px;
}

.sidebar ul {
  list-style: none;
  padding-left: 0;
}

.sidebar ul li {
  padding: 15px 20px;
  cursor: pointer;
  transition: 0.3s;
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 500;
}

.sidebar ul li:hover {
  background: rgba(255, 255, 255, 0.2);
}

.logout {
  padding: 15px 20px;
  background: rgba(255, 255, 255, 0.15);
  text-align: center;
  cursor: pointer;
  transition: 0.3s;
}

.logout:hover {
  background: red;
}

/* Main Content */
.main-content {
  flex: 1;
  padding: 30px;
}

header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

header h1 {
  color: #00264d;
  font-size: 28px;
}

/* Cards */
.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.card {
  background: white;
  border-radius: 15px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  padding: 20px;
  text-align: center;
  transition: 0.3s;
}

.card:hover {
  transform: translateY(-5px);
}

.card i {
  font-size: 35px;
  color: #5e17eb;
  margin-bottom: 10px;
}

.card h3 {
  color: #00264d;
  margin-bottom: 5px;
}

.card p {
  color: gray;
  font-size: 14px;
}

/* Table */
table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border-radius: 10px;
  overflow: hidden;
  margin-top: 20px;
}

th, td {
  padding: 12px 15px;
  border-bottom: 1px solid #ddd;
  text-align: left;
  font-size: 14px;
}

th {
  background: #00264d;
  color: white;
}

tr:hover {
  background: #f1f1f1;
  transition: 0.3s;
}

/* Action Buttons */
.action-btn {
  padding: 5px 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  color: white;
  font-size: 14px;
  margin-right: 5px;
  transition: 0.3s;
}

.approve {
  background: #27ae60;
}

.approve:hover {
  background: #1e8449;
}

.delete {
  background: #e74c3c;
}

.delete:hover {
  background: #c0392b;
}

/* Responsive */
@media (max-width: 768px) {
  .cards {
    grid-template-columns: 1fr;
  }

  .sidebar {
    width: 200px;
  }
}

</style>
</head>
<body>
<div class="sidebar">
  <div>
    <h2>Admin Panel</h2>
    <ul>
      <li><i class="fa-solid fa-house"></i>Dashboard</li>
      <li><i class="fa-solid fa-box"></i>Manage Lost/Found Items</li>
      <li><i class="fa-solid fa-user"></i>Manage Users</li>
    </ul>
  </div>
  <div class="logout" id="logoutBtn">
    <i class="fa-solid fa-right-from-bracket"></i> Logout
  </div>
</div>

<div class="main-content">
  <header>
    <h1>Welcome, Admin</h1>
  </header>

  <div class="cards">
    <div class="card">
      <i class="fa-solid fa-box"></i>
      <h3>Lost/Found Items</h3>
      <p><?php echo $totalReportsRow; ?> Items reported</p>
    </div>
    <div class="card">
      <i class="fa-solid fa-user"></i>
      <h3>Registered Users</h3>
      <p><?php echo $totalUsersRow; ?> Active users</p>
    </div>
  </div>

  <h2 style="margin-top:20px;">Manage Reports</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Item Name</th>
      <th>Reported By</th>
      <th>Location</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($reports)): ?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo htmlspecialchars($row['title']); ?></td>
      <td><?php echo htmlspecialchars($row['username']); ?></td>
      <td><?php echo htmlspecialchars($row['location']); ?></td>
      <td><?php echo $row['status']; ?></td>
      <td>
        <?php if($row['status']=='pending'): ?>
          <a href="?approve=<?php echo $row['id']; ?>" class="action-btn approve">Approve</a>
        <?php endif; ?>
        <a href="?delete=<?php echo $row['id']; ?>" class="action-btn delete" onclick="return confirm('Delete this report?')">Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>

<script>
document.getElementById("logoutBtn").addEventListener("click", () => {
  window.location.href = "logout.php";
});
</script>
</body>
</html>
