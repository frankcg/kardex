SET FOREIGN_KEY_CHECKS = 0;
	TRUNCATE kar_compra_detalle;
	TRUNCATE kar_compra_pago;
	TRUNCATE kar_compra_anulacion;
	TRUNCATE kar_compra_produccion;
	TRUNCATE kar_compra_taller;
	TRUNCATE TABLE kar_compra; 
SET FOREIGN_KEY_CHECKS = 1;

SET FOREIGN_KEY_CHECKS = 0;
	TRUNCATE kar_venta_detalle;
	TRUNCATE kar_venta_extorno;
	TRUNCATE kar_venta_pago;
	TRUNCATE TABLE kar_venta; 
SET FOREIGN_KEY_CHECKS = 1;

SET FOREIGN_KEY_CHECKS = 0; 
	TRUNCATE TABLE sel_cliente; 
SET FOREIGN_KEY_CHECKS = 1;

SET FOREIGN_KEY_CHECKS = 0; 
	TRUNCATE TABLE sel_proveedor;
	TRUNCATE TABLE sel_cuenta; 
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO sel_cliente (sDESCRIPCION) VALUES('CLIENTE GENERAL') ;
INSERT INTO sel_cliente (sDESCRIPCION, nESTADO) VALUES('TIENDA', 2);
INSERT INTO sel_cliente (sDESCRIPCION, nESTADO) VALUES('ALMACEN', 2);
INSERT INTO sel_cliente (sDESCRIPCION, nESTADO) VALUES('LOCAL LIBRE', 3);
INSERT INTO sel_cliente (sDESCRIPCION, nESTADO) VALUES('LOCAL LIBRE', 3);
INSERT INTO sel_cliente (sDESCRIPCION, nESTADO) VALUES('LOCAL LIBRE', 3);
INSERT INTO sel_cliente (sDESCRIPCION, nESTADO) VALUES('LOCAL LIBRE', 3);
INSERT INTO sel_cliente (sDESCRIPCION, nESTADO) VALUES('LOCAL LIBRE', 3);
INSERT INTO sel_cliente (sDESCRIPCION, nESTADO) VALUES('LOCAL LIBRE', 3);
INSERT INTO sel_cliente (sDESCRIPCION, nESTADO) VALUES('LOCAL LIBRE', 3);
INSERT INTO sel_proveedor (sDESCRIPCION) VALUES('PROVEEDOR GENERAL') ;
INSERT INTO sel_cuenta (sDESCRIPCION) VALUES('') ;
