<?php 
Class cuentacobroModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getCuentasPorCobrar($codLocal, $codVenta, $fechaInicio, $fechafin){

		
		$filtroVenta='';
		$filtroFecha='';

		if($codVenta!='vacio'){
			$filtroVenta = "AND a.nIDVENTA=$codVenta";
		}

		if($fechaInicio!='' && $fechafin!=''){
			$filtroFecha = "AND a.dFECHAVENTA BETWEEN '$fechaInicio 00:00:00' AND '$fechafin 23:59:59'";
		}
		
		$sql="
			SELECT a.*, b.sDESCRIPCION AS sCLIENTE, c.sDESCRIPCION AS sLOCAL
			FROM 
			(
				SELECT 
					a.dFECHAVENTA, 
					a.nIDVENTA, 
					a.nIDCLIENTE, 
					a.nIDLOCAL, 
					a.sOBSERVACION, 
					a.nCantidadTotalVenta,
					a.sCostoTotalVenta, 
					ROUND(SUM(c.fMONTO),2) AS sPagoTotalVenta, 
					ROUND(a.sCostoTotalVenta - SUM(ROUND(c.fMONTO,2)),2) AS 'sDeudaTotalVenta'
				FROM 
				(
					SELECT 
					a.dFECHAVENTA,
					a.nIDVENTA,
					a.nIDCLIENTE,
					a.nIDLOCAL,
					a.sOBSERVACION,
					SUM(b.nCANTIDAD) AS nCantidadTotalVenta,
					ROUND(SUM(b.nCANTIDAD * b.fPRECIO),2) AS sCostoTotalVenta
					FROM kar_venta a 
					INNER JOIN kar_venta_detalle b ON a.nIDVENTA = b.nIDVENTA AND b.nESTADO=1
					WHERE a.nidlocal=$codLocal and a.nESTADO=1 
					$filtroVenta
					$filtroFecha
					GROUP BY a.dFECHAVENTA,
					a.nIDVENTA,
					a.nIDCLIENTE,
					a.nIDLOCAL,
					a.sOBSERVACION
				) 
				AS a INNER JOIN kar_venta_pago c ON a.nIDVENTA = c.nIDVENTA AND c.nESTADO=1
				GROUP BY a.dFECHAVENTA,
				a.nIDVENTA,
				a.nIDCLIENTE,
				a.nIDLOCAL,
				a.sOBSERVACION,
				a.sCostoTotalVenta
			) AS a 
			INNER JOIN sel_cliente b ON a.nIDCLIENTE = b.nIDCLIENTE AND b.nESTADO=1
			INNER JOIN sel_local c ON a.nIDLOCAL = c.nIDLOCAL AND c.nESTADO=1
			WHERE a.sDeudaTotalVenta>0
		";
		//echo $sql; exit();
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getDetalleVentaPago($idVenta){
		$sql="SELECT a.nIDVENTAPAGO,
			a.nIDVENTA,
			a.nIDTIPOPAGO,
			a.fMONTO,
			a.nIDCUENTA,
			a.sOBSERVACION,
			a.dFECHAPAGO,
			b.sDESCRIPCION AS sTIPOPAGO,
			c.sDESCRIPCION AS sNROCUENTA
			FROM kar_venta_pago a 
			INNER JOIN sel_tipopago b ON a.nIDTIPOPAGO = b.nIDTIPOPAGO AND b.nESTADO = 1
			INNER JOIN sel_cuenta c ON a.nIDCUENTA=c.nIDCUENTA AND c.nESTADO=1
			WHERE a.nESTADO=1 
			AND a.nIDVENTA=$idVenta ORDER BY a.nIDVENTAPAGO DESC";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function addPago($idVenta, $observacionVentaPago, $fechaPago, $formaPago, $cuenta, $montopago){

		$user=$_SESSION['user'];

		$sql="INSERT INTO kar_venta_pago 
				SET nIDVENTA=$idVenta,
				nIDTIPOPAGO=$formaPago,
				fMONTO=$montopago,
				nIDCUENTA=$cuenta,
				sOBSERVACION='$observacionVentaPago',
				dFECHAPAGO='$fechaPago',
				sIDUSUARIOCREACION='$user' ";

		$this->_db->query($sql)or die ('Error en '.$sql);
		return $this->_db->insert_id;
	}

}

?>