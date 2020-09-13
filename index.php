<?php

ini_set('display_errors', 1);

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)) . DS);
define('APP_PATH', ROOT . 'application' . DS);


require_once APP_PATH . 'Config.php';
require_once APP_PATH . 'Autoload.php';
/*
require_once APP_PATH . 'Request.php';
require_once APP_PATH . 'Bootstrap.php';
require_once APP_PATH . 'Controller.php';
require_once APP_PATH . 'Model.php';
require_once APP_PATH . 'View.php';
require_once APP_PATH . 'Registro.php';
require_once APP_PATH . 'Database.php';
*/


try{
/*	
 	$req= new Request;
	echo $req->getControlador().'<br>';
	echo $req->getMetodo().'<br>';
	print_r($req->getArgs());
*/	
	session_start();
	
	
    Bootstrap::run(new Request);
}
catch(Exception $e){
    echo $e->getMessage();
}

?>