-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2025 at 05:30 AM
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
(6, 1, 6, 1, 'rejected', '2025-12-13 19:01:57');

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
(5, 'cannn', 'Cat', '', 'male', '1 year', 'Healthy', '', '2025-12-01', 'not_available', '1765289729_cat.png', 'colombo', 1, 'meet our cannnn................'),
(6, 'blue', 'Bird', '', 'male', '6 months', 'Healthy', '', '2025-12-01', 'available', '1765290187_blue.png', 'colombo', 1, 'blueeeeeeeeeeeeeee'),
(7, 'lily', 'Cat', '', 'female', '5 months', 'Healthy', 'vaccinated', '2025-11-04', 'available', '1765374077_lily.png', 'kandy', 2, 'meet our beautiful lily.');

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
(3, 1, 'Bird', NULL, 'multi', 'Colombo', '2025-12-13', 'yashini', '0123456789', 'found', '1765633782_type.png', '2025-12-13 13:49:42');

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
(1, 'animalcare', 'No 11', 'colombo', '0123456789', 'animal@gmail.com', '$2y$10$OOm8b.ViwqoSYfzbNCaXHOCErt106hDfkfcInT4jGexenghmTPt3q', 'active', NULL, 6.9271, 79.8612),
(2, 'Animal care and love', 'no 56 vidiyatathe mawathe kandy', 'kandy', '0701968862', 'animalcarelove01@gmail.com', '$2y$10$W3JrJXLnVh.oyZtwYRFjFuGGGDIL03B8t3MaYAGgB3tbvDlWMfXIS', 'active', NULL, 7.2906, 80.6337),
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
(1, 'Dog', 'Lat: 6.9836058716599, Lng: 81.057093200665', 'bsdgsdg', '0767211891', '2025-12-20 19:35:10', 'Pending', 3, NULL, NULL, NULL);

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `phone`, `password`, `role`, `created_at`) VALUES
(1, 'N.Yaseni', 'yashininawarathnam@gmail.com', '0767211890', '$2y$10$WVY97C7b/np.4T3ni6X13./RIgWrn7FH04BAuNi8gb4y8bQTfugHW', 'user', '2025-12-05 07:33:34'),
(2, 'animalcare', 'animal@gmail.com', '0123456789', '$2y$10$OOm8b.ViwqoSYfzbNCaXHOCErt106hDfkfcInT4jGexenghmTPt3q', 'rescuecenter', '2025-12-05 13:20:02'),
(3, 'Animal care and love', 'animalcarelove01@gmail.com', '0767211891', '$2y$10$W3JrJXLnVh.oyZtwYRFjFuGGGDIL03B8t3MaYAGgB3tbvDlWMfXIS', 'rescuecenter', '2025-12-10 13:13:40'),
(4, 'Royalcare', 'royalcare@gmail.com', '0124567890', '$2y$10$D.Jb9eZdSkOuTZRUiMnZA.byZEzpGzfzAtvwj.yWS977YcaOpWmgq', 'rescuecenter', '2025-12-20 13:58:49');

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
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `animals_details`
--
ALTER TABLE `animals_details`
  MODIFY `animal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- Constraints for table `lost_animals`
--
ALTER TABLE `lost_animals`
  ADD CONSTRAINT `lost_animals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rescue_requests`
--
ALTER TABLE `rescue_requests`
  ADD CONSTRAINT `fk_rescue_requests_rescue_center` FOREIGN KEY (`rescue_center_id`) REFERENCES `rescue_center` (`rescue_center_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
