<?php
require_once '../includes/auth.php';

require_once '../includes/db.php';
// Borrowing history, fines, usage stats
$history = $conn->query("SELECT b.*, u.name AS user_name, e.name AS equipment_name FROM bookings b JOIN users u ON b.user_id = u.id JOIN equipment e ON b.equipment_id = e.id ORDER BY b.booking_date DESC");
$fines = $conn->query("SELECT SUM(fine_amount) AS total_fines FROM bookings WHERE fine_amount > 0")->fetch_assoc();
$usage = $conn->query("SELECT sport_type, COUNT(*) AS count FROM equipment GROUP BY sport_type");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Reports</title>
  <link rel="stylesheet" href="../assets/css/style.css">
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
  <h2>Reports</h2>
  <h3>Borrowing History</h3>
  <table border="1" width="100%">
    <tr><th>User</th><th>Equipment</th><th>Slot Timin</th><th>Day</th><th>Status</th><th>Fine</th></tr>
    <?php while ($row = $history->fetch_assoc()): ?>
    <tr>
      <td><?= $row['user_name'] ?></td>
      <td><?= $row['equipment_name'] ?></td>
      <td><?= $row['booking_date'] ?></td>
     
      <td><?= $row['return_date'] ?></td>
      <td><?= $row['status'] ?></td>
      <td><?= $row['fine_amount'] ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
  <h3>Total Fines: <?= $fines['total_fines'] ?></h3>
  <h3>Usage Stats</h3>
  <table border="1" width="50%">
    <tr><th>Sport Type</th><th>Count</th></tr>
    <?php while ($row = $usage->fetch_assoc()): ?>
    <tr>
      <td><?= $row['sport_type'] ?></td>
      <td><?= $row['count'] ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
