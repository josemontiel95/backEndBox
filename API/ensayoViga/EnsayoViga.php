<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class EnsayoViga{


	
	
	/* Variables de utilería */
	private $wc = '/1QQ/';

	public function insertRegistroTecMuestra($token,$rol_usuario_id,$campo,$valor,$id_ensayoViga){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			switch ($campo) {
				case '1':
					$campo = 'condiciones';
					break;
				case '2':
					$campo = 'lijado';
					break;
				case '3':
					$campo = 'cuero';
					break;
				case '4':
					$campo = 'ancho1';
					break;
				case '5':
					$campo = 'ancho2';
					break;
				case '6':
					$campo = 'per1';
					break;
				case '7':
					$campo = 'per2';
					break;
				case '8':
					$campo = 'l1';
					break;
				case '9':
					$campo = 'l2';
					break;
				case '10':
					$campo = 'l3';
					break;
				case '11':
					$campo = 'disApoyo';
					break;
				case '12':
					$campo = 'disCarga';
					break;
				case '13':
					$campo = 'carga';
					break;

			}

			$dbS->squery("
						UPDATE
							ensayoViga
						SET
							1QQ = '1QQ'
						WHERE
							id_ensayoViga = 1QQ
				",array($campo,$valor,$id_ensayoViga),"UPDATE");
			$arr = array('estatus' => 'Exito en insercion', 'error' => 0);
			if(!$dbS->didQuerydied){
				$arr = array('id_ensayoViga' => $id_ensayoViga,'estatus' => '¡Exito en la inserccion de un registro!','error' => 0);
				return json_encode($arr);
			}else{
				$arr = array('id_ensayoViga' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
				return json_encode($arr);
			}
		}
		return json_encode($arr);
	}

	
	public function getRegistrosCilByID($token,$rol_usuario_id,$id_ensayoViga){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s = $dbS->qarrayA("
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

	//Aun no se realizan los calculos por falta de informacion por parte de gabino
	public function calcularAreaResis($token,$rol_usuario_id,$id_ensayoViga){
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
							ensayoViga
						WHERE 
							id_ensayoViga  = 1QQ
					",array($id_ensayoViga),"SELECT"
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
	public function getRegistrosByID($token,$rol_usuario_id,$id_ensayoViga){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			    	SELECT
						id_ensayoViga,
						footerEnsayo_id,
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
						registrosCampo_id,
						claveEspecimen,
						fecha,
						diasEnsaye,
						ensayoViga.formatoCampo_id,
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
						ensayoViga,registrosCampo,formatoCampo
					WHERE
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
										"
											SELECT
												registrosCampo_id
											FROM
												ensayoViga
											WHERE
												id_ensayoViga = 1QQ
										",
										array($id_ensayoViga),
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
						$dbS->commitTransaction();
						$arr = array('id_ensayoViga' => $id_ensayoViga,'estatus' => '¡Ensayo completado!','error' => 0);
						return json_encode($arr);
					}else{
						$dbS->rollbackTransaction();
						$arr = array('id_ensayoViga' => 'NULL','token' => $token,	'estatus' => 'Error en la actualizacion, verifica tus datos y vuelve a intentarlo','error' => 5);
						return json_encode($arr);
					}	
				}
				else{
					$dbS->rollbackTransaction();
					$arr = array('id_ensayoViga' => 'NULL','token' => $token,	'estatus' => 'Error en la consulta, verifica tus datos y vuelve a intentarlo','error' => 5);
					return json_encode($arr);
				}		
		}
		return json_encode($arr);
	}
}
?>