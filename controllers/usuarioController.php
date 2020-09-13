<?php 

class usuarioController extends Controller{
	
	public function __construct(){
		parent::__construct();
		if (! isset ( $_SESSION ['user'] ))
			$this->redireccionar ( 'index' );
	}

	/* ********************************************************************************************************************************
														MODULO USUARIO
	******************************************************************************************************************************** */

	public function index(){
		$this->_view->setJs(array('index'));
		$objModel=$this->loadModel('usuario');
		//$this->_view->cargo=$objModel->getcargo();
		//$this->_view->area=$objModel->getarea();
		$this->_view->renderizar('index');
	}

	public function getusuarios(){
		$objModel=$this->loadModel('usuario');
		$result=$objModel->getusuarios();
		$cont=0;

		while($reg=$result->fetch_object()){
			$cont++;

			if($reg->ESTADO == 'INACTIVO'){
				$btn='btn-success';
				$icon='fa-check';
				$title='Habilitar';
				$class='activarusuario';
			}else{
				$btn='btn-danger';
				$icon='fa-close';
				$title='Inhabilitar';
				$class='desactivarusuario';
			}			

			$boton='<button id="'.$reg->IDUSUARIO.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';

			$data ['data'] [] = array ('CONT'=>$cont,
				'IDUSUARIO' => $reg->IDUSUARIO,
				'NUMERODOC'=>$reg->NUMERODOC,
				'NOMBRE'=>utf8_encode($reg->NOMBRE),
				'ESTADO'=>$reg->ESTADO,
				'FECHA'=>$reg->FECHA,
				'OPCIONES'=>utf8_encode($boton),
				);
		}
		echo json_encode ( $data );
	}

	public function addusuario(){

		$usuario= strtoupper(trim($_POST['usuario']));
		$ndoc= strtoupper(trim($_POST['ndoc']));
		$apepaterno= strtoupper(trim($_POST['apepaterno']));
		$apematerno= strtoupper(trim($_POST['apematerno']));
		$nombre= strtoupper(trim($_POST['nombre']));		
		$telefono= strtoupper(trim($_POST['telefono']));
		//$cargo= strtoupper(trim($_POST['cargo']));
		//$area= strtoupper(trim($_POST['area']));
		$correo= strtoupper(trim($_POST['correo']));
		$contrasenia= strtoupper(trim($_POST['contrasenia']));

		$objModel=$this->loadModel('usuario');
		$existencia = $objModel->existenciausuario($usuario);
		$validaciondni = $objModel->existenciadni($ndoc);
		if($existencia){
			echo 'El usuario ya existe. Pruebe con otro usuario';
		}else if($validaciondni){
			echo 'El DNI ya existe.';
		}else{
			$result = $objModel->addusuario($usuario, $ndoc, $apepaterno, $apematerno, $nombre, $telefono, $correo, $contrasenia);
			if($result) echo 'ok'; else echo 'error';
		}		
	}

	public function getusuario(){
		$idusuario = $_POST['idusuario'];		
		$objModel=$this->loadModel('usuario');
		$result=$objModel->getusuario($idusuario);
		$reg=$result->fetch_object();
		echo json_encode($reg);
	}

	public function updateusuario(){

		$usuario= strtoupper(trim($_POST['usuario']));
		$ndoc= strtoupper(trim($_POST['ndoc']));
		$apepaterno= strtoupper(trim($_POST['apepaterno']));
		$apematerno= strtoupper(trim($_POST['apematerno']));
		$nombre= strtoupper(trim($_POST['nombre']));		
		$telefono= strtoupper(trim($_POST['telefono']));
		$correo= strtoupper(trim($_POST['correo']));
		//$cargo= $_POST['cargo'];
		//$area= $_POST['area'];
		$contrasenia= strtoupper(trim($_POST['contrasenia']));

		$objModel=$this->loadModel('usuario');
		$result = $objModel->updateusuario($usuario, $ndoc, $apepaterno, $apematerno, $nombre, $telefono, $correo, $contrasenia);
		if($result) echo 1; else echo 0;		
	}

	public function delusuario(){
		$idusuario = $_POST['idusuario'];
		$estado = $_POST['estado'];
		$objModel=$this->loadModel('usuario');
		$result=$objModel->delusuario($idusuario, $estado);
		if($result) echo 'ok'; else echo 'error';
	}

	/* ********************************************************************************************************************************
																MODULO PERMISOS
	******************************************************************************************************************************** */

