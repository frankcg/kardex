<?php 
Class anulacionModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getCompras($codLocal, $codCompra, $fechaInicio, $fechafin){

		
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
		
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getDetalleCompra($idCompra){
		$sql = "SELECT a.*,  ROUND(a.fPRECIO*a.nCANTIDAD,2) AS COSTO, b.sNOMBRE AS PRODUCTO
				FROM kar_compra_detalle a INNER JOIN kar_producto b ON a.nIDPRODUCTO=b.nIDPRODUCTO AND b.nESTADO=1
				WHERE a.nIDCOMPRA = $idCompra";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function validarAnulacion($idCompra){
		$user=$_SESSION['user'];
		$data = array();

		$sql = "SELECT `nIDCOMPRADETALLE` FROM kar_compra_detalle WHERE nidcompra = $idCompra";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);

		if($result->num_rows){

			while($reg=$result->fetch_object()){
				$data[] = $reg->nIDCOMPRADETALLE;
			}
			$arrayIdCompraDetalle = join($data,',');

			$sql2 = "SELECT * FROM kar_venta_detalle WHERE `nESTADO` = 1 AND `nIDCOMPRADETALLE` IN ($arrayIdCompraDetalle)";
			$result2 = $this->_db->query($sql2)or die ('Error en '.$sql2);

			if($result2->num_rows)
				return true;
			return false;
		}
		return false;
	}

	public function anularCompra($idCompra, $motivo){
		$user=$_SESSION['user'];
		date_default_timezone_set('America/Lima');
		$fechaHoraActual = date('Y-m-d H:m:s');

		$sql = "UPDATE kar_compra SET nESTADO=3, sIDUSUARIOMOD='$user' WHERE nIDCOMPRA=$idCompra";
		$this->_db->query($sql)or die ('Error en '.$sql);

		$sql2 = "UPDATE kar_compra_detalle SET nESTADO=3, sIDUSUARIOMOD='$user' WHERE nIDCOMPRA=$idCompra";
		$this->_db->query($sql2)or die ('Error en '.$sql2);

		$sql3 = "INSERT INTO kar_compra_anulacion SET nIDCOMPRA=$idCompra, sMOTIVO='$motivo', dFECHA_ANULACION='$fechaHoraActual', sIDUSUARIOCREACION='$user'";
		$this->_db->query($sql3)or die ('Error en '.$sql3);

		return $this->_db->insert_id;
	}

}

?>