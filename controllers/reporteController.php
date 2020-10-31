<?php 

class reporteController extends Controller{
	
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


	public function getEstados(){
		$objModel=$this->loadModel('reporte');
		$result=$objModel->getEstados();
		$data = array();
		while($reg=$result->fetch_object()){
			$data[] = array(
				'nIDESTADO'	=> $reg->nIDESTADO,
				'sDESCRIPCION'			=> $reg->sDESCRIPCION,
			);
		}
		echo json_encode($data);
	}


	public function getReporteFecha($fechaInicio, $fechafin){
	
		$objModel=$this->loadModel('reporte');
		$result=$objModel->getReporteFecha($fechaInicio, $fechafin);
		$cont=0;

		while($reg=$result->fetch_object()){
			$cont++;

			$data ['data'] [] = array (
				'CONT' => $cont,
				'IDVENTA' => $reg->IDVENTA,
				'IDCOMPRA' => $reg->IDCOMPRA,
				'IDPRODUCTO' => $reg->IDPRODUCTO,
				'PRODUCTO' => $reg->PRODUCTO,
				'FECHA_VENTA' => $reg->FECHA_VENTA,				
				'CANTIDAD' => $reg->CANTIDAD,
				'OBSERVACION' => $reg->OBSERVACION,
				'PRECIO_VENTA_UNIDAD' => $reg->PRECIO_VENTA,
				'PRECIO_VENTA_TOTAL' => $reg->PRECIO_VENTA_TOTAL,
				'PRECIO_COMPRA_UNIDAD' => $reg->PRECIO_UNIDAD,
				'PRECIO_COMPRA_TOTAL' => $reg->PRECIO_COMPRA_TOTAL,
				'GANANCIA' => $reg->GANANCIA,
				'VENDEDOR' => $reg->VENDEDOR,
				);
		}
		echo json_encode ( $data );
	}

	public function getVentas($estado,$fechaInicio, $fechafin,$idLocal){
 
		$objModel=$this->loadModel('reporte');
		$result=$objModel->getVentas($idLocal,$fechaInicio, $fechafin,$estado);
		$cont=0;

		$btn='btn-info';
		$icon='fa-file';
		$title='Habilitar';
		$class='viewPdf';

		while($reg=$result->fetch_object()){
			$cont++;

			$boton='<button id="'.$reg->nIDVENTA.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';

			$data ['data'] [] = array (
				'nIDVENTA' 		=> $reg->nIDVENTA,
				'nIDLOCAL' 		=> $reg->nIDLOCAL,
				'nLOCAL' 		=> $reg->nLOCAL,
				'dFECHAVENTA' 	=> $reg->dFECHAVENTA,
				'total' 		=> $reg->total,
				'OPCIONES' 		=> $boton,
				);
		}
		echo json_encode ( $data );
	}

	public function getCompras($estado,$fechaInicio, $fechafin,$idLocal){
 
		$objModel=$this->loadModel('reporte');
		$result=$objModel->getCompras($idLocal,$fechaInicio, $fechafin,$estado);
		$cont=0;

		$btn='btn-info';
		$icon='fa-file';
		$title='Habilitar';
		$class='viewPdf';

		while($reg=$result->fetch_object()){
			$cont++;
			$boton='<button id="'.$reg->nIDCOMPRA.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';
			$data ['data'] [] = array (
				'nIDCOMPRA' 	=> $reg->nIDCOMPRA,
				'nIDLOCAL' 		=> $reg->nIDLOCAL,
				'nLOCAL' 		=> $reg->nLOCAL,
				'dFECHACOMPRA' 	=> $reg->dFECHACOMPRA,
				'total' 		=> $reg->total,
				'OPCIONES' 		=> $boton,
				);
		}
		echo json_encode ( $data );
	}

	public function getCuentasPorPagar($fechaInicio, $fechafin,$idLocal){
 
		$objModel=$this->loadModel('reporte');
		$result=$objModel->getCuentasPorPagar($idLocal,$fechaInicio, $fechafin);
		$cont=0;

		$btn='btn-info';
		$icon='fa-file';
		$title='Habilitar';
		$class='viewPdf';

		while($reg=$result->fetch_object()){
			$cont++;
			$boton='<button id="'.$reg->nIDCOMPRA.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';
			$data ['data'] [] = array (
				'dFECHACOMPRA'	=> $reg->dFECHACOMPRA , 
				'nIDCOMPRA'	=> $reg->nIDCOMPRA , 
				'nIDPROVEEDOR'	=> $reg->nIDPROVEEDOR , 
				'nIDLOCAL'	=> $reg->nIDLOCAL , 
				'sOBSERVACION'	=> $reg->sOBSERVACION , 
				'nCantidadTotalCompra'	=> $reg->nCantidadTotalCompra,
				'sCostoTotalCompra'	=> $reg->sCostoTotalCompra , 
				'sPagoTotalCompra'	=> $reg->sPagoTotalCompra , 
				'sDeudaTotalCompra'	=> $reg->sDeudaTotalCompra,
				);
		}
		echo json_encode ( $data );
	}

	public function getCuentasPorCobrar($fechaInicio, $fechafin,$idLocal){
 
		$objModel=$this->loadModel('reporte');
		$result=$objModel->getCuentasPorCobrar($idLocal,$fechaInicio, $fechafin);
		$cont=0;

		$btn='btn-info';
		$icon='fa-file';
		$title='Habilitar';
		$class='viewPdf';

		while($reg=$result->fetch_object()){
			$cont++;
			$boton='<button id="'.$reg->nIDVENTA.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';
			$data ['data'] [] = array (
					'dFECHAVENTA' => $reg->dFECHAVENTA,
					'nIDVENTA' => $reg->nIDVENTA,
					'nIDCLIENTE' => $reg->nIDCLIENTE,
					'nIDLOCAL' => $reg->nIDLOCAL,
					'sOBSERVACION' => $reg->sOBSERVACION,
					'nCantidadTotalVenta' => $reg->nCantidadTotalVenta,
					'sCostoTotalVenta' => $reg->sCostoTotalVenta,
					'sPagoTotalVenta' => $reg->sPagoTotalVenta,
					'sDeudaTotalVenta' => $reg->sDeudaTotalVenta,
					'sCLIENTE' => $reg->sCLIENTE,
					'sLOCAL' => $reg->sLOCAL,
				);
		}
		echo json_encode ( $data );
	}





	
}
?>