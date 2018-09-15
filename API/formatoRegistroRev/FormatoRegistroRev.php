<?php 


include_once("./../../usuario/Usuario.php");
class FormatoRegistroRev{
	private $id_formatoCampo;
	private $informeNo;
	private $ordenDeServicio_id;
	private $observaciones;
	private $mr;
	private $tipo;


	private $wc = '/1QQ/';

	public function insertJefeBrigada($token,$rol_usuario_id,$campo,$valor,$id_formatoRegistroRev){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			switch ($campo) {
				case '1':
					$campo = 'regNo';
					break;
				case '2':
					$campo = 'localizacion';
					break;
				case '3':
					$campo = 'tipoConcreto';
					break;
				case '4':
					$campo = 'cono_id';
					break;
				case '5':
					$campo = 'varilla_id';
					break;
				case '6':
					$campo = 'flexometro_id';
					break;
				case '7':
					$campo = 'posInicial';
					break;
				case '8':
					$campo = 'posFinal';
					break;
			}

			$dbS->squery("
						UPDATE
							formatoRegistroRev
						SET
							1QQ = '1QQ'
						WHERE
							id_formatoRegistroRev = 1QQ

				",array($campo,$valor,$id_formatoRegistroRev),"UPDATE");
			$arr = array('estatus' => 'Exito en insercion', 'error' => 0);
			if(!$dbS->didQuerydied){
				$arr = array('id_formatoRegistroRev' => $id_formatoRegistroRev,'estatus' => '¡Exito en la inserccion de informacion!','error' => 0);
				return json_encode($arr);
			}else{
				$arr = array('id_formatoRegistroRev' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
				return json_encode($arr);
			}
		}
		return json_encode($arr);

	}
	public function getAllAdmin($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			     	SELECT
						formatoRegistroRev.id_formatoRegistroRev,
						regNo,
						formatoRegistroRev.localizacion,
						observaciones,
						formatoRegistroRev.cono_id,
						CONO,
						formatoRegistroRev.varilla_id,
						VARILLA,
						formatoRegistroRev.flexometro_id,
						FLEXOMETRO
					FROM
						formatoRegistroRev,
						(
							SELECT
								id_formatoRegistroRev,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS CONO
							FROM
								formatoRegistroRev
							LEFT JOIN
								herramientas
							ON
								formatoRegistroRev.cono_id = herramientas.id_herramienta
						)AS cono,
						(
							SELECT
								id_formatoRegistroRev,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS VARILLA
							FROM
								formatoRegistroRev
							LEFT JOIN
								herramientas
							ON
								formatoRegistroRev.varilla_id = herramientas.id_herramienta
						)AS varilla,
						(
							SELECT
								id_formatoRegistroRev,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS FLEXOMETRO
							FROM
								formatoRegistroRev
							LEFT JOIN
								herramientas
							ON
								formatoRegistroRev.flexometro_id = herramientas.id_herramienta
						)AS flexometro
					WHERE
						cono.id_formatoRegistroRev = formatoRegistroRev.id_formatoRegistroRev AND
						varilla.id_formatoRegistroRev = formatoRegistroRev.id_formatoRegistroRev AND
						flexometro.id_formatoRegistroRev = formatoRegistroRev.id_formatoRegistroRev AND
						formatoRegistroRev.ordenDeTrabajo_id = 1QQ
					ORDER BY 
						formatoRegistroRev.id_formatoRegistroRev	        

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
	public function initInsertRev($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			//Informacion para crear el "Informe No."
			$a= $dbS->qarrayA("
		      	SELECT 
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
					"
						INSERT INTO
							formatoRegistroRev
							(
								regNo,
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
						$arr = array('id_formatoRegistroRev' => $id,'informeNo'=>$infoNo,'token' => $token,	'estatus' => 'Exito en la insersion','error' => 0);									
					}
					else{
						$dbS->rollbackTransaction();
						$arr = array('id_formatoRegistroRev' => 'NULL','token' => $token,	'estatus' => 'Error en la modificacion de consecutivoDocumentos, verifica tus datos y vuelve a intentarlo','error' => 5);
					}
				}else{
					$dbS->rollbackTransaction();
					$arr = array('id_formatoRegistroRev' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			else{
				$dbS->rollbackTransaction();
				$arr = array('id_formatoRegistroRev' => 'NULL','token' => $token,	'estatus' => 'Error en la consulta, verifica tus datos y vuelve a intentarlo','error' => 7);
			}	
		}
		return json_encode($arr);

	}
	public function insertJefeBrigada2($token,$rol_usuario_id,$regNo,$ordenDeTrabajo_id,$localizacion,$cono_id,$varilla_id,$flexometro_id,$longitud,$latitud){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			

			$dbS->squery("
						INSERT INTO
						formatoRegistroRev(regNo,ordenDeTrabajo_id,localizacion,cono_id,varilla_id,flexometro_id,posInicial,observaciones)
						VALUES
						('1QQ',1QQ,'1QQ',1QQ,1QQ,1QQ,PointFromText('POINT(1QQ 1QQ)'),'NO HAY OBSERVACIONES')
				",array($regNo,$ordenDeTrabajo_id,$localizacion,$cono_id,$varilla_id,$flexometro_id,$longitud,$latitud),"INSERT");
			if(!$dbS->didQuerydied){
				$id=$dbS->lastInsertedID;
				$arr = array('id_formatoRegistroRev' =>$id,'estatus' => 'Exito en insercion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);

	}

	public function getInfoByID($token,$rol_usuario_id,$id_formatoRegistroRev){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			      SELECT
			      	regNo,
			        obra,
					formatoRegistroRev.localizacion,
					formatoRegistroRev.observaciones,
					nombre,
					razonSocial,
					CONCAT(calle,' ',noExt,' ',noInt,', ',col,', ',municipio,', ',estado) AS direccion,
					formatoRegistroRev.cono_id,
					CONO,
					formatoRegistroRev.varilla_id,
					VARILLA,
					formatoRegistroRev.flexometro_id,
					FLEXOMETRO
			      FROM 
			        ordenDeTrabajo,cliente,obra,formatoRegistroRev,
			        (
							SELECT
								id_formatoRegistroRev,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS CONO
							FROM
								formatoRegistroRev
							LEFT JOIN
								herramientas
							ON
								formatoRegistroRev.cono_id = herramientas.id_herramienta
						)AS cono,
						(
							SELECT
								id_formatoRegistroRev,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS VARILLA
							FROM
								formatoRegistroRev
							LEFT JOIN
								herramientas
							ON
								formatoRegistroRev.varilla_id = herramientas.id_herramienta
						)AS varilla,
						(
							SELECT
								id_formatoRegistroRev,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS FLEXOMETRO
							FROM
								formatoRegistroRev
							LEFT JOIN
								herramientas
							ON
								formatoRegistroRev.flexometro_id = herramientas.id_herramienta
						)AS flexometro
			      WHERE 
			      	obra_id = id_obra AND
			      	cliente_id = id_cliente AND
			      	cono.id_formatoRegistroRev = formatoRegistroRev.id_formatoRegistroRev AND
					varilla.id_formatoRegistroRev = formatoRegistroRev.id_formatoRegistroRev AND
					flexometro.id_formatoRegistroRev = formatoRegistroRev.id_formatoRegistroRev AND
					ordenDeTrabajo.id_ordenDeTrabajo = formatoRegistroRev.ordenDeTrabajo_id AND
			      	formatoRegistroRev.id_formatoRegistroRev = 1QQ
			      ",
			      array($id_formatoRegistroRev),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('id_formatoRegistroRev' => $id_formatoRegistroRev,'estatus' => 'Error no se encontro ese id','error' => 5);
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

	public function updateFooter($token,$rol_usuario_id,$id_formatoRegistroRev,$observaciones,$cono_id,$varilla_id,$flexometro_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
								formatoRegistroRev
							SET
								observaciones ='1QQ',
								cono_id = 1QQ,
								varilla_id = 1QQ,
								flexometro_id = 1QQ
							WHERE
								active=1 AND
								id_formatoRegistroRev = 1QQ
					 "
					,array($observaciones,$cono_id,$varilla_id,$flexometro_id,$id_formatoRegistroRev),"UPDATE"
			      	);
			$arr = array('id_formatoRegistroRev' => $id_formatoRegistroRev,'estatus' => 'Exito de actualizacion de footer','error' => 0);	
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}		
		}
		return json_encode($arr);
	}

	public function updateHeader($token,$rol_usuario_id,$id_formatoRegistroRev,$regNo,$localizacion){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
								formatoRegistroRev
							SET
								regNo ='1QQ',
								localizacion = '1QQ'
							WHERE
								active=1 AND
								id_formatoRegistroRev = 1QQ
					 "
					,array($regNo,$localizacion,$id_formatoRegistroRev),"UPDATE"
			      	);
			$arr = array('id_formatoRegistroRev' => $id_formatoRegistroRev,'estatus' => 'Exito de actualizacion de header','error' => 0);	
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}		
		}
		return json_encode($arr);
	}

}
?>