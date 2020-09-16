<?php 
Class productoModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getComboProductos(){
		$sql="SELECT * FROM `kar_producto` WHERE ESTADO=1";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getMarcas($idProducto){
		$sql="SELECT * FROM kar_producto_detalle WHERE IDPRODUCTO='$idProducto' AND ESTADO=1";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getProductos(){
		$sql = "SELECT a.IDPRODUCTO, a.NOMBRE, b.IDPRODUCTODETALLE, b.MARCA, b.MODELO, b.DESCRIPCION, DATE_FORMAT(b.FECHACREACION,'%d/%m/%Y') AS FECHA,
				IF(b.ESTADO=1,'ACTIVO','INACTIVO') AS ESTADO
				FROM kar_producto a 
				INNER JOIN kar_producto_detalle b ON a.IDPRODUCTO = b.IDPRODUCTO
				WHERE a.ESTADO=1";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function addProducto($nombre, $modelo, $marca, $descripcion){
		$user=$_SESSION['user'];

		$sql="SELECT * FROM kar_producto WHERE NOMBRE='$nombre'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		if($result->num_rows){
			$producto=$result->fetch_object();		
			$idProducto = $producto->IDPRODUCTO;
		}
			
		if(isset($idProducto)){
			$sql="INSERT INTO kar_producto_detalle SET idproducto='$idProducto', marca='$marca', modelo='$modelo', descripcion='$descripcion', idusuariocreacion='$user' ";
			$this->_db->query($sql) or die ('Error en '.$sql);
		}else{
			$sql="INSERT INTO kar_producto SET nombre='$nombre', idusuariocreacion='$user' ";
			$this->_db->query($sql) or die ('Error en '.$sql);

			$idProducto=$this->_db->insert_id;

			$sql="INSERT INTO kar_producto_detalle SET idproducto='$idProducto', marca='$marca', modelo='$modelo', descripcion='$descripcion', idusuariocreacion='$user' ";
			$this->_db->query($sql) or die ('Error en '.$sql);
		}

		if($this->_db->errno)
			return false;
		return true;
	}

	public function cambiarEstadoProducto($idProducto, $idProductoDetalle, $estado){
		$user=$_SESSION['user'];
		$sql="UPDATE kar_producto_detalle SET estado='$estado', idusuariomod = '$user' WHERE idproductodetalle = '$idProductoDetalle' ";
		$this->_db->query($sql) or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function updateProducto($idProducto, $idProductoDetalle, $nombre, $modelo, $marca, $descripcion){
		$user=$_SESSION['user'];
		$sql="UPDATE kar_producto SET nombre='$nombre', idusuariomod = '$user' WHERE idproducto= '$idProducto' ";
		$this->_db->query($sql) or die ('Error en '.$sql);

		$sql="UPDATE kar_producto_detalle SET marca='$marca', modelo='$modelo', descripcion='$descripcion', idusuariomod = '$user' WHERE idproductodetalle= '$idProductoDetalle'";
		$this->_db->query($sql) or die ('Error en '.$sql);

		if($this->_db->errno)
			return false;
		return true;
	}

	public function getStocks(){
		$sql = "SELECT a.*, DATE_FORMAT(a.FECHACREACION,'%d/%m/%Y') AS FECHA, (a.CANTIDAD*a.PRECIO_VENTA)-a.INVERSION AS GANANCIA, b.NOMBRE AS PRODUCTO, c.MARCA, c.MODELO
				FROM kar_stock a INNER JOIN kar_producto b ON a.IDPRODUCTO = b.IDPRODUCTO
				INNER JOIN kar_producto_detalle c ON a.IDPRODUCTODETALLE = c.IDPRODUCTODETALLE
				WHERE a.ESTADO=1 AND c.ESTADO=1";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function addStock($idProducto, $idDetalleProducto, $cantidad, $precio, $inversion, $observacion){
		$user=$_SESSION['user'];
		$sql="INSERT INTO kar_stock SET idproducto='$idProducto', idproductodetalle='$idDetalleProducto', cantidad='$cantidad', precio_venta='$precio', inversion='$inversion', observacion='$observacion', idusuariocreacion='$user' ";
		$this->_db->query($sql) or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function deleteStock($idStock, $estado){
		$user=$_SESSION['user'];
		$sql="UPDATE kar_stock SET estado='$estado', idusuariomod = '$user' WHERE idstock = '$idStock' ";
		$this->_db->query($sql) or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

}

?>