# ************************************************************
# Sequel Pro SQL dump
# Version 4500
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.22)
# Database: quiz
# Generation Time: 2016-02-09 09:38:17 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table quiz_answer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `quiz_answer`;

CREATE TABLE `quiz_answer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quiz_quiz_id` int(11) unsigned DEFAULT NULL,
  `quiz_question_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_foreignkey_quiz_answer_quiz_quiz` (`quiz_quiz_id`),
  KEY `index_foreignkey_quiz_answer_quiz_question` (`quiz_question_id`),
  CONSTRAINT `c_fk_quiz_answer_quiz_question_id` FOREIGN KEY (`quiz_question_id`) REFERENCES `quiz_question` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `c_fk_quiz_answer_quiz_quiz_id` FOREIGN KEY (`quiz_quiz_id`) REFERENCES `quiz_quiz` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `quiz_answer` WRITE;
/*!40000 ALTER TABLE `quiz_answer` DISABLE KEYS */;

INSERT INTO `quiz_answer` (`id`, `title`, `message`, `image`, `quiz_quiz_id`, `quiz_question_id`)
VALUES
	(1,'Han Solo','You have a strong and determined personality, but beneath your tough exterior you have a loving heart and an inner bravery that will help you through the tough times. You learn from your mistakes and always stay true to yourself!','A',1,1),
	(2,'Luke Skywalker','You\'re courageous, eager for adventure and an all-round hero. Sometimes you find it hard to control your emotions, but you always have the happiness and wellbeing of others at heart!','B',1,1),
	(3,'Princess Leia','You\'re level-headed, courageous, and with a sharp-tongued wit surpassed only by your beauty. You\'ve been through some tough times but always come out stronger in the end!','C',1,1),
	(4,'Chewbacca','You may look tough, and even scary to some people, but deep down you\'re a big softie. You\'re loyal, affectionate and humble - but if you think something isn\'t fair you\'re not afraid to say so! ','D',1,1),
	(5,'Finn','Despite what life has thrown at you, you have a good heart and true empathy for other people. You are brave, intelligent and with the strength to face down whatever life throws at you!','E',1,1),
	(6,'Rey','You have a heart full of generosity and a desire to help others, often putting their needs before your own. You have a great imagination that sets you apart from the majority of others!','F',1,1);

/*!40000 ALTER TABLE `quiz_answer` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table quiz_question
# ------------------------------------------------------------

DROP TABLE IF EXISTS `quiz_question`;

CREATE TABLE `quiz_question` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quiz_quiz_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_foreignkey_quiz_question_quiz_quiz` (`quiz_quiz_id`),
  CONSTRAINT `c_fk_quiz_question_quiz_quiz_id` FOREIGN KEY (`quiz_quiz_id`) REFERENCES `quiz_quiz` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `quiz_question` WRITE;
/*!40000 ALTER TABLE `quiz_question` DISABLE KEYS */;

INSERT INTO `quiz_question` (`id`, `title`, `quiz_quiz_id`)
VALUES
	(1,'Which Star Wars Character Are You?!',1);

/*!40000 ALTER TABLE `quiz_question` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table quiz_quiz
# ------------------------------------------------------------

DROP TABLE IF EXISTS `quiz_quiz`;

CREATE TABLE `quiz_quiz` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `theme` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nextaction` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `quiz_quiz` WRITE;
/*!40000 ALTER TABLE `quiz_quiz` DISABLE KEYS */;

INSERT INTO `quiz_quiz` (`id`, `theme`, `nextaction`)
VALUES
	(1,'starwars','/');

/*!40000 ALTER TABLE `quiz_quiz` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table quiz_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `quiz_user`;

CREATE TABLE `quiz_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `createdate` datetime DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fbid` double DEFAULT NULL,
  `lastupdate` datetime DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,2) DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `quiz_user` WRITE;
/*!40000 ALTER TABLE `quiz_user` DISABLE KEYS */;


/*!40000 ALTER TABLE `quiz_user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
