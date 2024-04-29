-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2024 at 01:44 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `newrich`
--

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` int(11) NOT NULL,
  `form_structure` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`form_structure`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `form_structure`, `created_at`) VALUES
(1, '{\"form_name\":\"Form 1\",\"fields\":[{\"type\":\"input\",\"name\":\"username\",\"label\":\"Username\",\"placeholder\":\"Enter your username\",\"required\":true,\"sendWithEmail\":true},{\"type\":\"textarea\",\"name\":\"bio\",\"label\":\"Bio\",\"placeholder\":\"Tell us about yourself\",\"required\":false,\"sendWithEmail\":false},{\"type\":\"select\",\"name\":\"country\",\"label\":\"Country\",\"options\":[{\"value\":\"us\",\"label\":\"United States\"},{\"value\":\"ca\",\"label\":\"Canada\"},{\"value\":\"mx\",\"label\":\"Mexico\"}],\"required\":true,\"sendWithEmail\":true},{\"type\":\"radio\",\"name\":\"gender\",\"label\":\"Gender\",\"options\":[{\"value\":\"male\",\"label\":\"Male\"},{\"value\":\"female\",\"label\":\"Female\"}],\"required\":true,\"sendWithEmail\":true},{\"type\":\"checkbox\",\"name\":\"subscribe\",\"label\":\"Subscribe to newsletter\",\"required\":false,\"sendWithEmail\":false}]}', '2024-04-28 18:00:37'),
(2, '{\"form_name\":\"Form 2\",\"fields\":[{\"type\":\"input\",\"name\":\"name\",\"label\":\"Name\",\"placeholder\":\"Enter your name\",\"required\":true,\"sendWithEmail\":true},{\"type\":\"textarea\",\"name\":\"feedback\",\"label\":\"Feedback\",\"placeholder\":\"Enter your feedback\",\"required\":true,\"sendWithEmail\":true},{\"type\":\"select\",\"name\":\"department\",\"label\":\"Department\",\"options\":[{\"value\":\"hr\",\"label\":\"Human Resources\"},{\"value\":\"eng\",\"label\":\"Engineering\"},{\"value\":\"sales\",\"label\":\"Sales\"}],\"required\":true,\"sendWithEmail\":false},{\"type\":\"radio\",\"name\":\"satisfaction\",\"label\":\"Satisfaction\",\"options\":[{\"value\":\"yes\",\"label\":\"Yes\"},{\"value\":\"no\",\"label\":\"No\"}],\"required\":true,\"sendWithEmail\":false},{\"type\":\"checkbox\",\"name\":\"accept_terms\",\"label\":\"I accept the terms and conditions\",\"required\":true,\"sendWithEmail\":true}]}', '2024-04-28 18:00:49');

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `submission_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`submission_data`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `form_id`, `submission_data`, `created_at`) VALUES
(1, 1, '\"{\\\"username\\\":\\\"Test form 1\\\",\\\"bio\\\":\\\"Testing the form Bio\\\",\\\"country\\\":\\\"us\\\",\\\"gender\\\":\\\"male\\\",\\\"subscribe\\\":\\\"on\\\",\\\"honeypot\\\":\\\"\\\"}\"', '2024-04-29 11:41:40'),
(2, 2, '\"{\\\"name\\\":\\\"Testing Name\\\",\\\"feedback\\\":\\\"Testing feedback\\\",\\\"department\\\":\\\"hr\\\",\\\"satisfaction\\\":\\\"yes\\\",\\\"accept_terms\\\":\\\"on\\\",\\\"honeypot\\\":\\\"\\\"}\"', '2024-04-29 11:43:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
