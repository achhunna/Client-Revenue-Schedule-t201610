-- phpMyAdmin SQL Dump
-- version 4.0.10.15
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 07, 2016 at 09:55 PM
-- Server version: 5.5.46
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tally_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `acctg_change_log`
--

CREATE TABLE IF NOT EXISTS `acctg_change_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `mc_user_id` int(11) NOT NULL,
  `source` varchar(255) NOT NULL,
  `table_change` varchar(255) NOT NULL,
  `reference_id` int(11) NOT NULL,
  `log_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `change_type` varchar(255) NOT NULL,
  `field_change` varchar(255) NOT NULL,
  `old_value` text NOT NULL,
  `new_value` text NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=97 ;

--
-- Dumping data for table `acctg_change_log`
--

INSERT INTO `acctg_change_log` (`log_id`, `mc_user_id`, `source`, `table_change`, `reference_id`, `log_date`, `change_type`, `field_change`, `old_value`, `new_value`) VALUES
(1, 1, 'csv', 'acctg_invoice_clients', 1, '2016-11-28 02:30:42', 'add', 'client_id', '', '123'),
(2, 1, 'csv', 'acctg_invoice_clients', 1, '2016-11-28 02:30:42', 'add', 'meta_key', '', 'name,sf_link,email'),
(3, 1, 'csv', 'acctg_invoice_clients', 1, '2016-11-28 02:30:42', 'add', 'meta_value', '', 'Turner,https://salesforce.com/turner,contact@turner.com'),
(4, 1, 'csv', 'acctg_invoice_clients', 2, '2016-11-28 02:30:42', 'add', 'client_id', '', '234'),
(5, 1, 'csv', 'acctg_invoice_clients', 2, '2016-11-28 02:30:42', 'add', 'meta_key', '', 'name,sf_link,email'),
(6, 1, 'csv', 'acctg_invoice_clients', 2, '2016-11-28 02:30:42', 'add', 'meta_value', '', 'Nytimes,https://salesforce.com/nytimes,contact@nytimes.com'),
(7, 1, 'csv', 'acctg_invoice_clients', 3, '2016-11-28 02:30:42', 'add', 'client_id', '', '325'),
(8, 1, 'csv', 'acctg_invoice_clients', 3, '2016-11-28 02:30:42', 'add', 'meta_key', '', 'name,sf_link,email'),
(9, 1, 'csv', 'acctg_invoice_clients', 3, '2016-11-28 02:30:42', 'add', 'meta_value', '', 'Microsoft,https://salesforce.com/microsoft,contact@microsoft.com'),
(10, 1, 'csv', 'acctg_invoice_clients', 4, '2016-11-28 02:30:42', 'add', 'client_id', '', '456'),
(11, 1, 'csv', 'acctg_invoice_clients', 4, '2016-11-28 02:30:42', 'add', 'meta_key', '', 'name,sf_link,email'),
(12, 1, 'csv', 'acctg_invoice_clients', 4, '2016-11-28 02:30:42', 'add', 'meta_value', '', 'Wired,https://salesforce.com/wired,contact@wired.com'),
(13, 1, 'csv', 'acctg_invoice_clients', 5, '2016-11-28 02:30:42', 'add', 'client_id', '', '567'),
(14, 1, 'csv', 'acctg_invoice_clients', 5, '2016-11-28 02:30:42', 'add', 'meta_key', '', 'name,sf_link,email'),
(15, 1, 'csv', 'acctg_invoice_clients', 5, '2016-11-28 02:30:42', 'add', 'meta_value', '', 'Wirecutter,https://salesforce.com/wirecutter,contact@wirecutter.com'),
(16, 1, 'csv', 'acctg_invoice_clients', 6, '2016-11-28 02:30:42', 'add', 'client_id', '', '100'),
(17, 1, 'csv', 'acctg_invoice_clients', 6, '2016-11-28 02:30:42', 'add', 'meta_key', '', 'name,sf_link,email'),
(18, 1, 'csv', 'acctg_invoice_clients', 6, '2016-11-28 02:30:42', 'add', 'meta_value', '', 'Washington Post,https://salesforce.com/wp,contact@wp.com'),
(19, 1, 'csv', 'acctg_invoice_clients', 7, '2016-11-28 02:30:42', 'add', 'client_id', '', '101'),
(20, 1, 'csv', 'acctg_invoice_clients', 7, '2016-11-28 02:30:42', 'add', 'meta_key', '', 'name,sf_link,email'),
(21, 1, 'csv', 'acctg_invoice_clients', 7, '2016-11-28 02:30:42', 'add', 'meta_value', '', 'Engadget,https://salesforce.com/engadget,contact@engadget.com'),
(22, 1, 'csv', 'acctg_invoice_clients_key_dates', 1, '2016-11-28 02:30:49', 'add', 'client_id', '', '123'),
(23, 1, 'csv', 'acctg_invoice_clients_key_dates', 1, '2016-11-28 02:30:49', 'add', 'date_deal_done', '', '2016-03-01'),
(24, 1, 'csv', 'acctg_invoice_clients_key_dates', 1, '2016-11-28 02:30:49', 'add', 'date_effective', '', '2016-04-01'),
(25, 1, 'csv', 'acctg_invoice_clients_key_dates', 1, '2016-11-28 02:30:49', 'add', 'date_term', '', '2017-04-01'),
(26, 1, 'csv', 'acctg_invoice_clients_key_dates', 2, '2016-11-28 02:30:49', 'add', 'client_id', '', '234'),
(27, 1, 'csv', 'acctg_invoice_clients_key_dates', 2, '2016-11-28 02:30:49', 'add', 'date_deal_done', '', '2016-03-01'),
(28, 1, 'csv', 'acctg_invoice_clients_key_dates', 2, '2016-11-28 02:30:49', 'add', 'date_effective', '', '2016-04-01'),
(29, 1, 'csv', 'acctg_invoice_clients_key_dates', 2, '2016-11-28 02:30:49', 'add', 'date_term', '', '2017-03-01'),
(30, 1, 'csv', 'acctg_invoice_clients_key_dates', 3, '2016-11-28 02:30:49', 'add', 'client_id', '', '325'),
(31, 1, 'csv', 'acctg_invoice_clients_key_dates', 3, '2016-11-28 02:30:49', 'add', 'date_deal_done', '', '2016-03-01'),
(32, 1, 'csv', 'acctg_invoice_clients_key_dates', 3, '2016-11-28 02:30:49', 'add', 'date_effective', '', '2016-04-01'),
(33, 1, 'csv', 'acctg_invoice_clients_key_dates', 3, '2016-11-28 02:30:49', 'add', 'date_term', '', '2017-03-01'),
(34, 1, 'csv', 'acctg_invoice_client_schedules', 1, '2016-11-28 02:30:54', 'add', 'client_id', '', '123'),
(35, 1, 'csv', 'acctg_invoice_client_schedules', 1, '2016-11-28 02:30:54', 'add', 'date_only', '', '2016-01-01'),
(36, 1, 'csv', 'acctg_invoice_client_schedules', 1, '2016-11-28 02:30:54', 'add', 'transaction_note', '', 'First estimate'),
(37, 1, 'csv', 'acctg_invoice_client_schedules', 1, '2016-11-28 02:30:54', 'add', 'transaction_product_variation', '', ''),
(38, 1, 'csv', 'acctg_invoice_client_schedules', 1, '2016-11-28 02:30:54', 'add', 'transaction_type', '', 'estimate'),
(39, 1, 'csv', 'acctg_invoice_client_schedules', 1, '2016-11-28 02:30:54', 'add', 'transaction_value', '', '10000'),
(40, 1, 'csv', 'acctg_invoice_client_schedules', 2, '2016-11-28 02:30:54', 'add', 'client_id', '', '123'),
(41, 1, 'csv', 'acctg_invoice_client_schedules', 2, '2016-11-28 02:30:54', 'add', 'date_only', '', '2016-05-01'),
(42, 1, 'csv', 'acctg_invoice_client_schedules', 2, '2016-11-28 02:30:54', 'add', 'transaction_note', '', 'April payment'),
(43, 1, 'csv', 'acctg_invoice_client_schedules', 2, '2016-11-28 02:30:54', 'add', 'transaction_product_variation', '', ''),
(44, 1, 'csv', 'acctg_invoice_client_schedules', 2, '2016-11-28 02:30:54', 'add', 'transaction_type', '', 'payment'),
(45, 1, 'csv', 'acctg_invoice_client_schedules', 2, '2016-11-28 02:30:54', 'add', 'transaction_value', '', '5000'),
(46, 1, 'csv', 'acctg_invoice_client_schedules', 3, '2016-11-28 02:30:54', 'add', 'client_id', '', '123'),
(47, 1, 'csv', 'acctg_invoice_client_schedules', 3, '2016-11-28 02:30:54', 'add', 'date_only', '', '2016-06-01'),
(48, 1, 'csv', 'acctg_invoice_client_schedules', 3, '2016-11-28 02:30:54', 'add', 'transaction_note', '', 'Q1 invoice'),
(49, 1, 'csv', 'acctg_invoice_client_schedules', 3, '2016-11-28 02:30:54', 'add', 'transaction_product_variation', '', ''),
(50, 1, 'csv', 'acctg_invoice_client_schedules', 3, '2016-11-28 02:30:54', 'add', 'transaction_type', '', 'bill'),
(51, 1, 'csv', 'acctg_invoice_client_schedules', 3, '2016-11-28 02:30:54', 'add', 'transaction_value', '', '20000'),
(52, 1, 'csv', 'acctg_invoice_client_schedules', 4, '2016-11-28 02:30:54', 'add', 'client_id', '', '123'),
(53, 1, 'csv', 'acctg_invoice_client_schedules', 4, '2016-11-28 02:30:54', 'add', 'date_only', '', '2016-02-01'),
(54, 1, 'csv', 'acctg_invoice_client_schedules', 4, '2016-11-28 02:30:54', 'add', 'transaction_note', '', 'First sales order'),
(55, 1, 'csv', 'acctg_invoice_client_schedules', 4, '2016-11-28 02:30:54', 'add', 'transaction_product_variation', '', ''),
(56, 1, 'csv', 'acctg_invoice_client_schedules', 4, '2016-11-28 02:30:54', 'add', 'transaction_type', '', 'sales order'),
(57, 1, 'csv', 'acctg_invoice_client_schedules', 4, '2016-11-28 02:30:54', 'add', 'transaction_value', '', '10000'),
(58, 1, 'csv', 'acctg_invoice_client_schedules', 5, '2016-11-28 02:30:54', 'add', 'client_id', '', '325'),
(59, 1, 'csv', 'acctg_invoice_client_schedules', 5, '2016-11-28 02:30:54', 'add', 'date_only', '', '2016-01-01'),
(60, 1, 'csv', 'acctg_invoice_client_schedules', 5, '2016-11-28 02:30:54', 'add', 'transaction_note', '', 'First estimate'),
(61, 1, 'csv', 'acctg_invoice_client_schedules', 5, '2016-11-28 02:30:54', 'add', 'transaction_product_variation', '', ''),
(62, 1, 'csv', 'acctg_invoice_client_schedules', 5, '2016-11-28 02:30:54', 'add', 'transaction_type', '', 'estimate'),
(63, 1, 'csv', 'acctg_invoice_client_schedules', 5, '2016-11-28 02:30:54', 'add', 'transaction_value', '', '50000'),
(64, 1, 'csv', 'acctg_invoice_client_schedules', 6, '2016-11-28 02:30:54', 'add', 'client_id', '', '325'),
(65, 1, 'csv', 'acctg_invoice_client_schedules', 6, '2016-11-28 02:30:54', 'add', 'date_only', '', '2016-05-01'),
(66, 1, 'csv', 'acctg_invoice_client_schedules', 6, '2016-11-28 02:30:54', 'add', 'transaction_note', '', 'April payment'),
(67, 1, 'csv', 'acctg_invoice_client_schedules', 6, '2016-11-28 02:30:54', 'add', 'transaction_product_variation', '', ''),
(68, 1, 'csv', 'acctg_invoice_client_schedules', 6, '2016-11-28 02:30:54', 'add', 'transaction_type', '', 'payment'),
(69, 1, 'csv', 'acctg_invoice_client_schedules', 6, '2016-11-28 02:30:54', 'add', 'transaction_value', '', '2000'),
(70, 1, 'csv', 'acctg_invoice_client_schedules', 7, '2016-11-28 02:30:54', 'add', 'client_id', '', '325'),
(71, 1, 'csv', 'acctg_invoice_client_schedules', 7, '2016-11-28 02:30:54', 'add', 'date_only', '', '2016-06-01'),
(72, 1, 'csv', 'acctg_invoice_client_schedules', 7, '2016-11-28 02:30:54', 'add', 'transaction_note', '', 'Q1 invoice'),
(73, 1, 'csv', 'acctg_invoice_client_schedules', 7, '2016-11-28 02:30:54', 'add', 'transaction_product_variation', '', ''),
(74, 1, 'csv', 'acctg_invoice_client_schedules', 7, '2016-11-28 02:30:54', 'add', 'transaction_type', '', 'bill'),
(75, 1, 'csv', 'acctg_invoice_client_schedules', 7, '2016-11-28 02:30:54', 'add', 'transaction_value', '', '10000'),
(76, 1, 'csv', 'acctg_invoice_client_schedules', 8, '2016-11-28 02:30:54', 'add', 'client_id', '', '325'),
(77, 1, 'csv', 'acctg_invoice_client_schedules', 8, '2016-11-28 02:30:54', 'add', 'date_only', '', '2016-02-01'),
(78, 1, 'csv', 'acctg_invoice_client_schedules', 8, '2016-11-28 02:30:54', 'add', 'transaction_note', '', 'First sales order'),
(79, 1, 'csv', 'acctg_invoice_client_schedules', 8, '2016-11-28 02:30:54', 'add', 'transaction_product_variation', '', ''),
(80, 1, 'csv', 'acctg_invoice_client_schedules', 8, '2016-11-28 02:30:54', 'add', 'transaction_type', '', 'sales order'),
(81, 1, 'csv', 'acctg_invoice_client_schedules', 8, '2016-11-28 02:30:54', 'add', 'transaction_value', '', '50000'),
(82, 1, 'app', 'acctg_invoice_client_schedules', 7, '2016-11-28 02:31:11', 'update', 'date_only', 'Q1 invoice', 'Q2 invoice'),
(83, 1, 'app', 'acctg_invoice_client_schedules', 3, '2016-11-28 02:31:22', 'update', 'date_only', 'Q1 invoice', 'Q3 invoice'),
(84, 1, 'app', 'acctg_invoice_client_schedules', 3, '2016-11-28 02:31:22', 'update', 'transaction_type', '20000.00', '25000.00'),
(85, 1, 'app', 'acctg_invoice_client_schedules', 5, '2016-11-28 15:31:15', 'update', 'transaction_type', '50000.00', '55000.00'),
(86, 1, 'app', 'acctg_invoice_client_schedules', 1, '2016-11-28 19:56:16', 'update', 'transaction_type', '10000.00', '11000.00'),
(87, 1, 'app', 'acctg_invoice_client_schedules', 1, '2016-11-28 20:21:32', 'update', 'transaction_type', '11000.00', '10000.00'),
(88, 1, 'app', 'acctg_invoice_client_schedules', 3, '2016-11-28 21:57:32', 'update', 'transaction_type', '25000.00', '20000.00'),
(89, 1, 'app', 'acctg_invoice_client_schedules', 3, '2016-11-28 21:57:42', 'update', 'date_only', 'Q3 invoice', 'Q2 invoice'),
(90, 1, 'app', 'acctg_invoice_client_schedules', 3, '2016-11-28 21:57:42', 'update', 'transaction_product_variation', 'bill', 'billing'),
(91, 1, 'app', 'acctg_invoice_client_schedules', 3, '2016-11-28 21:57:49', 'update', 'transaction_product_variation', 'billing', 'bill'),
(92, 1, 'app', 'acctg_invoice_client_schedules', 5, '2016-11-28 23:35:08', 'update', 'transaction_type', '55000.00', '50000.00'),
(93, 1, 'app', 'acctg_invoice_client_schedules', 1, '2016-11-29 17:37:14', 'update', 'transaction_type', '10000.00', '11000.00'),
(94, 1, 'app', 'acctg_invoice_client_schedules', 1, '2016-11-29 19:12:21', 'update', 'transaction_type', '11000.00', '10000.00'),
(95, 1, 'app', 'acctg_invoice_client_schedules', 1, '2016-11-30 00:28:15', 'update', 'transaction_type', '10000.00', '15000.00'),
(96, 1, 'app', 'acctg_invoice_client_schedules', 1, '2016-12-07 17:07:41', 'update', 'transaction_type', '15000.00', '14000.00');

