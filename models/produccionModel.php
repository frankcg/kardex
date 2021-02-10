<?php 
Class produccionModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getVentas($codLocal, $codCompra, $fechaInicio, $fechafin){
		
		$filtroCompra='';
		$filtroFecha='';

		if($codCompra!='vacio'){
			$filtroCompra = "AND a.nIDCOMPRA=$codCompra";
		}

		if($fechaInicio!='' && $fechafin!=''){
			$filtroFecha = "AND a.dFECHACOMPRA BETWEEN '$fechaInicio 00:00:00' AND '$fechafin 23:59:59'";
		}
		
		
		$sql="SELECT a.nIDLOCAL, a.nIDCOMPRA, a.nIDPROVEEDOR, a.dFECHACOMPRA, SUM(CONVERT(b.nCANTIDAD,UNSIGNED INTEGER)) AS CANTIDAD_TOTAL_COMPRA, ROUND(SUM(b.nCANTIDAD*b.fPRECIO),2) AS COSTO_TOTAL_COMPRA,c.sDESCRIPCION, a.sOBSERVACION
			FROM kar_compra a 
			INNER JOIN kar_compra_detalle b ON a.nIDCOMPRA=b.nIDCOMPRA AND b.nESTADO=1
			INNER JOIN sel_proveedor c ON a.nIDPROVEEDOR=c.nIDPROVEEDOR AND c.nESTADO=1
			WHERE a.nidlocal=$codLocal AND a.nestado=1 
			$filtroCompra
			$filtroFecha
			GROUP BY a.nIDLOCAL, a.nIDCOMPRA, a.nIDPROVEEDOR, a.dFECHACOMPRA";
		
		// echo($sql);
		// exit();
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getDetalleCompra($idCompra){
		$sql = "SELECT a.nIDCOMPRA, 
				a.nIDCOMPRADETALLE, 
				a.nIDPRODUCTO, 
				a.nCANTIDAD AS nCANTIDADCOMPRADA, 
				b.sNOMBRE AS PRODUCTO, 
				IFNULL(SUM(c.nCANTIDAD),0) AS nCANTIDADPRODUCIDA,
				(a.nCANTIDAD - IFNULL(SUM(c.nCANTIDAD),0)) AS nDIFERENCIA
				FROM kar_compra_detalle a INNER JOIN kar_producto b ON a.nIDPRODUCTO=b.nIDPRODUCTO AND b.nESTADO=1
				LEFT JOIN kar_compra_produccion c ON a.nIDCOMPRADETALLE=c.nIDCOMPRADETALLE
				WHERE a.nIDCOMPRA = $idCompra AND a.nESTADO=1
				GROUP BY a.nIDCOMPRA, a.nIDCOMPRADETALLE, a.nIDPRODUCTO, a.nCANTIDAD";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getDetalleProduccion($idCompraDetalle){
		$sql = "SELECT nIDPRODUCCION, nIDCOMPRADETALLE, nCANTIDAD, dFECHAPRODUCCION, sIDUSUARIOCREACION 
				FROM kar_compra_produccion 
				WHERE nIDCOMPRADETALLE = $idCompraDetalle AND nESTADO=1
				ORDER BY dFECHAPRODUCCION DESC";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
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