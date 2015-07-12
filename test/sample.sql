-- Adminer 4.2.0 MySQL dump

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `role_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `account` (`id`, `username`, `password`, `session`, `status`, `role_id`) VALUES
('A0000000001',	'user',	'123456789',	'ha45638bc2',	1,	'1'),
('A0000000002',	'admin',	'123456789',	'ga563ab68c',	1,	'2');

DROP TABLE IF EXISTS `email_queue`;
CREATE TABLE `email_queue` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL COMMENT '1 is pending, 2 is sent pass, 3 is sent fail',
  `fail_cnt` int(11) NOT NULL,
  `last_update` date NOT NULL,
  `guid` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `msg` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tos` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ccs` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `bccs` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attchs` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `email_queue` (`id`, `status`, `fail_cnt`, `last_update`, `guid`, `subject`, `msg`, `tos`, `ccs`, `bccs`, `attchs`) VALUES
('A0000000001',	2,	0,	'2015-06-30',	'5387g43a8f',	'testing',	'this is sample message',	'a:1:{s:3:\"qwe\";s:11:\"qwe@zxc.com\";}',	'N;',	'N;',	'N;'),
('A0000000002',	1,	0,	'2015-06-30',	'627g3a456b7c',	'Kelopok',	'Kelopok sangat sedap.... ',	'a:1:{s:3:\"asd\";s:11:\"asd@abc.com\";}',	'N;',	'N;',	'N;'),
('A0000000003',	1,	0,	'2015-07-01',	'68g23a76f',	'gogogo',	'....',	'a:1:{s:3:\"asd\";s:11:\"asd@abc.com\";}',	'N;',	'N;',	'N;'),
('A0000000004',	1,	0,	'2015-07-01',	'A0000000004',	'John mail',	'content.....',	'a:1:{s:4:\"john\";s:13:\"john@koko.net\";}',	'a:0:{}',	'a:0:{}',	'a:1:{s:17:\"sample attachment\";s:69:\"/Users/chingchetsiang/git/web-starter-kit/test/cases/email/sample.txt\";}');

DROP TABLE IF EXISTS `key_value`;
CREATE TABLE `key_value` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key_pair` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key_pair`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `key_value` (`id`, `key_pair`, `value`) VALUES
('A0000000001',	'secret',	's:9:\"zxcasdqwe\";');

DROP TABLE IF EXISTS `schedule`;
CREATE TABLE `schedule` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `function_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `month` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weekday` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hour` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `minute` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `schedule` (`id`, `class_name`, `function_name`, `description`, `status`, `month`, `day`, `weekday`, `hour`, `minute`) VALUES
('A0000000001',	'SchScheduleTest',	'foobar',	'test run dummy function, it will never able to run',	1,	'20',	'*',	'*/10',	'0',	'0'),
('A0000000002',	'SchScheduleTest',	'dupFile',	'duplicate sample file',	1,	'*',	'*',	'*',	'*',	'*'),
('A0000000003',	'SchScheduleTest',	'dupFile2',	'duplicate sample file',	1,	'*',	'*',	'*',	'*',	'*');

-- 2015-07-01 12:26:50