<?php
// admin/bulk_upload.php
require_once '../includes/db.php';

$resultMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    $pythonPath = "C:\\Users\\namal\\AppData\\Local\\Programs\\Python\\Python311\\python.exe";
    $scriptPath = "C:\\xampp\\htdocs\\project\\admin\\scripts\\bulk_upload.py";
    $uploadDir  = "C:\\xampp\\htdocs\\project\\admin\\uploads\\";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $filePath = $uploadDir . basename($_FILES['excel_file']['name']);
    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $filePath)) {
        $cmd = "\"$pythonPath\" \"$scriptPath\" \"$filePath\" 2>&1";
        $output = [];
        $return_var = 0;
        exec($cmd, $output, $return_var);
    $resultMsg = implode('<br>', $output);
    } else {
        $resultMsg = "Failed to upload file.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bulk Upload Students</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Input fields */
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
    <h2>Bulk Upload Students</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Select Excel file (.xlsx):</label>
        <input type="file" name="excel_file" accept=".xlsx" required class="form-input">
        <button type="submit" class="btn btn-success">Upload</button>
    </form>
</div>
</body>
</html>
