<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            background: 
                linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.4)),
                url('https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'),
                url('https://images.unsplash.com/photo-1546519638-68e109498ffc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover, cover, cover;
            background-position: center, center, center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Sports equipment overlay pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                radial-gradient(circle at 60% 70%, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 200px 200px, 150px 150px, 250px 250px;
            pointer-events: none;
            z-index: 1;
            animation: patternFloat 30s ease-in-out infinite;
        }

        @keyframes patternFloat {
            0%, 100% { 
                transform: translateX(0) translateY(0); 
                opacity: 0.3;
            }
            25% { 
                transform: translateX(10px) translateY(-5px); 
                opacity: 0.5;
            }
            50% { 
                transform: translateX(-5px) translateY(10px); 
                opacity: 0.3;
            }
            75% { 
                transform: translateX(-10px) translateY(-10px); 
                opacity: 0.4;
            }
        }

        /* Floating particles animation */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 2;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            animation: float 25s infinite linear;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }

        /* Header Styles */
        header {
            background: rgba(30, 58, 114, 0.95);
            backdrop-filter: blur(15px);
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 2rem;
            position: relative;
            z-index: 10;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header-left h1 {
            color: #fff;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.25rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .header-left p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .header-right {
            display: flex;
            gap: 1rem;
        }

        .header-btn {
            padding: 0.6rem 1.2rem;
            background: rgba(59, 130, 246, 0.8);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }

        .header-btn:hover {
            background: rgba(59, 130, 246, 1);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        /* Main Container */
        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            position: relative;
            z-index: 5;
            max-width: 800px;
            margin: 0 auto;
        }

        .welcome-message {
            text-align: center;
            margin-bottom: 3rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            padding: 2rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .welcome-message h2 {
            font-size: 2.8rem;
            color: #fff;
            margin-bottom: 1rem;
            text-shadow: 0 3px 6px rgba(0, 0, 0, 0.5);
            animation: fadeInUp 1s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .buttons-container {
            display: flex;
            flex-direction: column;
            gap: 2.5rem;
            width: 100%;
            max-width: 500px;
        }

        .btn-group {
            text-align: center;
            animation: fadeInUp 1s ease;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.4s ease;
        }

        .btn-group:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .btn-group:nth-child(2) { animation-delay: 0.2s; }
        .btn-group:nth-child(3) { animation-delay: 0.4s; }

        .btn {
            display: inline-block;
            padding: 1.2rem 2.5rem;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.8), rgba(29, 78, 216, 0.9));
            color: white;
            text-decoration: none;
            border-radius: 50px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            font-size: 1.2rem;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(15px);
            box-shadow: 0 6px 25px rgba(59, 130, 246, 0.4);
            min-width: 300px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 1), rgba(29, 78, 216, 1));
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 10px 35px rgba(59, 130, 246, 0.6);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .btn-icon {
            width: 90px;
            height: 90px;
            margin-top: 1.5rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
            transition: all 0.4s ease;
            backdrop-filter: blur(15px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-group:hover .btn-icon {
            transform: rotateY(180deg) scale(1.1);
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
        }

        .btn-icon svg {
            width: 45px;
            height: 45px;
            fill: white;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        /* Footer */
        footer {
            background: rgba(30, 58, 114, 0.95);
            color: rgba(255, 255, 255, 0.95);
            text-align: center;
            padding: 2.5rem;
            margin-top: 4rem;
            backdrop-filter: blur(15px);
            border-top: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3);
        }

        footer p {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        footer p:first-child {
            font-size: 1.2rem;
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .header-right {
                justify-content: center;
            }

            .welcome-message h2 {
                font-size: 2.2rem;
            }

            .btn {
                min-width: 250px;
                font-size: 1rem;
                padding: 1rem 2rem;
            }

            .container {
                padding: 1rem;
            }

            .btn-group {
                padding: 1.5rem;
            }

            .btn-icon {
                width: 70px;
                height: 70px;
            }

            .btn-icon svg {
                width: 35px;
                height: 35px;
            }
        }

        /* Additional sports-themed elements */
        .sports-accent {
            position: absolute;
            top: 20%;
            right: 10%;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            animation: bounce 6s ease-in-out infinite;
            z-index: 1;
        }

        .sports-accent:nth-child(2) {
            top: 60%;
            left: 5%;
            width: 80px;
            height: 80px;
            animation-delay: 2s;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.1); }
        }
    </style>
</head>
<body>
    <!-- Sports accent elements -->
    <div class="sports-accent"></div>
    <div class="sports-accent"></div>

    <!-- Animated background particles -->
    <div class="particles" id="particles"></div>

    <!-- Header -->
    <header>
        <div class="header-content">
            <div class="header-left">
                <h1>Equipment Management System</h1>
                <p>Student Portal</p>
            </div>
            <div class="header-right">
                <a href="../student/dashboard.php" class="header-btn">Home</a>
                <a href="../logout.php" class="header-btn">Logout</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="welcome-message">
            <h2>Welcome, Student!</h2>
        </div>

        <div class="buttons-container">
            <div class="btn-group">
                <a class="btn" href="search.php">Search & Book Equipment</a>
                <div class="btn-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                    </svg>
                </div>
            </div>

            <div class="btn-group">
                <a class="btn" href="my_bookings.php">My Bookings</a>
                <div class="btn-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                    </svg>
                </div>
            </div>
            <div class="btn-group">
                <a class="btn" href="../admin/change_password.php">CHANGE PASSWORD</a>
                <div class="btn-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Equipment Management System. All rights reserved.</p>
        <p>Designed for seamless equipment booking and management.</p>
    </footer>

    <script>
        // Create floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 40;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 15) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        // Initialize particles when page loads
        document.addEventListener('DOMContentLoaded', createParticles);

        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add entrance animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.btn-group').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });

        // Dynamic background change with sports images
        const sportsImages = [
            'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', // Cricket stadium
            'https://images.unsplash.com/photo-1546519638-68e109498ffc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', // Basketball court
            'https://images.unsplash.com/photo-1544717297-fa95b6ee9643?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', // Badminton/Tennis court
            'https://images.unsplash.com/photo-1593341646782-e0b495cff86d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'  // Badminton shuttlecock
        ];

        let currentImageIndex = 0;

        function changeBackground() {
            currentImageIndex = (currentImageIndex + 1) % sportsImages.length;
            document.body.style.backgroundImage = `
                linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.4)),
                url('${sportsImages[currentImageIndex]}')
            `;
        }

        // Change background every 15 seconds
        setInterval(changeBackground, 15000);
    </script>
</body>
</html>