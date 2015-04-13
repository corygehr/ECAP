CREATE DATABASE  IF NOT EXISTS `eoc_cap_mgmt` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `eoc_cap_mgmt`;
-- MySQL dump 10.13  Distrib 5.6.11, for Win32 (x86)
--
-- Host: localhost    Database: eoc_cap_mgmt
-- ------------------------------------------------------
-- Server version	5.5.27

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account_types`
--

DROP TABLE IF EXISTS `account_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Type ID',
  `name` varchar(255) NOT NULL COMMENT 'Type Name',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_types`
--

LOCK TABLES `account_types` WRITE;
/*!40000 ALTER TABLE `account_types` DISABLE KEYS */;
INSERT INTO `account_types` VALUES (1,'Local'),(2,'Cosign');
/*!40000 ALTER TABLE `account_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lot_attendance`
--

DROP TABLE IF EXISTS `lot_attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lot_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Entry ID',
  `lot_id` int(11) NOT NULL COMMENT 'Corresponding Lot ID',
  `attendance` int(11) NOT NULL COMMENT 'Current Lot Attendance Count',
  `create_user` varchar(10) NOT NULL COMMENT 'Username of Creating User',
  `create_time` datetime NOT NULL COMMENT 'Date/Time of Row Creation',
  PRIMARY KEY (`id`),
  KEY `CAPACITY_LOT_ID_idx` (`lot_id`),
  KEY `CAPACITY_USERNAME_idx` (`create_user`),
  CONSTRAINT `CAPACITY_LOT_ID` FOREIGN KEY (`lot_id`) REFERENCES `lots` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CAPACITY_USERNAME` FOREIGN KEY (`create_user`) REFERENCES `users` (`username`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lot_attendance`
--

