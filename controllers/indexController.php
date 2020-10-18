<?php 

class indexController extends Controller{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$this->_view->setJs(array('index'));	
		$this->_view->renderizar('index',true);
	}
	 
	public function login(){
		
		$user = strtoupper(trim($_POST['user']));
		$pass = strtoupper(trim($_POST['pass']));
				
		$intentos = isset($_COOKIE['intentos'])?$_COOKIE['intentos']:1;
		$objModel=$this->loadModel('usuario');

		if ($intentos>10) {
			echo 'Sistema Bloqueado';
		}else if($objModel->validactivo($user)){
			echo 'Usuario Inactivo. Comuniquese con el Administrador';
		}else{				
			if ($objModel->validUser($user,$pass)){				
				$_SESSION['user']=$user;
				$_SESSION['nombre']=$objModel->getNombre($user);
				$_SESSION['menu'] = $objModel->getMenu($user);
				$_SESSION['idpersona'] = $objModel->getidpersona($user);
				
				$_SESSION['cart'] = array();
				$_SESSION['cart']['products'] = array();
				$_SESSION['cart']['ventasproducts'] = array();
				$_SESSION['cart']['payments'] = array();
				
				/*$_SESSION['idperfil'] = $objModel->getIdperfil($user);
				
				$_SESSION['arrayempresa']=$objModel->getEmpresas($_SESSION['user']);
                $_SESSION['foto']=$objModel->getFoto($_SESSION['idpersona']);*/
				
				$intentos=1;
				//setcookie('intentos',$intentos,time()+60);
				echo 1;
			}else
				echo 0;
		}	
		$intentos++;
		//setcookie('intentos',$intentos,time()+60);
	}	
	
	public function logout(){
	
		unset($_SESSION['user']);
		unset($_SESSION['nombre']);		
		unset($_SESSION['menu']);
		unset($_SESSION['idpersona']);
		$this->redireccionar('index');
	}

	public function permisoxmodulo(){
		$idmodulo = $_POST['idmodulo'];
		$objModel=$this->loadModel('usuario');
		$result = $objModel->permisoxmodulo($idmodulo);
		echo $result;
	}


	
	
	
	
	
}


?>