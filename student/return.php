<?php
require_once '../includes/auth.php';
if (!is_student()) { header('Location: ../login.php'); exit; }
require_once '../includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = intval($_POST['booking_id']);
    $stmt = $conn->prepare('UPDATE bookings SET status = "returned", return_date = NOW() WHERE id = ?');
    $stmt->bind_param('i', $booking_id);
    $stmt->execute();
    $stmt->close();
    // Set equipment available again
    $stmt = $conn->prepare('SELECT equipment_id FROM bookings WHERE id = ?');
    $stmt->bind_param('i', $booking_id);
    $stmt->execute();
    $stmt->bind_result($equipment_id);
    $stmt->fetch();
    $stmt->close();
    $conn->query("UPDATE equipment SET availability_status = 'available' WHERE id = $equipment_id");
    header('Location: my_bookings.php');
    exit;
}
?>
