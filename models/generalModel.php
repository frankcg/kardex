<?php 
Class generalModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getNombreLocal($codLocal){		
		$sql="SELECT * FROM sel_local WHERE nIDLOCAL=$codLocal";		
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		$res = $result->fetch_object();
		return $res->sDESCRIPCION;
	}

}

?>