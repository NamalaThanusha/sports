<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Approve/Reject booking
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'approve') {
        $conn->query("UPDATE bookings SET status = 'approved' WHERE id = $id");
        // Mark equipment as borrowed
        $row = $conn->query("SELECT equipment_id FROM bookings WHERE id = $id")->fetch_assoc();
        $conn->query("UPDATE equipment SET availability_status = 'borrowed' WHERE id = {$row['equipment_id']}");
    } elseif ($action === 'reject') {
        $conn->query("UPDATE bookings SET status = 'rejected' WHERE id = $id");
        $row = $conn->query("SELECT equipment_id FROM bookings WHERE id = $id")->fetch_assoc();
        $conn->query("UPDATE equipment SET availability_status = 'available' WHERE id = {$row['equipment_id']}");
    }
}

$result = $conn->query("SELECT b.*, u.name AS user_name, e.name AS equipment_name 
                        FROM bookings b 
                        JOIN users u ON b.user_id = u.id 
                        JOIN equipment e ON b.equipment_id = e.id 
                        ORDER BY b.booking_date DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Bookings</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
       table {
      border-collapse: collapse;
      width: 100%;
      margin-top: 20px;
      font-size: 16px;
      background-color: #fff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 14px 20px;
      text-align: left;
    }
    th {
      background-color: #0065B5;
      color: white;
      font-weight: bold;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    tr:hover {
      background-color: #f1f1f1;
    }

    
    .btn-approve {
      background-color: #28a745;
      color: white;
      padding: 6px 12px;
      text-decoration: none;
      border-radius: 5px;
    }
    .btn-approve:hover {
      background-color: #218838;
    }
    .btn-reject {
      background-color: #dc3545;
      color: white;
      padding: 6px 12px;
      text-decoration: none;
      border-radius: 5px;
    }
    .btn-reject:hover {
      background-color: #c82333;
    }
  </style>
</head>
<body>
<header>
  <h1>Sports Equipment Booking Portal</h1>
  <p>A platform to digitize the process of lending and booking sports equipment in colleges to reduce loss, misuse, and disputes.</p>
  <nav>
    <a href="./dashboard.php">Dashboard</a>
    <a href="./bulk_upload.php">Bulk Uploading</a>
    <a href="./add.php">Add Equipment</a>
    <a href="./manage_bookings.php">Manage Bookings</a>
    <a href="./manage_equipment.php">Manage Equipment</a>
  </nav>
</header>
<div class="container">
  <h2>Manage Bookings</h2>
  <table border="1" width="100%">
    <tr><th>User</th><th>Equipment</th><th>Slot Timing</th><th>Day</th><th>Status</th><th>Action</th></tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['user_name'] ?></td>
      <td><?= $row['equipment_name'] ?></td>
      <td><?= $row['booking_date'] ?></td>
      <td><?= $row['return_date'] ?></td>
      <td><?= $row['status'] ?></td>
      <td>
        <?php if ($row['status'] === 'pending'): ?>
          <a class="btn-approve" href="?action=approve&id=<?= $row['id'] ?>">Approve</a>
          <a class="btn-reject" href="?action=reject&id=<?= $row['id'] ?>">Reject</a>
        <?php else: ?>
          -
        <?php endif; ?>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
