-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Dec 02, 2025 at 07:25 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shantubag_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` int(10) NOT NULL,
  `booking_type` enum('Room','Package') NOT NULL,
  `room_type` varchar(50) DEFAULT NULL,
  `package_type` varchar(100) DEFAULT NULL,
  `checkin` date NOT NULL,
  `checkout` date NOT NULL,
  `guests` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_mode` varchar(20) NOT NULL,
  `receipt` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `payment_status` varchar(20) DEFAULT 'Pending',
  `cancel_reason` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Confirmed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `name`, `email`, `phone`, `booking_type`, `room_type`, `package_type`, `checkin`, `checkout`, `guests`, `total_price`, `payment_mode`, `receipt`, `created_at`, `payment_status`, `cancel_reason`, `status`) VALUES
(1, 'Manasi Mahesh  Omable', 'manasiomable6440@gmail.com', 2147483647, 'Room', 'Tent', NULL, '2025-10-10', '2025-10-12', 1, '1500.00', 'UPI', 'uploads/receipt2.jpg', '2025-09-25 22:14:53', 'Paid', NULL, 'Confirmed'),
(2, 'Sujal Shantaram Shinde', 'manasiombale2003@gmail.com', 2147483647, 'Room', 'Cottage', 'Farm Visit', '2025-11-07', '2025-11-08', 1, '2000.00', 'UPI', 'uploads/receipts/1762785825_Screenshot 2025-07-01 101558.png', '2025-11-10 15:43:45', 'Paid', '', 'Confirmed'),
(4, 'Manasi Santosh Shelar', 'manasiombale2003@gmail.com', 2147483647, 'Room', 'Tent', 'Farm Visit', '2025-05-10', '2025-11-13', 1, '1500.00', 'UPI', 'uploads/receipts/1762964551_1759240506_QR.jpeg', '2025-11-12 17:22:31', 'Paid', '', 'Confirmed'),
(5, 'Harshad Sanjay Dhanawade', 'harshaddhanawade93@gmail.com', 2147483647, 'Package', 'Cottage', 'Farm Visit', '2025-11-13', '2025-11-15', 2, '2400.00', 'UPI', 'uploads/receipts/1762968911_1759240506_QR.jpeg', '2025-11-12 18:35:11', 'Paid', '', 'Confirmed'),
(37, 'payal Shantaram Jadhav', 'payal2003@gmail.com', 2147483647, 'Package', 'Cottage', 'Farm Visit', '2025-11-17', '2025-11-19', 4, '4800.00', 'UPI', 'uploads/receipts/1763311427_1759240467_QR.jpeg', '2025-11-16 17:43:47', 'Paid', '', 'Confirmed'),
(39, 'Niraj Anand Padale', 'nirajpadale2003@gmail.com', 2147483647, 'Room', 'Cottage', 'Farm Visit', '2025-11-19', '2025-11-12', 2, '4000.00', 'UPI', 'uploads/receipts/1763481315_1759240467_QR.jpeg', '2025-11-18 16:55:15', 'Paid', '', 'Confirmed'),
(40, 'Hrutik mahesh Omable', 'hrlaptop4141@gmail.com', 2147483647, 'Package', 'Cottage', 'Farm Visit', '2025-11-18', '2025-11-20', 2, '2400.00', 'UPI', 'uploads/receipts/1763487614_1759240467_QR.jpeg', '2025-11-18 18:40:14', 'Paid', '', 'Confirmed'),
(41, 'Shreya Subash Ombale', 'shreyaombale@123gmail.com', 2147483647, 'Package', 'Cottage', 'Farm Visit', '2025-11-21', '2025-11-23', 1, '1200.00', 'Cash', 'uploads/receipts/1763648845_1759240506_QR.jpeg', '2025-11-20 15:27:25', 'Paid', '', 'Confirmed'),
(46, 'Sanjana Sanjay Salunkhe', 'sanjanashinde2025@gmail.com', 2147483647, 'Room', 'Cottage', 'Farm Visit', '2025-11-23', '2025-11-25', 1, '2000.00', 'UPI', 'uploads/receipts/1763912892_1759240506_QR.jpeg', '2025-11-23 16:48:12', 'Paid', '', 'Confirmed'),
(49, 'Manasi Santosh Shelar', 'manasiombale2003@gmail.com', 2147483647, 'Room', 'Cottage', 'Farm Visit', '2025-11-23', '2025-11-24', 6, '12000.00', 'UPI', 'uploads/receipts/1763913373_1759240506_QR.jpeg', '2025-11-23 16:56:13', 'Paid', '', 'Confirmed'),
(51, 'Neha shantosh Havre', 'manasiombale2003@gmail.com', 2147483647, 'Room', 'Cottage', 'Farm Visit', '2025-11-23', '2025-11-25', 2, '4000.00', 'UPI', 'uploads/receipts/1763914260_1759240506_QR.jpeg', '2025-11-23 17:11:00', 'Paid', '', 'Confirmed'),
(61, 'sahil mahesh Ombale', 'sahilombale6440@gmail.com', 2147483647, 'Room', 'Cottage', 'Farm Visit', '2025-11-24', '2025-11-25', 2, '4000.00', 'UPI', 'uploads/receipts/1764003660_1759240506_QR.jpeg', '2025-11-24 18:01:00', 'Paid', '', 'Confirmed'),
(62, 'Anita Mahesh Ombale', 'anitaombale6440@gmail.com', 2147483647, 'Room', 'Cottage', 'Farm Visit', '2025-11-29', '2025-11-30', 2, '4000.00', 'Cash', '', '2025-11-28 20:09:17', 'Unpaid', '', 'Confirmed'),
(63, 'Aakash Ram Patil', 'aakashpatil2025@gmail.com', 2147483647, 'Package', 'Cottage', 'Weekend Stay', '2025-12-02', '2025-12-03', 2, '9000.00', 'UPI', 'uploads/receipts/1764579764_1759240506_QR.jpeg', '2025-12-01 10:02:44', 'Paid', '', 'Confirmed'),
(71, 'snehal prakash Shinde', 'manasiombale2003@gmail.com', 2147483647, 'Room', 'Tent', 'Farm Visit', '2025-12-02', '2025-12-03', 1, '1500.00', 'UPI', 'uploads/receipts/1764602626_1759240506_QR.jpeg', '2025-12-01 16:23:46', 'Paid', '', 'Confirmed'),
(72, 'Anujya Aandrao Jadhav', 'anujaj1324@gmail.com', 2147483647, 'Package', 'Cottage', 'Weekend Stay', '2025-12-03', '2025-12-04', 2, '9000.00', 'Cash', 'uploads/receipts/1764684109_1759240644_QR.jpeg', '2025-12-02 15:01:49', 'Unpaid', '', 'Confirmed'),
(73, 'Anujya Aandrao Jadhav', 'anujaj1324@gmail.com', 2147483647, 'Room', 'Tent', 'Farm Visit', '2025-12-03', '2025-12-04', 2, '3000.00', 'UPI', 'uploads/receipts/1764684186_1759240506_QR.jpeg', '2025-12-02 15:03:06', 'Paid', '', 'Confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `cancellations`
--

CREATE TABLE `cancellations` (
  `cancel_id` int(11) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `cancel_date` datetime DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cancellations`
--

INSERT INTO `cancellations` (`cancel_id`, `user_name`, `booking_id`, `reason`, `cancel_date`, `status`) VALUES
(1, 'Manasi Ombale', 101, 'Change of plans', '2025-11-10 21:33:36', 'Processed'),
(2, 'Aarav Patil', 102, 'Health issue', '2025-11-10 21:33:36', 'Pending'),
(4, 'Guest', 1, 'cancell the plan', '2025-11-18 21:30:37', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `message`, `created_at`) VALUES
(1, 'Shreya Subash Ombale', 'have any travelling facilities', '2025-11-20 21:40:22'),
(3, 'Hrutik mahesh Omable', 'what type of food have there', '2025-11-20 21:44:30'),
(4, 'Aakash Ram Patil', 'what are the food facility', '2025-12-01 14:38:00');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `facility_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`facility_id`, `name`, `description`, `image_path`) VALUES
(1, 'Nature Walk', 'Enjoy peaceful walks surrounded by greenery and fresh air.', 'img/facilities/image1.png'),
(2, 'Organic Farming', 'Experience natural farming methods and learn sustainable practices.', 'img/facilities/image2.jpg'),
(3, 'Play Area', 'Fun and safe space for children to enjoy outdoor activities.', 'img/facilities/image3.jpg'),
(4, 'Boating', 'Relax with a calm boating experience in natural surroundings.', 'img/facilities/image4.png'),
(5, 'Swimming Pool', 'Cool down and refresh with our clean and safe pool facility.', 'img/facilities/image5.jpeg'),
(6, 'Conference Hall', 'Spacious hall for meetings, events, and cultural gatherings.', 'img/facilities/image6.jpeg'),
(7, 'Outdoor Dining', 'Enjoy fresh meals in a natural open-air environment.', 'img/facilities/image7.jpeg'),
(8, 'Bonfire Night', 'Spend evenings with warmth, music, and memorable moments.', 'img/facilities/image8.jpeg'),
(9, 'Deluxe Room', 'Spacious and comfortable stay with all modern amenities.', 'img/facilities/Room1.jpeg'),
(10, 'Family Room', 'Perfect for families to relax and enjoy quality time together.', 'img/facilities/Room2.jpeg'),
(11, 'Couple Suite', 'Private and cozy setting ideal for couples and honeymooners.', 'img/facilities/Room3.jpeg'),
(12, 'AC Room', 'Cool and refreshing rooms for a comfortable summer stay.', 'img/facilities/Room4.jpeg'),
(13, 'Non-AC Room', 'Affordable option with natural ventilation and comfort.', 'img/facilities/Room5.jpeg'),
(14, 'Dormitory', 'Budget-friendly group accommodation with basic facilities.', 'img/facilities/Room6.jpeg'),
(15, 'traditional food', 'traditional Food', ''),
(16, 'traditional food', 'traditional Food', '');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `rating` enum('Excellent','Good','Average','Poor') NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `subject`, `rating`, `message`, `created_at`) VALUES
(1, 'Manasi Mahesh Ombale', 'rahulsharma24@gamil.com', NULL, 'Excellent', 'Amazing experience! Everything was well organized.', '2025-09-29 15:20:29'),
(2, 'Sneha Patil', 'snehapatil2024@gmail.com', NULL, 'Good', 'Good service, but food variety can be improved.', '2025-09-29 15:20:29'),
(3, 'Amit Joshi', 'amitjoshi@gamil.com', NULL, 'Average', 'Stay was fine, but cleanliness needs attention.', '2025-09-29 15:20:29'),
(4, 'Neha sanjay  Havre', 'nehahavre@gmail.com', NULL, 'Excellent', 'Its very good experience', '2025-11-05 02:47:25'),
(5, 'Harshad Sanjay Dhanawade', 'harshaddhanawade93@gmail.com', NULL, 'Good', 'beautiful Fram', '2025-11-12 18:23:07'),
(6, 'Shreya Subash Ombale', 'shreyaombale@123gmail.com', NULL, 'Excellent', 'its very good servies beautiful place ', '2025-11-20 14:31:34'),
(7, 'Anita Mahesh Ombale', 'anitaombale6440@gmail.com', NULL, '', 'best facilties are there', '2025-11-28 19:12:54'),
(8, 'Aakash Ram Patil', 'aakashpatil2025@gmail.com', NULL, '', 'good experience', '2025-12-01 09:07:22'),
(9, 'Anujya Aandrao Jadhav', 'anujaj1324@gmail.com', NULL, '', 'there are best facilties in farme', '2025-12-02 13:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `gallery_id` int(10) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`gallery_id`, `image`, `title`) VALUES
(1, '1759556804_Room4.jpeg', 'tent room'),
(2, '1761754286_Room6.jpeg', 'AC-Room'),
(3, '1761754334_Room1.jpeg', 'cottage');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) DEFAULT 'general',
  `status` varchar(20) DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `type`, `status`, `created_at`) VALUES
