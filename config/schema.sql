-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 30, 2019 at 07:04 PM
-- Server version: 10.1.37-MariaDB-0+deb9u1
-- PHP Version: 7.2.9-1+b2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `finance`
--

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `country_id` tinyint(3) NOT NULL,
  `country_name` tinytext NOT NULL,
  `country_flag` mediumblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `currency_id` tinyint(3) NOT NULL,
  `iso_code` tinytext NOT NULL,
  `name` tinytext NOT NULL,
  `symbol` char(1) NOT NULL,
  `symbol_minor` char(1) NOT NULL,
  `ranking_id` tinyint(1) NOT NULL,
  `country_id` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `currency_rates`
--

CREATE TABLE `currency_rates` (
  `currency_rate_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `currency_1` tinyint(3) NOT NULL,
  `currency_2` tinyint(3) NOT NULL,
  `rate` decimal(14,10) NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `issue_tracker`
--

CREATE TABLE `issue_tracker` (
  `issue_id` smallint(6) NOT NULL,
  `issue_type` tinyint(1) NOT NULL,
  `file` varchar(128) NOT NULL,
  `function` varchar(32) NOT NULL,
  `params` varchar(64) NOT NULL,
  `message` varchar(64) NOT NULL,
  `issue_status` tinyint(1) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `issue_types`
--

CREATE TABLE `issue_types` (
  `issue_type_id` tinyint(1) NOT NULL,
  `issue_description` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ranking`
--

CREATE TABLE `ranking` (
  `ranking_id` tinyint(1) NOT NULL,
  `ranking_name` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`currency_id`) USING BTREE,
  ADD KEY `currency_country` (`country_id`),
  ADD KEY `currency_ranking` (`ranking_id`);

--
-- Indexes for table `currency_rates`
--
ALTER TABLE `currency_rates`
  ADD PRIMARY KEY (`currency_rate_id`),
  ADD UNIQUE KEY `date` (`date`,`currency_1`,`currency_2`),
  ADD KEY `currency_1` (`currency_1`),
  ADD KEY `currency_2` (`currency_2`);

--
-- Indexes for table `issue_tracker`
--
ALTER TABLE `issue_tracker`
  ADD PRIMARY KEY (`issue_id`),
  ADD KEY `issue_tracker_issue_type_id` (`issue_type`);

--
-- Indexes for table `issue_types`
--
ALTER TABLE `issue_types`
  ADD PRIMARY KEY (`issue_type_id`);

--
-- Indexes for table `ranking`
--
ALTER TABLE `ranking`
  ADD PRIMARY KEY (`ranking_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `country_id` tinyint(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currency_rates`
--
ALTER TABLE `currency_rates`
  MODIFY `currency_rate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issue_tracker`
--
ALTER TABLE `issue_tracker`
  MODIFY `issue_id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issue_types`
--
ALTER TABLE `issue_types`
  MODIFY `issue_type_id` tinyint(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ranking`
--
ALTER TABLE `ranking`
  MODIFY `ranking_id` tinyint(1) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `currencies`
--
ALTER TABLE `currencies`
  ADD CONSTRAINT `currency_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`country_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `currency_ranking` FOREIGN KEY (`ranking_id`) REFERENCES `ranking` (`ranking_id`) ON UPDATE CASCADE;

--
-- Constraints for table `currency_rates`
--
ALTER TABLE `currency_rates`
  ADD CONSTRAINT `currency_1` FOREIGN KEY (`currency_1`) REFERENCES `currencies` (`currency_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `currency_2` FOREIGN KEY (`currency_2`) REFERENCES `currencies` (`currency_id`) ON UPDATE CASCADE;

--
-- Constraints for table `issue_tracker`
--
ALTER TABLE `issue_tracker`
  ADD CONSTRAINT `issue_tracker_issue_type_id` FOREIGN KEY (`issue_type`) REFERENCES `issue_types` (`issue_type_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
