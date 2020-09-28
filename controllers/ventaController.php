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

	public function getCompras(){
		$idProducto = $_POST['idProducto'];
		$objModel=$this->loadModel('venta');
		$result = $objModel->getCompras($idProducto);
		echo '<option selected disabled> SELECCIONE </option>';
		while ($reg = $result->fetch_object()){
			echo '<option value="'.$reg->IDCOMPRA.'" > '.$reg->ALIAS. ' </option>';
		}
	}

	public function getStockActual(){
		$idCompra = $_POST['idCompra'];
		$objModel=$this->loadModel('venta');
		$result = $objModel->getStockActual($idCompra);
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
		$idCompra = $_POST['idCompra'];
		$cantidad = $_POST['cantidad'];		
		$precioVenta = $_POST['precioVenta'];		
		$observacion= strtoupper(trim($_POST['observacion']));		

		$objModel=$this->loadModel('venta');
		$result = $objModel->addVenta($idProducto,$idCompra,$cantidad,$precioVenta,$observacion);
		if($result) echo 'ok'; else echo 'error';
	}
}

?>