<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
include_once("./../../generadorFormatos/GeneradorFormatos.php");
include_once("./../../mailer/Mailer.php");
include_once("./../../mailer/sendgrid-php/sendgrid-php.php");

class footerEnsayo{
	
	/* Variables de utilería */
	private $wc = '/1QQ/';
	
	public function ping2($data){
		$generador = new GeneradorFormatos();

		echo $data;
	}

	public function formatoSeen($token,$rol_usuario_id,$id_formatoCampo,$id_footerEnsayo){
		global $dbS;

		$usuario = new Usuario();

		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$id_usuario=$usuario->id_usuario;
		if($arr['error'] == 0){
			$dbS->beginTransaction();
			$dbS->squery(
				"UPDATE
					footerEnsayo
				SET
					notVistoJLForEnsayoApproval = 0
				WHERE
					id_footerEnsayo = '1QQ'
				",array($id_footerEnsayo),
				"UPDATE -- FooterEnsayo :: generatePDFFinal : 3 var_system[apiRoot]:".$var_system[0]
			);
			if($dbS->didQuerydied){
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en actualizar el estado de lectura del formato, verifica tus datos y vuelve a intentarlo','error' => 24);
				return json_encode($arr);
			}
			$dbS->squery(
				"UPDATE
					formatoCampo
				SET
					notVistoJLForBrigadaApproval = 0
				WHERE
					id_formatoCampo = 1QQ
				",array($id_formatoCampo),
				"UPDATE -- FooterEnsayo :: generatePDFFinal : 3 var_system[apiRoot]:".$var_system[0]
			);

			if(!$dbS->didQuerydied){
				$arr = array('id_footerEnsayo' => $id_footerEnsayo,'estatus' => 'Exito Formato generado','error' => 0);	
				$dbS->commitTransaction();
			}else{
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en actualizar el estado de lectura del formato, verifica tus datos y vuelve a intentarlo','error' => 23);
				return json_encode($arr);
			}
		}
		return json_encode($arr);
	}

	public function autEnsayoForAdmin($token,$rol_usuario_id,$id_formatoCampo,$id_ensayo){
		global $dbS;

		$usuario = new Usuario();
		$mailer = new Mailer();

		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$id_usuario=$usuario->id_usuario;
		if($arr['error'] == 0){
			$dbS->beginTransaction();
			$iguala= $dbS->qvalue(
				"	SELECT
						IF(obra.tipo = 1, 1,0) AS iguala
					FROM
						formatoCampo JOIN 
						ordenDeTrabajo ON id_ordenDeTrabajo = ordenDeTrabajo_id JOIN
						obra ON id_obra=obra_id
					WHERE
						id_formatoCampo =1QQ
				",array($id_formatoCampo),
				"SELECT -- FooterEnsayo :: generatePDFFinal : 1"
			);
			if($dbS->didQuerydied || ($iguala == "empty")){
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en autorizar ensayo, verifica tus datos y vuelve a intentarlo','error' => 34);
				return json_encode($arr);
			}

			$a = $dbS->qarrayA(
				"	SELECT
						tipo
					FROM
						footerEnsayo
					WHERE
						formatoCampo_id =1QQ
				",array($id_formatoCampo),
				"SELECT -- FooterEnsayo :: generatePDFFinal : 1"
			);
			if(!$dbS->didQuerydied && !($a=="empty")){
				$table = "";
				switch($a['tipo']){
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
				}
				$b = $dbS->qarrayA(
					"	SELECT
							pdfFinal,
							jefaLabApproval_id,
							ensayo.status,
							grupo,
							registrosCampo.status AS regSatatus,
							registrosCampo.formatoCampo_id AS formatoCampo_id
						FROM
							1QQ AS ensayo INNER JOIN registrosCampo ON registrosCampo_id = id_registrosCampo
						WHERE
							1QQ = 1QQ
					",array($table,$id,$id_ensayo),
					"SELECT -- FooterEnsayo :: generatePDFFinal : 1"
				);
				//Cachamos la excepcion
				if(!$dbS->didQuerydied && !($b=="empty")){
					if($b['status'] == 0 || $b['status'] == 2 || $b['regSatatus'] == 0 || $b['regSatatus'] == 3){ // 0 es edicion TMU y 2 es edicion JL. Ambos estados son invalidos para autorizar
						$dbS->rollbackTransaction();
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en autorizar ensayo, no puedes autorizar antes de que el TMU ceda sus derechos de escritura','error' => 20);
						return json_encode($arr);
					}

					$dbS->squery(
						"UPDATE
							footerEnsayo
						SET
							ensayosAwaitingApproval = ensayosAwaitingApproval-1
						WHERE
							formatoCampo_id =1QQ
						",array($id_formatoCampo),
						"UPDATE -- FooterEnsayo :: generatePDFFinal : 3 var_system[apiRoot]:".$var_system[0]
					);
					if($dbS->didQuerydied){
						$dbS->rollbackTransaction();
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en autorizar ensayo, verifica tus datos y vuelve a intentarlo','error' => 24);
						return json_encode($arr);
					}
					$dbS->squery(
						"UPDATE
							registrosCampo
						SET
							status = 4
						WHERE
							formatoCampo_id =1QQ 
							AND grupo = 1QQ
						",array($id_formatoCampo,$b['grupo']),
						"UPDATE -- FooterEnsayo :: generatePDFFinal : 3 var_system[apiRoot]:".$var_system[0]
					);
					if($dbS->didQuerydied){
						$dbS->rollbackTransaction();
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en autorizar ensayo, verifica tus datos y vuelve a intentarlo','error' => 24);
						return json_encode($arr);
					}
					$dbS->squery(
						"UPDATE
							1QQ
						SET
							jefaLabApproval_id = '1QQ',
							status = 4
						WHERE
							1QQ = 1QQ
						"
						,array($table,$id_usuario,$id,$id_ensayo),
						"UPDATE -- FooterEnsayo :: generatePDFFinal : 3 var_system[apiRoot]:".$var_system[0]
					);

					if(!$dbS->didQuerydied){
						if($iguala == 1){
							$arr=json_decode($this->sentIfIguala($id_formatoCampo,$table,$id_ensayo,$id),true);
							if($arr['error'] == 0){
								$arr = array('id_footerEnsayo' => $id_footerEnsayo,'estatus' => 'Exito Formato generado','error' => 0);	
								$dbS->commitTransaction();
							}else{
								$dbS->rollbackTransaction();
								return json_encode($arr);
							}
						}else{
							$arr = array('id_footerEnsayo' => $id_footerEnsayo,'estatus' => 'Exito Formato generado','error' => 0);	
							$dbS->commitTransaction();
						}
					}else{
						$dbS->rollbackTransaction();
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en autorizar ensayo, verifica tus datos y vuelve a intentarlo','error' => 23);
						return json_encode($arr);
					}
				}else{
					$dbS->rollbackTransaction();
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en autorizar ensayo, verifica tus datos y vuelve a intentarlo','error' => 21);
					return json_encode($arr);
				}
				
			}else{
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en autorizar ensayo, verifica tus datos y vuelve a intentarlo','error' => 22);
			}
		}
		return json_encode($arr);
	}


