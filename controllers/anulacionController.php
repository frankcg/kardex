<?php 

class anulacionController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){		
		$this->_view->setJs(array('index'));
		$this->_view->renderizar('index');
	}

	public function getCompras($codCompra=0, $fechaInicio='', $fechafin=''){
		/*echo $codCompra.'1<br>';
		echo $fechaInicio.'2<br>';
		echo $fechafin.'3<br>';
		exit();*/
		
		$objModel=$this->loadModel('anulacion');
		$result=$objModel->getCompras($codCompra, $fechaInicio, $fechafin);
		$data = array();

		while($reg=$result->fetch_object()){

			/*if($reg->ESTADO != 'ACTIVO'){
				$btn='btn-success';
				$icon='fa-check';
				$title='Habilitar';
				$class='activarusuario';
			}else{
				$btn='btn-danger';
				$icon='fa-close';
				$title='Inhabilitar';
				$class='desactivarusuario';
			}
			*/		

			$btn='btn-danger';
			$icon='fa-close';
			$title='Eliminar';
			$class='eliminarCompra';				

			$boton='<button idCompra="'.$reg->nIDCOMPRA.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';

			$data ['data'] [] = array(
				'FECHA_COMPRA' => $reg->dFECHACOMPRA,				
				'IDCOMPRA' => $reg->nIDCOMPRA,
				'PROVEEDOR'=>utf8_encode($reg->sLABEL),				
				'CANTIDAD_TOTAL_COMPRA' => $reg->CANTIDAD_TOTAL_COMPRA,
				'COSTO_TOTAL_COMPRA' => $reg->COSTO_TOTAL_COMPRA,
				'OBSERVACION' => $reg->sOBSERVACION,
				//'OPCIONES'=>utf8_encode($boton),
			);
		}
		echo json_encode ( $data );		
	}
}

?>