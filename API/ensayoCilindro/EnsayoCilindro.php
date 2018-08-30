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
			}

			$dbS->squery("
						UPDATE
							ensayoCilindro;
						SET
							1QQ = '1QQ'
						WHERE
							id_ensayoCilindro = 1QQ AND
							status = 0

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
	


}
?>