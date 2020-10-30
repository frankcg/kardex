<?php 
Class reporteModel extends Model{
	
	public function __construct(){
		parent::__construct();
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
	
	
	public function getVentas($idLocal,$fechaInicio, $fechafin){
		$sql="SELECT 
					b.nIDVENTA
					,d.nIDLOCAL
					, d.sDESCRIPCION nLOCAL
					, b.dFECHAVENTA 
					,e.sDESCRIPCION nCLIENTE
					,(SELECT TRUNCATE(SUM(z.FPRECIO*z.NCANTIDAD),3) AS TOTAL  FROM  kar_venta_detalle AS z WHERE  z.NIDVENTA=b.nIDVENTA) total
				FROM
					kar_venta AS b 
					INNER JOIN sel_local AS d 
					ON b.nIDLOCAL = d.nIDLOCAL 
					INNER JOIN sel_cliente AS e 
					ON e.nIDCLIENTE = b.nIDCLIENTE 
				WHERE 	d.nIDLOCAL='$idLocal'
				AND b.dFECHAVENTA BETWEEN '$fechaInicio 00:00:00' AND '$fechafin 23:59:59'
				ORDER BY  b.nIDVENTA DESC
				 ";
				
		$response=$this->_db->query($sql)or die ('Error en '.$sql);
		return $response;
	}


	public function getCompras($idLocal,$fechaInicio, $fechafin){
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
				AND b.dFECHACOMPRA BETWEEN '$fechaInicio 00:00:00' AND '$fechafin 23:59:59'
				ORDER BY  b.nIDCOMPRA DESC
				 ";
		$response=$this->_db->query($sql)or die ('Error en '.$sql);
		return $response;
	}



}

?>

 