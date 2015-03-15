-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Mar 15, 2015 at 09:12 PM
-- Server version: 5.5.38
-- PHP Version: 5.6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `health`
--

-- --------------------------------------------------------

--
-- Table structure for table `patientdata`
--

CREATE TABLE `patientdata` (
`id` int(12) NOT NULL,
  `patientid` int(12) NOT NULL,
  `userid` int(12) NOT NULL,
  `bodytemperature` int(4) NOT NULL,
  `bp_sp` int(4) NOT NULL,
  `bp_dp` int(4) NOT NULL,
  `symptoms` varchar(30) DEFAULT NULL,
  `comment` varchar(100) DEFAULT NULL,
  `latitude` decimal(9,0) DEFAULT NULL,
  `longitude` decimal(9,0) DEFAULT NULL,
  `insertedDate` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patientdata`
--

INSERT INTO `patientdata` (`id`, `patientid`, `userid`, `bodytemperature`, `bp_sp`, `bp_dp`, `symptoms`, `comment`, `latitude`, `longitude`, `insertedDate`) VALUES
(7, 1, 1, 91, 78, 0, '1,2,3,4', 'DSAADSDAS', NULL, NULL, '2015-03-15 18:10:09'),
(8, 1, 1, 91, 78, 0, '1,2,3,4', 'DSAADSDAS', NULL, NULL, '2015-03-15 18:10:11'),
(13, 6, 1, 91, 78, 0, '3', 'DSAADSDAS', NULL, NULL, '2015-03-15 19:24:49'),
(19, 12, 1, 91, 78, 78, '3', 'DSAADSDAS', NULL, NULL, '2015-03-15 23:29:32');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
`id` int(12) NOT NULL,
  `name` varchar(40) NOT NULL,
  `age` int(2) NOT NULL,
  `gender` varchar(1) NOT NULL,
  `address` varchar(100) NOT NULL,
  `userid` int(12) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `name`, `age`, `gender`, `address`, `userid`) VALUES
(1, 'das', 21, 'm', 'dsa', 1),
(6, 'arka', 21, 'M', 'asdasdasdas', 1),
(12, 'arka1', 21, 'M', 'asdasdasdas', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
`id` int(12) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(40) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
(1, 'arka', 'arka', 'arka@gmail.com'),
(2, 'admin', 'admin', 'admin@temp.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `patientdata`
--
ALTER TABLE `patientdata`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `patientdata`
--
ALTER TABLE `patientdata`
MODIFY `id` int(12) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
MODIFY `id` int(12) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(12) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;