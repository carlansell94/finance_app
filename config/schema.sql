-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 14, 2019 at 06:32 PM
-- Server version: 10.3.15-MariaDB-1
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
  `last_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
  `params` varchar(256) NOT NULL,
  `message` varchar(64) NOT NULL,
  `issue_status` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
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

-- --------------------------------------------------------

--
-- Table structure for table `stock_exchanges`
--

CREATE TABLE `stock_exchanges` (
  `exchange_id` int(2) NOT NULL,
  `exchange_symbol` varchar(6) NOT NULL,
  `exchange_name` varchar(32) NOT NULL,
  `exchange_suffix` varchar(3) DEFAULT NULL,
  `country_id` tinyint(3) NOT NULL,
  `timezone` varchar(20) NOT NULL,
  `open` time NOT NULL,
  `close` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stock_financials`
--

CREATE TABLE `stock_financials` (
  `stock_id` int(3) NOT NULL,
  `financial_currency` varchar(3) DEFAULT NULL,
  `number_of_shares` bigint(12) DEFAULT NULL,
  `last_fiscal_year_end` date DEFAULT NULL,
  `book_value` decimal(6,3) DEFAULT NULL,
  `enterprise_value` bigint(13) DEFAULT NULL,
  `trailing_eps` decimal(7,3) DEFAULT NULL,
  `forward_eps` decimal(7,3) DEFAULT NULL,
  `ebitda` bigint(12) DEFAULT NULL,
  `gross_profit` bigint(12) DEFAULT NULL,
  `ebitda_margin` decimal(6,5) DEFAULT NULL,
  `gross_margin` decimal(10,5) DEFAULT NULL,
  `profit_margin` decimal(10,5) DEFAULT NULL,
  `earnings_quarterly_growth` decimal(6,3) DEFAULT NULL,
  `revenue_growth` decimal(6,3) DEFAULT NULL,
  `earnings_growth` decimal(6,3) DEFAULT NULL,
  `beta` decimal(8,6) DEFAULT NULL,
  `trailing_pe` decimal(10,5) DEFAULT NULL,
  `forward_pe` decimal(10,5) DEFAULT NULL,
  `current_ratio` decimal(6,3) DEFAULT NULL,
  `return_on_assets` decimal(10,8) DEFAULT NULL,
  `total_revenue` bigint(13) DEFAULT NULL,
  `total_cash` int(13) DEFAULT NULL,
  `total_debt` bigint(13) DEFAULT NULL,
  `debt_to_equity` decimal(9,3) DEFAULT NULL,
  `dividend_yield` decimal(6,5) DEFAULT NULL,
  `forward_dividend` decimal(3,2) DEFAULT NULL,
  `last_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stock_markets`
--

CREATE TABLE `stock_markets` (
  `market_id` tinyint(3) UNSIGNED NOT NULL,
  `market_name` text NOT NULL,
  `market_symbol` text NOT NULL,
  `exchange_id` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stock_market_constituents`
--

CREATE TABLE `stock_market_constituents` (
  `constituent_id` mediumint(8) UNSIGNED NOT NULL,
  `stock_id` int(4) NOT NULL,
  `market_id` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stock_market_prices`
--

CREATE TABLE `stock_market_prices` (
  `market_price_id` int(11) UNSIGNED NOT NULL,
  `market_id` int(2) NOT NULL,
  `date` date NOT NULL,
  `price` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stock_prices`
--

CREATE TABLE `stock_prices` (
  `stock_id` int(4) NOT NULL,
  `date` date NOT NULL,
  `high` decimal(8,2) NOT NULL,
  `low` decimal(8,2) NOT NULL,
  `close` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stock_symbol_list`
--

CREATE TABLE `stock_symbol_list` (
  `stock_id` int(4) NOT NULL,
  `stock_symbol` varchar(4) NOT NULL,
  `stock_name` varchar(64) NOT NULL,
  `stock_sector` varchar(24) DEFAULT NULL,
  `stock_description` varchar(8192) DEFAULT NULL,
  `stock_currency` varchar(3) NOT NULL,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
-- Indexes for table `stock_exchanges`
--
ALTER TABLE `stock_exchanges`
  ADD PRIMARY KEY (`exchange_id`),
  ADD KEY `stock_exchanges_country_id` (`country_id`);

--
-- Indexes for table `stock_financials`
--
ALTER TABLE `stock_financials`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `stock_id` (`stock_id`),
  ADD KEY `stock_financials_currency` (`financial_currency`);

--
-- Indexes for table `stock_markets`
--
ALTER TABLE `stock_markets`
  ADD PRIMARY KEY (`market_id`),
  ADD KEY `stock_market_exchange` (`exchange_id`);

--
-- Indexes for table `stock_market_constituents`
--
ALTER TABLE `stock_market_constituents`
  ADD PRIMARY KEY (`constituent_id`),
  ADD UNIQUE KEY `stock_id` (`stock_id`,`market_id`);

--
-- Indexes for table `stock_market_prices`
--
ALTER TABLE `stock_market_prices`
  ADD PRIMARY KEY (`market_price_id`),
  ADD UNIQUE KEY `market_id-date` (`market_id`,`date`) USING BTREE;

--
-- Indexes for table `stock_prices`
--
ALTER TABLE `stock_prices`
  ADD PRIMARY KEY (`stock_id`,`date`);

--
-- Indexes for table `stock_symbol_list`
--
ALTER TABLE `stock_symbol_list`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `market_currency` (`stock_currency`);

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
-- AUTO_INCREMENT for table `stock_exchanges`
--
ALTER TABLE `stock_exchanges`
  MODIFY `exchange_id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_markets`
--
ALTER TABLE `stock_markets`
  MODIFY `market_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_market_constituents`
--
ALTER TABLE `stock_market_constituents`
  MODIFY `constituent_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_market_prices`
--
ALTER TABLE `stock_market_prices`
  MODIFY `market_price_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_symbol_list`
--
ALTER TABLE `stock_symbol_list`
  MODIFY `stock_id` int(4) NOT NULL AUTO_INCREMENT;

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

--
-- Constraints for table `stock_exchanges`
--
ALTER TABLE `stock_exchanges`
  ADD CONSTRAINT `stock_exchanges_country_id` FOREIGN KEY (`country_id`) REFERENCES `countries` (`country_id`);

--
-- Constraints for table `stock_financials`
--
ALTER TABLE `stock_financials`
  ADD CONSTRAINT `stock_financials_stock_id` FOREIGN KEY (`stock_id`) REFERENCES `stock_symbol_list` (`stock_id`) ON UPDATE CASCADE;

--
-- Constraints for table `stock_markets`
--
ALTER TABLE `stock_markets`
  ADD CONSTRAINT `stock_market_exchange` FOREIGN KEY (`exchange_id`) REFERENCES `stock_exchanges` (`exchange_id`) ON UPDATE CASCADE;

--
-- Constraints for table `stock_prices`
--
ALTER TABLE `stock_prices`
  ADD CONSTRAINT `stock_prices_symbol_id` FOREIGN KEY (`stock_id`) REFERENCES `stock_symbol_list` (`stock_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;