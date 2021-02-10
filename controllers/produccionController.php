<?php 

class produccionController extends Controller{
	
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

	public function getCompras($codLocal=0, $codCompra=0, $fechaInicio='', $fechafin=''){	
	 
		$objModel=$this->loadModel('produccion');
		$result=$objModel->getVentas($codLocal, $codCompra, $fechaInicio, $fechafin);
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

	public function getDetalleCompra($idCompra){
		$objModel=$this->loadModel('produccion');
		$result=$objModel->getDetalleCompra($idCompra);
		$data = array();
		while($reg=$result->fetch_object()){

			$detalleProduccion = array();
			
			$response=$objModel->getDetalleProduccion($reg->nIDCOMPRADETALLE);

			while($det=$response->fetch_object()){

				$detalleProduccion[] = array(
					'nIDPRODUCCION' => $det->nIDPRODUCCION,
					'nIDCOMPRADETALLE' => $det->nIDCOMPRADETALLE,
					'nCANTIDAD' => $det->nCANTIDAD,
					'dFECHAPRODUCCION' => $det->dFECHAPRODUCCION,
					'sIDUSUARIOCREACION' => $det->sIDUSUARIOCREACION,					
				); 
			}

			$data ['data'] [] = array(
				'nIDDETALLE'	=> $reg->nIDCOMPRADETALLE,
				'nIDCOMPRA'	=> $reg->nIDCOMPRA,
				'nIDPRODUCTO'	=> $reg->nIDPRODUCTO,
				'nCANTIDADCOMPRADA'	=> $reg->nCANTIDADCOMPRADA,
				'sPRODUCTO'	=> $reg->PRODUCTO,
				'nCANTIDADPRODUCIDA' => $reg->nCANTIDADPRODUCIDA,
				'nDIFERENCIA' => $reg->nDIFERENCIA,
				'detalleProduccion' => $detalleProduccion,
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