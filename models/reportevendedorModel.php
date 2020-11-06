<?php 
Class reportevendedorModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getEstados(){		
		$sql=	" SELECT
							nIDESTADO
							, sDESCRIPCION
							, nESTADO
							, dFECHACREACION
					FROM sel_estado
					WHERE nESTADO = 1 ";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}


	public function getReporteFecha($fechaInicio, $fechafin){
		
		$sql="SELECT a.IDVENTA, a.IDPRODUCTO, a.IDCOMPRA, a.CANTIDAD, a.PRECIO_VENTA, DATE_FORMAT(a.FECHA_VENTA,'%d/%m/%Y') AS FECHA_VENTA, a.IDVENDEDOR, a.OBSERVACION, b.NOMBRE AS PRODUCTO, CONCAT(e.NOMBRE,' ',e.AP_PATERNO,' ',e.AP_MATERNO) AS VENDEDOR, f.PRECIO_UNIDAD,	
			(a.CANTIDAD*a.PRECIO_VENTA) AS PRECIO_VENTA_TOTAL, 
			(a.CANTIDAD*f.PRECIO_UNIDAD) AS PRECIO_COMPRA_TOTAL,
			((a.CANTIDAD*a.PRECIO_VENTA) - (a.CANTIDAD*f.PRECIO_UNIDAD)) AS GANANCIA
			FROM kar_venta a 
			INNER JOIN kar_producto b ON a.IDPRODUCTO = b.IDPRODUCTO AND b.ESTADO=1
			INNER JOIN kar_usuario d ON a.IDVENDEDOR = d.IDUSUARIO AND d.ESTADO=1
			INNER JOIN kar_persona e ON d.IDPERSONA = e.IDPERSONA AND e.ESTADO=1
			INNER JOIN kar_compra f ON a.IDCOMPRA=f.IDCOMPRA AND f.ESTADO=1
			WHERE a.ESTADO=1 AND a.FECHA_VENTA BETWEEN '$fechaInicio 00:00:00' AND '$fechafin 23:59:59'
			ORDER BY FECHA_VENTA DESC";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	
	public function getVentasTable($codLocal, $tipoVenta, $fechaInicio, $fechafin){

		$filtroFecha='';

		if($fechaInicio!='' && $fechafin!=''){
			$filtroFecha = "AND a.dFECHAVENTA BETWEEN '$fechaInicio 00:00:00' AND '$fechafin 23:59:59'";
		}
		
		$sql="
			SELECT a.*, b.sDESCRIPCION AS sCLIENTE, c.sDESCRIPCION AS sLOCAL
			FROM 
			(
				SELECT 
					a.dFECHAVENTA, 
					a.nIDVENTA, 
					a.nIDVENTACOMPARTIDA,
					a.nIDCLIENTE, 
					a.nIDLOCAL, 
					a.sOBSERVACION, 
					a.nCantidadTotalVenta,
					a.sCostoTotalVenta, 
					ROUND(SUM(c.fMONTO),2) AS sPagoTotalVenta, 
					ROUND(a.sCostoTotalVenta - SUM(ROUND(c.fMONTO,2)),2) AS 'sDeudaTotalVenta'
				FROM 
				(
					SELECT 
					a.dFECHAVENTA,
					a.nIDVENTA,
					a.nIDVENTACOMPARTIDA,
					a.nIDCLIENTE,
					a.nIDLOCAL,
					a.sOBSERVACION,
					SUM(b.nCANTIDAD) AS nCantidadTotalVenta,
					ROUND(SUM(b.nCANTIDAD * b.fPRECIO),2) AS sCostoTotalVenta
					FROM kar_venta a 
					LEFT JOIN kar_venta_detalle b ON a.nIDVENTACOMPARTIDA = b.nIDVENTA AND b.nESTADO='$tipoVenta'
					WHERE a.nidlocal=$codLocal and a.nESTADO='$tipoVenta' 
					$filtroFecha
					GROUP BY a.dFECHAVENTA,
					a.nIDVENTA,
					a.nIDCLIENTE,
					a.nIDLOCAL,
					a.sOBSERVACION
				) 
				AS a LEFT JOIN kar_venta_pago c ON a.nIDVENTA = c.nIDVENTA AND c.nESTADO='$tipoVenta'
				GROUP BY a.dFECHAVENTA,
				a.nIDVENTA,
				a.nIDCLIENTE,
				a.nIDLOCAL,
				a.sOBSERVACION,
				a.sCostoTotalVenta
			) AS a 
			INNER JOIN sel_cliente b ON a.nIDCLIENTE = b.nIDCLIENTE AND b.nESTADO=1
			INNER JOIN sel_local c ON a.nIDLOCAL = c.nIDLOCAL AND c.nESTADO=1
			 
		";
		//echo $sql; exit();
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}
	
	public function getDetalleVentaPago($idVenta){
		$sql="SELECT a.nIDVENTAPAGO,
			a.nIDVENTA,
			a.nIDTIPOPAGO,
			a.fMONTO,
			a.nIDCUENTA,
			a.sOBSERVACION,
			a.dFECHAPAGO,
			b.sDESCRIPCION AS sTIPOPAGO,
			c.sDESCRIPCION AS sNROCUENTA
			FROM kar_venta_pago a 
			LEFT JOIN sel_tipopago b ON a.nIDTIPOPAGO = b.nIDTIPOPAGO AND b.nESTADO = 1
			LEFT JOIN sel_cuenta c ON a.nIDCUENTA=c.nIDCUENTA AND c.nESTADO=1
			WHERE a.nESTADO=5 
			AND a.nIDVENTA=$idVenta ORDER BY a.nIDVENTAPAGO DESC";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getDetalleVenta($idVenta){
		$sql = "SELECT
			a.nIDVENTA,
			a.nIDPRODUCTO,
			b.sNOMBRE AS PRODUCTO, 
			SUM(a.nCANTIDAD) AS 'nCANTIDAD',
			a.fPRECIO,
			ROUND(a.fPRECIO*SUM(a.nCANTIDAD),2) AS 'COSTO'
			FROM kar_venta_detalle a INNER JOIN kar_producto b ON a.nIDPRODUCTO=b.nIDPRODUCTO AND b.nESTADO=1
			WHERE a.nIDVENTA = $idVenta and a.nESTADO='5'
			GROUP BY a.nIDVENTA, a.nIDPRODUCTO, b.sNOMBRE, a.fPRECIO";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}
	
	public function getCompras($codLocal, $tipoCompra, $fechaInicio, $fechafin){

		$filtroFecha = "AND a.dFECHACOMPRA BETWEEN '$fechaInicio 00:00:00' AND '$fechafin 23:59:59'";

		$sql="SELECT a.*, b.sDESCRIPCION AS sPROVEEDOR, c.sDESCRIPCION AS sLOCAL
		FROM 
		(
			SELECT 
				a.dFECHACOMPRA, 
				a.nIDCOMPRA, 
				a.nIDPROVEEDOR, 
				a.nIDLOCAL, 
				a.sOBSERVACION, 
				a.nCantidadTotalCompra,
				a.sCostoTotalCompra, 
				IFNULL(ROUND(SUM(c.fMONTO),2),0)  AS sPagoTotalCompra, 
				ROUND(a.sCostoTotalCompra - SUM(ROUND(IFNULL(c.fMONTO,0),2)),2) AS 'sDeudaTotalCompra'
			FROM 
			(
				SELECT 
				a.dFECHACOMPRA,
				a.nIDCOMPRA,
				a.nIDPROVEEDOR,
				a.nIDLOCAL,
				a.sOBSERVACION,
				SUM(b.nCANTIDAD) AS nCantidadTotalCompra,
				ROUND(SUM(b.nCANTIDAD * b.fPRECIO),2) AS sCostoTotalCompra
				FROM kar_compra a 
				INNER JOIN kar_compra_detalle b ON a.nIDCOMPRA = b.nIDCOMPRA AND b.nESTADO='$tipoCompra' 
				WHERE a.nidlocal=$codLocal and a.nESTADO='$tipoCompra' 
				$filtroFecha
				GROUP BY a.dFECHACOMPRA,
				a.nIDCOMPRA,
				a.nIDPROVEEDOR,
				a.nIDLOCAL,
				a.sOBSERVACION
					) 
					AS a LEFT JOIN kar_compra_pago c ON a.nIDCOMPRA = c.nIDCOMPRA AND c.nESTADO='$tipoCompra' 
					GROUP BY a.dFECHACOMPRA,
					a.nIDCOMPRA,
					a.nIDPROVEEDOR,
					a.nIDLOCAL,
					a.sOBSERVACION,
					a.sCostoTotalCompra
				) AS a 
				INNER JOIN sel_proveedor b ON a.nIDPROVEEDOR = b.nIDPROVEEDOR AND b.nESTADO='$tipoCompra' 
				INNER JOIN sel_local c ON a.nIDLOCAL = c.nIDLOCAL AND c.nESTADO='$tipoCompra' 
				 ;";
		// echo($sql);
		$response=$this->_db->query($sql)or die ('Error en '.$sql);
		return $response;
	}


	public function getCuentasPorPagar($idLocal,$fechaInicio, $fechafin){

		$filtroFecha = "AND a.dFECHACOMPRA BETWEEN '$fechaInicio 00:00:00' AND '$fechafin 23:59:59'";

		$sql="SELECT a.*, b.sDESCRIPCION AS sPROVEEDOR, c.sDESCRIPCION AS sLOCAL
		FROM 
		(
			SELECT 
				a.dFECHACOMPRA, 
				a.nIDCOMPRA, 
				a.nIDPROVEEDOR, 
				a.nIDLOCAL, 
				a.sOBSERVACION, 
				a.nCantidadTotalCompra,
				a.sCostoTotalCompra, 
				IFNULL(ROUND(SUM(c.fMONTO),2),0)  AS sPagoTotalCompra, 
				ROUND(a.sCostoTotalCompra - SUM(ROUND(IFNULL(c.fMONTO,0),2)),2) AS 'sDeudaTotalCompra'
			FROM 
			(
				SELECT 
				a.dFECHACOMPRA,
				a.nIDCOMPRA,
				a.nIDPROVEEDOR,
				a.nIDLOCAL,
				a.sOBSERVACION,
				SUM(b.nCANTIDAD) AS nCantidadTotalCompra,
				ROUND(SUM(b.nCANTIDAD * b.fPRECIO),2) AS sCostoTotalCompra
				FROM kar_compra a 
				INNER JOIN kar_compra_detalle b ON a.nIDCOMPRA = b.nIDCOMPRA AND b.nESTADO=1
				WHERE a.nidlocal=$idLocal and a.nESTADO=1 
				$filtroFecha
				GROUP BY a.dFECHACOMPRA,
				a.nIDCOMPRA,
				a.nIDPROVEEDOR,
				a.nIDLOCAL,
				a.sOBSERVACION
					) 
					AS a LEFT JOIN kar_compra_pago c ON a.nIDCOMPRA = c.nIDCOMPRA AND c.nESTADO=1
					GROUP BY a.dFECHACOMPRA,
					a.nIDCOMPRA,
					a.nIDPROVEEDOR,
					a.nIDLOCAL,
					a.sOBSERVACION,
					a.sCostoTotalCompra
				) AS a 
				INNER JOIN sel_proveedor b ON a.nIDPROVEEDOR = b.nIDPROVEEDOR AND b.nESTADO=1
				INNER JOIN sel_local c ON a.nIDLOCAL = c.nIDLOCAL AND c.nESTADO=1
				WHERE a.sDeudaTotalCompra>0
				 ";
		$response=$this->_db->query($sql)or die ('Error en '.$sql);
		return $response;
	}

	public function getCuentasPorCobrar($codLocal, $fechaInicio, $fechafin){
		
		$filtroVenta='';
		$filtroFecha='';		

		if($fechaInicio!='' && $fechafin!=''){
			$filtroFecha = "AND a.dFECHAVENTA BETWEEN '$fechaInicio 00:00:00' AND '$fechafin 23:59:59'";
		}
		
		$sql="
			SELECT a.*, b.sDESCRIPCION AS sCLIENTE, c.sDESCRIPCION AS sLOCAL
			FROM 
			(
				SELECT 
					a.dFECHAVENTA, 
					a.nIDVENTA, 
					a.nIDCLIENTE, 
					a.nIDLOCAL, 
					a.sOBSERVACION, 
					a.nCantidadTotalVenta,
					a.sCostoTotalVenta, 
					IFNULL(ROUND(SUM(c.fMONTO),2),0) AS sPagoTotalVenta, 
					ROUND(a.sCostoTotalVenta - SUM(ROUND(IFNULL(c.fMONTO,0),2)),2) AS 'sDeudaTotalVenta'
				FROM 
				(
					SELECT 
					a.dFECHAVENTA,
					a.nIDVENTA,
					a.nIDCLIENTE,
					a.nIDLOCAL,
					a.sOBSERVACION,
					SUM(b.nCANTIDAD) AS nCantidadTotalVenta,
					ROUND(SUM(b.nCANTIDAD * b.fPRECIO),2) AS sCostoTotalVenta
					FROM kar_venta a 
					INNER JOIN kar_venta_detalle b ON a.nIDVENTA = b.nIDVENTA AND b.nESTADO=1
					WHERE a.nidlocal=$codLocal and a.nESTADO=1
					$filtroFecha
					GROUP BY a.dFECHAVENTA,
					a.nIDVENTA,
					a.nIDCLIENTE,
					a.nIDLOCAL,
					a.sOBSERVACION
				) 
				AS a LEFT JOIN kar_venta_pago c ON a.nIDVENTA = c.nIDVENTA AND c.nESTADO=1
				GROUP BY a.dFECHAVENTA,
				a.nIDVENTA,
				a.nIDCLIENTE,
				a.nIDLOCAL,
				a.sOBSERVACION,
				a.sCostoTotalVenta
			) AS a 
			INNER JOIN sel_cliente b ON a.nIDCLIENTE = b.nIDCLIENTE AND b.nESTADO=1
			INNER JOIN sel_local c ON a.nIDLOCAL = c.nIDLOCAL AND c.nESTADO=1
			WHERE a.sDeudaTotalVenta>0
		";
		//echo $sql; exit();
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}


	
	public function getInversion($idLocal){
		$sql="SELECT * FROM vw_sel_productos_inversion WHERE nLOCAL='$idLocal'";
		$response=$this->_db->query($sql)or die ('Error en '.$sql);
		return $response;
	}

	
	public function getReporteBalance($fechaInicio, $fechafin,$idLocal){

		$filtroFecha='';

		if($fechaInicio!='' && $fechafin!=''){
			$filtroFecha = "AND a.dFECHAVENTA BETWEEN '$fechaInicio 00:00:00' AND '$fechafin 23:59:59'";
		}

			$sql="SELECT  az.FECHA
				,SUM(az.nCantidadVentas) ventas
				,SUM(az.nCantidadTotalProductosVentas) ventasProductos
				,SUM(az.sCostoTotalVentas) ventasCosto
				,SUM(az.efectivo) AS efectivo
				,SUM(az.deposito) AS deposito
				,(SUM(az.sCostoTotalVentas) - (IFNULL(SUM(az.efectivo),0) + IFNULL(SUM(az.deposito),0))) AS credito
				,SUM(az.sCostoCompra) AS compraCosto
			FROM (
			SELECT 
			DATE(a.dFECHAVENTA) AS FECHA
				,a.nIDVENTA
				, COUNT(DISTINCT a.nIDVENTA) AS nCantidadVentas
				, SUM(b.nCANTIDAD) AS nCantidadTotalProductosVentas
				, ROUND(SUM(b.nCANTIDAD * b.fPRECIO), 2) AS sCostoTotalVentas
				,IFNULL((SELECT ROUND(SUM(c.fMONTO),2) FROM kar_venta_pago c WHERE c.nIDVENTA=a.nIDVENTA AND  c.nIDTIPOPAGO = '01'),0) AS efectivo 
				,IFNULL((SELECT ROUND(SUM(c.fMONTO),2) FROM kar_venta_pago c WHERE c.nIDVENTA=a.nIDVENTA AND  c.nIDTIPOPAGO = '02' ),0) AS deposito
				, ROUND(SUM(b.nCANTIDAD * c.fPRECIO), 2) AS sCostoCompra
			FROM
			kar_venta a 
			LEFT JOIN kar_venta_detalle b 
				ON a.nIDVENTA = b.nIDVENTA 
				AND b.nESTADO = 1 
				INNER JOIN kar_compra_detalle c 
				ON c.nIDPRODUCTO = b.nIDPRODUCTO 
				AND c.nIDCOMPRADETALLE=b.nIDCOMPRADETALLE
				WHERE a.nidlocal = '$idLocal' 
				$filtroFecha
				AND a.nESTADO = 1 
				GROUP BY DATE(a.dFECHAVENTA)
			,a.nIDVENTA
			)az
			GROUP BY az.FECHA;";
		//echo $sql; exit();
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getDetalleDepositosXDia($fechaPago){
		$sql="SELECT 
			b.sDESCRIPCION AS tipoPago
			,c.sDESCRIPCION AS cuenta
			,SUM(a.fMONTO) fmonto
			FROM
					kar_venta_pago a
			INNER JOIN sel_tipopago b
			ON a.nIDTIPOPAGO=b.nIDTIPOPAGO
			INNER JOIN sel_cuenta c 
			ON a.nidcuenta=c.nIDCUENTA
			WHERE a.nESTADO = 1 
					AND DATE(a.dFECHAPAGO) = DATE('$fechaPago') 
			GROUP BY  a.nIDTIPOPAGO, a.nidcuenta ";
		$response=$this->_db->query($sql)or die ('Error en '.$sql);
		return $response;
	}

	public function getVentasXVendedor($fechaInicio, $fechafin,$idLocal){
		$filtroFecha = "AND a.dFECHAVENTA BETWEEN '$fechaInicio 00:00:00' AND '$fechafin 23:59:59'";
		$sql="SELECT 
			CAST(a.dFECHAVENTA AS DATE) AS dFECHAVENTA,
			a.sIDUSUARIOCREACION AS sVENDEDOR,
			SUM(b.nCANTIDAD) AS nCantidadTotalVenta,
			ROUND(SUM(b.nCANTIDAD * b.fPRECIO),2) AS sCostoTotalVenta					
			FROM kar_venta a 
			INNER JOIN kar_venta_detalle b ON a.nIDVENTA = b.nIDVENTA AND b.nESTADO='1'
			WHERE a.nidlocal=$idLocal AND a.nESTADO=1
			$filtroFecha
			GROUP BY CAST(a.dFECHAVENTA AS DATE),
			a.sIDUSUARIOCREACION";
		$response=$this->_db->query($sql)or die ('Error en '.$sql);
		return $response;
	}	

}

?>

 