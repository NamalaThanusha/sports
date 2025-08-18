<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
// Add equipment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $sport_type = $_POST['sport_type'];
    $image_url = $_POST['image_url'];
    $stmt = $conn->prepare('INSERT INTO equipment (name, sport_type, availability_status, image_url) VALUES (?, ?, "available", ?)');
    $stmt->bind_param('sss', $name, $sport_type, $image_url);
    $stmt->execute();
    $stmt->close();
}
// Remove equipment
if (isset($_GET['remove'])) {
  $id = intval($_GET['remove']);
  // First delete all bookings for this equipment
  $conn->query("DELETE FROM bookings WHERE equipment_id = $id");
  // Then delete the equipment itself
  $conn->query("DELETE FROM equipment WHERE id = $id");
}
$equipments = $conn->query('SELECT * FROM equipment');
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Equipment</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    /* Table styling */
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

    /* Bigger columns */
    th:nth-child(1), td:nth-child(1) { width: 30%; }
    th:nth-child(2), td:nth-child(2) { width: 25%; }
    th:nth-child(3), td:nth-child(3) { width: 20%; }
    th:nth-child(4), td:nth-child(4) { width: 25%; }

    /* Action button styling */
    .action-btn {
      display: inline-block;
      padding: 8px 14px;
      background-color: #dc3545;
      color: #fff;
      border-radius: 5px;
      text-decoration: none;
      font-size: 14px;
      font-weight: bold;
      transition: background-color 0.2s ease;
    }
    .action-btn:hover {
      background-color: #b02a37;
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
  <h2>Manage Equipment</h2>
  <form method="post">
    <input type="text" name="name" placeholder="Equipment Name" required>
    <input type="text" name="sport_type" placeholder="Sport Type" required>
    <input type="text" name="image_url" placeholder="Image URL (assets/images/...)" required>
    <button class="btn" type="submit" name="add">Add Equipment</button>
  </form>
  <h3>Equipment List</h3>
  <table>
    <tr><th>Name</th><th>Sport</th><th>Status</th><th>Action</th></tr>
    <?php while ($row = $equipments->fetch_assoc()): ?>
    <tr>
      <td><?= $row['name'] ?></td>
      <td><?= $row['sport_type'] ?></td>
      <td><?= $row['availability_status'] ?></td>
      <td><a class="action-btn" href="?remove=<?= $row['id'] ?>">Remove</a></td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
