<?php 

class cuentacobroController extends Controller{
	
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

		$objModel2=$this->loadModel('cuentacobro');
		$this->_view->clientes=$objModel2->getClientesPorCobrar($idLocal);

		$this->_view->setJs(array('index'));
		$this->_view->renderizar('index');
	}

	public function getCuentasPorCobrar($codLocal=0, $codVenta=0, $fechaInicio='', $fechafin='', $nIdCliente=''){		
		$objModel=$this->loadModel('cuentacobro');
		$result=$objModel->getCuentasPorCobrar($codLocal, $codVenta, $fechaInicio, $fechafin, $nIdCliente);
		$data = array();

		while($reg=$result->fetch_object()){			

			$data ['data'] [] = array(
				'dFECHAVENTA' => $reg->dFECHAVENTA,				
				'nIDVENTA' => $reg->nIDVENTA,
				'sCLIENTE'=>utf8_encode($reg->sCLIENTE),
				'sLOCAL'=>utf8_encode($reg->sLOCAL),
				'sCostoTotalVenta' => $reg->sCostoTotalVenta,
				'sPagoTotalVenta' => $reg->sPagoTotalVenta,
				'sDeudaTotalVenta' => $reg->sDeudaTotalVenta,
				'nCantidadTotalVenta' => $reg->nCantidadTotalVenta,				
				'sOBSERVACION' => $reg->sOBSERVACION
			);
		}
		echo json_encode ( $data );		
	}

	public function getDetalleVentaPago(){
		$idVenta = $_POST['idVenta'];
		$objModel=$this->loadModel('cuentacobro');
		$result=$objModel->getDetalleVentaPago($idVenta);
		$data = array();
		while($reg=$result->fetch_object()){
			$data[] = array(
				'nIDVENTAPAGO' => $reg->nIDVENTAPAGO,
				'nIDVENTA' => $reg->nIDVENTA, 
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

		$idVenta = $_POST['idVenta'];
		$observacionVentaPago = $_POST['observacionVentaPago'];
		$fechaPago = $_POST['fechaPago'];
		$formaPago = $_POST['formaPago'];
		$cuenta = $_POST['cuenta'];
		$idCuenta = $_POST['idCuenta'];		
		$montopago = $_POST['montopago'];

		$idCuentacompra = 1; // pago en efectivo

		$objModelCompra=$this->loadModel('compra');
		$existeCuenta= ($idCuenta=='' || !$idCuenta) ? 0 : $objModelCompra->cuentavalidate($idCuenta);
		
		if(!$existeCuenta){
			if($formaPago=='02'){
				$idCuentacompra = $objModelCompra->insertCuenta($cuenta);	
			}		
		}else{
			if($formaPago=='02'){
				$idCuentacompra =$idCuenta;	
			}
		}

		$objModel=$this->loadModel('cuentacobro');
		$result=$objModel->addPago($idVenta, $observacionVentaPago, $fechaPago, $formaPago, $idCuentacompra, $montopago);	
		echo json_encode(array('idPago'=>$result));
	}

}