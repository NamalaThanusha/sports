<?php
// Authentication functions
session_start();
require_once 'db.php';

// Login using register_number and password
function login($register_number, $password) {
    global $conn;
    $stmt = $conn->prepare('SELECT id, name, password, role FROM users WHERE register_number = ?');
    $stmt->bind_param('s', $register_number);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hash, $role);
        $stmt->fetch();
        if (password_verify($password, $hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_role'] = $role;
            $_SESSION['register_number'] = $register_number;
            return true;
        }
    }
    return false;
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin() {
    return is_logged_in() && $_SESSION['user_role'] === 'admin';
}

function is_student() {
    return is_logged_in() && $_SESSION['user_role'] === 'student';
}

function logout() {
    session_destroy();
}
?>
