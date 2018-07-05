<?php
	$salt = array(
		"login"		=> "asdf",
		"request"	=> "zxcviuegfweofihw",
		"prefix"	=> 3
	);
	
	include_once("mysqlSystem.php");
	include_once("Types.php");
	$dbS = new MySQLSystem();
	
	session_start();

	function encurl($sData){
		global $salt;
		$rndPrefix = mt_rand(1 * pow(10,$salt['prefix']-1),1 * pow(10,$salt['prefix'])-1);
		$sData = $rndPrefix.".".$sData;
		$sResult = "";
		for($i = 0; $i<strlen($sData);$i++){
			$sChar = substr($sData, $i, 1);
			$skeyChar 	= substr($salt['request'], ($i % strlen($salt['request']))-1, 1);
			$sChar 		= chr(ord($sChar) + ord($skeyChar));
			$sResult	.= $sChar;
		}

		$sBase64 = base64_encode($sResult);
		return str_replace('=', '', strtr($sBase64, '+/', '-_'));
	}

	function decurl($sData){
		global $salt;
		$sResult = "";
		$sBase64 = strtr($sData, '-_', '+/');
		$sData = base64_decode($sBase64."==");
		for($i =0; $i < strlen($sData);$i++){
			$sChar 		= substr($sData, $i, 1);
			$skeyChar 	= substr( $salt['request'], ($i % strlen($salt['request']))-1,1);
			$sChar 		= chr( ord($sChar) - ord($skeyChar));
			$sResult 	.= $sChar;
		}
		if( strpos($sResult,".") !== $salt['prefix']){
			return "FALSEVALUE";
		}
		else{
			return substr($sResult, $salt['prefix']+1);
		}
	}
?>