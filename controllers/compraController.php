<?php 

class compraController extends Controller{
	
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


	public function getTipopago(){
		$objModel=$this->loadModel('compra');
		$result = $objModel->getTipopago();
		echo '<option selected value="" disabled> SELECCIONE </option>';
		while ($reg = $result->fetch_object()){
			echo '<option value="'.$reg->nIDTIPOPAGO.'" > '.$reg->sDESCRIPCION. '  </option>';
		}
	}

	public function promCompraProductos(){
		$objModel=$this->loadModel('compra');
		date_default_timezone_set('America/Lima');
		$tableProducts = "";
		$result = $objModel->promCompraProductos($_POST['id']);
		while ($reg = $result->fetch_object()){
			$date = date_create($reg->FECHA);
			$date = date_format($date, 'd-m-Y');
			if($reg->DIFERENCIA == 0 ){
				$diffDias = "Hoy";
			}else{
				if($reg->DIFERENCIA == 1){
					$diffDias = "Hace: ".$reg->DIFERENCIA." Dia";
				}else{
					$diffDias = "Hace: ".$reg->DIFERENCIA." Dias";
				}
			}
			$tableProducts .= 	'
									<div class="widget-notifications-description"><strong>S/</strong><a class="etPrecio">'.$reg->fPRECIO.'</a>	</div>
									<div class="widget-notifications-date">'.$date.' - '.$diffDias.' </div>
								';
			
		}
		echo($tableProducts);
	}


	public function getCompras(){

		$objModel=$this->loadModel('compra');
		$result=$objModel->getCompras();
		$cont=0;
		$data = array();

		while($reg=$result->fetch_object()){

			$cont++;

			$btn='btn-danger';
			$icon='fa-close';
			$title='Eliminar';
			$class='eliminarCompra';

			$hide = ($reg->VENTA>=1) ? 'hide' : '';		

			$boton='<button idCompra="'.$reg->IDCOMPRA.'" class="'.$class.' btn '.$btn.' btn-xs '.$hide.'" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';

			$data ['data'] [] = array(
				'CONT'=>$cont,
				'IDPRODUCTO' => $reg->IDPRODUCTO,
				'IDCOMPRA' => $reg->IDCOMPRA,
				'PRODUCTO'=>utf8_encode($reg->PRODUCTO),
				'FECHA_COMPRA' => $reg->FECHA_COMPRA,
				'PRECIO_TOTAL' => $reg->PRECIO_TOTAL,
				'PRECIO_UNIDAD' => $reg->PRECIO_UNIDAD,
				'CANTIDAD' => $reg->CANTIDAD,
				'ALIAS' => $reg->ALIAS,
				'VENTA' => $reg->VENTA,
				'OBSERVACION' => $reg->OBSERVACION,				
				'OPCIONES'=>utf8_encode($boton),
			);
		}
		echo json_encode ( $data );
	}

	public function autocomplete(){
		$search = $_GET['query'];
		$codLocal = $_GET['codLocal'];
		$objModel=$this->loadModel('compra');
		$result=$objModel->autocomplete($search,$codLocal);		
		$data = array();
		while($reg=$result->fetch_object()){
			$data[] = array("value"=>$reg->nIDPRODUCTO,"label"=>$reg->sNOMBRE);
		}
		echo json_encode($data);
	}

	public function autoproveedor(){
		$search = $_GET['query'];
		$objModel=$this->loadModel('compra');
		$result=$objModel->autoproveedor($search);		
		$data = array();
		while($reg=$result->fetch_object()){
			$data[] = array("value"=>$reg->nIDPROVEEDOR,"label"=>$reg->sDESCRIPCION);
		}
		echo json_encode($data);
	}
	
	public function autocuenta(){
		$search = $_GET['query'];
		$objModel=$this->loadModel('compra');
		$result=$objModel->autocuenta($search);		
		$data = array();
		while($reg=$result->fetch_object()){
			$data[] = array("value"=>$reg->nIDCUENTA,"label"=>$reg->sDESCRIPCION);
		}
		echo json_encode($data);
	}


