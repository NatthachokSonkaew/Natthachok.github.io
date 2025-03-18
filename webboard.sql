-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2025 at 07:48 PM
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
-- Database: `webboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `created_at`) VALUES
(1, '99', '99', '2025-03-18 18:10:56');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `status` enum('active','hidden','deleted') DEFAULT 'active',
  `create_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `question_id`, `username`, `comment`, `status`, `create_date`, `user_id`) VALUES
(12, 8, '22', 'ผมแนะนำให้ใช้เทคนิค Pomodoro ครับ ทำงานเป็นช่วงๆ 25 นาที แล้วพัก 5 นาที จะช่วยเพิ่มประสิทธิภาพการทำงานได้ดี', 'active', '2025-03-18 18:42:12', 0),
(13, 8, '22', 'ลองใช้แอปพลิเคชันจัดการเวลา เช่น Todoist หรือ Google Calendar ครับ ช่วยให้เห็นภาพรวมของงานและกำหนดเวลาได้ง่ายขึ้น', 'active', '2025-03-18 18:42:21', 0),
(14, 9, '22', 'ผมมักจะทำโอ๊ตมีลใส่ผลไม้และนมครับ ทำง่ายและกินอร่อย แถมยังดีต่อสุขภาพด้วย', 'active', '2025-03-18 18:42:33', 0),
(15, 9, '22', 'ลองทำไข่เจียวใส่ผักหรือน้ำผึ้งก็ได้ครับ ใช้เวลาน้อยและให้พลังงานดี', 'active', '2025-03-18 18:42:49', 0),
(16, 10, '22', 'ลองหลีกเลี่ยงการใช้โทรศัพท์หรืออุปกรณ์อิเล็กทรอนิกส์ก่อนนอนครับ เพราะแสงจากจอทำให้การหลับไม่ลึก', 'active', '2025-03-18 18:43:00', 0),
(17, 10, '22', 'การตั้งเวลานอนให้สม่ำเสมอทุกวันจะช่วยให้ร่างกายเคยชินและหลับได้ลึกขึ้นครับ', 'active', '2025-03-18 18:43:06', 0),
(18, 11, '22', 'ช่วงนี้รู้สึกเครียดมาก อยากได้วิธีลดความเครียดหรือผ่อนคลายจากการทำงานบ้างครับ ใครมีประสบการณ์ดีๆ มาแชร์หน่อยครับ', 'active', '2025-03-18 18:43:21', 0),
(19, 11, '22', 'ลองฝึกการหายใจลึกๆ หรือทำสมาธิ 10 นาทีทุกวันครับ จะช่วยลดความเครียดได้ดี', 'active', '2025-03-18 18:43:29', 0),
(20, 12, '22', 'สำหรับเครื่องดูดฝุ่น ผมแนะนำ Dyson เพราะดูดฝุ่นได้ดีและใช้งานง่ายครับ', 'active', '2025-03-18 18:43:38', 0),
(21, 12, '22', 'ถ้าหม้อหุงข้าว ผมชอบของ Panasonic ครับ ใช้งานทนทานและหุงข้าวได้อร่อย', 'active', '2025-03-18 18:43:49', 0);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reply`
--

CREATE TABLE `reply` (
  `reply_id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `details` text NOT NULL,
  `create_date` datetime DEFAULT current_timestamp(),
  `status` enum('active','hidden','deleted') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `profile_image` varchar(255) DEFAULT 'default.png',
  `created_at` datetime DEFAULT current_timestamp(),
  `role` enum('admin','member') DEFAULT 'member',
  `status` varchar(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `profile_image`, `created_at`, `role`, `status`) VALUES
(10, '99', '$2y$10$KCr7LML7gE0eHPkChsys/OiEZvbvTgpbVRToLXZ1okx.Sz6uOHLjq', 'pattaranit_th66@live.rmutl.ac.th', 'Untitled-4.png', '2025-03-18 18:15:29', 'admin', 'active'),
(12, '22', '$2y$10$c75r1c06yqGDAilIae5kWOV9Dyz8UyCfRXoKsSJ5yPQkgCEXlNC5W', '222222@gmail.com', 'pixeleap_1700218011996.png', '2025-03-18 18:51:29', 'member', 'active'),
(13, 'InXO_L', '$2y$10$yyB0QtnXtJvsqgZaGL/Xm.fa8F6zchTK7ONTxQTjO4w17JcXT2tnu', 'FFFFFF@gmail.com', 'default.png', '2025-03-18 19:47:49', 'member', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `webboard`
--

CREATE TABLE `webboard` (
  `question_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `question` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `create_date` datetime DEFAULT current_timestamp(),
  `view_count` int(11) DEFAULT 0,
  `status` enum('active','hidden','deleted') DEFAULT 'active',
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `webboard`
--

INSERT INTO `webboard` (`question_id`, `user_id`, `question`, `details`, `create_date`, `view_count`, `status`, `username`) VALUES
(8, NULL, 'วิธีจัดการเวลาในการทำงานให้มีประสิทธิภาพ', 'บางครั้งรู้สึกว่ามีเวลาน้อยเกินไปในการทำงานและทำกิจกรรมต่างๆ รบกวนขอคำแนะนำในการจัดการเวลาให้มีประสิทธิภาพหน่อยครับ', '2025-03-19 01:40:16', 0, 'active', '99'),
(9, NULL, 'การเตรียมอาหารเช้าแบบง่ายๆ', 'สำหรับคนที่ไม่มีเวลามากตอนเช้า คุณมีเมนูอาหารเช้าง่ายๆ ที่ทำเร็วและอร่อยบ้างไหมครับ?', '2025-03-19 01:40:37', 0, 'active', '99'),
(10, NULL, 'เคล็ดลับในการนอนหลับให้มีคุณภาพ', 'พยายามนอนให้ตรงเวลาแล้ว แต่ยังรู้สึกเหนื่อยในตอนเช้า มีใครมีเคล็ดลับในการนอนให้หลับลึกและฟื้นฟูร่างกายได้ดีบ้างครับ?', '2025-03-19 01:41:14', 0, 'active', '22'),
(11, NULL, 'วิธีการลดความเครียดในชีวิตประจำวัน', 'ช่วงนี้รู้สึกเครียดมาก อยากได้วิธีลดความเครียดหรือผ่อนคลายจากการทำงานบ้างครับ ใครมีประสบการณ์ดีๆ มาแชร์หน่อยครับ', '2025-03-19 01:41:29', 0, 'active', '22'),
(12, NULL, 'การเลือกซื้อของใช้ในบ้านที่คุ้มค่า', 'ช่วงนี้กำลังหาของใช้ในบ้านใหม่ เช่น เครื่องดูดฝุ่น หรือ หม้อหุงข้าว ใครมีคำแนะนำสินค้าที่คุ้มค่าและใช้งานดีบ้างครับ?', '2025-03-19 01:41:47', 0, 'active', '22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reply`
--
ALTER TABLE `reply`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- Indexes for table `webboard`
--
ALTER TABLE `webboard`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reply`
--
ALTER TABLE `reply`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `webboard`
--
ALTER TABLE `webboard`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `webboard` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `reply`
--
ALTER TABLE `reply`
  ADD CONSTRAINT `reply_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `webboard` (`question_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reply_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `webboard`
--
ALTER TABLE `webboard`
  ADD CONSTRAINT `webboard_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
