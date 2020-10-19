<?php 

class compraController extends Controller{
	
 	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){		
		$this->_view->setJs(array('index'));

		$this->idLocalesclass = $_GET["idLocal"];
		
		// $idLocales = $_GET["idLocal"];
		// $_SESSION["idLocal"] = $idLocales;
		// global $idLocales;
		//$objModel=$this->loadModel('compra');
		//$this->_view->productos=$objModel->getComboProductos();
		$this->_view->renderizar('index');
		
	}

 

	public function getTipopago(){
		$objModel=$this->loadModel('compra');
		$result = $objModel->getTipopago();
		echo '<option selected value="" disabled> SELECCIONE </option>';
		while ($reg = $result->fetch_object()){
			echo '<option value="'.$reg->IDTIPOPAGO.'" > '.$reg->DESCRIPCION. '  </option>';
		}
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
		$objModel=$this->loadModel('compra');
		$result=$objModel->autocomplete($search);		
		$data = array();
		while($reg=$result->fetch_object()){
			$data[] = array("value"=>$reg->IDPRODUCTO,"label"=>$reg->NOMBRE);
		}
		echo json_encode($data);
	}

	public function autoproveedor(){
		$search = $_GET['query'];
		$objModel=$this->loadModel('compra');
		$result=$objModel->autoproveedor($search);		
		$data = array();
		while($reg=$result->fetch_object()){
			$data[] = array("value"=>$reg->nIDPROVEEDOR,"label"=>$reg->sLABEL);
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
							<strong>'.$items["precio"].'</strong>
						</td>
						<td class="p-a-2">
							<strong>'.$items["cantidad"].'</strong>
						</td>
						<td class="p-a-2">
							<strong class="total">'.$items["cantidad"]*$items["precio"].'</strong>
						</td>
						<td class="p-a-2">
							 
						<button type="button" class="btn btn-xs btn-warning btn-outline btn-rounded btn-outline-colorless btn-delete" id="'.$items["name"].'">x</button>

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


	public function showpaymentCart(){

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

		$pagosTotal = 0;

		foreach ($_SESSION["cart"]["payments"] as $payments) {
			$pagosTotal 	= $pagosTotal + $payments['montopago'];
		}

		if($pagosTotal + $montopago <= $montoapagar){
			$paymentArray = array(
				"formaPago"		=>$formaPago
				,"cuenta"		=>$cuenta
				,"montopago"	=>$montopago
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

			$idCompra = $objModel->insertcompra($idProveedorcompra,$observaciones);

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
								$idProductocompra =	$objModel->insertProducto($nombre);
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

						$result =	$objModel->insertCompraPagos($idCompra, $idtipopago,$montopago,$cuenta);

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
		echo($result);
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