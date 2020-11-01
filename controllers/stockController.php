<?php 

class stockController extends Controller{
	
	public function __construct(){
		parent::__construct();		
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	public function index(){
		$idLocal = $_GET['idLocal'];
		$this->_view->idLocal=$idLocal;
		//echo $idLocal; exit();
		$objModel=$this->loadModel('general');
		$nombreLocal = $objModel->getNombreLocal($idLocal);		
		$this->_view->nombreLocal=$nombreLocal;

		$this->_view->setJs(array('index'));
		$this->_view->renderizar('index');
	}

	public function getStock($codLocal){
		$objModel=$this->loadModel('stock');
		$result=$objModel->getStock($codLocal);
		
		$data = array();
		while($reg=$result->fetch_object()){	

			$data ['data'] [] = array (
				'PRODUCTO' => utf8_encode($reg->sNOMBRE),
				'nCANTIDAD'=>$reg->nCANTIDAD,
				'nIDPRODUCTO'=>$reg->nIDPRODUCTO,
			);
		}
		echo json_encode ( $data );
	}
}
?>