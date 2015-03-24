SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `health`
--
DROP PROCEDURE IF EXISTS `insertWithoutPatientId`;
DROP PROCEDURE IF EXISTS `insertWithPatientId`;

DROP TABLE IF EXISTS `action`;
DROP TABLE IF EXISTS `consultation`;
DROP TABLE IF EXISTS `patientdata`;
DROP TABLE IF EXISTS `patients`;
DROP TABLE IF EXISTS `users`;


----------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------

DELIMITER $$
-- Procedures
--
CREATE PROCEDURE `insertWithoutPatientId`(IN `name` VARCHAR(40), IN `dob` DATE, IN `gender` VARCHAR(1), IN `height` DECIMAL(6,2), IN `weight` DECIMAL(6,2), IN `address` VARCHAR(100), IN `bodytemperature` DECIMAL(5,2), IN `bp_sp` DECIMAL(5,2), IN `bp_dp` DECIMAL(5,2), IN `symptoms` VARCHAR(30), IN `comment` VARCHAR(100), IN `latitude` DECIMAL(9,6), IN `longitude` DECIMAL(9,6), IN `userid` INT(12), IN `consultation` BOOLEAN)
    DETERMINISTIC
BEGIN
DECLARE patientId INT(12);
DECLARE patientDataId INT(12);

START TRANSACTION;

	INSERT INTO `patients`( `name`, `dob`, `gender`,`height`,`weight`, `address`, `userid`) VALUES (name , dob ,gender ,height ,weight ,address ,userid)   ;
	
	SET patientId = LAST_INSERT_ID();
	
	INSERT INTO `patientdata`( `patientid`, `userid`, `bodytemperature`, `bp_sp`, `bp_dp`, `symptoms`, `comment`,`latitude`,`longitude`, `insertedDate`, `consultation`) VALUES ( patientId,userid ,bodytemperature ,bp_sp,bp_dp,symptoms,comment,latitude,longitude , NOW(), consultation);
 
 SET patientDataId = LAST_INSERT_ID();
 
 select patientId as `patientId`, patientDataId as `patientDataId`;
    COMMIT;
END$$

CREATE PROCEDURE `insertWithPatientId`(IN `patientid` INT(12), IN `bodytemperature` DECIMAL(5,2), IN `bp_sp` DECIMAL(5,2), IN `bp_dp` DECIMAL(5,2), IN `symptoms` VARCHAR(30), IN `comment` VARCHAR(100), IN `latitude` DECIMAL(9,6), IN `longitude` DECIMAL(9,6), IN `userid` INT(12), IN `consultation` BOOLEAN)
    DETERMINISTIC
BEGIN
DECLARE patientDataId INT(12);
START TRANSACTION;

	INSERT INTO `patientdata`( `patientid`, `userid`, `bodytemperature`, `bp_sp`, `bp_dp`, `symptoms`, `comment`,`latitude`,`longitude`, `insertedDate`, `consultation`) VALUES (patientid ,userid ,bodytemperature ,bp_sp ,bp_dp,symptoms ,comment ,latitude,longitude, NOW(), consultation);

 SET patientDataId = LAST_INSERT_ID();
 
 SELECT patientDataId as patientDataId;
    COMMIT;
END$$
----------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------

DELIMITER ;

CREATE TABLE `users` (
`id` int(12) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `email`) VALUES
('arka', 'arka', 'arka@gmail.com'),
('admin', 'admin', 'admin@temp.com');


-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
`id` int(12) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(1) NOT NULL,
  `height` decimal(6,2) NOT NULL,
  `weight` decimal(6,2) NOT NULL,
  `address` varchar(100) NOT NULL,
  `userid` int(12) NOT NULL,
  FOREIGN KEY (userid) REFERENCES
  users (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `patientdata`
--

CREATE TABLE `patientdata` (
`id` int(12) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `patientid` int(12) NOT NULL,
  `userid` int(12) NOT NULL,
  `bodytemperature` decimal(5,2) NOT NULL,
  `bp_sp` decimal(5,2) NOT NULL,
  `bp_dp` decimal(5,2) NOT NULL,
  `symptoms` varchar(30) DEFAULT NULL,
  `comment` varchar(100) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `insertedDate` datetime NOT NULL,
  `consultation` tinyint(1) NOT NULL DEFAULT '0',
  FOREIGN KEY (userid) REFERENCES
  users (id),
  FOREIGN KEY (patientid) REFERENCES
  patients (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `action`
--

CREATE TABLE `action` (
`id` int(12)  PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `patientdataid` int(12) NOT NULL,
  `actiondetails` varchar(40) DEFAULT NULL,
  `date` date NOT NULL,
  FOREIGN KEY (patientdataid) REFERENCES
  patientdata (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `consultation`
--

CREATE TABLE `consultation` (
`id` int(12) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `patientId` int(12) NOT NULL,
  `patientDataId` int(12) NOT NULL,
  `isChecked` tinyint(1) NOT NULL DEFAULT '0',
  `assignedToUser` int(12) DEFAULT NULL,
  FOREIGN KEY (patientid) REFERENCES
  patients (id),
  FOREIGN KEY (patientDataId) REFERENCES
  patientdata (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


----------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------




--
-- Triggers `patientdata`
--
DELIMITER //
CREATE TRIGGER `insertForConsultation` AFTER INSERT ON `patientdata`
 FOR EACH ROW BEGIN 
UPDATE `consultation` SET `isChecked`=1 WHERE `patientId` = NEW.patientId;
IF NEW.consultation = true THEN 
INSERT INTO `consultation`( `patientId`, `patientDataId`, `isChecked`, `assignedToUser`) VALUES (NEW.patientid,NEW.id,0,null); 
END IF; 
END
//
DELIMITER ;
