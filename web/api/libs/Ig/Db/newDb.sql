SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

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

CREATE TABLE `function` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `group_id` varchar(100) DEFAULT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `function_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `function_group` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `function_group` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `key_value` (
  `id` varchar(100) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='generic key-value storage';

CREATE TABLE `lan_code` (
  `id` varchar(100) NOT NULL,
  `code` varchar(100) NOT NULL,
  `description` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='language code';

CREATE TABLE `login` (
  `id` varchar(100) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `login` datetime NOT NULL,
  `logout` datetime DEFAULT NULL COMMENT 'can force logout by server',
  `last_access` datetime NOT NULL,
  `remarks` text,
  `remember_me` int(11) NOT NULL DEFAULT '0',
  KEY `user_id` (`user_id`),
  CONSTRAINT `login_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `account` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User login Activity';

CREATE TABLE `log_schedule` (
  `id` varchar(100) NOT NULL,
  `func` varchar(250) NOT NULL COMMENT 'execute function name',
  `start` datetime NOT NULL,
  `end` datetime DEFAULT NULL,
  `status` int(11) NOT NULL COMMENT '1 is start, 2 is success, 3 is fail',
  `fail_msg` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='scheduler record log';

CREATE TABLE `role` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` int(11) NOT NULL COMMENT '1 is active, 2 is disabled',
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User Role';

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