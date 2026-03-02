-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2026 at 03:47 PM
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
(4, 1, 16, 1, 'pending', '2026-02-17 12:25:33'),
(7, 1, 15, 2, 'pending', '2026-02-21 17:26:43'),
(8, 8, 17, 1, 'pending', '2026-02-28 10:27:57'),
(9, 8, 11, 2, 'rejected', '2026-02-28 10:28:11');

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
(6, 'Blue', 'Bird', '', 'male', '6 months', 'Healthy', 'vaccinated', '2025-12-01', 'available', '1765290187_blue.png', 'colombo', 1, 'Meet Blue!\r\nBlue is a vibrant 6-month-old male bird with a cheerful personality! He’s healthy, full of energy, and loves interacting with people. With his bright colors and playful nature, Blue is sure to brighten up any home and bring joy to his new family.\r\n\r\nHe’s already vaccinated and enjoys perching, singing, and exploring his surroundings. Blue is looking for a caring owner who can give him love, attention, and a safe, happy environment. Adopt Blue today and add a feathered friend who’s as fun as he is affectionate!'),
(7, 'Lily', 'Cat', '', 'female', '5 months', 'Healthy', 'vaccinated', '2025-11-04', 'available', '1765374077_lily.png', 'kandy', 2, 'Meet Lily 🐾\r\nLily is an adorable 5-month-old female cat full of energy and curiosity! She’s healthy, vaccinated, and loves exploring her surroundings. With her playful spirit and gentle personality, Lily is the perfect companion for families or anyone looking for a loving feline friend.\r\n\r\nLily enjoys cuddles, chasing toys, and discovering cozy spots to nap in. She’s ready to find her forever home where she can grow, play, and bring endless joy to her new family. Adopt Lily today and welcome a bundle of love and purrs into your life!'),
(9, 'Luna', 'Cat', '', 'male', '2 months', 'Healthy', 'vaccinated', '2026-01-04', 'available', '1767591539_luna.png', 'kandy', 2, 'Meet Luna 🐾\r\nLuna is a sweet and gentle cat looking for her forever home. She loves curling up in cozy spots and enjoys quiet cuddle sessions, making her the perfect companion for someone who loves calm and affectionate pets.\r\n\r\nLuna is healthy, vaccinated, and spayed, and she’s great with both adults and older children. She enjoys playtime with toys and watching the world from a sunny windowsill. Luna is curious, loving, and will quickly become a beloved member of your family.\r\n\r\nIf you’re ready to welcome warmth and purrs into your home, Luna is waiting for you!'),
(10, 'Tiger', 'Dog', '', 'male', '9 months', 'Recovering', 'vaccinated', '2025-12-31', 'available', '1767591621_tiger.png', 'kandy', 2, 'Meet Tiger...\r\nTiger is a brave 9-month-old male dog with a heart full of love! He’s currently recovering but steadily getting stronger, and he’s already vaccinated. Tiger is playful, curious, and loves spending time with people who treat him with care and kindness.\r\n\r\nWith a little patience and lots of affection, Tiger will blossom into a loyal and joyful companion. He’s ready to find a forever home where he can feel safe, loved, and part of a family. Give Tiger a chance, and he’ll fill your days with tail wags, love, and happiness!'),
(11, 'Ginger', 'Rabbit', '', 'male', '4 months', 'Healthy', 'vaccinated', '2025-12-28', 'available', '1767591710_kutty.png', 'kandy', 2, 'Meet Ginger!\r\nGinger is an adorable 3-month-old male rabbit who’s full of energy and curiosity! He’s healthy, vaccinated, and loves hopping around, exploring, and nibbling on his favorite treats. With his soft fur and playful personality, Ginger is sure to bring joy to any home.\r\n\r\nHe enjoys gentle cuddles, playtime, and discovering cozy little spots to relax. Ginger is looking for a caring forever home where he can grow happy, healthy, and loved. Adopt Ginger today and welcome a fluffy bundle of fun into your family!'),
(15, 'Simba', 'Dog', '', 'male', '3 months', 'Recovering', 'vaccinated', '2026-02-15', 'available', '1771310001_simba.png', 'kandy', 2, 'Meet Simba \r\n\r\nSimba is an adorable 3-month-old male pup full of curiosity and charm! With his bright eyes and playful spirit, he’s always ready to explore the world around him. Simba is gentle, affectionate, and already showing signs of becoming a loving and loyal companion.\r\n\r\nHe enjoys cuddles, playful moments, and being around people who shower him with care and attention. With proper training, patience, and lots of love, Simba will grow into a confident and happy dog who brings joy to every day.\r\n\r\nSimba is looking for a warm, caring forever home where he can grow, learn, and feel truly loved. Open your heart to Simba, and he’ll reward you with endless love, puppy kisses, and happiness! 🐶💛'),
(16, 'Jully', 'Bird', '', 'male', '5 months', 'Healthy', 'vaccinated', '2026-02-06', 'available', '1771310571_Jully.png', 'colombo', 1, 'Meet Jully \r\n\r\nJully is a charming 5-month-old male parrot with a bright personality and a curious mind! He’s lively, alert, and loves observing everything around him. Jully enjoys gentle interaction and is already learning to feel comfortable around people who treat him with patience and kindness.\r\n\r\nWith time, care, and regular bonding, Jully will grow into a friendly and intelligent companion who brings color and joy to your home. He’s perfect for a family or individual ready to give him attention, a safe environment, and lots of love.\r\n\r\nJully is looking for a forever home where he can spread his wings, feel secure, and be truly cherished. Welcome Jully into your life, and enjoy the happiness and cheerful moments he brings every day! 💚🦜'),
(17, 'Bella', 'Hamsters', '', 'female', '4 months', 'Healthy', 'vaccinated', '2025-12-22', 'available', '1771310683_bella.png', 'colombo', 1, 'Meet Bella, an adorable 4-month-old female hamster with a sweet and curious nature! She loves exploring her little world, scurrying around with energy and excitement. Bella is gentle, alert, and enjoys a calm, caring environment where she feels safe.\r\n\r\nWith patience and gentle handling, Bella will become a delightful little companion. She’s perfect for someone who appreciates small moments of joy and is ready to give her love, care, and attention.\r\n\r\nBella is looking for a cozy forever home where she can feel secure and happy. Bring Bella home, and let her tiny paws add big happiness to your life! 🐹💖'),
(19, 'Snowy', 'Rabbit', '', 'female', '5 months', 'Healthy', 'vaccinated', '2026-03-01', 'available', '1772438659_59.png', 'kandy', 2, 'Meet Snowy 🐰\r\nSnowy is a gentle and friendly rabbit looking for a loving forever home. With her soft white fur and calm nature, she enjoys relaxing in quiet spaces and hopping around curiously to explore her surroundings.\r\n\r\nSnowy is healthy and well cared for, and she’s comfortable around both adults and older children who handle her gently. She loves munching on fresh greens, playing with simple toys, and stretching out for cozy rest time. Snowy has a sweet personality and will bring a sense of peace and joy to any home.\r\n\r\nIf you’re ready to welcome a calm, adorable companion into your life, Snowy is waiting to meet you! 🥕💛'),
(20, 'Milo', 'Dog', '', 'male', '7 months', 'Healthy', 'vaccinated', '2026-02-22', 'available', '1772438750_54.png', 'kandy', 2, 'Meet Milo 🐶\r\nMilo is a playful and loving dog who’s looking for his forever home. With his bright eyes and wagging tail, he brings instant happiness wherever he goes. Milo enjoys going for walks, playing fetch, and spending quality time with the people he loves.\r\n\r\nMilo is healthy, vaccinated, and well-trained with basic commands. He gets along well with adults, children, and other pets, making him a great addition to a family home. He’s affectionate, loyal, and always ready to brighten your day with his cheerful personality.\r\n\r\nIf you’re searching for a friendly companion who will fill your home with joy and unconditional love, Milo is ready to be part of your family! 🐾'),
(21, 'Whiskers', 'Dog', '', 'male', '1 month', 'Healthy', 'vaccinated', '2026-02-25', 'available', '1772438841_55.png', 'colombo', 1, 'Meet Whiskers 🐶\r\nWhiskers is a friendly and gentle dog with a heart full of love. He has a calm yet playful personality and enjoys being around people, whether it’s joining in for playtime or relaxing quietly by your side.\r\n\r\nWhiskers is healthy, vaccinated, and well cared for. He does well with adults and children and is patient and affectionate by nature. He loves short walks, soft toys, and plenty of attention. Whiskers is loyal, intelligent, and eager to form a strong bond with his new family.\r\n\r\nIf you’re looking for a sweet companion who will bring comfort, happiness, and loyalty into your home, Whiskers is ready to meet you! 🐾'),
(22, 'Buddy', 'Dog', '', 'male', '8 months', 'Healthy', 'vaccinated', '2026-02-18', 'available', '1772441495_53.png', 'badulla', 3, 'Meet Buddy 🐶\r\n\r\nBuddy is an energetic and playful 8-month-old pup who is full of curiosity and joy. He loves exploring new places, chasing toys, and learning new tricks. Buddy’s youthful energy makes him a fun companion for active families or anyone who enjoys spending time outdoors.\r\n\r\nBuddy is healthy, vaccinated, and microchipped. He’s social and gets along well with other dogs and friendly children. While he’s playful, he also enjoys quiet cuddle time after a day of fun. Buddy is intelligent, affectionate, and eager to learn, making him the perfect balance of lively and loving.\r\n\r\nIf you’re ready for a spirited, loyal, and loving companion, Buddy is waiting to bring happiness into your home! 🐾💛'),
(23, 'Misty', 'Cat', '', 'female', '2 months', 'Healthy', 'vaccinated', '2026-02-03', 'available', '1772441591_56.png', 'badulla', 3, 'Meet Misty 🐱\r\n\r\nMisty is a curious and affectionate cat who loves exploring every corner of her home. She has a playful side, chasing toys and pouncing with delight, but she also enjoys cozying up in a warm lap for quiet cuddle sessions. Misty’s gentle purr and sweet personality make her an instant friend to anyone she meets.\r\n\r\nMisty is healthy, vaccinated, and spayed. She’s great with adults and older children, and she enjoys gentle interactions and playtime with safe toys. Intelligent and loving, Misty will quickly become a treasured member of your family.\r\n\r\nIf you’re ready to welcome a playful, affectionate, and charming companion into your life, Misty is waiting to meet you! 🐾💖'),
(24, 'Peanut', 'Hamsters', '', 'female', '3 months', 'Recovering', 'vaccinated', '2026-01-20', 'available', '1772441684_60.png', 'badulla', 3, 'Meet Peanut 🐹\r\n\r\nPeanut is an adorable and curious little hamster looking for a cozy forever home. She loves exploring her cage, running on her wheel, and nibbling on tasty treats. Despite her tiny size, Peanut has a big personality and is full of energy and charm.\r\n\r\nPeanut is healthy and well cared for, and she enjoys gentle handling by kids and adults alike. She loves having tunnels, chew toys, and small hideaways to play and rest in. Peanut is curious, playful, and will bring lots of tiny moments of joy and amusement to your home.\r\n\r\nIf you’re ready for a small but delightful companion who’s full of personality, Peanut is waiting for you!'),
(25, 'Nala', 'Cat', '', 'female', '8 months', 'Healthy', 'vaccinated', '2026-03-01', 'available', '1772441750_58.png', 'badulla', 3, 'Meet Nala 🐱\r\n\r\nNala is a sweet and playful cat with a heart full of curiosity. She loves exploring her surroundings, chasing toys, and curling up in warm, cozy spots for quiet naps. Nala has a gentle and affectionate personality, making her the perfect companion for someone who enjoys a loving and interactive pet.\r\n\r\nNala is healthy, vaccinated, and spayed. She’s great with adults and older children and enjoys safe playtime as well as relaxing cuddle sessions. Intelligent and loving, Nala quickly bonds with her humans and will become a cherished member of any family.\r\n\r\nIf you’re ready to welcome a playful, affectionate, and charming companion into your home, Nala is waiting to meet you!'),
(26, 'Oreo', 'Cat', '', 'female', '2 months', 'Healthy', '', '2026-02-22', 'available', '1772441868_57.png', 'colombo', 1, 'Meet Oreo 🐱\r\n\r\nOreo is an adorable and lively cat who loves to play and explore her surroundings. With her striking black-and-white coat, she’s as charming as she is curious. Oreo enjoys chasing toys, climbing, and finding sunny spots to lounge and watch the world go by.\r\n\r\nOreo is healthy, vaccinated, and spayed. She’s friendly with adults and older children and loves gentle attention and playtime. Intelligent and affectionate, Oreo will quickly become a beloved member of your family, bringing joy, warmth, and purrs wherever she goes.\r\n\r\nIf you’re ready for a playful, loving, and charming companion, Oreo is waiting to meet you!'),
(27, 'Saffron', 'Cat', '', 'female', '1 month', 'Healthy', 'vaccinated', '2026-01-31', 'available', '1772441981_61.png', 'colombo', 1, 'Meet Saffron 🐱\r\nSaffron is a curious and elegant cat with a warm, golden personality. She loves exploring cozy corners, chasing feather toys, and basking in the sunlight. Saffron’s playful spirit is perfectly balanced with gentle cuddle sessions, making her a loving companion.\r\n\r\nSaffron is healthy, vaccinated, and spayed. She’s great with adults and older children, enjoys interactive play, and quickly bonds with those who give her love and attention.\r\n\r\nSaffron will brighten your home with her charm and curiosity!'),
(28, 'Rusty', 'Dog', '', 'male', '8 months', 'Healthy', 'vaccinated', '2026-01-28', 'available', '1772442531_70.png', 'kandy', 2, 'Meet Rusty 🐶\r\nRusty is an energetic and friendly 8-month-old pup full of curiosity and joy. He loves running around, playing fetch, and exploring new places. Rusty’s playful personality makes him a perfect companion for an active family or anyone who enjoys outdoor fun.\r\n\r\nRusty is healthy, vaccinated, and microchipped. He’s social, gets along well with other dogs, and is great with older children. Rusty is intelligent, affectionate, and eager to learn, making him a lively yet loving addition to any home.'),
(29, 'Marbles', 'Cat', '', 'female', '7 months', 'Healthy', 'vaccinated', '2025-12-16', 'available', '1772442649_74.png', 'kandy', 2, 'Meet Marbles 🐱\r\nMarbles is a sweet and playful cat with a curious streak. She enjoys exploring every nook and cranny, batting at toys, and snuggling in cozy spots. Marbles has a gentle, affectionate personality that makes her a wonderful companion.\r\n\r\nMarbles is healthy, vaccinated, and spayed. She’s great with older children and adults, loves interactive play, and adores quiet cuddle time.\r\n\r\nMarbles is ready to bring joy, love, and endless purrs to your home!'),
(30, 'Zephyr', 'Cat', '', 'female', '1 month', 'Healthy', 'vaccinated', '2026-03-01', 'available', '1772443228_73.png', 'kandy', 2, 'Meet Zephyr 🐱\r\nZephyr is a lively and adventurous cat with a gentle soul. She loves exploring high shelves, chasing laser pointers, and curling up in soft blankets after a day full of fun. Zephyr’s intelligence and affectionate nature make her a truly special companion.\r\n\r\nZephyr is healthy, vaccinated, and spayed. She’s comfortable around adults and older kids, loves interactive toys, and enjoys quiet bonding moments.\r\n\r\nBring Zephyr home, and you’ll have a playful, loving friend for life! 🐾'),
(31, 'Coco', 'Dog', '', 'female', '8 months', 'Recovering', 'vaccinated', '2026-02-08', 'available', '1772443355_71.png', 'kandy', 2, 'Meet Coco 🐶\r\nCoco is a sweet and gentle dog who loves cuddles and companionship. She enjoys quiet walks, soft toys, and snuggling up next to her humans after a fun day. Coco’s calm and affectionate nature makes her ideal for families or individuals looking for a loving, loyal friend.\r\n\r\nCoco is healthy, vaccinated, and spayed. She’s friendly with adults, children, and other pets. Coco is playful but also enjoys peaceful downtime, making her a balanced and devoted companion.');

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
(5, 1, 'Animal care and love', 2000.00, '0767211890', 'Card', 'Success', 'TRX_6963b7835edbc', '2026-01-11 14:45:23', 'yaseni', 'yashininawarathnam@gmail.com'),
(6, 1, 'Animal care and love', 1000.00, '0767211890', 'Card', 'Success', 'TRX_697e130489c43', '2026-01-31 14:34:44', 'yashini', 'yashininawarathnam@gmail.com'),
(7, 1, 'AnimalCare', 10000.00, '0767211890', 'Card', 'Success', 'TRX_69816c8026a13', '2026-03-01 03:33:20', 'yashini', 'yashininawarathnam@gmail.com'),
(8, 3, 'Animal care and love', 6000.00, '0767211890', 'Card', 'Success', 'TRX_6996e25f6d106', '2026-03-01 10:13:51', 'Nivi', 'yashininawarathnam@gmail.com'),
(9, NULL, 'Animal care and love', 10000.00, '0767211890', 'Card', 'Success', 'TRX_69a19aab29102', '2026-03-02 13:22:51', 'yashna', 'yashininawaratnam@gmail.com');

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
(1, 1, 1, 4, 'The staff were helpful and responsive, but the adoption process happened very fast. Taking a bit more time to understand the adopter’s situation might lead to better matches.', '2026-01-09 15:05:25'),
(3, 1, 2, 5, 'The rescue center does a great job responding quickly, but the adoption process could benefit from a more thorough evaluation period to ensure responsible and successful adoptions.', '2026-01-13 10:21:08'),
(4, 8, 2, 5, 'The process was very fast. Slowing it down slightly could help ensure the animal and adopter are a good long-term match.', '2026-02-19 09:13:00');

