<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class EnsayoViga{


	
	
	/* Variables de utilería */
	private $wc = '/1QQ/';

	public function ping($data){
		return $data;	
	}


	public function onChangePuntosDeApoyo($token,$rol_usuario_id,$valor,$id_ensayoViga){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			if($valor == 1){ // lijado
				$dbS->squery(
					"   UPDATE
							ensayoViga
						SET
							fecha = CURDATE(),
							lijado = 'SI',
							cuero = 'NO'
						WHERE
							id_ensayoViga = 1QQ
				",array($id_ensayoViga),"UPDATE -- EnsayoViga ::  onChangePuntosDeApoyo : 1");
			}else if($valor == 2){ // cuero
				$dbS->squery(
					"   UPDATE
							ensayoViga
						SET
							fecha = CURDATE(),
							lijado = 'NO',
							cuero = 'SI'
						WHERE
							id_ensayoViga = 1QQ
				",array($id_ensayoViga),"UPDATE -- EnsayoViga ::  onChangePuntosDeApoyo : 2");
			}else{ // error
				$arr = array('id_ensayoViga' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 6);
				return json_encode($arr);
			}
			if($dbS->didQuerydied){
				$arr = array('id_ensayoViga' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 10);
				return json_encode($arr);
			}
			$fechaEnsayo = $dbS->qarrayA(
				"   SELECT 
						fecha
					FROM
						ensayoViga
					WHERE
						id_ensayoViga = 1QQ
				",
				array($id_ensayoViga),
				"SELECT -- EnsayoViga :: insertRegistroTecMuestra : 2"
			);

			if(!$dbS->didQuerydied){
				$arr = array('id_ensayoViga' => $id_ensayoViga,'estatus' => '¡Exito en la inserccion de un registro!','fechaEnsayo' => $fechaEnsayo['fecha'],'error' => 0);
			}else{
				$arr = array('id_ensayoViga' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 7);
			}
			
		}
		return json_encode($arr);
	}

	public function insertRegistroTecMuestra($token,$rol_usuario_id,$campo,$valor,$id_ensayoViga){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			switch ($campo) {
				case '1':
					$campo = 'condiciones';
					$this->updateCampo($campo,$valor,$id_ensayoViga);
					break;
				case '2': // Si uso
					$campo = 'lijado';
					$this->updateCampo($campo,$valor,$id_ensayoViga);
					break;
				case '3':
					$campo = 'posFractura';
					$arr = $this->updateCampo($campo,$valor,$id_ensayoViga);
					break;
				case '4':
					$campo = 'ancho1';
					$arr = $this->updateCampo($campo,$valor,$id_ensayoViga);
					break;
				case '5':
					$campo = 'ancho2';
					$arr = $this->updateCampo($campo,$valor,$id_ensayoViga);
					break;
				case '6':
					$campo = 'per1';
					$arr = $this->updateCampo($campo,$valor,$id_ensayoViga);
					break;
				case '7':
					$campo = 'per2';
					$arr = $this->updateCampo($campo,$valor,$id_ensayoViga);
					break;
				case '8':
					$campo = 'l1';
					$arr = $this->updateCampo($campo,$valor,$id_ensayoViga);
					break;
				case '9':
					$campo = 'l2';
					$arr = $this->updateCampo($campo,$valor,$id_ensayoViga);
					break;
				case '10':
					$campo = 'l3';
					$arr = $this->updateCampo($campo,$valor,$id_ensayoViga);
					break;
				case '11':
					$campo = 'disApoyo';
					$arr = $this->updateCampo($campo,$valor,$id_ensayoViga);
					break;
				case '12':
					$campo = 'disCarga';
					$arr = $this->updateCampo($campo,$valor,$id_ensayoViga);
					break;
				case '13':
					$campo = 'carga';
					$arr = $this->updateCampo($campo,$valor,$id_ensayoViga);
					break;
				case '14':
					$campo = 'defectos';
					$arr = $this->updateCampo($campo,$valor,$id_ensayoViga);
					break;
				case '15':
					$campo = 'velAplicacionExp';
					$arr = $this->updateCampo($campo,$valor,$id_ensayoViga);
					break;
				case '16':
					$campo = 'tiempoDeCarga';
					$arr = $this->updateCampo($campo,$valor,$id_ensayoViga);
					break;
			}
		}
		return json_encode($arr);
	}

	private function updateCampo($campo,$valor,$id_ensayoViga){
		global $dbS;
		$dbS->squery("
					UPDATE
						ensayoViga
					SET
						fecha = CURDATE(),
						1QQ = '1QQ'
					WHERE
						id_ensayoViga = 1QQ
			",array($campo,$valor,$id_ensayoViga),
			"UPDATE-- EnsayoViga :: insertRegistroTecMuestra : 1");
		$arr = array('estatus' => 'Exito en insercion', 'error' => 0);
		if(!$dbS->didQuerydied){
			$fechaEnsayo = $dbS->qarrayA(
				"
					SELECT 
						fecha
					FROM
						ensayoViga
					WHERE
						id_ensayoViga = 1QQ
				",
				array($id_ensayoViga),
				"SELECT -- EnsayoViga :: insertRegistroTecMuestra : 2"
			);
			$arr = array('id_ensayoViga' => $id_ensayoViga,'estatus' => '¡Exito en la inserccion de un registro!','fechaEnsayo' => $fechaEnsayo['fecha'],'error' => 0);
		}else{
			$arr = array('id_ensayoViga' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
		}
		return json_encode($arr);
	}


	public function getAllRegistrosFromFooterByID($token,$rol_usuario_id,$footerEnsayo_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qAll("
			    	SELECT
						id_ensayoViga,
						ensayoViga.formatoCampo_id AS formatoCampo_id,
						IF(registrosCampo.status = 3,'SI','NO') AS completado,
						encargado_id,
						CONCAT(nombre,' ',apellido) AS nombre,
						ensayoViga.fecha AS fechaEnsayo,
						condiciones,
						lijado,
						cuero,
						ancho1,
						ancho2,
						per1,
						per2,
						l1,
						l2,
						l3,
						disApoyo,
						disCarga,
						carga,
						defectos,
						registrosCampo_id,
						claveEspecimen,
						registrosCampo.fecha AS fechaColado,
						diasEnsaye,
						informeNo,
						ensayoViga.formatoCampo_id,
						ensayoViga.status AS status,
						CASE
							WHEN MOD(diasEnsaye,3) = 1 THEN prueba1  
							WHEN MOD(diasEnsaye,3) = 2 THEN prueba2  
							WHEN MOD(diasEnsaye,3) = 0 THEN prueba3  
							ELSE 'Error, Contacta a soporte'
						END AS diasEnsayeFinal
					FROM 
						ensayoViga,registrosCampo,formatoCampo,footerEnsayo,usuario
					WHERE
						ensayoViga.footerEnsayo_id = id_footerEnsayo AND
						encargado_id = id_usuario AND
						id_formatoCampo = ensayoViga.formatoCampo_id AND
						id_registrosCampo = ensayoViga.registrosCampo_id AND
						ensayoViga.footerEnsayo_id = 1QQ
			      ",
			      array($footerEnsayo_id),
			      "SELECT-- ensayoViga :: getAllRegistrosFromFooterByID"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('No existen registro relacionados con el id_ensayoViga'=>$id_ensayoViga,'error' => 5);
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
	

	public function getOldMembers($token,$rol_usuario_id,$id_ensayoViga){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$info = $dbS->qarrayA(
				"   SELECT 
						grupo,
						registrosCampo.formatoCampo_id AS formatoCampo_id,
						id_registrosCampo
					FROM
						registrosCampo, ensayoViga
					WHERE
						id_registrosCampo = registrosCampo_id AND
						id_ensayoViga = 1QQ
				",
				array($id_ensayoViga),
				"SELECT -- EnsayoViga :: getOldMembers : 1"
			);
			if($dbS->didQuerydied || $info=="empty"){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getOldMembers , verifica tus datos y vuelve a intentarlo','error' => 10);
				return json_encode($arr);
			}
			$arr = $dbS->qAll(
				"   SELECT 
						id_ensayoViga
					FROM
						registrosCampo, ensayoViga
					WHERE
						id_registrosCampo = registrosCampo_id AND
						registrosCampo.formatoCampo_id = 1QQ AND
						grupo = '1QQ' AND
						id_ensayoViga < 1QQ
					ORDER BY id_registrosCampo DESC
				",
				array($info['formatoCampo_id'],$info['grupo'],$id_ensayoViga),
				"SELECT -- EnsayoViga :: getOldMembers : 2"
			);
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getOldMembers , verifica tus datos y vuelve a intentarlo','error' => 11);
				return json_encode($arr);
			}else if($arr=="empty"){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getOldMembers , verifica tus datos y vuelve a intentarlo','error' => 12);
				return json_encode($arr);
			}

		}
		return json_encode($arr);
	}
	
	public function getRegistrosByID($token,$rol_usuario_id,$id_ensayoViga){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			    	SELECT
						id_ensayoViga,
						ensayoViga.formatoCampo_id AS formatoCampo_id,
						IF(registrosCampo.status = 3,'SI','NO') AS completado,
						encargado_id,
						CONCAT(nombre,' ',apellido) AS nombre,
						ensayoViga.fecha AS fechaEnsayo,
						condiciones,
						CASE 
							WHEN lijado = 'NO' THEN 2
							WHEN lijado = 'SI' THEN 1
							else NULL
						END AS apoyo,
						lijado,
						cuero,
						posFractura,
						ancho1,
						ancho2,
						per1,
						per2,
						l1,
						l2,
						l3,
						disApoyo,
						disCarga,
						carga,
						defectos,
						registrosCampo_id,
						claveEspecimen,
						registrosCampo.fecha AS fechaColado,
						diasEnsaye,
						ensayoViga.formatoCampo_id,
						ensayoViga.status AS status,
						CASE
							WHEN MOD(diasEnsaye,3) = 1 THEN prueba1
							WHEN MOD(diasEnsaye,3) = 2 THEN prueba2
							WHEN MOD(diasEnsaye,3) = 0 THEN prueba3  
							ELSE 'Error, Contacta a soporte'
						END AS diasEnsayeFinal,
						velAplicacionExp,
						tiempoDeCarga,
						prom,
						mr
					FROM 
						ensayoViga,registrosCampo,formatoCampo,footerEnsayo,usuario
					WHERE
						ensayoViga.footerEnsayo_id = id_footerEnsayo AND
						encargado_id = id_usuario AND
						id_formatoCampo = ensayoViga.formatoCampo_id AND
						id_registrosCampo = ensayoViga.registrosCampo_id AND
						id_ensayoViga = 1QQ
			      ",
			      array($id_ensayoViga),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('No existen registro relacionados con el id_ensayoViga'=>$id_ensayoViga,'error' => 5);
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

	public function completeEnsayo($token,$rol_usuario_id,$id_ensayoViga){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
				$dbS->beginTransaction();
				$a = $dbS->qarrayA(
					"	SELECT
							registrosCampo_id,
							footerEnsayo_id
						FROM
							ensayoViga
						WHERE
							id_ensayoViga = 1QQ
					",
					array($id_ensayoViga),
					"SELECT -- EnsayoViga ::  completeEnsayo : 1"
					);
				if(!$dbS->didQuerydied){
					$dbS->squery(
						"UPDATE
							footerEnsayo
						SET
							pendingEnsayos = pendingEnsayos -1
						WHERE
							id_footerEnsayo = 1QQ
						",array($a['footerEnsayo_id']),
						"UPDATE -- EnsayoViga ::  completeEnsayo : 2"
					);
					if($dbS->didQuerydied){
						$dbS->rollbackTransaction();
						$arr = array('ensayoViga' => 'NULL','token' => $token,	'estatus' => 'Error en la actualizacion del registroCCH, verifica tus datos y vuelve a intentarlo','error' => 40);
						return json_encode($arr);
					}
					$dbS->squery(
						"UPDATE
							registrosCampo
						SET
							statusEnsayo = 1
						WHERE
							id_registrosCampo = 1QQ
						",array($a['registrosCampo_id']),
						"UPDATE -- EnsayoViga ::  completeEnsayo : 3"
					);

					if(!$dbS->didQuerydied){
						$dbS->squery(
							"UPDATE
								ensayoViga
							SET
								fecha = CURDATE(),
								status = 1
							WHERE
								id_ensayoViga = 1QQ
							",array($id_ensayoViga),
							"UPDATE -- EnsayoViga ::  completeEnsayo : 4"
						);
						if(!$dbS->didQuerydied){
							$dbS->commitTransaction();
							$arr = array('ensayoViga' => $id_ensayoCilindro,'estatus' => '¡Ensayo completado!','error' => 0);
							return json_encode($arr);
						}
						else{
							$dbS->rollbackTransaction();
							$arr = array('ensayoViga' => 'NULL','token' => $token,	'estatus' => 'Error en la actualizacion del ensayoViga, verifica tus datos y vuelve a intentarlo','error' => 13);
							return json_encode($arr);	
						}
					}else{
						$dbS->rollbackTransaction();
						$arr = array('ensayoViga' => 'NULL','token' => $token,	'estatus' => 'Error en la actualizacion del registroCCH, verifica tus datos y vuelve a intentarlo','error' => 12);
						return json_encode($arr);
					}
				}else{
					$dbS->rollbackTransaction();
					$arr = array('ensayoViga' => 'NULL','token' => $token,	'estatus' => 'Error en la consulta, verifica tus datos y vuelve a intentarlo','error' => 10);
					return json_encode($arr);
				}		
		}
		return json_encode($arr);
	}

	public function calcularModulo($token,$rol_usuario_id,$id_ensayoViga){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->beginTransaction();
			$var_system = $dbS->qarrayA(
					"   SELECT
							ensayo_def_distanciaApoyos
						FROM
							systemstatus
						ORDER BY id_systemstatus DESC;
					",array(),"SELECT -- EnsayoViga :: calcularModulo : 1"
					);
			if(!$dbS->didQuerydied){
				$variables = $dbS->qarrayA(
					"   SELECT
							carga,
							ancho1,
							ancho2,
							per1,
							per2,
							disApoyo,
							posFractura,
							l1,
							l2,
							l3
						FROM
							ensayoViga
						WHERE 
							id_ensayoViga  = 1QQ
					",array($id_ensayoViga),
					"SELECT -- EnsayoViga :: calcularModulo : 2"
					);
				if(!$dbS->didQuerydied){
					
					$promedioAncho = ($variables['ancho1'] + $variables['ancho2'])/2;
					$promedioPer = ($variables['per1'] + $variables['per2'])/2;
					$area = ($promedioAncho * ($promedioPer * $promedioPer));
					$prom = $variables['l1']*$variables['l2']*$variables['l3'];
					if($area != 0){
						if($variables['posFractura'] == 1){ // Dentro del Claro
							$modulo = number_format(($variables['carga']*$variables['disApoyo'])/$area,2);
							$error = 0;
						}else if($variables['posFractura'] == 2){  // Fuera del Claro
							$modulo = number_format((3*$variables['carga']*$prom)/$area,2);
							$error = 0;
						}
					}else{
						$modulo = "Error: division entre cero." ;
						$error = 0;
					}
					if($error == 0){
						$dbS->squery(
							"UPDATE
								ensayoViga
							SET
								fecha = CURDATE(),
								mr = '1QQ'
							WHERE
								id_ensayoViga = 1QQ
							",array($modulo,$id_ensayoViga),
							"UPDATE -- EnsayoViga ::  calcularModulo : 3"
						);
						if($dbS->didQuerydied){
							$dbS->rollbackTransaction();
							$arr = array('estatus' => 'No se pudieron cargar las variables del registro.','error' => 40);
						}else{
							$arr = array('area' => $area,'modulo' => $modulo, 'error'=> $error);
							$dbS->commitTransaction();
						}
					}else{
						$dbS->rollbackTransaction();
						$arr = array('area' => $area,'modulo' => $modulo, 'error'=> $error);
					}
				}
				else{
					$dbS->rollbackTransaction();
					$arr = array(' ' => 'No se pudieron cargar las variables del registro.','error' => 6);
				}	
			}else{
				$dbS->rollbackTransaction();
				$arr = array('estatus' => 'No se pudieron cargar las constantes del sistema.','error' => 7);
			}
		}
		return json_encode($arr);
	}

	public function calcularPromedio($token,$rol_usuario_id,$id_ensayoViga){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->beginTransaction();
			$variables = $dbS->qarrayA(
					"   SELECT
							l1,
							l2,
							l3
						FROM
							ensayoViga
						WHERE 
							id_ensayoViga  = 1QQ
					",array($id_ensayoViga),"SELECT -- EnsayoViga :: calcularPromedio : 1"
					);
			if(!$dbS->didQuerydied){
				$dbS->commitTransaction();
				$promedio = number_format(($variables['l1'] + $variables['l2'] + $variables['l3'])/3,2);
				$dbS->squery(
					"UPDATE
						ensayoViga
					SET
						fecha = CURDATE(),
						prom = '1QQ'
					WHERE
						id_ensayoViga = 1QQ
					",array($promedio,$id_ensayoViga),
					"UPDATE -- EnsayoViga ::  calcularPromedio : 2"
				);
				if($dbS->didQuerydied){
					$dbS->rollbackTransaction();
					$arr = array('estatus' => 'No se pudieron cargar las variables del registro.','error' => 40);
				}else{
					$dbS->commitTransaction();
					$arr = array('promedio' => $promedio,'error' => 0);
				}
			}else{
				$dbS->rollbackTransaction();
				$arr = array('estatus' => 'No se pudieron cargar las variables del registro.','error' => 6);
			}
		}
		return json_encode($arr);
	}
	public function calcularVelocidad($token,$rol_usuario_id,$id_ensayoViga){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->beginTransaction();
			$variables = $dbS->qarrayA(
				"	SELECT
						mr,
						tiempoDeCarga
					FROM
						ensayoViga
					WHERE 
						id_ensayoViga  = 1QQ
				",array($id_ensayoViga),"SELECT -- EnsayoViga :: calcularVelocidad : 1"
			);
			if(!$dbS->didQuerydied){
			
				$velAplicacionExp = ($variables['mr'])/$variables['tiempoDeCarga'];
				
				$dbS->squery(
					"UPDATE
						ensayoViga
					SET
						fecha = CURDATE(),
						velAplicacionExp = '1QQ'
					WHERE
						id_ensayoViga = 1QQ
					",array($velAplicacionExp,$id_ensayoViga),
					"UPDATE -- EnsayoViga ::  calcularVelocidad : 2"
				);
				if($dbS->didQuerydied){
					$dbS->rollbackTransaction();
					$arr = array('estatus' => 'No se pudieron cargar las variables del registro.','error' => 40);
				}else{
					$dbS->commitTransaction();
					$arr = array('velAplicacionExp' => $velAplicacionExp,'error' => 0);
				}
			}else{
				$dbS->rollbackTransaction();
				$arr = array('estatus' => 'No se pudieron cargar las variables del registro.','error' => 6);
			}
		}
		return json_encode($arr);
	}
}
?>