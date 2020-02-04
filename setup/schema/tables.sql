SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


DROP TABLE IF EXISTS `currency_rates`;
CREATE TABLE `currency_rates` (
  `currency_rate_id` mediumint(8) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `currency_1` tinyint(3) UNSIGNED NOT NULL,
  `currency_2` tinyint(3) UNSIGNED NOT NULL,
  `rate` decimal(14,10) NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `cryptocurrency_rates`;
CREATE TABLE `cryptocurrency_rates` (
  `crypto_rate_id` mediumint(8) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `crypto_id` tinyint(3) UNSIGNED NOT NULL,
  `currency_id` tinyint(3) UNSIGNED NOT NULL,
  `rate` decimal(12,6) NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `issue_tracker`;
CREATE TABLE `issue_tracker` (
  `issue_id` smallint(6) UNSIGNED NOT NULL,
  `issue_type` tinyint(1) UNSIGNED NOT NULL,
  `file` varchar(128) NOT NULL,
  `function` varchar(32) NOT NULL,
  `params` varchar(256) NOT NULL,
  `message` varchar(64) NOT NULL,
  `issue_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `issue_types`;
CREATE TABLE `issue_types` (
  `issue_type_id` tinyint(1) UNSIGNED NOT NULL,
  `issue_description` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `stock_market_constituents`;
CREATE TABLE `stock_market_constituents` (
  `constituent_id` mediumint(8) UNSIGNED NOT NULL,
  `stock_id` smallint(5) UNSIGNED NOT NULL,
  `market_id` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `stock_market_prices`;
CREATE TABLE `stock_market_prices` (
  `market_price_id` int(11) UNSIGNED NOT NULL,
  `market_id` tinyint(3) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `price` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `stock_prices`;
CREATE TABLE `stock_prices` (
  `stock_id` smallint(5) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `high` decimal(8,2) NOT NULL,
  `low` decimal(8,2) NOT NULL,
  `close` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `currencies`;
CREATE TABLE `currencies` (
  `currency_id` tinyint(3) UNSIGNED NOT NULL,
  `iso_code` tinytext NOT NULL,
  `name` tinytext NOT NULL,
  `symbol` char(1) NOT NULL,
  `symbol_minor` char(1) NOT NULL,
  `ranking_id` tinyint(1) UNSIGNED NOT NULL,
  `country_id` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `cryptocurrencies`;
CREATE TABLE `cryptocurrencies` (
  `crypto_id` tinyint(3) UNSIGNED NOT NULL,
  `symbol` varchar(4) NOT NULL,
  `name` varchar(64) NOT NULL,
  `icon` mediumblob DEFAULT NULL,
  `creation_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `stock_markets`;
CREATE TABLE `stock_markets` (
  `market_id` tinyint(3) UNSIGNED NOT NULL,
  `market_name` text NOT NULL,
  `market_symbol` text NOT NULL,
  `exchange_id` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `stock_exchanges`;
CREATE TABLE `stock_exchanges` (
  `exchange_id` tinyint(3) UNSIGNED NOT NULL,
  `exchange_symbol` varchar(6) NOT NULL,
  `exchange_name` varchar(32) NOT NULL,
  `exchange_suffix` varchar(3) DEFAULT NULL,
  `country_id` tinyint(3) UNSIGNED NOT NULL,
  `timezone` varchar(20) NOT NULL,
  `open` time NOT NULL,
  `close` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `stock_symbol_list`;
CREATE TABLE `stock_symbol_list` (
  `stock_id` smallint(5) UNSIGNED NOT NULL,
  `stock_symbol` varchar(4) NOT NULL,
  `stock_name` varchar(64) NOT NULL,
  `stock_sector` varchar(24) DEFAULT NULL,
  `stock_description` varchar(8192) DEFAULT NULL,
  `stock_currency` varchar(3) NOT NULL,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `country_id` tinyint(3) UNSIGNED NOT NULL,
  `country_name` tinytext NOT NULL,
  `country_flag` mediumblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ranking`;
CREATE TABLE `ranking` (
  `ranking_id` tinyint(1) UNSIGNED NOT NULL,
  `ranking_name` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_id`);

ALTER TABLE `cryptocurrencies`
  ADD PRIMARY KEY (`crypto_id`);

ALTER TABLE `cryptocurrency_rates`
  ADD PRIMARY KEY (`crypto_rate_id`),
  ADD KEY `cryptocurrency_rates_crypto_id` (`crypto_id`),
  ADD KEY `cryptocurrency_rates_currency_id` (`currency_id`);

ALTER TABLE `currencies`
  ADD PRIMARY KEY (`currency_id`) USING BTREE,
  ADD KEY `currency_country` (`country_id`),
  ADD KEY `currency_ranking` (`ranking_id`);

ALTER TABLE `currency_rates`
  ADD PRIMARY KEY (`currency_rate_id`),
  ADD UNIQUE KEY `date` (`date`,`currency_1`,`currency_2`),
  ADD KEY `currency_1` (`currency_1`),
  ADD KEY `currency_2` (`currency_2`);

ALTER TABLE `issue_tracker`
  ADD PRIMARY KEY (`issue_id`),
  ADD KEY `issue_tracker_issue_type_id` (`issue_type`);

ALTER TABLE `issue_types`
  ADD PRIMARY KEY (`issue_type_id`);

ALTER TABLE `ranking`
  ADD PRIMARY KEY (`ranking_id`);

ALTER TABLE `stock_exchanges`
  ADD PRIMARY KEY (`exchange_id`),
  ADD KEY `stock_exchanges_country_id` (`country_id`);

ALTER TABLE `stock_markets`
  ADD PRIMARY KEY (`market_id`),
  ADD KEY `stock_market_exchange` (`exchange_id`);

ALTER TABLE `stock_market_constituents`
  ADD PRIMARY KEY (`constituent_id`),
  ADD UNIQUE KEY `stock_id` (`stock_id`,`market_id`),
  ADD KEY `stock_market_constituents_market_id` (`market_id`);

ALTER TABLE `stock_market_prices`
  ADD PRIMARY KEY (`market_price_id`),
  ADD UNIQUE KEY `market_id-date` (`market_id`,`date`) USING BTREE;

ALTER TABLE `stock_prices`
  ADD PRIMARY KEY (`stock_id`,`date`);

ALTER TABLE `stock_symbol_list`
  ADD PRIMARY KEY (`stock_id`);


ALTER TABLE `countries`
  MODIFY `country_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `cryptocurrencies`
  MODIFY `crypto_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `cryptocurrency_rates`
  MODIFY `crypto_rate_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `currency_rates`
  MODIFY `currency_rate_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `issue_tracker`
  MODIFY `issue_id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `issue_types`
  MODIFY `issue_type_id` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `ranking`
  MODIFY `ranking_id` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `stock_exchanges`
  MODIFY `exchange_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `stock_markets`
  MODIFY `market_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `stock_market_constituents`
  MODIFY `constituent_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `stock_market_prices`
  MODIFY `market_price_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `stock_symbol_list`
  MODIFY `stock_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `currencies`
  ADD CONSTRAINT `currencies_country_id` FOREIGN KEY (`country_id`) REFERENCES `countries` (`country_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `currencies_ranking_id` FOREIGN KEY (`ranking_id`) REFERENCES `ranking` (`ranking_id`) ON UPDATE CASCADE;

ALTER TABLE `currency_rates`
  ADD CONSTRAINT `currency_rates_currency_1` FOREIGN KEY (`currency_1`) REFERENCES `currencies` (`currency_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `currency_rates_currency_2` FOREIGN KEY (`currency_2`) REFERENCES `currencies` (`currency_id`) ON UPDATE CASCADE;

ALTER TABLE `cryptocurrency_rates`
  ADD CONSTRAINT `cryptocurrency_rates_crypto_id` FOREIGN KEY (`crypto_id`) REFERENCES `cryptocurrencies` (`crypto_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cryptocurrency_rates_currency_id` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`currency_id`) ON UPDATE CASCADE;

ALTER TABLE `issue_tracker`
  ADD CONSTRAINT `issue_tracker_issue_type_id` FOREIGN KEY (`issue_type`) REFERENCES `issue_types` (`issue_type_id`) ON UPDATE CASCADE;

ALTER TABLE `stock_exchanges`
  ADD CONSTRAINT `stock_exchanges_country_id` FOREIGN KEY (`country_id`) REFERENCES `countries` (`country_id`) ON UPDATE CASCADE;

ALTER TABLE `stock_markets`
  ADD CONSTRAINT `stock_markets_exchange_id` FOREIGN KEY (`exchange_id`) REFERENCES `stock_exchanges` (`exchange_id`) ON UPDATE CASCADE;

ALTER TABLE `stock_market_constituents`
  ADD CONSTRAINT `stock_market_constituents_market_id` FOREIGN KEY (`market_id`) REFERENCES `stock_markets` (`market_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `stock_market_constituents_stock_id` FOREIGN KEY (`stock_id`) REFERENCES `stock_symbol_list` (`stock_id`) ON UPDATE CASCADE;

ALTER TABLE `stock_market_prices`
  ADD CONSTRAINT `stock_market_prices_market_id` FOREIGN KEY (`market_id`) REFERENCES `stock_markets` (`market_id`) ON UPDATE CASCADE;

ALTER TABLE `stock_prices`
  ADD CONSTRAINT `stock_prices_stock_id` FOREIGN KEY (`stock_id`) REFERENCES `stock_symbol_list` (`stock_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
