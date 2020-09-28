<?php 
Class catalogoModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getCatalogo(){
		$sql="SELECT * FROM (
			SELECT a.CANTIDAD AS STOCK_GENERAL, IF(b.CANT_VENTA,b.CANT_VENTA,0) AS CANT_VENTA, (a.CANTIDAD-IF(b.CANT_VENTA,b.CANT_VENTA,0)) AS STOCK_ACTUAL
			,c.NOMBRE, a.PRECIO_UNIDAD, a.IDPRODUCTO, a.IDCOMPRA
			FROM kar_compra a 
			LEFT JOIN(SELECT a.IDCOMPRA, SUM(a.CANTIDAD) AS CANT_VENTA FROM kar_venta a WHERE a.ESTADO=1 GROUP BY a.IDCOMPRA) b 
			ON a.IDCOMPRA = b.IDCOMPRA AND a.ESTADO=1
			INNER JOIN `kar_producto` c ON a.IDPRODUCTO = c.IDPRODUCTO AND c.ESTADO=1	
			WHERE a.ESTADO=1
		) AS a WHERE a.STOCK_ACTUAL>0";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}
}
?>