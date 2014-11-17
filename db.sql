-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `id` varchar(100) NOT NULL,
  `function_id` varchar(100) NOT NULL,
  `role_id` varchar(100) NOT NULL,
  `authorize` tinyint(1) NOT NULL,
  KEY `function_id` (`function_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `access_ibfk_1` FOREIGN KEY (`function_id`) REFERENCES `function` (`id`),
  CONSTRAINT `access_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='user authority record';

INSERT INTO `access` (`id`, `function_id`, `role_id`, `authorize`) VALUES
('A0000000001',	'A0000000001',	'A0000000001',	0),
('A0000000002',	'A0000000001',	'A0000000002',	1),
('A0000000003',	'A0000000001',	'A0000000003',	0),
('A0000000004',	'A0000000002',	'A0000000001',	0),
('A0000000005',	'A0000000002',	'A0000000002',	1),
('A0000000006',	'A0000000002',	'A0000000003',	0),
('A0000000007',	'A0000000003',	'A0000000001',	0),
('A0000000008',	'A0000000003',	'A0000000002',	1),
('A0000000009',	'A0000000003',	'A0000000003',	0),
('A0000000010',	'A0000000001',	'A0000000004',	0),
('A0000000011',	'A0000000002',	'A0000000004',	0),
('A0000000012',	'A0000000003',	'A0000000004',	0);

DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` int(11) NOT NULL COMMENT '1 is active, 2 is disabled',
  `role_id` varchar(100) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `attachment_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `username` (`username`),
  KEY `role_id` (`role_id`),
  KEY `attachment_id` (`attachment_id`),
  CONSTRAINT `account_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  CONSTRAINT `account_ibfk_2` FOREIGN KEY (`attachment_id`) REFERENCES `attachment` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User account';

INSERT INTO `account` (`id`, `name`, `status`, `role_id`, `username`, `password`, `attachment_id`) VALUES
('A0000000001',	'Anonymous',	1,	'A0000000001',	'anonymous',	'',	NULL),
('A0000000002',	'Admin',	1,	'A0000000002',	'admin',	'1q2w3e',	NULL),
('A0000000003',	'User',	1,	'A0000000003',	'user',	'123456789',	NULL);

DROP TABLE IF EXISTS `account_log`;
CREATE TABLE `account_log` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '1 is active, 2 is disabled',
  `role_id` varchar(100) DEFAULT NULL,
  `username` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `attachment_id` varchar(100) DEFAULT NULL,
  `created` datetime NOT NULL,
  `author` varchar(100) NOT NULL,
  `referred` varchar(100) NOT NULL,
  `crud` varchar(1) NOT NULL,
  `log` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User account log';


DROP TABLE IF EXISTS `attachment`;
CREATE TABLE `attachment` (
  `id` varchar(100) NOT NULL,
  `filename` varchar(250) NOT NULL COMMENT 'user posted file name',
  `filepath` varchar(250) NOT NULL COMMENT 'physical file name',
  `guid` varchar(100) NOT NULL COMMENT 'file unique indentifier',
  `checksum` varchar(100) DEFAULT NULL COMMENT 'file content checksum, optional',
  `mime` varchar(100) DEFAULT NULL COMMENT 'file MIME type',
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`guid`),
  UNIQUE KEY `filepath` (`filepath`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='file handling';


DROP TABLE IF EXISTS `email_queue`;
CREATE TABLE `email_queue` (
  `id` varchar(100) NOT NULL,
  `tos` text NOT NULL COMMENT 'to s',
  `ccs` text NOT NULL COMMENT 'cc s',
  `bccs` text NOT NULL COMMENT 'bcc s',
  `attchs` text NOT NULL COMMENT 'atachments',
  `subject` varchar(100) NOT NULL,
  `msg` text NOT NULL,
  `last_update` datetime NOT NULL,
  `status` int(11) NOT NULL COMMENT '1 is not start yet, 2 is success, 3 is fail',
  `attempt` int(11) NOT NULL COMMENT 'send email number of attempt',
  `error_msg` text COMMENT 'error message',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='email queue';


DROP TABLE IF EXISTS `function`;
CREATE TABLE `function` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `group_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `function_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `function_group` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `function` (`id`, `name`, `group_id`) VALUES
('A0000000001',	'manage user',	'A0000000001'),
('A0000000002',	'view user',	'A0000000001'),
('A0000000003',	'manage schedule',	'A0000000001');

DROP TABLE IF EXISTS `function_group`;
CREATE TABLE `function_group` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `function_group` (`id`, `name`) VALUES
('A0000000001',	'Admin');

DROP TABLE IF EXISTS `key_value`;
CREATE TABLE `key_value` (
  `id` varchar(100) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='generic key-value storage';

INSERT INTO `key_value` (`id`, `value`) VALUES
('update-ver',	'i:6;');

DROP TABLE IF EXISTS `lan_code`;
CREATE TABLE `lan_code` (
  `id` varchar(100) NOT NULL,
  `code` varchar(100) NOT NULL,
  `description` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='language code';

INSERT INTO `lan_code` (`id`, `code`, `description`) VALUES
('A0000000001',	'en',	'English');

DROP TABLE IF EXISTS `login`;
CREATE TABLE `login` (
  `id` varchar(100) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `login` datetime NOT NULL,
  `logout` datetime DEFAULT NULL COMMENT 'can force logout by server',
  `last_access` datetime NOT NULL,
  `remarks` text,
  KEY `user_id` (`user_id`),
  CONSTRAINT `login_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `account` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User login Activity';

INSERT INTO `login` (`id`, `user_id`, `session_id`, `login`, `logout`, `last_access`, `remarks`) VALUES
('A0000000001',	'A0000000002',	'hngunluvrh6lf4dia8448g4vj1',	'2014-11-03 19:39:03',	'2014-11-03 19:39:28',	'2014-11-03 19:39:28',	NULL),
('A0000000002',	'A0000000002',	'5kjomqamc71l60v0i62m0v1db5',	'2014-11-07 22:53:18',	'2014-11-07 22:56:08',	'2014-11-07 22:56:08',	NULL),
('A0000000003',	'A0000000002',	'5kjomqamc71l60v0i62m0v1db5',	'2014-11-07 22:58:28',	'2014-11-09 11:59:37',	'2014-11-09 09:37:44',	'force logout due to new client login with this username'),
('A0000000004',	'A0000000002',	'9l0qp3pa7va73pgj392idjq2l3',	'2014-11-09 11:59:37',	'2014-11-10 16:05:27',	'2014-11-09 11:59:46',	'force logout due to new client login with this username'),
('A0000000005',	'A0000000002',	'dlaj1a4eehv5tsnnvrqmao8513',	'2014-11-10 16:05:27',	'2014-11-12 15:11:30',	'2014-11-10 16:05:27',	'force logout due to new client login with this username'),
('A0000000006',	'A0000000002',	'tjb2f5ddujv0l1633qolomu3v3',	'2014-11-12 15:11:30',	NULL,	'2014-11-12 15:16:30',	NULL);

DROP TABLE IF EXISTS `log_schedule`;
CREATE TABLE `log_schedule` (
  `id` varchar(100) NOT NULL,
  `func` varchar(250) NOT NULL COMMENT 'execute function name',
  `start` datetime NOT NULL,
  `end` datetime DEFAULT NULL,
  `status` int(11) NOT NULL COMMENT '1 is start, 2 is success, 3 is fail',
  `fail_msg` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='scheduler record log';


DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` int(11) NOT NULL COMMENT '1 is active, 2 is disabled',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User Role';

INSERT INTO `role` (`id`, `name`, `status`) VALUES
('A0000000001',	'anonymous',	1),
('A0000000002',	'admin',	1),
('A0000000003',	'user',	1),
('A0000000004',	'manager',	1);

DROP TABLE IF EXISTS `schedule`;
CREATE TABLE `schedule` (
  `id` varchar(100) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `function_name` varchar(100) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `weekday` varchar(100) NOT NULL COMMENT '* or [0-9]',
  `month` varchar(100) NOT NULL COMMENT '* or [0-9]',
  `day` varchar(100) NOT NULL COMMENT '* or [0-9]',
  `hour` varchar(100) NOT NULL COMMENT '* or [0-9]',
  `minute` varchar(100) NOT NULL COMMENT '* or [0-9]',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1 is active, 2 is in-active',
  `record_opt` int(11) NOT NULL COMMENT 'record options. 1 is record fail, 2 is record all, 3 is no record',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='scheduler task';

INSERT INTO `schedule` (`id`, `class_name`, `function_name`, `description`, `weekday`, `month`, `day`, `hour`, `minute`, `status`, `record_opt`) VALUES
('A0000000001',	'EmailUtil',	'runQueue',	'send email',	'*',	'*',	'*',	'*',	'*/5',	1,	1),
('A0000000002',	'LoginUtil',	'checkLogin',	'check user login activitiy',	'*',	'*',	'*',	'*',	'*',	1,	1);

-- 2014-11-14 01:41:28