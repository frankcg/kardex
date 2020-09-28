<?php 
Class compraModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}	

	public function getCompras(){
		$sql = "SELECT b.NOMBRE AS PRODUCTO, DATE_FORMAT(a.FECHA_COMPRA,'%d/%m/%Y') AS FECHA_COMPRA, (a.CANTIDAD*a.PRECIO_UNIDAD) AS PRECIO_TOTAL,  a.IDCOMPRA, a.IDPRODUCTO, a.PRECIO_UNIDAD, a.CANTIDAD, a.ALIAS , a.OBSERVACION, (SELECT COUNT(*) FROM kar_venta z WHERE z.IDCOMPRA=a.IDCOMPRA) AS VENTA
			FROM kar_compra a INNER JOIN kar_producto b ON a.IDPRODUCTO=b.IDPRODUCTO AND b.ESTADO=1
			WHERE a.ESTADO=1 ORDER BY FECHA_COMPRA DESC";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function autocomplete($valor){		
		$sql="SELECT * FROM kar_producto WHERE ESTADO=1 AND NOMBRE LIKE '%$valor%'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}
	
	public function addCompra($nombre, $cantidad, $precioCompra, $aliasCompra, $descripcion){
		$user=$_SESSION['user'];
		date_default_timezone_set('America/Lima');
		$fechaHoraActual = date('Y-m-d H:m:s');

		$sql="SELECT * FROM kar_producto WHERE NOMBRE='$nombre' AND ESTADO=1";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);

		if($result->num_rows){
			$producto=$result->fetch_object();		
			$idProducto = $producto->IDPRODUCTO;

			$sql="INSERT INTO kar_compra SET IDPRODUCTO=$idProducto, CANTIDAD='$cantidad', PRECIO_UNIDAD='$precioCompra', ALIAS='$aliasCompra', OBSERVACION='$descripcion', FECHA_COMPRA='$fechaHoraActual', IDUSUARIOCREACION='$user'";
			$this->_db->query($sql) or die ('Error en '.$sql);

		}else{
			$sql="INSERT INTO kar_producto SET NOMBRE='$nombre', IDUSUARIOCREACION='$user'";
			$this->_db->query($sql) or die ('Error en '.$sql);

			$idProducto=$this->_db->insert_id;

			$sql="INSERT INTO kar_compra SET IDPRODUCTO=$idProducto, CANTIDAD='$cantidad', PRECIO_UNIDAD='$precioCompra', ALIAS='$aliasCompra', OBSERVACION='$descripcion', FECHA_COMPRA='$fechaHoraActual', IDUSUARIOCREACION='$user'";
			$this->_db->query($sql) or die ('Error en '.$sql);
		}

		if($this->_db->errno)
			return false;
		return true;
	}

	public function eliminarCompra($idCompra, $estado){
		$user=$_SESSION['user'];
		$sql="UPDATE kar_compra SET estado='$estado', idusuariomod = '$user' WHERE IDCOMPRA = '$idCompra' ";
		$this->_db->query($sql) or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function updateCompra($idCompra, $nombre, $cantidad, $precioCompra, $aliasCompra, $descripcion){
		$user=$_SESSION['user'];		

		$sql="UPDATE kar_compra SET CANTIDAD='$cantidad', PRECIO_UNIDAD='$precioCompra', ALIAS='$aliasCompra', OBSERVACION='$descripcion',IDUSUARIOMOD='$user' WHERE IDCOMPRA=$idCompra";
			$this->_db->query($sql) or die ('Error en '.$sql);

		if($this->_db->errno)
			return false;
		return true;
	}

}

?>