<?php 

class anulacionController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){
		$idLocal = $_GET['idLocal'];
		$this->_view->idLocal=$idLocal;
		$this->_view->setJs(array('index'));
		$this->_view->renderizar('index');
	}

	public function getCompras($codLocal=0, $codCompra=0, $fechaInicio='', $fechafin=''){		
		
		$objModel=$this->loadModel('anulacion');
		$result=$objModel->getCompras($codLocal, $codCompra, $fechaInicio, $fechafin);
		$data = array();

		while($reg=$result->fetch_object()){			

			$data ['data'] [] = array(
				'FECHA_COMPRA' => $reg->dFECHACOMPRA,				
				'IDCOMPRA' => $reg->nIDCOMPRA,
				'PROVEEDOR'=>utf8_encode($reg->sDESCRIPCION),				
				'CANTIDAD_TOTAL_COMPRA' => $reg->CANTIDAD_TOTAL_COMPRA,
				'COSTO_TOTAL_COMPRA' => $reg->COSTO_TOTAL_COMPRA,
				'OBSERVACION' => $reg->sOBSERVACION
			);
		}
		echo json_encode ( $data );		
	}

	public function getDetalleCompra(){
		$idCompra = $_POST['idcompra'];
		$objModel=$this->loadModel('anulacion');
		$result=$objModel->getDetalleCompra($idCompra);
		$data = array();
		while($reg=$result->fetch_object()){
			$data[] = array(
				'nIDDETALLE'	=> $reg->nIDCOMPRADETALLE,
				'nIDCOMPRA'	=> $reg->nIDCOMPRA,
				'nIDPRODUCTO'	=> $reg->nIDPRODUCTO,
				'nCANTIDAD'	=> $reg->nCANTIDAD,
				'fPRECIO'	=> $reg->fPRECIO,
				'fCOSTO'	=> $reg->COSTO,
				'sPRODUCTO'	=> $reg->PRODUCTO,
			);
		}
		echo json_encode($data);
	}

	public function anularCompra(){
		$idCompra = $_POST['idcompra'];
		$motivo = $_POST['motivo'];
		$objModel=$this->loadModel('anulacion');
		$result=$objModel->anularCompra($idCompra, $motivo);
		echo json_encode(array('idAnulacion'=>$result));
	}

}

?>