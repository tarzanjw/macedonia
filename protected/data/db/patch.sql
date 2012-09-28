
USE `vgid`;


DROP TABLE IF EXISTS `secret_question`;

CREATE TABLE `secret_question` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question_content` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


insert  into `secret_question`(`id`,`question_content`) values (1,'Con chó nhà bạn tên gì'),(2,'Số điện thoại đầu tiên bạn sử dụng');


alter table acc_auth
  add column  `secret_answer_salt` varchar(32) DEFAULT NULL;


  DROP TABLE IF EXISTS otp_api_log;
CREATE TABLE otp_api_log (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  request_method VARCHAR(16) NOT NULL,
  request_url TEXT NOT NULL,
  response_code INT(11) NOT NULL DEFAULT 200,
  src_ip VARCHAR(16) NOT NULL,
  dst_ip VARCHAR(16) DEFAULT NULL,
  request_headers TEXT NOT NULL,
  response_headers TEXT DEFAULT NULL,
  request_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  duration FLOAT NOT NULL COMMENT 'in second',
  post_data TEXT DEFAULT NULL,
  response_data TEXT DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AVG_ROW_LENGTH = 1694
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Definition for table rest_api_log
--
DROP TABLE IF EXISTS rest_api_log;
CREATE TABLE rest_api_log (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  request_method VARCHAR(16) NOT NULL,
  request_url TEXT NOT NULL,
  response_code INT(11) NOT NULL DEFAULT 200,
  src_ip TINYTEXT NOT NULL,
  dst_ip TINYTEXT DEFAULT NULL,
  request_headers TEXT NOT NULL,
  response_headers TEXT DEFAULT NULL,
  request_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  duration FLOAT NOT NULL COMMENT 'in second',
  post_data TEXT DEFAULT NULL,
  response_data TEXT DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
CHARACTER SET utf8
COLLATE utf8_general_ci;