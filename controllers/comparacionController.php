<?php 

class comparacionController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){
		$this->_view->setJs(array('index'));
		$objModel=$this->loadModel('asignacion');
		$this->_view->evaluacion1=$objModel->getevaluciones();
		$this->_view->evaluacion2=$objModel->getevaluciones();
		$this->_view->renderizar('index');
	}

	public function getpersonal(){

		$idevaluacion1 = $_POST['idevaluacion1'];
		$idevaluacion2 = $_POST['idevaluacion2'];
		$objModel=$this->loadModel('comparacion');
		$result =$objModel->getpersonal($idevaluacion1, $idevaluacion2);
		$html='<option selected="selected" disabled="disabled">--SELECCIONE--</option>';
		while ($reg = $result->fetch_object()){
			$html.='<option value="'.$reg->IDEVALUADO.'" > '.$reg->NOMBRE.' </option>';
		}
		echo $html;
	}

	public function getidevaluacion($idevaluacion, $idevaluado){

		$objModel=$this->loadModel('comparacion');
		$result =$objModel->getidevaluacion($idevaluacion, $idevaluado);
		$html='';
		$promedio=0;
		$contador=0;
		while($reg = $result->fetch_object()) {
			$promedio=$promedio+$reg->CALIFICACION;
			$contador++;
			$html.='
					<tr>
		              <th>'.$reg->IDEVALUACION.'</th>
		              <th>'.$reg->EVALUADOR.'</th>
		              <th>'.$reg->CALIFICACION.' %</th>		              
		            </tr>';
		}

		$html.='<tr>
			        <th colspan="3">Promedio de Evaluacion: '.$promedio/$contador.' %</th>		              
		        </tr>';

		echo $html;
	}
}

?>