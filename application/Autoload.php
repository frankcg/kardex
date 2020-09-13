<?php 
function __autoload($class){
	if (!file_exists($file = dirname(__FILE__).'/'. $class .'.php')) return;
	
	require_once($file);
}

?>