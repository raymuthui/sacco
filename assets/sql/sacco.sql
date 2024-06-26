-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 19, 2024 at 01:30 PM
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
-- Database: `sacco`
--

-- --------------------------------------------------------

--
-- Table structure for table `borrowers`
--

CREATE TABLE `borrowers` (
  `id` int(30) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `contact_no` varchar(30) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `tax_id` varchar(50) NOT NULL,
  `member_id` int(30) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_list`
--

CREATE TABLE `loan_list` (
  `id` int(30) NOT NULL,
  `member_id` int(30) DEFAULT NULL,
  `ref_no` varchar(50) NOT NULL,
  `loan_type_id` int(30) NOT NULL,
  `purpose` text NOT NULL,
  `amount` double NOT NULL,
  `penalty_accrued` float DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0= request, 1= confrimed,2=released,3=complteted,4=denied\r\n',
  `date_released` datetime NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_list`
--

INSERT INTO `loan_list` (`id`, `member_id`, `ref_no`, `loan_type_id`, `purpose`, `amount`, `penalty_accrued`, `status`, `date_released`, `date_created`) VALUES
(3, 13, '123', 2, 'Build a house', 200000, NULL, 2, '2024-04-19 11:33:21', '2024-04-19 04:33:58'),
(4, 13, '472', 1, 'Start a business', 4300000, NULL, 2, '2024-04-19 12:16:08', '2024-04-19 05:16:46');

-- --------------------------------------------------------

--
-- Table structure for table `loan_schedules`
--

CREATE TABLE `loan_schedules` (
  `id` int(30) NOT NULL,
  `loan_id` int(30) NOT NULL,
  `date_due` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_schedules`
--

INSERT INTO `loan_schedules` (`id`, `loan_id`, `date_due`) VALUES
(1, 1, '2022-06-27'),
(2, 1, '2022-07-27'),
(3, 1, '2022-08-27'),
(4, 1, '2022-09-27'),
(5, 1, '2022-10-27'),
(6, 1, '2022-11-27'),
(7, 1, '2022-12-27'),
(8, 1, '2023-01-27'),
(9, 1, '2023-02-27'),
(10, 1, '2023-03-27'),
(11, 1, '2023-04-27'),
(12, 1, '2023-05-27'),
(13, 1, '2023-06-27'),
(14, 1, '2023-07-27'),
(15, 1, '2023-08-27'),
(16, 1, '2023-09-27'),
(17, 1, '2023-10-27'),
(18, 1, '2023-11-27'),
(19, 1, '2023-12-27'),
(20, 1, '2024-01-27'),
(21, 1, '2024-02-27'),
(22, 1, '2024-03-27'),
(23, 1, '2024-04-27'),
(24, 1, '2024-05-27'),
(25, 1, '2024-06-27'),
(26, 1, '2024-07-27'),
(27, 1, '2024-08-27'),
(28, 1, '2024-09-27'),
(29, 1, '2024-10-27'),
(30, 1, '2024-11-27'),
(31, 1, '2024-12-27'),
(32, 1, '2025-01-27'),
(33, 1, '2025-02-27'),
(34, 1, '2025-03-27'),
(35, 1, '2025-04-27'),
(36, 1, '2025-05-27'),
(37, 2, '2022-06-27'),
(38, 2, '2022-07-27'),
(39, 2, '2022-08-27'),
(40, 2, '2022-09-27'),
(41, 2, '2022-10-27'),
(42, 2, '2022-11-27'),
(43, 2, '2022-12-27'),
(44, 2, '2023-01-27'),
(45, 2, '2023-02-27'),
(46, 2, '2023-03-27'),
(47, 2, '2023-04-27'),
(48, 2, '2023-05-27'),
(49, 2, '2023-06-27'),
(50, 2, '2023-07-27'),
(51, 2, '2023-08-27'),
(52, 2, '2023-09-27'),
(53, 2, '2023-10-27'),
(54, 2, '2023-11-27'),
(55, 2, '2023-12-27'),
(56, 2, '2024-01-27'),
(57, 2, '2024-02-27'),
(58, 2, '2024-03-27'),
(59, 2, '2024-04-27'),
(60, 2, '2024-05-27'),
(61, 2, '2024-06-27'),
(62, 2, '2024-07-27'),
(63, 2, '2024-08-27'),
(64, 2, '2024-09-27'),
(65, 2, '2024-10-27'),
(66, 2, '2024-11-27'),
(67, 2, '2024-12-27'),
(68, 2, '2025-01-27'),
(69, 2, '2025-02-27'),
(70, 2, '2025-03-27'),
(71, 2, '2025-04-27'),
(72, 2, '2025-05-27'),
(73, 2, '2025-06-27'),
(74, 2, '2025-07-27'),
(75, 2, '2025-08-27'),
(76, 2, '2025-09-27'),
(77, 2, '2025-10-27'),
(78, 2, '2025-11-27'),
(79, 2, '2025-12-27'),
(80, 2, '2026-01-27'),
(81, 2, '2026-02-27'),
(82, 2, '2026-03-27'),
(83, 2, '2026-04-27'),
(84, 2, '2026-05-27'),
(85, 2, '2026-06-27'),
(86, 2, '2026-07-27'),
(87, 2, '2026-08-27'),
(88, 2, '2026-09-27'),
(89, 2, '2026-10-27'),
(90, 2, '2026-11-27'),
(91, 2, '2026-12-27'),
(92, 2, '2027-01-27'),
(93, 2, '2027-02-27'),
(94, 2, '2027-03-27'),
(95, 2, '2027-04-27'),
(96, 2, '2027-05-27'),
(97, 2, '2027-06-27'),
(98, 2, '2027-07-27'),
(99, 2, '2027-08-27'),
(100, 2, '2027-09-27'),
(101, 2, '2027-10-27'),
(102, 2, '2027-11-27'),
(103, 2, '2027-12-27'),
(104, 2, '2028-01-27'),
(105, 2, '2028-02-27'),
(106, 2, '2028-03-27'),
(107, 2, '2028-04-27'),
(108, 2, '2028-05-27');

-- --------------------------------------------------------

--
-- Table structure for table `loan_types`
--

CREATE TABLE `loan_types` (
  `id` int(30) NOT NULL,
  `type_name` text NOT NULL,
  `description` text NOT NULL,
  `min_amount` float NOT NULL,
  `max_amount` float NOT NULL,
  `months` int(11) NOT NULL,
  `interest_percentage` float NOT NULL,
  `penalty_rate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_types`
--

INSERT INTO `loan_types` (`id`, `type_name`, `description`, `min_amount`, `max_amount`, `months`, `interest_percentage`, `penalty_rate`) VALUES
(1, 'Small Business', 'Small Business Loans', 10000, 1000000, 36, 8, 3),
(2, 'Mortgages', 'Mortgages', 50000, 5000000, 24, 5, 2),
(3, 'Personal Loans', 'Personal Loans', 2000, 200000, 27, 6, 2),
(6, 'Silver Loan', 'This is a silver loan', 150000, 10500000, 12, 10, 5);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(30) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `contact_no` varchar(30) NOT NULL,
  `address` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(250) DEFAULT NULL,
  `tax_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=pending, 1=approved',
  `id_front_path` varchar(255) NOT NULL,
  `id_back_path` varchar(255) NOT NULL,
  `profile_pic_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `firstname`, `middlename`, `lastname`, `contact_no`, `address`, `email`, `password`, `tax_id`, `status`, `id_front_path`, `id_back_path`, `profile_pic_path`) VALUES
(13, 'Lennox', 'Kimathi', 'Kabo', '0731313123', 'Embakasi, Imara Daima', 'lennox@gmail.com', '12345', '56hy', 1, 'uploads/Mpesa..png', 'uploads/spiral-logo.png', 'uploads/spiral-logo.png');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `article_image_path` varchar(250) NOT NULL,
  `article_title` text NOT NULL,
  `article_content` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `article_image_path`, `article_title`, `article_content`, `date_created`) VALUES
(3, 'uploads/_0a3a2967-ae5b-4647-9d84-ab54d7c503b2.jpeg', 'Unoma', 'The Don', '2024-04-19 10:58:38'),
(25, 'uploads/sa1.jpeg', 'Welcome', 'Welcome to our marvelous sacco! It is an honor to have you in our pyramid scheme. Be ready to lose all your money to us! Cheers:)', '2024-04-19 11:25:29');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(30) NOT NULL,
  `loan_id` int(30) NOT NULL,
  `payee` text NOT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `penalty_amount` float NOT NULL DEFAULT 0,
  `overdue` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=no , 1 = yes',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` varchar(200) NOT NULL,
  `contact` text NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1=admin , 2 = staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `contact`, `username`, `password`, `type`) VALUES
(1, 'Administrator', '0700000000', 'borelennox@gmail.com', 'admin', 1),
(2, 'Lennox Kabo', '0731313123', 'lexx', '12345', 1),
(3, 'Joan', '0712345678', 'jojo', '123456', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `borrowers`
--
ALTER TABLE `borrowers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_member_id` (`member_id`);

--
-- Indexes for table `loan_list`
--
ALTER TABLE `loan_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_loan_member_id` (`member_id`),
  ADD KEY `fk_loan_type_id` (`loan_type_id`);

--
-- Indexes for table `loan_schedules`
--
ALTER TABLE `loan_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_types`
--
ALTER TABLE `loan_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_loan_id` (`loan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `borrowers`
--
ALTER TABLE `borrowers`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `loan_list`
--
ALTER TABLE `loan_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `loan_schedules`
--
ALTER TABLE `loan_schedules`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `loan_types`
--
ALTER TABLE `loan_types`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowers`
--
ALTER TABLE `borrowers`
  ADD CONSTRAINT `fk_member_id` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `loan_list`
--
ALTER TABLE `loan_list`
  ADD CONSTRAINT `fk_loan_member_id` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_loan_type_id` FOREIGN KEY (`loan_type_id`) REFERENCES `loan_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_loan_id` FOREIGN KEY (`loan_id`) REFERENCES `loan_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
