<?php 
Class ventaModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getComboProductos(){
		$sql="SELECT * FROM vw_sel_productos_stock";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	
	public function getTipopago(){

		$sql="SELECT
				nIDTIPOPAGO,
				sDESCRIPCION
			FROM sel_tipopago";
				
		$response=$this->_db->query($sql)or die ('Error en '.$sql);

		return $response;
	}
	
	
	public function getCompras($idProducto){

		$sql="SELECT DISTINCT a.IDCOMPRA, a.ALIAS FROM (
			SELECT a.IDCOMPRA, a.IDPRODUCTO,(a.CANTIDAD-IF(SUM(b.CANTIDA_VENDIDA),SUM(b.CANTIDA_VENDIDA),0)) AS STOCK_ACTUAL,c.NOMBRE, a.ALIAS
			FROM kar_compra a 
			LEFT JOIN 
			(
			SELECT  a.IDPRODUCTO, a.IDCOMPRA, SUM(CANTIDAD) AS CANTIDA_VENDIDA
			FROM kar_venta a 
			WHERE a.ESTADO=1
			GROUP BY a.IDPRODUCTO, a.IDCOMPRA
			) AS b ON a.IDCOMPRA=b.IDCOMPRA
			INNER JOIN `kar_producto` c ON a.IDPRODUCTO = c.IDPRODUCTO AND c.ESTADO=1 
			WHERE a.`ESTADO`=1
			GROUP BY a.IDCOMPRA, a.IDPRODUCTO, a.ALIAS
			) AS a WHERE a.STOCK_ACTUAL > 0 AND IDPRODUCTO=$idProducto ORDER BY ALIAS";
				
		$response=$this->_db->query($sql)or die ('Error en '.$sql);

		return $response;
	}

	public function getStockActual($idStock){
		$sql="SELECT a.CANTIDAD AS STOCK_GENERAL, IF(SUM(b.CANTIDAD),SUM(b.CANTIDAD),0) AS CANT_VENDIDA, (a.CANTIDAD-IF(SUM(b.CANTIDAD),SUM(b.CANTIDAD),0) ) AS STOCK_ACTUAL
			FROM kar_compra a INNER JOIN kar_venta b ON a.IDCOMPRA = b.IDCOMPRA AND b.ESTADO=1
			WHERE a.IDCOMPRA=$idStock AND a.ESTADO=1 ";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function addVenta($idProducto,$idCompra,$cantidad,$precioVenta,$observacion){
		$user=$_SESSION['user'];
		date_default_timezone_set('America/Lima');
		$fechaHoraActual = date('Y-m-d H:m:s');

		$sql="INSERT INTO kar_venta SET `IDPRODUCTO`='$idProducto',  `IDCOMPRA`='$idCompra', `CANTIDAD`='$cantidad', `PRECIO_VENTA`='$precioVenta', `OBSERVACION`='$observacion', `IDUSUARIOCREACION`='$user', `IDVENDEDOR`='$user', FECHA_VENTA='$fechaHoraActual' ";
		$this->_db->query($sql) or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function getDetalleProducto($idProductoDetalle){
		$sql="SELECT * FROM `kar_producto_detalle` WHERE `IDPRODUCTODETALLE`=$idProductoDetalle AND `ESTADO`=1";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}
}

?>