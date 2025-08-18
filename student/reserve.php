<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipment_id = intval($_POST['equipment_id']);
    $user_id = $_SESSION['user_id'];
    $slot_time = $_POST['slot_time'];
    $return_date = $_POST['return_date'];
    $stmt = $conn->prepare('SELECT availability_status FROM equipment WHERE id = ?');
    $stmt->bind_param('i', $equipment_id);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    $stmt->close();
    if ($status === 'available') {
        $stmt = $conn->prepare('INSERT INTO bookings (user_id, equipment_id, booking_date, slot_time, return_date, status) VALUES (?, ?, NOW(), ?, ?, "pending")');
        $stmt->bind_param('iiss', $user_id, $equipment_id, $slot_time, $return_date);
        $stmt->execute();
        $stmt->close();
        $conn->query("UPDATE equipment SET availability_status = 'reserved' WHERE id = $equipment_id");
        header('Location: my_bookings.php');
        exit;
    } else {
        echo "<script>alert('Equipment not available');window.location='search.php';</script>";
        exit;
    }
}
?>
