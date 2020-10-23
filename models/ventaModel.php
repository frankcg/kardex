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

	public function autocuenta($valor){		
		$sql="SELECT * FROM sel_cuenta WHERE nESTADO=1 AND sDESCRIPCION LIKE '$valor%'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
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


	public function autocliente($valor){		
		$sql="SELECT * FROM sel_cliente WHERE nESTADO=1 AND sDESCRIPCION LIKE '$valor%'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}


	public function clientevalidate($idCLiente){		
		$sql="SELECT nIDCLIENTE FROM sel_cliente WHERE nIDCLIENTE='$idCLiente'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		if($result->num_rows){
			return 1;
		}else{
			return 0;
			}
	}


	public function promVentaProductos($idProducto){		
		$sql=	"

		SELECT
			nIDPRODUCTO
			,(SELECT fprecio FROM kar_compra_detalle WHERE nIDPRODUCTO = '$idProducto' ORDER BY dFECHACREACION LIMIT 1)  LAST
			,MAX(fPrecio) MAX
			,MIN(fPRECIO) MIN
			,TRUNCATE(AVG(fPRECIO),2) AVG
		FROM 	kar_compra_detalle
		WHERE	 nIDPRODUCTO = '$idProducto'
			AND nESTADO= '1'
		ORDER 	BY dFECHACREACION DESC
		LIMIT 3;";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}


	
	public function getStockVenta($idProducto){		
		$sql=	"
					SELECT 
					a.nIDCOMPRADETALLE
					,a.nCANTIDAD - (SELECT IFNULL(SUM(b.nCANTIDAD),0) FROM kar_venta_detalle AS b  WHERE a.nIDCOMPRADETALLE = b.nIDCOMPRADETALLE AND b.nIDPRODUCTO = a.nIDPRODUCTO) AS nSTOCK
				FROM
					kar_compra_detalle a
				WHERE a.bSTOCK = 1 
					AND a.nESTADO = 1 
					AND a.nIDPRODUCTO = '$idProducto' 
				ORDER BY a.nIDCOMPRADETALLE ASC  
					";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}






	public function insertCliente($cliente){	
		$user=$_SESSION['user'];
		date_default_timezone_set('America/Lima');
		$fechaHoraActual = date('Y-m-d H:m:s');

		$sql=	"INSERT INTO sel_cliente
				(
				sDESCRIPCION
				,sIDUSUARIOCREACION
				,dFECHACREACION
				)
				VALUES 
				(
				'$cliente'
				,'$user'
				,'$fechaHoraActual'
				);";
		$this->_db->query($sql)or die ('Error en '.$sql);
		$idCLiente=$this->_db->insert_id;
		return $idCLiente;
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
	

	public function insertVenta($codLocal,$idClientecompra,$observaciones){	
		
		$user=$_SESSION['user'];
		date_default_timezone_set('America/Lima');
		$fechaHoraActual = date('Y-m-d H:m:s');

		$sql=	"INSERT INTO kar_venta
						(nIDLOCAL,
						nIDCLIENTE,
						dFECHAVENTA,
						sOBSERVACION,
						sIDUSUARIOCREACION,
						dFECHACREACION
						)
				VALUES ('$codLocal',
						'$idClientecompra',
						'$fechaHoraActual',
						'$observaciones',
						'$user',
						'$fechaHoraActual'
				);";
		$this->_db->query($sql)or die ('Error en '.$sql);
		$idCompra=$this->_db->insert_id;
		return $idCompra;
	}

	public function insertVentaDetalle($idventa,$idCompraDetalle,$idProducto,$cantidadVendida,$precio){		
		$user=$_SESSION['user'];
		$sql=	"INSERT INTO kar_venta_detalle
				(nIDVENTA
				,nIDCOMPRADETALLE
				,nIDPRODUCTO
				,nCANTIDAD
				,fPRECIO
				,sIDUSUARIOCREACION)
				VALUES 
				('$idventa'
				,'$idCompraDetalle'
				,'$idProducto'
				,'$cantidadVendida'
				,'$precio'
				,'$user');";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function insertVentaPagos($idventa,$idtipopago,$montopago,$idCuentaventa){		
		$user=$_SESSION['user'];
		date_default_timezone_set('America/Lima');
		$fechaHoraActual = date('Y-m-d H:m:s');
		$sql=	"INSERT INTO kar_venta_pago
				(nIDVENTA
				,nIDTIPOPAGO
				,fMONTO
				,sCUENTA
				,sIDUSUARIOCREACION
				,dFECHACREACION)
				VALUES 
				('$idventa'
				,'$idtipopago'
				,'$montopago'
				,'$idCuentaventa'
				,'$user'
				,'$fechaHoraActual');";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}
	

	public function updateCompraStockzer0($idCompraDetalle){		
		$sql=		"UPDATE kar_compra_detalle a 
					SET bSTOCK=0 
					WHERE nESTADO = 1 AND nIDCOMPRADETALLE='$idCompraDetalle'";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
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