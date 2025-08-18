<?php
require_once 'includes/db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $register_number = $_POST['register_number'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role = 'student';
  try {
    $stmt = $conn->prepare('INSERT INTO users (name, register_number, email, password, role) VALUES (?, ?, ?, ?, ?)');
    if (!$stmt) {
      throw new Exception('Database error: ' . $conn->error);
    }
    $stmt->bind_param('sssss', $name, $register_number, $email, $password, $role);
    if ($stmt->execute()) {
      header('Location: login.php');
      exit;
    } else {
      throw new Exception('Registration failed. Register Number or Email may already exist.');
    }
  } catch (Exception $e) {
    if (strpos($e->getMessage(), 'users') !== false) {
      $error = 'Critical error: The users table does not exist. Please import seed.sql in phpMyAdmin.';
    } else {
      $error = $e->getMessage();
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register - Sports Equipment Booking Portal</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'admin/header.php'; ?>
<div class="container">
  <h2>Register as Student</h2>
  <form method="post">
  <input type="text" name="name" placeholder="Name" required class="form-input"><br>
  <input type="text" name="register_number" placeholder="Register Number" required class="form-input"><br>
  <input type="email" name="email" placeholder="Email" required class="form-input"><br>
  <input type="password" name="password" placeholder="Password" required class="form-input"><br>
  <button class="btn" type="submit">Register</button>
  </form>
  <p style="color:red;"><?= $error ?></p>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
