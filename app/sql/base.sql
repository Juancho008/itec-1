/*
SQLyog Community v13.1.7 (64 bit)
MySQL - 5.6.51-log : Database - automatizaciones
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*Table structure for table `auditoria_general` */

DROP TABLE IF EXISTS `auditoria_general`;

CREATE TABLE `auditoria_general` (
  `id_auditoria` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `accion` varchar(100) DEFAULT NULL,
  `tabla` varchar(100) DEFAULT NULL,
  `id_registro` int(11) DEFAULT NULL,
  `atributo` varchar(100) DEFAULT NULL,
  `valor_anterior` varchar(1000) DEFAULT NULL,
  `valor_actual` varchar(1000) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_auditoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `auditoria_general` */

/*Table structure for table `menu_grupos` */

DROP TABLE IF EXISTS `menu_grupos`;

CREATE TABLE `menu_grupos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  `img` varchar(100) DEFAULT NULL,
  `id_grupo_sup` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `menu_grupos` */

insert  into `menu_grupos`(`id`,`nombre`,`orden`,`img`,`id_grupo_sup`) values 
(1,'Administracion',500,NULL,0);

/*Table structure for table `menu_items` */

DROP TABLE IF EXISTS `menu_items`;

CREATE TABLE `menu_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(150) DEFAULT NULL,
  `nombre` varchar(200) DEFAULT NULL,
  `id_grupo` int(11) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  `img` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `menu_items` */

insert  into `menu_items`(`id`,`url`,`nombre`,`id_grupo`,`orden`,`img`) values 
(1,'Home','Inicio',0,1,NULL),
(2,'Usuarios','Usuario',1,70,NULL),
(3,'Perfiles','Perfiles',1,80,NULL),
(4,'MenuGrupos','Grupos del Menu',1,90,NULL),
(5,'MenuItems','Items del manu',1,100,NULL);

/*Table structure for table `parametros` */

DROP TABLE IF EXISTS `parametros`;

CREATE TABLE `parametros` (
  `clave` varchar(20) DEFAULT NULL,
  `valor` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `parametros` */

insert  into `parametros`(`clave`,`valor`) values 
('id_perfil_admin','1'),
('planilla_nueva_id','7');

/*Table structure for table `perfiles_permisos` */

DROP TABLE IF EXISTS `perfiles_permisos`;

CREATE TABLE `perfiles_permisos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_perfil` int(11) DEFAULT NULL,
  `controlador` varchar(100) DEFAULT NULL,
  `permisos` varchar(10) DEFAULT NULL,
  `creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `perfiles_permisos` */

insert  into `perfiles_permisos`(`id`,`id_perfil`,`controlador`,`permisos`,`creado`) values 
(1,2,'Home','L','2021-04-13 15:58:47'),
(2,2,'Usuarios','ABML','2021-04-13 15:58:55');

/*Table structure for table `perfiles_usuarios` */

DROP TABLE IF EXISTS `perfiles_usuarios`;

CREATE TABLE `perfiles_usuarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` varchar(400) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `perfiles_usuarios` */

insert  into `perfiles_usuarios`(`id`,`nombre`,`descripcion`,`estado`) values 
(1,'Administrador',NULL,1),
(2,'Usuario',NULL,1);

/*Table structure for table `permisos_perfiles` */

DROP TABLE IF EXISTS `permisos_perfiles`;

CREATE TABLE `permisos_perfiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_perfil` int(11) DEFAULT NULL,
  `id_menu_item` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `permisos_perfiles` */

insert  into `permisos_perfiles`(`id`,`id_perfil`,`id_menu_item`) values 
(1,2,1),
(2,2,2);

/*Table structure for table `permisos_usuarios` */

DROP TABLE IF EXISTS `permisos_usuarios`;

CREATE TABLE `permisos_usuarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_menu_item` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `permisos_usuarios` */

/*Table structure for table `usuarios` */

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `id_perfil` int(11) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `clave` varchar(300) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `hora_ultimo_acceso` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cantidad_acceso` int(10) unsigned DEFAULT NULL,
  `verificacion` varchar(300) DEFAULT NULL,
  `estado` int(11) DEFAULT '0',
  `imagen` varchar(300) DEFAULT NULL,
  `dni` int(11) DEFAULT NULL,
  `id_municipio` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
