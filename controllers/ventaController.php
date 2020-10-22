<?php 

class ventaController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){		
		$idLocal = $_GET['idLocal'];
		$this->_view->idLocal=$idLocal;
		$this->_view->setJs(array('index'));
		$this->_view->renderizar('index');
	}

	public function getComboProductos(){
		$objModel=$this->loadModel('venta');
		$result = $objModel->getComboProductos();
		echo '<option selected disabled> SELECCIONE </option>';
		while ($reg = $result->fetch_object()){
			echo '<option value="'.$reg->nIDPRODUCTO.'"  id2="'.$reg->sNOMBRE.'" id3="'.$reg->nCANTIDAD.'"> '.$reg->sNOMBRE. ' - '.$reg->nCANTIDAD. '</option>';
		}
	}

	
	public function getTipopago(){
		$objModel=$this->loadModel('venta');
		$result = $objModel->getTipopago();
		echo '<option selected value="" disabled> SELECCIONE </option>';
		while ($reg = $result->fetch_object()){
			echo '<option value="'.$reg->nIDTIPOPAGO.'" > '.$reg->sDESCRIPCION. '  </option>';
		}
	}

	public function autocuenta(){
		$search = $_GET['query'];
		$objModel=$this->loadModel('venta');
		$result=$objModel->autocuenta($search);		
		$data = array();
		while($reg=$result->fetch_object()){
			$data[] = array("value"=>$reg->nIDCUENTA,"label"=>$reg->sDESCRIPCION);
		}
		echo json_encode($data);
	}

	public function promVentaProductos(){
		$idProducto = $_POST['idProducto'];
		$objModel=$this->loadModel('venta');
		$result=$objModel->promVentaProductos($idProducto);
		$data = array();
		while($reg=$result->fetch_object()){
			$data[] = array(
				'nIDPRODUCTO'	=> $reg->nIDPRODUCTO,
				'LAST'			=> $reg->LAST,
				'MAX'		=> $reg->MAX,
				'MIN'		=> $reg->MIN,
				'AVG'		=> $reg->AVG,
			);
		}
		echo json_encode($data);
	}

	public function autocliente(){
		$search = $_GET['query'];
		$objModel=$this->loadModel('venta');
		$result=$objModel->autocliente($search);		
		$data = array();
		while($reg=$result->fetch_object()){
			$data[] = array("value"=>$reg->nIDCLIENTE,"label"=>$reg->sDESCRIPCION);
		}
		echo json_encode($data);
	}
	





	public function clearCartventas(){
		unset($_SESSION["cart"]["ventasproducts"]);
		$_SESSION['cart']['ventasproducts'] = array();

		unset($_SESSION["cart"]["ventaspayments"]);
		$_SESSION['cart']['ventaspayments'] = array();
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
				if($items["cartNombre"]!==""){
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


	public function clearproductCart(){
		$id= $_POST['id'];

		print_r($_POST);
		echo($id);
		echo("entro al post");

		unset($_SESSION["cart"]["ventasproducts"][$id]);

		if(!isset($_SESSION["cart"]["ventasproducts"][$id])){
			return 1;
		}else {
			return 0;
		}

	}
	

	public function clearpaymentCart(){
		$id= $_POST['id'];

		print_r($_POST);
		echo($id);
		echo("entro al post");

		unset($_SESSION["cart"]["ventaspayments"][$id]);

		if(!isset($_SESSION["cart"]["ventaspayments"][$id])){
			return 1;
		}else {
			return 0;
		}

	}

	
	public function addpaymentCart(){

		$formaPago		= $_POST['formaPago']; 	
		$cuenta			= $_POST['cuenta']; 		
		$montopago		= $_POST['montopago']; 		
		$montoapagar	= $_POST['montoapagar']; 	
		$idCuenta		= $_POST['idCuenta']; 

		$pagosTotal = 0;

		foreach ($_SESSION["cart"]["ventaspayments"] as $payments) {
			$pagosTotal 	= $pagosTotal + $payments['montopago'];
		}

		if($pagosTotal + $montopago <= $montoapagar){
			$paymentArray = array(
				"formaPago"		=>$formaPago
				,"cuenta"		=>$cuenta
				,"montopago"	=>$montopago
				,"idCuenta"				=>$idCuenta
			);
			array_push($_SESSION["cart"]["ventaspayments"],$paymentArray);
			echo("1");
			// echo('<pre>');
			// print_r($_SESSION);
			// echo('</pre>');
		}else{
			echo("0");
		}


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


	

	public function finishpaymentCart(){

		$codLocal		= $_POST['codLocal'];
		$cliente		= $_POST['proveedor'];
		$idCLiente		= $_POST['idProveedor'];
		$observaciones	= $_POST['observaciones'];

		$objModel=$this->loadModel('venta');

		// echo('<pre>');
		// print_r($_SESSION["cart"]["ventaspayments"] );
		// echo('</pre>');
		// exit();

		try {

			$existeProveedor =	$objModel->clientevalidate($idCLiente);
				if($existeProveedor !== 1){
					$idClientecompra =	$objModel->insertCliente($cliente);
				}else{
					$idClientecompra =	$idCLiente;
				}

				
			$idventa = $objModel->insertVenta($codLocal,$idClientecompra,$observaciones);


			try {


				foreach ($_SESSION["cart"]["ventasproducts"] as $productos) {
					foreach($productos as $items){
		
						$idProducto = $items['idProducto'];
						// $nombre = $items['cartNombre'];
						$cantidad = $items['cantidad'];
						$precio = $items['precio'];

						$result =	$objModel->insertVentaDetalle($idventa,$idProducto,$cantidad,$precio); 	

					}
				}

			try {
					foreach ($_SESSION["cart"]["ventaspayments"] as $payments) {
		
						$idtipopago = $payments['formaPago'];
						$cuenta 	= $payments['cuenta'];
						$montopago 	= $payments['montopago'];
						$idCuenta 	= $payments['idCuenta'];

						$existeCuenta=$objModel->cuentavalidate($idCuenta);
							if($existeCuenta !== 1){
								$idCuentaventa =	$objModel->insertCuenta($cuenta);
							}else{
								$idCuentaventa =	$idCuenta;
							}

						$result =	$objModel->insertVentaPagos($idventa, $idtipopago,$montopago,$idCuentaventa);

					}
				} catch (Exception $e) {
					//Exception Pago Detalle
				}
			} catch (Exception $e) {
				//Exception Producto
			}
		} catch (Exception $e) {
			//Exception Proveedor / idCompra
		}

		if($result == 1 ){
			$this->clearCartventas();
		}


		$data[] = array(
			'idventa'	=> $idventa,
			'result'	=> $result,
		);
	
		echo json_encode($data);
	}




}


?>