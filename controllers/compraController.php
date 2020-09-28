<?php 

class compraController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){		
		$this->_view->setJs(array('index'));
		//$objModel=$this->loadModel('compra');
		//$this->_view->productos=$objModel->getComboProductos();
		$this->_view->renderizar('index');
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
		$search = $_POST['search'];
		$objModel=$this->loadModel('compra');
		$result=$objModel->autocomplete($search);		
		$data = array();
		while($reg=$result->fetch_object()){
			$data[] = array("value"=>$reg->IDPRODUCTO,"label"=>$reg->NOMBRE);
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