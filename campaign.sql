-- MySQL dump 10.13  Distrib 5.7.10, for Win64 (x86_64)
--
-- Host: localhost    Database: campaign
-- ------------------------------------------------------
-- Server version	5.7.10-log

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
-- Table structure for table `campaign`
--

DROP TABLE IF EXISTS `campaign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '标题',
  `destination` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '目的地',
  `rendezvous` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '集合地',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '单价',
  `origin` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '来源',
  `totalNum` smallint(3) NOT NULL DEFAULT '0' COMMENT '活动人数',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  `beginTime` int(11) NOT NULL DEFAULT '0' COMMENT '活动开始时间',
  `endTime` int(11) NOT NULL DEFAULT '0' COMMENT '活动结束时间',
  `keywords` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '关键字，以|分割',
  `dayNum` smallint(6) NOT NULL DEFAULT '0' COMMENT '活动天数',
  `campType` varchar(50) COLLATE utf8_bin NOT NULL,
  `locationName` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '地点',
  `isStick` int(11) NOT NULL DEFAULT '0',
  `isDel` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 未删除  1 删除',
  PRIMARY KEY (`id`),
  KEY `stick_time` (`isStick`,`updateTime`),
  KEY `title` (`title`),
  KEY `locationName` (`locationName`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='活动表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaign`
--

