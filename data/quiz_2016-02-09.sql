-- phpMyAdmin SQL Dump
-- version 3.3.10.4
-- http://www.phpmyadmin.net
--
-- Generato il: 15 feb, 2016 at 03:01 AM
-- Versione MySQL: 5.6.25
-- Versione PHP: 5.5.26

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `playgroundquiz`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `quiz_answer`
--

CREATE TABLE IF NOT EXISTS `quiz_answer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sharemessage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quiz_quiz_id` int(11) unsigned DEFAULT NULL,
  `quiz_question_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_foreignkey_quiz_answer_quiz_quiz` (`quiz_quiz_id`),
  KEY `index_foreignkey_quiz_answer_quiz_question` (`quiz_question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dump dei dati per la tabella `quiz_answer`
--

INSERT INTO `quiz_answer` (`id`, `title`, `message`, `sharemessage`, `image`, `quiz_quiz_id`, `quiz_question_id`) VALUES
(1, 'Han Solo', 'You have a strong and determined personality, but beneath your tough exterior you have a loving heart and an inner bravery that will help you through the tough times. You learn from your mistakes and always stay true to yourself!', 'I have a strong and determined personality, but beneath my tough exterior I have a loving heart and an inner bravery that helps me through the tough times. I learn from my mistakes and always stay true to myself! Who would you be?', 'A', 1, 1),
(2, 'Luke Skywalker', 'You''re courageous, eager for adventure and an all-round hero. Sometimes you find it hard to control your emotions, but you always have the happiness and wellbeing of others at heart!', 'I''m courageous, eager for adventure and an all-round hero. Sometimes I find it hard to control my emotions, but I always have the happiness and wellbeing of others at heart! Who would you be?', 'B', 1, 1),
(3, 'Princess Leia', 'You''re level-headed, courageous, and with a sharp-tongued wit surpassed only by your beauty. You''ve been through some tough times but always come out stronger in the end!', 'I''m level-headed, courageous, and with a sharp-tongued wit surpassed only by my beauty. I''ve been through some tough times but always come out stronger in the end! Who would you be?', 'C', 1, 1),
(4, 'Chewbacca', 'You may look tough, and even scary to some people, but deep down you''re a big softie. You''re loyal, affectionate and humble - but if you think something isn''t fair you''re not afraid to say so! ', 'I may look tough, and even scary to some people, but deep down I''m a big softie. I''m loyal, affectionate and humble - but if I think something isn''t fair I''m not afraid to say so! Who would you be?', 'D', 1, 1),
(5, 'Finn', 'Despite what life has thrown at you, you have a good heart and true empathy for other people. You are brave, intelligent and with the strength to face down whatever life throws at you!', 'Despite what life has thrown at me, I have a good heart and true empathy for other people. I am brave, intelligent and with the strength to face down whatever life throws at me! Who would you be?', 'E', 1, 1),
(6, 'Rey', 'You have a heart full of generosity and a desire to help others, often putting their needs before your own. You have a great imagination that sets you apart from the majority of others!', 'I have a heart full of generosity and a desire to help others, often putting their needs before my own. I have a great imagination that sets me apart from the majority of others! Who would you be?', 'F', 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `quiz_question`
--

CREATE TABLE IF NOT EXISTS `quiz_question` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quiz_quiz_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_foreignkey_quiz_question_quiz_quiz` (`quiz_quiz_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `quiz_question`
--

INSERT INTO `quiz_question` (`id`, `title`, `quiz_quiz_id`) VALUES
(1, 'Which Star Wars Character Are You?!', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `quiz_quiz`
--

CREATE TABLE IF NOT EXISTS `quiz_quiz` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `theme` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nextaction` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `quiz_quiz`
--

INSERT INTO `quiz_quiz` (`id`, `theme`, `nextaction`) VALUES
(1, 'starwars', '/');

-- --------------------------------------------------------

--
-- Struttura della tabella `quiz_user`
--

CREATE TABLE IF NOT EXISTS `quiz_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `createdate` datetime DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fbid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastupdate` datetime DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `quiz_user`
--

INSERT INTO `quiz_user` (`id`, `createdate`, `name`, `email`, `gender`, `ip`, `fbid`, `lastupdate`, `city`, `country`, `latitude`, `longitude`) VALUES
(1, '2016-02-13 13:45:15', 'Matteo Monti', 'mmonti@gmail.com', 'male', '80.6.109.222', '10154491551609838', '2016-02-15 10:59:49', 'United Kingdom', 'GB', '', '');

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `quiz_answer`
--
ALTER TABLE `quiz_answer`
  ADD CONSTRAINT `c_fk_quiz_answer_quiz_question_id` FOREIGN KEY (`quiz_question_id`) REFERENCES `quiz_question` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `c_fk_quiz_answer_quiz_quiz_id` FOREIGN KEY (`quiz_quiz_id`) REFERENCES `quiz_quiz` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Limiti per la tabella `quiz_question`
--
ALTER TABLE `quiz_question`
  ADD CONSTRAINT `c_fk_quiz_question_quiz_quiz_id` FOREIGN KEY (`quiz_quiz_id`) REFERENCES `quiz_quiz` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;