(1, 'New feedback received from Neha Havre: \"\"', 'feedback', 'unread', '2025-11-05 07:17:25'),
(2, 'New booking received from Neha Sanjay Havre for Package.', 'booking', 'unread', '2025-11-05 14:57:33'),
(22, '✅ New booking and payment from payal Shantaram Jadhav (Payment ID: PAY000005)', 'booking', 'unread', '2025-11-16 16:43:47'),
(23, '✅ New booking and payment from Niraj Anand Padale (Payment ID: PAY000006)', 'booking', 'unread', '2025-11-18 15:19:25'),
(24, '✅ New booking and payment from Niraj Anand Padale (Payment ID: PAY000007)', 'booking', 'unread', '2025-11-18 15:55:15'),
(25, '✅ New booking and payment from Hrutik mahesh Omable (Payment ID: PAY000008)', 'booking', 'unread', '2025-11-18 17:40:14'),
(26, '✅ New booking and payment from Shreya Subash Ombale (Payment ID: PAY000009)', 'booking', 'unread', '2025-11-20 14:27:25'),
(27, '✅ New booking from Sanjana Sanjay Salunkhe (Payment ID: PAY000010)', 'booking', 'unread', '2025-11-23 15:25:00'),
(28, 'New booking from Sanjana Sanjay Salunkhe (Payment ID: PAY000011)', 'booking', 'unread', '2025-11-23 15:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `description`, `price`) VALUES
(1, 'Farm Visit', 'A guided tour of our organic farm and interactive activities', '1200'),
(2, 'Weekend Stay', 'Relax with a 2-night stay in cozy cottages, enjoy bonfires, cultural programs, and farm-fresh meals. Perfect for family & friends!', '4500'),
(3, 'Adventure Package', 'Thrilling outdoor activities like trekking, bullock cart rides, ropeway swings, and much more for those who seek adventure', '2800'),
(4, 'Custom Package', 'Create your own unique agro-tourism experience by combining farm activities, adventure, and leisure according to your preference.', '5000');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) DEFAULT 0.00,
  `payment_mode` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `receipt` varchar(255) DEFAULT NULL,
  `payment_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `user_name`, `booking_id`, `amount`, `payment_mode`, `status`, `receipt`, `payment_date`) VALUES
(1, 'Manasi Santosh Shelar', 33, '1500.00', 'UPI', 'Paid', 'uploads/receipts/1762964551_1759240506_QR.jpeg', '2025-11-12 21:52:31'),
(4, 'Harshad Sanjay Dhanawade', 36, '2400.00', 'UPI', 'Paid', 'uploads/receipts/1762968911_1759240506_QR.jpeg', '2025-11-12 23:05:11'),
(5, 'payal Shantaram Jadhav', 37, '4800.00', 'UPI', 'Paid', 'uploads/receipts/1763311427_1759240467_QR.jpeg', '2025-11-16 22:13:47'),
(7, 'Niraj Anand Padale', 39, '4000.00', 'UPI', 'Paid', 'uploads/receipts/1763481315_1759240467_QR.jpeg', '2025-11-18 21:25:15'),
(8, 'Hrutik mahesh Omable', 40, '2400.00', 'UPI', 'Paid', 'uploads/receipts/1763487614_1759240467_QR.jpeg', '2025-11-18 23:10:14'),
(9, 'Shreya Subash Ombale', 41, '1200.00', 'UPI', 'Paid', 'uploads/receipts/1763648845_1759240506_QR.jpeg', '2025-11-20 19:57:25'),
(10, 'Sanjana Sanjay Salunkhe', 42, '8000.00', 'UPI', 'Paid', 'uploads/receipts/1763911500_1759240506_QR.jpeg', '2025-11-23 20:55:00'),
(29, 'sahil mahesh Ombale', 61, '4000.00', 'UPI', 'Paid', 'uploads/receipts/1764003660_1759240506_QR.jpeg', '2025-11-24 22:31:00'),
(30, 'Anita Mahesh Ombale', 62, '4000.00', 'Cash', 'Unpaid', '', '2025-11-29 00:39:17'),
(31, 'Aakash Ram Patil', 63, '9000.00', 'UPI', 'Paid', 'uploads/receipts/1764579764_1759240506_QR.jpeg', '2025-12-01 14:32:44'),
(32, 'snehal prakash Shinde', 64, '1200.00', 'UPI', 'Paid', 'uploads/receipts/1764583661_1759240506_QR.jpeg', '2025-12-01 15:37:41'),
(40, 'Anujya Aandrao Jadhav', 72, '9000.00', 'Cash', 'Unpaid', 'uploads/receipts/1764684109_1759240644_QR.jpeg', '2025-12-02 19:31:49'),
(41, 'Anujya Aandrao Jadhav', 73, '3000.00', 'UPI', 'Paid', 'uploads/receipts/1764684186_1759240506_QR.jpeg', '2025-12-02 19:33:06');

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `refund_id` int(11) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_mode` varchar(50) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `request_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `refunds`
--

INSERT INTO `refunds` (`refund_id`, `user_name`, `booking_id`, `payment_id`, `amount`, `payment_mode`, `reason`, `status`, `request_date`) VALUES
(1, 'Niraj Anand Padale', 39, 7, '4000.00', 'UPI', 'health issue', 'Approved', '2025-11-18 00:00:00'),
(2, 'Shreya Subash Ombale', 41, 9, '1200.00', 'Cash', 'change the plan', 'Approved', '2025-11-20 00:00:00'),
(3, 'Sanjana Sanjay Salunkhe', 42, 10, '8000.00', 'UPI', 'cancel the plan', 'Approved', '2025-11-23 00:00:00'),
(4, 'Anita Mahesh Ombale', 62, 30, '4000.00', 'Cash', 'cancel plan', 'Approved', '2025-11-28 00:00:00'),
(5, 'Aakash Ram Patil', 63, 31, '9000.00', 'UPI', 'another plan', 'Approved', '2025-12-01 00:00:00'),
(6, 'snehal prakash Shinde', 64, 32, '1200.00', 'UPI', 'cancel plan', 'Approved', '2025-12-01 00:00:00'),
(7, 'snehal prakash Shinde', 64, 32, '1200.00', 'UPI', 'no', 'Approved', '2025-12-01 00:00:00'),
(8, 'Anujya Aandrao Jadhav', 72, 40, '9000.00', 'Cash', 'cancel the plan', 'Approved', '2025-12-02 00:00:00'),
(9, 'Anujya Aandrao Jadhav', 72, 40, '9000.00', 'Cash', 'another paln', 'Pending', '2025-12-02 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_type` varchar(50) DEFAULT NULL,
  `generated_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_name`, `price`) VALUES
