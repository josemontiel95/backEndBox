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
	
	//Añadimos a que obra esta agendada???? PENDIENTE
	public function getAllHerraAvailable($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      SELECT 
			        id_herramienta,
					herramienta_tipo_id,
					fechaDeCompra,
					placas,
					condicion,
					tipo,
					herramientas.observaciones,
					herramientas.createdON,
					herramientas.lastEditedON,
					estado_herramienta.estado
				FROM 
			        herramienta_tipo,
					herramientas,
					(
						SELECT
							herramienta_id,
							CASE
		    					WHEN herramienta_ordenDeTrabajo.active = 0 AND CURDATE()>ordenDeTrabajo.fechaInicio THEN 'Completado'
		    					WHEN herramienta_ordenDeTrabajo.active = 1 AND CURDATE()<ordenDeTrabajo.fechaInicio THEN 'Agendado'
		    					ELSE 'Error'
						END AS estado

						FROM
							herramienta_ordenDeTrabajo,
							ordenDeTrabajo
						WHERE
							ordenDeTrabajo_id = id_ordenDeTrabajo

					) AS estado_herramienta
				
				WHERE
			      	 id_herramienta_tipo = herramienta_tipo_id AND
			      	 herramientas.active = 1 AND 
			      	 estado_herramienta.herramienta_id = id_herramienta AND
			      	 (estado = 'Completado' OR estado = 'Agendado')
			      ",
			      array(),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($arr == "empty")
					$arr = array('estatus' =>"No hay registros", 'error' => 5); 
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query , verifica tus datos y vuelve a intentarlo','error' => 6);	
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

	
	public function getAllAdmin($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      		SELECT 
							id_ordenDeTrabajo,
							cotizacion_id,
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
							usuario.nombre AS nombre_jefe_brigada_id,
							jefe_brigada_id,
							jefa.nombre AS nombre_jefa_lab_id,
							ordenDeTrabajo.jefa_lab_id
						from
							usuario,ordenDeTrabajo,obra,laboratorio,
							(SELECT 

									jefa_lab_id,
									nombre 

							FROM

									usuario,
									ordenDeTrabajo

							WHERE

								id_usuario = jefa_lab_id) AS jefa
						WHERE
							obra_id = id_obra AND
							ordenDeTrabajo.laboratorio_id = id_laboratorio AND
							id_usuario = jefe_brigada_id AND
							jefa.jefa_lab_id = ordenDeTrabajo.jefa_lab_id
			      ",
			      array(),
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


	public function insertAdmin($token,$rol_usuario_id,$cotizacion_id,$obra_id,$actividades,$condicionesTrabajo,$fechaInicio,$fechaFin,$horaInicio,$horaFin,$observaciones,$lugar,$jefa_lab_id,$jefe_brigada_id,$laboratorio_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						ordenDeTrabajo(cotizacion_id,obra_id,actividades,condicionesTrabajo,fechaInicio,fechaFin,horaInicio,horaFin,observaciones,lugar,jefa_lab_id,jefe_brigada_id,laboratorio_id)

						VALUES
						(1QQ,1QQ,'1QQ','1QQ','1QQ','1QQ','1QQ','1QQ','1QQ','1QQ','1QQ',1QQ,1QQ)
				",array($cotizacion_id,$obra_id,$actividades,$condicionesTrabajo,$fechaInicio,$fechaFin,$horaInicio,$horaFin,$observaciones,$lugar,$jefa_lab_id,$jefe_brigada_id,$laboratorio_id),"INSERT");
			if(!$dbS->didQuerydied){
				$arr = array('id_ordenDeTrabajo' => 'No disponible, esto NO es un error','estatus' => 'Exito en insercion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	public function upDateAdmin($token,$rol_usuario_id,$id_ordenDeTrabajo,$cotizacion_id,$obra_id,$actividades,$condicionesTrabajo,$fechaInicio,$fechaFin,$horaInicio,$horaFin,$observaciones,$lugar,$jefa_lab_id,$jefe_brigada_id,$laboratorio_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							ordenDeTrabajo
						SET
							cotizacion_id = 1QQ,
							obra_id = 1QQ,
							actividades = '1QQ', 
							condicionesTrabajo = '1QQ',
							fechaInicio = '1QQ',
							fechaFin = '1QQ',
							horaInicio = '1QQ',
							horaFin = '1QQ',
							observaciones = '1QQ',
							lugar = '1QQ',
							jefa_lab_id = 1QQ,
							jefe_brigada_id = 1QQ,
							laboratorio_id = 1QQ
						WHERE
							id_ordenDeTrabajo = 1QQ
					 "
					,array($cotizacion_id,$obra_id,$actividades,$condicionesTrabajo,$fechaInicio,$fechaFin,$horaInicio,$horaFin,$observaciones,$lugar,$jefa_lab_id,$jefe_brigada_id,$laboratorio_id,$id_ordenDeTrabajo),"UPDATE"
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
							cotizacion_id,
							actividades,
							condicionesTrabajo,
							fechaInicio,
							fechaFin,
							horaInicio,
							horaFin,
							observaciones,

							obra_id,
							obra.obra,
							lugar,
							ordenDeTrabajo.laboratorio_id,
							laboratorio,
							usuario.nombre AS nombre_jefe_brigada_id,
							jefe_brigada_id,
							jefa.nombre AS nombre_jefa_lab_id,
							ordenDeTrabajo.jefa_lab_id
						from
							usuario,ordenDeTrabajo,obra,laboratorio,
							(SELECT 

									jefa_lab_id,
									nombre 

							FROM

									usuario,
									ordenDeTrabajo

							WHERE

								id_usuario = jefa_lab_id) AS jefa
						WHERE
							id_ordenDeTrabajo = 1QQ AND
							obra_id = id_obra AND
							ordenDeTrabajo.laboratorio_id = id_laboratorio AND
							id_usuario = jefe_brigada_id AND
							jefa.jefa_lab_id = ordenDeTrabajo.jefa_lab_id
			      ",
			      array($id_ordenDeTrabajo),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					return "empty";
				}
				else{
					return json_encode($s);
				}
			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query, verifica tus datos y vuelve a intentarlo','error' => 2);
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