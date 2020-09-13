<?php 
Class busquedaModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getevaluadores($idevaluacion){
		$sql="SELECT DISTINCT a.IDEVALUADOR, 
		(SELECT CONCAT(NOMBRE,', ',AP_PATERNO,' ',AP_MATERNO) FROM dt_persona WHERE IDPERSONA=a.IDEVALUADOR) AS NOMBRE
		FROM `dt_prueba` a WHERE a.IDEVALUACION='$idevaluacion'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getevaluados($idevaluacion, $idevaluador){

		$sql="SELECT DISTINCT a.IDEVALUADO,
			(SELECT CONCAT(NOMBRE,', ',AP_PATERNO,' ',AP_MATERNO) FROM dt_persona WHERE IDPERSONA=a.IDEVALUADO) AS NOMBRE
			FROM `dt_prueba` a WHERE a.IDEVALUACION='$idevaluacion' AND IDEVALUADOR='$idevaluador'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getevaluaciones($idevaluacion, $idevaluador, $idevaluado){
		$sql="SELECT b.NOMBRE AS NOMBRE_EVALUACION,
			(SELECT CONCAT(NOMBRE,', ',AP_PATERNO,' ',AP_MATERNO) FROM dt_persona WHERE IDPERSONA=a.IDEVALUADO) AS IDEVALUADO,
			(SELECT CONCAT(NOMBRE,', ',AP_PATERNO,' ',AP_MATERNO) FROM dt_persona WHERE IDPERSONA=a.IDEVALUADOR) AS IDEVALUADOR,
			c.NOMBRE AS NOMBRE_COMPETENCIA, d.NOMBRE AS NOMBRE_CONDUCTA
			FROM `dt_prueba` a INNER JOIN `dt_evaluacion` b ON a.IDEVALUACION=b.IDEVALUACION
			INNER JOIN dt_competencia c ON a.IDCOMPETENCIA=c.IDCOMPETENCIA 
			INNER JOIN dt_competencia_conducta d ON a.IDCOMPETENCIA=d.IDCOMPETENCIA AND a.IDCONDUCTA=d.IDCONDUCTA
			WHERE a.IDEVALUACION='$idevaluacion' AND a.IDEVALUADOR='$idevaluador' AND a.IDEVALUADO='$idevaluado'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getareas($idevaluacion){
		$sql="SELECT DISTINCT c.IDAREA, c.NOMBRE
			FROM dt_prueba a INNER JOIN `dt_persona` b ON a.IDEVALUADO=b.IDPERSONA
			INNER JOIN `dt_area` c ON b.IDAREA=c.IDAREA
			WHERE IDEVALUACION=$idevaluacion";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getevaluacionesxarea($idevaluacion, $idarea){

		$sql="SELECT a.IDEVALUACION, a.IDEVALUADO, b.NOMBRE AS NOMBRE_EVALUACION,
			(SELECT CONCAT(NOMBRE,', ',AP_PATERNO,' ',AP_MATERNO) FROM dt_persona WHERE IDPERSONA=a.IDEVALUADO) AS EVALUADO,
			ROUND(SUM(CALIFICACION)/COUNT(*),2) AS PROMEDIO
			FROM `dt_prueba` a INNER JOIN `dt_evaluacion` b ON a.IDEVALUACION=b.IDEVALUACION
			WHERE a.IDEVALUACION=$idevaluacion
			AND a.IDEVALUADO IN (SELECT DISTINCT IDPERSONA FROM dt_persona WHERE IDAREA=$idarea)
			GROUP BY a.IDEVALUACION, a.IDEVALUADO
			ORDER BY PROMEDIO DESC";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;

	}
}

?>