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

		$_SESSION['cart']['products'] = array();
		$_SESSION['cart']['payments'] = array();
		
		$this->_view->setJs(array('index'));
		$this->_view->renderizar('index');
	}

	public function getComboProductos($codLocal){
		$codLocal = $codLocal;
		$objModel=$this->loadModel('venta');
		$result = $objModel->getComboProductos($codLocal);
		echo '<option selected disabled> SELECCIONE </option>';
		while ($reg = $result->fetch_object()){
			echo '<option value="'.$reg->nIDPRODUCTO.'"  id2="'.$reg->sNOMBRE.'" id3="'.$reg->nCANTIDAD.'" title="'.$reg->nCANTIDAD.'" id4="'.$reg->nLOCAL.'"> '.$reg->sLOCAL. ' - '.$reg->sNOMBRE.' - '.' </a></option>';
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
		$search 	= $_GET['query'];
		$interno 	= $_GET['interno'];
		$idLocal 	= $_GET['idLocal'];
		$objModel=$this->loadModel('venta');
		$result=$objModel->autocliente($search,$interno,$idLocal);		
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
		$idLocal		= $_POST['idLocal'];

		$objModel=$this->loadModel('general');
		$nombreLocal = $objModel->getNombreLocal($idLocal);		
		

		$productsArray = array(
			"idProducto"=>$idProducto
			,"cartNombre"=>$cartNombre
			,"cantidad"=>$cantidad
			,"precio"=>$precioCompra
			,"perdida"=>$perdida
			,"idLocal"=>$idLocal
			,"nombreLocal"=>$nombreLocal
		);

		// $this->clearCartventas();

		if(isset($_SESSION["cart"]["ventasproducts"][$idProducto])){
			array_splice($_SESSION["cart"]["ventasproducts"][$idProducto],0);
			array_push($_SESSION["cart"]["ventasproducts"][$idProducto],$productsArray);
		}else{
			$_SESSION["cart"]["ventasproducts"][$idProducto] = array();
			array_push($_SESSION["cart"]["ventasproducts"][$idProducto],$productsArray);
		}	
	}

	public function showproductCart(){

		$tableProducts = "";

		// echo('<pre>');
		// print_r($_SESSION["cart"]["ventasproducts"]);
		// echo('</pre>');

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
								<div class="font-size-12 text-muted"><strong>LOCAL : </strong>'.$items["nombreLocal"].'</div>
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
		unset($_SESSION["cart"]["ventasproducts"][$id]);
		if(!isset($_SESSION["cart"]["ventasproducts"][$id])){
			return 1;
		}else {
			return 0;
		}
	}	

	public function clearpaymentCart(){
		$id= $_POST['id'];
		unset($_SESSION["cart"]["ventaspayments"][$id]);
		if(!isset($_SESSION["cart"]["ventaspayments"][$id])){
			return 1;
		}else {
			return 0;
		}
	}

	public function paymentsstock(){

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
		$interno		= $_POST['interno'];

		$objModel		=$this->loadModel('venta');
		$objModelCompra	=$this->loadModel('compra');

		$idVentaCompartida = ''; 

		try {

			$existeProveedor =	$objModel->clientevalidate($idCLiente);
			if($existeProveedor !== 1){
				$idClientecompra =	$objModel->insertCliente($cliente);
			}else{
				$idClientecompra =	$idCLiente;
			}
	
			$idventa = $objModel->insertVenta($codLocal,$idClientecompra,$observaciones);

			if ($interno == 'on') {
				$idCompra = $objModelCompra->insertcompra($idCLiente,$codLocal,$observaciones);
			}

			$countVentaInsert = 0;
			try {
				foreach ($_SESSION["cart"]["ventasproducts"] as $productos) {
					foreach($productos as $items){
			
						$idProducto = $items['idProducto'];
						$cantidad = $items['cantidad'];
						$precio = $items['precio'];
						$nombre = $items['cartNombre'];


						$idLocalProducto = $items['idLocal'];

						$cantidadrestante = $cantidad ; 
						$vendido = 0;
						$vendidoFinal = 0;
		
						if ($interno == 'on') {
							$result =	$objModelCompra->productvalidateNombre($nombre,$idCLiente );
							$count=0;
	
							while($reg=$result->fetch_object()){
								 $nIDPRODUCTO = $reg->nIDPRODUCTO;
								 $count++;
							}
								if($count>0){
									$idProductocompra =	$nIDPRODUCTO; 
								}else{
									$idProductocompra =	$objModelCompra->insertProducto($nombre, $idCLiente);
								}
						}

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
							

							if($codLocal != $idLocalProducto ){
								if($countVentaInsert == 0){
									$idVentaCompartida = $objModel->insertVentaCompartida($idLocalProducto,$idClientecompra,$observaciones,$idventa);
									$result =	$objModel->insertVentaDetalleCompartida($idVentaCompartida,$idCompraDetalle,$idProducto,$cantidadVendida,$precio );
									$countVentaInsert++;
								}else{
									$result =	$objModel->insertVentaDetalleCompartida($idVentaCompartida,$idCompraDetalle,$idProducto,$cantidadVendida,$precio );
								}
							}
						}
						if ($interno == 'on') {
							$result =	$objModelCompra->insertCompraDetalle($idCompra,$idProductocompra,$cantidad,$precio); 
						}
		
					}
				}

				try {
					foreach ($_SESSION["cart"]["ventaspayments"] as $payments) {
		
						$idtipopago = $payments['formaPago'];
						$cuenta 	= $payments['cuenta'];
						$montopago 	= $payments['montopago'];
						$idCuenta 	= $payments['idCuenta'];
						$idCuentaventa = 1;
						$existeCuenta= ($idCuenta=='' || !$idCuenta) ? 0 : $objModel->cuentavalidate($idCuenta);
						
							if(!$existeCuenta){
								if($idtipopago=='02'){
									$idCuentaventa = $objModel->insertCuenta($cuenta);	
								}
							}else{
								if($idtipopago=='02'){
									$idCuentaventa =$idCuenta;	
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
		// $resultpagos=$objModel->getVentaPagos($idFactura);

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
		// $pdf->Cell(25,4,'Metodos de pago: ',0,1,'');
		// $pdf->Ln(0);

		// while($reg=$resultpagos->fetch_object()){
		// 	$Metodo = $reg->sdescripcion;
		// 	$Monto = $reg->fmonto;
		// 	$pdf->Cell(25,4,$Metodo.'-'.$simbolo.$Monto,0,1,'');
		// }

		// PIE DE PAGINA
		$pdf->Ln(10);
		$pdf->Cell(60,0,'***ESTE COMPROBANTE***',0,1,'C');
		$pdf->Ln(3);
		$pdf->Cell(60,0,'HASTA QUE EL RETIRO DE LA TIENDA',0,1,'C');
		
		$pdf->Output('ticket-'.$idFactura.'.pdf','i');
 
 	}


	 public function creacionFacturaurl($idFactura){
		date_default_timezone_set('America/Lima');
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
		// $pdf->Cell(0,4,$tienda,0,1,'C');
		$pdf->Cell(0,4,'PROFORMA DE VENTA',0,1,'C');
		$pdf->SetFont('Helvetica','',7);
		$pdf->Cell(0,4,'RUC.:'.$sRUC,0,1,'C');
		$pdf->Cell(0,4,$sDIRECCION,0,1,'C');
		$pdf->Cell(0,4,'Lima-Peru',0,1,'C');
		
		// $pdf->Cell(0,4,'Telf:'.$nTELEFONO ,0,1,'C');
		// DATOS FACTURA 
		$pdf->SetFont('Helvetica','',7);
		$pdf->Ln(5);

		$date = date_create($fVenta);
		$newDate = date_format($date,"d/m/Y H:i:s");

		$pdf->Cell(25,4,'Cliente: '.$cliente ,0,1,'|');
		$pdf->Cell(25,4,'Proforma: '.$idFactura ,0,1,'');
		$pdf->Cell(25,4,'Fecha: '.$newDate,0,1,'');
		 
	
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

			$pdf->MultiCell(30,5,$sNOMBRE,0,'L'); 
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
		// $pdf->Cell(25,3,'Resumen: ',0,1,'');
		$pdf->Ln(1);
		$totalpagos = 0;
		while($reg=$resultpagos->fetch_object()){
			$Metodo = $reg->sdescripcion;
			$Monto = $reg->fmonto;
			$totalpagos = $totalpagos + $Monto;

			// $pdf->Cell(25, 3, $Metodo.':', 0);    
			// $pdf->Cell(20, 3, '', 0);
			// $pdf->Cell(15, 3, $simbolo.$Monto,0,0,'R');
			// $pdf->Ln(3);    
		}

		$pdf->Cell(25, 3, 'Pago Total:', 0);    
		$pdf->Cell(20, 3, '', 0);
		$pdf->Cell(15, 3, $simbolo.$totalpagos,0,0,'R');
		$pdf->Ln(3);
		$pdf->Cell(25, 3, 'Deuda:', 0);    
		$pdf->Cell(20, 3, '', 0);
		$pdf->Cell(15, 3, $simbolo.($totalpagos-$total),0,0,'R');



		$pdf->Ln(10);
		$pdf->Cell(60,0,'***DISCLAIMER***',0,1,'C');
		$pdf->Ln(1);
		$pdf->Cell(60,3,'Este documento tiene ',0,1,'C');
		$pdf->Cell(60,3,'caracter meramente informativo ',0,1,'C');
		$pdf->Cell(60,3,'Su contenido carece de valor legal.',0,1,'C');

		$pdf->Output('ticket-'.$idFactura.'.pdf','i');
 
	 }
	 

	 public function getVentas($codLocal){		

		$objModel=$this->loadModel('venta');
		$result=$objModel->getVentas($codLocal);
		$cont=0;

		$btn='btn-info';
		$icon='fa-file';
		$title='Habilitar';
		$class='viewPdf';
		$data = array();
		while($reg=$result->fetch_object()){
			$cont++;

			$boton='<button id="'.$reg->nIDVENTA.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';

			$data ['data'] [] = array (
				'dFECHAVENTA' 		=> $reg->dFECHAVENTA,
				'nIDVENTA' 		=> $reg->nIDVENTA,
				'sCLIENTE' 		=> $reg->sCLIENTE,
				'sVENDEDOR' 	=> $reg->sIDUSUARIOCREACION,
				'sCostoTotalVenta' 		=> $reg->sCostoTotalVenta,
				'OPCIONES' 		=> $boton,
				);
		}
		echo json_encode ( $data );
	}



	
public function finishpaymentCarttest(){

	$codLocal		= 002;
	$cliente		= 001;
	$idCLiente		= 003;
	$observaciones	= 'finishpaymentCarttest';
	$interno		= 'on';

	$objModel=$this->loadModel('venta');
	$objModelCompra	=$this->loadModel('compra');
	$idVentaCompartida = ''; 

	try {

		$existeProveedor =	$objModel->clientevalidate($idCLiente);
		if($existeProveedor !== 1){
			$idClientecompra =	$objModel->insertCliente($cliente);
		}else{
			$idClientecompra =	$idCLiente;
		}

		$idventa = 00000000;
		$countVentaInsert = 0;

		if ($interno == 'on') {

			//$idCompra = $objModelCompra->insertcompra($idCLiente,$codLocal,$observaciones);

			$idCompra = 22222222;
		}



		echo('<pre>');
		print_r($_SESSION["cart"]["ventasproducts"] );
		echo('</pre>');


		try {
			foreach ($_SESSION["cart"]["ventasproducts"] as $productos) {
				foreach($productos as $items){
		
					$idProducto = $items['idProducto'];
					$cantidad = $items['cantidad'];
					$precio = $items['precio'];
					$nombre = $items['cartNombre'];


					$idLocalProducto = $items['idLocal'];

					$cantidadrestante = $cantidad ; 
					$vendido = 0;
					$vendidoFinal = 0;
	
					if ($interno == 'on') {
						$result =	$objModelCompra->productvalidateNombre($nombre,$idCLiente );
						$count=0;

						while($reg=$result->fetch_object()){
							 $nIDPRODUCTO = $reg->nIDPRODUCTO;
							 $count++;
						}
							if($count>0){
								$idProductocompra =	$nIDPRODUCTO; 
								echo('Existe:'.$idProductocompra);
							}else{
								$idProductocompra =	$objModelCompra->insertProducto($nombre, $idCLiente);
								echo('Sin Existencia:'.$idProductocompra);
							}
						}


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
	
					echo('<pre>');
					print_r($dataVenta);
					echo('</pre> dataventa <br>');


					foreach ($dataVenta as $key => $value) {
						echo('foreachr');
						$idCompraDetalle 	= $value['nIDCOMPRADETALLE'];
						$nStock 			= $value['nSTOCK'];
						$cantidadVendida 	= $value['nVENDIDO'];


						if($cantidadVendida == $nStock){
							// $objModel->updateCompraStockzer0($idCompraDetalle);
							echo($idCompraDetalle);
						}

						// $result =	$objModel->insertVentaDetalle($idventa,$idCompraDetalle,$idProducto,$cantidadVendida,$precio);
						 	echo($idventa.$idCompraDetalle.$idProducto.$cantidadVendida.$precio);


						if($codLocal != $idLocalProducto ){
							if($countVentaInsert == 0){

								echo('venta compartida');

								// $idVentaCompartida = $objModel->insertVentaCompartida($idLocalProducto,$idClientecompra,$observaciones,$idventa);
								// $result =	$objModel->insertVentaDetalleCompartida($idVentaCompartida,$idCompraDetalle,$idProducto,$cantidadVendida,$precio );
								$countVentaInsert++;
							}else{

								echo('insertVentaDetalleCompartida');

								// $result =	$objModel->insertVentaDetalleCompartida($idVentaCompartida,$idCompraDetalle,$idProducto,$cantidadVendida,$precio );
							}
						}

						echo('<br> interno <br>');			

					}

					if ($interno == 'on') {
						//$result =	$objModel->insertCompraDetalle($idCompra,$idProductocompra,$cantidad,$precio); 
						echo($idCompra.'--'.$idProductocompra.'--'.$cantidad.'--'.$precio.' --<br>');
					}
				}
			}

			try {
				foreach ($_SESSION["cart"]["ventaspayments"] as $payments) {
	
					$idtipopago = $payments['formaPago'];
					$cuenta 	= $payments['cuenta'];
					$montopago 	= $payments['montopago'];
					$idCuenta 	= $payments['idCuenta'];
					$idCuentaventa = 1;
					$existeCuenta= ($idCuenta=='' || !$idCuenta) ? 0 : $objModel->cuentavalidate($idCuenta);
					
						if(!$existeCuenta){
							if($idtipopago=='02'){
								$idCuentaventa = $objModel->insertCuenta($cuenta);	
							}
						}else{
							if($idtipopago=='02'){
								$idCuentaventa =$idCuenta;	
							}
						}

					//$result =	$objModel->insertVentaPagos($idventa, $idtipopago,$montopago,$idCuentaventa);

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
	//	$this->clearCartventas();
	}

	$data[] = array(
		'idventa'	=> $idventa,
		'result'	=> $result,
	);

	//echo json_encode($data);
}


}

