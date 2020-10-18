<?php 

class productoController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){		
		$this->_view->setJs(array('index'));
		$objModel=$this->loadModel('producto');
		$this->_view->productos=$objModel->getComboProductos();
		$this->_view->renderizar('index');
	}

	public function getProductos(){
		$objModel=$this->loadModel('producto');
		$result=$objModel->getProductos();
		$cont=0;
		$data = array();
		while($reg=$result->fetch_object()){
			$cont++;

			if($reg->ESTADO == 'INACTIVO'){
				$btn='btn-success';
				$icon='fa-check';
				$title='Habilitar';
				$class='activarProducto';
			}else{
				$btn='btn-danger';
				$icon='fa-close';
				$title='Inhabilitar';
				$class='desactivarProducto';
			}			

			$boton='<button idProducto="'.$reg->IDPRODUCTO.'"  idProductoDetalle="'.$reg->IDPRODUCTODETALLE.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';

			$data ['data'] [] = array ('CONT'=>$cont,
				'IDPRODUCTO' => $reg->IDPRODUCTO,
				'IDPRODUCTODETALLE' => $reg->IDPRODUCTODETALLE,
				'NOMBRE' => $reg->NOMBRE,
				'MARCA'=>utf8_encode($reg->MARCA),
				'MODELO'=>utf8_encode($reg->MODELO),
				'DESCRIPCION'=>utf8_encode($reg->DESCRIPCION),
				'ESTADO'=>$reg->ESTADO,
				'FECHA'=>$reg->FECHA,
				'OPCIONES'=>utf8_encode($boton),
				);
		}
		echo json_encode ( $data );
	}

	public function addProducto(){

		$nombre= strtoupper(trim($_POST['nombre']));
		$modelo= strtoupper(trim($_POST['modelo']));
		$marca= strtoupper(trim($_POST['marca']));
		$descripcion= strtoupper(trim($_POST['descripcion']));

		$objModel=$this->loadModel('producto');
		$result = $objModel->addProducto($nombre, $modelo, $marca, $descripcion);
		if($result) echo 'ok'; else echo 'error';	
	}

	public function cambiarEstadoProducto(){
		$idProducto = $_POST['idProducto'];
		$idProductoDetalle = $_POST['idProductoDetalle'];
		$estado = $_POST['estado'];
		$objModel=$this->loadModel('producto');
		$result=$objModel->cambiarEstadoProducto($idProducto, $idProductoDetalle, $estado);
		if($result) echo 'ok'; else echo 'error';
	}

	public function updateProducto(){

		$idProducto = strtoupper(trim($_POST['idProducto']));
		$idProductoDetalle= strtoupper(trim($_POST['idProductoDetalle']));
		$nombre= strtoupper(trim($_POST['nombre']));
		$modelo= strtoupper(trim($_POST['modelo']));
		$marca= strtoupper(trim($_POST['marca']));
		$descripcion= strtoupper(trim($_POST['descripcion']));

		$objModel=$this->loadModel('producto');
		$result = $objModel->updateProducto($idProducto, $idProductoDetalle, $nombre, $modelo, $marca, $descripcion);
		if($result) echo 1; else echo 0;		
	}

	public function getMarcas(){
		$idProducto = $_POST['idProducto'];
		$objModel=$this->loadModel('producto');
		$result = $objModel->getMarcas($idProducto);
		echo '<option selected disabled> SELECCIONE </option>';
		while ($reg = $result->fetch_object()){
			echo '<option value="'.$reg->IDPRODUCTODETALLE.'" > '.$reg->MARCA." / ".$reg->MODELO. ' </option>';
		}
	}
	/* ********************************************************************************* */

	public function getStocks(){
		$objModel=$this->loadModel('producto');
		$result=$objModel->getStocks();
		$cont=0;
		$data = array();
		while($reg=$result->fetch_object()){
			$cont++;

			$btn='btn-danger';
			$icon='fa-close';
			$title='Eliminar';
			$class='deleteStock';

			$boton='<button id="'.$reg->IDSTOCK.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';

			$data ['data'] [] = array ('CONT'=>$cont,
				'IDSTOCK' => $reg->IDSTOCK,
				'IDPRODUCTO' => $reg->IDPRODUCTO,
				'IDPRODUCTODETALLE' => $reg->IDPRODUCTODETALLE,
				'MARCA' => $reg->MARCA,
				'MODELO' => $reg->MODELO,
				'CANTIDAD' => $reg->CANTIDAD,
				'PRECIO_VENTA'=>$reg->PRECIO_VENTA,
				'INVERSION'=>$reg->INVERSION,
				'GANANCIA'=>$reg->GANANCIA,
				'PRODUCTO'=>utf8_encode($reg->PRODUCTO),
				'OBSERVACION'=>utf8_encode($reg->OBSERVACION),
				'ESTADO'=>$reg->ESTADO,
				'FECHA'=>$reg->FECHA,
				'OPCIONES'=>utf8_encode($boton),
				);
		}
		echo json_encode ( $data );
	}

	public function addStock(){

		$idProducto= $_POST['producto'];
		$idDetalleProducto = $_POST['marca'];
		$cantidad= $_POST['cantidad'];
		$precio= $_POST['precio'];
		$inversion= $_POST['inversion'];
		$observacion= strtoupper(trim($_POST['observacion']));
		
		$objModel=$this->loadModel('producto');
		$result = $objModel->addStock($idProducto, $idDetalleProducto, $cantidad, $precio, $inversion, $observacion);
		if($result) echo 'ok'; else echo 'error';	
	}

	public function deleteStock(){
		$idStock = $_POST['idStock'];
		$estado = $_POST['estado'];
		$objModel=$this->loadModel('producto');
		$result=$objModel->deleteStock($idStock, $estado);
		if($result) echo 'ok'; else echo 'error';
	}

}

?>