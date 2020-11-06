<?php 

class reportevendedorController extends Controller{
	
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

	public function getDetalleVentaPago(){
		$idVenta = $_POST['idVenta'];
		$objModel=$this->loadModel('reportevendedor');
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

	public function getDetalleVenta(){
		$idVenta = $_POST['idVenta'];
		$objModel=$this->loadModel('reportevendedor');
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
	
	public function getVentasTable($codLocal=0, $tipoVenta=0, $fechaInicio='', $fechafin=''){		
		$objModel=$this->loadModel('reportevendedor');
		$result=$objModel->getVentasTable($codLocal, $tipoVenta, $fechaInicio, $fechafin);
		$data = array();

		$btn='btn-info';
		$icon='fa-file';
		$title='Habilitar';
		$class='viewPdf';
		$data = array();
		while($reg=$result->fetch_object()){			
			$boton='<button id="'.$reg->nIDVENTA.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';

			$data ['data'] [] = array(
				'dFECHAVENTA' => $reg->dFECHAVENTA,				
				'nIDVENTA' => $reg->nIDVENTA,
				'nIDVENTACOMPARTIDA' => $reg->nIDVENTACOMPARTIDA,
				'sCLIENTE'=>utf8_encode($reg->sCLIENTE),
				'sLOCAL'=>utf8_encode($reg->sLOCAL),
				'sCostoTotalVenta' => $reg->sCostoTotalVenta,
				'sPagoTotalVenta' => $reg->sPagoTotalVenta,
				'sDeudaTotalVenta' => $reg->sDeudaTotalVenta,
				'nCantidadTotalVenta' => $reg->nCantidadTotalVenta,				
				'sOBSERVACION' => $reg->sOBSERVACION,
				'OPCIONES' => $boton
			);
		}
		echo json_encode ( $data );		
	}

}
?>