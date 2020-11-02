/*
SQLyog Ultimate v10.3 
MySQL - 5.5.5-10.3.24-MariaDB-cll-lve : Database - fzbsokgg_tauro_kardex_v2
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`fzbsokgg_tauro_kardex_v2` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `fzbsokgg_tauro_kardex_v2`;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_compra` */

insert  into `kar_compra`(`nIDCOMPRA`,`nIDLOCAL`,`nIDPROVEEDOR`,`nESTADO`,`sOBSERVACION`,`dFECHACOMPRA`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values (0000000001,0002,0001,3,'DASD','2020-11-02 12:11:44','FLAURA','2020-11-02 12:29:44','FLAURA','2020-11-02 12:41:10');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_compra_anulacion` */

insert  into `kar_compra_anulacion`(`nIDANULACION`,`nIDCOMPRA`,`sMOTIVO`,`dFECHA_ANULACION`,`nESTADO`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values (0000000001,0000000001,'SI LO SE','2020-11-02 12:11:10',1,'FLAURA','2020-11-02 12:41:10',NULL,'0000-00-00 00:00:00');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `kar_compra_detalle` */

insert  into `kar_compra_detalle`(`nIDCOMPRADETALLE`,`nIDCOMPRA`,`nIDPRODUCTO`,`nCANTIDAD`,`fPRECIO`,`bSTOCK`,`nESTADO`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values (0000000001,0000000001,00099,50,7,1,3,'FLAURA','2020-11-02 12:29:44','FLAURA','2020-11-02 12:41:10'),(0000000002,0000000001,00100,50,10,1,3,'FLAURA','2020-11-02 12:29:44','FLAURA','2020-11-02 12:41:10');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_compra_pago` */

insert  into `kar_compra_pago`(`nIDCOMPRAPAGO`,`nIDCOMPRA`,`nIDTIPOPAGO`,`fMONTO`,`nIDCUENTA`,`sOBSERVACION`,`dFECHAPAGO`,`nESTADO`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values (0000000001,0000000001,01,500,0001,NULL,'2020-11-02 12:11:44',3,'FLAURA','2020-11-02 12:29:44','FLAURA','2020-11-02 12:41:10');

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
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_persona` */

insert  into `kar_persona`(`IDPERSONA`,`NOMBRE`,`AP_PATERNO`,`AP_MATERNO`,`NUMERODOC`,`CORREO`,`TELEFONO`,`TIPOPERSONA`,`ESTADO`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values (0106,'FRANK','LAURA','BORJA',73191639,'FRANK.CG9@GMAIL.COM',993690057,'USER','1','FLAURA','2018-04-02 00:00:00','','2018-05-01 13:09:57'),(0196,'NOMBRE','PATERNO','MATERNO',10101010,'',0,'USER','1','FLAURA','2020-09-16 21:08:35',NULL,'0000-00-00 00:00:00'),(0197,'TICONA','CRISTIAN','MAMANI',96969696,'',0,'USER','1','FLAURA','2020-09-28 01:34:11',NULL,'0000-00-00 00:00:00'),(0198,'REYBI ANTONY','TICLIA','ACEVEDO',96969697,'',0,'USER','1','FLAURA','2020-09-28 10:03:27',NULL,'0000-00-00 00:00:00');

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
  CONSTRAINT `kar_producto_ibfk_1` FOREIGN KEY (`nIDLOCAL`) REFERENCES `sel_local` (`nIDLOCAL`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_producto` */

insert  into `kar_producto`(`nIDPRODUCTO`,`nIDLOCAL`,`sNOMBRE`,`nESTADO`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values (00012,0002,'SABANA POLAR','1','CMAMANI','2020-09-29 21:15:25',NULL,'2020-10-19 23:54:26'),(00013,0002,'SABANA PIEL','1','CMAMANI','2020-09-29 21:16:18',NULL,'2020-10-19 23:54:26'),(00014,0002,'MANTA PELUCHE','1','CMAMANI','2020-09-29 21:32:28',NULL,'2020-10-19 23:54:26'),(00015,0002,'MANTA PIEL','1','CMAMANI','2020-09-29 21:33:14',NULL,'2020-10-19 23:54:26'),(00016,0002,'MANTA POLAR','1','CMAMANI','2020-09-29 21:34:10',NULL,'2020-10-19 23:54:26'),(00017,0002,'MANTA PIEL CON BORDE','1','CMAMANI','2020-09-29 21:35:05',NULL,'2020-10-19 23:54:26'),(00018,0002,'COBERTOR PLUSH','1','CMAMANI','2020-09-29 21:52:17',NULL,'2020-10-19 23:54:26'),(00019,0002,'COBERTOR GOLDSUN 3PCS','1','CMAMANI','2020-09-29 21:54:56',NULL,'2020-10-19 23:54:26'),(00020,0002,'COBERTOR 3D CON FUNDA','1','CMAMANI','2020-09-29 21:56:29',NULL,'2020-10-19 23:54:26'),(00021,0002,'COBERTOR 3D SIN FUNDA','1','CMAMANI','2020-09-29 21:57:50',NULL,'2020-10-19 23:54:26'),(00022,0002,'COBERTOR PIEL CON CARNERO','1','CMAMANI','2020-09-29 21:58:54',NULL,'2020-10-19 23:54:26'),(00023,0002,'COBERTOR PIEL DE CONEJO','1','CMAMANI','2020-09-29 22:00:57',NULL,'2020-10-19 23:54:26'),(00024,0002,'COBERTOR DISNEY CON CARNERO','1','CMAMANI','2020-09-29 22:02:42',NULL,'2020-10-19 23:54:26'),(00025,0002,'COBERTOR PIEL CON CARNERO TIGRES','1','CMAMANI','2020-09-29 22:04:42',NULL,'2020-10-19 23:54:26'),(00026,0002,'COBERTOR DEPORTIVO TELA CON CARNERO','1','CMAMANI','2020-09-29 22:05:44',NULL,'2020-10-19 23:54:26'),(00027,0002,'COBERTOR DEPORTIVO TELA CON PIEL','1','CMAMANI','2020-09-29 22:16:32',NULL,'2020-10-19 23:54:26'),(00028,0002,'COBERTOR PIEL CON CARNERO 3PCS','1','CMAMANI','2020-09-29 22:18:15',NULL,'2020-10-19 23:54:26'),(00029,0002,'FRAZADA INTERMEDIA','1','CMAMANI','2020-09-29 22:19:12',NULL,'2020-10-19 23:54:26'),(00030,0002,'FRAZADA CON CARNERO','1','CMAMANI','2020-09-29 22:19:54',NULL,'2020-10-19 23:54:26'),(00031,0002,'FRAZADA 6K','1','CMAMANI','2020-09-29 22:26:57',NULL,'2020-10-19 23:54:26'),(00032,0002,'FRAZADA 8K','1','CMAMANI','2020-09-29 22:27:39',NULL,'2020-10-19 23:54:26'),(00033,0002,'COBERTOR TELA CON CARNERO GRUESO 5PCS CASA PANDA','1','CMAMANI','2020-09-29 22:29:14',NULL,'2020-10-19 23:54:26'),(00034,0002,'COBERTOR TELA CON CARNERO DELGADO ESCORPION','1','CMAMANI','2020-09-29 22:30:15',NULL,'2020-10-19 23:54:26'),(00035,0002,'SABANA ALGODON 1 1/2','1','CMAMANI','2020-09-29 22:35:21',NULL,'2020-10-19 23:54:26'),(00036,0002,'SABANA ALGODON 2 PLAZAS','1','CMAMANI','2020-09-29 22:36:41',NULL,'2020-10-19 23:54:26'),(00037,0002,'SABANA CAJA 2 PLAZAS','1','CMAMANI','2020-09-29 22:37:32',NULL,'2020-10-19 23:54:26'),(00038,0002,'SABANA BLANCA 1 1/2','1','CMAMANI','2020-09-29 22:39:03',NULL,'2020-10-19 23:54:26'),(00039,0002,'SABANA BRAMANTE','1','CMAMANI','2020-09-29 22:40:04',NULL,'2020-10-19 23:54:26'),(00040,0002,'SABANA BRAMANTE 2 PLAZAS','1','CMAMANI','2020-09-29 22:41:27',NULL,'2020-10-19 23:54:26'),(00041,0002,'FORRO DE COLCHON 1 1/2','1','CMAMANI','2020-09-29 22:46:38',NULL,'2020-10-19 23:54:26'),(00042,0002,'FORRO DE COLCHON 2 PLAZAS','1','CMAMANI','2020-09-29 22:47:29',NULL,'2020-10-19 23:54:26'),(00043,0002,'SABANA ECONOMICA NANCY','1','CMAMANI','2020-09-29 22:49:13',NULL,'2020-10-19 23:54:26'),(00044,0002,'COBERTOR DISNEY PIEL CON CARNERO','1','CMAMANI','2020-09-29 22:56:18',NULL,'2020-10-19 23:54:26'),(00045,0002,'COBERTOR POLAR CON POLAR','1','CMAMANI','2020-10-03 17:28:13',NULL,'2020-10-19 23:54:26'),(00046,0002,'SABANA DISNEY EN CAJA','1','CMAMANI','2020-10-03 17:42:23',NULL,'2020-10-19 23:54:26'),(00047,0002,'SABANA PELUCHE','1','CMAMANI','2020-10-03 17:47:45',NULL,'2020-10-19 23:54:26'),(00048,0002,'COBERTOR CALAMINA CON CARNERO','1','CMAMANI','2020-10-03 17:57:08',NULL,'2020-10-19 23:54:26'),(00049,0002,'EDREDON VERANO 7PCS ESCORPION','1','CMAMANI','2020-10-03 17:58:42',NULL,'2020-10-19 23:54:26'),(00082,0002,'SABANA POLAR ROJA','1','FLAURA','2020-10-15 20:33:11',NULL,'2020-10-19 23:54:26'),(00083,0002,'SABANA GUINDA','1','FLAURA','2020-10-18 22:55:58',NULL,'2020-10-19 23:54:26'),(00084,0002,'SABANA FUXIA','1','FLAURA','2020-10-18 22:55:58',NULL,'2020-10-19 23:54:26'),(00085,0002,'FRASADA ROJA','1','FLAURA','2020-10-18 23:02:17',NULL,'2020-10-19 23:54:26'),(00086,0002,'FRASADA ROJA','1','FLAURA','2020-10-18 23:05:44',NULL,'2020-10-19 23:54:26'),(00087,0002,'PELUCHE','1','FLAURA','2020-10-19 17:54:30','FLAURA','2020-10-19 23:54:26'),(00089,0002,'SILLA GAMER','1','FLAURA',NULL,NULL,'0000-00-00 00:00:00'),(00090,0002,'MESA PORTATIL','1','FLAURA',NULL,NULL,'0000-00-00 00:00:00'),(00091,0002,'TECLADO','1','FLAURA',NULL,NULL,'0000-00-00 00:00:00'),(00092,0002,'ANTEOJOS','1','FLAURA',NULL,NULL,'0000-00-00 00:00:00'),(00093,0002,'TV 50 PULGADAS','1','FLAURA',NULL,NULL,'0000-00-00 00:00:00'),(00094,0002,'EQUIPO DE SONIDO','1','FLAURA',NULL,NULL,'0000-00-00 00:00:00'),(00095,0002,'SABANA POLAR AZUL','1','FLAURA',NULL,NULL,'0000-00-00 00:00:00'),(00096,0003,'SABANA POLAR','1','FLAURA',NULL,NULL,'0000-00-00 00:00:00'),(00097,0002,'CAMA QUEEN','1','FLAURA',NULL,NULL,'0000-00-00 00:00:00'),(00098,0002,'CAMA KING','1','FLAURA',NULL,NULL,'0000-00-00 00:00:00'),(00099,0002,'VASO','1','FLAURA','2020-11-02 12:29:44',NULL,'0000-00-00 00:00:00'),(00100,0002,'JARRA','1','FLAURA','2020-11-02 12:29:44',NULL,'0000-00-00 00:00:00');

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

insert  into `kar_usuario`(`IDUSUARIO`,`CONTRASENIA`,`ESTADO`,`IDPERSONA`,`IDPERFIL`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values ('CMAMANI','e1f62e3cdacb60537a660a436d9616d0549f66f4',1,0197,01,'FLAURA','2020-09-28 01:34:11','FLAURA','2020-09-28 01:34:44'),('FLAURA','458f4c520e432987bb4851036aa393ae26c8cba6',1,0106,01,'ADMIN','2020-09-13 00:15:05','FLAURA','2020-09-13 00:26:31'),('RTICLIA','a4ca05c428c63505b2eb852ea556598c10d51d8f',1,0198,05,'FLAURA','2020-09-28 10:03:27','FLAURA','2020-09-28 10:03:33'),('VENDEDOR','f84ae4782a61dc97f19accb967656c3225743d3a',1,0196,05,'FLAURA','2020-09-16 21:08:35','FLAURA','2020-09-27 20:40:20');

/*Table structure for table `kar_venta` */

DROP TABLE IF EXISTS `kar_venta`;

CREATE TABLE `kar_venta` (
  `nIDVENTA` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nIDLOCAL` int(4) unsigned zerofill NOT NULL,
  `nIDCLIENTE` int(7) unsigned zerofill NOT NULL,
  `dFECHAVENTA` datetime NOT NULL,
  `sOBSERVACION` mediumtext COLLATE utf8_spanish2_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_venta` */

insert  into `kar_venta`(`nIDVENTA`,`nIDLOCAL`,`nIDCLIENTE`,`dFECHAVENTA`,`sOBSERVACION`,`nESTADO`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values (0000000001,0002,0000001,'2020-11-02 12:11:56','','4','FLAURA','2020-11-02 12:11:56','FLAURA','2020-11-02 12:40:55');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_venta_detalle` */

insert  into `kar_venta_detalle`(`nIDVENTADETALLE`,`nIDVENTA`,`nIDCOMPRADETALLE`,`nIDPRODUCTO`,`nCANTIDAD`,`fPRECIO`,`nESTADO`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values (0000000001,0000000001,0000000002,00100,'10',11,4,'FLAURA','2020-11-02 12:38:56','FLAURA','2020-11-02 12:40:55');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_venta_extorno` */

insert  into `kar_venta_extorno`(`nIDEXTORNO`,`nIDVENTA`,`sMOTIVO`,`dFECHA_EXTORNO`,`nESTADO`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values (0000000001,0000000001,'NO LO SE','2020-11-02 12:11:55',1,'FLAURA','2020-11-02 12:40:55',NULL,'0000-00-00 00:00:00');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `kar_venta_pago` */

insert  into `kar_venta_pago`(`nIDVENTAPAGO`,`nIDVENTA`,`nIDTIPOPAGO`,`fMONTO`,`nIDCUENTA`,`sOBSERVACION`,`dFECHAPAGO`,`nESTADO`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values (0000000001,0000000001,01,50,0001,NULL,'2020-11-02 12:11:56',4,'FLAURA','2020-11-02 12:11:56','FLAURA','2020-11-02 12:40:55');

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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `seguridad_modulo` */

insert  into `seguridad_modulo`(`IDMODULO`,`NOMBRE_MODULO`,`DESCRIPCION`,`TIPO`,`UBICACION`,`nIDLOCAL`,`FLAG`,`dFECHAMOD`,`ORDEN`) values (01,'MENU_PANEL','Inicio','MENU_PAN','panel',0003,1,'2020-10-31 22:47:34',1),(02,'MENU_SEGURIDAD','Control de Acceso','MENU_SEG','usuario',0003,1,'2020-10-31 22:47:35',2),(10,'MENU_COMPRA','Registrar Compra','MENU_TIENDA_1','compra',0002,1,'2020-10-31 22:47:40',3),(11,'MENU_CATALOGO','Stock de Productos','MENU_TIENDA_1','stock',0002,1,'2020-10-31 22:52:34',5),(12,'MENU_VENTAS','Registrar Venta','MENU_TIENDA_1','venta',0002,1,'2020-10-31 22:47:48',4),(13,'MENU_REPORTES','Reportes','MENU_TIENDA_1','reporte',0002,1,'2020-10-31 22:48:20',10),(14,'MENU_ANULACION','Anulacion de Compra','MENU_TIENDA_1','anulacion',0002,1,'2020-10-31 22:48:13',7),(15,'MENU_EXTORNO','Extorno de Venta','MENU_TIENDA_1','extorno',0002,1,'2020-10-31 22:48:12',6),(16,'MENU_CUENTA_PAGO','Cuentas por Pagar','MENU_TIENDA_1','cuentapago',0002,1,'2020-10-31 22:48:17',8),(17,'MENU_CUENTA_COBRO','Cuentas por Cobrar','MENU_TIENDA_1','cuentacobro',0002,1,'2020-10-31 22:48:18',9),(18,'MENU_COMPRA','Registrar Compra','MENU_TIENDA_2','compra',0004,1,'2020-11-01 23:38:24',11),(19,'MENU_VENTAS','Registrar Venta','MENU_TIENDA_2','venta',0004,1,'2020-11-01 23:38:25',12),(20,'MENU_CATALOGO','Stock de Productos','MENU_TIENDA_2','stock',0004,1,'2020-11-01 23:38:26',13),(21,'MENU_EXTORNO','Extorno de Venta','MENU_TIENDA_2','extorno',0004,1,'2020-11-01 23:38:26',14),(22,'MENU_ANULACION','Anulacion de Compra','MENU_TIENDA_2','anulacion',0004,1,'2020-11-01 23:38:28',15),(23,'MENU_CUENTA_PAGO','Cuentas por Pagar','MENU_TIENDA_2','cuentapago',0004,1,'2020-11-01 23:38:28',16),(24,'MENU_CUENTA_COBRO','Cuentas por Cobrar','MENU_TIENDA_2','cuentacobro',0004,1,'2020-11-01 23:38:29',17),(25,'MENU_REPORTES','Reportes','MENU_TIENDA_2','reporte',0004,1,'2020-11-01 23:38:29',18);

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
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8;

/*Data for the table `seguridad_modulo_perfil` */

insert  into `seguridad_modulo_perfil`(`IDDETALLE`,`IDMODULO`,`IDPERFIL`,`PERMISO`,`dFECHAMOD`) values (0169,01,01,0,'2020-11-01 23:40:02'),(0170,02,01,0,'2020-11-01 23:40:02'),(0171,10,01,0,'2020-11-01 23:40:03'),(0172,11,01,0,'2020-11-01 23:40:03'),(0173,12,01,0,'2020-11-01 23:40:03'),(0174,13,01,0,'2020-11-01 23:40:03'),(0175,14,01,0,'2020-11-01 23:40:03'),(0176,15,01,0,'2020-11-01 23:40:03'),(0177,16,01,0,'2020-11-01 23:40:03'),(0178,17,01,0,'2020-11-01 23:40:03'),(0179,18,01,0,'2020-11-01 23:40:03'),(0180,19,01,0,'2020-11-01 23:40:04'),(0181,20,01,0,'2020-11-01 23:40:04'),(0182,21,01,0,'2020-11-01 23:40:04'),(0183,22,01,0,'2020-11-01 23:40:04'),(0184,23,01,0,'2020-11-01 23:40:04'),(0185,24,01,0,'2020-11-01 23:40:04'),(0186,25,01,0,'2020-11-01 23:40:04'),(0191,01,07,0,'0000-00-00 00:00:00'),(0192,19,07,0,'0000-00-00 00:00:00'),(0193,20,07,0,'0000-00-00 00:00:00'),(0194,21,07,0,'0000-00-00 00:00:00'),(0195,11,05,0,'0000-00-00 00:00:00'),(0196,12,05,0,'0000-00-00 00:00:00'),(0197,15,05,0,'0000-00-00 00:00:00'),(0198,01,05,0,'0000-00-00 00:00:00');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `seguridad_perfil` */

insert  into `seguridad_perfil`(`IDPERFIL`,`NOMBRE_PERFIL`,`FLAG`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values (01,'ADMINISTRADOR',1,'','2018-04-05 17:32:48','FLAURA','2018-04-10 14:49:05'),(05,'VENDEDOR TIENDA',1,'','2020-09-13 12:50:30','','2020-11-02 12:25:02'),(07,'VENDEDOR ALMACEN',1,'','2020-11-02 12:26:31',NULL,'0000-00-00 00:00:00');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `sel_cliente` */

insert  into `sel_cliente`(`nIDCLIENTE`,`sDESCRIPCION`,`nESTADO`,`sIDUSUARIOCREACION`,`dFECHACREACION`,`sIDUSUARIOMOD`,`dFECHAMOD`) values (0000001,'CLIENTE GENERAL',1,'FLAURA','2020-11-01 23:08:26',NULL,'2020-11-01 23:08:31');

/*Table structure for table `sel_cuenta` */

DROP TABLE IF EXISTS `sel_cuenta`;

CREATE TABLE `sel_cuenta` (
  `nIDCUENTA` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `sDESCRIPCION` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `nESTADO` int(2) DEFAULT 1,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDCUENTA`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

/*Data for the table `sel_cuenta` */

insert  into `sel_cuenta`(`nIDCUENTA`,`sDESCRIPCION`,`nESTADO`,`dFECHAMOD`) values (0001,'',1,'2020-11-01 23:14:43');

/*Table structure for table `sel_estado` */

DROP TABLE IF EXISTS `sel_estado`;

CREATE TABLE `sel_estado` (
  `nIDESTADO` int(20) NOT NULL AUTO_INCREMENT,
  `sDESCRIPCION` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nESTADO` int(1) DEFAULT 1,
  `dFECHAMOD` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIDESTADO`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `sel_estado` */

insert  into `sel_estado`(`nIDESTADO`,`sDESCRIPCION`,`nESTADO`,`dFECHAMOD`) values (1,'ACTIVAS',1,'2020-10-15 17:07:10'),(2,'INACTIVAS',0,'2020-10-15 17:07:15'),(3,'ANULADAS',1,'2020-10-15 17:07:18'),(4,'EXTORNADAS',1,'2020-10-15 17:07:20');

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

insert  into `sel_proveedor`(`nIDPROVEEDOR`,`sDESCRIPCION`,`nESTADO`,`dFECHAMOD`) values (0001,'PROVEEDOR GENETAL',1,'2020-11-01 23:11:24');

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
 `nTOTAL` double 
)*/;

/*Table structure for table `vw_sel_productos_stock` */

DROP TABLE IF EXISTS `vw_sel_productos_stock`;

/*!50001 DROP VIEW IF EXISTS `vw_sel_productos_stock` */;
/*!50001 DROP TABLE IF EXISTS `vw_sel_productos_stock` */;

/*!50001 CREATE TABLE  `vw_sel_productos_stock`(
 `nLOCAL` int(4) unsigned zerofill ,
 `sLOCAL` char(100) ,
 `nIDPRODUCTO` int(5) unsigned zerofill ,
 `sNOMBRE` char(150) ,
 `nCANTIDAD` double 
)*/;

/*View structure for view vw_sel_productos_inversion */

/*!50001 DROP TABLE IF EXISTS `vw_sel_productos_inversion` */;
/*!50001 DROP VIEW IF EXISTS `vw_sel_productos_inversion` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`fzbsokgg_flaura`@`190.233.137.%` SQL SECURITY DEFINER VIEW `vw_sel_productos_inversion` AS (select `az`.`nLOCAL` AS `nLOCAL`,`az`.`sLOCAL` AS `sLOCAL`,`az`.`nIDPRODUCTO` AS `nIDPRODUCTO`,`az`.`sNOMBRE` AS `sNOMBRE`,`az`.`nCANTIDAD` AS `nCANTIDAD`,`az`.`nTOTAL` AS `nTOTAL` from (select `a`.`nIDLOCAL` AS `nLOCAL`,`d`.`sDESCRIPCION` AS `sLOCAL`,`a`.`nIDPRODUCTO` AS `nIDPRODUCTO`,`a`.`sNOMBRE` AS `sNOMBRE`,(select sum(`b`.`nCANTIDAD`) AS `nCANTIDAD` from `fzbsokgg_tauro_kardex_v2`.`kar_compra_detalle` `b` where `a`.`nIDPRODUCTO` = `b`.`nIDPRODUCTO` and `b`.`nESTADO` = 1) - (select case when sum(`c`.`nCANTIDAD`) is not null then sum(`c`.`nCANTIDAD`) else 0 end AS `ventas` from `fzbsokgg_tauro_kardex_v2`.`kar_venta_detalle` `c` where `a`.`nIDPRODUCTO` = `c`.`nIDPRODUCTO` and `c`.`nESTADO` = 1) AS `nCANTIDAD`,(select sum(`z`.`nCANTIDAD` * `z`.`fPRECIO`) AS `total` from `fzbsokgg_tauro_kardex_v2`.`kar_compra_detalle` `z` where `z`.`nIDPRODUCTO` = `a`.`nIDPRODUCTO` and `z`.`bSTOCK` = '1' and `z`.`nESTADO` = '1') AS `nTOTAL` from (`fzbsokgg_tauro_kardex_v2`.`kar_producto` `a` join `fzbsokgg_tauro_kardex_v2`.`sel_local` `d` on(`a`.`nIDLOCAL` = `d`.`nIDLOCAL`)) where `a`.`nESTADO` = 1 group by `a`.`nIDPRODUCTO`) `az` where `az`.`nCANTIDAD` > 0 order by `az`.`sNOMBRE`) */;

/*View structure for view vw_sel_productos_stock */

/*!50001 DROP TABLE IF EXISTS `vw_sel_productos_stock` */;
/*!50001 DROP VIEW IF EXISTS `vw_sel_productos_stock` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`fzbsokgg_flaura`@`190.233.137.%` SQL SECURITY DEFINER VIEW `vw_sel_productos_stock` AS select `az`.`nLOCAL` AS `nLOCAL`,`az`.`sLOCAL` AS `sLOCAL`,`az`.`nIDPRODUCTO` AS `nIDPRODUCTO`,`az`.`sNOMBRE` AS `sNOMBRE`,`az`.`nCANTIDAD` AS `nCANTIDAD` from (select `a`.`nIDLOCAL` AS `nLOCAL`,`d`.`sDESCRIPCION` AS `sLOCAL`,`a`.`nIDPRODUCTO` AS `nIDPRODUCTO`,`a`.`sNOMBRE` AS `sNOMBRE`,(select sum(`b`.`nCANTIDAD`) AS `nCANTIDAD` from `fzbsokgg_tauro_kardex_v2`.`kar_compra_detalle` `b` where `a`.`nIDPRODUCTO` = `b`.`nIDPRODUCTO` and `b`.`nESTADO` = 1) - (select case when sum(`c`.`nCANTIDAD`) is not null then sum(`c`.`nCANTIDAD`) else 0 end AS `ventas` from `fzbsokgg_tauro_kardex_v2`.`kar_venta_detalle` `c` where `a`.`nIDPRODUCTO` = `c`.`nIDPRODUCTO` and `c`.`nESTADO` = 1) AS `nCANTIDAD` from (`fzbsokgg_tauro_kardex_v2`.`kar_producto` `a` join `fzbsokgg_tauro_kardex_v2`.`sel_local` `d` on(`a`.`nIDLOCAL` = `d`.`nIDLOCAL`)) where `a`.`nESTADO` = 1 group by `a`.`nIDPRODUCTO`) `az` where `az`.`nCANTIDAD` > 0 order by `az`.`sNOMBRE` */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
