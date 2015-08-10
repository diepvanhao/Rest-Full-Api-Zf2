/*
SQLyog Ultimate v8.71 
MySQL - 5.6.17 : Database - zf2api
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


/*Table structure for table `log` */

DROP TABLE IF EXISTS `log`;

CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postId` int(11) DEFAULT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `log` */

/*Table structure for table `post` */

DROP TABLE IF EXISTS `post`;

CREATE TABLE `post` (
  `postId` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`postId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `post` */

/*Table structure for table `post_tag` */

DROP TABLE IF EXISTS `post_tag`;

CREATE TABLE `post_tag` (
  `postId` int(11) NOT NULL,
  `tagId` int(11) NOT NULL,
  PRIMARY KEY (`postId`,`tagId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `post_tag` */

/*Table structure for table `tag` */

DROP TABLE IF EXISTS `tag`;

CREATE TABLE `tag` (
  `tagId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`tagId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `tag` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
