<?php 
Class controlsModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}	

	public function getTipopago(){
		$sql="SELECT IDTIPOPAGO, DESCRIPCION FROM sel_tipopago";	
		$response=$this->_db->query($sql)or die ('Error en '.$sql);
		return $response;
	}



}

?>