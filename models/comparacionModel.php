<?php 
Class comparacionModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getpersonal($idevaluacion1, $idevaluacion2){
		$sql="SELECT DISTINCT a.IDEVALUADO, CONCAT(b.`NOMBRE`,', ',b.`AP_PATERNO`,' ',b.`AP_MATERNO`) AS NOMBRE
			FROM dt_prueba a INNER JOIN `dt_persona` b ON a.`IDEVALUADO`=b.`IDPERSONA`
			WHERE a.IDEVALUACION IN ('$idevaluacion1','$idevaluacion2')";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getidevaluacion($idevaluacion, $idevaluado){

		$sql="SELECT DISTINCT c.`NOMBRE` AS IDEVALUACION, a.IDEVALUADO, CONCAT(b.`NOMBRE`,', ',b.`AP_PATERNO`,' ',b.`AP_MATERNO`) AS EVALUADOR, a.CALIFICACION
			FROM dt_prueba a INNER JOIN `dt_persona` b ON a.`IDEVALUADOR`=b.`IDPERSONA`
			INNER JOIN `dt_evaluacion` c ON a.`IDEVALUACION`= c.`IDEVALUACION`
			WHERE a.IDEVALUADO = '$idevaluado' AND a.IDEVALUACION='$idevaluacion'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;

	}
}

?>