<?php

Class evaluacionModel extends Model
{
    public function __construct(){
        parent::__construct();
    }

    public function getcompetencia($idevaluacion){

    	if(!$idevaluacion){
        	$sql="SELECT IDCOMPETENCIA, NOMBRE, '' AS SELECTED FROM `dt_competencia` WHERE ESTADO=1";
    	}else{
    		$sql="SELECT b.IDCOMPETENCIA, b.NOMBRE, a.SELECTED FROM(			
			SELECT IDCOMPETENCIA,'selected' SELECTED
			FROM `dt_evaluacion_detalle` 
			WHERE IDEVALUACION = '$idevaluacion') AS a RIGHT JOIN `dt_competencia` b  ON a.IDCOMPETENCIA = b.IDCOMPETENCIA WHERE b.ESTADO=1";
    	}
        $result = $this->_db->query($sql) or die ('Error en '.$sql);
        return $result;
    }

    public function getevaluaciones(){
    	$sql="SELECT a.IDEVALUACION, a.NOMBRE, DATE_FORMAT(a.`FECHA_APERTURA`,'%d/%m/%Y') AS FECHA_APERTURA, DATE_FORMAT(a.`FECHA_CIERRE`,'%d/%m/%Y') AS FECHA_CIERRE, IF(ESTADO=1,'ACTIVO',IF(ESTADO=0,'INACTIVO', 'CONCLUIDO')) AS ESTADO, DATE_FORMAT(a.`FECHACREACION`,'%d/%m/%Y') AS FECHA, 
			(SELECT COUNT(*) FROM `dt_evaluacion_detalle` WHERE IDEVALUACION=a.IDEVALUACION) AS CANT
			FROM `dt_evaluacion` a";
        $result = $this->_db->query($sql) or die ('Error en '.$sql);
        return $result;
    }

    public function addevaluacion($nombre, $fecha_apertura, $fecha_cierre, $observacion){
    	$user=$_SESSION['user'];
		$sql="INSERT INTO `dt_evaluacion` SET NOMBRE='$nombre', FECHA_APERTURA='$fecha_apertura', FECHA_CIERRE='$fecha_cierre', OBSERVACION='$observacion', IDUSUARIOCREACION='$user'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		return $this->_db->insert_id;
    }

    public function addevaluacion_detalle($idevaluacion, $idcompetencia){
    	$user=$_SESSION['user'];
		$sql="INSERT INTO `dt_evaluacion_detalle` SET IDEVALUACION='$idevaluacion', IDCOMPETENCIA='$idcompetencia', IDUSUARIOCREACION='$user'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
    }

    public function getevaluacion($idevaluacion){
    	$sql="SELECT * FROM `dt_evaluacion` WHERE IDEVALUACION='$idevaluacion'";
        $result = $this->_db->query($sql) or die ('Error en '.$sql);
        return $result;
    }

    public function updateevaluacion($idevaluacion, $nombre, $fecha_apertura, $fecha_cierre, $observacion){
    	$user=$_SESSION['user'];
		$sql="UPDATE `dt_evaluacion` SET NOMBRE='$nombre', FECHA_APERTURA='$fecha_apertura', FECHA_CIERRE='$fecha_cierre', OBSERVACION='$observacion', IDUSUARIOMOD='$user' WHERE IDEVALUACION='$idevaluacion'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
    }

    public function delete_detalle_evaluacion($idevaluacion){
    	$sql="DELETE FROM `dt_evaluacion_detalle` WHERE IDEVALUACION='$idevaluacion'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
    }

    public function delevaluacion($idevaluacion, $estado){
    	$user=$_SESSION['user'];
    	$sql="UPDATE `dt_evaluacion` SET ESTADO='$estado', IDUSUARIOMOD='$user' WHERE IDEVALUACION='$idevaluacion'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
    }

    public function concluirevaluacion($idevaluacion){
        $user=$_SESSION['user'];
        $sql="UPDATE `dt_evaluacion` SET ESTADO='2', IDUSUARIOMOD='$user' WHERE IDEVALUACION='$idevaluacion'";
        $this->_db->query($sql)or die ('Error en '.$sql);
        if($this->_db->errno)
            return false;
        return true;

    }

}

?>