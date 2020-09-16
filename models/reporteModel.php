<?php 
Class reporteModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getReporteFecha($fechaInicio, $fechafin){
		
		$sql="SELECT a.*, b.NOMBRE AS PRODUCTO, c.MARCA, c.MODELO, CONCAT(e.NOMBRE,' ',e.AP_PATERNO,' ',e.AP_MATERNO) AS VENDEDOR
			FROM `kar_venta` a 
			INNER JOIN `kar_producto` b ON a.IDPRODUCTO = b.IDPRODUCTO AND b.ESTADO=1
			INNER JOIN `kar_producto_detalle` c ON a.IDPRODUCTODETALLE = c.IDPRODUCTODETALLE AND c.ESTADO=1
			INNER JOIN `kar_usuario` d ON a.IDVENDEDOR = d.IDUSUARIO AND d.ESTADO=1
			INNER JOIN `kar_persona` e ON d.IDPERSONA = e.IDPERSONA AND e.ESTADO=1
			WHERE a.ESTADO=1 AND a.FECHA_VENTA BETWEEN '$fechaInicio 00:00:00' AND '$fechafin 23:59:59'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}
}

?>