LOCK TABLES `lot_attendance` WRITE;
/*!40000 ALTER TABLE `lot_attendance` DISABLE KEYS */;
/*!40000 ALTER TABLE `lot_attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lot_schedule`
--

DROP TABLE IF EXISTS `lot_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lot_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Schedule ID',
  `lot_id` int(11) NOT NULL COMMENT 'Corresponding Lot ID',
  `open_time` datetime NOT NULL COMMENT 'Date/Time Lot Opens',
  `close_time` datetime NOT NULL COMMENT 'Date/Time Lot Closes',
  `create_user` varchar(10) NOT NULL COMMENT 'Username of Creating User',
  `create_time` datetime NOT NULL COMMENT 'Date/Time of Row Creation',
  `update_user` varchar(10) DEFAULT NULL COMMENT 'Date/Time of Last Update',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Date/Time of Last Row Update',
  `delete_user` varchar(10) DEFAULT NULL COMMENT 'Username of Deleting User',
  `delete_time` datetime DEFAULT NULL COMMENT 'Date/Time of Row Deletion',
  PRIMARY KEY (`id`),
  KEY `SCHEDULE_LOT_idx` (`lot_id`),
  KEY `SCHEDULE_CREATOR_idx` (`create_user`),
  KEY `SCHEDULE_DELETER_idx` (`delete_user`),
  CONSTRAINT `SCHEDULE_LOT_ID` FOREIGN KEY (`lot_id`) REFERENCES `lots` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `SCHEDULE_CREATOR` FOREIGN KEY (`create_user`) REFERENCES `users` (`username`) ON UPDATE CASCADE,
  CONSTRAINT `SCHEDULE_DELETER` FOREIGN KEY (`delete_user`) REFERENCES `users` (`username`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lot_schedule`
--

LOCK TABLES `lot_schedule` WRITE;
/*!40000 ALTER TABLE `lot_schedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `lot_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lot_status_log`
--

DROP TABLE IF EXISTS `lot_status_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lot_status_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Status ID',
  `lot_id` int(11) NOT NULL COMMENT 'Corresponding Lot ID',
  `status_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Lot Status ID',
  `comment` text NOT NULL COMMENT 'Lot Status Comment',
  `create_user` varchar(10) NOT NULL COMMENT 'Username of Creating User',
  `create_time` datetime NOT NULL COMMENT 'Date/Time of Row Insert',
  PRIMARY KEY (`id`),
  KEY `STATUS_LOG_STATUS_ID_idx` (`status_id`),
  KEY `STATUS_LOG_CREATE_USER_idx` (`create_user`),
  CONSTRAINT `STATUS_LOG_STATUS_ID` FOREIGN KEY (`status_id`) REFERENCES `lot_statuses` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `STATUS_LOG_CREATE_USER` FOREIGN KEY (`create_user`) REFERENCES `users` (`username`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lot_status_log`
--

LOCK TABLES `lot_status_log` WRITE;
/*!40000 ALTER TABLE `lot_status_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `lot_status_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lot_statuses`
--

DROP TABLE IF EXISTS `lot_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lot_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Status ID',
  `name` varchar(255) NOT NULL COMMENT 'Status Name',
  `description` text NOT NULL COMMENT 'Status Description',
  `create_user` varchar(10) NOT NULL COMMENT 'Username of Creating User',
  `create_time` datetime NOT NULL COMMENT 'Date/Time of Row Insert',
  `update_user` varchar(10) NOT NULL COMMENT 'Username of Updating User',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Date/Time of Last Row Update',
  `delete_user` varchar(10) DEFAULT NULL COMMENT 'Username of Deleting User',
  `delete_time` datetime DEFAULT NULL COMMENT 'Date/Time of Row Deletion',
  PRIMARY KEY (`id`),
  KEY `STATUSES_CREATE_USER_idx` (`create_user`),
  KEY `STATUSES_UPDATE_USER_idx` (`update_user`),
  KEY `STATUSES_DELETE_USER_idx` (`delete_user`),
  CONSTRAINT `STATUSES_CREATE_USER` FOREIGN KEY (`create_user`) REFERENCES `users` (`username`) ON UPDATE CASCADE,
  CONSTRAINT `STATUSES_UPDATE_USER` FOREIGN KEY (`update_user`) REFERENCES `users` (`username`) ON UPDATE CASCADE,
  CONSTRAINT `STATUSES_DELETE_USER` FOREIGN KEY (`delete_user`) REFERENCES `users` (`username`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lot_statuses`
--

LOCK TABLES `lot_statuses` WRITE;
/*!40000 ALTER TABLE `lot_statuses` DISABLE KEYS */;
/*!40000 ALTER TABLE `lot_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lots`
--

DROP TABLE IF EXISTS `lots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lots` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Lot ID',
  `name` varchar(255) NOT NULL COMMENT 'Lot Name',
  `color` varchar(45) NOT NULL COMMENT 'Lot Color Hex Code',
  `location_name` varchar(255) NOT NULL COMMENT 'Name of Lot Location',
  `latitude` float(10,6) NOT NULL COMMENT 'Latitude',
  `longitude` float(10,6) NOT NULL COMMENT 'Longitude',
  `max_capacity` int(11) NOT NULL COMMENT 'Maximum Lot Capacity',
  `create_user` varchar(10) NOT NULL COMMENT 'Username of Creating User',
  `create_time` datetime NOT NULL COMMENT 'Date/Time of Lot Creation',
  `update_user` varchar(10) DEFAULT NULL COMMENT 'Username of Updating User',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Date/Time of Last Row Update',
  `delete_user` varchar(10) DEFAULT NULL COMMENT 'Username of Deleting User',
  `delete_time` datetime DEFAULT NULL COMMENT 'Date/Time of Lot Deletion',
  PRIMARY KEY (`id`),
  KEY `LOT_CREATOR_idx` (`create_user`),
  KEY `LOT_UPDATER_idx` (`update_user`),
  KEY `LOT_DELETER_idx` (`delete_user`),
  CONSTRAINT `LOT_CREATOR` FOREIGN KEY (`create_user`) REFERENCES `users` (`username`) ON UPDATE CASCADE,
  CONSTRAINT `LOT_UPDATER` FOREIGN KEY (`update_user`) REFERENCES `users` (`username`) ON UPDATE CASCADE,
  CONSTRAINT `LOT_DELETER` FOREIGN KEY (`delete_user`) REFERENCES `users` (`username`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lots`
--

LOCK TABLES `lots` WRITE;
/*!40000 ALTER TABLE `lots` DISABLE KEYS */;
INSERT INTO `lots` VALUES (1,'Lot A','Blue','Beaver Stadium',40.000999,35.000000,105,'cmg5573','2015-04-13 01:04:32',NULL,'2015-04-13 05:55:57',NULL,NULL);
/*!40000 ALTER TABLE `lots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_passwords`
--

DROP TABLE IF EXISTS `user_passwords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_passwords` (
  `username` varchar(10) NOT NULL COMMENT 'Username',
  `hash` varchar(64) NOT NULL COMMENT 'Password Hash',
  PRIMARY KEY (`username`),
  CONSTRAINT `UP_USERNAME` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_passwords`
--

LOCK TABLES `user_passwords` WRITE;
/*!40000 ALTER TABLE `user_passwords` DISABLE KEYS */;
INSERT INTO `user_passwords` VALUES ('password','61be55a8e2f6b4e172338bddf184d6dbee29c98853e0a0485ecee7f27b9af0b4');
/*!40000 ALTER TABLE `user_passwords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_rights`
--

DROP TABLE IF EXISTS `user_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Right ID',
  `username` varchar(10) NOT NULL COMMENT 'Username',
  `s` varchar(255) NOT NULL COMMENT 'Section Name',
  `ss` varchar(255) DEFAULT NULL COMMENT 'Subsection Name (NULL for all)',
  PRIMARY KEY (`id`),
  KEY `UR_USERNAME_idx` (`username`),
  CONSTRAINT `UR_USERNAME` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_rights`
--

LOCK TABLES `user_rights` WRITE;
/*!40000 ALTER TABLE `user_rights` DISABLE KEYS */;
INSERT INTO `user_rights` VALUES (1,'tester','*',NULL),(2,'password','*',NULL),(10,'LotA','LotConsole','manage');
/*!40000 ALTER TABLE `user_rights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_rights_identifiers`
--

DROP TABLE IF EXISTS `user_rights_identifiers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_rights_identifiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Right Identifier ID',
  `right_id` int(11) NOT NULL COMMENT 'Right ID',
  `identifier_name` varchar(255) NOT NULL COMMENT 'URL Right Identifier Name',
  `identifier_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `URI_RIGHT_ID_idx` (`right_id`),
  CONSTRAINT `URI_RIGHT_ID` FOREIGN KEY (`right_id`) REFERENCES `user_rights` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_rights_identifiers`
--

LOCK TABLES `user_rights_identifiers` WRITE;
/*!40000 ALTER TABLE `user_rights_identifiers` DISABLE KEYS */;
INSERT INTO `user_rights_identifiers` VALUES (1,10,'id','1');
/*!40000 ALTER TABLE `user_rights_identifiers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_types`
--

DROP TABLE IF EXISTS `user_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Type ID',
  `name` varchar(255) NOT NULL COMMENT 'Type Name',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_types`
--

LOCK TABLES `user_types` WRITE;
/*!40000 ALTER TABLE `user_types` DISABLE KEYS */;
INSERT INTO `user_types` VALUES (1,'Administrator'),(2,'Lot Attendant');
/*!40000 ALTER TABLE `user_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `username` varchar(10) NOT NULL COMMENT 'Username',
  `account_type` int(11) NOT NULL COMMENT 'Account Type',
  `user_type` int(11) NOT NULL COMMENT 'User Type',
  `full_name` varchar(255) NOT NULL COMMENT 'Full Name',
  `create_time` datetime NOT NULL COMMENT 'Date/Time of User Creation',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Date/Time of Last Row Update',
  `delete_date` datetime DEFAULT NULL COMMENT 'Date/Time of User Deletion',
  PRIMARY KEY (`username`),
  KEY `TYPE_USER_TYPE_ID_idx` (`account_type`),
  KEY `TYPE_USER_TYPE_ID_idx1` (`user_type`),
  CONSTRAINT `TYPE_ACCOUNT_TYPE_ID` FOREIGN KEY (`account_type`) REFERENCES `account_types` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `TYPE_USER_TYPE_ID` FOREIGN KEY (`user_type`) REFERENCES `user_types` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('cmg5573',1,1,'Cory Gehr','2015-04-12 00:00:00','2015-04-12 04:00:00',NULL),('LotA',2,2,'sda','2015-04-13 01:01:55','2015-04-13 05:01:55',NULL),('password',1,1,'Password Test','2015-04-13 00:57:51','2015-04-13 04:57:51',NULL),('tester',1,1,'Cory\'s Test Acct','2015-04-13 00:47:44','2015-04-13 04:47:44',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'eoc_cap_mgmt'
--

--
-- Dumping routines for database 'eoc_cap_mgmt'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-04-13  2:00:28
