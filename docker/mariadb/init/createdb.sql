CREATE DATABASE IF NOT EXISTS `default` COLLATE 'utf8_general_ci' ;
GRANT ALL ON `default`.* TO 'default'@'%' IDENTIFIED BY 'secret';

FLUSH PRIVILEGES ;