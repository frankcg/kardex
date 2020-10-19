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


	public function clearCartventas(){
		unset($_SESSION["cart"]["ventasproducts"]);
		$_SESSION['cart']['ventasproducts'] = array();
	}



	public function addproductCart(){
 
		$idProducto		= $_POST['idProducto'];		
		$cantidad		= $_POST['cantidad'];
		$cartNombre		= $_POST['cartNombre'];
		$precioCompra	= $_POST['precioCompra'];
 

		$productsArray = array(
			"idProducto"=>$idProducto
			,"cartNombre"=>$cartNombre
			,"cantidad"=>$cantidad
			,"precio"=>$precioCompra
		);

		// $this->clearCartventas();

			if(isset($_SESSION["cart"]["ventasproducts"][$idProducto])){
				
				// echo("if entra");
				// echo('<pre>');
				// print_r($productsArray);
				// echo('</pre>');

				array_splice($_SESSION["cart"]["ventasproducts"][$idProducto],0);
				array_push($_SESSION["cart"]["ventasproducts"][$idProducto],$productsArray);

				// echo('<pre>');
				// print_r($_SESSION["cart"]["ventasproducts"]);
				// echo('</pre>');

			}else{
				echo("else entra");
				$_SESSION["cart"]["ventasproducts"][$idProducto] = array();
				array_push($_SESSION["cart"]["ventasproducts"][$idProducto],$productsArray);
			}	
	}

	public function showproductCart(){

		$tableProducts = "";
 
		if(!empty($_SESSION["cart"]["ventasproducts"])){
		foreach ($_SESSION["cart"]["ventasproducts"] as $productos) {
			foreach($productos as $items){
				if($items["name"]!==""){
					$tableProducts .= '<tr> 
						<td class="p-a-2">
							<div class="font-weight-semibold">'.$items["cartNombre"].'</div>
							<div class="font-size-12 text-muted"><strong>ITEM ID : </strong>'.$items["idProducto"].'</div>
						</td>
						<td class="p-a-2">
							<strong>'.$items["cantidad"].'</strong>
						</td>
						<td class="p-a-2">
							<strong>'.$items["precio"].'</strong>
						</td>
						<td class="p-a-2">
							<strong class="total">'.$items["cantidad"]*$items["precio"].'</strong>
						</td>
						<td class="p-a-2">
							 
						<button type="button" class="btn btn-xs btn-warning btn-outline btn-rounded btn-outline-colorless btn-delete" id="'.$items["idProducto"].'">x</button>

						</td>
					  </tr>'
					  ;
					// echo('<pre>');
					// print_r($items);
					// echo('</pre>');
				}
			}
		  }
		}else {
			$tableProducts .= '<tr> 
						<td class="p-a-2">
							<div class="font-weight-semibold">-</div>
						</td>
						<td class="p-a-2">
							<strong>-</strong>
						</td>
						<td class="p-a-2">
							<strong>-</strong>
						</td>
						<td class="p-a-2">
							<strong>-</strong>
						</td>
					  </tr>'
					  ;
		}
		  echo($tableProducts);
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

	
public function addpaymentCart(){
	$formaPago		= $_POST['formaPago']; 	
	$cuenta			= $_POST['cuenta']; 		
	$montopago		= $_POST['montopago']; 		
 
	$paymentArray = array(
		"formaPago"		=>$formaPago
		,"cuenta"		=>$cuenta
		,"montopago"	=>$montopago

	);
	
	array_push($_SESSION["cart"]["ventaspayments"],$paymentArray);

}

public function showpaymentCart(){

	$tablePayments = "";	

	if(!empty($_SESSION["cart"]["ventaspayments"])){
	foreach ($_SESSION["cart"]["ventaspayments"] as $key => $value) {
			if($value["formaPago"]!==""){

				if($value["formaPago"]=="1"){
					$forma = "EFECTIVO";
				}else{
					$forma = "DEPOSITO";
				};

				$tablePayments .= '<tr> 
					<td class="p-a-2">
						<div class="font-weight-semibold">'.$key.'</div>
					</td>
					<td class="p-a-2">
						<div class="font-weight-semibold">'.$forma.'</div>
					</td>
					<td class="p-a-2">
						<strong class="pagototal" >'.$value["montopago"].'</strong>
					</td>
 
					<td class="p-a-2">
						 
					<button type="button" class="btn btn-xs btn-warning btn-outline btn-rounded btn-outline-colorless btn-delete" id="'.$key.'">x</button>

					</td>
				  </tr>'
				  ;
		}
	  }
	}else {
		$tablePayments .= '<tr> 
					<td class="p-a-2">
						<div class="font-weight-semibold">-</div>
					</td>
					<td class="p-a-2">
						<strong>-</strong>
					</td>
					<td class="p-a-2">
						<strong>-</strong>
					</td>
					<td class="p-a-2">
						<strong>-</strong>
					</td>
				  </tr>'
				  ;
	}
	  echo($tablePayments);
}


}


?>