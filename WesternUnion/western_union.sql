-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 31, 2019 at 11:59 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `western_union`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `AccNumb` int(20) NOT NULL,
  `Amt` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`AccNumb`, `Amt`) VALUES
(101, 1000),
(104, 5000);

-- --------------------------------------------------------

--
-- Table structure for table `custmer`
--

CREATE TABLE `custmer` (
  `cid` int(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `city` varchar(50) NOT NULL,
  `sessio_id` varchar(100) NOT NULL,
  `dt` datetime(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `otp`
--

CREATE TABLE `otp` (
  `id` int(100) NOT NULL,
  `uid` int(100) NOT NULL,
  `motp` int(6) NOT NULL,
  `eotp` int(6) NOT NULL,
  `dt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `otp`
--

INSERT INTO `otp` (`id`, `uid`, `motp`, `eotp`, `dt`) VALUES
(1, 101, 253641, 224136, '2019-08-31 13:53:11'),
(2, 101, 710221, 730979, '2019-08-31 13:53:36'),
(3, 101, 586036, 956165, '2019-08-31 15:09:00'),
(4, 101, 962507, 239444, '2019-08-31 15:14:36');

-- --------------------------------------------------------

--
-- Table structure for table `uid`
--

CREATE TABLE `uid` (
  `id` int(100) NOT NULL,
  `uidai` bigint(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mob` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `city` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uid`
--

INSERT INTO `uid` (`id`, `uidai`, `name`, `mob`, `email`, `city`) VALUES
(101, 125463987456, 'NITIN BHOPATKAR', '9754478819', 'nitin.bhopatkar@gmail.com', 'PUNE'),
(102, 121123451210, 'Sagar', '8574229031', 'sagar@gmail.com', 'Pune');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `custmer`
--
ALTER TABLE `custmer`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `otp`
--
ALTER TABLE `otp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otp_uid_fk` (`uid`);

--
-- Indexes for table `uid`
--
ALTER TABLE `uid`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uidai` (`uidai`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `custmer`
--
ALTER TABLE `custmer`
  MODIFY `cid` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp`
--
ALTER TABLE `otp`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `uid`
--
ALTER TABLE `uid`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `otp`
--
ALTER TABLE `otp`
  ADD CONSTRAINT `otp_uid_fk` FOREIGN KEY (`uid`) REFERENCES `uid` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
