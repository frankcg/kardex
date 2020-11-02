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
		$data = array();
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
		$data = array();
		while($reg=$result->fetch_object()){
			$cont++;

			$boton='<button id="'.$reg->nIDVENTA.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';

			$data ['data'] [] = array (
				'nIDVENTA' 		=> $reg->nIDVENTA,
				'nIDLOCAL' 		=> $reg->nIDLOCAL,
				'nLOCAL' 		=> $reg->nLOCAL,
				'dFECHAVENTA' 	=> $reg->dFECHAVENTA,
				'sCLIENTE'=>utf8_encode($reg->sCLIENTE),
				'sLOCAL'=>utf8_encode($reg->sLOCAL),
				'sOBSERVACION' => $reg->sOBSERVACION,
				'total' 		=> $reg->total,
				'OPCIONES' 		=> $boton,
				);
		}
		echo json_encode ( $data );
	}


	public function getVentasTable($codLocal=0, $tipoVenta=0, $fechaInicio='', $fechafin=''){		
		$objModel=$this->loadModel('reporte');
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



	public function getCompras($estado,$fechaInicio, $fechafin,$idLocal){
 
		$objModel=$this->loadModel('reporte');
		$result=$objModel->getCompras($idLocal,$fechaInicio, $fechafin,$estado);
		$cont=0;

		$btn='btn-info';
		$icon='fa-file';
		$title='Habilitar';
		$class='viewPdf';
		$data = array();
		while($reg=$result->fetch_object()){
			$cont++;
			$boton='<button id="'.$reg->nIDCOMPRA.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';
			$data ['data'] [] = array (
				'nIDCOMPRA' 	=> $reg->nIDCOMPRA,
				'nIDLOCAL' 		=> $reg->nIDLOCAL,
				'nLOCAL' 		=> $reg->nLOCAL,
				'dFECHACOMPRA' 	=> $reg->dFECHACOMPRA,
				'total' 		=> $reg->total,
				'PROVEEDOR' 	=> $reg->PROVEEDOR,
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
		$data = array();
		while($reg=$result->fetch_object()){
			$cont++;
			$boton='<button id="'.$reg->nIDCOMPRA.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';
			$data ['data'] [] = array (
				'dFECHACOMPRA'	=> $reg->dFECHACOMPRA,
				'nIDCOMPRA'	=> $reg->nIDCOMPRA, 
				'nIDPROVEEDOR'	=> $reg->nIDPROVEEDOR, 
				'sPROVEEDOR'	=> $reg->sPROVEEDOR, 
				'nIDLOCAL'	=> $reg->nIDLOCAL, 
				'sOBSERVACION'	=> $reg->sOBSERVACION, 
				'nCantidadTotalCompra'	=> $reg->nCantidadTotalCompra,
				'sCostoTotalCompra'	=> $reg->sCostoTotalCompra, 
				'sPagoTotalCompra'	=> $reg->sPagoTotalCompra, 
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
		$data = array();
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



	public function getInversion($idLocal){
 
		$objModel=$this->loadModel('reporte');
		$result=$objModel->getInversion($idLocal);
		$cont=0;

		$btn='btn-info';
		$icon='fa-file';
		$title='Habilitar';
		$class='viewPdf';
		$data = array();
		while($reg=$result->fetch_object()){
			$cont++;
			$boton='<button id="'.$reg->nIDPRODUCTO.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';
			$data ['data'] [] = array (
					'nIDPRODUCTO' => $reg->nIDPRODUCTO,
					'sNOMBRE' => $reg->sNOMBRE,
					'nCANTIDAD' => $reg->nCANTIDAD,
					'nTOTAL' => $reg->nTOTAL,
				);
		}
		echo json_encode ( $data );
	}


	public function getReporteBalance($fechaInicio, $fechafin,$idLocal){
		$objModel=$this->loadModel('reporte');
		$result=$objModel->getReporteBalance($fechaInicio, $fechafin,$idLocal);
		$data = array();
		while($reg=$result->fetch_object()){
			$data ['data'] [] = array (
				'FECHA' => $reg->FECHA,
				'ventas' => $reg->ventas,
				'ventasProductos' => $reg->ventasProductos,
				'ventasCosto' => $reg->ventasCosto,
				'efectivo' => $reg->efectivo,
				'deposito' => $reg->deposito,
				'credito' => $reg->credito,
				);
		}
		echo json_encode ( $data );
	}

	
}
?>