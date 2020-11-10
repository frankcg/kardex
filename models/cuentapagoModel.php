<?php 
Class cuentapagoModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getCuentasPorPagar($codLocal, $codCompra, $fechaInicio, $fechafin, $nIdProveedor){		
		
		$filtroCompra='';
		$filtroFecha='';
		$filtroProveedor = '';

		if($codCompra!='vacio'){
			$filtroCompra = "AND a.nIDCOMPRA=$codCompra";
		}

		if($fechaInicio!='vacio' && $fechafin!='vacio'){
			$filtroFecha = "AND a.dFECHACOMPRA BETWEEN '$fechaInicio 00:00:00' AND '$fechafin 23:59:59'";
		}

		if($nIdProveedor!=null && $nIdProveedor!='null'){
			//echo gettype($nIdCliente); exit();
			$filtroProveedor = "AND a.nIDPROVEEDOR=$nIdProveedor";
		}
		
		$sql="
			SELECT a.*, b.sDESCRIPCION AS sPROVEEDOR, c.sDESCRIPCION AS sLOCAL
			FROM 
			(
				SELECT 
					a.dFECHACOMPRA, 
					a.nIDCOMPRA, 
					a.nIDPROVEEDOR, 
					a.nIDLOCAL, 
					a.sOBSERVACION, 
					a.nCantidadTotalCompra,
					a.sCostoTotalCompra, 
					IFNULL(ROUND(SUM(c.fMONTO),2),0)  AS sPagoTotalCompra, 
					ROUND(a.sCostoTotalCompra - SUM(ROUND(IFNULL(c.fMONTO,0),2)),2) AS 'sDeudaTotalCompra'
				FROM 
				(
					SELECT 
					a.dFECHACOMPRA,
					a.nIDCOMPRA,
					a.nIDPROVEEDOR,
					a.nIDLOCAL,
					a.sOBSERVACION,
					SUM(b.nCANTIDAD) AS nCantidadTotalCompra,
					ROUND(SUM(b.nCANTIDAD * b.fPRECIO),2) AS sCostoTotalCompra
					FROM kar_compra a 
					INNER JOIN kar_compra_detalle b ON a.nIDCOMPRA = b.nIDCOMPRA AND b.nESTADO=1
					WHERE a.nidlocal=$codLocal and a.nESTADO=1 
					$filtroCompra
					$filtroFecha
					$filtroProveedor
					GROUP BY a.dFECHACOMPRA,
					a.nIDCOMPRA,
					a.nIDPROVEEDOR,
					a.nIDLOCAL,
					a.sOBSERVACION
				) 
				AS a LEFT JOIN kar_compra_pago c ON a.nIDCOMPRA = c.nIDCOMPRA AND c.nESTADO=1
				GROUP BY a.dFECHACOMPRA,
				a.nIDCOMPRA,
				a.nIDPROVEEDOR,
				a.nIDLOCAL,
				a.sOBSERVACION,
				a.sCostoTotalCompra
			) AS a 
			INNER JOIN sel_proveedor b ON a.nIDPROVEEDOR = b.nIDPROVEEDOR AND b.nESTADO=1
			INNER JOIN sel_local c ON a.nIDLOCAL = c.nIDLOCAL AND c.nESTADO=1
			WHERE a.sDeudaTotalCompra>0
		";
		//echo $sql; exit();
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getProveedorPorPagar($codLocal){
		$sql="SELECT DISTINCT a.nIDPROVEEDOR, b.sDESCRIPCION AS sPROVEEDOR
			FROM 
			(
				SELECT 
					a.dFECHACOMPRA, 
					a.nIDCOMPRA, 
					a.nIDPROVEEDOR, 
					a.nIDLOCAL, 
					a.sOBSERVACION, 
					a.nCantidadTotalCompra,
					a.sCostoTotalCompra, 
					IFNULL(ROUND(SUM(c.fMONTO),2),0)  AS sPagoTotalCompra, 
					ROUND(a.sCostoTotalCompra - SUM(ROUND(IFNULL(c.fMONTO,0),2)),2) AS 'sDeudaTotalCompra'
				FROM 
				(
					SELECT 
					a.dFECHACOMPRA,
					a.nIDCOMPRA,
					a.nIDPROVEEDOR,
					a.nIDLOCAL,
					a.sOBSERVACION,
					SUM(b.nCANTIDAD) AS nCantidadTotalCompra,
					ROUND(SUM(b.nCANTIDAD * b.fPRECIO),2) AS sCostoTotalCompra
					FROM kar_compra a 
					INNER JOIN kar_compra_detalle b ON a.nIDCOMPRA = b.nIDCOMPRA AND b.nESTADO=1
					WHERE a.nidlocal=$codLocal AND a.nESTADO=1 
					GROUP BY a.dFECHACOMPRA,
					a.nIDCOMPRA,
					a.nIDPROVEEDOR,
					a.nIDLOCAL,
					a.sOBSERVACION
				) 
				AS a LEFT JOIN kar_compra_pago c ON a.nIDCOMPRA = c.nIDCOMPRA AND c.nESTADO=1
				GROUP BY a.dFECHACOMPRA,
				a.nIDCOMPRA,
				a.nIDPROVEEDOR,
				a.nIDLOCAL,
				a.sOBSERVACION,
				a.sCostoTotalCompra
			) AS a 
			INNER JOIN sel_proveedor b ON a.nIDPROVEEDOR = b.nIDPROVEEDOR AND b.nESTADO=1
			WHERE a.sDeudaTotalCompra>0";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getDetalleCompraPago($idCompra){
		$sql="SELECT a.nIDCOMPRAPAGO,
			a.nIDCOMPRA,
			a.nIDTIPOPAGO,
			a.fMONTO,
			a.nIDCUENTA,
			a.sOBSERVACION,
			a.dFECHAPAGO,
			b.sDESCRIPCION AS sTIPOPAGO,
			c.sDESCRIPCION AS sNROCUENTA
			FROM kar_compra_pago a 
			INNER JOIN sel_tipopago b ON a.nIDTIPOPAGO = b.nIDTIPOPAGO AND b.nESTADO = 1
			INNER JOIN sel_cuenta c ON a.nIDCUENTA=c.nIDCUENTA AND c.nESTADO=1
			WHERE a.nESTADO=1 
			AND a.nIDCOMPRA=$idCompra ORDER BY a.nIDCOMPRAPAGO DESC";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function addPago($idCompra, $observacionCompraPago, $fechaPago, $formaPago, $cuenta, $montopago){

		$user=$_SESSION['user'];

		$sql="INSERT INTO kar_compra_pago 
				SET nIDCOMPRA=$idCompra,
				nIDTIPOPAGO=$formaPago,
				fMONTO=$montopago,
				nIDCUENTA=$cuenta,
				sOBSERVACION='$observacionCompraPago',
				dFECHAPAGO='$fechaPago',
				sIDUSUARIOCREACION='$user' ";

		$this->_db->query($sql)or die ('Error en '.$sql);
		return $this->_db->insert_id;
	}

}

?>