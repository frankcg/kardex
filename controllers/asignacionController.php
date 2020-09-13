<?php 

class asignacionController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){		
		$this->_view->setJs(array('index'));
		$objModel=$this->loadModel('asignacion');
		$this->_view->evaluacion=$objModel->getevaluciones();
		$this->_view->area=$objModel->getareas();
		$this->_view->renderizar('index');
	}

	public function getsolicitudes(){
		$objModel=$this->loadModel('asignacion');
		$result=$objModel->getsolicitudes();
		$i=0;
		while($reg=$result->fetch_object()){
			$data['data'] []= array('NOMBRE'=>$reg->NOMBRE,'FECHA'=>$reg->fecha,'IDUSUARIOCREACION'=>$reg->IDUSUARIOCREACION);			
		}
		echo json_encode($data);
	}

	public function getpersonal($idarea, $idevaluacion){

		$objModel=$this->loadModel('asignacion');
		$result=$objModel->getpersonal($idarea, $idevaluacion);
		
		while($reg=$result->fetch_object()){

			//COMBO COLEGA
			$array_colega = array();
			$array_colega = explode(",", $reg->ARR_PERSONA);
			$html_colega='';
			$html_colega.='<select class="colega form-control"><option selected="selected" disabled="disabled">--SELECCIONE--</option>';

			for ($i=0; $i<count($array_colega)/2; $i++) {
				//echo $array[$i].'<br>';
				if($reg->SELECTED_COLEGA == $array_colega[$i+count($array_colega)/2]){ $selected_colega='selected'; }else{ $selected_colega='';	}				
				$html_colega.='<option '.$selected_colega.' value="'.$array_colega[$i+count($array_colega)/2].",".$reg->IDPERSONA.",".$reg->IDGERENTE.'">'.$array_colega[$i].'</option>';
			} 	

			$html_colega.='</select>';
			//END COMBO COLEGA
			
			//COMBO CLIENTE
			$array_cliente = array();
			$array_cliente = explode(",", $reg->ARR_CLIENTE);
			$html_cliente='';
			$html_cliente.='<select class="cliente form-control"><option selected="selected" disabled="disabled">--SELECCIONE--</option>';

			for ($i=0; $i<count($array_cliente)/2; $i++) {
				//echo $array[$i].'<br>';
				if($reg->SELECTED_CLIENTE == $array_cliente[$i+count($array_cliente)/2]){ $selected_cliente='selected'; }else{ $selected_cliente='';	}				
				$html_cliente.='<option '.$selected_cliente.' value="'.$array_cliente[$i+count($array_cliente)/2].",".$reg->IDPERSONA.",".$reg->IDGERENTE.'">'.$array_cliente[$i].'</option>';
			} 	

			$html_cliente.='</select>';
			//END COMBO CLIENTE
			
			//COMBO PROVEEDOR
			$array_proveedor = array();
			$array_proveedor = explode(",", $reg->ARR_PROVEEDOR);
			$html_proveedor='';
			$html_proveedor.='<select class="proveedor form-control"><option selected="selected" disabled="disabled">--SELECCIONE--</option>';

			for ($i=0; $i<count($array_proveedor)/2; $i++) {
				//echo $array[$i].'<br>';
				if($reg->SELECTED_PROVEEDOR == $array_proveedor[$i+count($array_proveedor)/2]){ $selected_proveedor='selected'; }else{ $selected_proveedor='';	}				
				$html_proveedor.='<option '.$selected_proveedor.' value="'.$array_proveedor[$i+count($array_proveedor)/2].",".$reg->IDPERSONA.",".$reg->IDGERENTE.'">'.$array_proveedor[$i].'</option>';
			} 	

			$html_proveedor.='</select>';
			//END COMBO PROVEEDOR	

			$data ['data'] [] = array (
				'NOMBRE'=>$reg->NOMBRE,
				'NOM_AREA'=>$reg->NOM_AREA,
				'NOM_CARGO'=>$reg->NOM_CARGO,
				'GERENTE'=>$reg->GERENTE,
				'COLEGA'=>$html_colega,
				'CLIENTE'=>$html_cliente,
				'PROVEEDOR'=>$html_proveedor,
				);
		}
		echo json_encode ( $data );
	}

	public function addcolega(){		
		$array = explode(",", $_POST['idcolega']);
		$idcolega = $array[0];
		$idpersona = $array[1];
		$idgerente = $array[2];
		$idevaluacion = $_POST['idevaluacion'];
		$objModel=$this->loadModel('asignacion');
		$result=$objModel->addcolega($idcolega, $idpersona, $idgerente, $idevaluacion);
		if($result) echo 1; else echo 0;
	}

	public function addcliente(){		
		$array = explode(",", $_POST['idcliente']);
		$idcliente = $array[0];
		$idpersona = $array[1];
		$idgerente = $array[2];
		$idevaluacion = $_POST['idevaluacion'];
		$objModel=$this->loadModel('asignacion');
		$result=$objModel->addcliente($idcliente, $idpersona, $idgerente, $idevaluacion);
		if($result) echo 1; else echo 0;
	}

	public function addproveedor(){		
		$array = explode(",", $_POST['idproveedor']);
		$idproveedor = $array[0];
		$idpersona = $array[1];
		$idgerente = $array[2];
		$idevaluacion = $_POST['idevaluacion'];
		$objModel=$this->loadModel('asignacion');
		$result=$objModel->addproveedor($idproveedor, $idpersona, $idgerente, $idevaluacion);
		if($result) echo 1; else echo 0;
	}


}

?>