/*
SQLyog Ultimate v10.3 
MySQL - 5.5.5-10.3.25-MariaDB-cll-lve : Database - fzbsokgg_tauro_kardex
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`fzbsokgg_tauro_kardex` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `fzbsokgg_tauro_kardex`;

/*Table structure for table `kar_compra` */

DROP TABLE IF EXISTS `kar_compra`;

CREATE TABLE `kar_compra` (
  `nIDCOMPRA` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nIDLOCAL` int(4) unsigned zerofill NOT NULL,
  `nIDPROVEEDOR` int(4) unsigned zerofill NOT NULL,
  `nESTADO` int(1) DEFAULT 1,
  `sOBSERVACION` mediumtext COLLATE utf8_spanish2_ci DEFAULT NULL,
  `dFECHACOMPRA` datetime DEFAULT NULL,
  `sIDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHACREACION` datetime DEFAULT current_timestamp(),
  `sIDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDCOMPRA`),
  KEY `nIDLOCAL` (`nIDLOCAL`),
  KEY `nIDPROVEEDOR` (`nIDPROVEEDOR`),
  CONSTRAINT `kar_compra_ibfk_1` FOREIGN KEY (`nIDLOCAL`) REFERENCES `sel_local` (`nIDLOCAL`),
  CONSTRAINT `kar_compra_ibfk_2` FOREIGN KEY (`nIDPROVEEDOR`) REFERENCES `sel_proveedor` (`nIDPROVEEDOR`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_compra` */

/*Table structure for table `kar_compra_anulacion` */

DROP TABLE IF EXISTS `kar_compra_anulacion`;

CREATE TABLE `kar_compra_anulacion` (
  `nIDANULACION` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nIDCOMPRA` int(10) unsigned zerofill NOT NULL,
  `sMOTIVO` mediumtext CHARACTER SET latin1 NOT NULL,
  `dFECHA_ANULACION` datetime NOT NULL,
  `nESTADO` int(1) NOT NULL DEFAULT 1,
  `sIDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHACREACION` datetime DEFAULT current_timestamp(),
  `sIDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDANULACION`),
  KEY `nIDCOMPRA` (`nIDCOMPRA`),
  CONSTRAINT `kar_compra_anulacion_ibfk_1` FOREIGN KEY (`nIDCOMPRA`) REFERENCES `kar_compra` (`nIDCOMPRA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_compra_anulacion` */

/*Table structure for table `kar_compra_detalle` */

DROP TABLE IF EXISTS `kar_compra_detalle`;

CREATE TABLE `kar_compra_detalle` (
  `nIDCOMPRADETALLE` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nIDCOMPRA` int(10) unsigned zerofill NOT NULL,
  `nIDPRODUCTO` int(5) unsigned zerofill NOT NULL,
  `nCANTIDAD` int(10) NOT NULL,
  `fPRECIO` float NOT NULL,
  `bSTOCK` int(1) NOT NULL DEFAULT 1 COMMENT '1 CON STOCK / 0 SIN STOCK',
  `nESTADO` int(1) NOT NULL DEFAULT 1,
  `sIDUSUARIOCREACION` char(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHACREACION` datetime DEFAULT current_timestamp(),
  `sIDUSUARIOMOD` char(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDCOMPRADETALLE`),
  KEY `PRODUCTO` (`nIDPRODUCTO`),
  KEY `nIDCOMPRA` (`nIDCOMPRA`),
  CONSTRAINT `kar_compra_detalle_ibfk_1` FOREIGN KEY (`nIDCOMPRA`) REFERENCES `kar_compra` (`nIDCOMPRA`),
  CONSTRAINT `kar_compra_detalle_ibfk_2` FOREIGN KEY (`nIDPRODUCTO`) REFERENCES `kar_producto` (`nIDPRODUCTO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `kar_compra_detalle` */

/*Table structure for table `kar_compra_pago` */

DROP TABLE IF EXISTS `kar_compra_pago`;

CREATE TABLE `kar_compra_pago` (
  `nIDCOMPRAPAGO` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nIDCOMPRA` int(10) unsigned zerofill NOT NULL,
  `nIDTIPOPAGO` int(2) unsigned zerofill NOT NULL,
  `fMONTO` float NOT NULL,
  `nIDCUENTA` int(4) unsigned zerofill NOT NULL,
  `sOBSERVACION` mediumtext COLLATE utf8_spanish2_ci DEFAULT NULL,
  `dFECHAPAGO` datetime DEFAULT NULL,
  `nESTADO` int(1) NOT NULL DEFAULT 1,
  `sIDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHACREACION` datetime DEFAULT current_timestamp(),
  `sIDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDCOMPRAPAGO`),
  KEY `nIDCOMPRA` (`nIDCOMPRA`),
  KEY `nIDTIPOPAGO` (`nIDTIPOPAGO`),
  KEY `nIDCUENTA` (`nIDCUENTA`),
  CONSTRAINT `kar_compra_pago_ibfk_1` FOREIGN KEY (`nIDCOMPRA`) REFERENCES `kar_compra` (`nIDCOMPRA`),
  CONSTRAINT `kar_compra_pago_ibfk_2` FOREIGN KEY (`nIDTIPOPAGO`) REFERENCES `sel_tipopago` (`nIDTIPOPAGO`),
  CONSTRAINT `kar_compra_pago_ibfk_3` FOREIGN KEY (`nIDCUENTA`) REFERENCES `sel_cuenta` (`nIDCUENTA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_compra_pago` */

/*Table structure for table `kar_compra_produccion` */

DROP TABLE IF EXISTS `kar_compra_produccion`;

CREATE TABLE `kar_compra_produccion` (
  `nIDPRODUCCION` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nIDCOMPRADETALLE` int(10) unsigned zerofill NOT NULL,
  `nIDPRODUCTO` int(5) unsigned zerofill NOT NULL,
  `nCANTIDAD` int(10) NOT NULL,
  `dFECHAPRODUCCION` datetime NOT NULL,
  `nESTADO` int(1) NOT NULL DEFAULT 1,
  `sIDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHACREACION` datetime NOT NULL,
  `sIDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHAMOD` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDPRODUCCION`),
  KEY `nIDPRODUCTO` (`nIDPRODUCTO`),
  KEY `kar_compra_produccion_ibfk_1` (`nIDCOMPRADETALLE`),
  CONSTRAINT `kar_compra_produccion_ibfk_1` FOREIGN KEY (`nIDCOMPRADETALLE`) REFERENCES `kar_compra_detalle` (`nIDCOMPRADETALLE`),
  CONSTRAINT `kar_compra_produccion_ibfk_2` FOREIGN KEY (`nIDPRODUCTO`) REFERENCES `kar_producto` (`nIDPRODUCTO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_compra_produccion` */

/*Table structure for table `kar_compra_taller` */

DROP TABLE IF EXISTS `kar_compra_taller`;

CREATE TABLE `kar_compra_taller` (
  `nIDTALLER` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nIDCOMPRADETALLE` int(10) unsigned zerofill NOT NULL,
  `fCOSTOPRODUCCION` float NOT NULL,
  `fGANANCIA` float NOT NULL,
  `bTERMINADO` int(1) NOT NULL DEFAULT 0,
  `nESTADO` int(1) NOT NULL DEFAULT 1,
  `sIDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHACREACION` datetime NOT NULL,
  `sIDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHAMOD` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDTALLER`),
  KEY `kar_compra_taller_ibfk_1` (`nIDCOMPRADETALLE`),
  CONSTRAINT `kar_compra_taller_ibfk_1` FOREIGN KEY (`nIDCOMPRADETALLE`) REFERENCES `kar_compra_detalle` (`nIDCOMPRADETALLE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_compra_taller` */

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
  `sIDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHACREACION` datetime DEFAULT current_timestamp(),
  `sIDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`IDPERSONA`,`TIPOPERSONA`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_persona` */

insert  into `kar_persona`(`IDPERSONA`,`NOMBRE`,`AP_PATERNO`,`AP_MATERNO`,`NUMERODOC`,`CORREO`,`TELEFONO`,`TIPOPERSONA`,`ESTADO`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values (0106,'FRANK','LAURA','BORJA',73191639,'FRANK.CG9@GMAIL.COM',993690057,'USER','1','FLAURA','2018-04-02 00:00:00','','2018-05-01 13:09:57'),(0196,'NOMBRE','PATERNO','MATERNO',10101010,'',0,'USER','1','FLAURA','2020-09-16 21:08:35',NULL,'0000-00-00 00:00:00'),(0197,'TICONA','CRISTIAN','MAMANI',96969696,'',0,'USER','1','FLAURA','2020-09-28 01:34:11',NULL,'0000-00-00 00:00:00'),(0198,'REYBI ANTONY','TICLIA','ACEVEDO',96969697,'',0,'USER','1','FLAURA','2020-09-28 10:03:27',NULL,'0000-00-00 00:00:00'),(0199,'RUBEN','GARZON','DARIO',78787878,'',0,'USER','1','FLAURA','2020-11-05 18:38:02',NULL,'0000-00-00 00:00:00');

/*Table structure for table `kar_producto` */

DROP TABLE IF EXISTS `kar_producto`;

CREATE TABLE `kar_producto` (
  `nIDPRODUCTO` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nIDLOCAL` int(4) unsigned zerofill NOT NULL,
  `sNOMBRE` char(150) COLLATE utf8_spanish2_ci NOT NULL,
  `nESTADO` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '1',
  `sIDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHACREACION` datetime DEFAULT current_timestamp(),
  `sIDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDPRODUCTO`),
  KEY `nIDLOCAL` (`nIDLOCAL`),
  FULLTEXT KEY `sNOMBRE` (`sNOMBRE`),
  CONSTRAINT `kar_producto_ibfk_1` FOREIGN KEY (`nIDLOCAL`) REFERENCES `sel_local` (`nIDLOCAL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_producto` */

/*Table structure for table `kar_usuario` */

DROP TABLE IF EXISTS `kar_usuario`;

CREATE TABLE `kar_usuario` (
  `IDUSUARIO` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `CONTRASENIA` char(50) COLLATE utf8_spanish2_ci NOT NULL,
  `ESTADO` int(1) NOT NULL DEFAULT 1 COMMENT '1 ACTIVO / 0 INACTIVO',
  `IDPERSONA` int(4) unsigned zerofill NOT NULL,
  `IDPERFIL` int(2) unsigned zerofill DEFAULT NULL,
  `sIDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHACREACION` datetime DEFAULT current_timestamp(),
  `sIDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`IDUSUARIO`),
  KEY `fk_idperfil` (`IDPERFIL`),
  KEY `IDPERSONA` (`IDPERSONA`),
  CONSTRAINT `kar_usuario_ibfk_1` FOREIGN KEY (`IDPERSONA`) REFERENCES `kar_persona` (`IDPERSONA`),
  CONSTRAINT `kar_usuario_ibfk_2` FOREIGN KEY (`IDPERFIL`) REFERENCES `seguridad_perfil` (`IDPERFIL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_usuario` */

insert  into `kar_usuario`(`IDUSUARIO`,`CONTRASENIA`,`ESTADO`,`IDPERSONA`,`IDPERFIL`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values ('CMAMANI','e1f62e3cdacb60537a660a436d9616d0549f66f4',1,0197,01,'FLAURA','2020-09-28 01:34:11','FLAURA','2020-09-28 01:34:44'),('FLAURA','458f4c520e432987bb4851036aa393ae26c8cba6',1,0106,01,'ADMIN','2020-09-13 00:15:05','FLAURA','2020-09-13 00:26:31'),('RGARZON','db944fecdf894614befc308d412491f2074b3f53',1,0199,05,'FLAURA','2020-11-05 18:38:02','FLAURA','2020-11-05 18:38:37'),('RTICLIA','a4ca05c428c63505b2eb852ea556598c10d51d8f',1,0198,05,'FLAURA','2020-09-28 10:03:27','FLAURA','2020-09-28 10:03:33'),('VENDEDOR','f84ae4782a61dc97f19accb967656c3225743d3a',1,0196,05,'FLAURA','2020-09-16 21:08:35','FLAURA','2020-09-27 20:40:20');

/*Table structure for table `kar_venta` */

DROP TABLE IF EXISTS `kar_venta`;

CREATE TABLE `kar_venta` (
  `nIDVENTA` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nIDLOCAL` int(4) unsigned zerofill NOT NULL,
  `nIDCLIENTE` int(7) unsigned zerofill NOT NULL,
  `dFECHAVENTA` datetime NOT NULL,
  `sOBSERVACION` mediumtext COLLATE utf8_spanish2_ci NOT NULL,
  `nIDVENTACOMPARTIDA` int(10) unsigned zerofill NOT NULL,
  `nESTADO` char(1) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '1',
  `sIDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHACREACION` datetime DEFAULT current_timestamp(),
  `sIDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDVENTA`),
  KEY `nIDLOCAL` (`nIDLOCAL`),
  KEY `nIDCLIENTE` (`nIDCLIENTE`),
  CONSTRAINT `kar_venta_ibfk_1` FOREIGN KEY (`nIDLOCAL`) REFERENCES `sel_local` (`nIDLOCAL`),
  CONSTRAINT `kar_venta_ibfk_2` FOREIGN KEY (`nIDCLIENTE`) REFERENCES `sel_cliente` (`nIDCLIENTE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_venta` */

/*Table structure for table `kar_venta_detalle` */

DROP TABLE IF EXISTS `kar_venta_detalle`;

CREATE TABLE `kar_venta_detalle` (
  `nIDVENTADETALLE` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nIDVENTA` int(10) unsigned zerofill NOT NULL,
  `nIDCOMPRADETALLE` int(10) unsigned zerofill NOT NULL,
  `nIDPRODUCTO` int(5) unsigned zerofill NOT NULL,
  `nCANTIDAD` char(10) COLLATE utf8_spanish2_ci NOT NULL,
  `fPRECIO` float NOT NULL,
  `nESTADO` int(1) NOT NULL DEFAULT 1,
  `sIDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHACREACION` datetime DEFAULT current_timestamp(),
  `sIDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDVENTADETALLE`),
  KEY `nIDVENTA` (`nIDVENTA`),
  KEY `nIDPRODUCTO` (`nIDPRODUCTO`),
  KEY `nIDCOMPRADETALLE` (`nIDCOMPRADETALLE`),
  CONSTRAINT `kar_venta_detalle_ibfk_1` FOREIGN KEY (`nIDVENTA`) REFERENCES `kar_venta` (`nIDVENTA`),
  CONSTRAINT `kar_venta_detalle_ibfk_2` FOREIGN KEY (`nIDPRODUCTO`) REFERENCES `kar_producto` (`nIDPRODUCTO`),
  CONSTRAINT `kar_venta_detalle_ibfk_3` FOREIGN KEY (`nIDCOMPRADETALLE`) REFERENCES `kar_compra_detalle` (`nIDCOMPRADETALLE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_venta_detalle` */

/*Table structure for table `kar_venta_extorno` */

DROP TABLE IF EXISTS `kar_venta_extorno`;

CREATE TABLE `kar_venta_extorno` (
  `nIDEXTORNO` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nIDVENTA` int(10) unsigned zerofill NOT NULL,
  `sMOTIVO` mediumtext COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHA_EXTORNO` datetime NOT NULL,
  `nESTADO` int(1) NOT NULL DEFAULT 1,
  `sIDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHACREACION` datetime DEFAULT current_timestamp(),
  `sIDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDEXTORNO`),
  KEY `nIDVENTA` (`nIDVENTA`),
  CONSTRAINT `kar_venta_extorno_ibfk_1` FOREIGN KEY (`nIDVENTA`) REFERENCES `kar_venta` (`nIDVENTA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_venta_extorno` */

/*Table structure for table `kar_venta_pago` */

DROP TABLE IF EXISTS `kar_venta_pago`;

CREATE TABLE `kar_venta_pago` (
  `nIDVENTAPAGO` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nIDVENTA` int(10) unsigned zerofill NOT NULL,
  `nIDTIPOPAGO` int(2) unsigned zerofill NOT NULL,
  `fMONTO` float NOT NULL,
  `nIDCUENTA` int(4) unsigned zerofill NOT NULL,
  `sOBSERVACION` mediumtext COLLATE utf8_spanish2_ci DEFAULT NULL,
  `dFECHAPAGO` datetime NOT NULL,
  `nESTADO` int(1) NOT NULL DEFAULT 1,
  `sIDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHACREACION` datetime DEFAULT current_timestamp(),
  `sIDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDVENTAPAGO`),
  KEY `nIDVENTA` (`nIDVENTA`),
  KEY `nIDTIPOPAGO` (`nIDTIPOPAGO`),
  KEY `nIDCUENTA` (`nIDCUENTA`),
  CONSTRAINT `kar_venta_pago_ibfk_1` FOREIGN KEY (`nIDVENTA`) REFERENCES `kar_venta` (`nIDVENTA`),
  CONSTRAINT `kar_venta_pago_ibfk_2` FOREIGN KEY (`nIDTIPOPAGO`) REFERENCES `sel_tipopago` (`nIDTIPOPAGO`),
  CONSTRAINT `kar_venta_pago_ibfk_3` FOREIGN KEY (`nIDCUENTA`) REFERENCES `sel_cuenta` (`nIDCUENTA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_venta_pago` */

/*Table structure for table `seguridad_modulo` */

DROP TABLE IF EXISTS `seguridad_modulo`;

CREATE TABLE `seguridad_modulo` (
  `IDMODULO` int(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `NOMBRE_MODULO` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `DESCRIPCION` char(50) COLLATE utf8_spanish2_ci NOT NULL,
  `TIPO` char(20) COLLATE utf8_spanish2_ci NOT NULL,
  `UBICACION` char(50) COLLATE utf8_spanish2_ci NOT NULL,
  `nIDLOCAL` int(4) unsigned zerofill DEFAULT NULL,
  `FLAG` int(1) NOT NULL DEFAULT 1 COMMENT '1 -> ACTIVO / 0->INACTIVO',
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `ORDEN` int(2) NOT NULL,
  PRIMARY KEY (`IDMODULO`),
  KEY `nIDLOCAL` (`nIDLOCAL`),
  CONSTRAINT `seguridad_modulo_ibfk_1` FOREIGN KEY (`nIDLOCAL`) REFERENCES `sel_local` (`nIDLOCAL`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `seguridad_modulo` */

insert  into `seguridad_modulo`(`IDMODULO`,`NOMBRE_MODULO`,`DESCRIPCION`,`TIPO`,`UBICACION`,`nIDLOCAL`,`FLAG`,`dFECHAMOD`,`ORDEN`) values (01,'MENU_PANEL','Inicio','MENU_PAN','panel',0003,1,'2020-10-31 22:47:34',1),(02,'MENU_SEGURIDAD','Control de Acceso','MENU_SEG','usuario',0003,1,'2020-10-31 22:47:35',2),(10,'MENU_COMPRA','Registrar Compra','MENU_TIENDA_1','compra',0002,1,'2020-10-31 22:47:40',3),(11,'MENU_CATALOGO','Stock de Productos','MENU_TIENDA_1','stock',0002,1,'2020-10-31 22:52:34',5),(12,'MENU_VENTAS','Registrar Venta','MENU_TIENDA_1','venta',0002,1,'2020-10-31 22:47:48',4),(13,'MENU_REPORTES','Reportes','MENU_TIENDA_1','reporte',0002,1,'2020-10-31 22:48:20',10),(14,'MENU_ANULACION','Anulacion de Compra','MENU_TIENDA_1','anulacion',0002,1,'2020-10-31 22:48:13',7),(15,'MENU_EXTORNO','Extorno de Venta','MENU_TIENDA_1','extorno',0002,1,'2020-10-31 22:48:12',6),(16,'MENU_CUENTA_PAGO','Cuentas por Pagar','MENU_TIENDA_1','cuentapago',0002,1,'2020-10-31 22:48:17',8),(17,'MENU_CUENTA_COBRO','Cuentas por Cobrar','MENU_TIENDA_1','cuentacobro',0002,1,'2020-10-31 22:48:18',9),(18,'MENU_COMPRA','Registrar Compra','MENU_TIENDA_2','compra',0004,1,'2020-11-06 08:52:03',20),(19,'MENU_VENTAS','Registrar Venta','MENU_TIENDA_2','venta',0004,1,'2020-11-06 08:52:07',21),(20,'MENU_CATALOGO','Stock de Productos','MENU_TIENDA_2','stock',0004,1,'2020-11-06 08:52:09',22),(21,'MENU_EXTORNO','Extorno de Venta','MENU_TIENDA_2','extorno',0004,1,'2020-11-06 08:52:11',23),(22,'MENU_ANULACION','Anulacion de Compra','MENU_TIENDA_2','anulacion',0004,1,'2020-11-06 08:52:14',24),(23,'MENU_CUENTA_PAGO','Cuentas por Pagar','MENU_TIENDA_2','cuentapago',0004,1,'2020-11-06 08:52:16',25),(24,'MENU_CUENTA_COBRO','Cuentas por Cobrar','MENU_TIENDA_2','cuentacobro',0004,1,'2020-11-06 08:52:18',26),(25,'MENU_REPORTES','Reportes','MENU_TIENDA_2','reporte',0004,1,'2020-11-06 08:52:21',27),(26,'MENU_VENTAS','Registrar Venta','MENU_TALLER','venta',0006,1,'2020-11-05 22:42:05',0),(27,'MENU_COMPRA','Registrar Compra','MENU_TALLER','compra',0006,1,'2020-11-05 22:42:11',0),(28,'MENU_PRODUCCION','Registrar Produccion','MENU_TALLER','produccion',0006,1,'2020-11-05 22:42:31',0),(29,'MENU_REPORTE_COMPARTIDAS','Ventas Compartidas','MENU_TIENDA_1','reportevendedor',0002,1,'2020-11-06 08:57:55',11),(30,'MENU_REPORTE_COMPARTIDAS','Ventas Compartidas','MENU_TIENDA_2','reportevendedor',0004,1,'2020-11-06 08:58:00',28),(31,'MENU_INGRESOS','Ingresos','MENU_TALLER','ingreso',0006,1,'2020-11-06 19:00:50',29),(32,'MENU_SALIDA','Salida','MENU_TALLER','salida',0006,1,'0000-00-00 00:00:00',30);

/*Table structure for table `seguridad_modulo_perfil` */

DROP TABLE IF EXISTS `seguridad_modulo_perfil`;

CREATE TABLE `seguridad_modulo_perfil` (
  `IDDETALLE` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `IDMODULO` int(2) unsigned zerofill NOT NULL,
  `IDPERFIL` int(2) unsigned zerofill NOT NULL,
  `PERMISO` int(1) NOT NULL,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`IDDETALLE`),
  KEY `seguridad_modulo_perfil_ibfk_1` (`IDMODULO`),
  KEY `seguridad_modulo_perfil_ibfk_2` (`IDPERFIL`),
  CONSTRAINT `seguridad_modulo_perfil_ibfk_1` FOREIGN KEY (`IDMODULO`) REFERENCES `seguridad_modulo` (`IDMODULO`),
  CONSTRAINT `seguridad_modulo_perfil_ibfk_2` FOREIGN KEY (`IDPERFIL`) REFERENCES `seguridad_perfil` (`IDPERFIL`)
) ENGINE=InnoDB AUTO_INCREMENT=324 DEFAULT CHARSET=utf8;

/*Data for the table `seguridad_modulo_perfil` */

insert  into `seguridad_modulo_perfil`(`IDDETALLE`,`IDMODULO`,`IDPERFIL`,`PERMISO`,`dFECHAMOD`) values (0191,01,07,0,'0000-00-00 00:00:00'),(0192,19,07,0,'0000-00-00 00:00:00'),(0193,20,07,0,'0000-00-00 00:00:00'),(0194,21,07,0,'0000-00-00 00:00:00'),(0195,11,05,0,'0000-00-00 00:00:00'),(0196,12,05,0,'0000-00-00 00:00:00'),(0197,15,05,0,'0000-00-00 00:00:00'),(0198,01,05,0,'0000-00-00 00:00:00'),(0199,01,08,0,'0000-00-00 00:00:00'),(0200,12,08,0,'0000-00-00 00:00:00'),(0201,15,08,0,'0000-00-00 00:00:00'),(0202,19,08,0,'0000-00-00 00:00:00'),(0203,21,08,0,'0000-00-00 00:00:00'),(0299,10,01,0,'0000-00-00 00:00:00'),(0300,11,01,0,'0000-00-00 00:00:00'),(0301,12,01,0,'0000-00-00 00:00:00'),(0302,13,01,0,'0000-00-00 00:00:00'),(0303,14,01,0,'0000-00-00 00:00:00'),(0304,15,01,0,'0000-00-00 00:00:00'),(0305,16,01,0,'0000-00-00 00:00:00'),(0306,17,01,0,'0000-00-00 00:00:00'),(0307,29,01,0,'0000-00-00 00:00:00'),(0308,01,01,0,'0000-00-00 00:00:00'),(0309,02,01,0,'0000-00-00 00:00:00'),(0310,18,01,0,'0000-00-00 00:00:00'),(0311,19,01,0,'0000-00-00 00:00:00'),(0312,20,01,0,'0000-00-00 00:00:00'),(0313,21,01,0,'0000-00-00 00:00:00'),(0314,22,01,0,'0000-00-00 00:00:00'),(0315,23,01,0,'0000-00-00 00:00:00'),(0316,24,01,0,'0000-00-00 00:00:00'),(0317,25,01,0,'0000-00-00 00:00:00'),(0318,30,01,0,'0000-00-00 00:00:00'),(0319,26,01,0,'0000-00-00 00:00:00'),(0320,27,01,0,'0000-00-00 00:00:00'),(0321,28,01,0,'0000-00-00 00:00:00'),(0322,31,01,0,'0000-00-00 00:00:00'),(0323,32,01,0,'0000-00-00 00:00:00');

/*Table structure for table `seguridad_perfil` */

DROP TABLE IF EXISTS `seguridad_perfil`;

CREATE TABLE `seguridad_perfil` (
  `IDPERFIL` int(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `NOMBRE_PERFIL` char(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `FLAG` int(1) NOT NULL DEFAULT 1,
  `sIDUSUARIOCREACION` char(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHACREACION` datetime DEFAULT current_timestamp(),
  `sIDUSUARIOMOD` char(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`IDPERFIL`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `seguridad_perfil` */

insert  into `seguridad_perfil`(`IDPERFIL`,`NOMBRE_PERFIL`,`FLAG`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values (01,'ADMINISTRADOR',1,'','2018-04-05 17:32:48','FLAURA','2018-04-10 14:49:05'),(05,'VENDEDOR TIENDA',1,'','2020-09-13 12:50:30','','2020-11-02 12:25:02'),(07,'VENDEDOR ALMACEN',1,'','2020-11-02 12:26:31',NULL,'0000-00-00 00:00:00'),(08,'SUPERVISOR',1,'','2020-11-05 18:42:04',NULL,'0000-00-00 00:00:00');

/*Table structure for table `sel_cliente` */

DROP TABLE IF EXISTS `sel_cliente`;

CREATE TABLE `sel_cliente` (
  `nIDCLIENTE` int(7) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `sDESCRIPCION` char(100) COLLATE utf8_spanish2_ci NOT NULL,
  `nESTADO` int(1) NOT NULL DEFAULT 1,
  `sIDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHACREACION` datetime DEFAULT current_timestamp(),
  `sIDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDCLIENTE`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `sel_cliente` */

insert  into `sel_cliente`(`nIDCLIENTE`,`sDESCRIPCION`,`nESTADO`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values (0000001,'CLIENTE GENERAL',1,'','2020-11-07 13:54:06',NULL,'0000-00-00 00:00:00'),(0000002,'TIENDA',2,'','2020-11-07 13:54:06',NULL,'0000-00-00 00:00:00'),(0000003,'ALMACEN',2,'','2020-11-07 13:54:06',NULL,'0000-00-00 00:00:00'),(0000004,'LOCAL LIBRE',3,'','2020-11-07 13:54:06',NULL,'0000-00-00 00:00:00'),(0000005,'LOCAL LIBRE',3,'','2020-11-07 13:54:06',NULL,'0000-00-00 00:00:00'),(0000006,'LOCAL LIBRE',3,'','2020-11-07 13:54:06',NULL,'0000-00-00 00:00:00'),(0000007,'LOCAL LIBRE',3,'','2020-11-07 13:54:06',NULL,'0000-00-00 00:00:00'),(0000008,'LOCAL LIBRE',3,'','2020-11-07 13:54:07',NULL,'0000-00-00 00:00:00'),(0000009,'LOCAL LIBRE',3,'','2020-11-07 13:54:07',NULL,'0000-00-00 00:00:00'),(0000010,'LOCAL LIBRE',3,'','2020-11-07 13:54:07',NULL,'0000-00-00 00:00:00');

/*Table structure for table `sel_cuenta` */

DROP TABLE IF EXISTS `sel_cuenta`;

CREATE TABLE `sel_cuenta` (
  `nIDCUENTA` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `sDESCRIPCION` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `nESTADO` int(2) DEFAULT 1,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDCUENTA`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `sel_cuenta` */

insert  into `sel_cuenta`(`nIDCUENTA`,`sDESCRIPCION`,`nESTADO`,`dFECHAMOD`) values (0001,'',1,'0000-00-00 00:00:00');

/*Table structure for table `sel_estado` */

DROP TABLE IF EXISTS `sel_estado`;

CREATE TABLE `sel_estado` (
  `nIDESTADO` int(20) NOT NULL AUTO_INCREMENT,
  `sDESCRIPCION` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nESTADO` int(1) DEFAULT 1,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDESTADO`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `sel_estado` */

insert  into `sel_estado`(`nIDESTADO`,`sDESCRIPCION`,`nESTADO`,`dFECHAMOD`) values (1,'ACTIVAS',1,'2020-10-15 17:07:10'),(2,'INACTIVAS',0,'2020-10-15 17:07:15'),(3,'ANULADAS',1,'2020-10-15 17:07:18'),(4,'EXTORNADAS',1,'2020-10-15 17:07:20'),(5,'COMPARTIDA',1,'0000-00-00 00:00:00');

/*Table structure for table `sel_local` */

DROP TABLE IF EXISTS `sel_local`;

CREATE TABLE `sel_local` (
  `nIDLOCAL` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `sDESCRIPCION` char(100) COLLATE utf8_spanish2_ci NOT NULL,
  `sRUC` char(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `sDIRECCION` char(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `nTELEFONO` int(10) DEFAULT NULL,
  `nESTADO` int(1) NOT NULL DEFAULT 1,
  `sIDUSUARIOCREACION` char(30) COLLATE utf8_spanish2_ci NOT NULL,
  `dFECHACREACION` datetime DEFAULT current_timestamp(),
  `sIDUSUARIOMOD` char(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDLOCAL`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `sel_local` */

insert  into `sel_local`(`nIDLOCAL`,`sDESCRIPCION`,`sRUC`,`sDIRECCION`,`nTELEFONO`,`nESTADO`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values (0002,'Tienda','20602990959','Jr montevideo 785 int 229',999888777,1,'FLAURA','2020-10-19 22:41:58',NULL,'2020-10-31 23:09:13'),(0003,'Todos','20602990959','Av. Del Aire 551',999888777,1,'FLAURA','2020-10-19 23:15:54',NULL,'2020-10-31 23:09:51'),(0004,'Almacen','20602990959','Huanta 1298 int 202 - Cercando de Lima',999888777,1,'FLAURA','2020-10-31 23:09:22',NULL,'2020-10-31 23:09:55'),(0005,'Tienda 2','20602990959','Jr montevideo 785 int 101',999888777,1,'FLAURA','2020-10-31 23:10:08',NULL,'2020-10-31 23:10:25'),(0006,'Taller','20602990959','Huanta 1298 int 404',999888777,1,'FLAURA','2020-10-31 23:10:28',NULL,'2020-10-31 23:10:42');

/*Table structure for table `sel_proveedor` */

DROP TABLE IF EXISTS `sel_proveedor`;

CREATE TABLE `sel_proveedor` (
  `nIDPROVEEDOR` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `sDESCRIPCION` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nESTADO` int(2) DEFAULT 1,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDPROVEEDOR`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `sel_proveedor` */

insert  into `sel_proveedor`(`nIDPROVEEDOR`,`sDESCRIPCION`,`nESTADO`,`dFECHAMOD`) values (0001,'PROVEEDOR GENERAL',1,'0000-00-00 00:00:00');

/*Table structure for table `sel_tipopago` */

DROP TABLE IF EXISTS `sel_tipopago`;

CREATE TABLE `sel_tipopago` (
  `nIDTIPOPAGO` int(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `sDESCRIPCION` char(20) COLLATE utf8_spanish2_ci NOT NULL,
  `nESTADO` int(1) NOT NULL DEFAULT 1,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDTIPOPAGO`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `sel_tipopago` */

insert  into `sel_tipopago`(`nIDTIPOPAGO`,`sDESCRIPCION`,`nESTADO`,`dFECHAMOD`) values (01,'EFECTIVO',1,'0000-00-00 00:00:00'),(02,'DEPOSITO',1,'0000-00-00 00:00:00');

/*Table structure for table `vw_sel_productos_inversion` */

DROP TABLE IF EXISTS `vw_sel_productos_inversion`;

/*!50001 DROP VIEW IF EXISTS `vw_sel_productos_inversion` */;
/*!50001 DROP TABLE IF EXISTS `vw_sel_productos_inversion` */;

/*!50001 CREATE TABLE  `vw_sel_productos_inversion`(
 `nLOCAL` int(4) unsigned zerofill ,
 `sLOCAL` char(100) ,
 `nIDPRODUCTO` int(5) unsigned zerofill ,
 `sNOMBRE` char(150) ,
 `nCANTIDAD` double ,
 `nTOTAL` double(19,2) ,
 `avgPorCantidadPrecio` double(19,2) ,
 `avgPrecio` double(19,2) 
)*/;

/*Table structure for table `vw_sel_productos_stock` */

DROP TABLE IF EXISTS `vw_sel_productos_stock`;

/*!50001 DROP VIEW IF EXISTS `vw_sel_productos_stock` */;
/*!50001 DROP TABLE IF EXISTS `vw_sel_productos_stock` */;

/*!50001 CREATE TABLE  `vw_sel_productos_stock`(
 `nIDPRODUCTO` int(5) unsigned zerofill ,
 `nCantidadCompra` decimal(32,0) ,
 `nCantidadVenta` double ,
 `nCANTIDAD` double ,
 `sNOMBRE` char(150) ,
 `sLOCAL` char(100) ,
 `nLOCAL` int(4) unsigned zerofill 
)*/;

/*View structure for view vw_sel_productos_inversion */

/*!50001 DROP TABLE IF EXISTS `vw_sel_productos_inversion` */;
/*!50001 DROP VIEW IF EXISTS `vw_sel_productos_inversion` */;

/*!50001 CREATE SQL SECURITY DEFINER VIEW `vw_sel_productos_inversion` AS (select `c`.`nIDLOCAL` AS `nLOCAL`,`c`.`sDESCRIPCION` AS `sLOCAL`,`b`.`nIDPRODUCTO` AS `nIDPRODUCTO`,`b`.`sNOMBRE` AS `sNOMBRE`,sum(`az`.`nSTOCK`) AS `nCANTIDAD`,round(sum(`az`.`nSTOCK` * `az`.`fPRECIO`),2) AS `nTOTAL`,round(sum(`az`.`nSTOCK` * `az`.`fPRECIO`) / sum(`az`.`nSTOCK`),2) AS `avgPorCantidadPrecio`,round(avg(`az`.`fPRECIO`),2) AS `avgPrecio` from ((((select `a`.`nIDCOMPRADETALLE` AS `nIDCOMPRADETALLE`,`a`.`nIDPRODUCTO` AS `nIDPRODUCTO`,`a`.`nCANTIDAD` AS `nCantidadCompra`,sum(ifnull(`b`.`nCANTIDAD`,0)) AS `nCantidadVenta`,`a`.`nCANTIDAD` - sum(ifnull(`b`.`nCANTIDAD`,0)) AS `nSTOCK`,`a`.`fPRECIO` AS `fPRECIO` from (`fzbsokgg_tauro_kardex`.`kar_compra_detalle` `a` left join `fzbsokgg_tauro_kardex`.`kar_venta_detalle` `b` on(`a`.`nIDCOMPRADETALLE` = `b`.`nIDCOMPRADETALLE` and `b`.`nESTADO` = 1)) where `a`.`bSTOCK` = 1 and `a`.`nESTADO` = 1 group by `a`.`nIDCOMPRADETALLE`,`a`.`nIDPRODUCTO`)) `az` join `fzbsokgg_tauro_kardex`.`kar_producto` `b` on(`az`.`nIDPRODUCTO` = `b`.`nIDPRODUCTO`)) join `fzbsokgg_tauro_kardex`.`sel_local` `c` on(`b`.`nIDLOCAL` = `c`.`nIDLOCAL`)) group by `az`.`nIDPRODUCTO`) */;

/*View structure for view vw_sel_productos_stock */

/*!50001 DROP TABLE IF EXISTS `vw_sel_productos_stock` */;
/*!50001 DROP VIEW IF EXISTS `vw_sel_productos_stock` */;

/*!50001 CREATE SQL SECURITY DEFINER VIEW `vw_sel_productos_stock` AS (select `a`.`nIDPRODUCTO` AS `nIDPRODUCTO`,sum(`a`.`nCantidadCompra`) AS `nCantidadCompra`,sum(`a`.`nCantidadVenta`) AS `nCantidadVenta`,sum(`a`.`nSTOCK`) AS `nCANTIDAD`,`b`.`sNOMBRE` AS `sNOMBRE`,`c`.`sDESCRIPCION` AS `sLOCAL`,`b`.`nIDLOCAL` AS `nLOCAL` from ((((select `a`.`nIDCOMPRADETALLE` AS `nIDCOMPRADETALLE`,`a`.`nIDPRODUCTO` AS `nIDPRODUCTO`,`a`.`nCANTIDAD` AS `nCantidadCompra`,sum(ifnull(`b`.`nCANTIDAD`,0)) AS `nCantidadVenta`,`a`.`nCANTIDAD` - sum(ifnull(`b`.`nCANTIDAD`,0)) AS `nSTOCK` from (`fzbsokgg_tauro_kardex`.`kar_compra_detalle` `a` left join `fzbsokgg_tauro_kardex`.`kar_venta_detalle` `b` on(`a`.`nIDCOMPRADETALLE` = `b`.`nIDCOMPRADETALLE` and `b`.`nESTADO` = 1)) where `a`.`bSTOCK` = 1 and `a`.`nESTADO` = 1 group by `a`.`nIDCOMPRADETALLE`,`a`.`nIDPRODUCTO`)) `a` join `fzbsokgg_tauro_kardex`.`kar_producto` `b` on(`a`.`nIDPRODUCTO` = `b`.`nIDPRODUCTO` and `b`.`nESTADO` = 1)) join `fzbsokgg_tauro_kardex`.`sel_local` `c` on(`b`.`nIDLOCAL` = `c`.`nIDLOCAL` and `c`.`nESTADO` = 1)) group by `a`.`nIDPRODUCTO`) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
