<?php
require_once '../includes/auth.php';
if (!is_student()) { header('Location: ../login.php'); exit; }
require_once '../includes/db.php';
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT b.*, e.name AS equipment_name, e.sport_type FROM bookings b JOIN equipment e ON b.equipment_id = e.id WHERE b.user_id = $user_id ORDER BY b.booking_date DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>My Bookings</title>
<style>
  /* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: #333;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

/* Header Styles */
header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 2rem 0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

header h1 {
    text-align: center;
    color: #2c3e50;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

header p {
    text-align: center;
    color: #7f8c8d;
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    padding: 0 1rem;
}

/* Navigation Styles */
nav {
    text-align: center;
    margin-top: 1rem;
}

nav a {
    display: inline-block;
    margin: 0 1rem;
    padding: 0.75rem 2rem;
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
}

nav a:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
    background: linear-gradient(135deg, #2980b9, #3498db);
}

/* Container Styles */
.container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.container h2 {
    text-align: center;
    color: white;
    font-size: 2.2rem;
    margin-bottom: 2rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

/* Table Container */
.table-container {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(10px);
    overflow-x: auto;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

th {
    background: linear-gradient(135deg, #2c3e50, #34495e);
    color: white;
    padding: 1.2rem 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

th:first-child {
    border-top-left-radius: 12px;
}

th:last-child {
    border-top-right-radius: 12px;
}

td {
    padding: 1rem;
    border-bottom: 1px solid #e0e6ed;
    background: white;
    transition: background-color 0.3s ease;
}

tr:hover td {
    background-color: #f8f9fa;
}

tr:last-child td {
    border-bottom: none;
}

tr:last-child td:first-child {
    border-bottom-left-radius: 12px;
}

tr:last-child td:last-child {
    border-bottom-right-radius: 12px;
}

/* Status Badge Styles */
td:nth-child(6) {
    font-weight: 600;
    text-transform: capitalize;
}

/* Status Colors */
tr:has(td:nth-child(6):contains("borrowed")) td:nth-child(6) {
    color: #e67e22;
    background-color: #fdf2e9;
    border-radius: 20px;
    padding: 0.4rem 0.8rem;
    text-align: center;
    font-size: 0.9rem;
}

tr:has(td:nth-child(6):contains("returned")) td:nth-child(6) {
    color: #27ae60;
    background-color: #eafaf1;
    border-radius: 20px;
    padding: 0.4rem 0.8rem;
    text-align: center;
    font-size: 0.9rem;
}

tr:has(td:nth-child(6):contains("pending")) td:nth-child(6) {
    color: #f39c12;
    background-color: #fef9e7;
    border-radius: 20px;
    padding: 0.4rem 0.8rem;
    text-align: center;
    font-size: 0.9rem;
}

/* Button Styles */
.btn {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
    padding: 0.6rem 1.5rem;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    box-shadow: 0 3px 10px rgba(231, 76, 60, 0.3);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
    background: linear-gradient(135deg, #c0392b, #e74c3c);
}

.btn:active {
    transform: translateY(0);
}

/* Fine Amount Styling */
td:last-child {
    font-weight: 600;
    color: #e74c3c;
}

/* Equipment and Sport Type Styling */
td:nth-child(1) {
    font-weight: 600;
    color: #2c3e50;
}

td:nth-child(2) {
    color: #7f8c8d;
    font-style: italic;
}

/* Date Styling */
td:nth-child(3), td:nth-child(5) {
    color: #5d6d7e;
    font-family: 'Courier New', monospace;
}

/* Time Slot Styling */
td:nth-child(4) {
    color: #8e44ad;
    font-weight: 500;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem;
    color: #7f8c8d;
}

.empty-state h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #95a5a6;
}

/* Responsive Design */
@media (max-width: 768px) {
    header h1 {
        font-size: 2rem;
    }
    
    header p {
        font-size: 1rem;
    }
    
    nav a {
        display: block;
        margin: 0.5rem auto;
        max-width: 200px;
    }
    
    .container {
        padding: 0 0.5rem;
    }
    
    .table-container {
        padding: 1rem;
        margin: 0 0.5rem;
    }
    
    table {
        font-size: 0.9rem;
    }
    
    th, td {
        padding: 0.8rem 0.5rem;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    header {
        padding: 1.5rem 0;
    }
    
    header h1 {
        font-size: 1.7rem;
    }
    
    .container h2 {
        font-size: 1.8rem;
    }
    
    table {
        font-size: 0.8rem;
    }
    
    th, td {
        padding: 0.6rem 0.4rem;
    }
}

/* Loading Animation */
.loading {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Hover Effects for Table Rows */
tr {
    transition: all 0.3s ease;
}

tr:hover {
    transform: scale(1.01);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

/* Success/Error Messages */
.message {
    padding: 1rem 1.5rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    font-weight: 500;
}

.message.success {
    background: linear-gradient(135deg, #d5f4e6, #c3f0d8);
    color: #27ae60;
    border-left: 4px solid #27ae60;
}

.message.error {
    background: linear-gradient(135deg, #fdeaea, #fbd5d5);
    color: #e74c3c;
    border-left: 4px solid #e74c3c;
}

/* Footer Styling */
footer {
    background: rgba(0, 0, 0, 0.1);
    color: white;
    text-align: center;
    padding: 2rem 0;
    margin-top: 3rem;
    backdrop-filter: blur(10px);
}

/* Glassmorphism Effect */
.glass {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 15px;
}
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
</header><div class="container">
  <h2>My Bookings</h2>
  <table border="1" width="100%">
    <tr>
      <th>Equipment</th>
      <th>Sport</th>
      <th>Slot Timing</th>
      <th>Booking Day</th>
      <th>Status</th>
      <th>Return</th>
      <th>Fine</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['equipment_name'] ?></td>
      <td><?= $row['sport_type'] ?></td>
      <td><?= $row['booking_date'] ?></td>
      <td><?= $row['return_date'] ?></td>
      <td><?= $row['status'] ?></td>
      <td>
        <?php if ($row['status'] === 'borrowed'): ?>
          <form method="post" action="return.php">
            <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
            <button class="btn" type="submit">Return</button>
          </form>
        <?php elseif ($row['status'] === 'returned'): ?>
          Returned
        <?php else: ?>
          -
        <?php endif; ?>
      </td>
      <td><?= $row['fine_amount'] ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
