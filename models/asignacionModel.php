<?php 
Class asignacionModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getsolicitudes(){
		$idpersona=$_SESSION['idpersona'];		
		$sql="SELECT * FROM ( 
			SELECT DISTINCT a.IDEVALUACION, b.IDPERSONA AS IDEVALUADO, CONCAT_WS(' ',b.NOMBRE,b.AP_PATERNO,b.AP_MATERNO) AS NOMBRE, 
			DATE_FORMAT(a.`FECHACREACION`,'%d/%m/%Y') AS fecha, a.IDUSUARIOCREACION, IF(c.IDEVALUACION IS NULL,0,1) AS ESTADO_EVALUACION
			FROM `dt_asignacion` a INNER JOIN `dt_persona` b ON a.IDPERSONA=b.IDPERSONA
			LEFT JOIN dt_prueba c ON a.IDEVALUACION=c.IDEVALUACION AND b.IDPERSONA=c.IDEVALUADO AND a.IDPERSONA=c.IDEVALUADOR
			WHERE a.IDPERSONA='$idpersona' 
			UNION ALL	
			SELECT DISTINCT a.IDEVALUACION, b.IDPERSONA AS IDEVALUADO, CONCAT_WS(' ',b.NOMBRE,b.AP_PATERNO,b.AP_MATERNO) AS NOMBRE, 
			DATE_FORMAT(a.`FECHACREACION`,'%d/%m/%Y') AS fecha, a.IDUSUARIOCREACION, IF(c.IDEVALUACION IS NULL,0,1) AS ESTADO_EVALUACION
			FROM `dt_asignacion` a INNER JOIN `dt_persona` b ON a.IDPERSONA=b.IDPERSONA
			LEFT JOIN dt_prueba c ON a.IDEVALUACION=c.IDEVALUACION AND b.IDPERSONA=c.IDEVALUADO AND a.IDGERENTE=c.IDEVALUADOR
			WHERE a.IDGERENTE='$idpersona'
			UNION ALL 
			SELECT DISTINCT a.IDEVALUACION, b.IDPERSONA AS IDEVALUADO, CONCAT_WS(' ',b.NOMBRE,b.AP_PATERNO,b.AP_MATERNO) AS NOMBRE, 
			DATE_FORMAT(a.`FECHACREACION`,'%d/%m/%Y') AS fecha, a.IDUSUARIOCREACION, IF(c.IDEVALUACION IS NULL,0,1) AS ESTADO_EVALUACION
			FROM `dt_asignacion` a INNER JOIN `dt_persona` b ON a.IDPERSONA=b.IDPERSONA
			LEFT JOIN dt_prueba c ON a.IDEVALUACION=c.IDEVALUACION AND b.IDPERSONA=c.IDEVALUADO AND a.IDCOLEGA=c.IDEVALUADOR
			WHERE a.IDCOLEGA='$idpersona' 
			UNION ALL 
			SELECT DISTINCT a.IDEVALUACION, b.IDPERSONA AS IDEVALUADO, CONCAT_WS(' ',b.NOMBRE,b.AP_PATERNO,b.AP_MATERNO) AS NOMBRE, 
			DATE_FORMAT(a.`FECHACREACION`,'%d/%m/%Y') AS fecha, a.IDUSUARIOCREACION, IF(c.IDEVALUACION IS NULL,0,1) AS ESTADO_EVALUACION
			FROM `dt_asignacion` a INNER JOIN `dt_persona` b ON a.IDPERSONA=b.IDPERSONA
			LEFT JOIN dt_prueba c ON a.IDEVALUACION=c.IDEVALUACION AND b.IDPERSONA=c.IDEVALUADO AND a.IDCLIENTE=c.IDEVALUADOR
			WHERE a.IDCLIENTE='$idpersona' 
			UNION ALL 
			SELECT DISTINCT a.IDEVALUACION, b.IDPERSONA AS IDEVALUADO, CONCAT_WS(' ',b.NOMBRE,b.AP_PATERNO,b.AP_MATERNO) AS NOMBRE, 
			DATE_FORMAT(a.`FECHACREACION`,'%d/%m/%Y') AS fecha, a.IDUSUARIOCREACION, IF(c.IDEVALUACION IS NULL,0,1) AS ESTADO_EVALUACION
			FROM `dt_asignacion` a INNER JOIN `dt_persona` b ON a.IDPERSONA=b.IDPERSONA
			LEFT JOIN dt_prueba c ON a.IDEVALUACION=c.IDEVALUACION AND b.IDPERSONA=c.IDEVALUADO AND a.IDPROVEEDOR=c.IDEVALUADOR
			WHERE a.IDPROVEEDOR='$idpersona' 
			) AS a 
			WHERE a.ESTADO_EVALUACION =0";
		//echo $sql; exit();	
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getevaluciones(){
		$sql="SELECT IDEVALUACION, NOMBRE FROM `dt_evaluacion` WHERE ESTADO=1";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getareas(){
		$sql="SELECT * FROM `dt_area` WHERE FLAG=1 AND IDAREA <> 12";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getpersonal($idarea, $idevaluacion){
		$sql="SELECT a.IDPERSONA, CONCAT_WS(' ',a.NOMBRE,a.AP_PATERNO,a.AP_MATERNO) AS NOMBRE, b.NOMBRE AS NOM_AREA, c.NOMBRE AS NOM_CARGO, 
			(SELECT CONCAT_WS(' ',NOMBRE,AP_PATERNO,AP_MATERNO) AS A FROM `dt_persona` WHERE ESTADO=1 AND IDAREA=a.IDAREA AND IDCARGO=1) AS GERENTE,
			(SELECT IDPERSONA FROM `dt_persona` WHERE ESTADO=1 AND IDAREA=a.IDAREA AND IDCARGO=1) AS IDGERENTE,
			(SELECT CONCAT(GROUP_CONCAT('',NOMBRE),',',GROUP_CONCAT('',IDPERSONA)) FROM `dt_persona` WHERE IDPERSONA <> a.IDPERSONA AND ESTADO=1 AND IDAREA=a.IDAREA AND IDCARGO<>1) AS ARR_PERSONA,
			(SELECT IDCOLEGA FROM dt_asignacion WHERE IDEVALUACION='$idevaluacion' AND IDPERSONA=a.IDPERSONA) AS SELECTED_COLEGA,
			(SELECT  CONCAT(GROUP_CONCAT('',dc.NOMBRE),',',GROUP_CONCAT('',dc.IDPERSONA)) FROM `dt_cliente_area` dta INNER JOIN `dt_cliente` dc ON dta.IDCLIENTE=dc.IDCLIENTE WHERE dta.IDAREA=a.IDAREA) AS ARR_CLIENTE,
			(SELECT IDCLIENTE FROM dt_asignacion WHERE IDEVALUACION='$idevaluacion' AND IDPERSONA=a.IDPERSONA) AS SELECTED_CLIENTE,
			(SELECT  CONCAT(GROUP_CONCAT('',dc.NOMBRE),',',GROUP_CONCAT('',dc.IDPERSONA)) FROM `dt_proveedor_area` dta INNER JOIN `dt_proveedor` dc ON dta.IDPROVEEDOR=dc.IDPROVEEDOR WHERE dta.IDAREA=a.IDAREA) AS ARR_PROVEEDOR,
			(SELECT IDPROVEEDOR FROM dt_asignacion WHERE IDEVALUACION='$idevaluacion' AND IDPERSONA=a.IDPERSONA) AS SELECTED_PROVEEDOR
			FROM `dt_persona` a INNER JOIN `dt_area` b ON a.IDAREA=b.IDAREA
			INNER JOIN `dt_cargo` c ON a.IDCARGO = c.IDCARGO
			WHERE a.`ESTADO`=1 AND a.IDAREA=$idarea AND a.IDCARGO<>1";
		//echo $sql; exit();
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function addcolega($idcolega, $idpersona, $idgerente, $idevaluacion){
		$user=$_SESSION['user'];

		$sql_validacion="SELECT * FROM dt_asignacion WHERE IDEVALUACION='$idevaluacion' AND IDPERSONA='$idpersona' AND IDGERENTE='$idgerente'";		
		$result = $this->_db->query($sql_validacion)or die ('Error en '.$sql_validacion);
		$reg = $result->fetch_object();
		$idasignacion=$reg->IDASIGNACION;

		if($result->num_rows){
			$sql="UPDATE dt_asignacion SET IDCOLEGA='$idcolega', IDUSUARIOMOD='$user' WHERE IDASIGNACION='$idasignacion'";
			$this->_db->query($sql)or die ('Error en '.$sql);
		}else{
			$sql="INSERT INTO dt_asignacion SET IDEVALUACION='$idevaluacion', IDPERSONA='$idpersona', IDGERENTE='$idgerente', IDCOLEGA='$idcolega', IDUSUARIOCREACION='$user'";
			$this->_db->query($sql)or die ('Error en '.$sql);
		}
		
		if($this->_db->errno)
			return false;
		return true;
	}

	public function addcliente($idcliente, $idpersona, $idgerente, $idevaluacion){
		$user=$_SESSION['user'];

		$sql_area="SELECT * FROM `dt_persona` WHERE IDPERSONA='$idpersona'";
		$result_area = $this->_db->query($sql_area)or die ('Error en '.$sql_area);
		$reg_area = $result_area->fetch_object();
			$idarea=$reg_area->IDAREA;

		$sql_validacion="SELECT * FROM dt_asignacion WHERE IDEVALUACION='$idevaluacion' AND IDPERSONA='$idpersona' AND IDGERENTE='$idgerente'";		
		$result = $this->_db->query($sql_validacion)or die ('Error en '.$sql_validacion);
		$reg = $result->fetch_object();
		$idasignacion=$reg->IDASIGNACION;

		if($result->num_rows){
			$sql="UPDATE dt_asignacion SET IDCLIENTE='$idcliente', IDAREA='$idarea', IDUSUARIOMOD='$user' WHERE IDASIGNACION='$idasignacion'";
			$this->_db->query($sql)or die ('Error en '.$sql);
		}else{
			$sql="INSERT INTO dt_asignacion SET IDEVALUACION='$idevaluacion', IDPERSONA='$idpersona', IDGERENTE='$idgerente', IDCLIENTE='$idcliente', IDAREA='$idarea', IDUSUARIOCREACION='$user'";
			$this->_db->query($sql)or die ('Error en '.$sql);
		}
		
		if($this->_db->errno)
			return false;
		return true;

	}

	public function addproveedor($idproveedor, $idpersona, $idgerente, $idevaluacion){

		$user=$_SESSION['user'];

		$sql_area="SELECT * FROM `dt_persona` WHERE IDPERSONA='$idpersona'";
		$result_area = $this->_db->query($sql_area)or die ('Error en '.$sql_area);
		$reg_area = $result_area->fetch_object();
			$idarea=$reg_area->IDAREA;


		$sql_validacion="SELECT * FROM dt_asignacion WHERE IDEVALUACION='$idevaluacion' AND IDPERSONA='$idpersona' AND IDGERENTE='$idgerente'";		
		$result = $this->_db->query($sql_validacion)or die ('Error en '.$sql_validacion);
		$reg = $result->fetch_object();
		$idasignacion=$reg->IDASIGNACION;

		if($result->num_rows){
			$sql="UPDATE dt_asignacion SET IDPROVEEDOR='$idproveedor', IDUSUARIOMOD='$user', IDAREA='$idarea' WHERE IDASIGNACION='$idasignacion'";
			$this->_db->query($sql)or die ('Error en '.$sql);
		}else{
			$sql="INSERT INTO dt_asignacion SET IDEVALUACION='$idevaluacion', IDPERSONA='$idpersona', IDGERENTE='$idgerente', IDPROVEEDOR='$idproveedor', IDAREA='$idarea', IDUSUARIOCREACION='$user'";
			$this->_db->query($sql)or die ('Error en '.$sql);
		}
		
		if($this->_db->errno)
			return false;
		return true;

	}


}

?>