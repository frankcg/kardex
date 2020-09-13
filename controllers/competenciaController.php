<?php 

class competenciaController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){		
		$this->_view->setJs(array('index'));
		$this->_view->renderizar('index');
	}

	public function getcompetencias(){

		$objModel=$this->loadModel('competencia');
		$result=$objModel->getcompetencias();
		$cont=0;

		while($reg=$result->fetch_object()){
			$cont++;

			if($reg->ESTADO == 'INACTIVO'){
				$btn='btn-success';
				$icon='fa-check';
				$title='Habilitar';
				$class='activarcompetencia';
			}else{
				$btn='btn-danger';
				$icon='fa-close';
				$title='Inhabilitar';
				$class='desactivarcompetencia';
			}			

			$boton='<button id="'.$reg->IDCOMPETENCIA.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';

			$data ['data'] [] = array (
				'IDCOMPETENCIA'=>$reg->IDCOMPETENCIA,
				'NOMBRE'=>$reg->NOMBRE,
				'ESTADO'=>$reg->ESTADO,
				'FECHA'=>$reg->FECHA,
				'OPCIONES'=>$boton
				);
		}
		echo json_encode ( $data );

	}

	public function addcompetencia(){
		$nombre = $_POST['nombre'];
		$conducta = $_POST['conducta'];
		$observacion = $_POST['observacion'];		

		$objModel=$this->loadModel('competencia');
		$idcompetencia = $objModel->addcompetencia($nombre, $observacion);
		$idprioridad=0;

		foreach($conducta as $id => $valor){
			$idprioridad++;
			$nombre_conducta=$conducta[$id];
			$result=$objModel->addconducta($idcompetencia, $nombre_conducta, $idprioridad);
		}
		if($result) echo 1; else echo 0;		
	}

	public function getcompetencia(){
		$idcompetencia = $_POST['idcompetencia'];
		$objModel=$this->loadModel('competencia');
		$result = $objModel->getcompetencia($idcompetencia);
		$reg = $result->fetch_object();
		echo json_encode($reg);
	}

	public function updatecompetencia(){
		$idcompetencia = $_POST['idcompetencia'];
		$nombre = $_POST['nombre'];
		$conducta = $_POST['conducta'];
		$observacion = $_POST['observacion'];		

		$objModel=$this->loadModel('competencia');
		$objModel->updatecompetencia($idcompetencia, $nombre, $observacion);
		$objModel->deleteconducta($idcompetencia);
		$idprioridad=0;

		foreach($conducta as $id => $valor){
			$idprioridad++;
			$nombre_conducta=$conducta[$id];
			$result=$objModel->addconducta($idcompetencia, $nombre_conducta, $idprioridad);
		}
		if($result) echo 1; else echo 0;		
	}

	public function delcompetencia(){
		$idcompetencia = $_POST['idcompetencia'];
		$estado = $_POST['estado'];
		$objModel=$this->loadModel('competencia');
		$result=$objModel->delcompetencia($idcompetencia, $estado);
		if($result) echo 'ok'; else echo 'error';
	}
			
}

?>