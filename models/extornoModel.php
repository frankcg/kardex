<?php 
Class extornoModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getVentas($codLocal, $codVenta, $fechaInicio, $fechafin){

		
		$filtroVenta='';
		$filtroFecha='';

		if($codVenta!='vacio'){
			$filtroVenta = "AND a.nIDVENTA=$codVenta";
		}

		if($fechaInicio!='' && $fechafin!=''){
			$filtroFecha = "AND a.dFECHAVENTA BETWEEN '$fechaInicio 00:00:00' AND '$fechafin 23:59:59'";
		}
		
		$sql="SELECT a.nIDLOCAL, a.nIDVENTA, a.nIDCLIENTE, a.dFECHAVENTA, 
			SUM(CONVERT(b.nCANTIDAD,UNSIGNED INTEGER)) AS CANTIDAD_TOTAL_VENTA, 
			ROUND(SUM(b.nCANTIDAD*b.fPRECIO),2) AS COSTO_TOTAL_VENTA, 
			c.sDESCRIPCION, a.sOBSERVACION
			FROM kar_venta a 
			INNER JOIN kar_venta_detalle b ON a.nIDVENTA=b.nIDVENTA AND b.nESTADO=1
			INNER JOIN sel_cliente c ON a.nIDCLIENTE=c.nIDCLIENTE AND c.nESTADO=1
			WHERE a.nidlocal=$codLocal AND a.nestado=1
			$filtroVenta
			$filtroFecha
			GROUP BY a.nIDLOCAL, a.nIDVENTA, a.nIDCLIENTE, a.dFECHAVENTA";
		
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getDetalleVenta($idVenta){
		$sql = "SELECT
			a.nIDVENTA,
			a.nIDPRODUCTO,
			b.sNOMBRE AS PRODUCTO, 
			SUM(a.nCANTIDAD) AS 'nCANTIDAD',
			a.fPRECIO,
			ROUND(a.fPRECIO*SUM(a.nCANTIDAD),2) AS 'COSTO'
			FROM kar_venta_detalle a INNER JOIN kar_producto b ON a.nIDPRODUCTO=b.nIDPRODUCTO AND b.nESTADO=1
			WHERE a.nIDVENTA = $idVenta
			GROUP BY a.nIDVENTA, a.nIDPRODUCTO, b.sNOMBRE, a.fPRECIO";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function updateStockCompra($idVenta){
		$user=$_SESSION['user'];
		$data = array();

		$sql = "SELECT nIDCOMPRADETALLE FROM kar_venta_detalle WHERE nIDVENTA=$idVenta";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);

		while($reg=$result->fetch_object()){
			$data[] = $reg->nIDCOMPRADETALLE;
		}

		$arrayIdCompraDetalle = join($data,',');

		$sql2 = "UPDATE kar_compra_detalle SET bSTOCK=1, sIDUSUARIOMOD='$user' WHERE nIDCOMPRADETALLE IN ($arrayIdCompraDetalle)";
		$this->_db->query($sql2)or die ('Error en '.$sql2);

		if($this->_db->errno)
			return false;
		return true;
	}

	public function extornarVenta($idVenta, $motivo){
		$user=$_SESSION['user'];
		date_default_timezone_set('America/Lima');
		$fechaHoraActual = date('Y-m-d H:m:s');

		$sql = "UPDATE kar_venta SET nESTADO=4, sIDUSUARIOMOD='$user' WHERE nIDVENTA=$idVenta";
		$this->_db->query($sql)or die ('Error en '.$sql);

		$sql2 = "UPDATE kar_venta_detalle SET nESTADO=4, sIDUSUARIOMOD='$user' WHERE nIDVENTA=$idVenta";
		$this->_db->query($sql2)or die ('Error en '.$sql2);

		$sql3 = "UPDATE kar_venta_pago SET nESTADO=4, sIDUSUARIOMOD='$user' WHERE nIDVENTA=$idVenta";
		$this->_db->query($sql3)or die ('Error en '.$sql3);

		$sql4 = "INSERT INTO kar_venta_extorno SET nIDVENTA=$idVenta, sMOTIVO='$motivo', dFECHA_EXTORNO='$fechaHoraActual', sIDUSUARIOCREACION='$user'";
		$this->_db->query($sql4)or die ('Error en '.$sql4);

		return $this->_db->insert_id;
	}

}

?>