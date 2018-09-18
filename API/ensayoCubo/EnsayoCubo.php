<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class EnsayoCubo{


	
	
	/* Variables de utilería */
	private $wc = '/1QQ/';
	
	
	
	public function insertRegistroTecMuestra($token,$rol_usuario_id,$campo,$valor,$id_ensayoCubo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			switch ($campo) {
				case '1':
					$campo = 'l1';
					break;
				case '2':
					$campo = 'l2';
					break;
				case '3':
					$campo = 'carga';
					break;
				case '4':
					$campo = 'falla';
					break;
			}

			$dbS->squery("
						UPDATE
							ensayoCubo
						SET
							fecha = CURDATE() AND
							1QQ = '1QQ'
						WHERE
							id_ensayoCubo = 1QQ
				",array($campo,$valor,$id_ensayoCubo),"UPDATE- EnsayoCubo :: insertRegistroTecMuestra");
			$arr = array('estatus' => 'Exito en insercion', 'error' => 0);
			if(!$dbS->didQuerydied){
				$fechaEnsayo = $dbS->qarrayA(
					"
						SELECT 
							fecha
						FROM
							ensayoCubo
						WHERE
							id_ensayoCubo = 1QQ
					",
					array($id_ensayoCubo),
					"SELECT"
				);
				$arr = array('id_ensayoCubo' => $id_ensayoCubo,'estatus' => '¡Exito en la inserccion de un registro!','fechaEnsayo' => $fechaEnsayo['fecha'],'error' => 0);
				return json_encode($arr);
			}else{
				$arr = array('id_ensayoCubo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
				return json_encode($arr);
			}
		}
		return json_encode($arr);
	}

	
	public function calcularAreaResis($token,$rol_usuario_id,$id_ensayoCubo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->beginTransaction();
				$variables = $dbS->qarrayA(
					"
						SELECT
							l1,
							l2,
							carga
						FROM
							ensayoCubo
						WHERE 
							id_ensayoCubo  = 1QQ
					",array($id_ensayoCubo),"SELECT"
					);

			if(!$dbS->didQuerydied){
				$dbS->commitTransaction();
				$area = ($variables['l1']*$variables['l2']);
				if($area == 0){
					$area = 'Error: Verifique sus datos, el area debe ser distinta de 0';
					$resistencia = 'Error: No se puede realizar una division entre 0';
					$error = 5;
				} 	
				else{
					$resistencia = $variables['carga']/$area;
					$error = 0;
				}
				$arr = array('area' => $area,'resistencia' => $resistencia, 'error'=> $error);
				return json_encode($arr);
			}else{
				$dbS->rollbackTransaction();
				$arr = array('estatus' => 'No se pudieron cargar las variables del registro.','error' => 6);
				return json_encode($arr);
			}
		}
		return json_encode($arr);
	}
	
	public function getRegistrosByID($token,$rol_usuario_id,$id_ensayoCubo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			    	SELECT
						id_ensayoCubo,
						ensayoCubo.formatoCampo_id AS formatoCampo_id,
						IF(registrosCampo.status = 3,'SI','NO') AS completado,
						l1,
						l2,
						carga,
						falla,
						registrosCampo_id,
						claveEspecimen,
						ensayoCubo.fecha AS fechaEnsayo,
						diasEnsaye,
						ensayoCubo.formatoCampo_id,
						registrosCampo.fecha AS fechaColado,
						informeNo,
						CASE
							WHEN MOD(diasEnsaye,4) = 1 THEN prueba1  
							WHEN MOD(diasEnsaye,4) = 1 THEN prueba1
							WHEN MOD(diasEnsaye,4) = 2 THEN prueba2  
							WHEN MOD(diasEnsaye,4) = 2 THEN prueba2
							WHEN MOD(diasEnsaye,4) = 3 THEN prueba3  
							WHEN MOD(diasEnsaye,4) = 3 THEN prueba3
							WHEN MOD(diasEnsaye,4) = 0 THEN prueba4  
							WHEN MOD(diasEnsaye,4) = 0 THEN prueba4
							ELSE 'Error, Contacta a soporte'
						END AS diasEnsayeFinal
					FROM 
						ensayoCubo,registrosCampo,formatoCampo
					WHERE
						id_formatoCampo = ensayoCubo.formatoCampo_id AND
						id_registrosCampo = ensayoCubo.registrosCampo_id AND
						id_ensayoCubo = 1QQ
			      ",
			      array($id_ensayoCubo),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('No existen registro relacionados con el id_ensayoCubo'=>$id_ensayoCubo,'error' => 5);
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

	public function completeEnsayo($token,$rol_usuario_id,$id_ensayoCubo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
				$dbS->beginTransaction();
				$a = $dbS->qarrayA(
										"
											SELECT
												registrosCampo_id
											FROM
												ensayoCubo
											WHERE
												id_ensayoCubo = 1QQ
										",
										array($id_ensayoCubo),
										"SELECT"
									 );
				if(!$dbS->didQuerydied){
					$dbS->squery("
						UPDATE
							registrosCampo
						SET
							status = 1QQ
						WHERE
							id_registrosCampo = 1QQ
					",array(3,$a['registrosCampo_id']),"UPDATE");
					if(!$dbS->didQuerydied){
						$dbS->squery("
						UPDATE
							ensayoCubo
						SET
							fecha = CURDATE()
						WHERE
							id_ensayoCubo = 1QQ
						",array($id_ensayoCubo),"UPDATE");
						if(!$dbS->didQuerydied){
							$dbS->commitTransaction();
							$arr = array('id_ensayoCubo' => $id_ensayoCubo,'estatus' => '¡Ensayo completado!','error' => 0);
							return json_encode($arr);
						}
						else{
							$dbS->rollbackTransaction();
							$arr = array('id_ensayoCubo' => 'NULL','token' => $token,	'estatus' => 'Error en la actualizacion del registroCubo, verifica tus datos y vuelve a intentarlo','error' => 5);
							return json_encode($arr);	
						}
					}
					else{
						$dbS->rollbackTransaction();
						$arr = array('id_ensayoCubo' => 'NULL','token' => $token,	'estatus' => 'Error en la actualizacion del registroCCH, verifica tus datos y vuelve a intentarlo','error' => 5);
						return json_encode($arr);
					}
				}else{
					$dbS->rollbackTransaction();
					$arr = array('id_ensayoCubo' => 'NULL','token' => $token,	'estatus' => 'Error en la consulta, verifica tus datos y vuelve a intentarlo','error' => 5);
					return json_encode($arr);
				}		
		}
		return json_encode($arr);
	}
}
?>