	public function getprofilexusuario(){
		$usuario = $_POST['usuario'];
		$objModel=$this->loadModel('usuario');
		$result = $objModel->getprofilexusuario($usuario);
		echo '<option selected disabled> SELECCIONE </option>';
		while ($reg = $result->fetch_object()){
			echo '<option '.$reg->SELECTED.' value="'.$reg->IDPERFIL.'" > '.$reg->NOMBRE_PERFIL.' </option>';
		}
	}

	public function addacceso(){
		$idperfil = $_POST['idperfil'];
		$usuario = $_POST['usuario'];
		$objModel=$this->loadModel('usuario');
		$result = $objModel->addacceso($idperfil, $usuario);
		if($result) echo 1; else echo 0;
	}
	
	public function getaccesosmodulo(){
		$idusuario = $_POST['idusuario'];
		$objModel=$this->loadModel('usuario');
		$result = $objModel->getaccesosuser($idusuario);
		$reg = $result->fetch_object();
		echo json_encode($reg);
	}


	/* ********************************************************************************************************************************
																MODULO PERFIL
	******************************************************************************************************************************** */

	public function getprofiles(){
		$objModel=$this->loadModel('usuario');
		$result = $objModel->getprofiles();
		$cont=0;

		while($reg=$result->fetch_object()){
			$cont++;
			if($reg->ESTADO == 'INACTIVO'){
				$btn='btn-success';
				$icon='fa-check';
				$title='Habilitar';
				$class='activarperfil';
			}else{
				$btn='btn-danger';
				$icon='fa-close';
				$title='Inhabilitar';
				$class='desactivarperfil';
			}			

			$boton='<button id="'.$reg->IDPERFIL.'" class="'.$class.' btn '.$btn.' btn-xs" title="'.$title.'"><span class="fa '.$icon.'"></span></button>';

			$data ['data'] [] = array (
				'IDPERFIL'=>$reg->IDPERFIL,
				'NOMBRE_PERFIL'=>$reg->NOMBRE_PERFIL,
				'ESTADO'=>$reg->ESTADO,
				'CANTMODULOS'=>$reg->CANTMODULOS,
				'FECHA'=>$reg->FECHA,
				'OPCIONES'=>$boton
				);
		}
		echo json_encode ( $data );
	}

	public function getcombomodulo(){
		$objModel=$this->loadModel('usuario');
		$result = $objModel->getcombomodulo();		
		while ($reg = $result->fetch_object()){
			echo '<option value="'.$reg->IDMODULO.'" > '.$reg->DESCRIPCION.' </option>';
		}
	}


	public function addprofile(){
		$nomperfil = strtoupper(trim($_POST['nom_profile']));
		$modulo = $_POST['idmodulo'];
		$objModel=$this->loadModel('usuario');
		$validar = $objModel->validarnombreperfil($nomperfil);

		if(!$validar){
			$idperfil=$objModel->addnombreperfil($nomperfil);

			foreach($modulo as $id => $valor){
					$idmodulo=$modulo[$id];
					$result=$objModel->addprofile($idmodulo, $idperfil);
			}
			if($result) echo 'ok'; else echo 'error';

		}else{
			echo 'Ya existe el Nombre del Perfil';
		}		
	}
	
	public function getcombomoduloxperfil(){
		$idperfil = $_POST['idperfil'];
		$objModel=$this->loadModel('usuario');
		$result = $objModel->getcombomoduloxperfil($idperfil);
		while ($reg = $result->fetch_object()){
			echo '<option '.$reg->SELECTED.' value="'.$reg->IDMODULO.'" > '.$reg->DESCRIPCION.' </option>';
		}
	}

	public function updateprofile(){
		$idperfil = $_POST['idperfil'];
		$modulo = $_POST['idmodulo'];

		$objModel=$this->loadModel('usuario');
		$result = $objModel->deleteprofile($idperfil);

		foreach($modulo as $id => $valor){
				$idmodulo=$modulo[$id];
				$result=$objModel->addprofile($idmodulo, $idperfil);
		}

		if($result) echo 'ok'; else echo 'error';
	}

	public function deleteprofile(){
		$idperfil = $_POST['idperfil'];
		$estado = $_POST['estado'];
		$objModel=$this->loadModel('usuario');
		$result = $objModel->inhabilitarprofile($idperfil, $estado);		
		if($result) echo 1; else echo 0;
	}

}

?>