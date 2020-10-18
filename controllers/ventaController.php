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
		$this->_view->renderizar('index');
	}

	public function getComboProductos(){
		$objModel=$this->loadModel('venta');
		$result = $objModel->getComboProductos();
		echo '<option selected disabled> SELECCIONE </option>';
		while ($reg = $result->fetch_object()){
			echo '<option value="'.$reg->IDPRODUCTO.'"  id2="'.$reg->NOMBRE.'" id3="'.$reg->CANTIDAD.'"> '.$reg->NOMBRE. ' - '.$reg->CANTIDAD. '</option>';
		}
	}



	public function addproductCart(){

		$idProducto 	= $_POST['idProducto'];
		$nombre			= strtoupper(trim($_POST['nombre']));		
		$cantidad		= $_POST['cantidad'];
		$precioCompra	= $_POST['precioCompra'];
		$aliasCompra	= $_POST['aliasCompra'];
		$descripcion	= strtoupper(trim($_POST['descripcion']));

		$productsArray = array(
			"idProducto"=>$idProducto
			,"name"=>$nombre
			,"cantidad"=>$cantidad
			,"precio"=>$precioCompra
			,"alias"=>$aliasCompra
			,"descripcion"=>$descripcion
		);

			if(isset($_SESSION["cart"]["ventasproducts"][$nombre])){
				echo("if entra");
				// echo('<pre>');
				// print_r($productsArray);
				// echo('</pre>');

				array_splice($_SESSION["cart"]["ventasproducts"][$nombre],0);
				array_push($_SESSION["cart"]["ventasproducts"][$nombre],$productsArray);

				// echo('<pre>');
				// print_r($_SESSION["cart"]["products"][$nombre]);
				// echo('</pre>');

			}else{
				echo("else entra");
				$_SESSION["cart"]["ventasproducts"][$nombre] = array();
				array_push($_SESSION["cart"]["ventasproducts"][$nombre],$productsArray);
			}	
	
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