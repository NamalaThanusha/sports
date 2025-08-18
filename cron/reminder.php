<?php
// Cron job: Send reminders and update penalties for late returns
require_once '../includes/db.php';

// Get bookings that are borrowed and overdue (return_date is NULL and booking_date older than 3 days)
$result = $conn->query("SELECT b.id, b.user_id, u.email, e.name, b.booking_date FROM bookings b JOIN users u ON b.user_id = u.id JOIN equipment e ON b.equipment_id = e.id WHERE b.status = 'borrowed' AND DATE_ADD(b.booking_date, INTERVAL 3 DAY) < NOW()");

while ($row = $result->fetch_assoc()) {
    $booking_id = $row['id'];
    $email = $row['email'];
    $equipment = $row['name'];
    $date = $row['booking_date'];
    // Send email reminder
    $subject = "Sports Equipment Return Reminder";
    $message = "Dear user,\n\nPlease return the equipment '$equipment' borrowed on $date. Late returns will incur a penalty.\n\nRegards, Sports Equipment Booking Portal";
    mail($email, $subject, $message);
    // Update penalty
    $conn->query("UPDATE bookings SET status = 'late', fine_amount = 100 WHERE id = $booking_id");
}
?>
<!-- Run this script via cron or scheduled task for automated reminders -->
