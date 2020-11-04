<?php 

class cuentapagoController extends Controller{
	
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

	public function getCuentasPorPagar($codLocal=0, $codCompra=0, $fechaInicio='', $fechafin=''){		
		$objModel=$this->loadModel('cuentapago');
		$result=$objModel->getCuentasPorPagar($codLocal, $codCompra, $fechaInicio, $fechafin);
		$data = array();

		while($reg=$result->fetch_object()){			

			$data ['data'] [] = array(
				'dFECHACOMPRA' => $reg->dFECHACOMPRA,				
				'nIDCOMPRA' => $reg->nIDCOMPRA,
				'sPROVEEDOR'=>utf8_encode($reg->sPROVEEDOR),
				'sLOCAL'=>utf8_encode($reg->sLOCAL),
				'sCostoTotalCompra' => $reg->sCostoTotalCompra,
				'sPagoTotalCompra' => $reg->sPagoTotalCompra,
				'sDeudaTotalCompra' => $reg->sDeudaTotalCompra,
				'nCantidadTotalCompra' => $reg->nCantidadTotalCompra,				
				'sOBSERVACION' => $reg->sOBSERVACION
			);
		}
		echo json_encode ( $data );		
	}

	public function getDetalleCompraPago(){
		$idCompra = $_POST['idcompra'];
		$objModel=$this->loadModel('cuentapago');
		$result=$objModel->getDetalleCompraPago($idCompra);
		$data = array();
		while($reg=$result->fetch_object()){
			$data[] = array(
				'nIDCOMPRAPAGO' => $reg->nIDCOMPRAPAGO,
				'nIDCOMPRA' => $reg->nIDCOMPRA, 
				'nIDTIPOPAGO' => $reg->nIDTIPOPAGO, 
				'fMONTO' => $reg->fMONTO, 
				'nIDCUENTA' => $reg->nIDCUENTA, 
				'sNROCUENTA' => $reg->sNROCUENTA, 
				'sOBSERVACION' => $reg->sOBSERVACION, 
				'dFECHAPAGO' => $reg->dFECHAPAGO,
				'sTIPOPAGO' => $reg->sTIPOPAGO,
			);
		}
		echo json_encode($data);
	}

	public function addPago(){

		$idCompra = $_POST['idcompra'];
		$observacionCompraPago = $_POST['observacionCompraPago'];
		$fechaPago = $_POST['fechaPago'];
		$formaPago = $_POST['formaPago'];
		$cuenta = $_POST['cuenta'];
		$idCuenta = $_POST['idCuenta'];		
		$montopago = $_POST['montopago'];

		$idCuentacompra = 1; // pago en efectivo

		$objModelCompra=$this->loadModel('compra');
		$existeCuenta= ($idCuenta=='' || !$idCuenta) ? 0 : $objModelCompra->cuentavalidate($idCuenta);
		
		if($existeCuenta !== 1){
			if($formaPago=='02'){
				$idCuentacompra = $objModelCompra->insertCuenta($cuenta);	
			}		
		}else{
			if($formaPago=='02'){
				$idCuentacompra =$idCuenta;	
			}
		}

		$objModel=$this->loadModel('cuentapago');
		$result=$objModel->addPago($idCompra, $observacionCompraPago, $fechaPago, $formaPago, $idCuentacompra, $montopago);	
		echo json_encode(array('idPago'=>$result));
	}

}