<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class RegistrosRev{
	private $id_registrosRev;
	private $fecha;
	private $revProyecto;
	private $revObtenido;
	private $tamAgregado;
	private $idenConcreto;
	private $volumen;
	private $horaDeterminacion;
	private $herramienta_id;
	private $concretera_id;
	private $remisionNo;
	private $horaSalida;
	private $horaLlegada;

	private $wc = '/1QQ/';

	//DUMMY
	public function initInsert($token,$rol_usuario_id,$id_formatoRegistroRev){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
							registrosRev(formatoRegistroRev_id)

						VALUES
							(1QQ)
				",array($id_formatoRegistroRev),"INSERT");
			if(!$dbS->didQuerydied){
				$id=$dbS->lastInsertedID;
				$arr = array('id_registrosRev' => $id,'estatus' => '¡Exito en la inicializacion','error' => 0);
				return json_encode($arr);
			}else{
				$arr = array('id_registrosRev' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
				return json_encode($arr);
			}
		}
		return json_encode($arr);

	}
	
	public function insertRegistroJefeBrigada($token,$rol_usuario_id,$campo,$valor,$id_registrosRev){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			switch ($campo) {
				case '1':
					$campo = 'fecha';
					break;
				case '2':
					$campo = 'revProyecto';
					break;
				case '3':
					$campo = 'revObtenido';
					break;
				case '4':
					$campo = 'tamAgregado';
					break;
				case '5':
					$campo = 'idenConcreto';
					break;
				case '6':
					$campo = 'volumen';
					break;
				case '7':
					$campo = 'horaDeterminacion';
					break;
				case '8':
					$campo = 'herramienta_id';
					break;
				case '9':
					$campo = 'concretera_id';
					break;
				case '10':
					$campo = 'remisionNo';
					break;
				case '11':
					$campo = 'horaSalida';
					break;
				case '12':
					$campo = 'horaLlegada';
					break;
				case '13':
					$campo = 'status';
					break;

			}

			$dbS->squery("
						UPDATE
							registrosRev
						SET
							1QQ = '1QQ'
						WHERE
							id_registrosRev = 1QQ

				",array($campo,$valor,$id_registrosRev),"UPDATE");
			$arr = array('estatus' => 'Exito en insercion', 'error' => 0);
			if(!$dbS->didQuerydied){
				$arr = array('id_registrosRev' => $id_registrosRev,'estatus' => '¡Exito en la inserccion de un registro!','error' => 0);
				return json_encode($arr);
			}else{
				$arr = array('id_registrosRev' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
				return json_encode($arr);
			}
		}
		return json_encode($arr);
	}

	public function getRegistrosByID($token,$rol_usuario_id,$id_registrosRev){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			      SELECT
			      	id_registrosRev,
			      	fecha,
					revProyecto,	
					revObtenido,
					tamAgregado,
					idenConcreto,
					volumen,
					horaDeterminacion,
					herramienta_id,
					concretera_id,
					remisionNo,
					horaSalida,
					horaLlegada,
					status
			      FROM 
			      	registrosRev
			      WHERE 
			      	registrosRev.active = 1 AND
			      	id_registrosRev = 1QQ
			      ",
			      array($id_registrosRev),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('No existen registro relacionados con el id_registrosRev'=>$id_registrosRev,'error' => 5);
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
	public function getAllRegistrosByID($token,$rol_usuario_id,$id_formatoRegistroRev){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qAll("
			      SELECT
			      	id_registrosRev,
			      	formatoRegistroRev_id,
			      	fecha,
					revProyecto,	
					revObtenido,
					tamAgregado,
					idenConcreto,
					volumen,
					horaDeterminacion,
					herramienta_id,
					concretera_id,
					remisionNo,
					horaSalida,
					horaLlegada,
					status
			      FROM 
			      	registrosRev
			      WHERE 
			      	registrosRev.active = 1 AND
			      	formatoRegistroRev_id = 1QQ
			      ",
			      array($id_formatoRegistroRev),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('No existen registro relacionados con el id_formatoRegistroRev'=>$id_formatoRegistroRev,'error' => 5);
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

	public function deactivate($token,$rol_usuario_id,$id_registrosRev){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							registrosRev
						SET
							active = 1QQ
						WHERE
							active=1 AND
							id_registrosRev = 1QQ
					 "
					,array(0,$id_registrosRev),"UPDATE"
			      	);
		//PENDIENTE por la herramienta_tipo_id para poderla imprimir tengo que cargar las variables de la base de datos?
			if(!$dbS->didQuerydied){
				$arr = array('id_registrosRev' => $id_registrosRev,'estatus' => 'Registro se desactivo','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la desactivacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}

		}
		return json_encode($arr);
	}
	

	


}
?>