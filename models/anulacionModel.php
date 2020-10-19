<?php 
Class anulacionModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getCompras($codCompra, $fechaInicio, $fechafin){

		
		$filtroCompra='';
		$filtroFecha='';

		if($codCompra!='vacio'){
			$filtroCompra = "AND a.nIDCOMPRA=$codCompra";
		}

		if($fechaInicio!='' && $fechafin!=''){
			$filtroFecha = "AND a.dFECHACOMPRA BETWEEN '$fechaInicio 00:00:00' AND '$fechafin 23:59:59'";
		}
		
		$sql="SELECT a.nIDLOCAL, a.nIDCOMPRA, a.nIDPROVEEDOR, a.dFECHACOMPRA, SUM(b.nCANTIDAD) AS CANTIDAD_TOTAL_COMPRA, ROUND(SUM(b.nCANTIDAD*b.fPRECIO),2) AS COSTO_TOTAL_COMPRA,c.sLABEL, a.sOBSERVACION
			FROM kar_compra a 
			INNER JOIN kar_compra_detalle b ON a.nIDCOMPRA=b.nIDCOMPRA AND b.nESTADO=1
			INNER JOIN sel_proveedor c ON a.nIDPROVEEDOR=c.nIDPROVEEDOR AND c.nESTADO=1
			WHERE a.nidlocal=1  AND a.nestado=1 
			$filtroCompra
			$filtroFecha
			GROUP BY a.nIDLOCAL, a.nIDCOMPRA, a.nIDPROVEEDOR, a.dFECHACOMPRA";
		
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}	
}

?>