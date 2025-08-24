-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2025 at 04:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sports_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  `booking_date` text NOT NULL,
  `return_date` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','borrowed','returned','late') NOT NULL DEFAULT 'pending',
  `fine_amount` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `equipment_id`, `booking_date`, `return_date`, `status`, `fine_amount`) VALUES
(29, 9, 15, '12:00 PM – 2:00 PM', 'Monday', 'pending', 0.00),
(31, 9, 15, '8:00 AM – 10:00 AM', 'Monday', 'rejected', 0.00),
(32, 9, 15, '10:00 AM – 12:00 PM', 'Monday', 'pending', 0.00),
(33, 9, 15, '2:00 PM – 4:00 PM', 'Monday', 'pending', 0.00),
(34, 9, 15, '4:00 PM – 6:00 PM', 'Monday', 'pending', 0.00),
(36, 9, 17, '8:00 AM – 10:00 AM', 'Monday', 'approved', 0.00),
(37, 9, 16, '12:00 PM – 2:00 PM', 'Wednesday', 'pending', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sport_type` varchar(50) NOT NULL,
  `availability_status` enum('available','reserved','borrowed') NOT NULL DEFAULT 'available',
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `name`, `sport_type`, `availability_status`, `image_url`) VALUES
(15, 'Badminton', 'Indoor', 'available', '1755175456_badminton.jpg'),
(16, 'Football', 'outdoor', 'available', '1755175481_football1.jpg'),
(17, 'Hockey', 'outdoor', 'borrowed', '1755175505_hockey.jpg'),
(18, 'Table tennis', 'Indoor', 'available', '1755175537_table_tennis.jpg'),
(19, 'Carrom Board', 'Indoor', 'available', '1755175821_carrom.png'),
(22, 'Cricket', 'outdoor', 'available', '1755213105_cricket.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin') NOT NULL,
  `register_number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `register_number`) VALUES
