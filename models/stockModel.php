<?php 
Class stockModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function getStock($codLocal){
		$sql="SELECT * FROM vw_sel_productos_stock WHERE nLOCAL = $codLocal";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}
}
?>