<?php 
Class pruebaModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getpendientes($idevaluacion){
		$idpersona=$_SESSION['idpersona'];		
		$sql="
			SELECT * FROM (
				SELECT a.IDASIGNACION, a.IDEVALUACION, a.IDPERSONA AS IDEVALUADO, a.`IDPERSONA` AS EVALUADOR, 
				CONCAT_WS(',',a.IDASIGNACION, a.IDEVALUACION, a.IDPERSONA, a.`IDPERSONA`) AS CODIGOS,
				CONCAT_WS(' ',b.NOMBRE,b.AP_PATERNO,b.AP_MATERNO) AS NOMBRE, DATE_FORMAT(a.`FECHACREACION`,'%d/%m/%Y') AS fecha, a.IDUSUARIOCREACION, c.NOMBRE AS CARGO
				FROM `dt_asignacion` a INNER JOIN `dt_persona` b ON a.IDPERSONA=b.IDPERSONA
				INNER JOIN `dt_cargo` c ON b.IDCARGO=c.IDCARGO
				WHERE a.IDPERSONA='$idpersona' AND a.IDEVALUACION='$idevaluacion'
				UNION ALL
				SELECT a.IDASIGNACION, a.IDEVALUACION, a.IDPERSONA AS IDEVALUADO, a.`IDGERENTE` AS EVALUADOR, 
				CONCAT_WS(',',a.IDASIGNACION, a.IDEVALUACION, a.IDPERSONA, a.`IDGERENTE`) AS CODIGOS,
				CONCAT_WS(' ',b.NOMBRE,b.AP_PATERNO,b.AP_MATERNO) AS NOMBRE, DATE_FORMAT(a.`FECHACREACION`,'%d/%m/%Y') AS fecha, a.IDUSUARIOCREACION, c.NOMBRE AS CARGO
				FROM `dt_asignacion` a INNER JOIN `dt_persona` b ON a.IDPERSONA=b.IDPERSONA
				INNER JOIN `dt_cargo` c ON b.IDCARGO=c.IDCARGO
				WHERE a.IDGERENTE='$idpersona' AND a.IDEVALUACION='$idevaluacion'
				UNION ALL
				SELECT  a.IDASIGNACION, a.IDEVALUACION, a.IDPERSONA AS IDEVALUADO, a.`IDCOLEGA` AS EVALUADOR, 
				CONCAT_WS(',',a.IDASIGNACION, a.IDEVALUACION, a.IDPERSONA, a.`IDCOLEGA`) AS CODIGOS,
				CONCAT_WS(' ',b.NOMBRE,b.AP_PATERNO,b.AP_MATERNO) AS NOMBRE, DATE_FORMAT(a.`FECHACREACION`,'%d/%m/%Y') AS fecha, a.IDUSUARIOCREACION, c.NOMBRE AS CARGO
				FROM `dt_asignacion` a INNER JOIN `dt_persona` b ON a.IDPERSONA=b.IDPERSONA
				INNER JOIN `dt_cargo` c ON b.IDCARGO=c.IDCARGO
				WHERE a.IDCOLEGA='$idpersona' AND a.IDEVALUACION='$idevaluacion'
				UNION ALL
				SELECT  a.IDASIGNACION, a.IDEVALUACION, a.IDPERSONA AS IDEVALUADO, a.`IDCLIENTE` AS EVALUADOR, 
				CONCAT_WS(',',a.IDASIGNACION, a.IDEVALUACION, a.IDPERSONA, a.`IDCLIENTE`) AS CODIGOS,
				CONCAT_WS(' ',b.NOMBRE,b.AP_PATERNO,b.AP_MATERNO) AS NOMBRE, DATE_FORMAT(a.`FECHACREACION`,'%d/%m/%Y') AS fecha, a.IDUSUARIOCREACION, c.NOMBRE AS CARGO
				FROM `dt_asignacion` a INNER JOIN `dt_persona` b ON a.IDPERSONA=b.IDPERSONA
				INNER JOIN `dt_cargo` c ON b.IDCARGO=c.IDCARGO
				WHERE a.IDCLIENTE='$idpersona' AND a.IDEVALUACION='$idevaluacion'
				UNION ALL
				SELECT  a.IDASIGNACION, a.IDEVALUACION, a.IDPERSONA AS IDEVALUADO, a.`IDPROVEEDOR` AS EVALUADOR, 
				CONCAT_WS(',',a.IDASIGNACION, a.IDEVALUACION, a.IDPERSONA, a.`IDPROVEEDOR`) AS CODIGOS,
				CONCAT_WS(' ',b.NOMBRE,b.AP_PATERNO,b.AP_MATERNO) AS NOMBRE, DATE_FORMAT(a.`FECHACREACION`,'%d/%m/%Y') AS fecha, a.IDUSUARIOCREACION, c.NOMBRE AS CARGO
				FROM `dt_asignacion` a INNER JOIN `dt_persona` b ON a.IDPERSONA=b.IDPERSONA
				INNER JOIN `dt_cargo` c ON b.IDCARGO=c.IDCARGO
				WHERE a.IDPROVEEDOR='$idpersona' AND a.IDEVALUACION='$idevaluacion'
			) AS A
			WHERE IDEVALUADO NOT IN (SELECT DISTINCT IDEVALUADO FROM `dt_prueba` WHERE IDEVALUACION='$idevaluacion' AND IDEVALUADOR='$idpersona' )
			";
		//echo $sql; exit();
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getevaluacion_cabecera($idevaluacion){
		$sql="SELECT * FROM(
			SELECT a.IDCOMPETENCIA, b.NOMBRE 
			FROM `dt_evaluacion_detalle` a INNER JOIN `dt_competencia` b ON a.IDCOMPETENCIA=b.IDCOMPETENCIA
			WHERE a.IDEVALUACION='$idevaluacion'
			UNION ALL
			SELECT 'FINALIZAR','FINALIZAR'
			) AS a ORDER BY a.IDCOMPETENCIA ";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getevaluacion_body($idevaluacion){
		$sql="SELECT  CONCAT('FILA_',FILA) AS 'FILA', CONCAT('FILA_',FILA+1) AS 'NEXT', CONCAT('FILA_',IF(FILA=1,FILA, FILA-1)) AS 'PREV', IDCOMPETENCIA, COMPETENCIA, CONDUCTA, IDCONDUCTA
			FROM (
			SELECT @rownum:=@rownum+1 AS FILA, IDCOMPETENCIA, COMPETENCIA, CONDUCTA, IDCONDUCTA
			FROM(
			SELECT a.IDCOMPETENCIA, b.NOMBRE AS COMPETENCIA, GROUP_CONCAT('',c.NOMBRE) AS CONDUCTA, GROUP_CONCAT('',c.IDCONDUCTA) AS IDCONDUCTA
			FROM `dt_evaluacion_detalle` a INNER JOIN `dt_competencia` b ON a.IDCOMPETENCIA=b.IDCOMPETENCIA
			INNER JOIN `dt_competencia_conducta` c ON b.IDCOMPETENCIA=c.IDCOMPETENCIA
			WHERE a.IDEVALUACION='$idevaluacion'
			GROUP BY a.IDCOMPETENCIA
			UNION ALL
			SELECT 'FINALIZAR','FINALIZAR','FINALIZAR' ,'FINALIZAR'
			) AS a, (SELECT @rownum:=0) r
			) AS a ";
		//echo $sql; exit();
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function addprueba($idasignacion, $idevaluacion, $idevaluado, $idevaluador, $idcompetencia, $idconducta){
		$user=$_SESSION['user'];
		$sql="INSERT INTO `dt_prueba` SET IDASIGNACION='$idasignacion', IDEVALUACION='$idevaluacion', IDEVALUADO='$idevaluado', IDEVALUADOR='$idevaluador', IDCOMPETENCIA='$idcompetencia', IDCONDUCTA='$idconducta',IDUSUARIOCREACION='$user'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function calcularpeso($idasignacion, $idevaluacion, $idevaluado, $idevaluador){
		
		$sql="SELECT round(sum(peso)/count(*),2) as calificacion
			from `dt_prueba` a inner join dt_competencia_conducta b on a.`IDCONDUCTA`=b.`IDCONDUCTA`
			where a.IDASIGNACION=$idasignacion and a.IDEVALUACION=$idevaluacion and a.IDEVALUADO=$idevaluado AND a.IDEVALUADOR=$idevaluador";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);

		$reg = $result->fetch_object();
		$calificacion = $reg->calificacion;

		$sql_update="UPDATE dt_prueba SET CALIFICACION=$calificacion WHERE IDASIGNACION=$idasignacion AND IDEVALUACION=$idevaluacion AND IDEVALUADO=$idevaluado and IDEVALUADOR=$idevaluador";
		$this->_db->query($sql_update)or die ('Error en '.$sql_update);

		if($this->_db->errno)
			return false;
		return true;
	}

}

?>