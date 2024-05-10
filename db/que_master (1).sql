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
-- Table structure for table `que_master`
--

DROP TABLE IF EXISTS `que_master`;
CREATE TABLE `que_master` (
  `qrid` int(11) NOT NULL,
  `qforrid` int(11) NOT NULL,
  `DateEntered` datetime DEFAULT NULL,
  `purpose` text,
  `LastName` varchar(100) DEFAULT NULL,
  `FirstName` varchar(100) DEFAULT NULL,
  `MiddleName` varchar(100) DEFAULT NULL,
  `loginRID` int(11) DEFAULT NULL,
  `toLocation` varchar(255) DEFAULT NULL,
  `questatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-active, 1-on-going, 9-done',
  `QueTS` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Deleted` tinyint(1) NOT NULL DEFAULT '0',
  `zflag_wyz` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `que_master`
--

INSERT INTO `que_master` (`qrid`, `qforrid`, `DateEntered`, `purpose`, `LastName`, `FirstName`, `MiddleName`, `loginRID`, `toLocation`, `questatus`, `QueTS`, `Deleted`, `zflag_wyz`) VALUES
(1, 1, '2021-02-11 15:04:51', NULL, 'asdfd', 'fdfas', 'fdfasdfd', NULL, NULL, 0, '2021-02-11 07:17:51', 0, 0),
(2, 2, '2021-02-11 15:04:55', NULL, 'asdfd', 'fdfas', 'fdfasdfd', NULL, NULL, 0, '2021-02-11 07:17:51', 0, 0),
(3, 3, '2021-02-11 15:05:27', NULL, 'wefe', 'fefw', 'efefw', NULL, NULL, 0, '2021-02-11 07:17:51', 0, 0),
(4, 4, '2021-02-11 15:10:14', NULL, 'rf4', 'f4rf', 'rferf', NULL, NULL, 0, '2021-02-11 07:17:51', 0, 0),
(5, 5, '2021-02-11 15:10:43', NULL, 'gergev', 'rgerg', 'ergvrge', NULL, NULL, 0, '2021-02-11 07:17:51', 0, 0),
(6, 6, '2021-02-11 15:11:18', NULL, 'e rgwer', 'gvwergwve', 'rgvwerg', NULL, NULL, 0, '2021-02-11 07:17:51', 0, 0),
(7, 1, '2021-02-11 15:18:18', NULL, 'freawe', 'fsefaef', 'efsasef', NULL, NULL, 0, '2021-02-11 07:19:01', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `que_master`
--
ALTER TABLE `que_master`
  ADD PRIMARY KEY (`qrid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `que_master`
--
ALTER TABLE `que_master`
  MODIFY `qrid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
