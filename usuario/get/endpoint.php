<?php
	if(!empty($_GET)){
		$function= $_GET['function'];
	}else{
		return -2;
	}
	include_once("./../Usuario.php");

	switch ($function){
		case'login':
			$usuario = new Usuario();
			echo $usuario->login($_GET['email'],$_GET['function']);
		break;
	}

?>