(8, 'likhitha', 'sailikhitha68@gmail.com', '$2y$10$XEbhv1dfckO2hYCKAnERVung5eNsxDtm6i8rvrgH6g8Mdgl69hK.u', 'student', ''),
(9, 'likhitha', '', '$2y$10$0wgu20YdK5TOV92EPNB2NeaOQLwoSzYNNCqTI87Lfp7ltaskzQBpy', 'student', '24B91A12A3'),
(11, 'teja', 'bvst27@gmail.com', '$2y$10$Yy73wvBYu1KDFIbau86yt.yCvlLKEEc6LjjT2POwl8iRPZzPjCesa', 'student', '22b91a6206'),
(12, 'jujj', 'jyiu@gmail.com', '$2y$10$N8JnvcOZgrrs7Tf.JNALduPvVX4lbJesfsCGTWCyysiPL8VzjvL66', 'student', 'jhiuyu'),
(13, 'thanusha', 'namalatanusha@gmail.com', '$2y$10$YGG3lF2XAwo65RPGpUOgkeTIXuNjgUsk7raxmzZwj6dUwHJNB0MYG', 'student', '24b91a0777'),
(15, 'xyz', 'xyz@gmail.com', '$2y$10$fWK4ANxRK.5qubj4hJHuc.Q3KEQfMclI/6aw8OMiUOuo0eiC07iye', 'student', '7z0'),
(161, 'VATTIKUTI SATYA SRI ESWAR', 'satyaeswar01@gmail.com  ', '$2y$10$4EBV6u/9vQDMAPQwRzeLm.eaTTgQGD1HwqoOgL9BqcdL5lXCe2r.6', 'student', '24B91A1211'),
(162, 'VEERAVARAPU SOMU BHAVANI CHAITANYA', 'bhavanichaitanya02@gmail.com  ', '$2y$10$aqWchuRnN2XGi28w/zaNPuEJbQ9VcKSHoVl8LIQ86p8J.cKiMxdB.', 'student', '24B91A1212'),
(163, 'VEERAVILLI ROHITH', 'rohithveera03@gmail.com  ', '$2y$10$ykE2tt1B9sjLa/ckAzX2N.BKuTcK2nKXeBCxezqVbFWlS2BV6xMeG', 'student', '24B91A1213'),
(164, 'VELUGULA SNEHA PRIYANKA', 'snehapriyanka04@gmail.com  ', '$2y$10$8rWJI3yMiwlyA/llS6dX7uXEbheyS09frSfij6RDBilH6VDCsLEa6', 'student', '24B91A1214'),
(165, 'VEMULA VENKATA MEGHANA', 'meghanavenkata05@gmail.com  ', '$2y$10$aWiWAzxcDzdl.VR4zs/SmuZtWFLMw4haXks6oPK6aRTmMKMwPRF3e', 'student', '24B91A1215'),
(166, 'VISHNU SURYA SHER MAHAMMAD PURAM', 'vishnupuram06@gmail.com  ', '$2y$10$j5EXgQAv7aDi6cgTAC9MPukL3CLOyjaTADCkw47z91yUjecPuLNdq', 'student', '24B91A1216'),
(167, 'VOOTA SHYAM KRISHNA', 'shyamkrishna07@gmail.com  ', '$2y$10$ROl1UMCEJG9Zj1FtQwWsAOEGlfHwPCk2F5qAd50D.UNrtLgNeyQc2', 'student', '24B91A1217'),
(168, 'VUTUKURI NAGA SATYA BRAHMANI', 'satyabrahmani08@gmail.com  ', '$2y$10$48QiPo4DTwzH27mQrIsNG.t4e12vM6iu1GEBGC18Dj6W/v7SwdHi6', 'student', '24B91A1218'),
(169, 'VUTUKURI SAI CHAITANYA', 'saichaitanya09@gmail.com  ', '$2y$10$XCd10YQttrSRP0RoUn57JeZ8C21WkbeQTfMtcVhdBCXVu2X2FD0fW', 'student', '24B91A1219'),
(170, 'VUYYURU SANDEEP REDDY', 'sandeepreddy10@gmail.com  ', '$2y$10$NJyRWIgS5y/ZAWahenoRku27daBkge/yW8JoXUxvnL.LZb0NaDR82', 'student', '24B91A1220'),
(171, 'YELIPE VINCY PRIYA', 'vincypriya11@gmail.com  ', '$2y$10$i3eCQc1u8tSG0T4ma8RYt.nkO/j2x9pv40kbhD5BIOwDDRQtZ2bD6', 'student', '24B91A1221'),
(172, 'YEPURI TEJA SANTOSH', 'tejasantosh12@gmail.com  ', '$2y$10$6ywhRJWXc8GRu3cFeQ3/s.1CALW2PSwAgNHuG/QAeds7XnBKQiN.m', 'student', '24B91A1222'),
(173, 'BATCHU VENKATA SAI MEHAR VARSHITA', 'meharvarshita13@gmail.com  ', '$2y$10$ophdDJiZ.9v1dMRuWlrl.OXkN3TSzVuyLwT4NeRYqwFInbXGWKSia', 'student', '24B91A1223'),
(174, 'BELLAPUKONDA YOGESH', 'yogeshbellapukonda14@gmail.com  ', '$2y$10$5VW4BgyXwQImcoPPESoA8uOUWnt5i14XbI4vuN4aHp72dRwy8//.e', 'student', '24B91A1224'),
(175, 'BENGULURU SATYA SIVA TEJA', 'sivateja15@gmail.com  ', '$2y$10$ywkSnpg3lj0fr5Q74ptjfeX94Fm2FeA4o4jRdBO3T.exQ8PPNvTmO', 'student', '24B91A1225'),
(176, 'BOLLOJU ANEESHA', 'aneeshabolloju16@gmail.com  ', '$2y$10$qiyR8y6CAv5HxD1FGQPJMOoTC/YOn9ObM/Vvkj6.4YiwQizlGbcMy', 'student', '24B91A1226'),
(177, 'BORA VIJAYA SAI', 'vijayasai17@gmail.com  ', '$2y$10$B.zqCPtZ9bnNFkG5wDW8xubway4zEr.I23Nr8sLIN10SY/jO6ZBKq', 'student', '24B91A1227'),
(178, 'CHAMANA PAVANI', 'pavanichamana18@gmail.com  ', '$2y$10$BlY7foamsRE0uY.t03fdG.OQLlJgbs70bdsoXF8LoGdf1EVg3hpbS', 'student', '24B91A1228'),
(179, 'CHANDAKANNA VENNELA', 'vennelachandakan19@gmail.com  ', '$2y$10$EEJlm.jkqpcBI8gNcDQVme5xeSskNSncqpnkastHbD4iNuuM/5o1u', 'student', '24B91A1229'),
(180, 'CHANDANA NEERAJ DURGA RAM', 'neerajram20@gmail.com  ', '$2y$10$Sp3IaVJzMG5Hjr6yf0tbc.IqopUGG8O4lZK5I9VIOl2Lo4q2Indc2', 'student', '24B91A1230'),
(181, 'CHEGONDI MUKESH NAG', 'mukeshnag21@gmail.com  ', '$2y$10$eX37GbpTAyJZmXqY1zXyruwYSHUTsihSjKPkMsitiRgs2ay2cSmZS', 'student', '24B91A1231'),
(182, 'CHILLE RENUKA', 'renukachille22@gmail.com  ', '$2y$10$M3QdWJxoC3Z84qUx1n7oTOy.MlN.tXFiaoxLI4RaT09/ivRdCSGCS', 'student', '24B91A1232'),
(183, 'CHIRUMAMILLA DHANALAKSHMI', 'dhanalakshmichiru23@gmail.com  ', '$2y$10$TVtSHqS3ZAihKGwSM4IJ0.2Q0bv3XA4W7vGQtszBxr5ZPbUYjN/6K', 'student', '24B91A1233'),
(184, 'CHITIKENA VENKATA HIMABINDU', 'himabinduvenkata24@gmail.com  ', '$2y$10$UivEhShPoKJhPmpG3PBLruHatNvWWOiY0iLzQJdJlYks8lTRYJOTy', 'student', '24B91A1234'),
(185, 'DARAPUREDDI SIDDHARDHA', 'siddhardhadara25@gmail.com  ', '$2y$10$evv.xkPIZvesUgTrrpRKsOOlujGXbe10vfCk.IQqn/IFKLhLre.D6', 'student', '24B91A1235'),
(186, 'DHARANALA DEVI LAKSHMI PRANATHI', 'pranathilakshmi26@gmail.com  ', '$2y$10$AShT/PCD4gszhDeVBaT6ROivgX84TakHxCaqogCofLnCTTlaIaEy6', 'student', '24B91A1236'),
(187, 'DONTHAMALA SAI DINESH', 'sai.dinesh27@gmail.com  ', '$2y$10$MrH8XPuxfPvmMjWOU9pfiO0E.MmnTLSVighHr4hWsEhWOwl5qWX8O', 'student', '24B91A1237'),
(188, 'GANISETTI S V V D RAMA SUBBARAO', 'subbaraoganisetti28@gmail.com  ', '$2y$10$AsjNs.xW3CjqKDIeE/QZru6L1Fc28XzJrIrU588kU3NWojlhxq23W', 'student', '24B91A1238'),
(189, 'GANTA CHARISHMA', 'charishmaganta29@gmail.com  ', '$2y$10$Z2/fbJt4azSS113CGMa/tu.rvLPPgWeulcsAtwyPeSCc.H4sPivpe', 'student', '24B91A1239');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
