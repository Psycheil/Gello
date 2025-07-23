-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2025 at 04:20 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `manfas_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer`
--

CREATE TABLE `fertilizer` (
  `id` int(11) NOT NULL,
  `classification` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fertilizer`
--

INSERT INTO `fertilizer` (`id`, `classification`, `quantity`, `amount`, `date_added`, `date_updated`) VALUES
(2, '14-14-14', 10, 500.00, '2025-05-02 09:24:08', '2025-05-02 09:36:52');

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_sales`
--

CREATE TABLE `fertilizer_sales` (
  `id` int(11) NOT NULL,
  `classification` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `revenue` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fertilizer_sales`
--

INSERT INTO `fertilizer_sales` (`id`, `classification`, `quantity`, `price`, `revenue`) VALUES
(3, '14-14-14', 9, 500.00, 4500.00),
(6, 'Vermi', 20, 300.00, 6000.00);

-- --------------------------------------------------------

--
-- Table structure for table `livestock`
--

CREATE TABLE `livestock` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `livestock_type` varchar(50) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `age` varchar(50) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `livestock`
--

INSERT INTO `livestock` (`id`, `name`, `livestock_type`, `gender`, `age`, `date_added`) VALUES
(4, 'Sello, Jerome D.', 'Carabao', 'Male', '1 year and 3 months', '2025-05-02'),
(5, 'Lariosa, Jino E.', 'Pig', 'Male', '5 months', '2025-05-02'),
(6, 'Eliakim, Elumba G.', 'Cow', 'Female', '4 months', '2025-05-09');

-- --------------------------------------------------------

--
-- Table structure for table `machineries`
--

CREATE TABLE `machineries` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `machine` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `area` varchar(50) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `machineries`
--

INSERT INTO `machineries` (`id`, `name`, `machine`, `amount`, `area`, `date_added`) VALUES
(1, 'Manduloyong, Boy G.', 'Rotavator', 1000.00, '500', '2025-05-02'),
(4, 'Elumba, Eliakim G.', 'Tractor', 1000.00, '100', '2025-05-09');

-- --------------------------------------------------------

--
-- Table structure for table `machinery_services`
--

CREATE TABLE `machinery_services` (
  `id` int(11) NOT NULL,
  `machinery` varchar(255) NOT NULL,
  `area` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `income` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `machinery_services`
--

INSERT INTO `machinery_services` (`id`, `machinery`, `area`, `amount`, `income`) VALUES
(1, 'Tractor', 2, 5000.00, 10000.00),
(3, 'Rotavator', 3, 2000.00, 6000.00);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `product_role` enum('Manage','Produce') DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `group_membership` enum('RIC','FA','4H') DEFAULT NULL,
  `role_type` enum('member','officer') DEFAULT 'member',
  `officer_position` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `name`, `address`, `contact_number`, `product_role`, `gender`, `birthday`, `group_membership`, `role_type`, `officer_position`) VALUES
(17, 'Botay, Nilo Y.', 'Mabinay, Negros Oriental', '+639346784523', NULL, 'Male', '2001-01-01', 'FA', 'member', NULL),
(18, 'Arcanghel, Anna R.', 'Mabinay, Negros Oriental', '+639123456783', NULL, 'Female', '2000-05-08', 'RIC', 'member', NULL),
(21, 'Boboy, Johnny H.', 'Mabinay, Negros Oriental', '+639734174185', NULL, 'Male', '2004-11-17', 'FA', 'member', NULL),
(24, 'Atapang, Digong S.', 'Philippines', '+639929371745', NULL, 'Male', '1950-01-28', 'FA', 'officer', 'President'),
(25, 'Matapang, Leni R.', 'Phillippines', '+639137814674', NULL, 'Female', '1979-02-14', 'RIC', 'officer', 'Vice President'),
(26, 'Malakas, Binay N.', 'Philippines', '+639699378827', NULL, 'Male', '1984-02-07', 'FA', 'officer', 'Secretary'),
(28, 'Montenegro, Jonah R,', 'Mabinay', '+639128736849', NULL, 'Female', '2000-10-17', 'RIC', 'officer', 'Treasurer'),
(33, 'Elumba, Eliakim G.', 'Kabankalan, Negros Occidental', '+639848327584', NULL, 'Male', '2005-02-09', '4H', 'officer', 'Auditor'),
(35, 'Lariosa, Jino E.', 'Mabinay, Negros Oriental', '+639997468465', NULL, 'Male', '2000-07-05', 'FA', 'member', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `membership_payments`
--

CREATE TABLE `membership_payments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `monthly_due` text DEFAULT NULL,
  `membership_fee` decimal(10,2) DEFAULT NULL,
  `cbu` decimal(10,2) DEFAULT NULL,
  `date_added` datetime DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership_payments`
--

INSERT INTO `membership_payments` (`id`, `name`, `monthly_due`, `membership_fee`, `cbu`, `date_added`, `last_updated`) VALUES
(16, 'Arcanghel, Anna R.', '[\"10\",\"10\",\"10\",\"10\",\"10\",\"10\",\"10\",\"10\",0,0,0,0]', 120.00, 500.00, '2025-05-11 22:06:05', '2025-05-11 22:18:44'),
(17, 'Atapang, Digong S.', '[\"14\",\"15\",\"10\",0,0,0,0,0,0,0,0,0]', 120.00, 500.00, '2025-05-11 22:07:06', '2025-05-11 22:07:06'),
(18, 'Boboy, Johnny H.', '[\"12\",\"17\",\"16\",\"14\",0,0,0,0,0,0,0,0]', 120.00, 500.00, '2025-05-11 22:07:27', '2025-05-11 22:07:27'),
(19, 'Botay, Nilo Y.', '[\"15\",\"10\",0,0,0,0,0,0,0,0,0,0]', 120.00, 500.00, '2025-05-11 22:07:57', '2025-05-11 22:12:59'),
(20, 'Elumba, Eliakim G.', '[\"10\",\"10\",\"10\",\"10\",\"10\",0,0,0,0,0,0,0]', 120.00, 500.00, '2025-05-11 22:08:39', '2025-05-11 22:08:39'),
(21, 'Montenegro, Jonah R.', '[\"10\",\"10\",0,0,0,0,0,0,0,0,0,0]', 120.00, 500.00, '2025-05-11 22:09:01', '2025-05-11 22:09:01'),
(22, 'Malakas, Binay N.', '[\"10\",\"10\",\"10\",0,0,0,0,0,0,0,0,0]', 120.00, 500.00, '2025-05-11 22:09:21', '2025-05-11 22:09:21'),
(23, 'Matapang, Leni R.', '[\"15\",\"19\",\"15\",0,0,0,0,0,0,0,0,0]', 120.00, 500.00, '2025-05-11 22:09:43', '2025-05-11 22:09:43'),
(24, 'Lariosa, Jino E.', '[\"15\",\"10\",0,0,0,0,0,0,0,0,0,0]', 120.00, 500.00, '2025-05-11 22:17:02', '2025-05-11 22:17:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$jJYpRyQzGRhU.p5vW67Q6O.dP7dkSBj4bp5eadxAsWZEKkiA0C3yS'),
(2, 'maria123', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFx4sZ6b2Ad9enVvWcLgC6Q/6E2lX7m6'),
(3, 'pedro456', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFx4sZ6b2Ad9enVvWcLgC6Q/6E2lX7m6'),
(4, 'carlos789', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFx4sZ6b2Ad9enVvWcLgC6Q/6E2lX7m6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fertilizer`
--
ALTER TABLE `fertilizer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fertilizer_sales`
--
ALTER TABLE `fertilizer_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `livestock`
--
ALTER TABLE `livestock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `machineries`
--
ALTER TABLE `machineries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `machinery_services`
--
ALTER TABLE `machinery_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `membership_payments`
--
ALTER TABLE `membership_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fertilizer`
--
ALTER TABLE `fertilizer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fertilizer_sales`
--
ALTER TABLE `fertilizer_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `livestock`
--
ALTER TABLE `livestock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `machineries`
--
ALTER TABLE `machineries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `machinery_services`
--
ALTER TABLE `machinery_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `membership_payments`
--
ALTER TABLE `membership_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
