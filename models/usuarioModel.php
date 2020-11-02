<?php 
Class usuarioModel extends Model{
	
	public function __construct(){
		parent::__construct();
	}

	/* **********************************************************************************************************
												CONTROLLER INDEX
	************************************************************************************************************* */

	/*public function getcargo(){
		$sql="SELECT * FROM `dt_cargo` WHERE FLAG=1";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getarea(){
		$sql="SELECT * FROM `dt_area` WHERE FLAG=1";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}*/

	public function validactivo($user){
		$sql="SELECT * FROM `kar_usuario` WHERE IDUSUARIO='$user' AND ESTADO='0'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result->num_rows;
	}
	
	public function validUser($user,$pass){

		$sql="SELECT * FROM kar_usuario WHERE IDUSUARIO='$user' AND CONTRASENIA=SHA1('$pass')";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result->num_rows;
	}	

	public function getNombre($user){
		$sql="SELECT CONCAT(b.AP_PATERNO,' ', b.AP_MATERNO,', ',NOMBRE) AS NOMBRE
			FROM `kar_usuario` a INNER JOIN kar_persona b ON a.IDPERSONA=b.IDPERSONA
			WHERE a.IDUSUARIO = '$user'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		$reg="";
		if($result->num_rows)
			$reg=$result->fetch_object();
		return $reg->NOMBRE;
	}

	public function getFoto($user){
		$sql="SELECT `FOTO` FROM sismarc_assist_persona WHERE IDPERSONA='$user'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		$reg="";
		if($result->num_rows){
			$reg=$result->fetch_object();
            return $reg->FOTO;
		} else{
		    return 0;
        }
	}

	public function getMenu($user){
		$data=array();
		$sql="SELECT c.IDMODULO, c.NOMBRE_MODULO, c.DESCRIPCION, c.TIPO, c.UBICACION, c.nIDLOCAL 
			FROM kar_usuario a INNER JOIN `seguridad_modulo_perfil` b ON a.IDPERFIL=b.IDPERFIL
			INNER JOIN `seguridad_modulo` c ON b.IDMODULO=c.IDMODULO
			WHERE c.flag=1 and a.IDUSUARIO = '$user' ORDER BY c.ORDEN ASC";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		while ($reg= $result->fetch_object()){
			$data["$reg->TIPO"]["$reg->NOMBRE_MODULO"]= array('DESCRIPCION'=>$reg->DESCRIPCION,'UBICACION'=>$reg->UBICACION,'NIDLOCAL'=>$reg->nIDLOCAL);
		}		
		return $data;
	}

	public function getidpersona($user){
		$sql="SELECT IDPERSONA FROM `kar_usuario` WHERE IDUSUARIO='$user'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		$reg="";
		if($result->num_rows)
			$reg=$result->fetch_object();
		return $reg->IDPERSONA;
	}	

	/* **********************************************************************************************************
														CONTROLLER USUARIO
	************************************************************************************************************* */
	public function getusuarios(){
		$sql="SELECT a.IDUSUARIO, b.NUMERODOC, CONCAT_WS(' ',b.NOMBRE,b.AP_PATERNO,b.AP_MATERNO) AS NOMBRE, IF(a.ESTADO=1,'ACTIVO','INACTIVO') AS ESTADO, DATE_FORMAT(a.`dFECHACREACION`,'%d/%m/%Y') AS FECHA
			FROM kar_usuario a INNER JOIN `kar_persona` b ON a.`IDPERSONA`= b.IDPERSONA";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function existenciausuario($usuario){
		$sql="SELECT * FROM kar_usuario WHERE IDUSUARIO='$usuario'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		if($result->num_rows)
			return true;
		return false;
	}
	
	public function existenciadni($dni){
		$sql="SELECT * FROM `kar_persona` WHERE NUMERODOC='$dni'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		if($result->num_rows)
			return true;
		return false;		
	}
	
	public function addusuario($usuario, $ndoc, $apepaterno, $apematerno, $nombre, $telefono, $correo, $contrasenia){
		$user=$_SESSION['user'];
		$sql="INSERT INTO `kar_persona` SET nombre='$nombre', ap_paterno='$apepaterno', ap_materno='$apematerno', numerodoc='$ndoc', correo='$correo', telefono='$telefono', tipopersona='USER', idusuariocreacion='$user' ";
		$this->_db->query($sql) or die ('Error en '.$sql);

		$idpersona=$this->_db->insert_id;

		$sql2="INSERT INTO `kar_usuario` SET idusuario='$usuario', contrasenia=SHA1('$contrasenia'), idpersona='$idpersona', idusuariocreacion='$user'";
		$this->_db->query($sql2) or die ('Error en '.$sql2);

		if($this->_db->errno)
			return false;
		return true;
	}

	public function getusuario($idusuario){
		$sql="SELECT a.NOMBRE, a.AP_PATERNO, a.AP_MATERNO, a.NUMERODOC, a.CORREO, a.TELEFONO, b.IDUSUARIO, b.CONTRASENIA
			FROM `kar_persona` a INNER JOIN `kar_usuario` b ON a.IDPERSONA=b.IDPERSONA
			WHERE b.IDUSUARIO='$idusuario'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function updateusuario($usuario, $ndoc, $apepaterno, $apematerno, $nombre, $telefono, $correo, $contrasenia){
		$user=$_SESSION['user'];

		$sql_idpersona="SELECT * FROM `kar_usuario` WHERE IDUSUARIO = '$usuario'";
		$result_idpersona=$this->_db->query($sql_idpersona) or die ('Error en '.$sql_idpersona);		
		$reg = $result_idpersona->fetch_object();
		$idpersona = $reg->IDPERSONA;

		$sql_validar="SELECT * FROM `kar_usuario` WHERE IDUSUARIO = '$usuario' AND CONTRASENIA = '$contrasenia'";
		$result=$this->_db->query($sql_validar) or die ('Error en '.$sql_validar);		

		if(!$result->num_rows){
			$sql="UPDATE kar_usuario SET CONTRASENIA = sha1('$contrasenia'), IDUSUARIOMOD='$user' WHERE IDUSUARIO = '$usuario'";
			$this->_db->query($sql) or die ('Error en '.$sql);
		}else{
			$sql="UPDATE kar_persona SET nombre='$nombre', ap_paterno='$apepaterno', ap_materno='$apematerno', numerodoc='$ndoc', correo='$correo', telefono='$telefono' WHERE IDPERSONA = '$idpersona'";
			$this->_db->query($sql)or die ('Error en '.$sql);
		}

		if($this->_db->errno)
			return false;
		return true;
	}

	public function delusuario($idusuario, $estado){
		$user=$_SESSION['user'];
		$sql="UPDATE `kar_usuario` SET estado=$estado, idusuariomod='$user' WHERE idusuario='$idusuario'";
		$this->_db->query($sql) or die ('Error en '.$sql);		
		if($this->_db->errno)
			return false;
		return true;

	}

	/* ********************************************************************************************************************************
																MODULO PERFIL
	******************************************************************************************************************************** */
	
	public function getprofiles(){
		$sql="SELECT a.IDPERFIL, a.NOMBRE_PERFIL, IF(a.FLAG=1,'ACTIVO','INACTIVO') AS ESTADO, DATE_FORMAT(`dFECHACREACION`,'%d/%m/%Y') AS FECHA,
			(SELECT COUNT(*) FROM `seguridad_modulo_perfil` WHERE IDPERFIL=a.IDPERFIL) AS CANTMODULOS
			FROM `seguridad_perfil` a ORDER BY a.dFECHACREACION DESC";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getcombomodulo(){
		$sql="SELECT a.IDMODULO, a.NOMBRE_MODULO, a.DESCRIPCION, b.sDESCRIPCION AS LOCAL
			FROM `seguridad_modulo` a INNER JOIN sel_local b ON a.nIDLOCAL = b.nIDLOCAL
			WHERE a.FLAG = '1'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function getcombomoduloxperfil($idperfil){
		$sql="SELECT a.SELECTED, b.`IDMODULO`, b.DESCRIPCION, b.nIDLOCAL, c.sDESCRIPCION AS LOCAL FROM(			
			SELECT IDMODULO,'selected' SELECTED
			FROM `seguridad_modulo_perfil` 
			WHERE IDPERFIL = '$idperfil'
			) AS a RIGHT JOIN `seguridad_modulo` b  ON a.IDMODULO = b.IDMODULO 
			INNER JOIN sel_local c ON b.nIDLOCAL = c.nIDLOCAL
			WHERE b.flag=1";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function validarnombreperfil($nomperfil){
		$sql="SELECT * FROM `seguridad_perfil` WHERE NOMBRE_PERFIL='$nomperfil'";
		$result=$this->_db->query($sql)or die ('Error en '.$sql);
		return $result->num_rows;
	}	

	public function addnombreperfil($nomperfil){
		$sql="INSERT INTO `seguridad_perfil` SET NOMBRE_PERFIL='$nomperfil'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		return $this->_db->insert_id;
	}

	public function addprofile($idmodulo, $idperfil){
		$user=$_SESSION['user'];
		$sql="INSERT INTO `seguridad_modulo_perfil` SET IDMODULO='$idmodulo', IDPERFIL='$idperfil'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}	

	public function deleteprofile($idprofile){
		$sql="DELETE FROM `seguridad_modulo_perfil` WHERE IDPERFIL = '$idprofile'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function inhabilitarprofile($idperfil, $estado){
		$user=$_SESSION['user'];
		$sql="UPDATE seguridad_perfil SET FLAG=$estado, IDUSUARIOMOD='$user' WHERE IDPERFIL = '$idperfil'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}

	public function habilitarprofile($idperfil, $estado){
		$user=$_SESSION['user'];
		$sql="UPDATE sismarc_seguridad_perfil SET FLAG=$estado, IDUSUARIOMOD='$user' WHERE IDPERFIL = '$idperfil'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}	

	/* ******************************************************************************************************************
												MODULO ACCESO
	******************************************************************************************************************* */

	public function getprofilexusuario($usuario){
		$sql="SELECT a.SELECTED, b.`IDPERFIL`, b.NOMBRE_PERFIL FROM(			
			SELECT IDPERFIL,'selected' SELECTED
			FROM `kar_usuario` 
			WHERE IDUSUARIO = '$usuario') AS a RIGHT JOIN `seguridad_perfil` b  ON a.IDPERFIL = b.IDPERFIL";
		$result = $this->_db->query($sql)or die ('Error en '.$sql);
		return $result;
	}

	public function addacceso($idperfil, $usuario){
		$user=$_SESSION['user'];
		$sql="UPDATE `kar_usuario` SET IDPERFIL='$idperfil', IDUSUARIOMOD='$user' WHERE IDUSUARIO='$usuario'";
		$this->_db->query($sql)or die ('Error en '.$sql);
		if($this->_db->errno)
			return false;
		return true;
	}
	
	
}

?>