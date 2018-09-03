<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class EnsayoCilindro{


	
	
	/* Variables de utilería */
	private $wc = '/1QQ/';

	public function initInsert($token,$rol_usuario_id,$registrosCampo_id,$formatoCampo_id,$footerEnsayo_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
							ensayoCilindro(registrosCampo_id,formatoCampo_id,footerEnsayo_id)

						VALUES
							(1QQ,1QQ,1QQ)
				",array($registrosCampo_id,$formatoCampo_id,$footerEnsayo_id),"INSERT");
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
	
	public function insertRegistroTecMuestra($token,$rol_usuario_id,$campo,$valor,$id_ensayoCilindro){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			switch ($campo) {
				case '1':
					$campo = 'peso';
					break;
				case '2':
					$campo = 'd1';
					break;
				case '3':
					$campo = 'd2';
					break;
				case '4':
					$campo = 'h1';
					break;
				case '5':
					$campo = 'h2';
					break;
				case '6':
					$campo = 'carga';
					break;
				case '7':
					$campo = 'falla';
					break;

			}

			$dbS->squery("
						UPDATE
							ensayoCilindro
						SET
							1QQ = '1QQ'
						WHERE
							id_ensayoCilindro = 1QQ
				",array($campo,$valor,$id_ensayoCilindro),"UPDATE");
			$arr = array('estatus' => 'Exito en insercion', 'error' => 0);
			if(!$dbS->didQuerydied){
				$arr = array('id_ensayoCilindro' => $id_ensayoCilindro,'estatus' => '¡Exito en la inserccion de un registro!','error' => 0);
				return json_encode($arr);
			}else{
				$arr = array('id_ensayoCilindro' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
				return json_encode($arr);
			}
		}
		return json_encode($arr);
	}

	/*
	public function getRegistrosCilByID($token,$rol_usuario_id,$id_ensayoCilindro){
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
					diasEnsaye,
					unidad,
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


	public function Area($d1,$d2,$id_ensayoCilindro){
		$promedio = ($d1+$d2)/2;
		$

	}
	*/


}
?>