<?php
// Handle AJAX booking
if (isset($_GET['action']) && $_GET['action'] === 'reserve' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../includes/db.php';
    require_once '../includes/auth.php';

    if (!is_student()) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    $equipment_id = intval($_POST['equipment_id'] ?? 0);
    $slot_time    = $_POST['slot_time'] ?? '';
    $return_day   = $_POST['return_day'] ?? ''; // Changed from return_date to return_day
    $user_id      = $_SESSION['user_id'] ?? 0;

    if (!$equipment_id || !$slot_time || !$return_day || !$user_id) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        exit;
    }

    // Check if slot is already booked
    $stmt = $conn->prepare('SELECT COUNT(*) FROM bookings WHERE equipment_id = ? AND booking_date = ? AND status IN ("pending", "approved", "borrowed")');
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
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

    // Check availability
    $stmt = $conn->prepare('SELECT availability_status FROM equipment WHERE id = ?');
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
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

    // Insert booking — store day in return_date column
    $stmt = $conn->prepare('INSERT INTO bookings (user_id, equipment_id, booking_date, return_date, status) VALUES (?, ?, ?, ?, "pending")');
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param('iiss', $user_id, $equipment_id, $slot_time, $return_day);

    if ($stmt->execute()) {
        $stmt->close();
        // Get total slots for this equipment
        $slots = [];
        $slotsFile = __DIR__ . "/get_slots.php";
        if (file_exists($slotsFile)) {
            ob_start();
            include $slotsFile;
            $output = ob_get_clean();
            $slots = json_decode($output, true) ?? [];
        }

        // Count booked slots
        $stmt2 = $conn->prepare('SELECT COUNT(DISTINCT booking_date) FROM bookings WHERE equipment_id = ? AND status IN ("pending", "approved", "borrowed")');
        $stmt2->bind_param('i', $equipment_id);
        $stmt2->execute();
        $stmt2->bind_result($bookedSlots);
        $stmt2->fetch();
        $stmt2->close();

        // If booked slots match total slots, update equipment status
        if ($bookedSlots >= count($slots)) {
            $conn->query("UPDATE equipment SET availability_status = 'reserved' WHERE id = $equipment_id");
        }
        // Check final status after booking
        $stmt3 = $conn->prepare('SELECT availability_status FROM equipment WHERE id = ?');
        $stmt3->bind_param('i', $equipment_id);
        $stmt3->execute();
        $stmt3->bind_result($finalStatus);
        $stmt3->fetch();
        $stmt3->close();

        echo json_encode([
            'success' => true,
            'message' => 'Booking successful!',
            'status'  => $finalStatus
        ]);
    } else {
        $errorMsg = $stmt->error;
        $stmt->close();
        echo json_encode(['success' => false, 'message' => 'Failed to book equipment: ' . $errorMsg]);
    }
    exit;
}

// Equipment availability check
require_once '../includes/auth.php';
require_once '../includes/db.php';

