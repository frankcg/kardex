/*
SQLyog Ultimate v10.3 
MySQL - 5.5.5-10.1.40-MariaDB : Database - kardex
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`kardex` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish2_ci */;

USE `kardex`;

/*Table structure for table `kar_compra` */

DROP TABLE IF EXISTS `kar_compra`;

CREATE TABLE `kar_compra` (
  `IDCOMPRA` int(7) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `IDPRODUCTO` int(5) unsigned zerofill NOT NULL,
  `CANTIDAD` char(10) COLLATE utf8_spanish2_ci NOT NULL,
  `PRECIO_UNIDAD` char(15) COLLATE utf8_spanish2_ci NOT NULL COMMENT 'PRECIO X UNIDAD',
  `ALIAS` char(100) COLLATE utf8_spanish2_ci NOT NULL,
  `OBSERVACION` mediumtext COLLATE utf8_spanish2_ci,
  `FECHA_COMPRA` datetime NOT NULL,
  `ESTADO` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '1',
  `IDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `FECHACREACION` datetime DEFAULT CURRENT_TIMESTAMP,
  `IDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `FECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`IDCOMPRA`),
  KEY `IDPRODUCTO` (`IDPRODUCTO`),
  CONSTRAINT `kar_compra_ibfk_1` FOREIGN KEY (`IDPRODUCTO`) REFERENCES `kar_producto` (`IDPRODUCTO`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_compra` */

/*Table structure for table `kar_persona` */

DROP TABLE IF EXISTS `kar_persona`;

CREATE TABLE `kar_persona` (
  `IDPERSONA` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `NOMBRE` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `AP_PATERNO` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `AP_MATERNO` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `NUMERODOC` int(8) NOT NULL,
  `CORREO` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `TELEFONO` int(9) NOT NULL,
  `TIPOPERSONA` char(4) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'PERS' COMMENT 'PER PERSONAL / USER USUARIO',
  `ESTADO` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '1' COMMENT '1 SI / 0 NO',
  `IDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `FECHACREACION` datetime DEFAULT CURRENT_TIMESTAMP,
  `IDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `FECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`IDPERSONA`,`TIPOPERSONA`)
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_persona` */

insert  into `kar_persona`(`IDPERSONA`,`NOMBRE`,`AP_PATERNO`,`AP_MATERNO`,`NUMERODOC`,`CORREO`,`TELEFONO`,`TIPOPERSONA`,`ESTADO`,`IDUSUARIOCREACION`,`FECHACREACION`,`IDUSUARIOMOD`,`FECHAMOD`) values (0106,'FRANK','LAURA','BORJA',73191639,'FRANK.CG9@GMAIL.COM',993690057,'USER','1','FLAURA','2018-04-02 00:00:00','','2018-05-01 13:09:57'),(0196,'NOMBRE','PATERNO','MATERNO',10101010,'',0,'USER','1','FLAURA','2020-09-16 21:08:35',NULL,'0000-00-00 00:00:00');

/*Table structure for table `kar_producto` */

DROP TABLE IF EXISTS `kar_producto`;

CREATE TABLE `kar_producto` (
  `IDPRODUCTO` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `NOMBRE` char(150) COLLATE utf8_spanish2_ci NOT NULL,
  `ESTADO` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '1' COMMENT '1 SI / 0 NO',
  `IDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `FECHACREACION` datetime DEFAULT CURRENT_TIMESTAMP,
  `IDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `FECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`IDPRODUCTO`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_producto` */

/*Table structure for table `kar_usuario` */

DROP TABLE IF EXISTS `kar_usuario`;

CREATE TABLE `kar_usuario` (
  `IDUSUARIO` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `CONTRASENIA` char(50) COLLATE utf8_spanish2_ci NOT NULL,
  `ESTADO` int(1) NOT NULL DEFAULT '1' COMMENT '1 ACTIVO / 0 INACTIVO',
  `IDPERSONA` int(4) unsigned zerofill NOT NULL,
  `IDPERFIL` int(2) unsigned zerofill DEFAULT NULL,
  `IDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `FECHACREACION` datetime DEFAULT CURRENT_TIMESTAMP,
  `IDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `FECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`IDUSUARIO`),
  KEY `fk_idperfil` (`IDPERFIL`),
  KEY `IDPERSONA` (`IDPERSONA`),
  CONSTRAINT `kar_usuario_ibfk_1` FOREIGN KEY (`IDPERSONA`) REFERENCES `kar_persona` (`IDPERSONA`),
  CONSTRAINT `kar_usuario_ibfk_2` FOREIGN KEY (`IDPERFIL`) REFERENCES `seguridad_perfil` (`IDPERFIL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_usuario` */

insert  into `kar_usuario`(`IDUSUARIO`,`CONTRASENIA`,`ESTADO`,`IDPERSONA`,`IDPERFIL`,`IDUSUARIOCREACION`,`FECHACREACION`,`IDUSUARIOMOD`,`FECHAMOD`) values ('FLAURA','458f4c520e432987bb4851036aa393ae26c8cba6',1,0106,01,'ADMIN','2020-09-13 00:15:05','FLAURA','2020-09-13 00:26:31'),('VENDEDOR','f84ae4782a61dc97f19accb967656c3225743d3a',1,0196,05,'FLAURA','2020-09-16 21:08:35','FLAURA','2020-09-27 20:40:20');

/*Table structure for table `kar_venta` */

DROP TABLE IF EXISTS `kar_venta`;

CREATE TABLE `kar_venta` (
  `IDVENTA` int(7) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `IDPRODUCTO` int(5) unsigned zerofill NOT NULL,
  `IDCOMPRA` int(7) unsigned zerofill NOT NULL,
  `CANTIDAD` char(10) COLLATE utf8_spanish2_ci NOT NULL,
  `PRECIO_VENTA` char(15) COLLATE utf8_spanish2_ci NOT NULL,
  `FECHA_VENTA` datetime NOT NULL,
  `IDVENDEDOR` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `OBSERVACION` mediumtext COLLATE utf8_spanish2_ci NOT NULL,
  `ESTADO` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '1',
  `IDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `FECHACREACION` datetime DEFAULT CURRENT_TIMESTAMP,
  `IDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `FECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`IDVENTA`),
  KEY `IDPRODUCTO` (`IDPRODUCTO`),
  KEY `IDSTOCK` (`IDCOMPRA`),
  CONSTRAINT `kar_venta_ibfk_1` FOREIGN KEY (`IDPRODUCTO`) REFERENCES `kar_producto` (`IDPRODUCTO`),
  CONSTRAINT `kar_venta_ibfk_3` FOREIGN KEY (`IDCOMPRA`) REFERENCES `kar_compra` (`IDCOMPRA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_venta` */

/*Table structure for table `seguridad_modulo` */

DROP TABLE IF EXISTS `seguridad_modulo`;

CREATE TABLE `seguridad_modulo` (
  `IDMODULO` int(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `NOMBRE_MODULO` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `DESCRIPCION` char(50) COLLATE utf8_spanish2_ci NOT NULL,
  `TIPO` char(20) COLLATE utf8_spanish2_ci NOT NULL,
  `UBICACION` char(50) COLLATE utf8_spanish2_ci NOT NULL,
  `FLAG` int(1) NOT NULL DEFAULT '1' COMMENT '1 -> ACTIVO / 0->INACTIVO',
  `FECHAMOD` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`IDMODULO`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `seguridad_modulo` */

insert  into `seguridad_modulo`(`IDMODULO`,`NOMBRE_MODULO`,`DESCRIPCION`,`TIPO`,`UBICACION`,`FLAG`,`FECHAMOD`) values (01,'MENU_PANEL','Inicio','MENU_PAN','panel',1,'2018-04-03 16:59:26'),(02,'MENU_SEGURIDAD','Control de Acceso','MENU_SEG','usuario',1,'2018-04-03 16:50:55'),(10,'MENU_COMPRA','Compra de Producto','MENU_COM','compra',1,'2020-09-25 22:46:55'),(11,'MENU_CATALOGO','Catalogo de Productos','MENU_PRO','catalogo',1,'2020-09-13 13:32:40'),(12,'MENU_VENTAS','Registro de Ventas','MENU_VEN','venta',1,'2020-09-13 19:54:45'),(13,'MENU_REPORTES','Reportes','MENU_REP','reporte',1,'2020-09-13 01:06:26');

/*Table structure for table `seguridad_modulo_perfil` */

DROP TABLE IF EXISTS `seguridad_modulo_perfil`;

CREATE TABLE `seguridad_modulo_perfil` (
  `IDDETALLE` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `IDMODULO` int(2) unsigned zerofill NOT NULL,
  `IDPERFIL` int(2) unsigned zerofill NOT NULL,
  `PERMISO` int(1) NOT NULL,
  `FECHAMOD` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`IDDETALLE`),
  KEY `seguridad_modulo_perfil_ibfk_1` (`IDMODULO`),
  KEY `seguridad_modulo_perfil_ibfk_2` (`IDPERFIL`),
  CONSTRAINT `seguridad_modulo_perfil_ibfk_1` FOREIGN KEY (`IDMODULO`) REFERENCES `seguridad_modulo` (`IDMODULO`),
  CONSTRAINT `seguridad_modulo_perfil_ibfk_2` FOREIGN KEY (`IDPERFIL`) REFERENCES `seguridad_perfil` (`IDPERFIL`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8;

/*Data for the table `seguridad_modulo_perfil` */

insert  into `seguridad_modulo_perfil`(`IDDETALLE`,`IDMODULO`,`IDPERFIL`,`PERMISO`,`FECHAMOD`) values (0097,01,01,0,'2020-09-15 22:46:24'),(0098,02,01,0,'2020-09-15 22:46:24'),(0099,10,01,0,'2020-09-15 22:46:24'),(0100,11,01,0,'2020-09-15 22:46:24'),(0101,12,01,0,'2020-09-15 22:46:24'),(0102,13,01,0,'2020-09-15 22:46:24'),(0105,01,05,0,'2020-09-27 20:40:28'),(0106,11,05,0,'2020-09-27 20:40:28'),(0107,12,05,0,'2020-09-27 20:40:28');

/*Table structure for table `seguridad_perfil` */

DROP TABLE IF EXISTS `seguridad_perfil`;

CREATE TABLE `seguridad_perfil` (
  `IDPERFIL` int(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `NOMBRE_PERFIL` char(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `FLAG` int(1) NOT NULL DEFAULT '1',
  `IDUSUARIOCREACION` char(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `FECHACREACION` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IDUSUARIOMOD` char(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `FECHAMOD` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`IDPERFIL`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `seguridad_perfil` */

insert  into `seguridad_perfil`(`IDPERFIL`,`NOMBRE_PERFIL`,`FLAG`,`IDUSUARIOCREACION`,`FECHACREACION`,`IDUSUARIOMOD`,`FECHAMOD`) values (01,'ADMINISTRADOR',1,'','2018-04-05 17:32:48','FLAURA','2018-04-10 14:49:05'),(05,'VENDEDOR',1,'','2020-09-13 12:50:30','','2020-09-13 12:50:30');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
