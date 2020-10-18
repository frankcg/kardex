<?php 

class controlsController extends Controller{
 
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
 
	}

	public function index(){		
		$this->_view->renderizar('index');
		
	}
 
	public function getTipopago(){
		$objModel=$this->loadModel('controls');
		$result = $objModel->getTipopago();
		echo '<option selected disabled> SELECCIONE  </option>';
		while ($reg = $result->fetch_object()){
			echo '<option value="'.$reg->IDTIPOPAGO.'" > '.$reg->DESCRIPCION. '  </option>';
		}
	}



}
?>