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
				'IDSTOCK' => $reg->IDSTOCK,
				'IDPRODUCTODETALLE' => $reg->IDPRODUCTODETALLE,
				'FECHA_VENTA' => $reg->FECHA_VENTA,
				'PRODUCTO' => $reg->PRODUCTO,
				'MARCA' => $reg->MARCA,
				'MODELO' => $reg->MODELO,
				'CANTIDAD' => $reg->CANTIDAD,
				'PRECIO_SUGERIDO' => $reg->PRECIO_SUGERIDO,
				'PRECIO_VENDIDO' => $reg->PRECIO_VENDIDO,
				'VENDEDOR' => $reg->VENDEDOR,
				);
		}
		echo json_encode ( $data );
	}
}
?>