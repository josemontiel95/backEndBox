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
												regVerFle_id,
												prensa_id,
												tipo
											FROM
												footerEnsayo
											WHERE
												CURDATE() = DATE(createdON) AND
												tipo = '1QQ'

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
					ORDER BY id_systemstatus DESC;
				",array(),"SELECT"

				);
				$dbS->squery("
						INSERT INTO
							footerEnsayo(buscula_id,regVerFle_id,prensa_id,tipo)

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
				$arr = array('id_footerEnsayo' => $arr['id_footerEnsayo'],'estatus' => 'Ya se creo un footer el dia de hoy para el tipo:'.$tipo,'error'=>6,'existe' => 1);
			}
			
			
		}
		return json_encode($arr);

	}


	public function getFooterByID($token,$rol_usuario_id,$id_footerEnsayo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			      SELECT
			      	id_footerEnsayo,
					buscula_id,
					placas_bascula,
			        regVerFle_id,
			        placas_regla,
			        placas_verniers,
			        placas_flexometro,
					prensa_id,
					placas_prensa,
					tipo
			      FROM 
			      	footerEnsayo,
			      	(
			      		SELECT
			      			id_herramienta,
			      			placas AS placas_bascula
			      		FROM
			      			herramientas
			      		WHERE
			      			herramienta_tipo_id = 1005

			      	)AS basculas,
			      	(
			      		SELECT
			      			id_herramienta,
			      			placas AS placas_regla
			      		FROM
			      			herramientas
			      		WHERE
			      			herramienta_tipo_id = 1006

			      	)AS reglas,
			      	(
			      		SELECT
			      			id_herramienta,
			      			placas AS placas_verniers
			      		FROM
			      			herramientas
			      		WHERE
			      			herramienta_tipo_id = 1003

			      	)AS verniers,
			      	(
			      		SELECT
			      			id_herramienta,
			      			placas AS placas_prensa
			      		FROM
			      			herramientas
			      		WHERE
			      			herramienta_tipo_id = 1008

			      	)AS prensas,
			      	(
			      		SELECT
			      			id_herramienta,
			      			placas AS placas_flexometro
			      		FROM
			      			herramientas
			      		WHERE
			      			herramienta_tipo_id = 1003

			      	)AS flexometros
			      WHERE 
			      	footerEnsayo.active = 1 AND
			      	buscula_id = basculas.id_herramienta AND
			      	(regVerFle_id = reglas.id_herramienta OR regVerFle_id OR regVerFle_id = verniers.id_herramienta OR regVerFle_id = flexometros.id_herramienta) AND
			      	prensa_id = prensas.id_herramienta AND
			      	id_footerEnsayo = 1QQ
			      ",
			      array($id_footerEnsayo),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('No existen footer relacionados con el id_footerEnsayo'=>$id_footerEnsayo,'error' => 5);
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
					$campo = 'regVerFle_id';
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
							id_footerEnsayo = 1QQ
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