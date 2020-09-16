/*
SQLyog Ultimate v10.3 
MySQL - 5.5.5-10.1.40-MariaDB : Database - kardex1
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`kardex1` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish2_ci */;

USE `kardex1`;

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
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Table structure for table `kar_producto` */

DROP TABLE IF EXISTS `kar_producto`;

CREATE TABLE `kar_producto` (
  `IDPRODUCTO` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `NOMBRE` char(150) COLLATE utf8_spanish2_ci NOT NULL,
  `ESTADO` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '1' COMMENT '1 SI / 0 NO',
  `IDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `FECHACREACION` datetime DEFAULT CURRENT_TIMESTAMP,
  `IDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `FECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`IDPRODUCTO`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Table structure for table `kar_producto_detalle` */

DROP TABLE IF EXISTS `kar_producto_detalle`;

CREATE TABLE `kar_producto_detalle` (
  `IDPRODUCTODETALLE` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `IDPRODUCTO` int(4) unsigned zerofill NOT NULL,
  `MARCA` char(100) COLLATE utf8_spanish2_ci NOT NULL,
  `MODELO` char(100) COLLATE utf8_spanish2_ci NOT NULL,
  `DESCRIPCION` mediumtext COLLATE utf8_spanish2_ci,
  `ESTADO` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '1',
  `IDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `FECHACREACION` datetime DEFAULT CURRENT_TIMESTAMP,
  `IDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `FECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`IDPRODUCTODETALLE`),
  KEY `IDPRODUCTO` (`IDPRODUCTO`),
  CONSTRAINT `kar_producto_detalle_ibfk_1` FOREIGN KEY (`IDPRODUCTO`) REFERENCES `kar_producto` (`IDPRODUCTO`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Table structure for table `kar_stock` */

DROP TABLE IF EXISTS `kar_stock`;

CREATE TABLE `kar_stock` (
  `IDSTOCK` int(7) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `IDPRODUCTO` int(4) unsigned zerofill NOT NULL,
  `IDPRODUCTODETALLE` int(4) unsigned zerofill NOT NULL,
  `CANTIDAD` char(10) COLLATE utf8_spanish2_ci NOT NULL,
  `PRECIO_VENTA` char(15) COLLATE utf8_spanish2_ci NOT NULL,
  `INVERSION` char(15) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `OBSERVACION` mediumtext COLLATE utf8_spanish2_ci,
  `ESTADO` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '1',
  `IDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `FECHACREACION` datetime DEFAULT CURRENT_TIMESTAMP,
  `IDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `FECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`IDSTOCK`),
  KEY `IDPRODUCTO` (`IDPRODUCTO`),
  KEY `IDPRODUCTODETALLE` (`IDPRODUCTODETALLE`),
  CONSTRAINT `kar_stock_ibfk_1` FOREIGN KEY (`IDPRODUCTO`) REFERENCES `kar_producto` (`IDPRODUCTO`),
  CONSTRAINT `kar_stock_ibfk_2` FOREIGN KEY (`IDPRODUCTODETALLE`) REFERENCES `kar_producto_detalle` (`IDPRODUCTODETALLE`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

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

/*Table structure for table `kar_venta` */

DROP TABLE IF EXISTS `kar_venta`;

CREATE TABLE `kar_venta` (
  `IDVENTA` int(7) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `IDPRODUCTO` int(4) unsigned zerofill NOT NULL,
  `IDPRODUCTODETALLE` int(4) unsigned zerofill NOT NULL,
  `IDSTOCK` int(7) unsigned zerofill NOT NULL,
  `CANTIDAD` char(10) COLLATE utf8_spanish2_ci NOT NULL,
  `PRECIO_SUGERIDO` char(15) COLLATE utf8_spanish2_ci NOT NULL,
  `PRECIO_VENDIDO` char(15) COLLATE utf8_spanish2_ci NOT NULL,
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
  KEY `IDPRODUCTODETALLE` (`IDPRODUCTODETALLE`),
  KEY `IDSTOCK` (`IDSTOCK`),
  CONSTRAINT `kar_venta_ibfk_1` FOREIGN KEY (`IDPRODUCTO`) REFERENCES `kar_producto` (`IDPRODUCTO`),
  CONSTRAINT `kar_venta_ibfk_2` FOREIGN KEY (`IDPRODUCTODETALLE`) REFERENCES `kar_producto_detalle` (`IDPRODUCTODETALLE`),
  CONSTRAINT `kar_venta_ibfk_3` FOREIGN KEY (`IDSTOCK`) REFERENCES `kar_stock` (`IDSTOCK`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