(1, 'Standard Room', '1200.00'),
(2, 'Deluxe Room', '1800.50'),
(3, 'Family Suite', '2500.00'),
(4, 'Luxury Villa', '4500.75'),
(5, 'Dormitory', '800.00'),
(6, 'cottage', '2000.00'),
(7, 'cottage', '2000.00'),
(8, 'cottage', '2000.00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `phone`, `created_at`) VALUES
(1, 'Manasi Mahesh Ombale', 'manasi@example.com', 'user123', '9876543210', '2025-09-25 09:34:43'),
(2, 'Sejal Shantaram Shinde', 'sejal123@gmail.com', 'Sejal@123', '8928458864', '2025-09-25 10:16:39'),
(3, 'Neha Ajit Havre', 'nehahavre@gmail.com', 'neha@123', '8765432686', '2025-11-04 14:08:28'),
(4, 'Manasi Santosh Shelar', 'manasiombale2003@gmail.com', 'Manasi@123', '9860549846', '2025-11-07 15:04:14'),
(5, 'Harshad Sanjay Dhanawade', 'harshaddhanawade93@gmail.com', 'Harshas@123', '9860549846', '2025-11-12 17:33:29'),
(16, 'payal Shantaram Jadhav', 'payal2003@gmail.com', '$2y$10$i2dY88ukNtD4Rtac653nsuExo16i2m.A6wrJShL0l9jCaaI5r8oIC', '9860549846', '2025-11-16 16:38:07'),
(17, 'Niraj Anand Padale', 'nirajpadale2003@gmail.com', '$2y$10$WKD0qEOAFkweRGyc9m.KeOFXMJv6Xo92EdmmfwOsQG1niqqLT.xPe', '8768463421', '2025-11-18 14:36:44'),
(18, 'Shreya Subash Ombale', 'shreyaombale@123gmail.com', '$2y$10$pTfPkxLjEmtgyE5EaTWzuu3v9x/NV8muAbVHvYjbTFHWEeM5Fh12i', '8758362916', '2025-11-20 14:24:27'),
(19, 'Sanjana Sanjay Salunkhe', 'sanjanashinde2025@gmail.com', '$2y$10$jUXuclSaCMjn0S02EERr/O4KaH7dij/c48ePlH266xSd8cZiupzX6', '9876543212', '2025-11-23 15:23:15'),
(20, 'Anita Mahesh Ombale', 'anitaombale6440@gmail.com', '$2y$10$RYL1FkVvJRmqdBT5FAwoa.3tF9hO1cCoNeD6bNLpSS/jXO14ZlvKm', '6578942378', '2025-11-28 18:26:32'),
(21, 'Aakash Ram Patil', 'aakashpatil2025@gmail.com', '$2y$10$QizpOaj.eA0NiATypsB58eNicvAYY.Bw4Vrowx62A5xLju4r1aQG.', '8756704532', '2025-12-01 09:01:34'),
(22, 'snehal prakash Shinde', 'senhashinde2025@gmail.com', '$2y$10$iXTuXKJJiusY5W2P5goQ9eU4AHUUhtIWpXxz5oF70uqC3Tmr6Z6hC', '7654684356', '2025-12-01 10:05:49'),
(23, 'Anujya Aandrao Jadhav', 'anujaj1324@gmail.com', '$2y$10$HQ921qEV7b0/QNeoRxCQZO4uodZ0EIWTZ8xnt4Y6aRCltrKItmXj.', '9860549846', '2025-12-02 13:55:24');

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `visit_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `registered_only` enum('Yes','No') DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`id`, `name`, `email`, `phone`, `visit_date`, `created_at`, `registered_only`) VALUES
(1, 'Manasi Ombale', 'manasiombale6440gmail.com', '9876543210', '2025-09-20', '2025-09-27 16:37:53', 'No'),
(2, 'Rahul Patil', 'rahulpatil2003gmail.com', '9823123456', '2025-09-21', '2025-09-27 16:37:53', 'No'),
(3, 'Sneha Joshi', 'snehajoshi2025gmail.com', '9765432109', '2025-09-22', '2025-09-27 16:37:53', 'No'),
(4, 'Amit Kulkarni', 'amitkulkarnigmail.com', '9856473821', '2025-09-23', '2025-09-27 16:37:53', 'No'),
(5, 'Pooja Deshmukh', 'poojadeshmukhgamil.com', '9890123456', '2025-09-24', '2025-09-27 16:37:53', 'No');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cancellations`
--
ALTER TABLE `cancellations`
  ADD PRIMARY KEY (`cancel_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`facility_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`gallery_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`refund_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `cancellations`
--
ALTER TABLE `cancellations`
  MODIFY `cancel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `facility_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `gallery_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `refund_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
