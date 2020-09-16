<?php 

class ventaController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){		
		$this->_view->setJs(array('index'));
		$objModel=$this->loadModel('venta');
		$this->_view->productos=$objModel->getComboProductos();
		$this->_view->renderizar('index');
	}

	public function getProductoDetalle(){
		$idProducto = $_POST['idProducto'];
		$objModel=$this->loadModel('venta');
		$result = $objModel->getProductoDetalle($idProducto);
		echo '<option selected disabled> SELECCIONE </option>';
		while ($reg = $result->fetch_object()){
			echo '<option value="'.$reg->IDPRODUCTODETALLE.'" > '.$reg->MARCA." / ".$reg->MODELO. ' </option>';
		}
	}

	public function getPrecios(){
		$idProductoDetalle = $_POST['idProductoDetalle'];
		$objModel=$this->loadModel('venta');
		$result = $objModel->getPrecios($idProductoDetalle);
		echo '<option> SELECCIONE </option>';
		while ($reg = $result->fetch_object()){
			echo '<option value="'.$reg->IDSTOCK.'" precio="'.$reg->PRECIO_VENTA.'"> '.$reg->PRECIO_VENTA. ' </option>';
		}
	}

	public function getStockActual(){
		$idStock = $_POST['idStock'];
		$objModel=$this->loadModel('venta');
		$result = $objModel->getStockActual($idStock);
		echo json_encode($result->fetch_object());
	}

	public function getDetalleProducto(){
		$idProductoDetalle = $_POST['idProductoDetalle'];
		$objModel=$this->loadModel('venta');
		$result = $objModel->getDetalleProducto($idProductoDetalle);
		echo json_encode($result->fetch_object());
	}

	public function addVenta(){

		$idProducto = $_POST['idProducto'];
		$idProductoDetalle = $_POST['idProductoDetalle'];
		$idStock = $_POST['idStock'];
		$cantidad = $_POST['cantidad'];		
		$precioVenta = $_POST['precioVenta'];
		$precioSugerido = $_POST['precioSugerido'];
		$stockActual = $_POST['stockActual'];
		$observacion= strtoupper(trim($_POST['observacion']));		

		$objModel=$this->loadModel('venta');
		$result = $objModel->addVenta($idProducto,$idProductoDetalle,$idStock,$cantidad,$precioVenta,$precioSugerido,$stockActual,$observacion);
		if($result) echo 'ok'; else echo 'error';
	}
}

?>