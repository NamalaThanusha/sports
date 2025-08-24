<?php
session_start();
require_once '../includes/db.php'; // Adjust path if needed
// Check if user is logged in

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$reg_no = $_SESSION['reg_no'];
	$current_password = $_POST['current_password'] ?? '';
	$new_password = $_POST['new_password'] ?? '';
	$confirm_password = $_POST['confirm_password'] ?? '';
	// Basic validation
	if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
		$error = "All fields are required.";
	} elseif ($new_password !== $confirm_password) {
		$error = "New passwords do not match.";
	} elseif (strlen($new_password) < 6) {
		$error = "New password must be at least 6 characters.";
	} else {
	// Fetch current password hash from students table
	$stmt = $conn->prepare("SELECT password FROM students WHERE reg_no = ?");
	$stmt->bind_param("s", $reg_no);
	$stmt->execute();
	$stmt->bind_result($db_password_hash);
	if ($stmt->fetch()) {
		if (password_verify($current_password, $db_password_hash)) {
			// Update password
			$new_hash = password_hash($new_password, PASSWORD_DEFAULT);
			$stmt->close();
			$stmt = $conn->prepare("UPDATE students SET password = ? WHERE reg_no = ?");
			$stmt->bind_param("ss", $new_hash, $reg_no);
			if ($stmt->execute()) {
				$success = "Password changed successfully!";
			} else {
				$error = "Error updating password. Please try again.";
			}
		} else {
			$error = "Current password is incorrect.";
		}
	} else {
		$error = "Student not found.";
	}
	$stmt->close();
}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Change Password</title>
	<link rel="stylesheet" href="../assets/css/style.css">
	<style>
		.form-container { max-width: 400px; margin: 40px auto; padding: 24px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #ccc; }
		.form-group { margin-bottom: 16px; }
		label { display: block; margin-bottom: 6px; }
		input[type="password"] { width: 100%; padding: 8px; }
		.btn { background: #007bff; color: #fff; padding: 10px 18px; border: none; border-radius: 4px; cursor: pointer; }
		.message { margin-bottom: 16px; color: #d9534f; }
		.success { color: #28a745; }
	</style>
</head>
<body>
	<div class="form-container">
		<h2>Change Password</h2>
		<?php if ($error): ?>
			<div class="message"><?= htmlspecialchars($error) ?></div>
		<?php elseif ($success): ?>
			<div class="message success"><?= htmlspecialchars($success) ?></div>
		<?php endif; ?>
		<form method="post" autocomplete="off">
			<div class="form-group">
				<label for="current_password">Current Password</label>
				<input type="password" name="current_password" id="current_password" required>
			</div>
			<div class="form-group">
				<label for="new_password">New Password</label>
				<input type="password" name="new_password" id="new_password" required minlength="6">
			</div>
			<div class="form-group">
				<label for="confirm_password">Confirm New Password</label>
				<input type="password" name="confirm_password" id="confirm_password" required minlength="6">
			</div>
			<button type="submit" class="btn">Change Password</button>
		</form>
	</div>
</body>
</html>
