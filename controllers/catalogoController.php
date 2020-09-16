<?php 

class catalogoController extends Controller{
	
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

	public function getCatalogo(){
		$objModel=$this->loadModel('catalogo');
		$result=$objModel->getCatalogo();
		$cont=0;
		$data = array();
		while($reg=$result->fetch_object()){
			$cont++;

			$data ['data'] [] = array (				
				'CONT'=>$cont,
				'PRODUCTO' => utf8_encode($reg->NOMBRE),
				'MARCA' => utf8_encode($reg->MARCA),
				'MODELO' => utf8_encode($reg->MODELO),
				'PRECIO'=> 'S/.'.$reg->PRECIO_VENTA,
				'STOCK_GENERAL'=>$reg->STOCK_GENERAL,
				'CANTIDAD_VENTAS'=>$reg->CANT_VENTA,
				'STOCK_ACTUAL'=>$reg->STOCK_ACTUAL,
				'IDPRODUCTO'=>$reg->IDPRODUCTO,
				'IDPRODUCTODETALLE'=>$reg->IDPRODUCTODETALLE,
				'IDSTOCK'=>$reg->IDSTOCK,
				);
		}
		echo json_encode ( $data );
	}
}
?>