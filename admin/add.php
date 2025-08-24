<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $sport_type = $_POST['sport_type'];
    $availability_status = $_POST['availability_status'];

    // Handle image upload
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = './images/'; // folder where images are stored
        $fileName = basename($_FILES['image_file']['name']);
        $fileTmpPath = $_FILES['image_file']['tmp_name'];

        // Create unique name to avoid overwriting
        $newFileName = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $fileName);
        $destination = $uploadDir . $newFileName;

        // Check file type (optional but recommended)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($fileExt, $allowedExtensions)) {
            if (move_uploaded_file($fileTmpPath, $destination)) {
                $image_url =$newFileName; // store relative path in DB

                // Insert into database
                $stmt = $conn->prepare('INSERT INTO equipment (name, sport_type, availability_status, image_url) VALUES (?, ?, ?, ?)');
                if (!$stmt) {
                    $error = 'Database error: ' . $conn->error;
                } else {
                    $stmt->bind_param('ssss', $name, $sport_type, $availability_status, $image_url);
                    if ($stmt->execute()) {
                        $error = 'Equipment added successfully!';
                    } else {
                        $error = 'Failed to add equipment.';
                    }
                    $stmt->close();
                }
            } else {
                $error = 'Failed to move uploaded file.';
            }
        } else {
            $error = 'Invalid file type. Only JPG, JPEG, PNG, GIF allowed.';
        }
    } else {
        $error = 'No image file uploaded or upload error.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add Equipment</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    /* Form container */
    .form-input {
      width: 100%;
      padding: 8px;
      margin: 6px 0 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    /* Primary button */
    .btn {
      padding: 8px 14px;
      border-radius: 4px;
      text-decoration: none;
      font-size: 14px;
      border: none;
      cursor: pointer;
      background-color: #0065B5;
      color: white;
      transition: 0.3s;
    }
    .btn:hover {
      background-color: #004a86;
    }

    /* Success button */
    .btn-success {
      background-color: #28a745;
    }
    .btn-success:hover {
      background-color: #218838;
    }

    /* Danger button */
    .btn-danger {
      background-color: #dc3545;
    }
    .btn-danger:hover {
      background-color: #b02a37;
    }

    /* Message text */
    .message {
      color: green;
      font-weight: bold;
      margin-top: 10px;
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
  <h2>Add New Equipment</h2>
  <form method="post" enctype="multipart/form-data">
    <label>Name:</label>
    <input type="text" name="name" required class="form-input">

    <label>Sport Type:</label>
    <input type="text" name="sport_type" required class="form-input">

    <label>Availability Status:</label>
    <select name="availability_status" required class="form-input">
      <option value="available">Available</option>
      <option value="reserved">Reserved</option>
      <option value="borrowed">Borrowed</option>
    </select>

    <label>Upload Image:</label>
    <input type="file" name="image_file" accept="image/*" required class="form-input">

    <button class="btn btn-success" type="submit">Add Equipment</button>
  </form>
  
  <?php if (!empty($error)): ?>
    <p class="message"><?= $error ?></p>
  <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
