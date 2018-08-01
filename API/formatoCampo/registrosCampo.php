<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class registrosCampo{
	private $formatoCampo_id;
	private $claveEspecimen;
	private $fecha;
	private $fprima;
	private $revProyecto;
	private $revObra;
	private $tamagregado;
	private $volumen;
	private $tipoConcreto;
	private $unidad;
	private $horaMuestreo;
	private $tempMuestreo;
	private $tempRecoleccion;
	private $localizacion;

	private $wc = '/1QQ/';
	/*
	public function insertJefeBrigada(){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						registrosCampo(${'1QQ'})

						VALUES
						('1QQ')
				",array($formatoCampo_id,$claveEspecimen,$fecha,$fprima,$revProyecto,$revObra,$tamagregado,$volumen,$tipoConcreto,$unidad,$horaMuestreo,$tempMuestreo,$tempRecoleccion,$localizacion),"INSERT");
			$arr = array('estatus' => 'Exito en insercion', 'error' => 0);
			if($dbS->didQuerydied){
					$arr = array('token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}
	/*


	/*

	public function insertAdmin($token,$rol_usuario_id,$claveEspecimen,$fecha,$fprima,$revProyecto,$revObra,$tamagregado,$volumen,$tipoConcreto,$unidad,$horaMuestreo,$tempMuestreo,$tempRecoleccion,$localizacion){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						registrosCampo(claveEspecimen,fecha,fprima,revProyecto,revObra,tamagregado,volumen,tipoConcreto,unidad,horaMuestreo,tempMuestreo,tempRecoleccion,localizacion)

						VALUES
						('1QQ','1QQ',1QQ,1QQ,1QQ,1QQ,1QQ,'1QQ','1QQ','1QQ',1QQ,1QQ,'1QQ')
				",array($formatoCampo_id,$claveEspecimen,$fecha,$fprima,$revProyecto,$revObra,$tamagregado,$volumen,$tipoConcreto,$unidad,$horaMuestreo,$tempMuestreo,$tempRecoleccion,$localizacion),"INSERT");
			$arr = array('estatus' => 'Exito en insercion', 'error' => 0);
			if($dbS->didQuerydied){
					$arr = array('token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	public function upDateAdmin($token,$rol_usuario_id,$id_cliente,$rfc,$razonSocial,$nombre,$direccion,$email,$telefono,$nombreContacto,$telefonoDeContacto){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							cliente
						SET
							rfc ='1QQ',
							razonSocial = '1QQ',
							nombre = '1QQ',
							direccion = '1QQ',
							email = '1QQ',
							telefono ='1QQ',
							nombreContacto = '1QQ', 
							telefonoDeContacto = '1QQ'
						WHERE
							active=1 AND
							id_cliente = 1QQ
					 "
					,array($rfc,$razonSocial,$nombre,$direccion,$email,$telefono,$nombreContacto,$telefonoDeContacto,$id_cliente),"UPDATE"
			      	);
			$arr = array('estatus' => 'Exito de actualizacion','error' => 0);	
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}		
		}
		return json_encode($arr);

	}

	public function getAllAdmin($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      SELECT 
			        id_cliente,
					rfc,
					razonSocial,
					nombre,
					direccion,
					email,
					foto,
					telefono,
					nombreContacto,
					telefonoDeContacto,
					createdON,
					lastEditedON,
					IF(active = 1,'Si','No') AS active
			      FROM 
			        cliente
			      ",
			      array(),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
						if(count($arr) == 0)
							$arr = array('estatus' =>"No hay registros", 'error' => 5); //Pendiente
						
			}else
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en el query, verifica tus datos y vuelve a intentarlo','error' => 6);
		}
		return json_encode($arr);	
	}

	*/


	




}
?>