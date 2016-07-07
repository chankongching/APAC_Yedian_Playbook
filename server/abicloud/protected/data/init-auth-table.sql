-- MySQL dump 10.13  Distrib 5.5.41, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: abiktv
-- ------------------------------------------------------
-- Server version	5.5.41-0ubuntu0.12.04.1

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
-- Table structure for table `ak_auth_item`
--

DROP TABLE IF EXISTS `ak_auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ak_auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ak_auth_item`
--

LOCK TABLES `ak_auth_item` WRITE;
/*!40000 ALTER TABLE `ak_auth_item` DISABLE KEYS */;
INSERT INTO `ak_auth_item` VALUES ('CheckinCode.*',1,NULL,NULL,'N;'),('CheckinCode.Admin',0,NULL,NULL,'N;'),('CheckinCode.Create',0,NULL,NULL,'N;'),('CheckinCode.Delete',0,NULL,NULL,'N;'),('CheckinCode.Index',0,NULL,NULL,'N;'),('CheckinCode.Update',0,NULL,NULL,'N;'),('CheckinCode.View',0,NULL,NULL,'N;'),('CheckinState.*',1,NULL,NULL,'N;'),('CheckinState.Admin',0,NULL,NULL,'N;'),('CheckinState.Create',0,NULL,NULL,'N;'),('CheckinState.Delete',0,NULL,NULL,'N;'),('CheckinState.Index',0,NULL,NULL,'N;'),('CheckinState.Update',0,NULL,NULL,'N;'),('CheckinState.View',0,NULL,NULL,'N;'),('CheckinUser.*',1,NULL,NULL,'N;'),('CheckinUser.Admin',0,NULL,NULL,'N;'),('CheckinUser.Create',0,NULL,NULL,'N;'),('CheckinUser.Delete',0,NULL,NULL,'N;'),('CheckinUser.Index',0,NULL,NULL,'N;'),('CheckinUser.Update',0,NULL,NULL,'N;'),('CheckinUser.View',0,NULL,NULL,'N;'),('Device.*',1,NULL,NULL,'N;'),('Device.Admin',0,NULL,NULL,'N;'),('Device.Bind',0,NULL,NULL,'N;'),('Device.Create',0,NULL,NULL,'N;'),('Device.Delete',0,NULL,NULL,'N;'),('Device.Index',0,NULL,NULL,'N;'),('Device.Update',0,NULL,NULL,'N;'),('Device.View',0,NULL,NULL,'N;'),('DevicePlaylist.*',1,NULL,NULL,'N;'),('DevicePlaylist.Admin',0,NULL,NULL,'N;'),('DevicePlaylist.Create',0,NULL,NULL,'N;'),('DevicePlaylist.Delete',0,NULL,NULL,'N;'),('DevicePlaylist.Index',0,NULL,NULL,'N;'),('DevicePlaylist.Update',0,NULL,NULL,'N;'),('DevicePlaylist.View',0,NULL,NULL,'N;'),('DeviceState.*',1,NULL,NULL,'N;'),('DeviceState.Admin',0,NULL,NULL,'N;'),('DeviceState.Create',0,NULL,NULL,'N;'),('DeviceState.Delete',0,NULL,NULL,'N;'),('DeviceState.Index',0,NULL,NULL,'N;'),('DeviceState.Update',0,NULL,NULL,'N;'),('DeviceState.View',0,NULL,NULL,'N;'),('Guest',2,'访客','return Yii::app()->user->isGuest;',NULL),('Manage',2,'管理员组','return !Yii::app()->user->isGuest;','N;'),('Media.*',1,NULL,NULL,'N;'),('Media.Admin',0,NULL,NULL,'N;'),('Media.Create',0,NULL,NULL,'N;'),('Media.Delete',0,NULL,NULL,'N;'),('Media.Index',0,NULL,NULL,'N;'),('Media.Update',0,NULL,NULL,'N;'),('Media.View',0,NULL,NULL,'N;'),('Member',2,'操作员组','return !Yii::app()->user->isGuest;','N;'),('PlatformUser.*',1,NULL,NULL,'N;'),('PlatformUser.Admin',0,NULL,NULL,'N;'),('PlatformUser.Create',0,NULL,NULL,'N;'),('PlatformUser.Delete',0,NULL,NULL,'N;'),('PlatformUser.Index',0,NULL,NULL,'N;'),('PlatformUser.Update',0,NULL,NULL,'N;'),('PlatformUser.View',0,NULL,NULL,'N;'),('Room.*',1,NULL,NULL,'N;'),('Room.Admin',0,NULL,NULL,'N;'),('Room.Checkin',0,NULL,NULL,'N;'),('Room.Create',0,NULL,NULL,'N;'),('Room.Delete',0,NULL,NULL,'N;'),('Room.Index',0,NULL,NULL,'N;'),('Room.Reset',0,NULL,NULL,'N;'),('Room.Update',0,NULL,NULL,'N;'),('Room.View',0,NULL,NULL,'N;'),('RoomBooking.*',1,NULL,NULL,'N;'),('RoomBooking.Admin',0,NULL,NULL,'N;'),('RoomBooking.Create',0,NULL,NULL,'N;'),('RoomBooking.Delete',0,NULL,NULL,'N;'),('RoomBooking.Index',0,NULL,NULL,'N;'),('RoomBooking.Update',0,NULL,NULL,'N;'),('RoomBooking.View',0,NULL,NULL,'N;'),('Super',2,'超级用户',NULL,NULL);
/*!40000 ALTER TABLE `ak_auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ak_auth_item_child`
--

DROP TABLE IF EXISTS `ak_auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ak_auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `fk_auth_item_child_child` (`child`),
  CONSTRAINT `fk_auth_item_child_child` FOREIGN KEY (`child`) REFERENCES `ak_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_auth_item_child_parent` FOREIGN KEY (`parent`) REFERENCES `ak_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ak_auth_item_child`
--

LOCK TABLES `ak_auth_item_child` WRITE;
/*!40000 ALTER TABLE `ak_auth_item_child` DISABLE KEYS */;
INSERT INTO `ak_auth_item_child` VALUES ('Member','CheckinCode.Index'),('Member','CheckinUser.Index'),('Member','PlatformUser.Index'),('Member','Room.Checkin'),('Member','Room.Index'),('Member','Room.Reset'),('Member','RoomBooking.Delete'),('Member','RoomBooking.Index'),('Member','RoomBooking.View');
/*!40000 ALTER TABLE `ak_auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ak_auth_assignment`
--

DROP TABLE IF EXISTS `ak_auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ak_auth_assignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` int(11) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  KEY `fk_auth_assignment_userid` (`userid`),
  CONSTRAINT `fk_auth_assignment_itemname` FOREIGN KEY (`itemname`) REFERENCES `ak_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_auth_assignment_userid` FOREIGN KEY (`userid`) REFERENCES `ak_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ak_auth_assignment`
--

LOCK TABLES `ak_auth_assignment` WRITE;
/*!40000 ALTER TABLE `ak_auth_assignment` DISABLE KEYS */;
INSERT INTO `ak_auth_assignment` VALUES ('Member',2,NULL,'N;'),('Super',1,NULL,NULL);
/*!40000 ALTER TABLE `ak_auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ak_rights`
--

DROP TABLE IF EXISTS `ak_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ak_rights` (
  `itemname` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`itemname`),
  CONSTRAINT `fk_auth_rights_itemname` FOREIGN KEY (`itemname`) REFERENCES `ak_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ak_rights`
--

LOCK TABLES `ak_rights` WRITE;
/*!40000 ALTER TABLE `ak_rights` DISABLE KEYS */;
/*!40000 ALTER TABLE `ak_rights` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-06-26 10:51:48
