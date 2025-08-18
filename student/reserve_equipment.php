<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
header('Content-Type: application/json');

// Check DB connection
if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection error.']);
    exit;
}

if (!is_student()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    if (empty($_POST['equipment_id']) || empty($_POST['slot_time']) || empty($_POST['return_date'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        exit;
    }
    $equipment_id = intval($_POST['equipment_id']);
    $slot_time = $_POST['slot_time'];
    $return_date = $_POST['return_date'];
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit;
    }

    // Check if slot is already booked for this equipment
    $stmt = $conn->prepare('SELECT COUNT(*) FROM bookings WHERE equipment_id = ? AND slot_time = ? AND status IN ("pending", "approved", "borrowed")');
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error (prepare failed).']);
        exit;
    }
    $stmt->bind_param('is', $equipment_id, $slot_time);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    if ($count > 0) {
        echo json_encode(['success' => false, 'message' => 'Selected slot is already booked for this equipment.']);
        exit;
    }

    // Check availability status
    $stmt = $conn->prepare('SELECT availability_status FROM equipment WHERE id = ?');
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error (prepare failed).']);
        exit;
    }
    $stmt->bind_param('i', $equipment_id);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    $stmt->close();
    if ($status !== 'available') {
        echo json_encode(['success' => false, 'message' => 'Equipment is not available.']);
        exit;
    }

    // Insert booking
    $stmt = $conn->prepare('INSERT INTO bookings (user_id, equipment_id, booking_date, slot_time, return_date, status) VALUES (?, ?, NOW(), ?, ?, "pending")');
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error (prepare failed).']);
        exit;
    }
    $stmt->bind_param('iiss', $user_id, $equipment_id, $slot_time, $return_date);
    if ($stmt->execute()) {
        $stmt->close();
        // Optionally update equipment status to reserved
        $conn->query("UPDATE equipment SET availability_status = 'reserved' WHERE id = $equipment_id");
        echo json_encode(['success' => true, 'message' => 'Booking successful!']);
    } else {
        $stmt->close();
        echo json_encode(['success' => false, 'message' => 'Failed to book equipment.']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request.']);
exit;
