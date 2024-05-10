-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 11, 2021 at 08:20 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ipadrbg`
--

-- --------------------------------------------------------

--
-- Table structure for table `que_regs`
--

DROP TABLE IF EXISTS `que_regs`;
CREATE TABLE `que_regs` (
  `qregsRID` int(9) NOT NULL,
  `DateEntered` datetime DEFAULT NULL,
  `purpose` text,
  `LastName` varchar(100) DEFAULT NULL,
  `FirstName` varchar(100) DEFAULT NULL,
  `MiddleName` varchar(100) DEFAULT NULL,
  `loginRID` int(11) DEFAULT NULL,
  `questatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-active, 1-on-going, 9-done',
  `QueTS` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Deleted` tinyint(1) NOT NULL DEFAULT '0',
  `zflag_wyz` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `que_regs`
--
ALTER TABLE `que_regs`
  ADD PRIMARY KEY (`qregsRID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `que_regs`
--
ALTER TABLE `que_regs`
  MODIFY `qregsRID` int(9) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
