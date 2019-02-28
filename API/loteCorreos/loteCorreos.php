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

	public function completarLote($token,$rol_usuario_id,$lote){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$dbS->squery(
				"UPDATE 
					loteCorreos 
				SET 
					status= 2
				WHERE
					id_loteCorreos = 1QQ
				",
				array($lote),
				"UPDATE -- loteCorreos :: sentAllEmailFormatosByLote"
			);
			if($dbS->didQuerydied){
				$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 13);
				return json_encode($arr);				
			}else{
				$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Exito','error' => 0);
				return json_encode($arr);	
			}
		}
		return json_encode($arr);	
	}

	public function getAllAdmin($token,$rol_usuario_id,$status){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$arr= $dbS->qAll(
				"  SELECT
						lc.id_loteCorreos,
						lc.creador_id,
						CONCAT(u.nombre,' ',u.apellido) AS encargado,
						CASE 
							WHEN lc.status = 0 THEN 'Disponible'
							WHEN lc.status = 1 THEN 'Cerrado'
							WHEN lc.status = 2 THEN 'Enviados'
							ELSE 'Error, contacte a soporte'
						END AS estado,
						lc.status,
						lc.factua
					FROM
						loteCorreos AS lc INNER JOIN
						usuario AS u ON lc.creador_id = u.id_usuario
					WHERE
						lc.status < 1QQ 
						AND u.laboratorio_id = 1QQ
					ORDER BY 
						lc.createdON
			      ",
			      array($status,$laboratorio_id),
			      "SELECT -- LoteCorreos :: getAllAdmin"
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

	public function agregaFormatos($token,$rol_usuario_id,$lote,$formatosSeleccionadosCCH,$formatosSeleccionadosRev){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$id_usuario=$usuario->id_usuario;
		$dbS->beginTransaction();
		$formatosSeleccionadosCCH=json_decode($formatosSeleccionadosCCH);
		$formatosSeleccionadosRev=json_decode($formatosSeleccionadosRev);
		if($arr['error'] == 0){
			if($lote == -1){ 					// No hay lote, crerar uno nuevo
				$dbS->squery(
					"INSERT INTO loteCorreos( creador_id )VALUES(1QQ)"
					,array($id_usuario),
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
			foreach ($formatosSeleccionadosCCH as $value) {
				$no=$no+1;
				$dbS->squery(
					"INSERT INTO correoDeLote(loteCorreos_id,registrosCampo_id) VALUES(1QQ,1QQ)"
					,array($lote,$value),
					"INSERT -- loteCorreos :: agregaFormatos"
				);
				if($dbS->didQuerydied){
					$dbS->rollbackTransaction();
					$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 12);
					return json_encode($arr);				
				}
				$formatoCampo_id = $dbS->qvalue(
					"   SELECT
							formatoCampo_id
						FROM
							registrosCampo
						WHERE
							id_registrosCampo = 1QQ
				",array($value),
				"SELECT -- loteCorreos :: generateAllFormatosByLote"
				);
				if($dbS->didQuerydied || $formatoCampo_id == "empty"){
					$dbS->rollbackTransaction();
					$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 16);
					return json_encode($arr);				
				}
				$dbS->squery(
					"UPDATE
						formatoCampo
					SET
						loteStatus= loteStatus + 1
					WHERE
						id_formatoCampo = 1QQ
					",
					array($formatoCampo_id),
					"UPDATE -- loteCorreos :: agregaFormatos"
				);
				if($dbS->didQuerydied){
					$dbS->rollbackTransaction();
					$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 13);
					return json_encode($arr);				
				}
			}
			foreach ($formatosSeleccionadosRev as $value) {
				$no=$no+1;
				$dbS->squery(
					"INSERT INTO correoDeLote(loteCorreos_id,formatoRegistroRev_id) VALUES(1QQ,1QQ)"
					,array($lote,$value),
					"INSERT -- loteCorreos :: agregaFormatos"
				);
				if($dbS->didQuerydied){
					$dbS->rollbackTransaction();
					$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 12);
					return json_encode($arr);				
				}
				$dbS->squery(
					"UPDATE
						formatoRegistroRev
					SET
						loteStatus= loteStatus + 1
					WHERE
						id_formatoRegistroRev = 1QQ
					",
					array($value),
					"UPDATE -- loteCorreos :: agregaFormatos"
				);
				if($dbS->didQuerydied){
					$dbS->rollbackTransaction();
					$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 13);
					return json_encode($arr);				
				}
			}
			$dbS->squery(
				"UPDATE
					loteCorreos
				SET
					correosNo= correosNo + 1QQ
				WHERE
					id_loteCorreos = 1QQ
				",
				array($no,$lote),
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
			$arr= $dbS->qarrayA(
				"	SELECT
						lc.id_loteCorreos,
						lc.creador_id,
						CONCAT(u.nombre,' ',u.apellido) AS encargado,
						IF(lc.status = 0, 'Pendiente', 'Completado') AS estado,
						lc.status,
						lc.correosNo,
						DATE(lc.createdON) fecha,
						lc.factua,
						lc.observaciones,
						lc.customMail,
						lc.adjunto,
						lc.pdfPath,
						lc.xmlPath,
						lc.customMailStatus,
						lc.customText
					FROM
							loteCorreos AS lc 
						INNER JOIN
							usuario AS u ON lc.creador_id = u.id_usuario 
					WHERE
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
			$arr= $dbS->qAll(
				"   SELECT
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
				"SELECT
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
							"UPDATE 
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
					"UPDATE 
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
	public function sentFinalMailAdministrativo($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){

		}
		return json_encode($arr);	
	}
	public function getAllFormatos($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$arr= $dbS->qAll(
				"SELECT
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
			$arr= $dbS->qAll(
			"SELECT
				c.razonSocial,
				o.cotizacion,
				o.obra,
				CASE
					WHEN o.tipo = 1 THEN 'IGUALA'
					WHEN o.tipo = 2 THEN 'UNITARIO'
					ELSE 'Error, contacte a soporte'
				END AS tipoObra,
				fc.id_formatoCampo AS id_formato,
				fc.informeNo,
				fc.tipo AS tipo,
				2 AS tipoNo,
				fc.ensayadoFin,
				rc.id_registrosCampo,
				rc.fecha AS fechaColado,
				rc.claveEspecimen,
				ensayo.fecha AS fechaEnsayado,
				ensayo.pdfFinal,
				ensayo.sentToClientFinal,
				CASE
					WHEN ensayo.sentToClientFinal = 0 THEN 'No enviado'
					WHEN ensayo.sentToClientFinal = 1 THEN CONCAT('Enviado ',ensayo.sentToClientFinal,' vez')
					WHEN ensayo.sentToClientFinal > 1 THEN CONCAT('Enviado ',ensayo.sentToClientFinal,' veces')
				END AS estadoCorreos,
				ensayo.dateSentToClientFinal,
				cdl.status AS corrLoteStatus,
				cdl.id_correoDeLote
			FROM
					cliente AS c
				INNER JOIN 
					obra AS o ON id_cliente = cliente_id
				INNER JOIN 
					ordenDeTrabajo AS ot ON id_obra = obra_id
				INNER JOIN 
					formatoCampo AS fc ON  id_ordenDeTrabajo = ordenDeTrabajo_id
				INNER JOIN
					registrosCampo AS rc ON id_formatoCampo = formatoCampo_id
				INNER JOIN 
					ensayoCilindro AS ensayo ON id_registrosCampo = registrosCampo_id
				INNER JOIN
					correoDeLote AS cdl ON  cdl.registrosCampo_id = ensayo.registrosCampo_id
			WHERE
				cdl.loteCorreos_id = 1QQ
			UNION
			SELECT
				c.razonSocial,
				o.cotizacion,
				o.obra,
				CASE
					WHEN o.tipo = 1 THEN 'IGUALA'
					WHEN o.tipo = 2 THEN 'UNITARIO'
					ELSE 'Error, contacte a soporte'
				END AS tipoObra,
				fc.id_formatoCampo AS id_formato,
				fc.informeNo,
				fc.tipo AS tipo,
				1 AS tipoNo,
				fc.ensayadoFin,
				rc.id_registrosCampo,
				rc.fecha AS fechaColado,
				rc.claveEspecimen,
				ensayo.fecha AS fechaEnsayado,
				ensayo.pdfFinal,
				ensayo.sentToClientFinal,
				CASE
					WHEN ensayo.sentToClientFinal = 0 THEN 'No enviado'
					WHEN ensayo.sentToClientFinal = 1 THEN CONCAT('Enviado ',ensayo.sentToClientFinal,' vez')
					WHEN ensayo.sentToClientFinal > 1 THEN CONCAT('Enviado ',ensayo.sentToClientFinal,' veces')
				END AS estadoCorreos,
				ensayo.dateSentToClientFinal,
				cdl.status AS corrLoteStatus,
				cdl.id_correoDeLote
			FROM
					cliente AS c
				INNER JOIN 
					obra AS o ON id_cliente = cliente_id
				INNER JOIN 
					ordenDeTrabajo AS ot ON id_obra = obra_id
				INNER JOIN 
					formatoCampo AS fc ON  id_ordenDeTrabajo = ordenDeTrabajo_id
				INNER JOIN
					registrosCampo AS rc ON id_formatoCampo = formatoCampo_id
				INNER JOIN 
					ensayoCubo AS ensayo ON id_registrosCampo = registrosCampo_id
				INNER JOIN
					correoDeLote AS cdl ON  cdl.registrosCampo_id = ensayo.registrosCampo_id
			WHERE
				cdl.loteCorreos_id = 1QQ
			UNION
			SELECT
				c.razonSocial,
				o.cotizacion,
				o.obra,
				CASE
					WHEN o.tipo = 1 THEN 'IGUALA'
					WHEN o.tipo = 2 THEN 'UNITARIO'
					ELSE 'Error, contacte a soporte'
				END AS tipoObra,
				fc.id_formatoCampo AS id_formato,
				fc.informeNo,
				fc.tipo AS tipo,
				4 AS tipoNo,
				fc.ensayadoFin,
				rc.id_registrosCampo,
				rc.fecha AS fechaColado,
				rc.claveEspecimen,
				ensayo.fecha AS fechaEnsayado,
				ensayo.pdfFinal,
				ensayo.sentToClientFinal,
				CASE
					WHEN ensayo.sentToClientFinal = 0 THEN 'No enviado'
					WHEN ensayo.sentToClientFinal = 1 THEN CONCAT('Enviado ',ensayo.sentToClientFinal,' vez')
					WHEN ensayo.sentToClientFinal > 1 THEN CONCAT('Enviado ',ensayo.sentToClientFinal,' veces')
				END AS estadoCorreos,
				ensayo.dateSentToClientFinal,
				cdl.status AS corrLoteStatus,
				cdl.id_correoDeLote
			FROM
					cliente AS c
				INNER JOIN 
					obra AS o ON id_cliente = cliente_id
				INNER JOIN 
					ordenDeTrabajo AS ot ON id_obra = obra_id
				INNER JOIN 
					formatoCampo AS fc ON  id_ordenDeTrabajo = ordenDeTrabajo_id
				INNER JOIN
					registrosCampo AS rc ON id_formatoCampo = formatoCampo_id
				INNER JOIN 
					ensayoViga AS ensayo ON id_registrosCampo = registrosCampo_id
				INNER JOIN
					correoDeLote AS cdl ON  cdl.registrosCampo_id = ensayo.registrosCampo_id
			WHERE
				cdl.loteCorreos_id = 1QQ
			UNION
			SELECT 
				c.razonSocial,
				o.cotizacion,
				o.obra,
				CASE
					WHEN o.tipo = 1 THEN 'IGUALA'
					WHEN o.tipo = 2 THEN 'UNITARIO'
					ELSE 'Error, contacte a soporte'
				END AS tipoObra,
				fr.id_formatoRegistroRev AS id_formato,
				fr.regNo AS informeNo,
				'Revenimiento' AS tipo,
				1 AS tipoNo,
				'0' AS ensayadoFin,
				0 AS id_registrosCampo,
				DATE(fr.createdON) AS fechaColado,
				'N.A.' AS claveEspecimen,
				'N.A.' AS fechaEnsayado,
				fr.pdfFinal,
				fr.sentToClientFinal,
				CASE
					WHEN fr.sentToClientFinal = 0 THEN 'No enviado'
					WHEN fr.sentToClientFinal = 1 THEN CONCAT('Enviado ',fr.sentToClientFinal,' vez')
					WHEN fr.sentToClientFinal > 1 THEN CONCAT('Enviado ',fr.sentToClientFinal,' veces')
				END AS estadoCorreos,
				fr.dateSentToClientFinal,
				cdl.status AS corrLoteStatus,
				cdl.id_correoDeLote
			FROM
					cliente AS c
				INNER JOIN 
					obra AS o ON id_cliente = cliente_id
				INNER JOIN 
					ordenDeTrabajo AS ot ON id_obra = obra_id
				INNER JOIN 
					formatoRegistroRev AS fr ON  id_ordenDeTrabajo = ordenDeTrabajo_id
				INNER JOIN
					correoDeLote AS cdl ON  cdl.formatoRegistroRev_id = fr.id_formatoRegistroRev
			WHERE
				cdl.loteCorreos_id = 1QQ

			",
			array($lote,$lote,$lote,$lote),
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
	
	public function upDateLoteDetails($token,$rol_usuario_id,$id_loteCorreos,$factua,$observaciones,$customMail,$customText,$adjunto){
		global $dbS;
		$usuario = new Usuario();

		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		
		if($arr['error'] == 0){
			$dbS->squery(
				"UPDATE 
					loteCorreos 
				SET 
					factua = '1QQ',
					observaciones = '1QQ',
					customMail = '1QQ',
					customText = '1QQ',
					adjunto = '1QQ'
				WHERE
					id_loteCorreos = 1QQ
				",array($factua,$observaciones,$customMail,$customText,$adjunto,$id_loteCorreos),
				"UPDATE -- loteCorreos :: upDateLoteDetails : 1"
			);
			if($dbS->didQuerydied){
				$dbS->rollbackTransaction();
				$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 10);
				return json_encode($arr);				
			}		
		}
		return json_encode($arr);	
	}
	public function checkViabilityOfGroupMail($token,$rol_usuario_id,$id_loteCorreos){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$cuantos = $dbS->qvalue(
				"SELECT
					COUNT(*)
				FROM(
					SELECT
						id_obra,
						COUNT(*)
					FROM
							cliente AS c
						INNER JOIN 
							obra AS o ON id_cliente = cliente_id
						INNER JOIN 
							ordenDeTrabajo AS ot ON id_obra = obra_id
						INNER JOIN 
							formatoCampo AS fc ON  id_ordenDeTrabajo = ordenDeTrabajo_id
						INNER JOIN
							registrosCampo AS rc ON id_formatoCampo = formatoCampo_id
						INNER JOIN
							correoDeLote AS cdl ON  registrosCampo_id = id_registrosCampo 
						INNER JOIN
							loteCorreos AS lc ON id_loteCorreos = loteCorreos_id
					WHERE
						id_loteCorreos = 1QQ
					GROUP BY id_obra
				) AS T1
					",
					array($id_loteCorreos),"UPDATE -- LoteCorreos :: upLoadDoc : 1"
				);
			if($dbS->didQuerydied || $cuantos=="empty"){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en validacion de GroupMail, verifica tus datos y vuelve a intentarlo','error' => 20);
				return json_encode($arr);
			}
			if($cuantos == 0){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Exito','error' => 0,'GroupMailValid'=> 0,'GroupMailValidStatus'=> 'No haz agregado formatos a este lote');
				return json_encode($arr);
			}else if($cuantos == 1){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Exito','error' => 0,'GroupMailValid'=> 1,'GroupMailValidStatus'=> 'Solo hay una obra, GroupMail es viable');
				return json_encode($arr);
			}else if($cuantos > 1){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Exito','error' => 0,'GroupMailValid'=> 0,'GroupMailValidStatus'=> 'Hay reportes de diferentes obras en este lote. No es posible enviar un correo en grupo.');
				return json_encode($arr);
			}else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en validacion de GroupMail, verifica tus datos y vuelve a intentarlo','error' => 30);
				return json_encode($arr);
			}
		}
		return json_encode($arr);	
		
	}
	public function upLoadDoc($token,$rol_usuario_id,$id_loteCorreos,$fileName,$doc){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$resultado = $dbS->squery(
				"	UPDATE
						loteCorreos
					SET
						1QQ = '1QQ'
					WHERE
						id_loteCorreos = 1QQ
					",
					array($fileName,$doc,$id_loteCorreos),"UPDATE -- LoteCorreos :: upLoadDoc : 1"

				);
			if(!$dbS->didQuerydied){
					$id=$dbS->lastInsertedID;
					$arr = array('id_usuario' => $id, 'nombre' => $nombre, 'token' => $token,	'estatus' => '¡Exito!, redireccionando...','error' => 0);
					return json_encode($arr);
			}else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en subir la foto, verifica tus datos y vuelve a intentarlo','error' => 20);
					return json_encode($arr);
			}			
		}
		return json_encode($arr);
	}

	public function deleteCorreoLote($token,$rol_usuario_id,$id_correoDeLote,$id_lote){
		global $dbS;
		$usuario = new Usuario();

		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			//recolectamos info de tipo de formato para rollback
			$info = $dbS->qarrayA(
				"   SELECT
						CASE 
							WHEN registrosCampo_id IS NOT NULL THEN registrosCampo_id
							WHEN formatoRegistroRev_id IS NOT NULL THEN formatoRegistroRev_id
							ELSE -1
						END AS formato,
						CASE 
							WHEN registrosCampo_id IS NOT NULL THEN 1
							WHEN formatoRegistroRev_id IS NOT NULL THEN 2
							ELSE -1
						END AS formatoTipo
					FROM
						correoDeLote
					WHERE
					id_correoDeLote = 1QQ
				",array($id_correoDeLote),
			"SELECT -- loteCorreos :: deleteCorreoLote :: 1"
			);
			if($dbS->didQuerydied || $info == "empty" || $info["formatoTipo"]==-1){
				$dbS->rollbackTransaction();
				$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 16);
				return json_encode($arr);				
			}
			/* Comenzamos rollback */
			//Se disminuye el numero de correos que enviar.
			$dbS->squery(
				"UPDATE
					loteCorreos
				SET
					correosNo= correosNo - 1
				WHERE
					id_loteCorreos = 1QQ
				",array($id_lote),
				"UPDATE -- loteCorreos :: deleteCorreoLote :: 2"
			);
			if($dbS->didQuerydied){
				$dbS->rollbackTransaction();
				$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 11);
				return json_encode($arr);				
			}
			if($info["formatoTipo"]==1){ // formatoCampo
				$formatoCampo_id = $dbS->qvalue(
					"   SELECT
							formatoCampo_id
						FROM
							registrosCampo
						WHERE
							id_registrosCampo = 1QQ
				",array($info["formato"]),
				"SELECT -- loteCorreos :: deleteCorreoLote :: 3"
				);
				if($dbS->didQuerydied || $formatoCampo_id == "empty"){
					$dbS->rollbackTransaction();
					$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 16);
					return json_encode($arr);				
				}
				$dbS->squery(
					"UPDATE
						formatoCampo
					SET
						loteStatus= loteStatus - 1
					WHERE
						id_formatoCampo = 1QQ
					",
					array($formatoCampo_id),
					"UPDATE -- loteCorreos :: deleteCorreoLote :: 4"
				);
				if($dbS->didQuerydied){
					$dbS->rollbackTransaction();
					$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 13);
					return json_encode($arr);				
				}
			}else if($info["formatoTipo"]==2){ // Revenimiento
				$dbS->squery(
					"UPDATE
						formatoRegistroRev
					SET
						loteStatus= loteStatus - 1
					WHERE
						id_formatoRegistroRev = 1QQ
					",
					array($info["formato"]),
					"UPDATE -- loteCorreos :: deleteCorreoLote :: 5"
				);
				if($dbS->didQuerydied){
					$dbS->rollbackTransaction();
					$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 13);
					return json_encode($arr);				
				}
			}else{
				$dbS->rollbackTransaction();
				$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 11);
				return json_encode($arr);	
			}
			$dbS->squery(
				"DELETE FROM 
					correoDeLote 
				WHERE
					id_correoDeLote = 1QQ
				",array($id_correoDeLote),
				"UPDATE -- loteCorreos :: deleteCorreoLote :: 6"
			);

			if($dbS->didQuerydied){
				$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 10);
				return json_encode($arr);				
			}else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Exito','error' => 0);
				return json_encode($arr);
			}

		}
		return json_encode($arr);
	}

	public function sentAllEmailFormatosByLote($token,$rol_usuario_id,$lote,$all){
		global $dbS;
		$usuario = new Usuario();
		$mailer = new Mailer();

		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		$email=$usuario->email;
		$nombre= $usuario->nombre.' '.$usuario->apellido.'(LACOCS)';
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			$info= $dbS->qarrayA(
				"	SELECT
						lc.id_loteCorreos,
						lc.creador_id,
						CONCAT(u.nombre,' ',u.apellido) AS encargado,
						lc.status,
						lc.correosNo,
						DATE(lc.createdON) fecha,
						lc.factua,
						lc.observaciones,
						lc.customMail,
						lc.adjunto,
						IF(lc.pdfPath IS NOT NULL,lc.pdfPath,0) AS pdfPath,
						IF(lc.xmlPath IS NOT NULL,lc.xmlPath,0) AS xmlPath,
						lc.customMailStatus,
						lc.customText
					FROM
							loteCorreos AS lc 
						INNER JOIN
							usuario AS u ON lc.creador_id = u.id_usuario 
					WHERE
						lc.id_loteCorreos =1QQ
			      ",
			      array($lote),
			      "SELECT -- loteCorreos :: getLoteByID"
			      );

			if($dbS->didQuerydied || $info == "empty"){
				$dbS->rollbackTransaction();
				$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 16);
				return json_encode($arr);	
			}
			$var_system = $dbS->qvalue(
				"SELECT
					apiRoot
				FROM
					systemstatus
				ORDER BY id_systemstatus DESC;
				",array(),"SELECT -- FormatoCampo :: generatePDF : 2"
			);
			if($dbS->didQuerydied || ($var_system=="empty")){
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 7);
				return json_encode($arr);
			}
			$arr= $dbS->qAll(
				"SELECT
					CONCAT(c.nombreContacto,' (',c.razonSocial,')') AS nombreCliente,
					c.email AS emailCliente,
					CONCAT(o.nombre_residente,' (',c.razonSocial,')') AS nombreResidente,
					o.correo_residente AS emailResidente,
					CONCAT(c.nombre,' (',c.razonSocial,')') AS nombreAlterno,
					o.correo_alterno AS correo_alterno,
					ensayo.id_ensayoCilindro AS id,
					fc.informeNo,
					fc.tipo AS tipo,
					rc.fecha AS fechaColado,
					ensayo.pdfFinal,
					ensayo.sentToClientFinal,
					cdl.id_correoDeLote
				FROM
						cliente AS c
					INNER JOIN 
						obra AS o ON id_cliente = cliente_id
					INNER JOIN 
						ordenDeTrabajo AS ot ON id_obra = obra_id
					INNER JOIN 
						formatoCampo AS fc ON  id_ordenDeTrabajo = ordenDeTrabajo_id
					INNER JOIN
						registrosCampo AS rc ON id_formatoCampo = formatoCampo_id
					INNER JOIN 
						ensayoCilindro AS ensayo ON id_registrosCampo = registrosCampo_id
					INNER JOIN
						correoDeLote AS cdl ON  cdl.registrosCampo_id = ensayo.registrosCampo_id
				WHERE
					cdl.loteCorreos_id = 1QQ
				UNION
				SELECT
					CONCAT(c.nombreContacto,' (',c.razonSocial,')') AS nombreCliente,
					c.email AS emailCliente,
					CONCAT(o.nombre_residente,' (',c.razonSocial,')') AS nombreResidente,
					o.correo_residente AS emailResidente,
					CONCAT(c.nombre,' (',c.razonSocial,')') AS nombreAlterno,
					o.correo_alterno AS correo_alterno,
					ensayo.id_ensayoCubo AS id,
					fc.informeNo,
					fc.tipo AS tipo,
					rc.fecha AS fechaColado,
					ensayo.pdfFinal,
					ensayo.sentToClientFinal,
					cdl.id_correoDeLote
				FROM
						cliente AS c
					INNER JOIN 
						obra AS o ON id_cliente = cliente_id
					INNER JOIN 
						ordenDeTrabajo AS ot ON id_obra = obra_id
					INNER JOIN 
						formatoCampo AS fc ON  id_ordenDeTrabajo = ordenDeTrabajo_id
					INNER JOIN
						registrosCampo AS rc ON id_formatoCampo = formatoCampo_id
					INNER JOIN 
						ensayoCubo AS ensayo ON id_registrosCampo = registrosCampo_id
					INNER JOIN
						correoDeLote AS cdl ON  cdl.registrosCampo_id = ensayo.registrosCampo_id
				WHERE
					cdl.loteCorreos_id = 1QQ
				UNION
				SELECT
					CONCAT(c.nombreContacto,' (',c.razonSocial,')') AS nombreCliente,
					c.email AS emailCliente,
					CONCAT(o.nombre_residente,' (',c.razonSocial,')') AS nombreResidente,
					o.correo_residente AS emailResidente,
					CONCAT(c.nombre,' (',c.razonSocial,')') AS nombreAlterno,
					o.correo_alterno AS correo_alterno,
					ensayo.id_ensayoViga AS id,
					fc.informeNo,
					fc.tipo AS tipo,
					rc.fecha AS fechaColado,
					ensayo.pdfFinal,
					ensayo.sentToClientFinal,
					cdl.id_correoDeLote
				FROM
						cliente AS c
					INNER JOIN 
						obra AS o ON id_cliente = cliente_id
					INNER JOIN 
						ordenDeTrabajo AS ot ON id_obra = obra_id
					INNER JOIN 
						formatoCampo AS fc ON  id_ordenDeTrabajo = ordenDeTrabajo_id
					INNER JOIN
						registrosCampo AS rc ON id_formatoCampo = formatoCampo_id
					INNER JOIN 
						ensayoViga AS ensayo ON id_registrosCampo = registrosCampo_id
					INNER JOIN
						correoDeLote AS cdl ON  cdl.registrosCampo_id = ensayo.registrosCampo_id
				WHERE
					cdl.loteCorreos_id = 1QQ
				UNION
				SELECT 
					CONCAT(c.nombreContacto,' (',c.razonSocial,')') AS nombreCliente,
					c.email AS emailCliente,
					CONCAT(o.nombre_residente,' (',c.razonSocial,')') AS nombreResidente,
					o.correo_residente AS emailResidente,
					CONCAT(c.nombre,' (',c.razonSocial,')') AS nombreAlterno,
					o.correo_alterno AS correo_alterno,
					fr.id_formatoRegistroRev AS id,
					fr.regNo AS informeNo,
					'REVENIMIENTO' AS tipo,
					DATE(fr.createdON) AS fechaColado,
					fr.pdfFinal,
					fr.sentToClientFinal,
					cdl.id_correoDeLote
				FROM
						cliente AS c
					INNER JOIN 
						obra AS o ON id_cliente = cliente_id
					INNER JOIN 
						ordenDeTrabajo AS ot ON id_obra = obra_id
					INNER JOIN 
						formatoRegistroRev AS fr ON  id_ordenDeTrabajo = ordenDeTrabajo_id
					INNER JOIN
						correoDeLote AS cdl ON  cdl.formatoRegistroRev_id = fr.id_formatoRegistroRev
				WHERE
					cdl.loteCorreos_id = 1QQ
				",
			array($lote,$lote,$lote,$lote),
			"SELECT -- loteCorreos :: generateAllFormatosByLote"
			);
			if($dbS->didQuerydied){
				$dbS->rollbackTransaction();
				$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 16);
				return json_encode($arr);				
			}
			if(!$dbS->didQuerydied && !($arr=="empty")){
				
				foreach ($arr as $value) {
					if($value['sentToClientFinal']==0 || $all==1){
						$table = "";
						switch($value['tipo']){
							case "CILINDRO":
								$table="ensayoCilindro";
								$id="id_ensayoCilindro";
							break;
							case "CUBO":
								$table="ensayoCubo";
								$id="id_ensayoCubo";
							break;
							case "VIGAS":
								$table="ensayoViga";
								$id="id_ensayoViga";
							break;
							case "REVENIMIENTO":
								$table="formatoRegistroRev";
								$id="id_formatoRegistroRev";
							break;
						}

						$mailResponse=$mailer->sendMailFinalAdministrativo($value['emailCliente'], $value['nombreCliente'], $value['pdfFinal'],$info['encargado'],$info['factua'],$info['customMail'],$info['adjunto'],$var_system.$info['pdfPath'],$var_system.$info['xmlPath'],$info['customText']);
						if($mailResponse==202){
							$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
						}else{
							$dbS->rollbackTransaction();
							$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 18, 'mailError' => $mailResponse, '$value[emailCliente]' => $value['emailCliente'],  '$value[emailResidente]' => $value['emailResidente'], '$value[nombre]' => $value['nombre'], '$value[pdf]' => $value['pdf']);
							return json_encode($arr);	
						}
						$mailResponse=$mailer->sendMailFinalAdministrativo($value['emailResidente'], $value['nombreResidente'], $value['pdfFinal'],$info['encargado'],$info['factua'],$info['customMail'],$info['adjunto'],$var_system.$info['pdfPath'],$var_system.$info['xmlPath'],$info['customText']);
						if($mailResponse==202){
							$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
						}else{
							$dbS->rollbackTransaction();
							$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 17, 'mailError' => $mailResponse, '$value[emailCliente]' => $value['emailCliente'], '$value[emailResidente]' => $value['emailResidente'], '$value[nombre]' => $value['nombre'], '$value[pdf]' => $value['pdf'] );
							return json_encode($arr);	
						}
						$mailResponse=$mailer->sendMailFinalAdministrativo($value['correo_alterno'], $value['nombreAlterno'], $value['pdfFinal'],$info['encargado'],$info['factua'],$info['customMail'],$info['adjunto'],$var_system.$info['pdfPath'],$var_system.$info['xmlPath'],$info['customText']);
						if($mailResponse==202){
							$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
						}else{
							$dbS->rollbackTransaction();
							$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 40, 'mailError' => $mailResponse, '$value[emailCliente]' => $value['emailCliente'], '$value[emailResidente]' => $value['emailResidente'], '$value[nombre]' => $value['nombre'], '$value[pdf]' => $value['pdf'] );
							return json_encode($arr);	
						}
						/*Envio de copia al remitente
						$mailResponse=$mailer->sendMailFinalAdministrativoComprobante($email, $nombre, $value['pdfFinal'],$info['encargado'],$info['factua'],$info['customMail'],$info['adjunto'],$var_system.$info['pdfPath'],$var_system.$info['xmlPath'],$info['customText']);
						if($mailResponse==202){
							$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
						}else{
							$dbS->rollbackTransaction();
							$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 40, 'mailError' => $mailResponse, '$value[emailCliente]' => $value['emailCliente'], '$value[emailResidente]' => $value['emailResidente'], '$value[nombre]' => $value['nombre'], '$value[pdf]' => $value['pdf'] );
							return json_encode($arr);	
						}
						*/
						$dbS->squery(
							"UPDATE 
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
						$dbS->squery(
							"UPDATE 
								1QQ 
							SET 
								sentToClientFinal = sentToClientFinal +1
							WHERE
								1QQ = 1QQ
							"
							,
							array($table,$id,$value['id'])
							,
							"UPDATE -- loteCorreos :: sentAllEmailFormatosByLote"
						);
						if($dbS->didQuerydied){
							$dbS->rollbackTransaction();
							$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 10);
							return json_encode($arr);				
						}
					}
				}
				
				$dbS->squery(
					"UPDATE 
						loteCorreos 
					SET 
						status= 1
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
				
				$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
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

	public function sentGroupMailFormatosByLote($token,$rol_usuario_id,$lote){
		global $dbS;
		$usuario = new Usuario();
		$mailer = new Mailer();

		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		$email=$usuario->email;
		$nombre= $usuario->nombre.' '.$usuario->apellido.'(LACOCS)';
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			$arr = json_decode($this->checkViabilityOfGroupMail($token, $rol_usuario_id,$lote),true);
			if($arr['error'] != 0 || $arr['GroupMailValid'] == 0){
				$dbS->rollbackTransaction();
				$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Este lote no es candidato para envio en grupo o ha ocurrido un error en la validaci\u00F3n del mismo','error' => 16);
				return json_encode($arr);	
			}
			$info= $dbS->qarrayA(
				"	SELECT
						lc.id_loteCorreos,
						lc.creador_id,
						CONCAT(u.nombre,' ',u.apellido) AS encargado,
						lc.status,
						lc.correosNo,
						DATE(lc.createdON) fecha,
						lc.factua,
						lc.observaciones,
						lc.customMail,
						lc.adjunto,
						IF(lc.pdfPath IS NOT NULL,lc.pdfPath,0) AS pdfPath,
						IF(lc.xmlPath IS NOT NULL,lc.xmlPath,0) AS xmlPath,
						lc.customMailStatus,
						lc.customText
					FROM
							loteCorreos AS lc 
						INNER JOIN
							usuario AS u ON lc.creador_id = u.id_usuario 
					WHERE
						lc.id_loteCorreos =1QQ
			      ",
			      array($lote),
			      "SELECT -- loteCorreos :: getLoteByID"
			      );

			if($dbS->didQuerydied || $info == "empty"){
				$dbS->rollbackTransaction();
				$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 16);
				return json_encode($arr);	
			}
			$var_system = $dbS->qvalue(
				"SELECT
					apiRoot
				FROM
					systemstatus
				ORDER BY id_systemstatus DESC;
				",array(),"SELECT -- FormatoCampo :: generatePDF : 2"
			);
			if($dbS->didQuerydied || ($var_system=="empty")){
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 7);
				return json_encode($arr);
			}
			$data= $dbS->qAll(
				"SELECT
					CONCAT(c.nombreContacto,' (',c.razonSocial,')') AS nombreCliente,
					c.email AS emailCliente,
					CONCAT(o.nombre_residente,' (',c.razonSocial,')') AS nombreResidente,
					o.correo_residente AS emailResidente,
					CONCAT(c.nombre,' (',c.razonSocial,')') AS nombreAlterno,
					o.correo_alterno AS correo_alterno,
					ensayo.id_ensayoCilindro AS id,
					fc.informeNo,
					fc.tipo AS tipo,
					rc.fecha AS fechaColado,
					rc.claveEspecimen,
					ensayo.pdfFinal,
					ensayo.sentToClientFinal,
					cdl.id_correoDeLote
				FROM
						cliente AS c
					INNER JOIN 
						obra AS o ON id_cliente = cliente_id
					INNER JOIN 
						ordenDeTrabajo AS ot ON id_obra = obra_id
					INNER JOIN 
						formatoCampo AS fc ON  id_ordenDeTrabajo = ordenDeTrabajo_id
					INNER JOIN
						registrosCampo AS rc ON id_formatoCampo = formatoCampo_id
					INNER JOIN 
						ensayoCilindro AS ensayo ON id_registrosCampo = registrosCampo_id
					INNER JOIN
						correoDeLote AS cdl ON  cdl.registrosCampo_id = ensayo.registrosCampo_id
				WHERE
					cdl.loteCorreos_id = 1QQ
				UNION
				SELECT
					CONCAT(c.nombreContacto,' (',c.razonSocial,')') AS nombreCliente,
					c.email AS emailCliente,
					CONCAT(o.nombre_residente,' (',c.razonSocial,')') AS nombreResidente,
					o.correo_residente AS emailResidente,
					CONCAT(c.nombre,' (',c.razonSocial,')') AS nombreAlterno,
					o.correo_alterno AS correo_alterno,
					ensayo.id_ensayoCubo AS id,
					fc.informeNo,
					fc.tipo AS tipo,
					rc.fecha AS fechaColado,
					rc.claveEspecimen,
					ensayo.pdfFinal,
					ensayo.sentToClientFinal,
					cdl.id_correoDeLote
				FROM
						cliente AS c
					INNER JOIN 
						obra AS o ON id_cliente = cliente_id
					INNER JOIN 
						ordenDeTrabajo AS ot ON id_obra = obra_id
					INNER JOIN 
						formatoCampo AS fc ON  id_ordenDeTrabajo = ordenDeTrabajo_id
					INNER JOIN
						registrosCampo AS rc ON id_formatoCampo = formatoCampo_id
					INNER JOIN 
						ensayoCubo AS ensayo ON id_registrosCampo = registrosCampo_id
					INNER JOIN
						correoDeLote AS cdl ON  cdl.registrosCampo_id = ensayo.registrosCampo_id
				WHERE
					cdl.loteCorreos_id = 1QQ
				UNION
				SELECT
					CONCAT(c.nombreContacto,' (',c.razonSocial,')') AS nombreCliente,
					c.email AS emailCliente,
					CONCAT(o.nombre_residente,' (',c.razonSocial,')') AS nombreResidente,
					o.correo_residente AS emailResidente,
					CONCAT(c.nombre,' (',c.razonSocial,')') AS nombreAlterno,
					o.correo_alterno AS correo_alterno,
					ensayo.id_ensayoViga AS id,
					fc.informeNo,
					fc.tipo AS tipo,
					rc.fecha AS fechaColado,
					rc.claveEspecimen,
					ensayo.pdfFinal,
					ensayo.sentToClientFinal,
					cdl.id_correoDeLote
				FROM
						cliente AS c
					INNER JOIN 
						obra AS o ON id_cliente = cliente_id
					INNER JOIN 
						ordenDeTrabajo AS ot ON id_obra = obra_id
					INNER JOIN 
						formatoCampo AS fc ON  id_ordenDeTrabajo = ordenDeTrabajo_id
					INNER JOIN
						registrosCampo AS rc ON id_formatoCampo = formatoCampo_id
					INNER JOIN 
						ensayoViga AS ensayo ON id_registrosCampo = registrosCampo_id
					INNER JOIN
						correoDeLote AS cdl ON  cdl.registrosCampo_id = ensayo.registrosCampo_id
				WHERE
					cdl.loteCorreos_id = 1QQ
				UNION
				SELECT 
					CONCAT(c.nombreContacto,' (',c.razonSocial,')') AS nombreCliente,
					c.email AS emailCliente,
					CONCAT(o.nombre_residente,' (',c.razonSocial,')') AS nombreResidente,
					o.correo_residente AS emailResidente,
					CONCAT(c.nombre,' (',c.razonSocial,')') AS nombreAlterno,
					o.correo_alterno AS correo_alterno,
					fr.id_formatoRegistroRev AS id,
					fr.regNo AS informeNo,
					'REVENIMIENTO' AS tipo,
					DATE(fr.createdON) AS fechaColado,
					'N.A.' AS claveEspecimen,
					fr.pdfFinal,
					fr.sentToClientFinal,
					cdl.id_correoDeLote
				FROM
						cliente AS c
					INNER JOIN 
						obra AS o ON id_cliente = cliente_id
					INNER JOIN 
						ordenDeTrabajo AS ot ON id_obra = obra_id
					INNER JOIN 
						formatoRegistroRev AS fr ON  id_ordenDeTrabajo = ordenDeTrabajo_id
					INNER JOIN
						correoDeLote AS cdl ON  cdl.formatoRegistroRev_id = fr.id_formatoRegistroRev
				WHERE
					cdl.loteCorreos_id = 1QQ
				",
			array($lote,$lote,$lote,$lote),
			"SELECT -- loteCorreos :: generateAllFormatosByLote"
			);
			if($dbS->didQuerydied){
				$dbS->rollbackTransaction();
				$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 16);
				return json_encode($arr);				
			}
			if(!$dbS->didQuerydied && !($data=="empty")){
				$nombreCliente;
				$emailCliente;
				$nombreResidente;
				$emailResidente;
				$nombreAlterno;
				$correo_alterno;
				foreach ($data as $value) {
					$table = "";

					$nombreCliente = $value['nombreCliente'];
					$emailCliente = $value['emailCliente'];
					$nombreResidente = $value['nombreResidente'];
					$emailResidente = $value['emailResidente'];
					$nombreAlterno = $value['nombreAlterno'];
					$correo_alterno = $value['correo_alterno'];

					switch($value['tipo']){
						case "CILINDRO":
							$table="ensayoCilindro";
							$id="id_ensayoCilindro";
						break;
						case "CUBO":
							$table="ensayoCubo";
							$id="id_ensayoCubo";
						break;
						case "VIGAS":
							$table="ensayoViga";
							$id="id_ensayoViga";
						break;
						case "REVENIMIENTO":
							$table="formatoRegistroRev";
							$id="id_formatoRegistroRev";
						break;
					}
					$dbS->squery(
						"UPDATE 
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
					$dbS->squery(
						"UPDATE 
							1QQ 
						SET 
							sentToClientFinal = sentToClientFinal +1
						WHERE
							1QQ = 1QQ
						"
						,
						array($table,$id,$value['id'])
						,
						"UPDATE -- loteCorreos :: sentAllEmailFormatosByLote"
					);
					if($dbS->didQuerydied){
						$dbS->rollbackTransaction();
						$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 10);
						return json_encode($arr);				
					}

				}


				$mailResponse=$mailer->sendGroupMailFinalAdministrativo($emailCliente, $nombreCliente, $data, $info['encargado'],$info['factua'],$info['customMail'],$info['adjunto'],$var_system.$info['pdfPath'],$var_system.$info['xmlPath'],$info['customText']);
				if($mailResponse==202){
					$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
				}else{
					$dbS->rollbackTransaction();
					$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 18, 'mailError' => $mailResponse, '$value[emailCliente]' => $value['emailCliente'],  '$value[emailResidente]' => $value['emailResidente'], '$value[nombre]' => $value['nombre'], '$value[pdf]' => $value['pdf']);
					return json_encode($arr);	
				}
				$mailResponse=$mailer->sendGroupMailFinalAdministrativo($emailResidente, $nombreResidente, $data, $info['encargado'],$info['factua'],$info['customMail'],$info['adjunto'],$var_system.$info['pdfPath'],$var_system.$info['xmlPath'],$info['customText']);
				if($mailResponse==202){
					$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
				}else{
					$dbS->rollbackTransaction();
					$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 17, 'mailError' => $mailResponse, '$value[emailCliente]' => $value['emailCliente'], '$value[emailResidente]' => $value['emailResidente'], '$value[nombre]' => $value['nombre'], '$value[pdf]' => $value['pdf'] );
					return json_encode($arr);	
				}
				$mailResponse=$mailer->sendGroupMailFinalAdministrativo($correo_alterno, $nombreAlterno, $data, $info['encargado'],$info['factua'],$info['customMail'],$info['adjunto'],$var_system.$info['pdfPath'],$var_system.$info['xmlPath'],$info['customText']);
				if($mailResponse==202){
					$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
				}else{
					$dbS->rollbackTransaction();
					$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 40, 'mailError' => $mailResponse, '$value[emailCliente]' => $value['emailCliente'], '$value[emailResidente]' => $value['emailResidente'], '$value[nombre]' => $value['nombre'], '$value[pdf]' => $value['pdf'] );
					return json_encode($arr);	
				}
				/*Envio de copia al remitente
				$mailResponse=$mailer->sendMailFinalAdministrativoComprobante($email, $nombre, $value['pdfFinal'],$info['encargado'],$info['factua'],$info['customMail'],$info['adjunto'],$var_system.$info['pdfPath'],$var_system.$info['xmlPath'],$info['customText']);
				if($mailResponse==202){
					$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
				}else{
					$dbS->rollbackTransaction();
					$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 40, 'mailError' => $mailResponse, '$value[emailCliente]' => $value['emailCliente'], '$value[emailResidente]' => $value['emailResidente'], '$value[nombre]' => $value['nombre'], '$value[pdf]' => $value['pdf'] );
					return json_encode($arr);	
				}
				*/
				
				$dbS->squery(
					"UPDATE 
						loteCorreos 
					SET 
						status= 1
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
				
				$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
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
						c.razonSocial,
						o.cotizacion,
						o.obra,
						CASE
							WHEN o.tipo = 1 THEN 'IGUALA'
							WHEN o.tipo = 2 THEN 'UNITARIO'
							ELSE 'Error, contacte a soporte'
						END AS tipoObra,
						fc.id_formatoCampo AS id_formato,
						fc.informeNo,
						fc.tipo AS tipo,
						2 AS tipoNo,
						fc.ensayadoFin,
						rc.id_registrosCampo,
						rc.fecha AS fechaColado,
						rc.claveEspecimen,
						ensayo.fecha AS fechaEnsayado,
						ensayo.pdfFinal,
						ensayo.sentToClientFinal,
						ensayo.dateSentToClientFinal,
						CASE
							WHEN MOD(rc.diasEnsaye,4) = 1 THEN fc.prueba1  
							WHEN MOD(rc.diasEnsaye,4) = 2 THEN fc.prueba2  
							WHEN MOD(rc.diasEnsaye,4) = 3 THEN fc.prueba3  
							WHEN MOD(rc.diasEnsaye,4) = 0 THEN fc.prueba4  
							ELSE 'Error, Contacta a soporte'
						END AS diasEnsaye
					FROM
							cliente AS c
						INNER JOIN 
							obra AS o ON id_cliente = cliente_id
						INNER JOIN 
							ordenDeTrabajo AS ot ON id_obra = obra_id
						INNER JOIN 
							formatoCampo AS fc ON  id_ordenDeTrabajo = ordenDeTrabajo_id
						INNER JOIN
							registrosCampo AS rc ON id_formatoCampo = formatoCampo_id
						INNER JOIN 
							ensayoCilindro AS ensayo ON id_registrosCampo = registrosCampo_id
						LEFT JOIN
							correoDeLote AS cdl ON  cdl.registrosCampo_id = ensayo.registrosCampo_id
					WHERE
						rc.status > 3 AND 
						rc.loteStatus = 0 AND 
						cdl.id_correoDeLote IS NULL AND
						ot.laboratorio_id = 1QQ
					UNION
					SELECT
						c.razonSocial,
						o.cotizacion,
						o.obra,
						CASE
							WHEN o.tipo = 1 THEN 'IGUALA'
							WHEN o.tipo = 2 THEN 'UNITARIO'
							ELSE 'Error, contacte a soporte'
						END AS tipoObra,
						fc.id_formatoCampo AS id_formato,
						fc.informeNo,
						fc.tipo AS tipo,
						3 AS tipoNo,
						fc.ensayadoFin,
						rc.id_registrosCampo,
						rc.fecha AS fechaColado,
						rc.claveEspecimen,
						ensayo.fecha AS fechaEnsayado,
						ensayo.pdfFinal,
						ensayo.sentToClientFinal,
						ensayo.dateSentToClientFinal,
						CASE
							WHEN MOD(rc.diasEnsaye,4) = 1 THEN fc.prueba1  
							WHEN MOD(rc.diasEnsaye,4) = 2 THEN fc.prueba2  
							WHEN MOD(rc.diasEnsaye,4) = 3 THEN fc.prueba3  
							WHEN MOD(rc.diasEnsaye,4) = 0 THEN fc.prueba4  
							ELSE 'Error, Contacta a soporte'
						END AS diasEnsaye
					FROM
							cliente AS c
						INNER JOIN 
							obra AS o ON id_cliente = cliente_id
						INNER JOIN 
							ordenDeTrabajo AS ot ON id_obra = obra_id
						INNER JOIN 
							formatoCampo AS fc ON  id_ordenDeTrabajo = ordenDeTrabajo_id
						INNER JOIN
							registrosCampo AS rc ON id_formatoCampo = formatoCampo_id
						INNER JOIN 
							ensayoCubo AS ensayo ON id_registrosCampo = registrosCampo_id
						LEFT JOIN
							correoDeLote AS cdl ON  cdl.registrosCampo_id = ensayo.registrosCampo_id
					WHERE
						rc.status > 3 AND 
						rc.loteStatus = 0 AND 
						cdl.id_correoDeLote IS NULL AND
						ot.laboratorio_id = 1QQ
					UNION
					SELECT
						c.razonSocial,
						o.cotizacion,
						o.obra,
						CASE
							WHEN o.tipo = 1 THEN 'IGUALA'
							WHEN o.tipo = 2 THEN 'UNITARIO'
							ELSE 'Error, contacte a soporte'
						END AS tipoObra,
						fc.id_formatoCampo AS id_formato,
						fc.informeNo,
						fc.tipo AS tipo,
						4 AS tipoNo,
						fc.ensayadoFin,
						rc.id_registrosCampo,
						rc.fecha AS fechaColado,
						rc.claveEspecimen,
						ensayo.fecha AS fechaEnsayado,
						ensayo.pdfFinal,
						ensayo.sentToClientFinal,
						ensayo.dateSentToClientFinal,
						CASE
							WHEN MOD(rc.diasEnsaye,3) = 1 THEN fc.prueba1  
							WHEN MOD(rc.diasEnsaye,3) = 2 THEN fc.prueba2  
							WHEN MOD(rc.diasEnsaye,3) = 0 THEN fc.prueba3  
							ELSE 'Error, Contacta a soporte'
						END AS diasEnsaye
					FROM
							cliente AS c
						INNER JOIN 
							obra AS o ON id_cliente = cliente_id
						INNER JOIN 
							ordenDeTrabajo AS ot ON id_obra = obra_id
						INNER JOIN 
							formatoCampo AS fc ON  id_ordenDeTrabajo = ordenDeTrabajo_id
						INNER JOIN
							registrosCampo AS rc ON id_formatoCampo = formatoCampo_id
						INNER JOIN 
							ensayoViga AS ensayo ON id_registrosCampo = registrosCampo_id
						LEFT JOIN
							correoDeLote AS cdl ON  cdl.registrosCampo_id = ensayo.registrosCampo_id
					WHERE
						rc.status > 3 AND 
						rc.loteStatus = 0 AND 
						cdl.id_correoDeLote IS NULL AND
						ot.laboratorio_id = 1QQ
					UNION
					SELECT 
						c.razonSocial,
						o.cotizacion,
						o.obra,
						CASE
							WHEN o.tipo = 1 THEN 'IGUALA'
							WHEN o.tipo = 2 THEN 'UNITARIO'
							ELSE 'Error, contacte a soporte'
						END AS tipoObra,
						fr.id_formatoRegistroRev AS id_formato,
						fr.regNo AS informeNo,
						'Revenimiento' AS tipo,
						1 AS tipoNo,
						'0' AS ensayadoFin,
						0 AS id_registrosCampo,
						DATE(fr.createdON) AS fechaColado,
						'N.A.' AS claveEspecimen,
						'N.A.' AS fechaEnsayado,
						fr.pdfFinal,
						fr.sentToClientFinal,
						fr.dateSentToClientFinal,
						'N.A.' AS diasEnsaye
					FROM
							cliente AS c
						INNER JOIN 
							obra AS o ON id_cliente = cliente_id
						INNER JOIN 
							ordenDeTrabajo AS ot ON id_obra = obra_id
						INNER JOIN 
							formatoRegistroRev AS fr ON  id_ordenDeTrabajo = ordenDeTrabajo_id
						LEFT JOIN
							correoDeLote AS cdl ON  id_formatoRegistroRev = formatoRegistroRev_id
					WHERE
						fr.status > 1 AND 
						fr.loteStatus = 0 AND 
						cdl.id_correoDeLote IS NULL AND
						ot.laboratorio_id = 1QQ
			      ",
			      array($laboratorio_id,$laboratorio_id,$laboratorio_id,$laboratorio_id),
			      "SELECT -- LoteCorreos :: getAllAdministrativo : 1 "
			      );

			if(!$dbS->didQuerydied){
						if($arr == "empty")
							$arr = array('estatus' =>"No hay registros", 'error' => 5); 
						
			}else
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en el query, verifica tus datos y vuelve a intentarlo','error' => 6);
		}
		return json_encode($arr);	
	}
	
	public function getAllAdministrativoFULL($token,$rol_usuario_id,$id_cliente){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$usuario_id=$usuario->id_usuario;
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$arr= $dbS->qAll(
				"	SELECT
						c.razonSocial,
						o.cotizacion,
						o.obra,
						CASE
							WHEN o.tipo = 1 THEN 'IGUALA'
							WHEN o.tipo = 2 THEN 'UNITARIO'
							ELSE 'Error, contacte a soporte'
						END AS tipoObra,
						fc.id_formatoCampo AS id_formato,
						fc.informeNo,
						fc.tipo AS tipo,
						2 AS tipoNo,
						fc.ensayadoFin,
						rc.id_registrosCampo,
						rc.fecha AS fechaColado,
						rc.claveEspecimen,
						ensayo.fecha AS fechaEnsayado,
						ensayo.pdfFinal,
						ensayo.sentToClientFinal,
						ensayo.dateSentToClientFinal,
						lc.factua,
						lc.id_loteCorreos,
						DATE(lc.createdON) AS fechaLote
					FROM
							cliente AS c
						INNER JOIN 
							obra AS o ON id_cliente = cliente_id
						INNER JOIN 
							ordenDeTrabajo AS ot ON id_obra = obra_id
						INNER JOIN 
							formatoCampo AS fc ON  id_ordenDeTrabajo = ordenDeTrabajo_id
						INNER JOIN
							registrosCampo AS rc ON id_formatoCampo = formatoCampo_id
						INNER JOIN 
							ensayoCilindro AS ensayo ON id_registrosCampo = registrosCampo_id
						INNER JOIN
							correoDeLote AS cdl ON  cdl.registrosCampo_id = ensayo.registrosCampo_id
						INNER JOIN
							loteCorreos AS lc ON id_loteCorreos = loteCorreos_id
					WHERE
						rc.status > 3 AND 
						ot.laboratorio_id = 1QQ AND
						id_cliente = 1QQ
					UNION
					SELECT
						c.razonSocial,
						o.cotizacion,
						o.obra,
						CASE
							WHEN o.tipo = 1 THEN 'IGUALA'
							WHEN o.tipo = 2 THEN 'UNITARIO'
							ELSE 'Error, contacte a soporte'
						END AS tipoObra,
						fc.id_formatoCampo AS id_formato,
						fc.informeNo,
						fc.tipo AS tipo,
						1 AS tipoNo,
						fc.ensayadoFin,
						rc.id_registrosCampo,
						rc.fecha AS fechaColado,
						rc.claveEspecimen,
						ensayo.fecha AS fechaEnsayado,
						ensayo.pdfFinal,
						ensayo.sentToClientFinal,
						ensayo.dateSentToClientFinal,
						lc.factua,
						lc.id_loteCorreos,
						DATE(lc.createdON) AS fechaLote
					FROM
							cliente AS c
						INNER JOIN 
							obra AS o ON id_cliente = cliente_id
						INNER JOIN 
							ordenDeTrabajo AS ot ON id_obra = obra_id
						INNER JOIN 
							formatoCampo AS fc ON  id_ordenDeTrabajo = ordenDeTrabajo_id
						INNER JOIN
							registrosCampo AS rc ON id_formatoCampo = formatoCampo_id
						INNER JOIN 
							ensayoCubo AS ensayo ON id_registrosCampo = registrosCampo_id
						INNER JOIN
							correoDeLote AS cdl ON  cdl.registrosCampo_id = ensayo.registrosCampo_id
						INNER JOIN
							loteCorreos AS lc ON id_loteCorreos = loteCorreos_id
					WHERE
						rc.status > 3 AND 
						ot.laboratorio_id = 1QQ AND
						id_cliente = 1QQ
					UNION
					SELECT
						c.razonSocial,
						o.cotizacion,
						o.obra,
						CASE
							WHEN o.tipo = 1 THEN 'IGUALA'
							WHEN o.tipo = 2 THEN 'UNITARIO'
							ELSE 'Error, contacte a soporte'
						END AS tipoObra,
						fc.id_formatoCampo AS id_formato,
						fc.informeNo,
						fc.tipo AS tipo,
						4 AS tipoNo,
						fc.ensayadoFin,
						rc.id_registrosCampo,
						rc.fecha AS fechaColado,
						rc.claveEspecimen,
						ensayo.fecha AS fechaEnsayado,
						ensayo.pdfFinal,
						ensayo.sentToClientFinal,
						ensayo.dateSentToClientFinal,
						lc.factua,
						lc.id_loteCorreos,
						DATE(lc.createdON) AS fechaLote
					FROM
							cliente AS c
						INNER JOIN 
							obra AS o ON id_cliente = cliente_id
						INNER JOIN 
							ordenDeTrabajo AS ot ON id_obra = obra_id
						INNER JOIN 
							formatoCampo AS fc ON  id_ordenDeTrabajo = ordenDeTrabajo_id
						INNER JOIN
							registrosCampo AS rc ON id_formatoCampo = formatoCampo_id
						INNER JOIN 
							ensayoViga AS ensayo ON id_registrosCampo = registrosCampo_id
						INNER JOIN
							correoDeLote AS cdl ON  cdl.registrosCampo_id = ensayo.registrosCampo_id
						INNER JOIN
							loteCorreos AS lc ON id_loteCorreos = loteCorreos_id
					WHERE
						rc.status > 3 AND 
						ot.laboratorio_id = 1QQ AND
						id_cliente = 1QQ
					UNION
					SELECT 
						c.razonSocial,
						o.cotizacion,
						o.obra,
						CASE
							WHEN o.tipo = 1 THEN 'IGUALA'
							WHEN o.tipo = 2 THEN 'UNITARIO'
							ELSE 'Error, contacte a soporte'
						END AS tipoObra,
						fr.id_formatoRegistroRev AS id_formato,
						fr.regNo AS informeNo,
						'Revenimiento' AS tipo,
						1 AS tipoNo,
						'0' AS ensayadoFin,
						0 AS id_registrosCampo,
						DATE(fr.createdON) AS fechaColado,
						'N.A.' AS claveEspecimen,
						'N.A.' AS fechaEnsayado,
						fr.pdfFinal,
						fr.sentToClientFinal,
						fr.dateSentToClientFinal,
						lc.factua,
						lc.id_loteCorreos,
						DATE(lc.createdON) AS fechaLote
					FROM
							cliente AS c
						INNER JOIN 
							obra AS o ON id_cliente = cliente_id
						INNER JOIN 
							ordenDeTrabajo AS ot ON id_obra = obra_id
						INNER JOIN 
							formatoRegistroRev AS fr ON  id_ordenDeTrabajo = ordenDeTrabajo_id
						INNER JOIN
							correoDeLote AS cdl ON cdl.formatoRegistroRev_id = fr.id_formatoRegistroRev
						INNER JOIN
							loteCorreos AS lc ON id_loteCorreos = loteCorreos_id
					WHERE
						fr.status > 1 AND 
						ot.laboratorio_id = 1QQ AND
						id_cliente = 1QQ
			      ",
			      array($laboratorio_id,$id_cliente,$laboratorio_id,$id_cliente,$laboratorio_id,$id_cliente,$laboratorio_id,$id_cliente),
			      "SELECT -- LoteCorreos :: getAllAdministrativo : 1 ",$usuario_id
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