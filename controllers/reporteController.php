<?php 

class reporteController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){		
		$this->_view->setJs(array('index'));
		//$objModel=$this->loadModel('producto');
		//$this->_view->productos=$objModel->getComboProductos();
		$this->_view->renderizar('index');
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
	
}
?>