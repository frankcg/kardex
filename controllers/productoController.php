<?php 

class productoController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){		
		$this->_view->setJs(array('index'));
		//$objModel=$this->loadModel('asignacion');
		//$this->_view->evaluacion=$objModel->getevaluciones();
		//$this->_view->evaluacion2=$objModel->getevaluciones();
		$this->_view->renderizar('index');
	}

	public function getevaluadores(){
		$idevaluacion = $_POST['idevaluacion'];
		$objModel=$this->loadModel('busqueda');
		$result=$objModel->getevaluadores($idevaluacion);
		$html='<option selected="selected" disabled="disabled">--SELECCIONE--</option>';

		while ($reg = $result->fetch_object()){
			$html.='<option value="'.$reg->IDEVALUADOR.'" > '.$reg->NOMBRE.' </option>';
		}
		echo $html;
	}	

}

?>