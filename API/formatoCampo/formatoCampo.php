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

class formatoCampo{
	private $id_formatoCampo;
	private $informeNo;
	private $ordenDeServicio_id;
	private $observaciones;
	private $mr;
	private $tipo;


	private $wc = '/1QQ/';

	public function initInsertCCH($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			//Informacion para crear el "Informe No."
			$a= $dbS->qarrayA(
				"SELECT 
		      		id_obra,
					cotizacion,
					consecutivoDocumentos,
					prefijo,
					YEAR(NOW()) AS anio
				FROM
					obra,
					(
						SELECT
							obra_id
						FROM
							ordenDeTrabajo
						WHERE
							id_ordenDeTrabajo = 1QQ

					)AS ordenDeTrabajo
				WHERE
					id_obra = ordenDeTrabajo.obra_id
				",
				array($id_ordenDeTrabajo),
				"SELECT"
			);
			if(!$dbS->didQuerydied && !($a=="empty")){
				//Creamos el informe No.
				$año = $a['anio'] - 2000;
				$infoNo = $a['prefijo']."/".$a['cotizacion']."/".$año."/".$a['consecutivoDocumentos'];
				$dbS->squery(
					"INSERT INTO
							formatoCampo
							(
								informeNo,
								observaciones,
								ordenDeTrabajo_id
							)
						VALUES
							(
							'1QQ',
							'NO HAY OBSERVACIONES',
							1QQ
							)

					"
					,
					array($infoNo,$id_ordenDeTrabajo)
					,
					"INSERT"
				);
				if(!$dbS->didQuerydied){
					$id = $dbS->lastInsertedID;
					$dbS->squery(
								"
									UPDATE
										obra
									SET
										consecutivoDocumentos = consecutivoDocumentos+1
									WHERE
										id_obra = 1QQ

								"
								,
								array($a['id_obra'])
								,
								"SELECT"
							);
					if(!$dbS->didQuerydied){
						$dbS->commitTransaction();
						$arr = array('id_formatoCampo' => $id,'informeNo'=>$infoNo,'token' => $token,	'estatus' => 'Exito en la insersion','error' => 0);									
					}
					else{
						$dbS->rollbackTransaction();
						$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la modificacion de consecutivoDocumentos, verifica tus datos y vuelve a intentarlo','error' => 5);
					}
				}else{
					$dbS->rollbackTransaction();
					$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			else{
				$dbS->rollbackTransaction();
				$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la consulta, verifica tus datos y vuelve a intentarlo','error' => 7);
			}	
		}
		return json_encode($arr);

	}
	public function getAllAdministrativo($token,$rol_usuario_id){
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


	public function getAllAdmin($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll(
					"SELECT
						formatoCampo.id_formatoCampo,
						informeNo,
						observaciones,
						tipo,
						formatoCampo.cono_id,
						CONO,
						formatoCampo.varilla_id,
						VARILLA,
						formatoCampo.flexometro_id,
						FLEXOMETRO,
						formatoCampo.termometro_id,
						TERMOMETRO
					FROM
						formatoCampo,
						(
							SELECT
								id_formatoCampo,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS CONO
							FROM
								formatoCampo
							LEFT JOIN
								herramientas
							ON
								formatoCampo.cono_id = herramientas.id_herramienta
						)AS cono,
						(
							SELECT
								id_formatoCampo,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS VARILLA
							FROM
								formatoCampo
							LEFT JOIN
								herramientas
							ON
								formatoCampo.varilla_id = herramientas.id_herramienta
						)AS varilla,
						(
							SELECT
								id_formatoCampo,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS FLEXOMETRO
							FROM
								formatoCampo
							LEFT JOIN
								herramientas
							ON
								formatoCampo.flexometro_id = herramientas.id_herramienta
						)AS flexometro,
						(
							SELECT
								id_formatoCampo,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS TERMOMETRO
							FROM
								formatoCampo
							LEFT JOIN
								herramientas
							ON
								formatoCampo.termometro_id = herramientas.id_herramienta
						)AS termometro
					WHERE
						cono.id_formatoCampo = formatoCampo.id_formatoCampo AND
						varilla.id_formatoCampo = formatoCampo.id_formatoCampo AND
						flexometro.id_formatoCampo = formatoCampo.id_formatoCampo AND
						termometro.id_formatoCampo = formatoCampo.id_formatoCampo AND
						formatoCampo.ordenDeTrabajo_id = 1QQ
					ORDER BY 
						formatoCampo.id_formatoCampo	        

			      ",
			      array($id_ordenDeTrabajo),
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

	public function insertJefeBrigada($token,$rol_usuario_id,$campo,$valor,$id_formatoCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			switch ($campo) {
				case '1':
					$campo = 'observaciones';
					break;
				case '2':
					$campo = 'tipo';
					break;
				case '3':
					$campo = 'tipoConcreto';
					break;
				case '4':
					$campo = 'prueba1';
					break;
				case '5':
					$campo = 'prueba2';
					break;
				case '6':
					$campo = 'prueba3';
					break;
				case '7':
					$campo = 'prueba4';
					break;
				case '8':
					$campo = 'cono_id';
					break;
				case '9':
					$campo = 'varilla_id';
					break;
				case '10':
					$campo = 'flexometro_id';
					break;
				case '11':
					$campo = 'termometro_id';
					break;
				case '12':
					$campo = 'posInicial';
					break;
				case '13':
					$campo = 'posFinal';
					break;
			}

			$dbS->squery(
				"UPDATE
							formatoCampo
						SET
							1QQ = '1QQ'
						WHERE
							id_formatoCampo = 1QQ

				",array($campo,$valor,$id_formatoCampo),
				"UPDATE -- FormatoCampo :: insertJefeBrigada : 1"
			);
			$arr = array('estatus' => 'Exito en insercion', 'error' => 0);
			if(!$dbS->didQuerydied){
				$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => '¡Exito en la inserccion de informacion!','error' => 0);
				return json_encode($arr);
			}else{
				$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
				return json_encode($arr);
			}
		}
		return json_encode($arr);

	}


	public function getformatoDefoults($token,$rol_usuario_id,$tipo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			if($tipo == "VIGAS"){
				$arr = $dbS->qarrayA(
					"	SELECT
							id_systemstatus,
							cch_vigaDef_prueba1,
							cch_vigaDef_prueba2,
							cch_vigaDef_prueba3,
							maxNoOfRegistrosCCH_VIGAS,
							multiplosNoOfRegistrosCCH_VIGAS
						FROM
							systemstatus
						ORDER BY id_systemstatus DESC;
					",array(),
					"SELECT -- FormatoCampo :: getformatoDefoults : 1"
				);
			}else{
				$arr = $dbS->qarrayA(
					"	SELECT
							id_systemstatus,
							cch_def_prueba1,
							cch_def_prueba2,
							cch_def_prueba3,
							cch_def_prueba4,
							maxNoOfRegistrosCCH,
							multiplosNoOfRegistrosCCH
						FROM
							systemstatus
						ORDER BY id_systemstatus DESC;
					",array(),
					"SELECT -- FormatoCampo :: getformatoDefoults : 2"
				);
			}
			if(!$dbS->didQuerydied && !($arr == "empty")){
			}
			else{
				$arr = array('id_systemstatus' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
			return json_encode($arr);
		}
		return json_encode($arr);
	}

	/*
		Esta funcion es llamada en;
			Archivo: LlenaFormatoCCH.component.ts
			Usuario: Jefe de Brigada
			Protocolo: GET
	*/
	public function getNumberOfRegistrosByID($token,$rol_usuario_id,$id_formatoCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA(
				"SELECT 
					COUNT(*) As No
				FROM 
					registrosCampo
				WHERE
					active=1 AND 
					formatoCampo_id=1QQ
					",
					array($id_formatoCampo),
				"SELECT  -- FormatoCampo :: getNumberOfRegistrosByID : 1"
			);

			if(!$dbS->didQuerydied && $s!="empty" ){
				$a= $dbS->qarrayA(
					"SELECT 
						COUNT(*) As No
					FROM 
						registrosCampo 
					WHERE 
						herramienta_id IS NULL AND
						formatoCampo_id= 1QQ;
					",
					array($id_formatoCampo),
					"SELECT  -- FormatoCampo :: getNumberOfRegistrosByID : 2"
			    );
				if(!$dbS->didQuerydied && $a!="empty" ){
					$tipoModificable;
					if($a['No']==$s['No']){
						$tipoModificable=1;
					}else{
						$tipoModificable=0;
					}
					$arr = array('id_formatoCampo' => $id_formatoCampo,'tipoModificable' => $tipoModificable,'numberOfRegistrosByID' => $s['No'],'estatus' => 'Exito','error' => 0);

				}else{
					$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Error no se encontro ese id','error' => 5);
				}
			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getNumberOfRegistrosByID , verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);
	}


	/*
		Esta funcion es llamada en;
			Archivo: LlenaFormatoCCH.component.ts
			Usuario: Jefe de Brigada
			Protocolo: GET
	*/

	public function getInfoByID($token,$rol_usuario_id,$id_formatoCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA(
				"SELECT
			      	informeNo,
			        obra,
					localizacion,
					formatoCampo.observaciones,
					nombre,
					tipoConcreto,
					prueba1,
					prueba2,
					prueba3,
					prueba4,
					razonSocial,
					CONCAT(calle,' ',noExt,' ',noInt,', ',col,', ',municipio,', ',estado) AS direccion,
					formatoCampo.tipo AS tipo_especimen,
					formatoCampo.cono_id,
					formatoCampo.status,
					CONO,
					formatoCampo.varilla_id,
					VARILLA,
					formatoCampo.flexometro_id,
					FLEXOMETRO,
					formatoCampo.termometro_id,	
					TERMOMETRO,
					formatoCampo.preliminar AS preliminar
			      FROM 
			        ordenDeTrabajo,cliente,obra,formatoCampo,
			        (
							SELECT
								id_formatoCampo,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS CONO
							FROM
								formatoCampo
							LEFT JOIN
								herramientas
							ON
								formatoCampo.cono_id = herramientas.id_herramienta
						)AS cono,
						(
							SELECT
								id_formatoCampo,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS VARILLA
							FROM
								formatoCampo
							LEFT JOIN
								herramientas
							ON
								formatoCampo.varilla_id = herramientas.id_herramienta
						)AS varilla,
						(
							SELECT
								id_formatoCampo,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS FLEXOMETRO
							FROM
								formatoCampo
							LEFT JOIN
								herramientas
							ON
								formatoCampo.flexometro_id = herramientas.id_herramienta
						)AS flexometro,
						(
							SELECT
								id_formatoCampo,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS TERMOMETRO
							FROM
								formatoCampo
							LEFT JOIN
								herramientas
							ON
								formatoCampo.termometro_id = herramientas.id_herramienta
						)AS termometro
			      WHERE 
			      	obra_id = id_obra AND
			      	cliente_id = id_cliente AND
			      	cono.id_formatoCampo = formatoCampo.id_formatoCampo AND
					varilla.id_formatoCampo = formatoCampo.id_formatoCampo AND
					flexometro.id_formatoCampo = formatoCampo.id_formatoCampo AND
					termometro.id_formatoCampo = formatoCampo.id_formatoCampo AND
					ordenDeTrabajo.id_ordenDeTrabajo = formatoCampo.ordenDeTrabajo_id AND
			      	formatoCampo.id_formatoCampo = 1QQ
			      ",
			      array($id_formatoCampo),
			      "SELECT -- FormatoCampo :: getInfoByID : 1"
			      );

			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Error no se encontro ese id','error' => 5);
				}
				else{
					return json_encode($s);
				}
			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getInfoByID , verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);
	}



	public function getHeader($token,$rol_usuario_id,$id_ordenDeTrabajo,$id_formatoCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA(
				"SELECT
			      	informeNo,
			        obra,
					localizacion,
					razonSocial,
					tipoConcreto,
					prueba1,
					prueba2,
					prueba3,
					prueba4,
					CONCAT(calle,' ',noExt,' ',noInt,', ',col,', ',municipio,', ',estado) AS direccion

			      FROM 
			        ordenDeTrabajo,cliente,obra,formatoCampo
			      WHERE 
			      	obra_id = id_obra AND
			      	cliente_id = id_cliente AND
			      	id_formatoCampo = 1QQ AND
			      	id_ordenDeTrabajo = 1QQ  
			      ",
			      array($id_ordenDeTrabajo),
			      "SELECT -- FormatoCampo :: getHeader : 1"
			      );

			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Error no se encontro ese id','error' => 5);
				}
				else{
					return json_encode($s);
				}
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getClienteByID , verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);
	}

	public function updateFooter($token,$rol_usuario_id,$id_formatoCampo,$observaciones,$cono_id,$varilla_id,$flexometro_id,$termometro_id,$tipo,$tipoConcreto,$prueba1,$prueba2,$prueba3,$prueba4){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery(
				"	UPDATE
						formatoCampo
					SET
						observaciones ='1QQ',
						cono_id = 1QQ,
						varilla_id = 1QQ,
						flexometro_id = 1QQ,
						termometro_id = 1QQ,
						tipo ='1QQ',
						tipoConcreto ='1QQ',
						prueba1 ='1QQ',
						prueba2 ='1QQ',
						prueba3 ='1QQ',
						prueba4 ='1QQ'
					WHERE
						active=1 AND
						id_formatoCampo = 1QQ
				"
				,array($observaciones,$cono_id,$varilla_id,$flexometro_id,$termometro_id,$tipo,$tipoConcreto,$prueba1,$prueba2,$prueba3,$prueba4,$id_formatoCampo),
				"UPDATE  -- FormatoCampo :: updateHeader : 1"
			);
			$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito de actualizacion de footer','error' => 0);	
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}		
		}
		return json_encode($arr);
	}

	public function updateHeader($token,$rol_usuario_id,$id_formatoCampo,$informeNo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery(
				"	UPDATE
						formatoCampo
					SET
						informeNo ='1QQ'
					WHERE
						active=1 AND
						id_formatoCampo = 1QQ
				"
				,array($informeNo,$id_formatoCampo),
				"UPDATE  -- FormatoCampo :: updateHeader : 1"
			);
			$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito de actualizacion de header','error' => 0);	
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}		
		}
		return json_encode($arr);
	}
	public function ping2($data){
		echo $data;
	}

	public function generatePDF($token,$rol_usuario_id,$id_formatoCampo){
		global $dbS;

		$usuario = new Usuario();
		$mailer = new Mailer();
		$generador = new GeneradorFormatos();


		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			$info = $dbS->qarrayA(
				"SELECT
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
				array($id_formatoCampo)
				,"SELECT -- FormatoCampo :: generatePDF : 1"
			);
			if($dbS->didQuerydied || ($info=="empty")){
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 6);
				return json_encode($arr);
			}
			$var_system = $dbS->qarrayA(
			"
				SELECT
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

			//Obtenemos la hora para que no se repitan en caso de crear un nuevo formato
			$hora_de_creacion = getdate();
			$target_dir = "./../../../SystemData/FormatosData/".$info['id_cliente']."/".$info['id_obra']."/".$info['id_ordenDeTrabajo']."/".$id_formatoCampo."/";
			$dirDatabase = $var_system['apiRoot']."SystemData/FormatosData/".$info['id_cliente']."/".$info['id_obra']."/".$info['id_ordenDeTrabajo']."/".$id_formatoCampo."/"."preliminarCCH"."(".$hora_de_creacion['hours']."-".$hora_de_creacion['minutes']."-".$hora_de_creacion['seconds'].")".".pdf";
			if (!file_exists($target_dir)) {
				mkdir($target_dir, 0777, true);
			}
			$target_dir=$target_dir."preliminarCCH"."(".$hora_de_creacion['hours']."-".$hora_de_creacion['minutes']."-".$hora_de_creacion['seconds'].")".".pdf";
			//Llamada a el generador de formatos
			//Cachamos la excepcion
			try{
				$generador->generateCCH($token,$rol_usuario_id,$id_formatoCampo,$target_dir);
			}catch(Exception $e){
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la generacion del formato:'.$e->getMessage(),'error' => 8);
				return json_encode($arr);
			}
			$dbS->squery(
				"UPDATE
					formatoCampo
				SET
					preliminar = '1QQ'
				WHERE
					id_formatoCampo = 1QQ
			"
			,array($dirDatabase,$id_formatoCampo),"UPDATE -- formatoCampo :: generatePDF : 3"
			);

			if($dbS->didQuerydied){ // Si no murio la query de guardar el preliminar en BD
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 40);
				return json_encode($arr);
			}else{
				$dbS->commitTransaction();
				return json_encode($arr);
			}

		}
		return json_encode($arr);
	}

	public function completeFormato($token,$rol_usuario_id,$id_formatoCampo){
		global $dbS;

		$usuario = new Usuario();
		$mailer = new Mailer();

		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			$a = $dbS->qarrayA(
				"   SELECT 
						COUNT(*) AS No
					FROM 
					 	registrosCampo 
					WHERE 
					 	formatoCampo_id= 1QQ
				"
				,
				array($id_formatoCampo)
				,
				"SELECT -- FormatoCampo :: completeFormato : 1"
			);
			if(!$dbS->didQuerydied && !($a=="empty")){
				$dbS->squery(
					"UPDATE
						formatoCampo
					SET
						status = 1,
						ensayadoFin = 1QQ,
						registrosNo = 1QQ,
						notVistoJLForBrigadaApproval = 1
					WHERE
						active = 1 AND
						id_formatoCampo = 1QQ
				 "
				,array($a['No'],$a['No'],$id_formatoCampo),
				"UPDATE -- FormatoCampo :: completeFormato : 2"
		      	);

				if(!$dbS->didQuerydied){
					$dbS->squery(
						"	UPDATE
								registrosCampo
							SET
								status = 2
							WHERE
								active = 1 AND
								formatoCampo_id = 1QQ
						 "
						,array($id_formatoCampo),
						"UPDATE -- FormatoCampo :: completeFormato : 3"
				    );
					if(!$dbS->didQuerydied){
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

						"
						,
						array($id_formatoCampo)
						,
						"SELECT  -- FormatoCampo :: completeFormato : 4"
						);
						if(!$dbS->didQuerydied && ($info != "empty")){
							try{
								
								$dirDatabase = $dbS->qvalue(
									"   SELECT preliminar FROM formatoCampo WHERE id_formatoCampo=1QQ",
									array($id_formatoCampo),"SELECT  -- FormatoCampo :: completeFormato : 5"
								);

								if($dbS->didQuerydied){ // Si no murio la query de guardar el preliminar en BD
									$dbS->rollbackTransaction();
									$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 40);
									return json_encode($arr);
								}
								//  Envio del primer correo
								$resp=$mailer->sendMailBasic($info['emailCliente'], $info['nombre'], $dirDatabase);
								if($resp==202){
									$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
								}else{
									$dbS->rollbackTransaction();
									$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 106, 'sendGridError'=>$resp);
									return json_encode($arr);
								}
								//  Envio del segundo correo
								$resp=$mailer->sendMailBasic($info['emailResidente'], $info['nombre'], $dirDatabase);
								if($resp==202){
									$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
								}else{
									$dbS->rollbackTransaction();
									$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 107, 'sendGridError'=>$resp);
									return json_encode($arr);
								}
								//  Envio del tercer correo
								$resp=$mailer->sendMailBasic($info['correo_alterno'], $info['nombre'], $dirDatabase);
								if($resp==202){
									$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
								}else{
									$dbS->rollbackTransaction();
									$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 108, 'sendGridError'=>$resp);
									return json_encode($arr);
								}
								
								$dbS->commitTransaction();
								return json_encode($arr);

							}catch(Exception $e){
								$dbS->rollbackTransaction();
								$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la generacion del formato:'.$e->getMessage(),'error' => 7);
								return json_encode($arr);
							}
						}else{
							$dbS->rollbackTransaction();
							$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la consulta de la informacion para la carpeta','error' => 9);
						}
					}else{
						$dbS->rollbackTransaction();
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , verifica tus datos y vuelve a intentarlo','error' => 10);
					}
				}else{
					$dbS->rollbackTransaction();
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , verifica tus datos y vuelve a intentarlo','error' => 11);
				}
			}else{
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , verifica tus datos y vuelve a intentarlo','error' => 12);
			}			
		}
		$dbS->rollbackTransaction();
		return json_encode($arr);
	}

	public function sentMail($token,$rol_usuario_id,$id_formatoCampo){
		global $dbS;

		$usuario = new Usuario();
		$mailer = new Mailer();

		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			$a = $dbS->qarrayA(
				"   SELECT 
						COUNT(*) AS No
					FROM 
					 	registrosCampo 
					WHERE 
					 	formatoCampo_id= 1QQ
				"
				,
				array($id_formatoCampo)
				,
				"SELECT -- FormatoCampo :: completeFormato : 1"
			);
			if(!$dbS->didQuerydied && !($a=="empty")){
				if(!$dbS->didQuerydied){
					if(!$dbS->didQuerydied){
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
									"   SELECT preliminar FROM formatoCampo WHERE id_formatoCampo=1QQ",
									array($id_formatoCampo),"SELECT  -- FormatoCampo :: completeFormato : 5"
								);
								if($dbS->didQuerydied){ // Si no murio la query de guardar el preliminar en BD
									$dbS->rollbackTransaction();
									$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 40);
									return json_encode($arr);
								}
								//  Envio del primer correo
								$resp=$mailer->sendMailBasic($info['emailCliente'], $info['nombre'], $dirDatabase);
								if($resp==202){
									$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato enviado','error' => 0);	
								}else{
									$dbS->rollbackTransaction();
									$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 106, 'sendGridError'=>$resp);
									return json_encode($arr);
								}
								//  Envio del segundo correo
								$resp=$mailer->sendMailBasic($info['emailResidente'], $info['nombre'], $dirDatabase);
								if($resp==202){
									$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato enviado','error' => 0);	
								}else{
									$dbS->rollbackTransaction();
									$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 107, 'sendGridError'=>$resp);
									return json_encode($arr);
								}
								//  Envio del tercer correo
								$resp=$mailer->sendMailBasic($info['correo_alterno'], $info['nombre'], $dirDatabase);
								if($resp==202){
									$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato enviado','error' => 0);	
								}else{
									$dbS->rollbackTransaction();
									$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 108, 'sendGridError'=>$resp);
									return json_encode($arr);
								}
								
								$dbS->commitTransaction();
								return json_encode($arr);

							}catch(Exception $e){
								$dbS->rollbackTransaction();
								$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la generacion del formato:'.$e->getMessage(),'error' => 7);
								return json_encode($arr);
							}
						}else{
							$dbS->rollbackTransaction();
							$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la consulta de la informacion para la carpeta','error' => 9);
						}
					}else{
						$dbS->rollbackTransaction();
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , verifica tus datos y vuelve a intentarlo','error' => 10);
					}
				}else{
					$dbS->rollbackTransaction();
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , verifica tus datos y vuelve a intentarlo','error' => 11);
				}
			}else{
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , verifica tus datos y vuelve a intentarlo','error' => 12);
			}			
		}
		$dbS->rollbackTransaction();
		return json_encode($arr);
	}
}
?>