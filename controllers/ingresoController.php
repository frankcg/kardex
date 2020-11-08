<?php 

class ingresoController extends Controller{
	
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

		$_SESSION['cart']['products'] = array();
		$_SESSION['cart']['payments'] = array();

		$this->_view->setJs(array('index'));
		$this->_view->renderizar('index');
	}

	public function clearCart(){
		unset($_SESSION["cart"]["products"]);
		unset($_SESSION["cart"]["payments"]);
		$_SESSION['cart']['products'] = array();
		$_SESSION['cart']['payments'] = array();
	}


	public function addproductCart(){

		$idProducto 	= $_POST['idProducto'];
		$nombre			= strtoupper(trim($_POST['nombre']));		
		$cantidad		= $_POST['cantidad'];
		$precioCompra	= $_POST['precioCompra'];
		$aliasCompra	= $_POST['aliasCompra'];
		$cProduccion	= $_POST['cProduccion'];
		$ganancia		= $_POST['ganancia'];
		$descripcion	= strtoupper(trim($_POST['descripcion']));
		$codLocal 		= $_POST['codLocal'];


		
		$objModel=$this->loadModel('compra');
		$count = 0;
		$result =	$objModel->productvalidateNombre($nombre,$codLocal );
		
		while($reg=$result->fetch_object()){
			 $nIDPRODUCTO = $reg->nIDPRODUCTO;
			 $count++;
		}

		if($count>0){
			$idProducto =	$nIDPRODUCTO; 
		}else{
			$idProducto =	$idProducto;
		}

		$productsArray = array(
			"idProducto"=>$idProducto
			,"name"=>$nombre
			,"cantidad"=>$cantidad
			,"precio"=>$precioCompra
			,"alias"=>$aliasCompra
			,"descripcion"=>$descripcion
			,"cProduccion"=>$cProduccion
			,"ganancia"=>$ganancia
		);

			if(isset($_SESSION["cart"]["products"][$nombre])){
				echo("if entra");
				// echo('<pre>');
				// print_r($productsArray);
				// echo('</pre>');

				array_splice($_SESSION["cart"]["products"][$nombre],0);
				array_push($_SESSION["cart"]["products"][$nombre],$productsArray);

				// echo('<pre>');
				// print_r($_SESSION["cart"]["products"][$nombre]);
				// echo('</pre>');

			}else{
				echo("else entra");
				$_SESSION["cart"]["products"][$nombre] = array();
				array_push($_SESSION["cart"]["products"][$nombre],$productsArray);
			}	

		// $objModel=$this->loadModel('compra');
		// $result = $objModel->addCompra($nombre, $cantidad, $precioCompra, $aliasCompra, $descripcion);
		// if($result) echo 'ok'; else echo 'error';	
 
	}

	public function showproductCart(){

		$tableProducts = "";
 
		if(!empty($_SESSION["cart"]["products"])){
		foreach ($_SESSION["cart"]["products"] as $productos) {
			foreach($productos as $items){
				if($items["name"]!==""){
					$tableProducts .= '<tr> 
						<td class="p-a-2">
							<div class="font-weight-semibold">'.$items["name"].'</div>
							<div class="font-size-12 text-muted">'.$items["descripcion"].'</div>
						</td>
						<td class="p-a-2">
							<strong>'.$items["cantidad"].'</strong>
						</td>
						<td class="p-a-2">
						<strong>'.$items["precio"].'</strong>
						</td>
						<td class="p-a-2">
						<strong>'.$items["cProduccion"].'</strong>
						</td>
						<td class="p-a-2">
						<strong>'.$items["ganancia"].'</strong>
						</td>
						<td class="p-a-2">
							<strong class="total">'.$items["cantidad"]*$items["precio"].'</strong>
						</td>
						<td class="p-a-2">
							 
						<button type="button" class="btn btn-xs btn-warning btn-outline btn-rounded btn-outline-colorless btn-delete" id="'.$items["name"].'">x</button>

						</td>
					  </tr>'
					  ;
					echo('<pre>');
					print_r($items);
					echo('</pre>');
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


	public function showpaymentCart(){

		// echo('<pre>');
		// print_r($_SESSION);
		// echo('</pre>');

		$tablePayments = "";	
 
		if(!empty($_SESSION["cart"]["payments"])){
		foreach ($_SESSION["cart"]["payments"] as $key => $value) {
				if($value["formaPago"]!==""){

					if($value["formaPago"]=="1"){
						$forma = "EFECTIVO";
					}else{
						$forma = "DEPOSITO";
					};

					$tablePayments .= '<tr> 
 
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
		$proveedor		= $_POST['proveedor'];
		$idProveedor	= $_POST['idProveedor'];
		$observaciones	= $_POST['observaciones'];
		


		$objModel=$this->loadModel('ingreso');
		try {

			$existeProveedor =	$objModel->proveedorvalidate($idProveedor);
				if($existeProveedor !== 1){
					$idProveedorcompra =	$objModel->insertProveedor($proveedor);
				}else{
					$idProveedorcompra =	$idProveedor;
				}

			$idCompra = $objModel->insertcompra($codLocal,$idProveedorcompra,$observaciones);

			try {


				foreach ($_SESSION["cart"]["products"] as $productos) {
					foreach($productos as $items){
		
						$idProducto = $items['idProducto'];
						$nombre = $items['name'];
						$cantidad = $items['cantidad'];
						$precio = $items['precio'];
						$cProduccion = $items['cProduccion'];
						$ganancia = $items['ganancia'];
		
						
						// echo('<pre>');
						// print_r($_SESSION["cart"]["products"][$nombre]);
						// echo('</pre>');

						$existeProducto=$objModel->productvalidate($idProducto);


							if($existeProducto !== 1){
								$idProductocompra =	$objModel->insertProducto($nombre, $codLocal);
							}else{
								$idProductocompra =	$idProducto;
							}

						$idCompradetalle =	$objModel->insertCompraDetalle($idCompra,$idProductocompra,$cantidad,$precio); 
						$result =	$objModel->insertCartTaller($idCompradetalle,$cProduccion,$ganancia); 	


					}
				}

			try {
					foreach ($_SESSION["cart"]["payments"] as $payments) {
		
						$idtipopago = $payments['formaPago'];
						$cuenta 	= $payments['cuenta'];
						$montopago 	= $payments['montopago'];
						$idCuenta 	= $payments['idCuenta'];
						$idCuentacompra = 1;

						$existeCuenta= ($idCuenta=='' || !$idCuenta) ? 0 : $objModel->cuentavalidate($idCuenta);
							if(!$existeCuenta){								
								if($idtipopago=='02'){
									$idCuentacompra = $objModel->insertCuenta($cuenta);	
								}
							}else{
								if($idtipopago=='02'){
									$idCuentacompra =$idCuenta;	
								}
							}
						$result =	$objModel->insertCompraPagos($idCompra, $idtipopago,$montopago,$idCuentacompra);

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
			$this->clearCart();
		}


		$data[] = array(
			'idCompra'	=> $idCompra,
			'result'	=> $result,
		);
	
		echo json_encode($data);
	}



}
