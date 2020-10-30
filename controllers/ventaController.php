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

		$objModel=$this->loadModel('general');
		$nombreLocal = $objModel->getNombreLocal($idLocal);		
		$this->_view->nombreLocal=$nombreLocal;
		
		$this->_view->setJs(array('index'));
		$this->_view->renderizar('index');
	}

	public function getComboProductos($codLocal){
		$codLocal = $codLocal;
		$objModel=$this->loadModel('venta');
		$result = $objModel->getComboProductos($codLocal);
		echo '<option selected disabled> SELECCIONE </option>';
		while ($reg = $result->fetch_object()){
			echo '<option value="'.$reg->nIDPRODUCTO.'"  id2="'.$reg->sNOMBRE.'" id3="'.$reg->nCANTIDAD.'"> '.$reg->sLOCAL. ' - '.$reg->sNOMBRE. ' - '.$reg->nCANTIDAD. '</option>';
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
		$perdida		= $_POST['perdida'];

		$productsArray = array(
			"idProducto"=>$idProducto
			,"cartNombre"=>$cartNombre
			,"cantidad"=>$cantidad
			,"precio"=>$precioCompra
			,"perdida"=>$perdida
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

					if($items["perdida"]==0){
						$trClass = ''; 
					}else{
						$trClass = 'danger'; 
					}

					$tableProducts .= '<tr class='.$trClass.'> 
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


	public function paymentsstock(){
		
		
		echo('<pre>');
		print_r($_SESSION["cart"]["ventasproducts"]);
		echo('</pre>');

		$objModel=$this->loadModel('venta');

		foreach ($_SESSION["cart"]["ventasproducts"] as $productos) {
			foreach($productos as $items){
	
				$idProducto = $items['idProducto'];
				$cantidad = $items['cantidad'];
				$precio = $items['precio'];

				
				$cantidadrestante = $cantidad ; 
				$vendido = 0;
				$vendidoFinal = 0;

				$result=$objModel->getStockVenta($idProducto);
				$data = array();

				while($reg=$result->fetch_object()){

					$cantidadrestante = $cantidadrestante - $reg->nSTOCK;

					if($cantidadrestante>0){
						$vendido = $reg->nSTOCK;
					}else{
						$vendido = 	$cantidad - $vendidoFinal;				 
					}

					//Esta cantidad es igual a la cantidad inicial test

					$vendidoFinal += $vendido;

					$data[] = array(
						'nIDCOMPRADETALLE'	=> $reg->nIDCOMPRADETALLE,
						'nSTOCK'			=> $reg->nSTOCK,
						'nVENDIDO'			=> $vendido,
					);

					if($cantidadrestante > 0 ){
					}else{
						break;
					}
 
				}

				foreach ($data as $key => $value) {

					$cantidadVendida = $value['nVENDIDO'];
					$idCompraDetalle = $value['nIDCOMPRADETALLE'];
					
					// $result =	$objModel->insertVentaDetalle($idventa,$idCompraDetalle, $idProducto,$$cantidadVendida,$precio); 

				}

				echo(json_encode($data));

			}
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
		// echo('<pre>');
		// print_r($_SESSION["cart"]["ventaspayments"] );
		// echo('</pre>');
		// exit();
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
		$cliente		= $_POST['cliente'];
		$idCLiente		= $_POST['idCliente'];
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
						$cantidad = $items['cantidad'];
						$precio = $items['precio'];
		
						$cantidadrestante = $cantidad ; 
						$vendido = 0;
						$vendidoFinal = 0;
		
						$result=$objModel->getStockVenta($idProducto);
						$dataVenta = array();
		
						while($reg=$result->fetch_object()){
							$cantidadrestante = $cantidadrestante - $reg->nSTOCK;
							if($cantidadrestante>0){
								$vendido = $reg->nSTOCK;
							}else{
								$vendido = 	$cantidad - $vendidoFinal;				 
							}
							//Esta cantidad es igual a la cantidad inicial test
							$vendidoFinal += $vendido;
							$dataVenta[] = array(
								'nIDCOMPRADETALLE'	=> $reg->nIDCOMPRADETALLE,
								'nSTOCK'			=> $reg->nSTOCK,
								'nVENDIDO'			=> $vendido,
							);
							if($cantidadrestante > 0 ){
							}else{
								break;
							}
		 
						}
		
						foreach ($dataVenta as $key => $value) {
		
							$idCompraDetalle 	= $value['nIDCOMPRADETALLE'];
							$nStock 			= $value['nSTOCK'];
							$cantidadVendida 	= $value['nVENDIDO'];

							if($cantidadVendida == $nStock){
								$objModel->updateCompraStockzer0($idCompraDetalle);
							}

							$result =	$objModel->insertVentaDetalle($idventa,$idCompraDetalle,$idProducto,$cantidadVendida,$precio); 
		


						}
		
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
								if($idtipopago == '01'){
									$idCuentaventa =	"1";
								}else{
									$idCuentaventa =	$idCuenta;
								}
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


	public function creacionFactura(){

		$objModel=$this->loadModel('venta');
		$this->getLibrary('FPDF/fpdf');

		// $idFactura = '0000000022';
		$idFactura	= $_POST['id'];
		
		$simbolo = 'S/ ';

		$result=$objModel->getVentaFactura($idFactura);
		$resultProducts=$objModel->getVentaFactura($idFactura);
		$resultpagos=$objModel->getVentaPagos($idFactura);

		$pdf = new FPDF('P','mm',array(80,300));
		$pdf->AddPage();
		
		// CABECERA
		$pdf->SetFont('Helvetica','',12);
		$pdf->Cell(0,4,'Local 1 ',0,1,'C');
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(0,4,'RUC.: 000000000000',0,1,'C');
		$pdf->Cell(0,4,'Distrito',0,1,'C');
		$pdf->Cell(0,4,'Lima-Peru',0,1,'C');
		
		$pdf->Cell(0,4,'Telf: 0000 0000 0000',0,1,'C');
		// DATOS FACTURA 
		
		while($reg=$result->fetch_object()){
			$cliente = $reg->sDESCRIPCION;
			$idFactura = $reg->nIDVENTA;
			$fVenta = $reg->dFECHAVENTA;
		}

		$pdf->Ln(5);


		
		
		// COLUMNAS
		$pdf->SetFont('Helvetica', 'B', 7);
		$pdf->Cell(30, 10, 'Articulo', 0);
		$pdf->Cell(5, 10, 'Ud',0,0,'R');
		$pdf->Cell(10, 10, 'Precio',0,0,'R');
		$pdf->Cell(15, 10, 'Total',0,0,'R');
		$pdf->Ln(8);
		$pdf->Cell(60,0,'','T');
		$pdf->Ln(1);

		

		// PRODUCTOS
		$pdf->SetFont('Helvetica', '', 7);


		$total = 0;
		$data = array();

		while($reg=$resultProducts->fetch_object()){

			$sNOMBRE = $reg->sNOMBRE;
			$CANTIDAD = $reg->CANTIDAD;
			$fPRECIO = $reg->fPRECIO;
			$total	+= $CANTIDAD*$fPRECIO;

			$pdf->MultiCell(30,4,$sNOMBRE,0,'L'); 
			$pdf->Cell(35, -5, $CANTIDAD,0,0,'R');
			$pdf->Cell(10, -5, number_format(round($fPRECIO,2), 2, ',', ' '),0,0,'R');
			$pdf->Cell(15, -5, number_format(round($CANTIDAD*$fPRECIO,2), 2, ',', ' '),0,0,'R');
			$pdf->Ln(1);

		}


		// SUMATORIO DE LOS PRODUCTOS Y EL IVA
		$pdf->Ln(1);
		$pdf->Cell(60,0,'','T');
		$pdf->Ln(2);    
		$pdf->Cell(25, 10, 'TOTAL:', 0);    
		$pdf->Cell(20, 10, '', 0);
		$pdf->Cell(15, 10, $simbolo.$total,0,0,'R');
		$pdf->Ln(3);   
		
		$pdf->Ln(5);
		$pdf->Cell(25,4,'Cliente: '.$cliente ,0,1,'|');
		$pdf->Cell(25,4,'Proforma: F2020-'.$idFactura ,0,1,'');
		$pdf->Cell(25,4,'Fecha: '.$fVenta,0,1,'');
		$pdf->Cell(25,4,'Metodos de pago: ',0,1,'');
		$pdf->Ln(0);

		while($reg=$resultpagos->fetch_object()){
			$Metodo = $reg->sdescripcion;
			$Monto = $reg->fmonto;
			$pdf->Cell(25,4,$Metodo.'-'.$simbolo.$Monto,0,1,'');
		}

 
		// PIE DE PAGINA
		$pdf->Ln(10);
		$pdf->Cell(60,0,'***ESTE COMPROBANTE***',0,1,'C');
		$pdf->Ln(3);
		$pdf->Cell(60,0,'HASTA QUE EL RETIRO DE LA TIENDA',0,1,'C');
		
		$pdf->Output('ticket.pdf','i');
 
 	}


	 public function creacionFacturaurl($idFactura){

		$objModel=$this->loadModel('venta');
		$this->getLibrary('FPDF/fpdf');

		// $idFactura = '0000000022';
		$idFactura	= $idFactura;
		
		$simbolo = 'S/ ';

		$result=$objModel->getVentaFactura($idFactura);
		$resultProducts=$objModel->getVentaFactura($idFactura);
		$resultpagos=$objModel->getVentaPagos($idFactura);

		$pdf = new FPDF('P','mm',array(80,300));
		$pdf->AddPage();
		
		while($reg=$result->fetch_object()){
			$tienda = $reg->TIENDA;
			$cliente = $reg->CLIENTE;
			$idFactura = $reg->nIDVENTA;
			$fVenta = $reg->dFECHAVENTA;
			$sRUC = $reg->sRUC;
			$sDIRECCION = $reg->sDIRECCION;
			$nTELEFONO = $reg->nTELEFONO;
		}

		// CABECERA
		$pdf->SetFont('Helvetica','',12);
		$pdf->Cell(0,4,$tienda,0,1,'C');
		$pdf->SetFont('Helvetica','',7);
		$pdf->Cell(0,4,'RUC.:'.$sRUC,0,1,'C');
		$pdf->Cell(0,4,$sDIRECCION,0,1,'C');
		$pdf->Cell(0,4,'Lima-Peru',0,1,'C');
		
		$pdf->Cell(0,4,'Telf:'.$nTELEFONO ,0,1,'C');
		// DATOS FACTURA 
		$pdf->SetFont('Helvetica','',7);
		$pdf->Ln(5);
		$pdf->Cell(25,4,'Cliente: '.$cliente ,0,1,'|');
		$pdf->Cell(25,4,'Proforma: F2020-'.$idFactura ,0,1,'');
		$pdf->Cell(25,4,'Fecha: '.$fVenta,0,1,'');
		 
	
		// COLUMNAS
		$pdf->SetFont('Helvetica', 'B', 7);
		$pdf->Cell(30, 10, 'Articulo', 0);
		$pdf->Cell(5, 10, 'Ud',0,0,'R');
		$pdf->Cell(10, 10, 'Precio',0,0,'R');
		$pdf->Cell(15, 10, 'Total',0,0,'R');
		$pdf->Ln(8);
		$pdf->Cell(60,0,'','T');
		$pdf->Ln(1);

		

		// PRODUCTOS
		$pdf->SetFont('Helvetica', '', 7);


		$total = 0;
		$data = array();

		while($reg=$resultProducts->fetch_object()){

			$sNOMBRE = $reg->sNOMBRE;
			$CANTIDAD = $reg->CANTIDAD;
			$fPRECIO = $reg->fPRECIO;
			$total	+= $CANTIDAD*$fPRECIO;

			$pdf->MultiCell(30,4,$sNOMBRE,0,'L'); 
			$pdf->Cell(35, -5, $CANTIDAD,0,0,'R');
			$pdf->Cell(10, -5, number_format(round($fPRECIO,2), 2, ',', ' '),0,0,'R');
			$pdf->Cell(15, -5, number_format(round($CANTIDAD*$fPRECIO,2), 2, ',', ' '),0,0,'R');
			$pdf->Ln(1);

		}


		// SUMATORIO DE LOS PRODUCTOS Y EL IVA
		$pdf->Ln(1);
		$pdf->Cell(60,0,'','T');
		$pdf->Ln(2);    
		$pdf->Cell(25, 10, 'TOTAL:', 0);    
		$pdf->Cell(20, 10, '', 0);
		$pdf->Cell(15, 10, $simbolo.$total,0,0,'R');
		$pdf->Ln(3);    
		// PIE DE PAGINA

		// PAGOS
		$pdf->Ln(5);
		$pdf->Cell(60,0,'','T');
		$pdf->Ln(1);
		$pdf->Cell(25,3,'Metodos de pago: ',0,1,'');
		$pdf->Ln(1);

		while($reg=$resultpagos->fetch_object()){
			$Metodo = $reg->sdescripcion;
			$Monto = $reg->fmonto;
			$pdf->Cell(25, 3, $Metodo.':', 0);    
			$pdf->Cell(20, 3, '', 0);
			$pdf->Cell(15, 3, $simbolo.$Monto,0,0,'R');
			$pdf->Ln(3);    

			
		}



		$pdf->Ln(10);
		$pdf->Cell(60,0,'***DISCLAIMER***',0,1,'C');
		$pdf->Ln(1);
		$pdf->Cell(60,3,'Este documento tiene ',0,1,'C');
		$pdf->Cell(60,3,'caracter meramente informativo ',0,1,'C');
		$pdf->Cell(60,3,'Su contenido carece de valor legal.',0,1,'C');

		$pdf->Output('ticket.pdf','i');
 
	 }
	 

	 public function getVentas(){
	
		$idLocal 	= '0002';
		// $codLocal	= $_POST['codLocal'];

		$objModel=$this->loadModel('venta');
		$result=$objModel->getVentas($idLocal);
		$cont=0;

		$btn='btn-info';
		$icon='fa-file';
		$title='Habilitar';
		$class='viewPdf';

		while($reg=$result->fetch_object()){
			$cont++;

			$boton='<button id="'.$reg->nIDVENTA.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';

			$data ['data'] [] = array (
				'nIDVENTA' 		=> $reg->nIDVENTA,
				'nIDLOCAL' 		=> $reg->nIDLOCAL,
				'nLOCAL' 		=> $reg->nLOCAL,
				'dFECHAVENTA' 	=> $reg->dFECHAVENTA,
				'total' 		=> $reg->total,
				'OPCIONES' 		=> $boton,
				);
		}
		echo json_encode ( $data );
	}






}


?>