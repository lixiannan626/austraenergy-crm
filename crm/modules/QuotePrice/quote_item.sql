-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 05, 2013 at 02:55 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `goforsolar_crm`
--

-- --------------------------------------------------------

--
-- Table structure for table `quote_item`
--

CREATE TABLE IF NOT EXISTS `quote_item` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` text NOT NULL,
  `item_category` text NOT NULL,
  `item_brand` text NOT NULL,
  `item_price` text NOT NULL,
  `item_size` text NOT NULL,
  `item_available` int(11) NOT NULL DEFAULT '0',
  `item_deleted` int(11) NOT NULL DEFAULT '0',
  `item_roof_type` text NOT NULL,
  `item_service` text NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `quote_item`
--

INSERT INTO `quote_item` (`item_id`, `item_name`, `item_category`, `item_brand`, `item_price`, `item_size`, `item_available`, `item_deleted`, `item_roof_type`, `item_service`) VALUES
(1, 'Simax Mono 190W', 'Panel', 'Simax', '111', '190', 1, 0, '', ''),
(2, 'Simax Mono 250W', 'Panel', 'Simax', '222', '250', 0, 1, '', ''),
(4, 'Growatt 5000TL', 'Inverter', 'Growatt', '900', '5000', 1, 0, '', ''),
(5, 'Growatt 4000TL', 'Inverter', 'Growatt', '800', '4000', 1, 0, '', ''),
(6, 'Antai Tin 1.5kW', 'mounting', 'Antai', '100', '1500', 1, 0, 'Tin', ''),
(7, 'Install 3kW Tin', 'Installer', '', '3000', '3000', 1, 0, 'Tin', 'Standard'),
(8, 'Install 3kW Tile', 'Installer', '', '3500', '3000', 1, 0, 'Tile', 'Premium'),
(9, 'Simax Mono 250W', 'Panel', 'Simax', '2000', '2000', 1, 0, '', ''),
(10, 'Hanover Mono 250W', 'Panel', 'Hanover', '200', '250', 1, 0, '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
