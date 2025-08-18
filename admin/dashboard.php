<?php
require_once '../includes/auth.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    /* Dashboard container */
    .container {
      text-align: center;
      padding: 30px;
    }

    /* Button styling */
    .dashboard-btn {
      display: inline-block;
      padding: 12px 20px;
      margin: 12px;
      font-size: 16px;
      font-weight: bold;
      color: white;
      background-color: #0065B5;
      border-radius: 6px;
      text-decoration: none;
      transition: 0.3s ease;
    }

    .dashboard-btn:hover {
      background-color: #004f8a;
      transform: scale(1.05);
    }

    /* Header styling */
    header h1 {
      color: #0065B5;
      margin-bottom: 5px;
    }

    header p {
      color: #555;
      font-size: 15px;
      margin-bottom: 15px;
    }

    /* Navigation styling */
    nav {
      background-color: #F69C07;
      padding: 10px;
    }

    nav a {
      color: white;
      text-decoration: none;
      margin: 0 10px;
      font-weight: bold;
    }

    nav a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
<header>
  <h1>Sports Equipment Booking Portal</h1>
  <p>A platform to digitize the process of lending and booking sports equipment in colleges to reduce loss, misuse, and disputes.</p>
  <nav>
    <a href="http://localhost/project/admin/dashboard.php">Dashboard</a>
    <a href="http://localhost/project/admin/bulk_upload.php">Bulk Uploading</a>
    <a href="http://localhost/project/admin/add.php">Add Equipment</a>
    <a href="http://localhost/project/admin/manage_bookings.php">Manage Bookings</a>
    <a href="http://localhost/project/admin/manage_equipment.php">Manage Equipment</a>
  </nav>
</header>

<div class="container">
  <h2>Welcome</h2>
  <a class="dashboard-btn" href="manage_equipment.php">Manage Equipment</a>
  <a class="dashboard-btn" href="manage_bookings.php">Manage Bookings</a>
  <a class="dashboard-btn" href="reports.php">View Reports</a>
  <a class="dashboard-btn" href="../logout.php">Logout</a>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
