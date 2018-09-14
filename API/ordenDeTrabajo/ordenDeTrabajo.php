<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class ordenDeTrabajo{
	private $id_ordenDeTrabajo;
	private $obra_id;
	private $fecha;
	private $hora;
	private $lugar;
	private $jefa_lab_id;
	private $jefe_brigada_id;
	private $laboratorio_id;

	/* Variables de utilería */
	private $wc = '/1QQ/';

	public function getAllFormatos($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arrayCCH = $dbS->qAll(
							"
								SELECT
									id_formatoCampo AS id_formato,
									informeNo AS formatoNo,
									IF(informeNo IS NOT NULL,'CONTROL DE CONCRETO HIDRAULICO','ERROR')AS tipo
								FROM
									formatoCampo
								WHERE
									ordenDeTrabajo_id = 1QQ
							",
							array($id_ordenDeTrabajo),
							"SELECT"
						);
			
			if(!$dbS->didQuerydied){
				$arrayRev = $dbS->qAll(
							"
								SELECT
									id_formatoRegistroRev AS id_formato,
									regNo AS formatoNo,
									IF(regNo IS NOT NULL,'REVENIMIENTO','ERROR')AS tipo
								FROM
									formatoRegistroRev
								WHERE
									ordenDeTrabajo_id = 1QQ
							",
							array($id_ordenDeTrabajo),
							"SELECT"
						);
				if(!$dbS->didQuerydied){
					if($arrayRev != "empty" && $arrayCCH != "empty"){
						$arr = array_merge($arrayCCH,$arrayRev);
					}
					else{
						if($arrayRev == "empty" && $arrayCCH == "empty"){
							$arr = array('estatus' =>"No hay registros", 'error' => 5); 
						}
						else{
							if($arrayRev == "empty"){
								return json_encode($arrayCCH);
							}
							else{
								return json_encode($arrayRev);
							}
						}
					}						
				}
				else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query REV, verifica tus datos y vuelve a intentarlo','error' => 6);	
				}
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query de CCH, verifica tus datos y vuelve a intentarlo','error' => 6);	
			}		
		}
		return json_encode($arr);


	}
	
	
	
	public function getForDroptdownAdmin($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      SELECT 
			      	id_ordenDeTrabajo,
			      	obra
			      FROM 
			        obra,ordenDeTrabajo
			       WHERE
			       	obra_id = id_obra AND
			       	ordenDeTrabajo.active = 1
			      ORDER BY 
			      	obra
			      ",
			      array(),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($arr == "empty")
					$arr = array('estatus' =>"No hay registros", 'error' => 5); //Pendiente
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query , verifica tus datos y vuelve a intentarlo','error' => 6);	
			}
		}
		return json_encode($arr);
	}

	/*
	Esta funcion es llamada desde las siguientes rutas en el Front:
		-jefelab/orden-trabajo/orden-trabajo.component.ts
		-
	*/

	public function getAllAdmin($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id= $usuario->laboratorio_id;
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      		SELECT 
							id_ordenDeTrabajo,
							jefa_lab_id,
							obra_id,
							obra.obra,
							creadores.nombre AS CreadoPorJefaLab,
							actividades,
							condicionesTrabajo,
							fechaInicio,
							fechaFin,
							horaInicio,
							horaFin,
							observaciones,
							lugar,
							ordenDeTrabajo.laboratorio_id,
							laboratorio,
							usuario.nombre AS nombre_jefe_brigada_id,
							jefe_brigada_id,
							IF(ordenDeTrabajo.active = 1,'Si','No') AS active,
							ordenDeTrabajo.active AS activeColor
						from
							usuario,ordenDeTrabajo,obra,laboratorio,
							(SELECT id_usuario, nombre FROM usuario) AS creadores
						WHERE
							obra_id = id_obra AND
							ordenDeTrabajo.laboratorio_id = id_laboratorio AND
							jefe_brigada_id = usuario.id_usuario AND
							jefa_lab_id = creadores.id_usuario AND 
							ordenDeTrabajo.laboratorio_id = 1QQ
			      ",
			      array($laboratorio_id),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
						if($arr == "empty")
							$arr = array('estatus' =>"No hay registros", 'error' => 5); 
						
			}else
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en el query, verifica tus datos y vuelve a intentarlo','error' => 6);
		}
		return json_encode($arr);	
	}

	public function getAllByJefeBrigada($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      		SELECT 
							id_ordenDeTrabajo,
							jefa_lab_id,
							obra_id,
							obra.obra,

							actividades,
							condicionesTrabajo,
							fechaInicio,
							fechaFin,
							horaInicio,
							horaFin,
							observaciones,
							lugar,

							ordenDeTrabajo.laboratorio_id,
							laboratorio,
							jefe_brigada_id,
							IF(ordenDeTrabajo.active = 1,'Si','No') AS active
						from
							ordenDeTrabajo,obra,laboratorio
						WHERE
							obra_id = id_obra AND
							ordenDeTrabajo.laboratorio_id = id_laboratorio AND
							jefe_brigada_id = 1QQ
			      ",
			      array($arr['id_usuario']),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
						if($arr == "empty")
							$arr = array('estatus' =>"No hay registros", 'error' => 5); 
						
			}else
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en el query, verifica tus datos y vuelve a intentarlo','error' => 6);
		}
		return json_encode($arr);	

	}


	public function insertAdmin($token,$rol_usuario_id,$area,$obra_id,$actividades,$condicionesTrabajo,$fechaInicio,$fechaFin,$horaInicio,$horaFin,$observaciones,$lugar,$jefe_brigada_id,$laboratorio_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$id_JefaLab=$usuario->id_usuario;
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						ordenDeTrabajo(jefa_lab_id,area,obra_id,actividades,condicionesTrabajo,fechaInicio,fechaFin,horaInicio,horaFin,observaciones,lugar,jefe_brigada_id,laboratorio_id)

						VALUES
						('1QQ','1QQ',1QQ,'1QQ','1QQ','1QQ','1QQ','1QQ','1QQ','1QQ','1QQ',1QQ,1QQ)
				",array($id_JefaLab,$area,$obra_id,$actividades,$condicionesTrabajo,$fechaInicio,$fechaFin,$horaInicio,$horaFin,$observaciones,$lugar,$jefe_brigada_id,$laboratorio_id),"INSERT");
			if(!$dbS->didQuerydied){
				$arr = array('id_ordenDeTrabajo' => 'No disponible, esto NO es un error','estatus' => 'Exito en insercion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}
	public function insertJefeLabo($token,$rol_usuario_id,$area,$obra_id,$actividades,$condicionesTrabajo,$fechaInicio,$fechaFin,$horaInicio,$horaFin,$observaciones,$lugar,$jefe_brigada_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$id_JefaLab=$usuario->id_usuario;

		$a=$dbS->qarrayA("
			SELECT 
				laboratorio_id
			FROM 
				obra
			WHERE 
				id_obra=1QQ
		",array($obra_id),"SELECT");

		if(!$dbS->didQuerydied && !($a=="empty")){
			if($arr['error'] == 0){
				$dbS->squery("
							INSERT INTO
								ordenDeTrabajo(jefa_lab_id,area,obra_id,actividades,condicionesTrabajo,fechaInicio,fechaFin,horaInicio,horaFin,observaciones,lugar,jefe_brigada_id,laboratorio_id)
							VALUES
							('1QQ','1QQ',1QQ,'1QQ','1QQ','1QQ','1QQ','1QQ','1QQ','1QQ','1QQ',1QQ,1QQ)
					",array($id_JefaLab,$area,$obra_id,$actividades,$condicionesTrabajo,$fechaInicio,$fechaFin,$horaInicio,$horaFin,$observaciones,$lugar,$jefe_brigada_id,$a['laboratorio_id']),"INSERT");
				if(!$dbS->didQuerydied){
					$arr = array('id_ordenDeTrabajo' => $dbS->lastInsertedID,'estatus' => 'Exito en insercion', 'error' => 0);
				}
				else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
				}
			}
		}else{
			$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en el sistema, contacta a soporte','error' => 6);
		}
		return json_encode($arr);
	}

	public function upDateAdmin($token,$rol_usuario_id,$id_ordenDeTrabajo,$area,$obra_id,$actividades,$condicionesTrabajo,$fechaInicio,$fechaFin,$horaInicio,$horaFin,$observaciones,$lugar,$jefe_brigada_id,$laboratorio_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							ordenDeTrabajo
						SET
							area = '1QQ',
							obra_id = 1QQ,
							actividades = '1QQ', 
							condicionesTrabajo = '1QQ',
							fechaInicio = '1QQ',
							fechaFin = '1QQ',
							horaInicio = '1QQ',
							horaFin = '1QQ',
							observaciones = '1QQ',
							lugar = '1QQ',
							jefe_brigada_id = 1QQ,
							laboratorio_id = 1QQ
						WHERE
							id_ordenDeTrabajo = 1QQ
					 "
					,array($area,$obra_id,$actividades,$condicionesTrabajo,$fechaInicio,$fechaFin,$horaInicio,$horaFin,$observaciones,$lugar,$jefe_brigada_id,$laboratorio_id,$id_ordenDeTrabajo),"UPDATE"
			      	);
			if(!$dbS->didQuerydied){
				$arr = array('id_ordenDeTrabajo' => 'No disponible, esto NO es un error','estatus' => 'Exito en actualizacion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	public function updateJefeLabo($token,$rol_usuario_id,$area,$id_ordenDeTrabajo,$obra_id,$lugar,$actividades,$condicionesTrabajo,$jefe_brigada_id,$fechaInicio,$fechaFin,$horaInicio,$horaFin,$observaciones){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							ordenDeTrabajo
						SET
							area = '1QQ',
							obra_id = '1QQ', 
							lugar = '1QQ',
							actividades = '1QQ',
							condicionesTrabajo = '1QQ',
							jefe_brigada_id = '1QQ',
							fechaInicio = '1QQ',
							fechaFin = '1QQ',
							horaInicio = '1QQ',
							horaFin = '1QQ',
							observaciones = '1QQ'
						WHERE
							id_ordenDeTrabajo = 1QQ
					 "
					,array($area,$obra_id,$lugar,$actividades,$condicionesTrabajo,$jefe_brigada_id,$fechaInicio,$fechaFin,$horaInicio,$horaFin,$observaciones,$id_ordenDeTrabajo),"UPDATE"
			      	);
			if(!$dbS->didQuerydied){
				$arr = array('id_ordenDeTrabajo' => 'No disponible, esto NO es un error','estatus' => 'Exito en actualizacion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	
	public function getByIDAdmin($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			        SELECT 
							id_ordenDeTrabajo,
							actividades,
							condicionesTrabajo,
							fechaInicio,
							fechaFin,
							horaInicio,
							horaFin,
							observaciones,
							id_cliente,
							razonSocial,
							cliente.nombre,
							nombreContacto,
							telefonoDeContacto,
							obra_id,
							obra.obra,
							ordenDeTrabajo.status,
							lugar,
							area,
							ordenDeTrabajo.laboratorio_id,
							laboratorio,
							usuario.nombre AS nombre_jefe_brigada_id,
							jefe_brigada_id,
							IF(ordenDeTrabajo.active = 1,'Si','No') AS active
						from
							usuario,ordenDeTrabajo,obra,laboratorio,cliente
						WHERE
							obra_id = id_obra AND
							ordenDeTrabajo.laboratorio_id = id_laboratorio AND
							id_usuario = jefe_brigada_id AND
							obra.cliente_id = id_cliente AND
							id_ordenDeTrabajo = 1QQ
			      ",
			      array($id_ordenDeTrabajo),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('id_ordenDeTrabajo' => $id_ordenDeTrabajo,'estatus' => 'Error no se encontro ese id','error' => 5);
				}
				else{
					return json_encode($s);
				}
			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query, verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);
	}

	public function deactivate($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							ordenDeTrabajo
						SET
							active = 1QQ
						WHERE
							active=1 AND
							id_ordenDeTrabajo = 1QQ
					 "
					,array(0,$id_ordenDeTrabajo),"UPDATE"
			      	);
		//PENDIENTE por la herramienta_tipo_id para poderla imprimir tengo que cargar las variables de la base de datos?
			if(!$dbS->didQuerydied){
				$arr = array('id_ordenDeTrabajo' => $id_ordenDeTrabajo,'estatus' => 'Orden de Trabajo se desactivo','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la desactivacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}

		}
		return json_encode($arr);
	}

	public function activate($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							ordenDeTrabajo
						SET
							active = 1QQ
						WHERE
							active=0 AND
							id_ordenDeTrabajo = 1QQ
					 "
					,array(1,$id_ordenDeTrabajo),"UPDATE"
			      	);
			if(!$dbS->didQuerydied){
				$arr = array('id_ordenDeTrabajo' => $id_ordenDeTrabajo,'estatus' => 'Orden de Trabajo se activo','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la activacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}









}





?>