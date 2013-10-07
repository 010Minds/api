-- MySQL dump 10.13  Distrib 5.5.32, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: mydb
-- ------------------------------------------------------
-- Server version	5.5.32-0ubuntu0.12.10.1

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
-- Table structure for table `operation`
--

DROP TABLE IF EXISTS `operation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `qtd` varchar(45) NOT NULL,
  `value` varchar(45) NOT NULL,
  `type` varchar(1) NOT NULL,
  `action` varchar(8) NOT NULL COMMENT 'pending, rejected, accepted',
  `create_date` timestamp NULL DEFAULT NULL,
  `accepted_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operation`
--

LOCK TABLES `operation` WRITE;
/*!40000 ALTER TABLE `operation` DISABLE KEYS */;
/*!40000 ALTER TABLE `operation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock`
--

DROP TABLE IF EXISTS `stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `sigla` varchar(45) NOT NULL,
  `current` varchar(45) DEFAULT NULL,
  `open` varchar(45) DEFAULT NULL,
  `high` varchar(45) DEFAULT NULL,
  `low` varchar(45) DEFAULT NULL,
  `percent` varchar(45) DEFAULT NULL,
  `url` varchar(1000) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `stock_exchange_id` int(11) NOT NULL,
  `volume` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_exchange_id_idx` (`stock_exchange_id`),
  CONSTRAINT `stock_exchange_id` FOREIGN KEY (`stock_exchange_id`) REFERENCES `stock_exchange` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock`
--

