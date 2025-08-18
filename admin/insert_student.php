<?php
require_once '../includes/db.php';

$name = $_POST['name'] ?? '';
$register_number = $_POST['register_number'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($name && $register_number && $email && $password) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT INTO users (name, email, password, role, register_number) VALUES (?, ?, ?, "student", ?)');
    $stmt->bind_param('ssss', $name, $email, $hashed, $register_number);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "fail";
    }
    $stmt->close();
} else {
    echo "fail";
}
?>