if (isset($_GET['equipment_id'])) {
    $id = intval($_GET['equipment_id']);
    $stmt = $conn->prepare('SELECT availability_status FROM equipment WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    echo json_encode(['status' => $status]);
    exit;
}

$equipments = $conn->query('SELECT * FROM equipment');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Equipment</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/main.js"></script>
    <style>
        /* Header and Footer Bluish Styling */
        header {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6) !important;
            color: white !important;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(30, 58, 138, 0.3);
        }
        
        header h1 {
            color: white !important;
        }
        
        header p {
            color: #e0e7ff !important;
        }
        
        header nav a {
            color: #bfdbfe !important;
            text-decoration: none;
            margin-right: 15px;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        header nav a:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }
        
        footer {
            background: linear-gradient(135deg, #1e40af, #2563eb) !important;
            color: white !important;
            padding: 15px;
            text-align: center;
            margin-top: 40px;
        }

        /* Keep existing styles for the main content */
        .grid { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
        .card { background: #fafafa; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.08); padding: 16px; width: 220px; text-align: center; }
        .btn { background: #007bff; color: #fff; border: none; padding: 10px 18px; border-radius: 4px; cursor: pointer; }
        .btn:disabled { background: #aaa; cursor: not-allowed; }
        .status-available { color: #28a745; font-weight: bold; }
        .status-reserved { color: #ffc107; font-weight: bold; }
        .status-borrowed { color: #dc3545; font-weight: bold; }
        .modal-overlay { position: fixed; top:0; left:0; width:100vw; height:100vh; background: rgba(0, 0, 0, 0.5); display:flex; align-items:center; justify-content:center; z-index:9999; }
        .modal-content { background: #fff; padding: 30px; border-radius: 8px; min-width: 300px; max-width: 90vw; }
    </style>
</head>
<body>
<header>
  <h1>Sports Equipment Booking Portal</h1>
  <p>A platform to digitize the process of lending and booking sports equipment in colleges to reduce loss, misuse, and disputes.</p>
  <nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="/project/logout.php">Logout</a>
  </nav>
</header>
<div class="container">
    <h2>Available Sports Equipment</h2>
    <div class="grid">
        <?php while ($row = $equipments->fetch_assoc()): ?>
            <div class="card">
                <img src="../admin/images/<?= $row['image_url'] ?>" alt="<?= $row['name'] ?>" width="100">
                <h3><?= $row['name'] ?></h3>
                <p><?= $row['sport_type'] ?></p>
                <p>Status: <span id="status-<?= $row['id'] ?>" class="status-<?= $row['availability_status'] ?>"><?= $row['availability_status'] ?></span></p>
                <button class="btn reserve-btn" data-id="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>" data-status="<?= $row['availability_status'] ?>" <?= $row['availability_status'] !== 'available' ? 'disabled' : '' ?>>Reserve Now</button>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
function openCustomReservePopup(equipmentId, equipmentName, status) {
    if (status !== 'available') return;

    fetch(`/project/student/get_slots.php?equipment_id=${equipmentId}`)
        .then(response => response.json())
        .then(slots => {
            let slotOptions = slots.map(slot => `<option value='${slot}'>${slot}</option>`).join('');
            let popupHtml = `
                <div id='custom-reserve-popup' class='modal-overlay fade-in'>
                    <div class='modal-content'>
                        <h3>Reserve Equipment – ${equipmentName}</h3>
                        <form id='customReserveForm'>
                            <input type='hidden' name='equipment_id' value='${equipmentId}'>
                            <label>Slot Time:</label><br>
                            <select name='slot_time' required>${slotOptions}</select><br><br>
                            <label>Which Day:</label><br>
                            <select name='return_day' required>
                                <option value='Monday'>Monday</option>
                                <option value='Tuesday'>Tuesday</option>
                                <option value='Wednesday'>Wednesday</option>
                                <option value='Thursday'>Thursday</option>
                                <option value='Friday'>Friday</option>
                                <option value='Saturday'>Saturday</option>
                                <option value='Sunday'>Sunday</option>
                            </select><br><br>
                            <label>Availability Status:</label>
                            <input type='text' value='${status}' readonly><br><br>
                            <div id='custom-modal-error' style='color:red;'></div>
                            <button class='btn' type='submit'>Confirm Booking</button>
                            <button class='btn' type='button' onclick='closeCustomReservePopup()'>Cancel</button>
                        </form>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', popupHtml);

            document.getElementById('customReserveForm').onsubmit = function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                fetch(window.location.pathname + '?action=reserve', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        closeCustomReservePopup();
                        // Update UI only if server says it's reserved now
                        if (data.status === 'reserved') {
                            let statusElem = document.getElementById('status-' + equipmentId);
                            statusElem.textContent = 'reserved';
                            statusElem.className = 'status-reserved';
                            document.querySelector(`.reserve-btn[data-id="${equipmentId}"]`).disabled = true;
                        }
                    } else {
                        document.getElementById('custom-modal-error').textContent = data.message;
                    }
                });
            };
        });
}

function closeCustomReservePopup() {
    let popup = document.getElementById('custom-reserve-popup');
    if (popup) popup.remove();
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.reserve-btn').forEach(btn => {
        btn.onclick = function() {
            openCustomReservePopup(
                btn.getAttribute('data-id'),
                btn.getAttribute('data-name'),
                btn.getAttribute('data-status')
            );
        };
    });
});
</script>
<?php include '../includes/footer.php'; ?>
</body>
</html>