LOCK TABLES `stock` WRITE;
/*!40000 ALTER TABLE `stock` DISABLE KEYS */;
INSERT INTO `stock` VALUES (1,'Facebook, Inc','FB','24,86','24,66','24,98','24,42','0,79','http://www.nasdaq.com/symbol/fb/real-time','1','2013-06-29 20:38:38',1,'177.612.478'),(2,'Apple, Inc','AAPL','397,08','393,78','400,27','388,88','0,84','http://www.nasdaq.com/symbol/aapl/real-time','1','2013-06-29 20:38:45',1,'21.018.958'),(3,'Amazon.com, Inc','AMZN','277,64','277,55','279,83','276,12','0,03','http://www.nasdaq.com/symbol/amzn/real-time','1','2013-06-29 20:38:51',1,'3.083.750'),(4,'Google Inc','GOOG','880,21','877,07','881,84','874,03','0,36','http://www.nasdaq.com/symbol/goog/real-time','1','2013-06-29 20:38:55',1,'2.302.665'),(5,'Petrobras','PETR4','16,17','16,35','16,35','15,94','-1,28','http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?CodigoPapel=PETR4','2','2013-06-29 20:39:00',2,'0'),(7,'ALL AMERICA LATINA LOGISTICA S.A.','ALLL3','9,47','9,47','9,53','9,25','-1,13','http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?CodigoPapel=ALLL3','2','2013-06-29 20:39:03',2,'0'),(8,'ANHANGUERA EDUCACIONAL PARTICIPACOES S.A','AEDU3','12,80','13,11','13,51','12,80','-2,36','http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?CodigoPapel=AEDU3','2','2013-06-29 20:39:05',2,'0'),(9,'AREZZO INDÚSTRIA E COMÉRCIO S.A.','ARZZ3','33,95','34,38','34,90','33,26','-2,16','http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?CodigoPapel=ARZZ3','2','2013-06-29 20:39:08',2,'0'),(10,'B2W - COMPANHIA DIGITAL','BTOW3','6,64','7,09','7,14','6,55','-6,47','http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?CodigoPapel=BTOW3','2','2013-06-29 20:39:10',2,'0'),(11,'BCO BRADESCO S.A.','BBDC3','30,60','30,07','30,80','29,85','1,52','http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?CodigoPapel=BBDC3','2','2013-06-29 20:39:13',2,'0'),(12,'BCO BRASIL S.A.','BBAS3','22,06','21,50','22,48','21,18','2,50','http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?CodigoPapel=BBAS3','2','2013-06-29 20:39:15',2,'0'),(13,'BCO ESTADO DO RIO GRANDE DO SUL S.A.','BRSR3','13','0,00','0,00','0,00','0,00','http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?CodigoPapel=BRSR3','2','2013-06-29 20:39:18',2,'0'),(14,'BCO SANTANDER (BRASIL) S.A.','SANB3','0,12','0,13','0,14','0,12','-7,69','http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?CodigoPapel=SANB3','2','2013-06-29 20:39:20',2,'0'),(15,'BEMATECH S.A.','BEMA3','7,15','7,41','7,44','7,15','-3,50','http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?CodigoPapel=BEMA3','2','2013-06-29 20:39:22',2,'0'),(16,'BR MALLS PARTICIPACOES S.A.','BRML3','19,89','19,82','20,00','19,71','-0,35','http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?CodigoPapel=BRML3','2','2013-06-29 20:39:27',2,'0'),(17,'BRASIL BROKERS PARTICIPACOES S.A.','BBRK3','6,57','6,63','6,68','6,43','-0,90','http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?CodigoPapel=BBRK3','2','2013-06-29 20:39:29',2,'0'),(18,'CCR S.A.','CCRO3','17,69','17,79','18,07','17,39','0,11','http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?CodigoPapel=CCRO3','2','2013-06-29 20:39:32',2,'0'),(19,'CIA HERING','HGTX3','31,24','32,40','32,50','31,24','-3,49','http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?CodigoPapel=HGTX3','2','2013-06-29 20:39:35',2,'0'),(20,'CIA PARANAENSE DE ENERGIA - COPEL','CPLE3','20,95','21,11','21,96','20,95','0,04','http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?CodigoPapel=CPLE3','2','2013-06-29 20:39:38',2,'0'),(21,'CIELO S.A.','CIEL3','55,91','54,40','56,05','54,36','1,83','http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?CodigoPapel=CIEL3','2','2013-06-29 20:39:41',2,'0'),(22,'COSAN LIMITED','CZLT11','36,60','36,70','36,79','35,65','1,15','http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?CodigoPapel=CZLT11','2','2013-06-29 20:39:46',2,'0'),(26,'DOLAR','U$','2,23','2.2142','','','','https://www.google.com/finance/converter?a=1&from=USD&to=BRL&meta=ei%3DZK3JUeihIYOSlwPMvgE','3','2013-06-29 20:39:51',3,'');
/*!40000 ALTER TABLE `stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_exchange`
--

DROP TABLE IF EXISTS `stock_exchange`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_exchange` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_exchange`
--

LOCK TABLES `stock_exchange` WRITE;
/*!40000 ALTER TABLE `stock_exchange` DISABLE KEYS */;
INSERT INTO `stock_exchange` VALUES (1,'Nasdaq'),(2,'Bovespa'),(3,'Dollar');
/*!40000 ALTER TABLE `stock_exchange` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(45) NOT NULL,
  `user` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `reais` decimal(19,3) NOT NULL DEFAULT '10000.000',
  `dollars` decimal(19,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'thiagovictorino@gmail.com','victorino','123456','Thiago',10000.000,0.000),(2,'teste@gmail.com.br','teste','123456','Teste',1212.000,454545.000),(7,'eee@gmail.com','teste','1234587','Zaqueu',45.000,455.000),(8,'teste@gmail.com','teste','123456','Teste',1212.000,454545.000),(9,'teste@gmail.com','teste','123456','Teste',1212.000,454545.000);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_stock`
--

DROP TABLE IF EXISTS `user_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_stock` (
  `user_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `qtd` varchar(45) NOT NULL,
  `value` varchar(45) DEFAULT NULL,
  `create_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`,`stock_id`),
  KEY `user_stock_FK1_idx` (`stock_id`),
  KEY `user_stock_FK2_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_stock`
--

LOCK TABLES `user_stock` WRITE;
/*!40000 ALTER TABLE `user_stock` DISABLE KEYS */;
INSERT INTO `user_stock` VALUES (1,1,'10','104','0000-00-00 00:00:00'),(2,2,'20','100','0000-00-00 00:00:00'),(3,1,'15','105','0000-00-00 00:00:00'),(3,2,'52','99','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `user_stock` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-10-07 10:02:27
