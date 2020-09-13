<?php 

class busquedaController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){		
		$this->_view->setJs(array('index'));
		$objModel=$this->loadModel('asignacion');
		$this->_view->evaluacion=$objModel->getevaluciones();
		$this->_view->evaluacion2=$objModel->getevaluciones();
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

	public function getevaluados(){
		$idevaluacion = $_POST['idevaluacion'];
		$idevaluador = $_POST['idevaluador'];
		$objModel=$this->loadModel('busqueda');
		$result=$objModel->getevaluados($idevaluacion, $idevaluador);
		$html='<option selected="selected" disabled="disabled">--SELECCIONE--</option>';

		while ($reg = $result->fetch_object()){
			$html.='<option value="'.$reg->IDEVALUADO.'" > '.$reg->NOMBRE.' </option>';
		}
		echo $html;
	}

	public function getevaluaciones($idevaluacion, $idevaluador, $idevaluado){

		$objModel=$this->loadModel('busqueda');
		$result=$objModel->getevaluaciones($idevaluacion, $idevaluador, $idevaluado);

		while($reg=$result->fetch_object()){			

			$data ['data'] [] = array (
				'NOMBRE_EVALUACION'=>$reg->NOMBRE_EVALUACION,
				'IDEVALUADO'=>$reg->IDEVALUADO,
				'IDEVALUADOR'=>$reg->IDEVALUADOR,
				'NOMBRE_COMPETENCIA'=>$reg->NOMBRE_COMPETENCIA,
				'NOMBRE_CONDUCTA'=>$reg->NOMBRE_CONDUCTA
				);
		}
		echo json_encode ( $data );

	}

	public function getareas(){
		$idevaluacion = $_POST['idevaluacion'];		
		$objModel=$this->loadModel('busqueda');
		$result=$objModel->getareas($idevaluacion);
		$html='<option selected="selected" disabled="disabled">--SELECCIONE--</option>';
		while ($reg = $result->fetch_object()){
			$html.='<option value="'.$reg->IDAREA.'" > '.$reg->NOMBRE.' </option>';
		}
		echo $html;
	}

	public function getevaluacionesxarea($idevaluacion, $idarea){

		$objModel=$this->loadModel('busqueda');
		$result=$objModel->getevaluacionesxarea($idevaluacion, $idarea);

		while($reg=$result->fetch_object()){			

			$data ['data'] [] = array (
				'NOMBRE_EVALUACION'=>$reg->NOMBRE_EVALUACION,
				'IDEVALUADO'=>$reg->EVALUADO,
				'PROMEDIO'=>$reg->PROMEDIO
				);
		}
		echo json_encode ( $data );

	}

}

?>