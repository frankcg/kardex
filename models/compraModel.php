<?php 
Class compraModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}	

	public function getTipopago(){

		$sql="SELECT
				nIDTIPOPAGO,
				sDESCRIPCION
			FROM sel_tipopago";
				
		$response=$this->_db->query($sql)or die ('Error en '.$sql);

		return $response;
	}



	public function getCompras(){
		$sql = "SELECT b.NOMBRE AS PRODUCTO, DATE_FORMAT(a.FECHA_COMPRA,'%d/%m/%Y') AS FECHA_COMPRA, (a.CANTIDAD*a.PRECIO_UNIDAD) AS PRECIO_TOTAL,  a.IDCOMPRA, a.IDPRODUCTO, a.PRECIO_UNIDAD, a.CANTIDAD, a.ALIAS , a.OBSERVACION, (SELECT COUNT(*) FROM kar_venta z WHERE z.IDCOMPRA=a.IDCOMPRA) AS VENTA
			FROM kar_compra a INNER JOIN kar_producto b ON a.IDPRODUCTO=b.IDPRODUCTO AND b.ESTADO=1
			WHERE a.ESTADO=1 ORDER BY FECHA_COMPRA DESC";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function autocomplete($valor){		
		$sql="SELECT * FROM kar_producto WHERE nESTADO=1 AND sNOMBRE LIKE '$valor%' ";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function autoproveedor($valor){		
		$sql="SELECT * FROM sel_proveedor WHERE nESTADO=1 AND sDESCRIPCION LIKE '$valor%'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}
	
	public function autocuenta($valor){		
		$sql="SELECT * FROM sel_cuenta WHERE nESTADO=1 AND sDESCRIPCION LIKE '$valor%'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function productvalidate($idProducto){		
		$sql="SELECT nIDPRODUCTO FROM   kar_producto WHERE nIDPRODUCTO = '$idProducto' AND nESTADO=1 ";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		if($result->num_rows){
			return 1;
		}else{
			return 0;
			}
	}

	public function proveedorvalidate($idProveedor){		
		$sql="SELECT nIDPROVEEDOR FROM sel_proveedor WHERE nIDPROVEEDOR='$idProveedor'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		if($result->num_rows){
			return 1;
		}else{
			return 0;
			}
	}

	public function cuentavalidate($idCuenta){		
		$sql="SELECT * FROM  sel_cuenta WHERE nIDCUENTA = '$idCuenta' ";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		if($result->num_rows){
			return 1;
		}else{
			return 0;
			}
	}

	public function insertCuenta($cuenta){		
		$sql=	"INSERT INTO sel_cuenta
				(sDESCRIPCION)
				VALUES 
				('$cuenta');";
		$this->_db->query($sql)or die ('Error en '.$sql);
		$idCuenta=$this->_db->insert_id;
		return $idCuenta;
	}


	public function insertProveedor($proveedor){		
		$sql=	"INSERT INTO sel_proveedor
				(sDESCRIPCION)
				VALUES 
				('$proveedor');";
		$this->_db->query($sql)or die ('Error en '.$sql);
		$idProveedor=$this->_db->insert_id;
		return $idProveedor;
	}

	public function insertcompra($codLocal,$idproveedor,$observaciones){	
		
		$user=$_SESSION['user'];
		date_default_timezone_set('America/Lima');
		$fechaHoraActual = date('Y-m-d H:m:s');

		$sql=	"INSERT INTO kar_compra
						(nIDLOCAL,
						nIDPROVEEDOR,
						sOBSERVACION,
						dFECHACOMPRA,
						sIDUSUARIOCREACION
						)
				VALUES ('$codLocal',
						'$idproveedor',
						'$observaciones',
						'$fechaHoraActual',
						'$user'
				);";
		$this->_db->query($sql)or die ('Error en '.$sql);
		$idCompra=$this->_db->insert_id;
		return $idCompra;
	}

	public function insertProducto($nombre){		
		$user=$_SESSION['user'];
		date_default_timezone_set('America/Lima');
		$fechaHoraActual = date('Y-m-d H:m:s');
		$sql=	"INSERT INTO kar_producto
				(sNOMBRE
				,dIDUSUARIOCREACION)
				VALUES 
				('$nombre'
				,'$user');";
		$this->_db->query($sql)or die ('Error en '.$sql);
		$idProducto=$this->_db->insert_id;
		return $idProducto;
	}

	public function insertCompraDetalle($idCompra,$idProductocompra,$cantidad,$precio){		
		$user=$_SESSION['user'];
		$sql=	"INSERT INTO kar_compra_detalle
				(nIDCOMPRA
				,nIDPRODUCTO
				,nCANTIDAD
				,fPRECIO
				,sIDUSUARIOCREACION)
				VALUES 
				('$idCompra'
				,'$idProductocompra'
				,'$cantidad'
				,'$precio'
				,'$user');";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function insertCompraPagos($idCompra,$idtipopago,$montopago,$cuenta){		
		$user=$_SESSION['user'];
		$sql=	"INSERT INTO kar_compra_pago
				(nIDCOMPRA
				,nIDTIPOPAGO
				,fMONTO
				,sCUENTA
				,sIDUSUARIOCREACION)
				VALUES 
				('$idCompra'
				,'$idtipopago'
				,'$montopago'
				,'$cuenta'
				,'$user');";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}


	public function promCompraProductos($idProducto){		
		$sql=	"	SELECT  a.fPRECIO, DATE(b.dFECHACOMPRA) AS FECHA , DATEDIFF(CURRENT_TIMESTAMP,b.dFECHACOMPRA) DIFERENCIA
					FROM 	kar_compra_detalle AS a
					INNER JOIN kar_compra AS b ON a.nIDCOMPRA = b.nIDCOMPRA
					WHERE 	a.nIDPRODUCTO = $idProducto
					ORDER BY b.dFECHACOMPRA DESC
					LIMIT 	5 ";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}



	public function addCompraid($idProducto){		
		$sql="SELECT nIDPRODUCTO FROM   kar_producto WHERE nIDPRODUCTO = '$idProducto' AND nESTADO=1 ";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		if($result->num_rows){
			return 1;
		}else{
			return 0;
			}
	}


	public function addCompra($nombre, $cantidad, $precioCompra, $aliasCompra, $descripcion){
		$user=$_SESSION['user'];
		date_default_timezone_set('America/Lima');
		$fechaHoraActual = date('Y-m-d H:m:s');

		$sql="SELECT * FROM kar_producto WHERE sNOMBRE='$nombre' AND nESTADO=1";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);

		if($result->num_rows){
			$producto=$result->fetch_object();		
			$idProducto = $producto->IDPRODUCTO;

			$sql="INSERT INTO kar_compra SET IDPRODUCTO=$idProducto, CANTIDAD='$cantidad', PRECIO_UNIDAD='$precioCompra', ALIAS='$aliasCompra', OBSERVACION='$descripcion', FECHA_COMPRA='$fechaHoraActual', IDUSUARIOCREACION='$user'";
			$this->_db->query($sql) or die ('Error en '.$sql);

		}else{
			$sql="INSERT INTO kar_producto SET sNOMBRE='$nombre', dIDUSUARIOCREACION='$user'";
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