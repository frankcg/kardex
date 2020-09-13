<?php 
Class competenciaModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getcompetencias(){
		$sql="SELECT IDCOMPETENCIA, NOMBRE, IF(ESTADO=1,'ACTIVO','INACTIVO') AS ESTADO, DATE_FORMAT(`FECHACREACION`,'%d/%m/%Y') AS FECHA FROM `dt_competencia` ORDER BY 1 DESC";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function addcompetencia($nombre, $observacion){
		$user=$_SESSION['user'];
		$sql="INSERT INTO `dt_competencia` SET NOMBRE='$nombre', observacion='$observacion' ,idusuariocreacion='$user'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		return $this->_db->insert_id;	
	}

	public function addconducta($idcompetencia, $nombre_conducta, $idprioridad){
		$user=$_SESSION['user'];
		$sql="INSERT INTO `dt_competencia_conducta` SET IDCOMPETENCIA=$idcompetencia, NOMBRE='$nombre_conducta', PRIORIDAD=$idprioridad ,idusuariocreacion='$user'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function getcompetencia($idcompetencia){
		$sql="SELECT a.IDCOMPETENCIA, a.NOMBRE, a.OBSERVACION,
			(SELECT z.NOMBRE FROM `dt_competencia_conducta` z WHERE z.IDCOMPETENCIA=a.IDCOMPETENCIA AND z.PRIORIDAD=1) AS CONDUCTA1,
			(SELECT NOMBRE FROM `dt_competencia_conducta` WHERE IDCOMPETENCIA=a.IDCOMPETENCIA AND PRIORIDAD=2) AS CONDUCTA2,
			(SELECT NOMBRE FROM `dt_competencia_conducta` WHERE IDCOMPETENCIA=a.IDCOMPETENCIA AND PRIORIDAD=3) AS CONDUCTA3,
			(SELECT NOMBRE FROM `dt_competencia_conducta` WHERE IDCOMPETENCIA=a.IDCOMPETENCIA AND PRIORIDAD=4) AS CONDUCTA4
			FROM `dt_competencia` a
			WHERE a.IDCOMPETENCIA=$idcompetencia";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function updatecompetencia($idcompetencia, $nombre, $observacion){
		$user=$_SESSION['user'];
		$sql="UPDATE `dt_competencia` SET NOMBRE='$nombre', OBSERVACION='$observacion', IDUSUARIOMOD='$user' WHERE IDCOMPETENCIA=$idcompetencia";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function deleteconducta($idcompetencia){
		$sql="DELETE FROM `dt_competencia_conducta` WHERE IDCOMPETENCIA=$idcompetencia";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function delcompetencia($idcompetencia, $estado){
		$sql="UPDATE `dt_competencia` SET ESTADO=$estado WHERE IDCOMPETENCIA=$idcompetencia";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

}

?>