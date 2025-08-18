<?php
// Database connection for XAMPP (localhost, root, no password, sports_portal)
$host = 'localhost';
$db = 'sports_portal';
$user = 'root';
$pass = '';

// Check if MySQL is running by trying to connect to the server
$conn = @new mysqli($host, $user, $pass);
if ($conn->connect_errno) {
    die('<div style="color:red; font-weight:bold;">Error: MySQL server is not running or not accessible on localhost.<br>Details: ' . $conn->connect_error . '</div>');
}
// Now select the database
if (!$conn->select_db($db)) {
    die('<div style="color:red; font-weight:bold;">Error: Database <b>sports_portal</b> not found.<br>Details: ' . $conn->error . '</div>');
}
?>