LOCK TABLES `campaign` WRITE;
/*!40000 ALTER TABLE `campaign` DISABLE KEYS */;
INSERT INTO `campaign` VALUES (2,'你好','北京','上海',3.40,'真格',10,0,'2017-01-20 03:59:06',2017,2017,'',0,'0','北京市',0,0),(3,'你好','北京','上海',3.40,'真格',10,1484885062,'2017-01-20 04:04:22',1484179200,1485475200,'',15,'0','北京市',0,0),(4,'你好','北京','上海',3.40,'真格',10,1484887454,'2017-01-20 04:44:14',1484179200,1485475200,'',15,'0','北京市',0,0),(5,'你好','北京','上海',3.40,'真格',10,1484887516,'2017-01-20 04:45:16',1484179200,1485475200,'',15,'0','北京市',0,0),(6,'你好','北京','上海',3.40,'真格',10,1484887601,'2017-01-20 04:46:41',1484179200,1485475200,'',15,'0','北京市',0,0),(7,'你好','北京','上海',3.40,'真格',10,1484887609,'2017-01-20 04:46:49',1484179200,1485475200,'',15,'0','北京市',0,0),(8,'是多少','算是','地方',2.40,'222',2,1484888169,'2017-01-20 04:56:09',1484870400,1485475200,'',7,'0','北京市',0,0),(9,'是多少','算是','地方',2.40,'222',2,1484888182,'2017-01-20 04:56:22',1484870400,1485475200,'',7,'0','北京市',0,0),(10,'是多少','算是','地方',2.40,'222',2,1484888257,'2017-01-20 04:57:37',1484870400,1485475200,'',7,'0','北京市',0,0),(11,'是多少','算是','地方',2.40,'222',2,1484888299,'2017-01-20 04:58:19',1484870400,1485475200,'',7,'0','北京市',0,0),(12,'是多少','算是','地方',2.40,'222',2,1484888360,'2017-01-20 04:59:20',1484870400,1485475200,'',7,'0','北京市',0,0),(13,'是多少','算是','地方',2.40,'222',2,1484888780,'2017-01-20 05:06:21',1484870400,1485475200,'',7,'0','北京市',0,0),(14,'是多少','算是','地方',2.40,'222',2,1484888816,'2017-01-20 05:06:56',1484870400,1485475200,'',7,'0','北京市',0,0),(15,'是多少','算是','地方',2.40,'222',2,1484888831,'2017-01-20 05:07:11',1484870400,1485475200,'',7,'0','北京市',0,0),(16,'首都师大','速度','是多少',4.00,'是多少',2,1484888944,'2017-01-20 05:09:04',1484611200,1484956800,'',4,'0','北京市',0,1),(17,'这次不是测试','测试','测试',5.40,'测试',4,1484965061,'2017-01-21 02:17:41',1483574400,1485561600,'',23,'|1|2|3|','首都师大',0,0);
/*!40000 ALTER TABLE `campaign` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaign_content`
--

DROP TABLE IF EXISTS `campaign_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaign_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campId` int(11) NOT NULL DEFAULT '0' COMMENT '活动Id',
  `fieldName` varchar(200) COLLATE utf8_bin NOT NULL,
  `fieldTitle` varchar(200) COLLATE utf8_bin NOT NULL,
  `content` varchar(10000) COLLATE utf8_bin NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `campId` (`campId`,`fieldName`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='详细内容选项';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaign_content`
--

LOCK TABLES `campaign_content` WRITE;
/*!40000 ALTER TABLE `campaign_content` DISABLE KEYS */;
INSERT INTO `campaign_content` VALUES (1,1,'image','','20170120035906_523.jpg'),(2,1,'image','','20170120035906_283.jpg'),(3,1,'lineIntroductio','线路介绍','首都师大'),(4,1,'scheduling','行程安排','适当'),(5,1,'expenseExplanat','费用说明','适当是'),(6,1,'moreIntroductio','更多介绍','适当适当'),(7,3,'image','','20170120040422_154.jpg'),(8,3,'lineIntroductio','线路介绍','首都师大'),(9,3,'scheduling','行程安排','适当'),(10,3,'expenseExplanat','费用说明','适当是'),(11,3,'moreIntroductio','更多介绍','适当适当'),(12,4,'image','','20170120044414_491.jpg'),(13,4,'lineIntroductio','线路介绍','首都师大'),(14,4,'scheduling','行程安排','适当'),(15,4,'expenseExplanat','费用说明','适当是'),(16,4,'moreIntroductio','更多介绍','适当适当'),(17,5,'image','','20170120044516_814.jpg'),(18,5,'lineIntroductio','线路介绍','首都师大'),(19,5,'scheduling','行程安排','适当'),(20,5,'expenseExplanat','费用说明','适当是'),(21,5,'moreIntroductio','更多介绍','适当适当'),(22,6,'image','','20170120044641_164.jpg'),(23,6,'lineIntroductio','线路介绍','首都师大'),(24,6,'scheduling','行程安排','适当'),(25,6,'expenseExplanat','费用说明','适当是'),(26,6,'moreIntroductio','更多介绍','适当适当'),(27,7,'image','','20170120044649_271.jpg'),(28,7,'lineIntroductio','线路介绍','首都师大'),(29,7,'scheduling','行程安排','适当'),(30,7,'expenseExplanat','费用说明','适当是'),(31,7,'moreIntroductio','更多介绍','适当适当'),(32,8,'image','','20170120045609_532.jpg'),(33,8,'lineIntroductio','线路介绍','发达发达'),(34,8,'scheduling','行程安排','对方的身份'),(35,8,'expenseExplanat','费用说明','对方的身份'),(36,8,'moreIntroductio','更多介绍','双方的身份'),(37,9,'image','','20170120045622_164.jpg'),(38,9,'lineIntroductio','线路介绍','发达发达'),(39,9,'scheduling','行程安排','对方的身份'),(40,9,'expenseExplanat','费用说明','对方的身份'),(41,9,'moreIntroductio','更多介绍','双方的身份'),(42,10,'image','','20170120045737_448.jpg'),(43,10,'lineIntroductio','线路介绍','发达发达'),(44,10,'scheduling','行程安排','对方的身份'),(45,10,'expenseExplanat','费用说明','对方的身份'),(46,10,'moreIntroductio','更多介绍','双方的身份'),(47,11,'image','','20170120045819_487.jpg'),(48,11,'lineIntroductio','线路介绍','发达发达'),(49,11,'scheduling','行程安排','对方的身份'),(50,11,'expenseExplanat','费用说明','对方的身份'),(51,11,'moreIntroductio','更多介绍','双方的身份'),(52,12,'image','','20170120045920_488.jpg'),(53,12,'lineIntroductio','线路介绍','发达发达'),(54,12,'scheduling','行程安排','对方的身份'),(55,12,'expenseExplanat','费用说明','对方的身份'),(56,12,'moreIntroductio','更多介绍','双方的身份'),(57,13,'image','','20170120050620_191.jpg'),(58,13,'lineIntroductio','线路介绍','发达发达'),(59,13,'scheduling','行程安排','对方的身份'),(60,13,'expenseExplanat','费用说明','对方的身份'),(61,13,'moreIntroductio','更多介绍','双方的身份'),(62,14,'image','','20170120050656_762.jpg'),(63,14,'lineIntroductio','线路介绍','发达发达'),(64,14,'scheduling','行程安排','对方的身份'),(65,14,'expenseExplanat','费用说明','对方的身份'),(66,14,'moreIntroductio','更多介绍','双方的身份'),(67,15,'image','','20170120050711_355.jpg'),(68,15,'lineIntroductio','线路介绍','发达发达'),(69,15,'scheduling','行程安排','对方的身份'),(70,15,'expenseExplanat','费用说明','对方的身份'),(71,15,'moreIntroductio','更多介绍','双方的身份'),(72,16,'image','','20170120050904_844.jpg'),(73,16,'lineIntroductio','线路介绍','速度'),(74,16,'scheduling','行程安排','速度'),(75,16,'expenseExplanat','费用说明','是的'),(76,16,'moreIntroductio','更多介绍','速度'),(78,17,'lineIntroduction','线路介绍','就回家'),(79,17,'scheduling','行程安排','首都师大'),(80,17,'expenseExplanation','费用说明','时代的时尚'),(81,17,'moreIntroduction','更多介绍','啊大多数'),(82,17,'image','','20170121024750_744.jpg'),(83,17,'image','','20170121024750_125.jpg'),(84,17,'image','','20170121031237_910.jpg'),(85,17,'image','','20170121031237_655.jpg'),(86,17,'image','','20170121031237_159.jpg');
/*!40000 ALTER TABLE `campaign_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaign_order`
--

DROP TABLE IF EXISTS `campaign_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaign_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campId` int(11) NOT NULL DEFAULT '0' COMMENT '活动Id',
  `userId` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '用户uid',
  `num` tinyint(4) NOT NULL DEFAULT '0' COMMENT '购买数量',
  `mark` varchar(1000) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '留言',
  `phone` varchar(15) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '电话',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updateTime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '订单状态 1 付款完成  2  活动进行中  3  活动已结束',
  `evaluateStatus` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否评价',
  `userName` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `campTitle` varchar(1000) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `updateTime` (`updateTime`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='订单表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaign_order`
--

LOCK TABLES `campaign_order` WRITE;
/*!40000 ALTER TABLE `campaign_order` DISABLE KEYS */;
INSERT INTO `campaign_order` VALUES (1,17,'123',2,'sdsdsds','15210876969',0,0,0,0,'louzhiqiang',10.80,'这次不是测试');
/*!40000 ALTER TABLE `campaign_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaign_order_evaluate`
--

DROP TABLE IF EXISTS `campaign_order_evaluate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaign_order_evaluate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderId` int(11) NOT NULL DEFAULT '0' COMMENT '订单Id',
  `campId` int(11) NOT NULL DEFAULT '0' COMMENT '活动Id',
  `starLevel` tinyint(4) NOT NULL DEFAULT '0' COMMENT '星级',
  `content` varchar(10000) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '评价内容',
  `userId` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='评价详情表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaign_order_evaluate`
--

LOCK TABLES `campaign_order_evaluate` WRITE;
/*!40000 ALTER TABLE `campaign_order_evaluate` DISABLE KEYS */;
/*!40000 ALTER TABLE `campaign_order_evaluate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaign_search`
--

DROP TABLE IF EXISTS `campaign_search`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaign_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campId` int(11) NOT NULL DEFAULT '0' COMMENT '活动Id',
  `fieldName` varchar(15) COLLATE utf8_bin NOT NULL COMMENT '字段名称',
  `content` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fieldName_content` (`fieldName`,`content`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='详细内容选项';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaign_search`
--

LOCK TABLES `campaign_search` WRITE;
/*!40000 ALTER TABLE `campaign_search` DISABLE KEYS */;
INSERT INTO `campaign_search` VALUES (1,4,'campType','0'),(2,4,'campType','1'),(3,5,'campType','0'),(4,5,'campType','1'),(5,6,'campType','0'),(6,6,'campType','1'),(7,7,'campType','0'),(8,7,'campType','1'),(9,8,'campType','2'),(10,8,'campType','3'),(11,9,'campType','2'),(12,9,'campType','3'),(13,10,'campType','2'),(14,10,'campType','3'),(15,11,'campType','2'),(16,11,'campType','3'),(17,12,'campType','2'),(18,12,'campType','3'),(19,13,'campType','2'),(20,13,'campType','3'),(21,14,'campType','2'),(22,14,'campType','3'),(23,15,'campType','2'),(24,15,'campType','3'),(25,16,'campType','1'),(26,16,'campType','3'),(32,17,'campType','1'),(33,17,'campType','2'),(34,17,'campType','3');
/*!40000 ALTER TABLE `campaign_search` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaign_user`
--

DROP TABLE IF EXISTS `campaign_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaign_user` (
  `id` varchar(50) COLLATE utf8_bin NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  `photoUrl` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '头像',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `phone` varchar(15) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '电话',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaign_user`
--

LOCK TABLES `campaign_user` WRITE;
/*!40000 ALTER TABLE `campaign_user` DISABLE KEYS */;
INSERT INTO `campaign_user` VALUES ('','louzhiqiang','http://pic78.huitu.com/res/20160604/1029007_20160604114552332126_1.jpg',1485077526,'');
/*!40000 ALTER TABLE `campaign_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-01-23 15:24:16