	private function sentIfIguala($id_formatoCampo,$table,$id_ensayo,$id){
		global $dbS;
		$mailer = new Mailer();

		$info = $dbS->qarrayA(
			"   SELECT
					id_cliente,
					id_obra,
					id_ordenDeTrabajo,
					cliente.email AS emailCliente,
					obra.correo_residente AS emailResidente,
					obra.correo_alterno AS correo_alterno,
					CONCAT(nombre,'(',razonSocial,')') AS nombre,
					email
				FROM
					cliente,
					obra,
					ordenDeTrabajo,
					formatoCampo
				WHERE
					formatoCampo.ordenDeTrabajo_id = ordenDeTrabajo.id_ordenDeTrabajo AND
					ordenDeTrabajo.obra_id = obra.id_obra AND
					obra.cliente_id = cliente.id_cliente AND
					id_formatoCampo = 1QQ
			",
			array($id_formatoCampo),
			"SELECT  -- FormatoCampo :: completeFormato : 4"
		);
		if(!$dbS->didQuerydied && ($info != "empty")){
			try{
				$dirDatabase = $dbS->qvalue(
					"SELECT pdfFinal FROM 1QQ WHERE 1QQ=1QQ",
					array($table,$id,$id_ensayo),"SELECT  -- FormatoCampo :: completeFormato : 5"
				);
				if($dbS->didQuerydied || ($dirDatabase == "empty")){ // Si no murio la query de guardar el preliminar en BD
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 40);
					return json_encode($arr);
				}
				//  Envio del primer correo
				$resp=$mailer->sendMailFinal($info['emailCliente'], $info['nombre'], $dirDatabase);
				if($resp==202){
					$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato enviado','error' => 0);	
				}else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 106, 'sendGridError'=>$resp);
					return json_encode($arr);
				}
				//  Envio del segundo correo
				$resp=$mailer->sendMailFinal($info['emailResidente'], $info['nombre'], $dirDatabase);
				if($resp==202){
					$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato enviado','error' => 0);	
				}else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 107, 'sendGridError'=>$resp);
					return json_encode($arr);
				}
				//  Envio del tercer correo
				$resp=$mailer->sendMailFinal($info['correo_alterno'], $info['nombre'], $dirDatabase);
				if($resp==202){
					$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato enviado','error' => 0);	
				}else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 108, 'sendGridError'=>$resp);
					return json_encode($arr);
				}

				$dbS->squery(
					"UPDATE
						1QQ
					SET
						sentToClientFinal = sentToClientFinal + 1,
						dateSentToClientFinal = CURDATE()
					WHERE
						1QQ = 1QQ
					"
					,array($table,$id,$id_ensayo),
					"UPDATE -- FooterEnsayo :: generatePDFFinal : 3 var_system[apiRoot]:".$var_system[0]
				);

				if($dbS->didQuerydied){
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente:'.$e->getMessage(),'error' => 7);
					return json_encode($arr);
				}

				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Exito','error' => 0);
				return json_encode($arr);

			}catch(Exception $e){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente:'.$e->getMessage(),'error' => 7);
				return json_encode($arr);
			}
		}else{
			$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 108, 'sendGridError'=>$resp);
			return json_encode($arr);
		}
	}


	public function generatePDFFinal($token,$rol_usuario_id,$id_formatoCampo,$id_ensayo){
		global $dbS;

		$usuario = new Usuario();
		$generador = new GeneradorFormatos();

		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			$a = $dbS->qarrayA(
				"	SELECT
						tipo
					FROM
						footerEnsayo
					WHERE
						formatoCampo_id =1QQ
				",array($id_formatoCampo),
				"SELECT -- FooterEnsayo :: generatePDFFinal : 1"
			);
			if(!$dbS->didQuerydied && !($a=="empty")){
				$table = "";
				switch($a['tipo']){
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
				}
				$var_system = $dbS->qarrayA(
					"	SELECT
							apiRoot
						FROM
							systemstatus
						ORDER BY id_systemstatus DESC;
					",array(),
					"SELECT -- FooterEnsayo :: generatePDFFinal : 2"
				);
				if($dbS->didQuerydied || ($var_system == "empty")){
					$dbS->rollbackTransaction();
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la generacion del formato','error' => 20);
					return json_encode($arr);
				}
				$registrosCampo_id = $dbS->qvalue(
					"	SELECT
							registrosCampo_id
						FROM
							1QQ
						WHERE
							1QQ =1QQ
					",array($table,$id,$id_ensayo),
					"SELECT -- FooterEnsayo :: generatePDFFinal : 1"
				);
				if($dbS->didQuerydied || ($registrosCampo_id == "empty")){
					$dbS->rollbackTransaction();
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la generacion del formato','error' => 20);
					return json_encode($arr);
				}
				$hora_de_creacion = getdate();
				$target_dir = "./../../../SystemData/FormatosFinalesData/".$a['tipo']."/".$id_formatoCampo."/";
				$dirDatabase = $var_system['apiRoot']."SystemData/FormatosFinalesData/".$a['tipo']."/".$id_formatoCampo."/"."Final".$a['tipo']."(".$hora_de_creacion['hours']."-".$hora_de_creacion['minutes']."-".$hora_de_creacion['seconds'].")".".pdf";
				if (!file_exists($target_dir)) {
					mkdir($target_dir, 0777, true);
				}
				$target_dir=$target_dir."Final".$a['tipo']."(".$hora_de_creacion['hours']."-".$hora_de_creacion['minutes']."-".$hora_de_creacion['seconds'].")".".pdf";
				//Cachamos la excepcion
				try{
					$arr=json_decode( $generador->generateInformeCampo($token,$rol_usuario_id,$registrosCampo_id,$target_dir),true);
						
					if($arr['error'] > 0){
						$dbS->rollbackTransaction();
						return json_encode($arr);
					}
					$dbS->squery(
						"UPDATE
							1QQ
						SET
							pdfFinal = '1QQ'
						WHERE
							1QQ = 1QQ
						"
						,array($table,$dirDatabase,$id,$id_ensayo),
						"UPDATE -- FooterEnsayo :: generatePDFFinal : 3 var_system[apiRoot]:".$var_system[0]
					);
				}catch(Exception $e){
					$dbS->rollbackTransaction();
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la generacion del formato:'.$e->getMessage(),'error' => 7);
					return json_encode($arr);
				}
				if(!$dbS->didQuerydied){
					$arr = array('id_footerEnsayo' => $id_footerEnsayo,'estatus' => 'Exito Formato generado','error' => 0);	
					$dbS->commitTransaction();
				}else{
					$dbS->rollbackTransaction();
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la generacion del formato:'.$e->getMessage(),'error' => 30);
					return json_encode($arr);
				}
			}else{
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en generar formato , verifica tus datos y vuelve a intentarlo','error' => 11);
			}
		}
		return json_encode($arr);
	}


	public function generatePDFEnsayo($token,$rol_usuario_id,$id_footerEnsayo){
		global $dbS;

		$usuario = new Usuario();
		$mailer = new Mailer();
		$generador = new GeneradorFormatos();

		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			$a = $dbS->qarrayA(
				"	SELECT
						tipo
					FROM
						footerEnsayo
					WHERE
						id_footerEnsayo =1QQ
				",array($id_footerEnsayo),
				"SELECT -- FooterEnsayo :: generatePDFEnsayo : 1"
			);
			if(!$dbS->didQuerydied && !($a=="empty")){
				$table = "";
				switch($a['tipo']){
					case "CILINDRO":
						$table="ensayoCilindro";
					break;
					case "CUBO":
						$table="ensayoCubo";
					break;
					case "VIGAS":
						$table="ensayoViga";
					break;
				}
				$var_system = $dbS->qarrayA(
					"	SELECT
							apiRoot
						FROM
							systemstatus
						ORDER BY id_systemstatus DESC;
					",array(),
					"SELECT -- FooterEnsayo :: generatePDFEnsayo : 2"
				);
				if($dbS->didQuerydied || ($var_system == "empty")){
					$dbS->rollbackTransaction();
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la generacion del formato','error' => 20);
					return json_encode($arr);
				}
				
				$hora_de_creacion = getdate();
				$target_dir = "./../../../SystemData/FormatosGabsData/".$a['tipo']."/".$id_footerEnsayo."/";
				$dirDatabase = $var_system['apiRoot']."SystemData/FormatosGabsData/".$a['tipo']."/".$id_footerEnsayo."/"."ensayos".$a['tipo']."(".$hora_de_creacion['hours']."-".$hora_de_creacion['minutes']."-".$hora_de_creacion['seconds'].")".".pdf";
				if (!file_exists($target_dir)) {
					mkdir($target_dir, 0777, true);
				}
				$target_dir=$target_dir."ensayos".$a['tipo']."(".$hora_de_creacion['hours']."-".$hora_de_creacion['minutes']."-".$hora_de_creacion['seconds'].")".".pdf";
				//Cachamos la excepcion
				try{
					switch($a['tipo']){
						case "CILINDRO":
							$arr=json_decode( $generador->generateEnsayoCilindros($token,$rol_usuario_id,$id_footerEnsayo,$target_dir),true);
						break;
						case "CUBO":
							$arr=json_decode( $generador->generateEnsayoCubos($token,$rol_usuario_id,$id_footerEnsayo,$target_dir),true);
						break;
						case "VIGAS":
							$arr=json_decode( $generador->generateEnsayoVigas($token,$rol_usuario_id,$id_footerEnsayo,$target_dir),true);
						break;
					}

					if($arr['error'] > 0){
						$dbS->rollbackTransaction();
						return json_encode($arr);
					}

					$dbS->squery(
						"UPDATE
							footerEnsayo
						SET
							preliminarGabs = '1QQ'
						WHERE
							id_footerEnsayo = 1QQ
						"
						,array($dirDatabase,$id_footerEnsayo),
						"UPDATE -- FooterEnsayo :: generatePDFEnsayo : 3 var_system[apiRoot]:".$var_system[0]
					);
				}catch(Exception $e){
					$dbS->rollbackTransaction();
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la generacion del formato:'.$e->getMessage(),'error' => 7);
					return json_encode($arr);
				}
				$arr = array('id_footerEnsayo' => $id_footerEnsayo,'estatus' => 'Exito Formato generado','error' => 0);	
				$dbS->commitTransaction();
			}else{
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en generar formato , verifica tus datos y vuelve a intentarlo','error' => 11);
			}
		}
		return json_encode($arr);
	}

	/* BEGINS  completeFormato*/

	public function completeFormato($token,$rol_usuario_id,$id_footerEnsayo){
		global $dbS;

		$usuario = new Usuario();
		$mailer = new Mailer();

		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			$dbS->squery(
				"UPDATE
					footerEnsayo
				SET
					status = 2
				WHERE
					active = 1 AND
					id_footerEnsayo = 1QQ
				"
				,array($id_footerEnsayo),
				"UPDATE -- FooterEnsayo :: completeFormato : 1"
			);
			if(!$dbS->didQuerydied){
				/* 
					Obtenemos tipo para poder identificar la tabla de ensayo
					Obtenemos el formato de campo para el analizar si ya todos estan ensaydos y que todos tengan un status correcto.
				*/
				$a = $dbS->qarrayA(
					"SELECT
						formatoCampo_id,
						tipo
					FROM
						footerEnsayo
					WHERE
						id_footerEnsayo =1QQ
					",array($id_footerEnsayo),
					"SELECT -- FooterEnsayo :: completeFormato : 2"
				);
				if(!$dbS->didQuerydied && !($a=="empty")){
					$table = "";
					switch($a['tipo']){
						case "CILINDRO":
							$id= "id_ensayoCilindro";
							$table="ensayoCilindro";
						break;
						case "CUBO":
							$id= "id_ensayoCubo";
							$table="ensayoCubo";
						break;
						case "VIGAS":
							$id= "id_ensayoViga";
							$table="ensayoViga";
						break;
					}
					$homeAloneClubMembers = $dbS->qvalue(
							"SELECT
								COUNT(*) AS No
							FROM
								registrosCampo LEFT JOIN 
								1QQ AS ensayo ON registrosCampo_id = id_registrosCampo
							WHERE
								registrosCampo.formatoCampo_id =1QQ
								AND ensayo.active IS NULL
						",array($table,$a['formatoCampo_id']),
						"SELECT -- FooterEnsayo :: completeFormato : 3"
					);
					if($dbS->didQuerydied || ($homeAloneClubMembers=="empty") ){
						$dbS->rollbackTransaction();
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en verificar la integridad del formato, verifica tus datos y vuelve a intentarlo','error' => 12);
						return json_encode($arr);
					}
					if($homeAloneClubMembers != 0){
						$dbS->rollbackTransaction();
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error: Solo puedes completar cuando hayas ensayado TODOS los especimenes que el jefe de brigada tomo en obra','error' => 20);
						return json_encode($arr);
					}

					$underAgeMemebrsClub = $dbS->qvalue(
						"SELECT
							COUNT(*) AS No
						FROM
							(
								SELECT
									status,
									1QQ AS id
								FROM
									1QQ 
								WHERE
									status IN (0,1,2,3)
									AND formatoCampo_id =1QQ
							UNION
								SELECT 
									status,
									id_registrosCampo AS id
								FROM 
									registrosCampo
								WHERE 
									status IN (0,3)
									AND formatoCampo_id =1QQ
							) AS t1
						
						",array($id,$table,$a['formatoCampo_id'],$a['formatoCampo_id']),
						"SELECT -- FooterEnsayo :: completeFormato : 4"
					);
					if($dbS->didQuerydied || ($underAgeMemebrsClub=="empty") ){
						$dbS->rollbackTransaction();
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en verificar la integridad del formato, verifica tus datos y vuelve a intentarlo','error' => 13);
						return json_encode($arr);
					}
					if($underAgeMemebrsClub != 0){
						$dbS->rollbackTransaction();
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error: Todos los ensayos deben estar marcados como completados/autorizados para poder continuar','error' => 30);
						return json_encode($arr);
					}
					$noOfRegistries = $dbS->qvalue(
						"SELECT
							COUNT(*)
						FROM
							1QQ
						WHERE
							footerEnsayo_id =1QQ
						",array($table,$id_footerEnsayo),
						"SELECT-- FooterEnsayo :: completeFormato : 5"
					);
					if(!$dbS->didQuerydied && !($noOfRegistries=="empty")){
						/* 
							Nov 19 -- Jose Montiel
							Se retiro el codigo que disminuia los penidng ensayos de formatoCampo, y se migraron a los complete de cada ensayo. Esto con la finalidad de saber si ya se puede completar el ensayo.
							La funcionalidad solia estar aqui, pues antes se combinaban los registros provenientes de diferentes formatos de campo,
							al completarse, se disminuia individualmente a cada registro.
						*/
						$dbS->squery(
							"UPDATE
								formatoCampo
							SET
								status = 2
							WHERE
								id_formatoCampo = 1QQ
							"
							,array($a['formatoCampo_id']),
							"UPDATE-- FooterEnsayo :: completeFormato : 6"
						);

						if(!$dbS->didQuerydied){
							/* 
								Se  mueven los ENSAYOS a estado 5, 'Bloquedo por el padre.'
								0 - Edicion del TMU
								1 - Semi completado TMU
								2 - Edicion JL
								3 - Completado JL
								4 - Autorizado
								5 - Bloquedo por el padre.
							*/
							$dbS->squery(
								"UPDATE
									1QQ
								SET
									status = 5
								WHERE
									active = 1 AND
									footerEnsayo_id = 1QQ
								"
								,array($table,$id_footerEnsayo),
								"UPDATE-- FooterEnsayo :: completeFormato : 7"
							);
							if($dbS->didQuerydied){
								$dbS->rollbackTransaction();
								$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , verifica tus datos y vuelve a intentarlo','error' => 40);
								return json_encode($arr);
							}
							$dbS->squery(
								"UPDATE
									registrosCampo
								SET
									status = 5
								WHERE
									active = 1 AND
									formatoCampo_id = 1QQ
								"
								,array($a['formatoCampo_id']),
								"UPDATE-- FooterEnsayo :: completeFormato : 8"
							);

							if(!$dbS->didQuerydied){
								$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
							}else{
								$dbS->rollbackTransaction();
								$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , verifica tus datos y vuelve a intentarlo','error' => 10);
							}
						}else{
							$dbS->rollbackTransaction();
							$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , verifica tus datos y vuelve a intentarlo','error' => 10);
						}
					}else{
						$dbS->rollbackTransaction();
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , verifica tus datos y vuelve a intentarlo','error' => 10);
					}
				}else{
					$dbS->rollbackTransaction();
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , verifica tus datos y vuelve a intentarlo','error' => 12);
				}
			}else{
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , verifica tus datos y vuelve a intentarlo','error' => 11);
			}		
		}
		$dbS->commitTransaction();
		return json_encode($arr);
	}

	/* ENDS  completeFormato*/


	public function isTheWholeFamilyHereAndComplete($token,$rol_usuario_id,$id_footerEnsayo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			$footerEnsayoInfo = $dbS->qarrayA(
				"	SELECT
						formatoCampo_id,
						tipo
					FROM
						footerEnsayo
					WHERE
						id_footerEnsayo =1QQ
				",array($id_footerEnsayo),
				"SELECT-- FooterEnsayo :: isTheWholeFamilyHereAndComplete : 1"
			);
			if($dbS->didQuerydied || ($footerEnsayoInfo=="empty") ){
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en verificar la integridad del formato, verifica tus datos y vuelve a intentarlo','error' => 10);
				return json_encode($arr);
			}
			$table = "";
			switch($footerEnsayoInfo['tipo']){
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
			}
			$homeAloneClubMembers = $dbS->qvalue(
					"SELECT
						COUNT(*) AS No
					FROM
						registrosCampo LEFT JOIN 
						1QQ AS ensayo ON registrosCampo_id = id_registrosCampo
					WHERE
						registrosCampo.formatoCampo_id =1QQ
						AND ensayo.active IS NULL
					
				",array($table,$footerEnsayoInfo['formatoCampo_id']),
				"SELECT -- FooterEnsayo :: isTheWholeFamilyHereAndComplete : 2"
			);
			
			if($dbS->didQuerydied || ($homeAloneClubMembers=="empty") ){
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en verificar la integridad del formato, verifica tus datos y vuelve a intentarlo','error' => 12);
				return json_encode($arr);
			}
			if($homeAloneClubMembers != 0){
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error: Solo puedes completar cuando hayas ensayado TODOS los especimenes que el jefe de brigada tomo en obra','error' => 20);
				return json_encode($arr);
			}
			$underAgeMemebrsClub = $dbS->qvalue(
					"SELECT
						COUNT(*) AS No
					FROM
						1QQ 
					WHERE
						status IN (0,2)
						AND formatoCampo_id =1QQ
					
				",array($table,$footerEnsayoInfo['formatoCampo_id']),
				"SELECT -- FooterEnsayo :: isTheWholeFamilyHereAndComplete : 3"
			);
			
			if($dbS->didQuerydied || ($underAgeMemebrsClub=="empty") ){
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en verificar la integridad del formato, verifica tus datos y vuelve a intentarlo','error' => 13);
				return json_encode($arr);
			}
			if($homeAloneClubMembers != 0){
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error: Todos los ensayos deben estar marcados como completados para poder continuar','error' => 30);
				return json_encode($arr);
			}
			$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Exito','error' => 0);
		}
		$dbS->commitTransaction();
		return json_encode($arr);
	}
	
	public function getLog($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$arr = $dbS->qAll(
				    "SELECT
						id_log, 
						queryType,
						status
					FROM
						log LIMIT 10000
				",array(),
				"SELECT -- FooterEnsayo :: generatePDFEnsayo : 1"
			);
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en generar formato , verifica tus datos y vuelve a intentarlo','error' => 11);
			}else if($arr=="empty"){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en generar formato , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	public function getHistoricEnsayos($token,$rol_usuario_id,$obra_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr = $dbS->qAll(
				"SELECT
					O.obra,
					fc.informeNo,
					fc.tipo AS tipo,
					CASE
						WHEN fc.tipo = 'CILINDRO' THEN 2
						WHEN fc.tipo = 'CUBO' THEN 3
						WHEN fc.tipo = 'VIGAS' THEN 4
						ELSE 0
					END AS tipoNo,
					rc.claveEspecimen,
					rc.fecha AS fechaColado,
					CASE
						WHEN MOD(rc.diasEnsaye,4) = 1 THEN fc.prueba1  
						WHEN MOD(rc.diasEnsaye,4) = 2 THEN fc.prueba2  
						WHEN MOD(rc.diasEnsaye,4) = 3 THEN fc.prueba3  
						WHEN MOD(rc.diasEnsaye,4) = 0 THEN fc.prueba4  
						ELSE 'Error, Contacta a soporte'
					END AS diasEnsaye,
					CASE
						WHEN MOD(diasEnsaye,4) = 1 THEN DATE_ADD(rc.fecha, INTERVAL prueba1 DAY)
						WHEN MOD(diasEnsaye,4) = 2 THEN DATE_ADD(rc.fecha, INTERVAL prueba2 DAY)  
						WHEN MOD(diasEnsaye,4) = 3 THEN DATE_ADD(rc.fecha, INTERVAL prueba3 DAY)  
						WHEN MOD(diasEnsaye,4) = 0 THEN DATE_ADD(rc.fecha, INTERVAL prueba4 DAY)
						ELSE 'Error, Contacta a soporte'
					END AS fechaEnsayeAsignado,
					ensayo.fecha AS fechaEnsayoReal,
					ensayo.carga AS carga,
					ensayo.resistencia,
					ensayo.footerEnsayo_id AS id_footerEnsayo
				FROM
						obra AS O 
					INNER JOIN 
						ordenDeTrabajo AS ODT 
					ON id_obra = obra_id
					INNER JOIN 
						formatoCampo AS fc
					ON id_ordenDeTrabajo = ordenDeTrabajo_id 
					INNER JOIN
						registrosCampo AS rc
					ON id_formatoCampo = formatoCampo_id
					INNER JOIN
						ensayoCubo AS ensayo
					ON id_registrosCampo = registrosCampo_id
				WHERE 
					O.id_obra=1QQ
				UNION
				SELECT
					O.obra,
					fc.informeNo,
					fc.tipo AS tipo,
					CASE
						WHEN fc.tipo = 'CILINDRO' THEN 2
						WHEN fc.tipo = 'CUBO' THEN 3
						WHEN fc.tipo = 'VIGAS' THEN 4
						ELSE 0
					END AS tipoNo,
					rc.claveEspecimen,
					rc.fecha AS fechaColado,
					CASE
						WHEN MOD(rc.diasEnsaye,4) = 1 THEN fc.prueba1  
						WHEN MOD(rc.diasEnsaye,4) = 2 THEN fc.prueba2  
						WHEN MOD(rc.diasEnsaye,4) = 3 THEN fc.prueba3  
						WHEN MOD(rc.diasEnsaye,4) = 0 THEN fc.prueba4  
						ELSE 'Error, Contacta a soporte'
					END AS diasEnsaye,
					CASE
						WHEN MOD(diasEnsaye,4) = 1 THEN DATE_ADD(rc.fecha, INTERVAL prueba1 DAY)
						WHEN MOD(diasEnsaye,4) = 2 THEN DATE_ADD(rc.fecha, INTERVAL prueba2 DAY)  
						WHEN MOD(diasEnsaye,4) = 3 THEN DATE_ADD(rc.fecha, INTERVAL prueba3 DAY)  
						WHEN MOD(diasEnsaye,4) = 0 THEN DATE_ADD(rc.fecha, INTERVAL prueba4 DAY)
						ELSE 'Error, Contacta a soporte'
					END AS fechaEnsayeAsignado,
					ensayo.fecha AS fechaEnsayoReal,
					ensayo.carga AS carga,
					ensayo.resistencia,
					ensayo.footerEnsayo_id AS id_footerEnsayo
				FROM
						obra AS O 
					INNER JOIN 
						ordenDeTrabajo AS ODT 
					ON id_obra = obra_id
					INNER JOIN 
						formatoCampo AS fc
					ON id_ordenDeTrabajo = ordenDeTrabajo_id 
					INNER JOIN
						registrosCampo AS rc
					ON id_formatoCampo = formatoCampo_id
					INNER JOIN
						ensayoCilindro AS ensayo
					ON id_registrosCampo = registrosCampo_id
				WHERE 
					O.id_obra=1QQ
				UNION
				SELECT
					O.obra,
					fc.informeNo,
					fc.tipo AS tipo,
					CASE
						WHEN fc.tipo = 'CILINDRO' THEN 2
						WHEN fc.tipo = 'CUBO' THEN 3
						WHEN fc.tipo = 'VIGAS' THEN 4
						ELSE 0
					END AS tipoNo,
					rc.claveEspecimen,
					rc.fecha AS fechaColado,
					CASE
						WHEN MOD(rc.diasEnsaye,3) = 1 THEN fc.prueba1  
						WHEN MOD(rc.diasEnsaye,3) = 2 THEN fc.prueba2  
						WHEN MOD(rc.diasEnsaye,3) = 0 THEN fc.prueba3  
						ELSE 'Error, Contacta a soporte'
					END AS diasEnsaye,
					CASE
						WHEN MOD(diasEnsaye,3) = 1 THEN DATE_ADD(rc.fecha, INTERVAL prueba1 DAY)
						WHEN MOD(diasEnsaye,3) = 2 THEN DATE_ADD(rc.fecha, INTERVAL prueba2 DAY)  
						WHEN MOD(diasEnsaye,3) = 0 THEN DATE_ADD(rc.fecha, INTERVAL prueba3 DAY)  
						ELSE 'Error, Contacta a soporte'
					END AS fechaEnsayeAsignado,
					ensayo.fecha AS fechaEnsayoReal,
					ensayo.carga AS carga,
					ensayo.mr AS resistencia,
					ensayo.footerEnsayo_id AS id_footerEnsayo
				FROM
						obra AS O 
					INNER JOIN 
						ordenDeTrabajo AS ODT 
					ON id_obra = obra_id
					INNER JOIN 
						formatoCampo AS fc
					ON id_ordenDeTrabajo = ordenDeTrabajo_id 
					INNER JOIN
						registrosCampo AS rc
					ON id_formatoCampo = formatoCampo_id
					INNER JOIN
						ensayoViga AS ensayo
					ON id_registrosCampo = registrosCampo_id
				WHERE 
					O.id_obra=1QQ
				",array($obra_id,$obra_id,$obra_id),
				"SELECT -- FooterEnsayo :: generatePDFEnsayo : 1"
			);
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en generar formato , verifica tus datos y vuelve a intentarlo','error' => 11);
			}else if($arr=="empty"){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en generar formato , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	public function getAwaitingApproval($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id = $usuario->laboratorio_id;
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			$s= $dbS->qAll(
				"SELECT
					id_formatoCampo AS id,
					footerEnsayo.id_footerEnsayo AS id_footerEnsayo,
					CONCAT(nombre,' ',apellido) AS nombre,
					formatoCampo.tipo AS tipo,
					ensayosAwaitingApproval,
					notVistoJLForBrigadaApproval,
					notVistoJLForEnsayoApproval,
					CASE
						WHEN notVistoJLForBrigadaApproval = 1 AND ensayosAwaitingApproval = 0      THEN 'Revisar cambios JB'
						WHEN notVistoJLForBrigadaApproval = 1 AND ensayosAwaitingApproval IS NULL  THEN 'Revisar cambios JB'
						WHEN notVistoJLForBrigadaApproval = 1 AND ensayosAwaitingApproval > 0      THEN 'Autorizar y generar PDF'
						WHEN notVistoJLForBrigadaApproval = 0 AND ensayosAwaitingApproval > 0      THEN 'Autorizar y generar PDF'
					END AS accReq,
					informeNo AS informeNo,
					ordenDeTrabajo_id,
					CASE
						WHEN formatoCampo.tipo = 'CILINDRO' THEN 2
						WHEN formatoCampo.tipo = 'CUBO' THEN 3
						WHEN formatoCampo.tipo = 'VIGAS' THEN 4
						ELSE 0
					END AS tipoNo
				FROM
				    ordenDeTrabajo,
					formatoCampo LEFT JOIN 
					footerEnsayo ON formatoCampo_id = id_formatoCampo LEFT JOIN 
					usuario ON encargado_id = id_usuario
				WHERE
					id_ordenDeTrabajo = ordenDeTrabajo_id AND
					formatoCampo.status > 0 AND
					(
					(footerEnsayo.ensayosAwaitingApproval > 0 AND
					notVistoJLForEnsayoApproval > 0) OR
					notVistoJLForBrigadaApproval > 0
					) AND
					ordenDeTrabajo.laboratorio_id = 1QQ
				UNION
				SELECT 
					id_formatoRegistroRev AS id,
					'N.A.' AS id_footerEnsayo,
					'N.A.' AS nombre,
					'REVENIMIENTO' AS tipo,
					'N.A.' AS ensayosAwaitingApproval,
					notVistoJLForBrigadaApproval,
					'N.A.' AS notVistoJLForEnsayoApproval,
					IF(jefaLabApproval_id IS NOT NULL, 'Completado', 'Autorizar y generar PDF') AS accReq,
					regNo AS informeNo,
					ordenDeTrabajo_id,
					'1' AS tipoNo
				FROM
					formatoRegistroRev,
					ordenDeTrabajo
				WHERE
					id_ordenDeTrabajo = ordenDeTrabajo_id
					AND formatoRegistroRev.status > 0
					AND laboratorio_id = 1QQ
					AND notVistoJLForBrigadaApproval > 0
				",
				array($laboratorio_id,$laboratorio_id),
				"SELECT -- FooterEnsayo :: getAwaitingApproval : 1"
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
		$dbS->commitTransaction();
		return json_encode($arr);
	}

	public function getAwaitingApprovalSeen($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id = $usuario->laboratorio_id;
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			$s= $dbS->qAll(
				"SELECT
					id_formatoCampo AS id,
					footerEnsayo.id_footerEnsayo AS id_footerEnsayo,
					CONCAT(nombre,' ',apellido) AS nombre,
					formatoCampo.tipo AS tipo,
					ensayosAwaitingApproval,
					notVistoJLForBrigadaApproval,
					CASE
						WHEN notVistoJLForBrigadaApproval = 1 AND ensayosAwaitingApproval = 0      THEN 'Revisar cambios JB'
						WHEN notVistoJLForBrigadaApproval = 1 AND ensayosAwaitingApproval IS NULL  THEN 'Revisar cambios JB'
						WHEN notVistoJLForBrigadaApproval = 1 AND ensayosAwaitingApproval > 0      THEN 'Autorizar y generar PDF'
						WHEN notVistoJLForBrigadaApproval = 0 AND ensayosAwaitingApproval > 0      THEN 'Autorizar y generar PDF'
						WHEN notVistoJLForBrigadaApproval = 0 AND ensayosAwaitingApproval = 0 AND  formatoCampo.status = 1 AND formatoCampo.ensayadoFin = 0  THEN 'Completar Informe'
						ELSE CONCAT('Algo salio mal... contacte a soporte (ERR-',notVistoJLForBrigadaApproval,ensayosAwaitingApproval,formatoCampo.status,formatoCampo.ensayadoFin,')')
					END AS accReq,
					informeNo AS informeNo,
					ordenDeTrabajo_id,
					CASE
						WHEN formatoCampo.tipo = 'CILINDRO' THEN 2
						WHEN formatoCampo.tipo = 'CUBO' THEN 3
						WHEN formatoCampo.tipo = 'VIGAS' THEN 4
						ELSE 0
					END AS tipoNo
				FROM
				    ordenDeTrabajo,
					formatoCampo LEFT JOIN 
					footerEnsayo ON formatoCampo_id = id_formatoCampo LEFT JOIN 
					usuario ON encargado_id = id_usuario
				WHERE
					id_ordenDeTrabajo = ordenDeTrabajo_id 
					AND formatoCampo.status > 0 
					AND notVistoJLForEnsayoApproval = 0
					AND 
						(
							footerEnsayo.ensayosAwaitingApproval > 0 OR 
							(footerEnsayo.ensayosAwaitingApproval = 0 AND formatoCampo.status = 1 AND formatoCampo.ensayadoFin = 0)
						)

					AND ordenDeTrabajo.laboratorio_id = 1QQ
				UNION
				SELECT 
					id_formatoRegistroRev AS id,
					'N.A.' AS id_footerEnsayo,
					'N.A.' AS nombre,
					'REVENIMIENTO' AS tipo,
					'N.A.' AS ensayosAwaitingApproval,
					notVistoJLForBrigadaApproval,
					IF(jefaLabApproval_id IS NOT NULL, 'Completado', 'Autorizar y generar PDF') AS accReq,
					regNo AS informeNo,
					ordenDeTrabajo_id,
					'1' AS tipoNo
				FROM
					formatoRegistroRev,
					ordenDeTrabajo
				WHERE
					id_ordenDeTrabajo = ordenDeTrabajo_id
					AND formatoRegistroRev.status > 0
					AND laboratorio_id = 1QQ
					AND notVistoJLForBrigadaApproval = 0
					AND jefaLabApproval_id IS NULL
				",
				array($laboratorio_id,$laboratorio_id),
				"SELECT -- FooterEnsayo :: getAwaitingApproval : 1"
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
		$dbS->commitTransaction();
		return json_encode($arr);
	}

	public function initInsert($token,$rol_usuario_id,$tipo,$id_RegistroCCH){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			//Cargamos las variables del sistema
			$formatoCampo_id = 	$dbS->qvalue(
				"   SELECT
						formatoCampo_id
					FROM
						registrosCampo
					WHERE
						id_registrosCampo = '1QQ'
				",
				array($id_RegistroCCH),
				"SELECT -- FooterEnsayo :: initInsert : 1"
			);
			if($dbS->didQuerydied || $formatoCampo_id == "empty"){
				$dbS->rollbackTransaction();
				$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 30);
				return json_encode($arr);
			}
			$footerEnsayo_id = 	$dbS->qvalue(
				"   SELECT
						footerEnsayo_id
					FROM
						registrosCampo
					WHERE
						formatoCampo_id = '1QQ' AND
						footerEnsayo_id IS NOT NULL
					LIMIT 1
				",
				array($formatoCampo_id),
				"SELECT -- FooterEnsayo :: initInsert : 2"
			);
	
			if(!$dbS->didQuerydied && $footerEnsayo_id == "empty"){
				$var_system = $dbS->qarrayA(
				"   SELECT
						ensayo_def_buscula_id,
						ensayo_def_regVerFle_id,
						ensayo_def_prensa_id,
						ensayo_def_observaciones
					FROM
						systemstatus
					ORDER BY id_systemstatus DESC;
				",array(),"SELECT -- FooterEnsayo :: initInsert : 3"
				);

				$dbS->squery(
					"   INSERT INTO
							footerEnsayo(buscula_id,regVerFle_id,prensa_id,tipo,observaciones,encargado_id, formatoCampo_id)
						VALUES
							('1QQ','1QQ','1QQ','1QQ','1QQ',1QQ,1QQ)
				",array($var_system['ensayo_def_buscula_id'],$var_system['ensayo_def_regVerFle_id'],$var_system['ensayo_def_prensa_id'],$tipo,$var_system['observaciones'],$usuario->id_usuario,$formatoCampo_id),
				"INSERT -- FooterEnsayo :: initInsert : 4");
				if(!$dbS->didQuerydied){
					$id=$dbS->lastInsertedID;
					switch($tipo){
						case"CILINDRO":
							$idRegGabsCil=$this->checkifRegCCHRegCILINDRO($id_RegistroCCH);
							if($idRegGabsCil==-1){
								$idRegGabsCil=$this->initEnsayoCilindro($id_RegistroCCH,$id);
								if($idRegGabsCil == -2){
									$dbS->rollbackTransaction();
									$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 40);
								}else{
									$arr = array('id_footerEnsayo' => $id, 'id_RegistroGabs' => $idRegGabsCil,'estatus' => '¡Exito en la inicializacion','error' => 0,'existe' => 0);
								}
							}else if($idRegGabsCil==-2){
								$dbS->rollbackTransaction();
								$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
							}else{
								$arr = array('id_footerEnsayo' => $id, 'id_RegistroGabs' => $idRegGabsCil,'estatus' => '¡Exito en la inicializacion','error' => 0,'existe' => 0);
							}
						break;
						case"CUBO":
							$idRegGabsCubo=$this->checkifRegCCHRegCUBO($id_RegistroCCH);
							if($idRegGabsCubo==-1){
								$idRegGabsCubo=$this->initEnsayoCubo($id_RegistroCCH,$id);
								if($idRegGabsCubo == -2){
									$dbS->rollbackTransaction();
									$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 40);
								}else{
									$arr = array('id_footerEnsayo' => $id, 'id_RegistroGabs' => $idRegGabsCubo,'estatus' => '¡Exito en la inicializacion','error' => 0,'existe' => 0);
								}
								}else if($idRegGabsCubo==-2){
								$dbS->rollbackTransaction();
								$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
							}else{
								$arr = array('id_footerEnsayo' => $id, 'id_RegistroGabs' => $idRegGabsCubo,'estatus' => '¡Exito en la inicializacion','error' => 0,'existe' => 0);
							}
						break;
						case"VIGAS":
							$idRegGabsViga=$this->checkifRegCCHRegVIGA($id_RegistroCCH);
							if($idRegGabsViga==-1){
								$idRegGabsViga=$this->initEnsayoViga($id_RegistroCCH,$id);
								if($idRegGabsViga == -2){
									$dbS->rollbackTransaction();
									$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 40);
								}else{
									$arr = array('id_footerEnsayo' => $id, 'id_RegistroGabs' => $idRegGabsViga,'estatus' => '¡Exito en la inicializacion','error' => 0,'existe' => 0);
								}
							}else if($idRegGabsViga==-2){
								$dbS->rollbackTransaction();
								$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
							}else{
								$arr = array('id_footerEnsayo' => $id, 'id_RegistroGabs' => $idRegGabsViga,'estatus' => '¡Exito en la inicializacion','error' => 0,'existe' => 0);
							}
						break;
						default:
							$dbS->rollbackTransaction();
							$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 11);
						break;
					}
					$dbS->commitTransaction();
					return json_encode($arr);
				}else{
					$dbS->rollbackTransaction();
					$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
					return json_encode($arr);
				}

			}
			else{
				if(!$dbS->didQuerydied){
					switch($tipo){
						case"CILINDRO":
							$idRegGabsCil=$this->checkifRegCCHRegCILINDRO($id_RegistroCCH);
							if($idRegGabsCil==-1){
								$idRegGabsCil=$this->initEnsayoCilindro($id_RegistroCCH,$footerEnsayo_id);
								if($idRegGabsCil == -2){
									$dbS->rollbackTransaction();
									$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 40);
								}else{
									$arr = array('id_footerEnsayo' => $footerEnsayo_id, 'id_RegistroGabs' => $idRegGabsCil,'estatus' => 'Ya se creo un footer el dia de hoy para el tipo, se inicializo un nuevo registro Gab de tipo:'.$tipo,'error'=>0,'existe' => 1);
								}
							}else{ 
								if($idRegGabsCil==-2){
									$dbS->rollbackTransaction();
									$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
								}else{
									$arr = array('id_footerEnsayo' => $footerEnsayo_id, 'id_RegistroGabs' => $idRegGabsCil,'estatus' => 'Ya se creo un footer el dia de hoy para el tipo, ya existia un registro Gab de tipo:'.$tipo,'error'=>0,'existe' => 1);
								}
							}
						break;
						case"CUBO":
							$idRegGabsCubo=$this->checkifRegCCHRegCUBO($id_RegistroCCH);
							if($idRegGabsCubo==-1){
								$idRegGabsCubo=$this->initEnsayoCubo($id_RegistroCCH,$footerEnsayo_id);
								if($idRegGabsCubo == -2){
									$dbS->rollbackTransaction();
									$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 40);
								}else{
									$arr = array('id_footerEnsayo' => $footerEnsayo_id, 'id_RegistroGabs' => $idRegGabsCubo,'estatus' => 'Ya se creo un footer el dia de hoy para el tipo, se inicializo un nuevo registro Gab de tipo:'.$tipo,'error'=>0,'existe' => 1);
								}
							}else{ 
								if($idRegGabsCubo==-2){
									$dbS->rollbackTransaction();
									$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
								}else{
									$arr = array('id_footerEnsayo' => $footerEnsayo_id, 'id_RegistroGabs' => $idRegGabsCubo,'estatus' => 'Ya se creo un footer el dia de hoy para el tipo, ya existia un registro Gab de tipo:'.$tipo,'error'=>0,'existe' => 1);
								}
							}
						break;
						case"VIGAS":
							$idRegGabsViga=$this->checkifRegCCHRegVIGA($id_RegistroCCH);
							if($idRegGabsViga==-1){
								$idRegGabsViga=$this->initEnsayoViga($id_RegistroCCH,$footerEnsayo_id);
								if($idRegGabsViga == -2){
									$dbS->rollbackTransaction();
									$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 40);
								}else{
									$arr = array('id_footerEnsayo' => $footerEnsayo_id, 'id_RegistroGabs' => $idRegGabsViga,'estatus' => 'Ya se creo un footer el dia de hoy para el tipo, se inicializo un nuevo registro Gab de tipo:'.$tipo,'error'=>0,'existe' => 1);
								}
							}else{ 
								if($idRegGabsViga==-2){
									$dbS->rollbackTransaction();
									$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
								}else{
									$arr = array('id_footerEnsayo' => $footerEnsayo_id, 'id_RegistroGabs' => $idRegGabsViga,'estatus' => 'Ya se creo un footer el dia de hoy para el tipo, ya existia un registro Gab de tipo:'.$tipo,'error'=>0,'existe' => 1);
								}
							}
						break;
						default:
							$dbS->rollbackTransaction();
							$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 11);
						break;
					}
					$dbS->commitTransaction();
				}else{
					$dbS->rollbackTransaction();
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 12);
				}
			}			
		}
		return json_encode($arr);
	}

	public function checkifRegCCHRegCILINDRO($id_RegistroCCH){
		global $dbS;
		$a = $dbS->qarrayA(
			"SELECT
					*
			FROM
				ensayoCilindro
			WHERE 
				registrosCampo_id=1QQ;
			",array($id_RegistroCCH),
			"SELECT -- FooterEnsayo :: initInsert :: checkifRegCCHRegCILINDRO : 1"
		);
		if(!$dbS->didQuerydied){
			if(($a == "empty")){
				return -1;
			}else{
				return $a['id_ensayoCilindro'];
			}
		}else{
			return -2;
		}
	}

	public function checkifRegCCHRegCUBO($id_RegistroCCH){
		global $dbS;
		$a = $dbS->qarrayA(
			"SELECT
					*
			FROM
				ensayoCubo
			WHERE 
				registrosCampo_id=1QQ;
			",array($id_RegistroCCH),
			"SELECT -- FooterEnsayo :: initInsert :: checkifRegCCHRegCUBO : 1"
		);
		if(!$dbS->didQuerydied){
			if(($a == "empty")){
				return -1;
			}else{
				return $a['id_ensayoCubo'];
			}
		}else{
			return -2;
		}
	}

	public function checkifRegCCHRegVIGA($id_RegistroCCH){
		global $dbS;
		$a = $dbS->qarrayA(
			"SELECT
					*
			FROM
				ensayoViga
			WHERE 
				registrosCampo_id=1QQ;
			",array($id_RegistroCCH),
			"SELECT -- FooterEnsayo :: initInsert :: checkifRegCCHRegVIGA : 1"
		);
		if(!$dbS->didQuerydied){
			if(($a == "empty")){
				return -1;
			}else{
				return $a['id_ensayoViga'];
			}
		}else{
			return -2;
		}
	}

	public function initEnsayoCilindro($id_RegistroCCH,$id){
		global $dbS;
		$a= $dbS->qarrayA("
	      	SELECT
				formatoCampo_id
			FROM
				registrosCampo
			WHERE
				id_registrosCampo=1QQ
		      ",
		      array($id_RegistroCCH),
		      "SELECT -- footerEnsayo :: initInsert :: initEnsayoCilindro : 1"
		);
		if(!$dbS->didQuerydied && !($a=="empty")){
			$dbS->squery("
				UPDATE 
					registrosCampo
				SET
					footerEnsayo_id= 1QQ
				WHERE 
					id_registrosCampo= 1QQ
				",array($id,$id_RegistroCCH),
				"UPDATE -- footerEnsayo :: initInsert :: initEnsayoCilindro : 2"
			);

			if($dbS->didQuerydied){
				return-2;
			}

			$dbS->squery("
				UPDATE 
					footerEnsayo
				SET
					pendingEnsayos= pendingEnsayos + 1
				WHERE 
					id_footerEnsayo= 1QQ
				",array($id),
				"UPDATE -- footerEnsayo :: initInsert :: initEnsayoCilindro : 3"
			);

			if(!$dbS->didQuerydied){
				$dbS->squery("
					INSERT INTO
						ensayoCilindro(fecha,registrosCampo_id,formatoCampo_id,footerEnsayo_id,peso,d1,d2,h1,h2,carga,falla)
					VALUES
						(CURDATE(),1QQ,1QQ,1QQ,0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0)
					",array($id_RegistroCCH, $a['formatoCampo_id'],$id ),
					"INSERT -- footerEnsayo :: initInsert :: initEnsayoCilindro : 4"
				);
				if(!$dbS->didQuerydied){
					$idRegGabsCil=$dbS->lastInsertedID;
					return $idRegGabsCil;
				}else{
					return-2;
				}
			}else{
				return-2;
			}

		}
		return-2;
	}

	public function initEnsayoCubo($id_RegistroCCH,$id){
		global $dbS;
		$a= $dbS->qarrayA("
	      	SELECT
				formatoCampo_id
			FROM
				registrosCampo
			WHERE
				id_registrosCampo=1QQ
		      ",
		      array($id_RegistroCCH),
		      "SELECT -- footerEnsayo :: initInsert :: initEnsayoCubo : 1"
		);
		if(!$dbS->didQuerydied && !($a=="empty")){
			$dbS->squery("
				UPDATE 
					registrosCampo
				SET
					footerEnsayo_id= 1QQ
				WHERE 
					id_registrosCampo= 1QQ
				",array($id,$id_RegistroCCH),
				"UPDATE -- footerEnsayo :: initInsert :: initEnsayoCubo : 2"
			);

			if($dbS->didQuerydied){
				return-2;
			}

			$dbS->squery("
				UPDATE 
					footerEnsayo
				SET
					pendingEnsayos= pendingEnsayos + 1
				WHERE 
					id_footerEnsayo= 1QQ
				",array($id),
				"UPDATE -- footerEnsayo :: initInsert :: initEnsayoCubo : 3"
			);

			if(!$dbS->didQuerydied){
				$dbS->squery("
					INSERT INTO
						ensayoCubo(fecha,registrosCampo_id,formatoCampo_id,footerEnsayo_id)
					VALUES
						(CURDATE(),1QQ,1QQ,1QQ)
					",array($id_RegistroCCH, $a['formatoCampo_id'],$id ),
					"INSERT -- footerEnsayo :: initInsert :: initEnsayoCubo : 4"
				);
				if(!$dbS->didQuerydied){
					$idRegGabsCubo=$dbS->lastInsertedID;
					return $idRegGabsCubo;
				}else{
					return-2;
				}
			}else{
				return-2;
			}
		}
		return-2;
	}

	public function initEnsayoViga($id_RegistroCCH,$id){
		global $dbS;
		$disApoyo = $dbS->qvalue(
			"   SELECT
					ensayo_def_distanciaApoyos
				FROM
					systemstatus
				ORDER BY id_systemstatus DESC;
			",array(),"SELECT -- footerEnsayo :: initInsert :: initEnsayoViga : 0"
			);
		if($dbS->didQuerydied){
			return-2;
		}
		$a= $dbS->qarrayA("
	      	SELECT
				formatoCampo_id
			FROM
				registrosCampo
			WHERE
				id_registrosCampo=1QQ
		      ",
		      array($id_RegistroCCH),
		      "SELECT -- footerEnsayo :: initInsert :: initEnsayoViga : 1"
		);
		if(!$dbS->didQuerydied && !($a=="empty")){
			$dbS->squery("
				UPDATE 
					registrosCampo
				SET
					footerEnsayo_id= 1QQ
				WHERE 
					id_registrosCampo= 1QQ
				",array($id,$id_RegistroCCH),
				"UPDATE -- footerEnsayo :: initInsert :: initEnsayoViga : 2"
			);

			if($dbS->didQuerydied){
				return-2;
			}

			$dbS->squery("
				UPDATE 
					footerEnsayo
				SET
					pendingEnsayos= pendingEnsayos + 1
				WHERE 
					id_footerEnsayo= 1QQ
				",array($id),
				"UPDATE -- footerEnsayo :: initInsert :: initEnsayoViga : 3"
			);

			if(!$dbS->didQuerydied){
				$dbS->squery("
					INSERT INTO
						ensayoViga(fecha,registrosCampo_id,formatoCampo_id,footerEnsayo_id,fecha,disApoyo)
					VALUES
						(CURDATE(),1QQ,1QQ,1QQ,CURDATE(),'1QQ')
					",array($id_RegistroCCH, $a['formatoCampo_id'],$id,$disApoyo),
					"INSERT -- footerEnsayo :: initInsert :: initEnsayoViga : 4"
				);
				if(!$dbS->didQuerydied){
					$idRegGabsCubo=$dbS->lastInsertedID;
					return $idRegGabsCubo;
				}else{
					return-2;
				}
			}else{
				return-2;
			}
		}
		return-2;
	}

	public function getAllFooterPendientes($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$s= $dbS->qAll(
				"SELECT
					footerEnsayo.id_footerEnsayo AS id_footerEnsayo,
					buscula_id,
					basculas.placas AS buscula_placas,
					regVerFle_id,
					regVerFle.placas AS regVerFle_id_placas,		
					prensa_id,
					observaciones,
					prensas.placas AS prensa_placas,
					encargado_id,
					CONCAT(nombre,' ',apellido) AS nombre,
					footerEnsayo.tipo AS tipo,
					footerEnsayo.status AS status,
					CASE
						WHEN footerEnsayo.pendingEnsayos > 0 AND footerEnsayo.status = 0 AND DATE(footerEnsayo.createdON) = CURDATE() THEN 1
						WHEN footerEnsayo.pendingEnsayos > 0 AND footerEnsayo.status = 0 AND DATE(footerEnsayo.createdON) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN 2
						WHEN footerEnsayo.pendingEnsayos > 0 AND footerEnsayo.status = 0 AND DATE(footerEnsayo.createdON) = DATE_SUB(CURDATE(), INTERVAL 2 DAY) THEN 3
						WHEN footerEnsayo.pendingEnsayos = 0 THEN 0
						ELSE 4
					END AS color,
					CASE
						WHEN DATE(footerEnsayo.createdON) = CURDATE() THEN 'Hoy'
						WHEN DATE(footerEnsayo.createdON) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN 'Ayer'
						ELSE DATE(footerEnsayo.createdON)
					END AS fecha,
					pendingEnsayos,
					IF(footerEnsayo.pendingEnsayos = 0, 'Completado', 'Pendiente') AS estado
				FROM
					footerEnsayo,
					usuario,
					(
						SELECT
				  			id_herramienta,
				  			placas,
				  			id_footerEnsayo
				  		FROM
				  			herramientas,footerEnsayo
				  		WHERE
				  			buscula_id = id_herramienta
					)AS basculas,
					(
				  		SELECT
				  			id_herramienta,
				  			placas,
				  			id_footerEnsayo
				  		FROM
				  			herramientas,footerEnsayo
				  		WHERE
				  			prensa_id = id_herramienta 
				  	)AS prensas,
				  	(
				  		SELECT
				  			id_herramienta,
				  			placas,
				  			id_footerEnsayo
				  		FROM
				  			herramientas,footerEnsayo
				  		WHERE
				  			regVerFle_id = id_herramienta
				  	)AS regVerFle
				WHERE
					basculas.id_footerEnsayo = footerEnsayo.id_footerEnsayo AND
					prensas.id_footerEnsayo = footerEnsayo.id_footerEnsayo AND
					regVerFle.id_footerEnsayo = footerEnsayo.id_footerEnsayo AND
					encargado_id = id_usuario AND
					footerEnsayo.active = 1 AND
					(
						(pendingEnsayos = 0 AND
						DATE(footerEnsayo.lastEditedON) = CURDATE()) OR
						(pendingEnsayos >0)
					)AND 
					footerEnsayo.status = 0 AND
					buscula_id = basculas.id_herramienta AND
					prensa_id = prensas.id_herramienta AND
					regVerFle_id = regVerFle.id_herramienta AND
					usuario.laboratorio_id = 1QQ
			      ",
			      array($laboratorio_id),
			      "SELECT -- FooterEnsayo :: getAllFooterPendientes"
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
	public function ping($data){
		global $dbS;
		return $data;
	}
	public function getFooterByFormatoCampoID($token,$rol_usuario_id,$id_formatoCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA(
				"SELECT
					id_footerEnsayo,
					buscula_id,
					basculas.placas AS buscula_placas,
					regVerFle_id,
					regVerFle.placas AS regVerFle_id_placas,		
					prensa_id,
					footerEnsayo.observaciones,
					prensas.placas AS prensa_placas,
					encargado_id,
					CONCAT(nombre,' ',apellido) AS nombre,
					DATE(footerEnsayo.createdON) AS fecha,
					footerEnsayo.status AS status,
					formatoCampo.preliminar AS preliminar,
					preliminarGabs
				FROM
					footerEnsayo,
					formatoCampo,
					usuario,
					(
						SELECT
				  			id_herramienta,
				  			placas 
				  		FROM
				  			herramientas,footerEnsayo
				  		WHERE
				  			buscula_id = id_herramienta AND
				  			formatoCampo_id = 1QQ 
					)AS basculas,
					(
				  		SELECT
				  			id_herramienta,
				  			placas
				  		FROM
				  			herramientas,footerEnsayo
				  		WHERE
				  			prensa_id = id_herramienta AND
				  			formatoCampo_id = 1QQ
				  	)AS prensas,
				  	(
				  		SELECT
				  			id_herramienta,
				  			placas
				  		FROM
				  			herramientas,footerEnsayo
				  		WHERE
				  			regVerFle_id = id_herramienta AND
				  			formatoCampo_id = 1QQ
				  	)AS regVerFle
				WHERE
					formatoCampo.id_formatoCampo = footerEnsayo.formatoCampo_id AND
					encargado_id = id_usuario AND
					footerEnsayo.active = 1 AND
					buscula_id = basculas.id_herramienta AND
					prensa_id = prensas.id_herramienta AND
					regVerFle_id = regVerFle.id_herramienta AND
					formatoCampo_id = 1QQ
			      ",
			      array($id_formatoCampo,$id_formatoCampo,$id_formatoCampo,$id_formatoCampo),
			      "SELECT -- FooterEnsayo :: getFooterByID : 1"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('No existen footer relacionados con el id_footerEnsayo'=>$id_formatoCampo,'error' => 5);
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

	public function getFooterByID($token,$rol_usuario_id,$id_footerEnsayo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA(
				"SELECT
					id_footerEnsayo,
					buscula_id,
					basculas.placas AS buscula_placas,
					regVerFle_id,
					regVerFle.placas AS regVerFle_id_placas,		
					prensa_id,
					footerEnsayo.observaciones,
					prensas.placas AS prensa_placas,
					encargado_id,
					CONCAT(nombre,' ',apellido) AS nombre,
					DATE(footerEnsayo.createdON) AS fecha,
					footerEnsayo.status AS status,
					formatoCampo.preliminar AS preliminar,
					preliminarGabs
				FROM
					formatoCampo,
					usuario,
					footerEnsayo LEFT JOIN 
					(
						SELECT
				  			id_herramienta,
				  			placas 
				  		FROM
				  			herramientas,footerEnsayo
				  		WHERE
				  			buscula_id = id_herramienta AND
				  			id_footerEnsayo = 1QQ 
					)AS basculas ON footerEnsayo.buscula_id = basculas.id_herramienta
					LEFT JOIN 
					(
				  		SELECT
				  			id_herramienta,
				  			placas
				  		FROM
				  			herramientas,footerEnsayo
				  		WHERE
				  			prensa_id = id_herramienta AND
				  			id_footerEnsayo = 1QQ
				  	)AS prensas ON footerEnsayo.prensa_id = prensas.id_herramienta
					LEFT JOIN 
				  	(
				  		SELECT
				  			id_herramienta,
				  			placas
				  		FROM
				  			herramientas,footerEnsayo
				  		WHERE
				  			regVerFle_id = id_herramienta AND
				  			id_footerEnsayo = 1QQ
				  	)AS regVerFle  ON footerEnsayo.regVerFle_id = regVerFle.id_herramienta
				WHERE
					formatoCampo.id_formatoCampo = footerEnsayo.formatoCampo_id AND
					encargado_id = id_usuario AND
					footerEnsayo.active = 1 AND
					id_footerEnsayo = 1QQ
			      ",
			      array($id_footerEnsayo,$id_footerEnsayo,$id_footerEnsayo,$id_footerEnsayo),
			      "SELECT -- FooterEnsayo :: getFooterByID : 1"
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
				case '4':
					$campo = 'observaciones';
					break;
			}

			$dbS->squery("
						UPDATE
							footerEnsayo
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