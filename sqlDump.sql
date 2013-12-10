-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 10, 2013 at 06:26 AM
-- Server version: 5.5.29
-- PHP Version: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `tradeCollab`
--

-- --------------------------------------------------------

--
-- Table structure for table `tradeCollab_comments`
--

CREATE TABLE `tradeCollab_comments` (
  `chat_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `deal_id` mediumint(9) NOT NULL,
  `member_id` mediumint(9) NOT NULL,
  `chat` varchar(4096) DEFAULT NULL,
  `chat_created` datetime NOT NULL,
  PRIMARY KEY (`chat_id`),
  KEY `tradeCollab_comments_ibfk_1` (`deal_id`),
  KEY `tradeCollab_comments_ibfk_2` (`member_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `tradeCollab_comments`
--

INSERT INTO `tradeCollab_comments` (`chat_id`, `deal_id`, `member_id`, `chat`, `chat_created`) VALUES
(33, 3, 19, 'Heya', '2013-12-09 19:35:11'),
(34, 3, 19, 'oye hoye', '2013-12-09 19:35:44'),
(35, 3, 19, 'oye hoye', '2013-12-09 19:37:19'),
(36, 3, 19, 'abe sun', '2013-12-09 19:39:30'),
(37, 3, 19, 'oye', '2013-12-09 19:39:43'),
(38, 3, 19, 'oye', '2013-12-09 19:39:51'),
(39, 3, 19, 'oye', '2013-12-09 19:39:57'),
(40, 3, 19, 'abe', '2013-12-09 19:40:49'),
(41, 6, 19, 'abe samba', '2013-12-09 19:41:27'),
(42, 6, 19, 'kutte kamine saale lutwa dega bhai tu', '2013-12-09 19:58:38'),
(43, 6, 19, 'kutte kamine saale lutwa dega bhai tu lauda nahi de rahe hain paise tujhe.. ukhaad le jo ukhaadna hai', '2013-12-09 19:59:05'),
(44, 3, 19, 'Ma chuda laudo nahi de rha paise', '2013-12-09 20:09:06'),
(45, 43, 19, 'abe kyon be', '2013-12-09 23:23:30'),
(46, 45, 19, 'abe ye to khareedne ka hai be', '2013-12-09 23:38:27'),
(47, 45, 19, 'Laudus', '2013-12-09 23:38:36'),
(48, 45, 19, 'samjhe ky abe ki nahi', '2013-12-09 23:38:52');

-- --------------------------------------------------------

--
-- Table structure for table `tradeCollab_deal`
--

CREATE TABLE `tradeCollab_deal` (
  `deal_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `team_id` mediumint(9) DEFAULT NULL,
  `member_id` mediumint(9) DEFAULT NULL,
  `member_email` varchar(1024) NOT NULL,
  `stock_name` varchar(1024) NOT NULL,
  `stock_price` float DEFAULT NULL,
  `stock_quant` mediumint(9) DEFAULT NULL,
  `deal_nature` varchar(128) DEFAULT NULL,
  `reason` varchar(4096) DEFAULT NULL,
  `deal_end` date DEFAULT NULL,
  `deal_created` datetime DEFAULT NULL,
  `market` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`deal_id`),
  KEY `tradeCollab_deal_ibfk_1` (`team_id`),
  KEY `tradeCollab_deal_ibfk_2` (`member_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

--
-- Dumping data for table `tradeCollab_deal`
--

INSERT INTO `tradeCollab_deal` (`deal_id`, `team_id`, `member_id`, `member_email`, `stock_name`, `stock_price`, `stock_quant`, `deal_nature`, `reason`, `deal_end`, `deal_created`, `market`) VALUES
(3, 8, 20, 'abc@gmail.com', 'TEST', 20.5, 50, 'SELL', 'JLT', '2013-12-08', '2013-12-08 19:55:24', 'BSE'),
(4, 8, 20, 'abc@gmail.com', 'INFOSYS', 20.5, 100, 'BUY', 'JLT', '2013-12-13', '2013-12-08 19:56:00', 'NASDAQ'),
(5, 8, 21, 'abc2@gmail.com', 'TCS', 30.5, 100, 'BUY', 'JLT', '2013-12-13', '2013-12-08 19:56:21', 'BSE'),
(6, 8, 21, 'abc2@gmail.com', 'TCS', 30.5, 100, 'BUY', 'JLT', '2013-12-15', '2013-12-08 19:56:30', 'BSE'),
(42, 8, 19, 'somesh737@gmail.com', 'TATA', 23.5, 50, 'BUY', 'wer \r\n			', '2013-12-15', '2013-12-09 04:23:06', 'BSE'),
(43, 8, 19, 'somesh737@gmail.com', 'ACER', 230.5, 100, 'BUY', 'Abe Khareed Lo Yaar', '2013-12-25', '2013-12-09 21:56:35', 'NASDAQ'),
(44, 8, 19, 'somesh737@gmail.com', 'Google', 1111.5, 100, 'SELL', 'Abe bech Lo Yaar', '2013-12-25', '2013-12-09 23:34:13', NULL),
(45, 8, 19, 'somesh737@gmail.com', 'Google', 1000.5, 100, 'BUY', 'Abe bech Lo Yaar', '2013-12-25', '2013-12-09 23:34:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tradeCollab_deal_status`
--

CREATE TABLE `tradeCollab_deal_status` (
  `team_id` mediumint(9) DEFAULT NULL,
  `deal_id` mediumint(9) DEFAULT NULL,
  `member_id` mediumint(9) DEFAULT NULL,
  `member_status` varchar(128) DEFAULT NULL,
  KEY `tradeCollab_deal_status_ibfk_1` (`deal_id`),
  KEY `tradeCollab_deal_status_ibfk_2` (`member_id`),
  KEY `tradeCollab_deal_status_ibfk_3` (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tradeCollab_deal_status`
--

INSERT INTO `tradeCollab_deal_status` (`team_id`, `deal_id`, `member_id`, `member_status`) VALUES
(8, 42, 19, 'YES'),
(8, 42, 20, NULL),
(8, 42, 21, NULL),
(8, 3, 20, 'YES'),
(8, 3, 19, NULL),
(8, 4, 19, 'YES'),
(8, 5, 19, 'YES'),
(8, 6, 19, NULL),
(8, 6, 21, 'YES'),
(8, 5, 21, 'YES'),
(8, 4, 21, 'YES'),
(8, 3, 21, 'NO'),
(8, 4, 20, 'YES'),
(8, 5, 20, 'NO'),
(8, 6, 20, NULL),
(8, 43, 19, 'YES'),
(8, 43, 20, 'YES'),
(8, 43, 21, 'YES'),
(NULL, NULL, NULL, NULL),
(8, 44, 19, 'YES'),
(8, 44, 20, 'YES'),
(8, 44, 21, 'YES'),
(8, 45, 21, 'YES'),
(8, 45, 20, 'YES'),
(8, 45, 19, 'YES');

-- --------------------------------------------------------

--
-- Table structure for table `tradeCollab_members`
--

CREATE TABLE `tradeCollab_members` (
  `member_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `member_email` varchar(128) NOT NULL,
  `team_id` mediumint(9) DEFAULT NULL,
  `member_name` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`member_id`),
  KEY `tradeCollab_members_ibfk_1` (`team_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `tradeCollab_members`
--

INSERT INTO `tradeCollab_members` (`member_id`, `member_email`, `team_id`, `member_name`) VALUES
(19, 'somesh737@gmail.com', 8, 'somesh'),
(20, 'abc@gmail.com', 8, 'Rohit'),
(21, 'abc2@gmail.com', 8, 'Gandotra'),
(22, 'somesh@understandinggroup.com', NULL, 'Somesh');

-- --------------------------------------------------------

--
-- Table structure for table `tradeCollab_team`
--

CREATE TABLE `tradeCollab_team` (
  `team_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `team_name` varchar(1024) NOT NULL,
  `budget` double DEFAULT NULL,
  `markets` varchar(4096) NOT NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `tradeCollab_team`
--

INSERT INTO `tradeCollab_team` (`team_id`, `team_name`, `budget`, `markets`) VALUES
(8, 'Test', 150000.95, 'BSE;NASDAQ;Shanghai Stock Exchange;');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tradeCollab_comments`
--
ALTER TABLE `tradeCollab_comments`
  ADD CONSTRAINT `tradeCollab_comments_ibfk_1` FOREIGN KEY (`deal_id`) REFERENCES `tradeCollab_deal` (`deal_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tradeCollab_comments_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `tradeCollab_members` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tradeCollab_deal`
--
ALTER TABLE `tradeCollab_deal`
  ADD CONSTRAINT `tradeCollab_deal_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `tradeCollab_team` (`team_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tradeCollab_deal_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `tradeCollab_members` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tradeCollab_deal_status`
--
ALTER TABLE `tradeCollab_deal_status`
  ADD CONSTRAINT `tradeCollab_deal_status_ibfk_1` FOREIGN KEY (`deal_id`) REFERENCES `tradeCollab_deal` (`deal_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tradeCollab_deal_status_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `tradeCollab_members` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tradeCollab_deal_status_ibfk_3` FOREIGN KEY (`team_id`) REFERENCES `tradeCollab_team` (`team_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tradeCollab_members`
--
ALTER TABLE `tradeCollab_members`
  ADD CONSTRAINT `tradeCollab_members_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `tradeCollab_team` (`team_id`) ON DELETE CASCADE ON UPDATE CASCADE;