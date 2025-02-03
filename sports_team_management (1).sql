-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2025 at 10:02 PM
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
-- Database: `sports_team_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `id` int(11) NOT NULL,
  `team_member_id` int(11) NOT NULL,
  `medical_condition` text DEFAULT NULL,
  `medication` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `last_medical_checkup` date DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_staff`
--

CREATE TABLE `medical_staff` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `specialization` enum('doctor','physiotherapist','nutritionist') NOT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` enum('active','on_leave','retired') DEFAULT 'active',
  `joined_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_staff`
--

INSERT INTO `medical_staff` (`id`, `first_name`, `last_name`, `specialization`, `qualification`, `contact_number`, `email`, `status`, `joined_date`) VALUES
(1, 'Dr. Emily', 'Williams', 'doctor', 'Sports Medicine MD', '+1555666777', 'emily.williams@team.com', 'active', '2025-01-29 10:52:28'),
(2, 'Sarah', 'Brown', 'physiotherapist', 'Physical Therapy Degree', '+1444555666', 'sarah.brown@team.com', 'active', '2025-01-29 10:52:28'),
(3, 'Dr. Michael', 'Garcia', 'nutritionist', 'Sports Nutrition PhD', '+1333444555', 'michael.garcia@team.com', 'active', '2025-01-29 10:52:28');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `role` enum('player','coach','technical_director') NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive','injured') DEFAULT 'active',
  `joined_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `first_name`, `last_name`, `role`, `date_of_birth`, `nationality`, `contact_number`, `email`, `status`, `joined_date`) VALUES
(1, 'John', 'Doe', 'player', '1990-05-15', 'USA', '+1234567890', 'john.doe@team.com', 'active', '2025-01-29 10:52:28'),
(2, 'Mike', 'Smith', 'coach', '1975-03-22', 'UK', '+9876543210', 'mike.smith@team.com', 'active', '2025-01-29 10:52:28'),
(3, 'David', 'Johnson', 'technical_director', '1965-11-10', 'Canada', '+1122334455', 'david.johnson@team.com', 'active', '2025-01-29 10:52:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('technical_director','medical_staff','admin') NOT NULL,
  `email` varchar(100) NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `email`, `last_login`) VALUES
(1, 'admin', '$2y$10$3zGIReUUUmt6kxXTZEuFreOzL9Pf0zzaYL0tkxbILvRSKqYD1aA0K', 'admin', 'admin@team.com', NULL),
(2, 'techdirector', '$2y$10$4vL0L9R0DtO1BcNyLzTfreOzL9Pf0zzaYL0tkxbILvRSKqYD3bB1', 'technical_director', 'director@team.com', NULL),
(3, 'medstaff', '$2y$10$5wM1M0S1EuO2CdNzMzTgreeL0K9Pf1zzaYL0tkxbILvRSKqYD4cC2', 'medical_staff', 'medstaff@team.com', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_member_id` (`team_member_id`);

--
-- Indexes for table `medical_staff`
--
ALTER TABLE `medical_staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_medical_staff_specialization` (`specialization`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_member_role` (`role`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_user_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_staff`
--
ALTER TABLE `medical_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `medical_records_ibfk_1` FOREIGN KEY (`team_member_id`) REFERENCES `team_members` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
