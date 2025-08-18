<?php 
require_once 'includes/auth.php'; 

if (is_logged_in()) {
    header('Location: ' . ($_SESSION['user_role'] === 'admin' ? 'admin/dashboard.php' : 'student/dashboard.php'));
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $register_number = $_POST['register_number'];
    $password = $_POST['password'];
    if (login($register_number, $password)) {
        // Fetch user data from DB and store in session
        require_once 'includes/db.php';
        $stmt = $conn->prepare('SELECT id, name, email, role, register_number FROM users WHERE register_number = ?');
        $stmt->bind_param('s', $register_number);
        $stmt->execute();
        $stmt->bind_result($id, $name, $email, $role, $regnum);
        if ($stmt->fetch()) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = $role;
            $_SESSION['register_number'] = $regnum;
        }
        echo $_SESSION['user_id']; // Debug: check user role
        $stmt->close();
        header('Location: ' . ($_SESSION['user_role'] === 'admin' ? 'admin/dashboard.php' : 'student/dashboard.php'));
        exit;
    } else {
        $error = 'Invalid credentials.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sports Equipment Booking Portal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.7)), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800"><defs><pattern id="sports" x="0" y="0" width="200" height="200" patternUnits="userSpaceOnUse"><rect width="200" height="200" fill="%23f0f8ff"/><circle cx="50" cy="50" r="25" fill="%23ff6b35" opacity="0.8"/><rect x="120" y="20" width="60" height="60" rx="5" fill="%232196f3" opacity="0.8"/><polygon points="150,120 170,160 130,160" fill="%234caf50" opacity="0.8"/><rect x="20" y="120" width="60" height="30" rx="15" fill="%23ff9800" opacity="0.8"/></pattern></defs><rect width="1200" height="800" fill="url(%23sports)"/></svg>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
            overflow-x: hidden;
        }

        /* Sports Background Overlay */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 30%, rgba(255, 107, 53, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(33, 150, 243, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(76, 175, 80, 0.1) 0%, transparent 50%);
            z-index: -1;
        }

        /* Sports Background Animation */
        .sports-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.1;
        }

        .sport-item {
            position: absolute;
            font-size: 60px;
            color: white;
            animation: float 6s ease-in-out infinite;
        }

        .sport-item:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .sport-item:nth-child(2) {
            top: 20%;
            right: 10%;
            animation-delay: 1s;
        }

        .sport-item:nth-child(3) {
            top: 60%;
            left: 15%;
            animation-delay: 2s;
        }

        .sport-item:nth-child(4) {
            top: 70%;
            right: 20%;
            animation-delay: 3s;
        }

        .sport-item:nth-child(5) {
            top: 40%;
            left: 50%;
            animation-delay: 4s;
        }

        .sport-item:nth-child(6) {
            top: 80%;
            left: 60%;
            animation-delay: 5s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(10deg);
            }
        }

        /* Header Styles */
        .header {
            background: linear-gradient(90deg, #2c3e50 0%, #3498db 100%);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #74b9ff;
        }

        /* Main Container */
        .container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 20px;
        }

        /* Professional Login Form Styles */
        .login-form {
            background: rgba(255, 255, 255, 0.98);
            padding: 3.5rem 3rem;
            border-radius: 16px;
            box-shadow: 
                0 25px 50px rgba(0,0,0,0.15),
                0 0 0 1px rgba(255,255,255,0.3);
            width: 100%;
            max-width: 420px;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.18);
            position: relative;
            overflow: hidden;
        }

        .login-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3498db, #2980b9, #e74c3c, #f39c12);
            background-size: 400% 100%;
            animation: gradient-shift 3s ease infinite;
        }

        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .login-form h2 {
            text-align: center;
            margin-bottom: 2.5rem;
            color: #2c3e50;
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        .form-group {
            margin-bottom: 1.8rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #5a6c7d;
            font-weight: 500;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid #e8ecf0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fafbfc;
            color: #2c3e50;
            font-weight: 500;
        }

        .form-group input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 
                0 0 0 3px rgba(52, 152, 219, 0.1),
                0 4px 12px rgba(52, 152, 219, 0.15);
            background: #ffffff;
            transform: translateY(-1px);
        }

        .form-group input::placeholder {
            color: #95a5a6;
            font-weight: 400;
        }

        .btn {
            width: 100%;
            padding: 1.1rem;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            background: linear-gradient(135deg, #2980b9 0%, #21618c 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(52, 152, 219, 0.3);
        }

        .btn:active {
            transform: translateY(-1px);
        }

        .error-message {
            color: #e74c3c;
            text-align: center;
            margin-top: 1.5rem;
            font-weight: 500;
            padding: 1rem;
            background: rgba(231, 76, 60, 0.08);
            border-radius: 8px;
            border-left: 4px solid #e74c3c;
            font-size: 0.9rem;
        }

        /* Footer Styles */
        .footer {
            background: linear-gradient(90deg, #2c3e50 0%, #34495e 100%);
            color: white;
            text-align: center;
            padding: 2rem 0;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer h3 {
            margin-bottom: 1rem;
            color: #74b9ff;
        }

        .footer p {
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-form {
                padding: 2.5rem 2rem;
                margin: 1rem;
                max-width: 100%;
            }

            .sport-item {
                font-size: 40px;
            }

            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sports Background Animation -->
    <div class="sports-bg">
        <div class="sport-item">⚽</div>
        <div class="sport-item">🏀</div>
        <div class="sport-item">🏐</div>
        <div class="sport-item">🎾</div>
        <div class="sport-item">🏓</div>
        <div class="sport-item">🏸</div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">🏆 Sports Equipment Portal</div>
            <nav>
                <ul class="nav-links">
                    <li><a href="./index.html">Home</a></li>
                  
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Container -->
    <div class="container">
        <div class="login-form">
            <h2>🔐 Login</h2>
            <form method="post">
                <div class="form-group">
                    <label for="register_number">Register Number</label>
                    <input type="text" name="register_number" id="register_number" placeholder="Enter your register number" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                </div>
                <button class="btn" type="submit">Login</button>
            </form>
            
            <!-- Error Message -->
            <?php if ($error): ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <h3>Sports Equipment Booking Portal</h3>
            <p>Your gateway to sports excellence</p>
            <p>&copy; 2024 Sports Equipment Portal. All rights reserved.</p>
            <p>📞 Contact: +1 234-567-8900 | 📧 Email: info@sportsequipment.com</p>
        </div>
    </footer>

    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>