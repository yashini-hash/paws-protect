-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2026 at 09:18 AM
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
-- Database: `paws`
--

-- --------------------------------------------------------

--
-- Table structure for table `adopt_requests`
--

CREATE TABLE `adopt_requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL,
  `rescue_center_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `request_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adopt_requests`
--

INSERT INTO `adopt_requests` (`request_id`, `user_id`, `animal_id`, `rescue_center_id`, `status`, `request_date`) VALUES
(4, 1, 5, 1, 'approved', '2025-12-10 14:49:05'),
(6, 1, 6, 1, 'rejected', '2025-12-13 19:01:57'),
(8, 1, 9, 2, 'pending', '2026-01-13 15:46:53');

-- --------------------------------------------------------

--
-- Table structure for table `animals_details`
--

CREATE TABLE `animals_details` (
  `animal_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('Dog','Cat','Bird','Rabbit','Hamsters') NOT NULL,
  `breed` varchar(100) DEFAULT NULL,
  `gender` enum('male','female') NOT NULL,
  `age` varchar(50) NOT NULL,
  `health` enum('Healthy','Injured','Recovering') DEFAULT NULL,
  `vaccination` varchar(100) DEFAULT NULL,
  `rescue_date` date NOT NULL,
  `adoption_status` enum('available','adopted','not_available') DEFAULT 'available',
  `animal_image` varchar(255) DEFAULT NULL,
  `location` varchar(150) NOT NULL,
  `rescue_center_id` int(11) NOT NULL,
  `details` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `animals_details`
--

INSERT INTO `animals_details` (`animal_id`, `name`, `type`, `breed`, `gender`, `age`, `health`, `vaccination`, `rescue_date`, `adoption_status`, `animal_image`, `location`, `rescue_center_id`, `details`) VALUES
(5, 'cannn', 'Cat', '', 'male', '1 year', 'Healthy', '', '2025-12-01', 'not_available', '1765289729_cat.png', 'colombo', 1, 'Meet Cannn 🐾\r\nCannn is a 1-year-old male cat full of curiosity and charm! He’s healthy, active, and ready to find a loving forever home. Cannn loves to explore, play, and enjoy quality time with his humans. Whether it’s chasing toys or relaxing in a sunny spot, he’s sure to bring joy and purrs into your life.\r\n\r\nGive Cannn the chance he deserves, and he’ll reward you with endless love and companionship!'),
(6, 'blue', 'Bird', '', 'male', '6 months', 'Healthy', 'vaccinated', '2025-12-01', 'available', '1765290187_blue.png', 'colombo', 1, 'Meet Blue!\r\nBlue is a vibrant 6-month-old male bird with a cheerful personality! He’s healthy, full of energy, and loves interacting with people. With his bright colors and playful nature, Blue is sure to brighten up any home and bring joy to his new family.\r\n\r\nHe’s already vaccinated and enjoys perching, singing, and exploring his surroundings. Blue is looking for a caring owner who can give him love, attention, and a safe, happy environment. Adopt Blue today and add a feathered friend who’s as fun as he is affectionate!'),
(7, 'lily', 'Cat', '', 'female', '5 months', 'Healthy', 'vaccinated', '2025-11-04', 'available', '1765374077_lily.png', 'kandy', 2, 'Meet Lily 🐾\r\nLily is an adorable 5-month-old female cat full of energy and curiosity! She’s healthy, vaccinated, and loves exploring her surroundings. With her playful spirit and gentle personality, Lily is the perfect companion for families or anyone looking for a loving feline friend.\r\n\r\nLily enjoys cuddles, chasing toys, and discovering cozy spots to nap in. She’s ready to find her forever home where she can grow, play, and bring endless joy to her new family. Adopt Lily today and welcome a bundle of love and purrs into your life!'),
(9, 'luna', 'Cat', '', 'male', '2 months', 'Healthy', 'vaccinated', '2026-01-04', 'available', '1767591539_luna.png', 'kandy', 2, 'Meet Luna 🐾\r\nLuna is a sweet and gentle cat looking for her forever home. She loves curling up in cozy spots and enjoys quiet cuddle sessions, making her the perfect companion for someone who loves calm and affectionate pets.\r\n\r\nLuna is healthy, vaccinated, and spayed, and she’s great with both adults and older children. She enjoys playtime with toys and watching the world from a sunny windowsill. Luna is curious, loving, and will quickly become a beloved member of your family.\r\n\r\nIf you’re ready to welcome warmth and purrs into your home, Luna is waiting for you!'),
(10, 'tiger', 'Dog', '', 'male', '9 months', 'Recovering', 'vaccinated', '2025-12-31', 'available', '1767591621_tiger.png', 'kandy', 2, 'Meet Tiger...\r\nTiger is a brave 9-month-old male dog with a heart full of love! He’s currently recovering but steadily getting stronger, and he’s already vaccinated. Tiger is playful, curious, and loves spending time with people who treat him with care and kindness.\r\n\r\nWith a little patience and lots of affection, Tiger will blossom into a loyal and joyful companion. He’s ready to find a forever home where he can feel safe, loved, and part of a family. Give Tiger a chance, and he’ll fill your days with tail wags, love, and happiness!'),
(11, 'ginger', 'Rabbit', '', 'male', '3 months', 'Healthy', 'vaccinated', '2025-12-28', 'available', '1767591710_kutty.png', 'kandy', 2, 'Meet Ginger!\r\nGinger is an adorable 3-month-old male rabbit who’s full of energy and curiosity! He’s healthy, vaccinated, and loves hopping around, exploring, and nibbling on his favorite treats. With his soft fur and playful personality, Ginger is sure to bring joy to any home.\r\n\r\nHe enjoys gentle cuddles, playtime, and discovering cozy little spots to relax. Ginger is looking for a caring forever home where he can grow happy, healthy, and loved. Adopt Ginger today and welcome a fluffy bundle of fun into your family!');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `donation_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rescue_center` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'Card',
  `payment_status` enum('Pending','Success','Failed') DEFAULT 'Pending',
  `transaction_ref` varchar(100) DEFAULT NULL,
  `donated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `donor_name` varchar(100) DEFAULT NULL,
  `donor_email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`donation_id`, `user_id`, `rescue_center`, `amount`, `phone`, `payment_method`, `payment_status`, `transaction_ref`, `donated_at`, `donor_name`, `donor_email`) VALUES
(2, 1, 'Animal care and love', 5000.00, '0767211890', 'Card', 'Success', NULL, '2026-01-11 14:21:56', 'yaseni', 'yashininawartahnam@gmail.com'),
(3, 1, 'Royalcare', 1000.00, '0767211890', 'Card', 'Success', NULL, '2026-01-11 14:27:13', 'yaseni', 'yashininawartahnam@gmail.com'),
(4, 1, 'Animal care and love', 2000.00, '0767211890', 'Card', 'Success', 'TRX_6963b612a82de', '2026-01-11 14:39:14', 'yaseni', 'yashininawarathnam@gmail.com'),
(5, 1, 'Animal care and love', 2000.00, '0767211890', 'Card', 'Success', 'TRX_6963b7835edbc', '2026-01-11 14:45:23', 'yaseni', 'yashininawarathnam@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rescue_center_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `user_id`, `rescue_center_id`, `rating`, `message`, `created_at`) VALUES
(1, 1, 1, 4, 'good in animal care', '2026-01-09 15:05:25'),
(3, 1, 2, 5, 'good ', '2026-01-13 10:21:08');

-- --------------------------------------------------------

--
-- Table structure for table `lost_animals`
--

CREATE TABLE `lost_animals` (
  `lost_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `animal_type` varchar(50) NOT NULL,
  `breed` varchar(100) DEFAULT NULL,
  `color` varchar(50) NOT NULL,
  `lost_location` varchar(255) NOT NULL,
  `lost_date` date NOT NULL,
  `owner_name` varchar(150) NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `status` enum('found','notfound') NOT NULL DEFAULT 'notfound',
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lost_animals`
--

INSERT INTO `lost_animals` (`lost_id`, `user_id`, `animal_type`, `breed`, `color`, `lost_location`, `lost_date`, `owner_name`, `contact_number`, `status`, `image`, `created_at`) VALUES
(3, 1, 'Bird', NULL, 'multi', 'Colombo', '2025-12-13', 'yashini', '0123456789', 'notfound', '1765633782_type.png', '2025-12-13 13:49:42');

-- --------------------------------------------------------

--
-- Table structure for table `rescue_center`
--

CREATE TABLE `rescue_center` (
  `rescue_center_id` int(11) NOT NULL,
  `center_name` varchar(150) NOT NULL,
  `address` text NOT NULL,
  `district` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive','rejected') DEFAULT 'inactive',
  `logo` varchar(225) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rescue_center`
--

INSERT INTO `rescue_center` (`rescue_center_id`, `center_name`, `address`, `district`, `contact_number`, `email`, `password`, `status`, `logo`, `latitude`, `longitude`) VALUES
(1, 'animalcare', 'No 11 watala colombo', 'colombo', '0123456711', 'animal@gmail.com', '$2y$10$OOm8b.ViwqoSYfzbNCaXHOCErt106hDfkfcInT4jGexenghmTPt3q', 'active', 'rescue_1_1768301342.png', 6.9271, 79.8612),
(2, 'Animal care and love', 'no 56 vidiyatathe mawathe kandy', 'kandy', '0701968862', 'animalcarelove01@gmail.com', '$2y$10$W3JrJXLnVh.oyZtwYRFjFuGGGDIL03B8t3MaYAGgB3tbvDlWMfXIS', 'active', 'rescue_2_1768300816.png', 7.3053, 80.6359),
(3, 'Royalcare', 'No 10 clinic road badulla', 'badulla', '0124567890', 'royalcare@gmail.com', '$2y$10$D.Jb9eZdSkOuTZRUiMnZA.byZEzpGzfzAtvwj.yWS977YcaOpWmgq', 'active', NULL, 6.9934, 81.055);

-- --------------------------------------------------------

--
-- Table structure for table `rescue_requests`
--

CREATE TABLE `rescue_requests` (
  `request_id` int(11) NOT NULL,
  `animal_type` varchar(50) NOT NULL,
  `rescue_location` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `request_date` datetime DEFAULT current_timestamp(),
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  `rescue_center_id` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `assigned_to` varchar(100) DEFAULT NULL,
  `completed_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rescue_requests`
--

INSERT INTO `rescue_requests` (`request_id`, `animal_type`, `rescue_location`, `description`, `contact_number`, `request_date`, `status`, `rescue_center_id`, `notes`, `assigned_to`, `completed_date`) VALUES
(1, 'Dog', 'Lat: 6.9836058716599, Lng: 81.057093200665', 'with abnormal condition', '0767211891', '2025-12-20 19:35:10', 'Completed', 3, '', '1', NULL),
(8, 'Cat', 'Lat: 7.3061183630787, Lng: 80.635965581523', 'some injiury', '0124567890', '2026-01-13 15:38:30', 'In Progress', 2, '', '3', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `rescue_center_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `rescue_center_id`, `name`, `email`, `phone`, `status`, `created_at`) VALUES
(1, 3, 'dan', 'dan@gmail.com', '125278', 'active', '2025-12-24 07:41:04'),
(2, 1, 'thilak', 'thilak@gmail.com', '07745689231', 'active', '2025-12-26 13:54:54'),
(3, 2, 'nike', 'nike@gmail.com', '123456780', 'active', '2026-01-07 13:25:55'),
(4, 2, 'K Y john', 'johnky@gmail.com', '4578932', 'active', '2026-01-07 13:26:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','rescuecenter','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_image` varchar(200) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `phone`, `password`, `role`, `created_at`, `profile_image`, `reset_token`, `reset_expires`) VALUES
(1, 'N.Yaseni', 'yashininawarathnam@gmail.com', '0767211890', '$2y$10$V.ku/remD1dDpO.SZL8.XeFhyxejqM7uXfRYm8kRkKbMjmyD2qRd.', 'user', '2025-12-05 07:33:34', NULL, NULL, NULL),
(2, 'animalcare', 'animal@gmail.com', '0123456789', '$2y$10$OOm8b.ViwqoSYfzbNCaXHOCErt106hDfkfcInT4jGexenghmTPt3q', 'rescuecenter', '2025-12-05 13:20:02', NULL, NULL, NULL),
(3, 'Animal care and love', 'animalcarelove01@gmail.com', '0767211891', '$2y$10$W3JrJXLnVh.oyZtwYRFjFuGGGDIL03B8t3MaYAGgB3tbvDlWMfXIS', 'rescuecenter', '2025-12-10 13:13:40', NULL, NULL, NULL),
(4, 'Royalcare', 'royalcare@gmail.com', '0124567890', '$2y$10$D.Jb9eZdSkOuTZRUiMnZA.byZEzpGzfzAtvwj.yWS977YcaOpWmgq', 'rescuecenter', '2025-12-20 13:58:49', NULL, NULL, NULL),
(5, 'Indunii', 'indu13570@gmail.com', '0702020202', '$2y$10$NKSQ6lQtsK.jHGsoAD3g2eW0VHuVpUpXrySHGZIwmEsxdONuSZwZe', 'user', '2025-12-23 04:15:00', '1766639978_pic 2.jpeg', NULL, NULL),
(6, 'paws&protect', 'Pawsandprotectadmim05@gmail.com', '0767211878', '$2y$10$thwM3pOw8v7gnD4qiUdqV.ge7Z/D5xZMTeBbCRVeudlHuSYddsaQi', 'admin', '2026-01-16 03:41:45', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adopt_requests`
--
ALTER TABLE `adopt_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `animal_id` (`animal_id`),
  ADD KEY `rescue_center_id` (`rescue_center_id`);

--
-- Indexes for table `animals_details`
--
ALTER TABLE `animals_details`
  ADD PRIMARY KEY (`animal_id`),
  ADD KEY `fk_rescue_center` (`rescue_center_id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`donation_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `fk_feedback_user` (`user_id`),
  ADD KEY `fk_feedback_rescue_center` (`rescue_center_id`);

--
-- Indexes for table `lost_animals`
--
ALTER TABLE `lost_animals`
  ADD PRIMARY KEY (`lost_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rescue_center`
--
ALTER TABLE `rescue_center`
  ADD PRIMARY KEY (`rescue_center_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `rescue_requests`
--
ALTER TABLE `rescue_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `fk_rescue_requests_rescue_center` (`rescue_center_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `rescue_center_id` (`rescue_center_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adopt_requests`
--
ALTER TABLE `adopt_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `animals_details`
--
ALTER TABLE `animals_details`
  MODIFY `animal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lost_animals`
--
ALTER TABLE `lost_animals`
  MODIFY `lost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rescue_center`
--
ALTER TABLE `rescue_center`
  MODIFY `rescue_center_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rescue_requests`
--
ALTER TABLE `rescue_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adopt_requests`
--
ALTER TABLE `adopt_requests`
  ADD CONSTRAINT `adopt_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `adopt_requests_ibfk_2` FOREIGN KEY (`animal_id`) REFERENCES `animals_details` (`animal_id`),
  ADD CONSTRAINT `adopt_requests_ibfk_3` FOREIGN KEY (`rescue_center_id`) REFERENCES `rescue_center` (`rescue_center_id`);

--
-- Constraints for table `animals_details`
--
ALTER TABLE `animals_details`
  ADD CONSTRAINT `fk_rescue_center` FOREIGN KEY (`rescue_center_id`) REFERENCES `rescue_center` (`rescue_center_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_feedback_rescue_center` FOREIGN KEY (`rescue_center_id`) REFERENCES `rescue_center` (`rescue_center_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_feedback_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `lost_animals`
--
ALTER TABLE `lost_animals`
  ADD CONSTRAINT `lost_animals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rescue_requests`
--
ALTER TABLE `rescue_requests`
  ADD CONSTRAINT `fk_rescue_requests_rescue_center` FOREIGN KEY (`rescue_center_id`) REFERENCES `rescue_center` (`rescue_center_id`) ON DELETE CASCADE;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`rescue_center_id`) REFERENCES `rescue_center` (`rescue_center_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
