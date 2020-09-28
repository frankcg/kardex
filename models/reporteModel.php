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
}

?>