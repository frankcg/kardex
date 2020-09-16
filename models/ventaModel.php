<?php 
Class ventaModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getComboProductos(){
		
		$sql="
				SELECT DISTINCT a.IDPRODUCTO, a.NOMBRE FROM (
				SELECT a.IDSTOCK, a.IDPRODUCTO, a.IDPRODUCTODETALLE, (a.CANTIDAD-IF(SUM(b.CANTIDA_VENDIDA),SUM(b.CANTIDA_VENDIDA),0)) AS STOCK_ACTUAL
					,c.NOMBRE, d.MARCA, d.MODELO
				FROM kar_stock a 
				LEFT JOIN 
				(
				SELECT  a.IDPRODUCTO, a.IDPRODUCTODETALLE, a.IDSTOCK, SUM(CANTIDAD) AS CANTIDA_VENDIDA
				FROM kar_venta a 
				WHERE a.ESTADO=1
				GROUP BY a.IDPRODUCTO, a.IDPRODUCTODETALLE, a.IDSTOCK
				) AS b ON a.IDSTOCK=b.IDSTOCK AND a.ESTADO=1
				INNER JOIN `kar_producto` c ON a.IDPRODUCTO = c.IDPRODUCTO AND c.ESTADO=1 
				INNER JOIN `kar_producto_detalle` d ON a.IDPRODUCTODETALLE = d.IDPRODUCTODETALLE AND d.ESTADO=1
				GROUP BY a.IDSTOCK, a.IDPRODUCTO, a.IDPRODUCTODETALLE
				) AS a WHERE a.STOCK_ACTUAL > 0 ORDER BY NOMBRE, MARCA";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}
	
	public function getProductoDetalle($idProducto){

		$sql="SELECT DISTINCT a.IDPRODUCTO, a.IDPRODUCTODETALLE, a.MARCA, a.MODELO FROM (
				SELECT a.IDSTOCK, a.IDPRODUCTO, a.IDPRODUCTODETALLE, (a.CANTIDAD-IF(SUM(b.CANTIDA_VENDIDA),SUM(b.CANTIDA_VENDIDA),0)) AS STOCK_ACTUAL
					,c.NOMBRE, d.MARCA, d.MODELO
				FROM kar_stock a 
				LEFT JOIN 
				(
				SELECT  a.IDPRODUCTO, a.IDPRODUCTODETALLE, a.IDSTOCK, SUM(CANTIDAD) AS CANTIDA_VENDIDA
				FROM kar_venta a 
				WHERE a.ESTADO=1
				GROUP BY a.IDPRODUCTO, a.IDPRODUCTODETALLE, a.IDSTOCK
				) AS b ON a.IDSTOCK=b.IDSTOCK AND a.ESTADO=1
				INNER JOIN `kar_producto` c ON a.IDPRODUCTO = c.IDPRODUCTO AND c.ESTADO=1 
				INNER JOIN `kar_producto_detalle` d ON a.IDPRODUCTODETALLE = d.IDPRODUCTODETALLE AND d.ESTADO=1
				GROUP BY a.IDSTOCK, a.IDPRODUCTO, a.IDPRODUCTODETALLE
				) AS a WHERE a.STOCK_ACTUAL > 0 AND a.IDPRODUCTO=$idProducto
				ORDER BY MARCA";
				
		$response=$this->_db->query($sql)or die ('Error en '.$sql);

		return $response;
	}

	public function getPrecios($idProductoDetalle){
		$sql="SELECT a.IDSTOCK, a.PRECIO_VENTA FROM (
			SELECT a.IDSTOCK, a.IDPRODUCTO, a.IDPRODUCTODETALLE, (a.CANTIDAD-IF(SUM(b.CANTIDA_VENDIDA),SUM(b.CANTIDA_VENDIDA),0)) AS STOCK_ACTUAL
				,c.NOMBRE, d.MARCA, d.MODELO, a.PRECIO_VENTA
			FROM kar_stock a 
			LEFT JOIN 
			(
			SELECT  a.IDPRODUCTO, a.IDPRODUCTODETALLE, a.IDSTOCK, SUM(CANTIDAD) AS CANTIDA_VENDIDA
			FROM kar_venta a
			WHERE a.ESTADO=1
			GROUP BY a.IDPRODUCTO, a.IDPRODUCTODETALLE, a.IDSTOCK
			) AS b ON a.IDSTOCK=b.IDSTOCK AND a.ESTADO=1
			INNER JOIN `kar_producto` c ON a.IDPRODUCTO = c.IDPRODUCTO AND c.ESTADO=1 
			INNER JOIN `kar_producto_detalle` d ON a.IDPRODUCTODETALLE = d.IDPRODUCTODETALLE AND d.ESTADO=1
			GROUP BY a.IDSTOCK, a.IDPRODUCTO, a.IDPRODUCTODETALLE
			) AS a WHERE a.STOCK_ACTUAL > 0 AND a.IDPRODUCTODETALLE=$idProductoDetalle
			ORDER BY MARCA";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getStockActual($idStock){
		$sql="SELECT a.CANTIDAD AS STOCK_GENERAL, 
			IF(SUM(b.CANTIDAD),SUM(b.CANTIDAD),0) AS CANT_VENDIDA, 
			(a.CANTIDAD-IF(SUM(b.CANTIDAD),SUM(b.CANTIDAD),0) ) AS STOCK_ACTUAL
			FROM kar_stock a INNER JOIN kar_venta b ON a.IDSTOCK = b.IDSTOCK
			WHERE a.IDSTOCK=$idStock AND a.ESTADO=1 AND b.ESTADO=1";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function addVenta($idProducto,$idProductoDetalle,$idStock,$cantidad,$precioVenta,$precioSugerido,$stockActual,$observacion){
		$user=$_SESSION['user'];
		date_default_timezone_set('America/Lima');
		$fechaHoraActual = date('Y-m-d H:m:s');

		$sql="INSERT INTO kar_venta SET `IDPRODUCTO`='$idProducto', `IDPRODUCTODETALLE`='$idProductoDetalle', `IDSTOCK`='$idStock', `CANTIDAD`='$cantidad',`PRECIO_SUGERIDO`='$precioSugerido', `PRECIO_VENDIDO`='$precioVenta', `OBSERVACION`='$observacion', `IDUSUARIOCREACION`='$user', `IDVENDEDOR`='$user', FECHA_VENTA='$fechaHoraActual' ";
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