	public function addCompra(){

		$nombre= strtoupper(trim($_POST['nombre']));		
		$cantidad= $_POST['cantidad'];
		$precioCompra= $_POST['precioCompra'];
		$aliasCompra= $_POST['aliasCompra'];
		$descripcion= strtoupper(trim($_POST['descripcion']));

		

		$objModel=$this->loadModel('compra');
		$result = $objModel->addCompra($nombre, $cantidad, $precioCompra, $aliasCompra, $descripcion);
		if($result) echo 'ok'; else echo 'error';	
	}

	public function clearCart(){
		unset($_SESSION["cart"]["products"]);
		unset($_SESSION["cart"]["payments"]);
		$_SESSION['cart']['products'] = array();
		$_SESSION['cart']['payments'] = array();
	}

	public function clearproductCart(){
		$id= $_POST['id'];

		print_r($_POST);
		echo($id);
		echo("entro al post");

		unset($_SESSION["cart"]["products"][$id]);

		if(!isset($_SESSION["cart"]["products"][$id])){
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

		unset($_SESSION["cart"]["payments"][$id]);

		if(!isset($_SESSION["cart"]["payments"][$id])){
			return 1;
		}else {
			return 0;
		}

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

	public function addpaymentCart(){

		$formaPago		= $_POST['formaPago']; 	
		$cuenta			= $_POST['cuenta']; 		
		$montopago		= $_POST['montopago']; 		
		$montoapagar	= $_POST['montoapagar']; 	
		$idCuenta		= $_POST['idCuenta']; 

		$pagosTotal = 0;

		foreach ($_SESSION["cart"]["payments"] as $payments) {
			$pagosTotal 	= $pagosTotal + $payments['montopago'];
		}

		if($pagosTotal + $montopago <= $montoapagar){
			$paymentArray = array(
				"formaPago"		=>$formaPago
				,"cuenta"		=>$cuenta
				,"montopago"	=>$montopago
				,"idCuenta"		=>$idCuenta
			);
			array_push($_SESSION["cart"]["payments"],$paymentArray);
			echo("1");
		}else{
			echo("0");
		}

		// echo('<pre>');
		// print_r($_SESSION["cart"]["payments"]);
		// echo('</pre>');

		// $objModel=$this->loadModel('compra');
		// $result = $objModel->addCompra($nombre, $cantidad, $precioCompra, $aliasCompra, $descripcion);
		// if($result) echo 'ok'; else echo 'error';	
 
	}


	public function finishpaymentCart(){

		$codLocal		= $_POST['codLocal'];
		$proveedor		= $_POST['proveedor'];
		$idProveedor	= $_POST['idProveedor'];
		$observaciones	= $_POST['observaciones'];


		$objModel=$this->loadModel('compra');
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
		
						// echo('<pre>');
						// print_r($_SESSION["cart"]["products"][$nombre]);
						// echo('</pre>');

						$existeProducto=$objModel->productvalidate($idProducto);


							if($existeProducto !== 1){
								$idProductocompra =	$objModel->insertProducto($nombre, $codLocal);
							}else{
								$idProductocompra =	$idProducto;
							}

						$result =	$objModel->insertCompraDetalle($idCompra,$idProductocompra,$cantidad,$precio); 	
	
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



	public function eliminarCompra(){
		$idCompra = $_POST['idCompra'];
		$estado = $_POST['estado'];
		$objModel=$this->loadModel('compra');
		$result=$objModel->eliminarCompra($idCompra, $estado);
		if($result) echo 'ok'; else echo 'error';
	}

	public function updateCompra(){
		
		$idCompra= $_POST['idCompra'];
		$nombre= strtoupper(trim($_POST['nombre']));		
		$cantidad= $_POST['cantidad'];
		$precioCompra= $_POST['precioCompra'];
		$aliasCompra= $_POST['aliasCompra'];
		$descripcion= strtoupper(trim($_POST['descripcion']));

		$objModel=$this->loadModel('compra');
		$result = $objModel->updateCompra($idCompra, $nombre, $cantidad, $precioCompra, $aliasCompra, $descripcion);
		if($result) echo 'ok'; else echo 'error';	
	}

	

}

?>