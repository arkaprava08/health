SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `health`
--
DROP PROCEDURE IF EXISTS `insertWithoutPatientId`;
DROP PROCEDURE IF EXISTS `insertWithPatientId`;
DROP PROCEDURE IF EXISTS `getSymptoms`;


DROP TABLE IF EXISTS `symptomsIdToSymptomsName`;
DROP TABLE IF EXISTS `action`;
DROP TABLE IF EXISTS `consultation`;
DROP TABLE IF EXISTS `patientdata`;
DROP TABLE IF EXISTS `patients`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `bodyTemperatureTypeIdToTypeName`;
----------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------

DELIMITER $$
-- Procedures
--
CREATE PROCEDURE `insertWithoutPatientId`(IN `name` VARCHAR(40), IN `dob` DATE, IN `gender` VARCHAR(1), IN `height` DECIMAL(6,2), IN `weight` DECIMAL(6,2), IN `address` VARCHAR(100), IN `bodytemperature` DECIMAL(5,2), IN `bodyTemperatureTypeId` INT(12),IN `bp_sp` DECIMAL(5,2), IN `bp_dp` DECIMAL(5,2), IN `symptoms` VARCHAR(30), IN `comment` VARCHAR(100), IN `latitude` DECIMAL(9,6), IN `longitude` DECIMAL(9,6), IN `userid` INT(12), IN `consultation` BOOLEAN)
    DETERMINISTIC
BEGIN
DECLARE patientId INT(12);
DECLARE patientDataId INT(12);

START TRANSACTION;

	INSERT INTO `patients`( `name`, `dob`, `gender`,`height`,`weight`, `address`, `userid`) VALUES (name , dob ,gender ,height ,weight ,address ,userid)   ;
	
	SET patientId = LAST_INSERT_ID();
	
	INSERT INTO `patientdata`( `patientid`, `userid`, `bodytemperature`,`bodyTemperatureTypeId`, `bp_sp`, `bp_dp`, `symptoms`, `comment`,`latitude`,`longitude`, `insertedDate`, `consultation`) VALUES ( patientId,userid ,bodytemperature,bodyTemperatureTypeId ,bp_sp,bp_dp,symptoms,comment,latitude,longitude , NOW(), consultation);
 
 SET patientDataId = LAST_INSERT_ID();
 
 select patientId as `patientId`, patientDataId as `patientDataId`;
    COMMIT;
END$$

CREATE PROCEDURE `insertWithPatientId`(IN `patientid` INT(12), IN `bodytemperature` DECIMAL(5,2), IN `bodyTemperatureTypeId` INT(12),IN `bp_sp` DECIMAL(5,2), IN `bp_dp` DECIMAL(5,2), IN `symptoms` VARCHAR(30), IN `comment` VARCHAR(100), IN `latitude` DECIMAL(9,6), IN `longitude` DECIMAL(9,6), IN `userid` INT(12), IN `consultation` BOOLEAN)
    DETERMINISTIC
BEGIN
DECLARE patientDataId INT(12);
START TRANSACTION;

	INSERT INTO `patientdata`( `patientid`, `userid`, `bodytemperature`,`bodyTemperatureTypeId`, `bp_sp`, `bp_dp`, `symptoms`, `comment`,`latitude`,`longitude`, `insertedDate`, `consultation`) VALUES (patientid ,userid ,bodytemperature,bodyTemperatureTypeId ,bp_sp ,bp_dp,symptoms ,comment ,latitude,longitude, NOW(), consultation);

 SET patientDataId = LAST_INSERT_ID();
 
 SELECT patientDataId as patientDataId;
    COMMIT;
END$$

CREATE PROCEDURE `getSymptoms`()
BEGIN
SELECT * FROM `symptomsIdToSymptomsName`;
END$$

----------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------



DELIMITER ;

--
-- Table structure for table `symptomsIdToSymptomsName`
--

CREATE TABLE `symptomsIdToSymptomsName` (
	`id` int(12) PRIMARY KEY,
   `symptom` VARCHAR(100) NOT NULL,
   `symptomHindi` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `symptomsIdToSymptomsName`
VALUES
(1,"Cough","khansi"),
(2,"Chills","thand lag kar kampkapi ana"),
(3,"Pain in abdomen","pet mein dard"),
(4,"Nausea and vomiting","jee michlana aur ulti"),
(5,"Diarrhoea","dast"),
(6,"Body ache and fatigue","sharir mein dard aur thakan"),
(7,"Weight loss in recent past","wajan kam hua hai kya pichle kuch dino mein"),
(8,"Bruises on body","sarir par lal nisan or chakte"),
(9,"Dizziness and blurred vision","chakkar and aur dundhla dikhai dena"),
(10,"Bone and joint pain","jodo mein dard"),
(11,"Difficulty in breathing","sans  lene mein taklif"),
(12,"Decreased appetite","bhook kam hona"),
(13,"Urinary complaints like burning micturition","pesab mein taklif jaise pesab mein jalan"),
(14,"Gynecological complaints like white discharge","safed pani ka jana");

--
-- Table structure for table `bodyTemperatureTypeIdToTypeName`
--

CREATE TABLE `bodyTemperatureTypeIdToTypeName` (
	`id` int(12) PRIMARY KEY,
   `bodyTemperatureType` VARCHAR(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `bodyTemperatureTypeIdToTypeName` 
VALUES
(1, "ORAL"),
(2, "AUXILLARY");


--

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
  `bodyTemperatureTypeId` INT(12) NOT NULL,
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
  patients (id),
  FOREIGN KEY (bodyTemperatureTypeId) REFERENCES
  bodyTemperatureTypeIdToTypeName (id)
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
