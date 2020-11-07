<?php 
Class ingresoModel extends Model{
	
	public function __construct(){
		parent::__construct();
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

	public function productvalidateNombre($nombreProducto,$codLocal ){		
		$sql="SELECT nIDPRODUCTO FROM   kar_producto WHERE sNOMBRE = '$nombreProducto' AND nIDLOCAL='$codLocal' AND nESTADO=1 ";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
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

	public function insertProducto($nombre, $codLocal){		
		$user=$_SESSION['user'];
		date_default_timezone_set('America/Lima');
		$fechaHoraActual = date('Y-m-d H:m:s');
		$sql=	"INSERT INTO kar_producto
				(sNOMBRE,
				nIDLOCAL,
				sIDUSUARIOCREACION)
				VALUES 
				('$nombre'
				,$codLocal
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
		$this->_db->query($sql)or die ('Error en '.$sql);
		$idCompradetalle=$this->_db->insert_id;
		return $idCompradetalle;
	}

	public function insertCartTaller($idCompradetalle,$cProduccion,$ganancia){		
		$user=$_SESSION['user'];
		$sql=	"INSERT INTO kar_compra_taller
				(nIDCOMPRADETALLE
				,fCOSTOPRODUCCION
				,fGANANCIA
				,sIDUSUARIOCREACION
				)
				VALUES 
				('$idCompradetalle'
				,'$cProduccion'
				,'$ganancia'
				,'$user'
				);";
		$this->_db->query($sql)or die ('Error en '.$sql);
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}


	public function insertCompraPagos($idCompra,$idtipopago,$montopago,$cuenta){	
		date_default_timezone_set('America/Lima');
		$fechaHoraActual = date('Y-m-d H:m:s');	
		$user=$_SESSION['user'];
		$sql=	"INSERT INTO kar_compra_pago
				(nIDCOMPRA
				,nIDTIPOPAGO
				,fMONTO
				,nIDCUENTA
				,sIDUSUARIOCREACION
				,dFECHAPAGO)
				VALUES 
				('$idCompra'
				,'$idtipopago'
				,'$montopago'
				,'$cuenta'
				,'$user'
				,'$fechaHoraActual');";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}



}

?>