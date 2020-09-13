<?php 

class evaluacionController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){		
		$this->_view->setJs(array('index'));		
		$this->_view->renderizar('index');
	}

	public function listcompetencia(){
		$idevaluacion = $_POST['idevaluacion'];
		$objModel=$this->loadModel('evaluacion');
		$result=$objModel->getcompetencia($idevaluacion);
		while ($reg = $result->fetch_object()){
			echo '<option '.$reg->SELECTED.' value="'.$reg->IDCOMPETENCIA.'" > '.$reg->NOMBRE.' </option>';
		}

	}

	public function getevaluaciones(){

		$objModel=$this->loadModel('evaluacion');
		$result=$objModel->getevaluaciones();
		$cont=0;

		while($reg=$result->fetch_object()){
			$cont++;
			$boton='';		

			if($reg->ESTADO <> 'CONCLUIDO'){

				if($reg->ESTADO == 'INACTIVO'){
					$btn='btn-success';
					$icon='fa-check';
					$title='Habilitar';
					$class='activarevaluacion';
				}else{
					$btn='btn-danger';
					$icon='fa-close';
					$title='Inhabilitar';
					$class='desactivarevaluacion';
				}

				$boton='<button id="'.$reg->IDEVALUACION.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button> <button id="'.$reg->IDEVALUACION.'" class="concluir btn btn-warning btn-xs" title="Concluir"><span class="fa fa-thumbs-o-up"></span></button>';
			}

			$data ['data'] [] = array (
				'IDEVALUACION'=>$reg->IDEVALUACION,
				'NOMBRE'=>$reg->NOMBRE,
				'FECHA_APERTURA'=>$reg->FECHA_APERTURA,
				'FECHA_CIERRE'=>$reg->FECHA_CIERRE,
				'CANT'=>$reg->CANT,
				'ESTADO'=>$reg->ESTADO,
				'FECHA'=>$reg->FECHA,
				'OPCIONES'=>$boton
				);
		}
		echo json_encode ( $data );
	}

	public function addevaluacion(){

		$nombre = $_POST['nombre'];
		$fecha_apertura = $_POST['fecha_apertura'];
		$fecha_cierre = $_POST['fecha_cierre'];
		$competencia = $_POST['competencia'];
		$observacion = $_POST['observacion'];	


		$objModel=$this->loadModel('evaluacion');
		$idevaluacion = $objModel->addevaluacion($nombre, $fecha_apertura, $fecha_cierre, $observacion);		

		foreach($competencia as $id => $valor){			
			$idcompetencia=$competencia[$id];
			$result=$objModel->addevaluacion_detalle($idevaluacion, $idcompetencia);
		}
		if($result) echo 1; else echo 0;		
	}

	public function getevaluacion(){
		$idevaluacion = $_POST['idevaluacion'];
		$objModel=$this->loadModel('evaluacion');
		$result = $objModel->getevaluacion($idevaluacion);
		$reg = $result->fetch_object();
		echo json_encode($reg);
	}

	public function updateevaluacion(){

		$idevaluacion = $_POST['idevaluacion'];
		$nombre = $_POST['nombre'];
		$fecha_apertura = $_POST['fecha_apertura'];
		$fecha_cierre = $_POST['fecha_cierre'];
		$competencia = $_POST['competencia'];
		$observacion = $_POST['observacion'];


		$objModel=$this->loadModel('evaluacion');
		$objModel->updateevaluacion($idevaluacion, $nombre, $fecha_apertura, $fecha_cierre, $observacion);		
		$objModel->delete_detalle_evaluacion($idevaluacion);

		foreach($competencia as $id => $valor){			
			$idcompetencia=$competencia[$id];
			$result=$objModel->addevaluacion_detalle($idevaluacion, $idcompetencia);
		}
		if($result) echo 1; else echo 0;		
	}

	public function delevaluacion(){
		$idevaluacion = $_POST['idevaluacion'];
		$estado = $_POST['estado'];
		$objModel=$this->loadModel('evaluacion');
		$result=$objModel->delevaluacion($idevaluacion, $estado);
		if($result) echo 'ok'; else echo 'error';
	}

	public function concluirevaluacion(){
		$idevaluacion = $_POST['idevaluacion'];
		$objModel=$this->loadModel('evaluacion');
		$result=$objModel->concluirevaluacion($idevaluacion);
		if($result) echo 'ok'; else echo 'error';
	}


}

?>