-- phpMyAdmin SQL Dump
-- version 4.4.15.8
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 06, 2018 at 11:44 AM
-- Server version: 5.6.31
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wc_db_gpfst`
--

-- --------------------------------------------------------

--
-- Table structure for table `wc_t_cacfac_request`
--

CREATE TABLE IF NOT EXISTS `wc_t_cacfac_request` (
  `id` int(11) NOT NULL,
  `poNo` varchar(15) DEFAULT NULL,
  `lcNo` varchar(20) DEFAULT NULL,
  `ciNo` varchar(20) DEFAULT NULL,
  `ciValue` double DEFAULT NULL,
  `partValue` double DEFAULT NULL,
  `submittedBy` int(11) DEFAULT NULL,
  `submittedOn` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `submittedFrom` varchar(50) DEFAULT NULL,
  `issueDate` datetime DEFAULT NULL,
  `issuedBy` int(11) DEFAULT NULL,
  `issuedFrom` varchar(50) DEFAULT NULL,
  `letterBody` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wc_t_cacfac_request`
--
ALTER TABLE `wc_t_cacfac_request`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wc_t_cacfac_request`
--
ALTER TABLE `wc_t_cacfac_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