-- --------------------------------------------------------

--
-- Table structure for table `acctg_invoice_clients`
--

CREATE TABLE IF NOT EXISTS `acctg_invoice_clients` (
  `acctg_invoice_clients_meta_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL COMMENT 'matches accounting system client record ID',
  `meta_key` varchar(255) NOT NULL COMMENT 'name, service_slug, sf_link, dio_link, term, launch_date, sad_day_date, total_deal_value, document_link',
  `meta_value` varchar(255) NOT NULL,
  PRIMARY KEY (`acctg_invoice_clients_meta_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `acctg_invoice_clients`
--

INSERT INTO `acctg_invoice_clients` (`acctg_invoice_clients_meta_id`, `client_id`, `meta_key`, `meta_value`) VALUES
(1, 123, 'name,sf_link,email', 'Turner,https://salesforce.com/turner,contact@turner.com'),
(2, 234, 'name,sf_link,email', 'Nytimes,https://salesforce.com/nytimes,contact@nytimes.com'),
(3, 325, 'name,sf_link,email', 'Microsoft,https://salesforce.com/microsoft,contact@microsoft.com'),
(4, 456, 'name,sf_link,email', 'Wired,https://salesforce.com/wired,contact@wired.com'),
(5, 567, 'name,sf_link,email', 'Wirecutter,https://salesforce.com/wirecutter,contact@wirecutter.com'),
(6, 100, 'name,sf_link,email', 'Washington Post,https://salesforce.com/wp,contact@wp.com'),
(7, 101, 'name,sf_link,email', 'Engadget,https://salesforce.com/engadget,contact@engadget.com');

-- --------------------------------------------------------

--
-- Table structure for table `acctg_invoice_clients_key_dates`
--

CREATE TABLE IF NOT EXISTS `acctg_invoice_clients_key_dates` (
  `acctg_invoice_clients_key_dates_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL COMMENT 'matches accounting system client record ID',
  `date_deal_done` date NOT NULL,
  `date_effective` date NOT NULL,
  `date_term` date NOT NULL,
  PRIMARY KEY (`acctg_invoice_clients_key_dates_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `acctg_invoice_clients_key_dates`
--

INSERT INTO `acctg_invoice_clients_key_dates` (`acctg_invoice_clients_key_dates_id`, `client_id`, `date_deal_done`, `date_effective`, `date_term`) VALUES
(1, 123, '2016-03-01', '2016-04-01', '2017-04-01'),
(2, 234, '2016-03-01', '2016-04-01', '2017-03-01'),
(3, 325, '2016-03-01', '2016-04-01', '2017-03-01');

-- --------------------------------------------------------

--
-- Table structure for table `acctg_invoice_client_schedules`
--

CREATE TABLE IF NOT EXISTS `acctg_invoice_client_schedules` (
  `acctg_invoice_client_schedules_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL COMMENT 'matches accounting system client record ID',
  `date_only` date NOT NULL,
  `transaction_note` text NOT NULL,
  `transaction_product_variation` varchar(255) NOT NULL,
  `transaction_type` varchar(255) NOT NULL COMMENT 'bill, payment, sales order, adjustment, estimate',
  `transaction_value` decimal(10,2) NOT NULL,
  PRIMARY KEY (`acctg_invoice_client_schedules_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `acctg_invoice_client_schedules`
--

INSERT INTO `acctg_invoice_client_schedules` (`acctg_invoice_client_schedules_id`, `client_id`, `date_only`, `transaction_note`, `transaction_product_variation`, `transaction_type`, `transaction_value`) VALUES
(1, 123, '2016-01-01', 'First estimate', '', 'estimate', '14000.00'),
(2, 123, '2016-05-01', 'April payment', '', 'payment', '5000.00'),
(3, 123, '2016-06-01', 'Q2 invoice', '', 'bill', '20000.00'),
(4, 123, '2016-02-01', 'First sales order', '', 'sales order', '10000.00'),
(5, 325, '2016-01-01', 'First estimate', '', 'estimate', '50000.00'),
(6, 325, '2016-05-01', 'April payment', '', 'payment', '2000.00'),
(7, 325, '2016-06-01', 'Q2 invoice', '', 'bill', '10000.00'),
(8, 325, '2016-02-01', 'First sales order', '', 'sales order', '50000.00');

-- --------------------------------------------------------

--
-- Table structure for table `wp_users`
--

CREATE TABLE IF NOT EXISTS `wp_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_pass` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `spam` tinyint(2) NOT NULL DEFAULT '0',
  `deleted` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`),
  KEY `user_email` (`user_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `wp_users`
--

INSERT INTO `wp_users` (`ID`, `user_login`, `user_pass`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name`, `spam`, `deleted`) VALUES
(1, 'tally-admin', '$P$Bn8D7jOOVQhQGBekwfsjH/1IJ5P4Hv0', 'tally-admin', 'achhunna@gmail.com', '', '2016-09-27 16:18:43', '', 0, 'tally-admin', 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
