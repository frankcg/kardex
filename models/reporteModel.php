<?php 
Class reporteModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getEstados(){		
		$sql=	" SELECT
							nIDESTADO
							, sDESCRIPCION
							, nESTADO
							, dFECHACREACION
					FROM fzbsokgg_tauro_kardex_v2.sel_estado
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
	
	
	// public function getVentas($idLocal,$fechaInicio, $fechafin,$estado){

		
	// 	$filtroVenta='';
	// 	$filtroFecha='';

	// 	if($codVenta!='vacio'){
	// 		$filtroVenta = "AND a.nIDVENTA=$codVenta";
	// 	}

	// 	if($fechaInicio!='' && $fechafin!=''){
	// 		$filtroFecha = "AND a.dFECHAVENTA BETWEEN '$fechaInicio 00:00:00' AND '$fechafin 23:59:59'";
	// 	}
		


	// 	$sql="SELECT a.*, b.sDESCRIPCION AS sCLIENTE, c.sDESCRIPCION AS sLOCAL
	// 	FROM 
	// 	(
	// 		SELECT 
	// 			a.dFECHAVENTA, 
	// 			a.nIDVENTA, 
	// 			a.nIDCLIENTE, 
	// 			a.nIDLOCAL, 
	// 			a.sOBSERVACION, 
	// 			a.nCantidadTotalVenta,
	// 			a.sCostoTotalVenta, 
	// 			ROUND(SUM(c.fMONTO),2) AS sPagoTotalVenta, 
	// 			ROUND(a.sCostoTotalVenta - SUM(ROUND(c.fMONTO,2)),2) AS 'sDeudaTotalVenta'
	// 		FROM 
	// 		(
	// 			SELECT 
	// 			a.dFECHAVENTA,
	// 			a.nIDVENTA,
	// 			a.nIDCLIENTE,
	// 			a.nIDLOCAL,
	// 			a.sOBSERVACION,
	// 			SUM(b.nCANTIDAD) AS nCantidadTotalVenta,
	// 			ROUND(SUM(b.nCANTIDAD * b.fPRECIO),2) AS sCostoTotalVenta
	// 			FROM kar_venta a 
	// 			INNER JOIN kar_venta_detalle b ON a.nIDVENTA = b.nIDVENTA AND b.nESTADO=1
	// 			WHERE a.nidlocal='0002' AND a.nESTADO=1 

	// 			GROUP BY a.dFECHAVENTA,
	// 			a.nIDVENTA,
	// 			a.nIDCLIENTE,
	// 			a.nIDLOCAL,
	// 			a.sOBSERVACION
	// 		) 
	// 		AS a LEFT JOIN kar_venta_pago c ON a.nIDVENTA = c.nIDVENTA AND c.nESTADO=1
	// 		GROUP BY a.dFECHAVENTA,
	// 		a.nIDVENTA,
	// 		a.nIDCLIENTE,
	// 		a.nIDLOCAL,
	// 		a.sOBSERVACION,
	// 		a.sCostoTotalVenta
	// 		) AS a 
	// 		INNER JOIN sel_cliente b ON a.nIDCLIENTE = b.nIDCLIENTE 
	// 		INNER JOIN sel_local c ON a.nIDLOCAL = c.nIDLOCAL 
	// 			 ";
				
	// 	$response=$this->_db->query($sql)or die ('Error en '.$sql);
	// 	return $response;
	// }


	public function getCompras($idLocal,$fechaInicio, $fechafin,$estado){
		$sql="SELECT 
				b.nIDCOMPRA
				,d.nIDLOCAL
				, d.sDESCRIPCION nLOCAL
				, b.dFECHACOMPRA
				,e.sDESCRIPCION 
				,(SELECT TRUNCATE(SUM(z.FPRECIO*z.NCANTIDAD),3) AS TOTAL  FROM  kar_compra_detalle AS z WHERE  z.nIDCOMPRA=b.nIDCOMPRA) total
				FROM
				kar_compra AS b 
				INNER JOIN sel_local AS d 
				ON b.nIDLOCAL = d.nIDLOCAL 
				INNER JOIN sel_proveedor AS e 
				ON e.nIDPROVEEDOR = b.nIDPROVEEDOR 
				WHERE 	d.nIDLOCAL='$idLocal'
				AND b.dFECHACOMPRA BETWEEN '$fechaInicio 00:00:00' AND '$fechafin 23:59:59'
				AND b.nESTADO = '$estado'
				ORDER BY  b.nIDCOMPRA DESC
				 ";
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
				ROUND(SUM(c.fMONTO),2) AS sPagoTotalCompra, 
				ROUND(a.sCostoTotalCompra - SUM(ROUND(c.fMONTO,2)),2) AS 'sDeudaTotalCompra'
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
					AS a INNER JOIN kar_compra_pago c ON a.nIDCOMPRA = c.nIDCOMPRA AND c.nESTADO=1
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
					a.nIDCLIENTE,
					a.nIDLOCAL,
					a.sOBSERVACION,
					SUM(b.nCANTIDAD) AS nCantidadTotalVenta,
					ROUND(SUM(b.nCANTIDAD * b.fPRECIO),2) AS sCostoTotalVenta
					FROM kar_venta a 
					INNER JOIN kar_venta_detalle b ON a.nIDVENTA = b.nIDVENTA AND b.nESTADO='$tipoVenta'
					WHERE a.nidlocal=$codLocal and a.nESTADO='$tipoVenta' 
					$filtroFecha
					GROUP BY a.dFECHAVENTA,
					a.nIDVENTA,
					a.nIDCLIENTE,
					a.nIDLOCAL,
					a.sOBSERVACION
				) 
				AS a INNER JOIN kar_venta_pago c ON a.nIDVENTA = c.nIDVENTA AND c.nESTADO='$tipoVenta'
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
		// echo $sql; exit();
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
				, (az.sCostoTotalVentas - (IFNULL(az.efectivo,0) + IFNULL(az.deposito,0))) AS credito
			FROM (
			SELECT 
			DATE(a.dFECHAVENTA) AS FECHA
				,a.nIDVENTA
				, COUNT(DISTINCT a.nIDVENTA) AS nCantidadVentas
				, SUM(b.nCANTIDAD) AS nCantidadTotalProductosVentas
				, ROUND(SUM(b.nCANTIDAD * b.fPRECIO), 2) AS sCostoTotalVentas
				,(SELECT ROUND(SUM(c.fMONTO),2) FROM kar_venta_pago c WHERE c.nIDVENTA=a.nIDVENTA AND  c.nIDTIPOPAGO = '01'  ) AS efectivo 
				,(SELECT ROUND(SUM(c.fMONTO),2) FROM kar_venta_pago c WHERE c.nIDVENTA=a.nIDVENTA AND  c.nIDTIPOPAGO = '02' ) AS deposito 
			FROM
			kar_venta a 
			LEFT JOIN kar_venta_detalle b 
				ON a.nIDVENTA = b.nIDVENTA 
				AND b.nESTADO = 1 
				WHERE a.nidlocal = '$idLocal' 
				$filtroFecha
				AND a.nESTADO = 1 
				GROUP BY DATE(a.dFECHAVENTA)
			,a.nIDVENTA
			)az
			GROUP BY az.FECHA;";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}



}

?>

 