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


	public function initInsert($token,$rol_usuario_id,$formatoCampo_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
							registrosCampo(formatoCampo_id)

						VALUES
							(1QQ)
				",array($formatoCampo_id),"INSERT");
			if(!$dbS->didQuerydied){
				$id=$dbS->lastInsertedID;
				$arr = array('id_registrosCampo' => $id,'estatus' => '¡Exito en la inicializacion','error' => 0);
				return json_encode($arr);
			}else{
				$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
				return json_encode($arr);
			}
		}
		return json_encode($arr);

	}
	
	public function insertRegistroJefeBrigada($token,$rol_usuario_id,$campo,$valor,$id_registrosCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			switch ($campo) {
				case '1':
					$campo = 'claveEspecimen';
					break;
				case '2':
					$campo = 'fecha';
					break;
				case '3':
					$campo = 'fprima';
					break;
				case '4':
					$campo = 'revProyecto';
					break;
				case '5':
					$campo = 'revObra';
					break;
				case '6':
					$campo = 'tamAgregado';
					break;
				case '7':
					$campo = 'volumen';
					break;
				case '8':
					$campo = 'tipoConcreto';
					break;
				case '9':
					$campo = 'herramienta_id';
					break;
				case '10':
					$campo = 'horaMuestreo';
					break;
				case '11':
					$campo = 'tempMuestreo';
					break;
				case '12':
					$campo = 'tempRecoleccion';
					break;
				case '13':
					$campo = 'localizacion';
					break;

			}

			$dbS->squery("
						UPDATE
							registrosCampo
						SET
							1QQ = '1QQ'
						WHERE
							id_registrosCampo = 1QQ

				",array($campo,$valor,$id_registrosCampo),"UPDATE");
			$arr = array('estatus' => 'Exito en insercion', 'error' => 0);
			if(!$dbS->didQuerydied){
				$arr = array('id_registrosCampo' => $id_registrosCampo,'estatus' => '¡Exito en la inserccion de un registro!','error' => 0);
				return json_encode($arr);
			}else{
				$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
				return json_encode($arr);
			}
		}
		return json_encode($arr);
	}

	public function getRegistrosByID($token,$rol_usuario_id,$id_registrosCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			      SELECT
			      	id_registrosCampo,
					formatoCampo_id,
			        claveEspecimen,
					fecha,
					fprima,
					revProyecto,
					revObra,
					tamagregado,
					volumen,
					tipoConcreto,
					herramienta_id,
					horaMuestreo,
					tempMuestreo,
					tempRecoleccion,
					localizacion,
					status
			      FROM 
			      	registrosCampo
			      WHERE 
			      	registrosCampo.active = 1 AND
			      	id_registrosCampo = 1QQ
			      ",
			      array($id_registrosCampo),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('No existen registro relacionados con el id_registrosCampo'=>$id_registrosCampo,'error' => 5);
				}
				else{
					return json_encode($s);
				}
			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getHerramientaByID , verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);
	}



	/*
		Obtienes todos los registros relacionados con un formato de Campo
	*/
	public function getAllRegistrosByID($token,$rol_usuario_id,$id_formatoCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qAll("
			      SELECT
			      	id_registrosCampo,
					formatoCampo_id,
			        claveEspecimen,
					fecha,
					fprima,
					revProyecto,
					revObra,
					tamagregado,
					volumen,
					tipoConcreto,
					herramienta_id,
					horaMuestreo,
					tempMuestreo,
					tempRecoleccion,
					localizacion,
					status
			      FROM 
			      	registrosCampo
			      WHERE 
			      	registrosCampo.active = 1 AND
			      	formatoCampo_id = 1QQ
			      ",
			      array($id_formatoCampo),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('No existen registro relacionados con el id_formatoCampo'=>$id_formatoCampo,'error' => 5);
				}
				else{
					return json_encode($s);
				}
			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getHerramientaByID , verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);

	}

	public function deactivate($token,$rol_usuario_id,$id_registrosCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							registrosCampo
						SET
							active = 1QQ
						WHERE
							active=1 AND
							id_registrosCampo = 1QQ
					 "
					,array(0,$id_registrosCampo),"UPDATE"
			      	);
		//PENDIENTE por la herramienta_tipo_id para poderla imprimir tengo que cargar las variables de la base de datos?
			if(!$dbS->didQuerydied){
				$arr = array('id_registrosCampo' => $id_registrosCampo,'estatus' => 'Registro se desactivo','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la desactivacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}

		}
		return json_encode($arr);
	}
	

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