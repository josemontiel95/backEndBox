<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class footerEnsayo{


	
	
	/* Variables de utilería */
	private $wc = '/1QQ/';

	public function initInsert($token,$rol_usuario_id,$tipo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode
		($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			//Cargamos las variables del sistema
			$dbS->beginTransaction();	//Iniciamos la transacción
			$arr = 	$dbS->qarrayA(
										"
											SELECT
												id_footerEnsayo,
												buscula_id,
												regVerFle,
												prensa_id,
												tipo
											FROM
												footerEnsayo
											WHERE
												CURDATE() = DATE(createdON) AND
												tipo = 1QQ

										",
										array($tipo),
										"SELECT"
								);
			if($arr == "empty"){
				$var_system = $dbS->qarrayA(
				"
					SELECT
						ensayo_def_buscula_id,
						ensayo_def_regVerFle_id,
						ensayo_def_prensa_id
					FROM
						systemstatus

				",array(),"SELECT"

				);
				$dbS->squery("
						INSERT INTO
							footerEnsayo(buscula_id,regVerFle,prensa_id,tipo)

						VALUES
							(1QQ,1QQ,1QQ,'1QQ')
				",array($var_system['ensayo_def_buscula_id'],$var_system['ensayo_def_regVerFle_id'],$var_system['ensayo_def_prensa_id'],$tipo),"INSERT");
				if(!$dbS->didQuerydied){
					$id=$dbS->lastInsertedID;
					$arr = array('id_footerEnsayo' => $id,'estatus' => '¡Exito en la inicializacion','error' => 0,'existe' => 0);
					$dbS->commitTransaction();
					return json_encode($arr);
				}else{
					$dbS->rollbackTransaction();
					$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
					return json_encode($arr);
				}

			}
			else{
				$arr = array('id_footerEnsayo' => $id_footerEnsayo,'estatus' => 'Ya se creo un footer el dia de hoy para el tipo:'.$tipo,'error'=>6,'existe' => 1);
			}
			
			
		}
		return json_encode($arr);

	}
	
	public function insertRegistroTecMuestra($token,$rol_usuario_id,$campo,$valor,$id_footerEnsayo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			switch ($campo) {
				case '1':
					$campo = 'buscula_id';
					break;
				case '2':
					$campo = 'regVerFle';
					break;
				case '3':
					$campo = 'prensa_id';
					break;
			}

			$dbS->squery("
						UPDATE
							ensayoCilindro;
						SET
							1QQ = '1QQ'
						WHERE
							id_footerEnsayo = 1QQ AND
							status = 0

				",array($campo,$valor,$id_footerEnsayo),"UPDATE");
			if(!$dbS->didQuerydied){
				$arr = array('id_footerEnsayo' => $id_footerEnsayo,'estatus' => '¡Exito en el cambio del footer!','error' => 0);
				return json_encode($arr);
			}else{
				$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
				return json_encode($arr);
			}
		}
		return json_encode($arr);
	}
	


}
?>