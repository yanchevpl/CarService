/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.5.5-10.4.6-MariaDB : Database - carservice
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`carservice` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `carservice`;

/*Table structure for table `cars` */

DROP TABLE IF EXISTS `cars`;

CREATE TABLE `cars` (
  `id` bigint(255) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `make` varchar(500) NOT NULL,
  `model` varchar(500) NOT NULL,
  `make_year` varchar(100) NOT NULL,
  `comment` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cars_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `cars` */

insert  into `cars`(`id`,`user_id`,`make`,`model`,`make_year`,`comment`) values (1,2,'mercedes','benz','2021',NULL),(2,2,'mercedes','benz','2020',NULL),(3,2,'mercedes','c220','2003','няма'),(4,12,'VW','Golf4','2002','служебен');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2021_03_08_171132_create_role_user_table',1),(4,'2021_03_08_171132_create_roles_table',1),(5,'2021_03_08_171133_add_foreign_keys_to_role_user_table',1);

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `repair` */

DROP TABLE IF EXISTS `repair`;

CREATE TABLE `repair` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `serviceorder_id` bigint(255) NOT NULL,
  `operation` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'none',
  `part_name` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'none',
  `price` float NOT NULL DEFAULT 0,
  `labor` float NOT NULL DEFAULT 0,
  `comment` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`,`serviceorder_id`),
  KEY `serviceorder_id` (`serviceorder_id`),
  CONSTRAINT `repair_ibfk_1` FOREIGN KEY (`serviceorder_id`) REFERENCES `serviceorder` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `repair` */

insert  into `repair`(`id`,`serviceorder_id`,`operation`,`part_name`,`price`,`labor`,`comment`) values (1,6,'смяна','реле',35,10,'няма'),(2,7,'смяна','уплътнение',15,20,NULL),(3,7,'смяна','свещ',50,30,NULL),(4,8,'смяна','волан',300,120,NULL),(5,9,'смяна','карета',50,120,'няма'),(6,8,'смяна','масло 5w40',120,1,'43242');

/*Table structure for table `role_user` */

DROP TABLE IF EXISTS `role_user`;

CREATE TABLE `role_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user_id`),
  KEY `role_user_role_id_foreign` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `role_user` */

insert  into `role_user`(`id`,`role_id`,`user_id`) values (1,1,1),(2,3,2),(3,3,3),(4,3,4),(5,3,5),(6,3,6),(7,3,7),(8,3,8),(12,2,12);

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`description`,`created_at`,`updated_at`) values (1,'admin','Администратор',1615224308,1615224308),(2,'employee','Служител',1615224308,1615224308),(3,'customer','Клиент',1615224308,1615224308);

/*Table structure for table `serviceorder` */

DROP TABLE IF EXISTS `serviceorder`;

CREATE TABLE `serviceorder` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `car_id` bigint(255) unsigned NOT NULL,
  `trouble` varchar(500) DEFAULT NULL,
  `status` int(255) DEFAULT NULL,
  `schedule` varchar(500) DEFAULT NULL,
  `note` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `car_id` (`car_id`,`status`,`schedule`),
  KEY `status` (`status`),
  CONSTRAINT `serviceorder_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `serviceorder_ibfk_2` FOREIGN KEY (`status`) REFERENCES `servicestatus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `serviceorder` */

insert  into `serviceorder`(`id`,`car_id`,`trouble`,`status`,`schedule`,`note`) values (6,3,'светлини',3,'2021-03-12',NULL),(7,1,'дюзи',3,'2021-03-13',NULL),(8,3,'dasdas',2,'2021-03-14','dadasdas'),(9,4,'предницата хлопа',3,'2021-03-15','автомобила е готов !'),(10,1,'fdsfsd',3,'2021-03-20',NULL);

/*Table structure for table `servicestatus` */

DROP TABLE IF EXISTS `servicestatus`;

CREATE TABLE `servicestatus` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `servicestatus` */

insert  into `servicestatus`(`id`,`title`) values (1,'В изчакване'),(2,'В ремонт'),(3,'Приключен ремонт');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`phone`,`password`,`remember_token`,`created_at`,`updated_at`) values (1,'Administrator','admin@admin.com',NULL,NULL,'$2y$10$hEgeU3uSJxjp8eZNgUF0O.1FdO65zCPUR2utQ4au5Pc/24.MxNGXm',NULL,NULL,NULL),(2,'Пламен Янков','plamen_ya@mail.bg',NULL,NULL,'$2y$10$LnucxQMGijR/7j/t6pmWhuSl3NJMP8U9MNjzHIa/C3FaCXtgNX612',NULL,'2021-03-08 17:42:56','2021-03-08 17:42:56'),(3,'Иван Иванов','dnsreg@gbg.bg',NULL,'08896776083','$2y$10$t5ONPBTMgRnzyLjJCJtJMOXIYnIAFvXVK/CwC9ggxnWAkvqm7THRS',NULL,'2021-03-11 16:46:29','2021-03-11 16:46:29'),(4,'Атанас','aaa@abv.bg',NULL,'0896776083','$2y$10$BBzXIrPfJ3ySu2PvEI7nuuw8/AWYxHOMo8a9cXFt2SJ3aMdj2EnOa',NULL,'2021-03-11 16:55:00','2021-03-11 16:55:00'),(5,'Атанас','atanas@abv.bg',NULL,'0896776083','$2y$10$90pmniFSIf3IjFMxQwL.9ux.OAqTqNUkOHKvzWeekGG0OQ04bLHs2',NULL,'2021-03-11 16:57:35','2021-03-11 16:57:35'),(6,'Иван','ivan@abv.bg',NULL,'0896776083','$2y$10$OSvxR5PQcCP24j4GLCnMaOtB03JXKWrNgfWWGJTa3pWdKCw/vB4DK',NULL,'2021-03-11 17:01:32','2021-03-11 17:01:32'),(7,'Ивелин','ivo@abv.bg',NULL,'0896776083','$2y$10$p4vu2Ht8siIHSUO1aQ5XvOWfPdO9apZmjBg5Z476414AmLQGBMH36',NULL,'2021-03-11 17:02:14','2021-03-11 17:02:14'),(8,'dsadsa','dasdas@dasdas.com',NULL,'0896776083','$2y$10$1ZHt/0M1j7FBYUUdVSuPgOw9o2PmQqE6jCWjXn5YGeYt.q8asQB2q',NULL,'2021-03-11 17:03:13','2021-03-11 17:03:13'),(12,'ivan','ivan@gmail.com',NULL,'08896776083','$2y$10$iZSu2eYdzvmmjmTa1Pf9LeVnlm2KEHMIcj4h//CUDXRGZMl.h00wS',NULL,'2021-03-11 17:07:22','2021-03-11 17:07:22');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
