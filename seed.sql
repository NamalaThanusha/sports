-- Sports Equipment Booking Portal (Enhanced)
-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    register_number VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') NOT NULL
);

-- Equipment Table
CREATE TABLE equipment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    sport_type VARCHAR(50) NOT NULL,
    description TEXT,
    availability_status ENUM('available', 'reserved', 'borrowed') NOT NULL DEFAULT 'available',
    image_url VARCHAR(255)
);

-- Bookings Table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    equipment_id INT NOT NULL,
    booking_date DATETIME NOT NULL,
    slot_time VARCHAR(20) NOT NULL,
    return_date DATE,
    status ENUM('pending', 'approved', 'rejected', 'borrowed', 'returned', 'late') NOT NULL DEFAULT 'pending',
    fine_amount DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (equipment_id) REFERENCES equipment(id)
);

-- Sample Admin
INSERT INTO users (name, register_number, password, role) VALUES
('Admin User', 'ADMIN001', '$2y$10$examplehashforadmin', 'admin');

-- Sample Students
INSERT INTO users (name, register_number, password, role) VALUES
('Student One', 'STU1001', '$2y$10$examplehashforstudent1', 'student'),
('Student Two', 'STU1002', '$2y$10$examplehashforstudent2', 'student');

-- Sample Equipment
INSERT INTO equipment (name, sport_type, description, availability_status, image_url) VALUES
('Cricket Bat', 'Cricket', 'High quality English willow bat.', 'available', 'assets/images/cricket_bat.jpg'),
('Badminton Racket', 'Badminton', 'Lightweight carbon fiber racket.', 'available', 'assets/images/badminton_racket.jpg'),
('Football', 'Football', 'FIFA approved match ball.', 'available', 'assets/images/football.jpg'),
('Volleyball', 'Volleyball', 'Professional volleyball.', 'available', 'assets/images/volleyball.jpg'),
('Table Tennis Paddle', 'Table Tennis', 'ITTF approved paddle.', 'available', 'assets/images/tt_paddle.jpg'),
('Basketball', 'Basketball', 'Indoor/outdoor basketball.', 'available', 'assets/images/basketball.jpg');

-- Sample Bookings
INSERT INTO bookings (user_id, equipment_id, booking_date, slot_time, return_date, status, fine_amount) VALUES
(2, 1, '2025-08-10 10:00:00', '10:00-11:00', '2025-08-12', 'approved', 0),
(3, 2, '2025-08-11 11:00:00', '11:00-12:00', '2025-08-13', 'pending', 0);