-- --------------------------------------------------------

--
-- Table structure for table `lost_animals`
--

CREATE TABLE `lost_animals` (
  `lost_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `animal_type` varchar(50) NOT NULL,
  `breed` varchar(100) DEFAULT NULL,
  `color` varchar(5000) NOT NULL,
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
(4, 1, 'Dog', 'local', 'brown colour ', 'kandy', '2026-02-26', 'yashini', '0701968862', 'notfound', '1771234163_rockey.png', '2026-02-16 09:29:23');

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
(1, 'AnimalCare', 'No 11 watala colombo', 'colombo', '0123456717', 'animal@gmail.com', '$2y$10$OOm8b.ViwqoSYfzbNCaXHOCErt106hDfkfcInT4jGexenghmTPt3q', 'active', 'rescue_1_1768301342.png', 6.9271, 79.8612),
(2, 'Animal care and love', 'no 56 vidiyatathe mawathe kandy', 'kandy', '070196886', 'animalcarelove01@gmail.com', '$2y$10$W3JrJXLnVh.oyZtwYRFjFuGGGDIL03B8t3MaYAGgB3tbvDlWMfXIS', 'active', 'rescue_2_1768300816.png', 7.3053, 80.6359),
(3, 'Royalcare', 'No 10 clinic road badulla', 'badulla', '0124567890', 'royalcare@gmail.com', '$2y$10$D.Jb9eZdSkOuTZRUiMnZA.byZEzpGzfzAtvwj.yWS977YcaOpWmgq', 'active', NULL, 6.9934, 81.055),
(13, 'Safe Haven Animal Rescue', 'No. 120, Kandy Road', 'Kurunegala', '0754812344', 'safehaven.animals@gmail.com', '$2y$10$3kfdKOPVygIto.TdzjjjqOsuR5uDm.54iWKpvATEsq1YjslPywb4i', 'inactive', NULL, 7.4071193, 80.4920439),
(14, 'Loving Paws Rescue Foundation', 'No. 78, Galle Road', 'Kalutara', '0754896312', 'lovingpaws.foundation@gmail.com', '$2y$10$B6aXsMMZ61HDkeHdtdZJE.bpr1uItVXQTaDsftuCNcmV8GaPEOELe', 'inactive', NULL, 6.6637558, 79.9306314);

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
(8, 'Cat', 'Lat: 7.3061183630787, Lng: 80.635965581523', 'some injiury', '0124567890', '2026-01-13 15:38:30', 'Completed', 2, '', '3', NULL),
(9, 'Cat', 'Lat: 7.3061815872883, Lng: 80.636016816978', 'hghv', '0123456789', '2026-01-24 20:22:11', 'In Progress', 2, 'the rescue on progress', '4', NULL),
(10, 'Cat', 'Lat: 7.306148167946, Lng: 80.636005011818', 'injury', '0764589312', '2026-02-19 12:35:18', 'In Progress', 2, '', '5', NULL);

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
(2, 1, 'Jony', 'jony@gmail.com', '0774568923', 'active', '2025-12-26 13:54:54'),
(3, 2, 'nike', 'nike@gmail.com', '123456780', 'active', '2026-01-07 13:25:55'),
(5, 2, 'anna', 'anna123@gamil.com', '123456789', 'active', '2026-02-18 14:52:44');

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
(1, 'Yashini', 'yashininawarathnam@gmail.com', '0767211890', '$2y$10$Rep9lX4vEMNDBMrZp5/yleF5uAr3/2VKVFryYha2xSh2K56LOVEfG', 'user', '2025-12-05 07:33:34', NULL, NULL, NULL),
(2, 'animalcare', 'animal@gmail.com', '0123456789', '$2y$10$OOm8b.ViwqoSYfzbNCaXHOCErt106hDfkfcInT4jGexenghmTPt3q', 'rescuecenter', '2025-12-05 13:20:02', NULL, NULL, NULL),
(3, 'Animal care and love', 'animalcarelove01@gmail.com', '0767211891', '$2y$10$W3JrJXLnVh.oyZtwYRFjFuGGGDIL03B8t3MaYAGgB3tbvDlWMfXIS', 'rescuecenter', '2025-12-10 13:13:40', NULL, NULL, NULL),
(4, 'Royalcare', 'royalcare@gmail.com', '0124567890', '$2y$10$D.Jb9eZdSkOuTZRUiMnZA.byZEzpGzfzAtvwj.yWS977YcaOpWmgq', 'rescuecenter', '2025-12-20 13:58:49', NULL, NULL, NULL),
(5, 'Indunii', 'indu13570@gmail.com', '0702020202', '$2y$10$NKSQ6lQtsK.jHGsoAD3g2eW0VHuVpUpXrySHGZIwmEsxdONuSZwZe', 'user', '2025-12-23 04:15:00', '1766639978_pic 2.jpeg', NULL, NULL),
(6, 'paws&protect', 'Pawsandprotectadmim05@gmail.com', '0767211878', '$2y$10$thwM3pOw8v7gnD4qiUdqV.ge7Z/D5xZMTeBbCRVeudlHuSYddsaQi', 'admin', '2026-01-16 03:41:45', NULL, NULL, NULL),
(8, 'ava', 'ava@gmail.com', '1234567890', '$2y$10$Ht9MIgDjv.8GNEyrshQdb.NyEZL8HXpc2GrcafIm.c.97Ad0DXnRq', 'user', '2026-02-19 09:10:40', NULL, NULL, NULL),
(16, 'Safe Haven Animal Rescue', 'safehaven.animals@gmail.com', '0754812344', '$2y$10$3kfdKOPVygIto.TdzjjjqOsuR5uDm.54iWKpvATEsq1YjslPywb4i', 'rescuecenter', '2026-02-27 13:00:49', NULL, NULL, NULL),
(17, 'Loving Paws Rescue Foundation', 'lovingpaws.foundation@gmail.com', '0754896312', '$2y$10$B6aXsMMZ61HDkeHdtdZJE.bpr1uItVXQTaDsftuCNcmV8GaPEOELe', 'rescuecenter', '2026-02-27 13:02:31', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adopt_requests`
--
ALTER TABLE `adopt_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_animal_id` (`animal_id`),
  ADD KEY `idx_rescue_center_id` (`rescue_center_id`);

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
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `animals_details`
--
ALTER TABLE `animals_details`
  MODIFY `animal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lost_animals`
--
ALTER TABLE `lost_animals`
  MODIFY `lost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rescue_center`
--
ALTER TABLE `rescue_center`
  MODIFY `rescue_center_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `rescue_requests`
--
ALTER TABLE `rescue_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adopt_requests`
--
ALTER TABLE `adopt_requests`
  ADD CONSTRAINT `fk_adopt_animal` FOREIGN KEY (`animal_id`) REFERENCES `animals_details` (`animal_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_adopt_rescue` FOREIGN KEY (`rescue_center_id`) REFERENCES `rescue_center` (`rescue_center_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_adopt_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
