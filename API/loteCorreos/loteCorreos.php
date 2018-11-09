<?php 

/*

	Definimos la configuracion de la zona horaria
	-Direcctorio de zonas de America: http://php.net/manual/es/timezones.america.php

*/
date_default_timezone_set('America/Mexico_City');

include_once("./../../usuario/Usuario.php");
include_once("./../../mailer/Mailer.php");
include_once("./../../mailer/sendgrid-php/sendgrid-php.php");
include_once("./../../generadorFormatos/GeneradorFormatos.php");

class loteCorreos{
	private $id_formatoCampo;
	private $informeNo;
	private $ordenDeServicio_id;
	private $observaciones;
	private $mr;
	private $tipo;


	private $wc = '/1QQ/';


	public function getAllAdmin($token,$rol_usuario_id,$status){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			     	SELECT
						lc.id_loteCorreos,
						lc.creador_id,
						CONCAT(u.nombre,' ',u.apellido) AS encargado,
						CASE 
							WHEN lc.status = 0 THEN 'Disponible'
							WHEN lc.status = 1 THEN 'Cerrado'
							WHEN lc.status = 2 THEN 'Enviados'
							ELSE 'Error, contacte a soporte'
						END AS estado,
						lc.status
					FROM
						loteCorreos AS lc,
						usuario AS u
					WHERE
						lc.creador_id = u.id_usuario AND 
						lc.status < 1QQ AND
						laboratorio_id = 1QQ
					ORDER BY 
						lc.createdON
			      ",
			      array($status,$laboratorio_id),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($arr == "empty"){
					$arr = array('estatus' =>"No hay registros", 'error' => 5); 
				}		
			}else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en el query, verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);	
	}

	public function agregaFormatos($token,$rol_usuario_id,$lote,$formatosSeleccionados){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$id_usuario=$usuario->id_usuario;
		$dbS->beginTransaction();
		$formatosSeleccionados=json_decode($formatosSeleccionados);
		if($arr['error'] == 0){
			if($lote == -1){ 					// No hay lote, crerar uno nuevo
				$dbS->squery(
					"
					INSERT INTO 
						loteCorreos(
							creador_id
						)
					VALUES
						(
							1QQ
						)
					"
					,
					array($id_usuario)
					,
					"INSERT -- loteCorreos :: agregaFormatos"
				);
				if(!$dbS->didQuerydied){ // Todo va bien, obtendremos id del lote y continuamos
					//Creamos el informe No.
					$lote=$dbS->lastInsertedID;
				}else{
					$dbS->rollbackTransaction();
					$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la consulta, verifica tus datos y vuelve a intentarlo','error' => 7);
					return json_encode($arr);
				}
			}
			$no=0;
			foreach ($formatosSeleccionados as $value) {
				$no=$no+1;
				$dbS->squery(
					"
					INSERT INTO 
						correoDeLote(
							loteCorreos_id,
							formatoCampo_id
						)
					VALUES
						(
							1QQ,
							1QQ
						)
					"
					,
					array($lote,$value)
					,
					"INSERT -- loteCorreos :: agregaFormatos"
				);
				if($dbS->didQuerydied){
					$dbS->rollbackTransaction();
					$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 12);
					return json_encode($arr);				
				}
				$dbS->squery(
					"
					UPDATE
						formatoCampo
					SET
						loteStatus= loteStatus + 1
					WHERE
						id_formatoCampo = 1QQ
					"
					,
					array($value)
					,
					"UPDATE -- loteCorreos :: agregaFormatos"
				);
				if($dbS->didQuerydied){
					$dbS->rollbackTransaction();
					$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 13);
					return json_encode($arr);				
				}
			}
			$dbS->squery(
				"
				UPDATE
					loteCorreos
				SET
					correosNo= correosNo + 1QQ
				WHERE
					id_loteCorreos = 1QQ
				"
				,
				array($no,$lote)
				,
				"UPDATE -- loteCorreos :: agregaFormatos"
			);
			if($dbS->didQuerydied){
				$dbS->rollbackTransaction();
				$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 11);
				return json_encode($arr);				
			}
			$dbS->commitTransaction();
			$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Exito! ','error' => 0);
		}
		return json_encode($arr);
	}

	public function getLoteByID($token,$rol_usuario_id,$lote){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$arr= $dbS->qarrayA("
			     	SELECT
						lc.id_loteCorreos,
						lc.creador_id,
						CONCAT(u.nombre,' ',u.apellido) AS encargado,
						IF(lc.status = 0, 'Pendiente', 'Completado') AS estado,
						lc.status,
						lc.correosNo,
						DATE(lc.createdON) fecha
					FROM
						loteCorreos AS lc,
						usuario AS u
					WHERE
						lc.creador_id = u.id_usuario AND 
						lc.id_loteCorreos =1QQ
			      ",
			      array($lote),
			      "SELECT -- loteCorreos :: getLoteByID"
			      );

			if(!$dbS->didQuerydied){
				if($arr == "empty"){
					$arr = array('estatus' =>"No hay registros", 'error' => 5); 
				}		
			}else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en el query, verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);
	}



	public function generateAllFormatosByLote($token,$rol_usuario_id,$lote){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			     	SELECT
						cl.formatoCampo_id AS formatoCampo_id,
						cl.id_correoDeLote AS id_correoDeLote
					FROM
						correoDeLote AS cl
					WHERE
						cl.status = 0 AND
						cl.loteCorreos_id = 1QQ	        
			      ",
			      array($lote),
			      "SELECT -- loteCorreos :: generateAllFormatosByLote"
			      );
			if($dbS->didQuerydied){
				$dbS->rollbackTransaction();
				$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 15);
				return json_encode($arr);				
			}
			$var_system = $dbS->qarrayA(
			"
				SELECT
					apiRoot
				FROM
					systemstatus
				ORDER BY id_systemstatus DESC;
			",array(),"SELECT -- loteCorreos :: generateAllFormatosByLote"
			);
			if($dbS->didQuerydied){
				$dbS->rollbackTransaction();
				$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 16);
				return json_encode($arr);				
			}
			if(!$dbS->didQuerydied && !($arr=="empty")){
				foreach ($arr as $value) {
					
					$hora_de_creacion = getdate();
					$target_dir = "./../../../SystemData/FormatosFINALES/".$lote."/".$value['formatoCampo_id']."/";
					$dirDatabase = $var_system['apiRoot']."SystemData/FormatosFINALES/".$lote."/".$value['formatoCampo_id']."/"."ReporteDeConcreto"."(".$hora_de_creacion['hours']."-".$hora_de_creacion['minutes']."-".$hora_de_creacion['seconds'].")".".pdf";
					if (!file_exists($target_dir)) {
					    mkdir($target_dir, 0777, true);
					}
					$target_dir=$target_dir."ReporteDeConcreto"."(".$hora_de_creacion['hours']."-".$hora_de_creacion['minutes']."-".$hora_de_creacion['seconds'].")".".pdf";
					//Llamada a el generador de formatos
					$generador = new GeneradorFormatos();
					//Cachamos la excepcion
					try{
						$generador->generateInformeCampo($token,$rol_usuario_id,$value['formatoCampo_id'],$target_dir);
						$dbS->squery(
							"
							UPDATE 
								correoDeLote 
							SET 
								pdf= '1QQ',
								status = status +1
							WHERE
								id_correoDeLote = 1QQ
							"
							,
							array($dirDatabase,$value['id_correoDeLote'])
							,
							"UPDATE -- loteCorreos :: generateAllFormatosByLote"
						);
						if($dbS->didQuerydied){
							$dbS->rollbackTransaction();
							$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 10);
							return json_encode($arr);				
						}
						
					}catch(Exception $e){
						$dbS->rollbackTransaction();
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la generacion del formato:'.$e->getMessage(),'error' => 11);
						return json_encode($arr);
					}
				}
				$dbS->squery(
					"
					UPDATE 
						loteCorreos 
					SET 
						status= status+1
					WHERE
						id_loteCorreos = 1QQ
					",
					array($lote),
					"UPDATE -- loteCorreos :: generateAllFormatosByLote"
				);
				if($dbS->didQuerydied){
					$dbS->rollbackTransaction();
					$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 13);
					return json_encode($arr);				
				}

				$dbS->commitTransaction();
				$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Exito! ','error' => 0);
			}else{
				if($arr=="empty"){
					$arr = array('lote' => $lote, 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la generacion del formato:','error' => 12);
				}else{
					$arr = array('lote' => $lote, 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la generacion del formato:','error' => 14);
				}
				$dbS->rollbackTransaction();
				return json_encode($arr);
			}
		}
		return json_encode($arr);	
	}
	public function getAllFormatos($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			     	SELECT
						fc.id_formatoCampo,
						lc.id_loteCorreos,
						fc.informeNo,
						fc.tipo,
						o.cotizacion,
						c.razonSocial,
						o.obra,
						cl.status,
						fc.ensayadoFin,
						cl.pdf AS link,
						c.email AS emailCliente,
						o.correo_residente AS emailResidente,
						IF(cl.pdf IS NULL, 'No','Si') AS PDF
					FROM
						formatoCampo AS fc,
						ordenDeTrabajo AS ot,
						obra AS o,
						cliente AS c,
						loteCorreos AS lc,
						correoDeLote AS cl
					WHERE
						fc.ordenDeTrabajo_id = ot.id_ordenDeTrabajo AND
						ot.obra_id = o.id_obra AND
						o.cliente_id = c.id_cliente AND
						lc.id_loteCorreos = cl.loteCorreos_id AND 
						cl.formatoCampo_id = fc.id_formatoCampo AND
						lc.status = 2  AND
						o.laboratorio_id = 1QQ
					ORDER BY 
						fc.lastEditedON DESC
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

	public function getAllFormatosByLote($token,$rol_usuario_id,$lote){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			     	SELECT
						fc.id_formatoCampo,
						fc.informeNo,
						fc.observaciones,
						fc.tipo,
						o.cotizacion,
						c.razonSocial,
						o.obra,
						cl.status,
						fc.ensayadoFin,
						cl.pdf AS link,
						c.email AS emailCliente,
						o.correo_residente AS emailResidente,
						IF(cl.pdf IS NULL, 'No','Si') AS PDF
					FROM
						formatoCampo AS fc,
						ordenDeTrabajo AS ot,
						obra AS o,
						cliente AS c,
						loteCorreos AS lc,
						correoDeLote AS cl
					WHERE
						fc.ordenDeTrabajo_id = ot.id_ordenDeTrabajo AND
						ot.obra_id = o.id_obra AND
						o.cliente_id = c.id_cliente AND
						lc.id_loteCorreos = cl.loteCorreos_id AND 
						cl.formatoCampo_id = fc.id_formatoCampo AND 
						lc.id_loteCorreos = 1QQ
					ORDER BY 
						fc.lastEditedON DESC	        

			      ",
			      array($lote),
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


	public function sentAllEmailFormatosByLote($token,$rol_usuario_id,$lote){
		global $dbS;
		$usuario = new Usuario();
		$mailer = new Mailer();

		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			     	SELECT
						cl.formatoCampo_id AS formatoCampo_id,
						cl.id_correoDeLote AS id_correoDeLote,
						cl.pdf,
						c.email AS emailCliente,
						CONCAT(c.nombre,'(',c.razonSocial,')') AS nombre,
						o.correo_residente AS emailResidente
					FROM
						correoDeLote AS cl,
						formatoCampo AS fc,
						ordenDeTrabajo AS ot,
						obra AS o,
						cliente AS c
					WHERE
						cl.formatoCampo_id = fc.id_formatoCampo AND
						fc.ordenDeTrabajo_id = ot.id_ordenDeTrabajo AND
						ot.obra_id = o.id_obra AND
						o.cliente_id = c.id_cliente AND
						cl.status = 1 AND
						cl.loteCorreos_id = 1QQ	        
			      ",
			      array($lote),
			      "SELECT -- loteCorreos :: generateAllFormatosByLote"
			      );
			if($dbS->didQuerydied){
				$dbS->rollbackTransaction();
				$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 16);
				return json_encode($arr);				
			}
			if(!$dbS->didQuerydied && !($arr=="empty")){
				foreach ($arr as $value) {
					$mailResponse=$mailer->sendMailFinal($value['emailCliente'], $value['nombre'], $value['pdf']);
					if($mailResponse==202){
						$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
					}else{
						$dbS->rollbackTransaction();
						$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 18, 'mailError' => $mailResponse, '$value[emailCliente]' => $value['emailCliente'],  '$value[emailResidente]' => $value['emailResidente'], '$value[nombre]' => $value['nombre'], '$value[pdf]' => $value['pdf']);
						return json_encode($arr);	
					}
					$mailResponse=$mailer->sendMailFinal($value['emailResidente'], $value['nombre'], $value['pdf']);
					if($mailResponse==202){
						$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
					}else{
						$dbS->rollbackTransaction();
						$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 17, 'mailError' => $mailResponse, '$value[emailCliente]' => $value['emailCliente'], '$value[emailResidente]' => $value['emailResidente'], '$value[nombre]' => $value['nombre'], '$value[pdf]' => $value['pdf'] );
						return json_encode($arr);	
					}

					/*
					if($mailer->sendMailFinal($correo, $info['nombre'], $dirDatabase)==202){
						$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
					}else{
						$dbS->rollbackTransaction();
						$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 15);
						return json_encode($arr);	
					}
					*/
					$dbS->squery(
						"
						UPDATE 
							correoDeLote 
						SET 
							status = status +1
						WHERE
							id_correoDeLote = 1QQ
						"
						,
						array($value['id_correoDeLote'])
						,
						"UPDATE -- loteCorreos :: sentAllEmailFormatosByLote"
					);
					if($dbS->didQuerydied){
						$dbS->rollbackTransaction();
						$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 10);
						return json_encode($arr);				
					}

				}
				$dbS->squery(
					"
					UPDATE 
						loteCorreos 
					SET 
						status= status+1
					WHERE
						id_loteCorreos = 1QQ
					",
					array($lote),
					"UPDATE -- loteCorreos :: sentAllEmailFormatosByLote"
				);
				if($dbS->didQuerydied){
					$dbS->rollbackTransaction();
					$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 13);
					return json_encode($arr);				
				}
				$dbS->commitTransaction();
			}else{
				if($arr=="empty"){
					$arr = array('lote' => $lote, 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en el envio de correos','error' => 12);
				}else{
					$arr = array('lote' => $lote, 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en el envio de correos','error' => 14);
				}
				$dbS->rollbackTransaction();
				return json_encode($arr);
			}
		}
		$dbS->rollbackTransaction();
		return json_encode($arr);

	}

	public function getAllAdministrativo($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$arr= $dbS->qAll(
				"	SELECT
						fc.id_formatoCampo,
						fc.informeNo,
						fc.observaciones,
						fc.tipo,
						o.cotizacion,
						c.razonSocial,
						o.obra,
						fc.ensayadoFin
					FROM
						formatoCampo AS fc,
						ordenDeTrabajo AS ot,
						obra AS o,
						cliente AS c
					WHERE
						fc.ordenDeTrabajo_id = ot.id_ordenDeTrabajo AND
						ot.obra_id = o.id_obra AND
						o.cliente_id = c.id_cliente AND
						fc.ensayadoFin = 0 AND 
						fc.loteStatus = 0 AND 
						ot.laboratorio_id = 1QQ
					ORDER BY 
						fc.lastEditedON DESC
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
	
	public function ping2($data){
		echo $data;
	}

}
?>