<?php 

class extornoController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){		
		$idLocal = $_GET['idLocal'];
		$this->_view->idLocal=$idLocal;

		$objModel=$this->loadModel('general');
		$nombreLocal = $objModel->getNombreLocal($idLocal);		
		$this->_view->nombreLocal=$nombreLocal;

		$this->_view->setJs(array('index'));
		$this->_view->renderizar('index');
	}

	public function getVentas($codLocal=0, $codVenta=0, $fechaInicio='', $fechafin=''){		
		$objModel=$this->loadModel('extorno');
		$result=$objModel->getVentas($codLocal, $codVenta, $fechaInicio, $fechafin);
		$data = array();

		while($reg=$result->fetch_object()){			

			$data ['data'] [] = array(
				'FECHA_VENTA' => $reg->dFECHAVENTA,				
				'IDVENTA' => $reg->nIDVENTA,
				'CLIENTE'=>utf8_encode($reg->sDESCRIPCION),				
				'CANTIDAD_TOTAL_VENTA' => $reg->CANTIDAD_TOTAL_VENTA,
				'COSTO_TOTAL_VENTA' => $reg->COSTO_TOTAL_VENTA,
				'OBSERVACION' => $reg->sOBSERVACION
			);
		}
		echo json_encode ( $data );		
	}

	public function getDetalleVenta(){
		$idVenta = $_POST['idVenta'];
		$objModel=$this->loadModel('extorno');
		$result=$objModel->getDetalleVenta($idVenta);
		$data = array();
		while($reg=$result->fetch_object()){
			$data[] = array(
				//'nIDDETALLE'	=> $reg->nIDVENTADETALLE,
				'nIDVENTA'	=> $reg->nIDVENTA,
				'nIDPRODUCTO'	=> $reg->nIDPRODUCTO,
				'nCANTIDAD'	=> $reg->nCANTIDAD,
				'fPRECIO'	=> $reg->fPRECIO,
				'fCOSTO'	=> $reg->COSTO,
				'sPRODUCTO'	=> $reg->PRODUCTO,
			);
		}
		echo json_encode($data);
	}

	public function extornarVenta(){
		$idVenta = $_POST['idVenta'];
		$motivo = $_POST['motivo'];
		$objModel=$this->loadModel('extorno');

		$objModel->updateStockCompra($idVenta);
		$result=$objModel->extornarVenta($idVenta, $motivo);
		
		echo json_encode(array('idExtorno'=>$result));
	}

}

?>