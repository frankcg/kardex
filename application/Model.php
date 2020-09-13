<?php

/*
 * -------------------------------------

 * Model.php
 * -------------------------------------
 */


class Model
{
	protected $_db;

	public function __construct() {
		$this->_db = new Database();
	}

	public function getPaginacion()
	{
		if(isset($this->_db->_paginacion)){
			return $this->_db->_paginacion;
		} else {
			return false;
		}
	}


}

?>
