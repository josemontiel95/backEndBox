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
							fecha = CURDATE(),
							1QQ = '1QQ'
						WHERE
							id_ensayoCilindro = 1QQ
				",array($campo,$valor,$id_ensayoCilindro),"UPDATE");
			$arr = array('estatus' => 'Exito en insercion', 'error' => 0);
			if(!$dbS->didQuerydied){
				$fechaEnsayo = $dbS->qarrayA(
					"
						SELECT 
							fecha
						FROM
							ensayoCilindro
						WHERE
							fecha = CURDATE(),
							id_ensayoCilindro = 1QQ
					",
					array($id_ensayoCilindro),
					"SELECT"
				);
				$arr = array('id_ensayoCilindro' => $id_ensayoCilindro,'estatus' => '¡Exito en la inserccion de un registro!','fechaEnsayo' => $fechaEnsayo['fecha'],'error' => 0);
				return json_encode($arr);
			}else{
				$arr = array('id_ensayoCilindro' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
				return json_encode($arr);
			}
		}
		return json_encode($arr);
	}

	
	public function calcularAreaResis($token,$rol_usuario_id,$id_ensayoCilindro){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->beginTransaction();
			$var_system = $dbS->qarrayA(
					"
						SELECT
							ensayo_def_pi
						FROM
							systemstatus
						ORDER BY id_systemstatus DESC;
					",array(),"SELECT"
					);
			if(!$dbS->didQuerydied){
				$variables = $dbS->qarrayA(
					"
						SELECT
							d1,
							d2,
							carga
						FROM
							ensayoCilindro
						WHERE 
							id_ensayoCilindro  = 1QQ
					",array($id_ensayoCilindro),"SELECT"
					);
				if(!$dbS->didQuerydied){
					$dbS->commitTransaction();
					$promedio = ($variables['d1'] + $variables['d2'])/2;
					$area = ((($promedio * $promedio) * $var_system['ensayo_def_pi'])/4);
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
				}
				else{
					$dbS->rollbackTransaction();
					$arr = array('estatus' => 'No se pudieron cargar las variables del registro.','error' => 6);
					return json_encode($arr);
				}	
			}else{
				$dbS->rollbackTransaction();
				$arr = array('estatus' => 'No se pudieron cargar las constantes del sistema.','error' => 7);
				return json_encode($arr);
			}
		}
		return json_encode($arr);
	}
	public function getRegistrosByID($token,$rol_usuario_id,$id_ensayoCilindro){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			    	SELECT
						id_ensayoCilindro,
						footerEnsayo_id,
						IF(registrosCampo.status = 3,'SI','NO') AS completado,
						peso,
						d1,
						d2,
						h1,
						h2,
						carga,
						falla,
						registrosCampo_id,
						claveEspecimen,
						ensayoCilindro.fecha AS fechaEnsayo,
						diasEnsaye,
						ensayoCilindro.formatoCampo_id,
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
						ensayoCilindro,registrosCampo,formatoCampo
					WHERE
						id_formatoCampo = ensayoCilindro.formatoCampo_id AND
						id_registrosCampo = ensayoCilindro.registrosCampo_id AND
						id_ensayoCilindro = 1QQ
			      ",
			      array($id_ensayoCilindro),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('No existen registro relacionados con el id_ensayoCilindro'=>$id_ensayoCilindro,'error' => 5);
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

	public function completeEnsayo($token,$rol_usuario_id,$id_ensayoCilindro){
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
												ensayoCilindro
											WHERE
												id_ensayoCilindro = 1QQ
										",
										array($id_ensayoCilindro),
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
							ensayoCilindro
						SET
							fecha = CURDATE()
						WHERE
							id_ensayoCilindro = 1QQ
						",array($id_ensayoCilindro),"UPDATE");
						if(!$dbS->didQuerydied){
							$dbS->commitTransaction();
							$arr = array('id_ensayoCilindro' => $id_ensayoCilindro,'estatus' => '¡Ensayo completado!','error' => 0);
							return json_encode($arr);
						}
						else{
							$dbS->rollbackTransaction();
							$arr = array('id_ensayoCilindro' => 'NULL','token' => $token,	'estatus' => 'Error en la actualizacion del registroCilindro, verifica tus datos y vuelve a intentarlo','error' => 5);
							return json_encode($arr);	
						}
					}
					else{
						$dbS->rollbackTransaction();
						$arr = array('id_ensayoCilindro' => 'NULL','token' => $token,	'estatus' => 'Error en la actualizacion del registroCCH, verifica tus datos y vuelve a intentarlo','error' => 5);
						return json_encode($arr);
					}
				}else{
					$dbS->rollbackTransaction();
					$arr = array('id_ensayoCilindro' => 'NULL','token' => $token,	'estatus' => 'Error en la consulta, verifica tus datos y vuelve a intentarlo','error' => 5);
					return json_encode($arr);
				}		
		}
		return json_encode($arr);
	}
}
?>