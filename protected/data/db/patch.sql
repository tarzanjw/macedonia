
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
