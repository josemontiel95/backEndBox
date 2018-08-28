<?php 


include_once("./../../usuario/Usuario.php");
class formatoCampo{
	private $id_formatoCampo;
	private $informeNo;
	private $ordenDeServicio_id;
	private $observaciones;
	private $mr;
	private $tipo;


	private $wc = '/1QQ/';

	public function getAllAdmin($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			     	SELECT
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
	

	public function insertJefeBrigada($token,$rol_usuario_id,$informeNo,$ordenDeTrabajo_id,$tipo,$cono_id,$varilla_id,$flexometro_id,$termometro_id,$longitud,$latitud){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			

			$dbS->squery("
						INSERT INTO
						formatoCampo(informeNo,ordenDeTrabajo_id,tipo,cono_id,varilla_id,flexometro_id,termometro_id,posInicial,observaciones)
						VALUES
						('1QQ',1QQ,'1QQ',1QQ,1QQ,1QQ,1QQ,PointFromText('POINT(1QQ 1QQ)'),'NO HAY OBSERVACIONES')
				",array($informeNo,$ordenDeTrabajo_id,$tipo,$cono_id,$varilla_id,$flexometro_id,$termometro_id,$longitud,$latitud),"INSERT");
			if(!$dbS->didQuerydied){
				$id=$dbS->lastInsertedID;
				$arr = array('id_formatoCampo' =>$id,'estatus' => 'Exito en insercion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);

	}




	public function getInfoByID($token,$rol_usuario_id,$id_formatoCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			      SELECT
			      	informeNo,
			        obra,
					localizacion,
					formatoCampo.observaciones,
					nombre,
					razonSocial,
					CONCAT(calle,' ',noExt,' ',noInt,', ',col,', ',municipio,', ',estado) AS direccion,
					formatoCampo.tipo,
					formatoCampo.cono_id,
					CONO,
					formatoCampo.varilla_id,
					VARILLA,
					formatoCampo.flexometro_id,
					FLEXOMETRO,
					formatoCampo.termometro_id,	
					TERMOMETRO
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
			      "SELECT"
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
			$s= $dbS->qarrayA("
			      SELECT
			      	informeNo,
			        obra,
					localizacion,
					razonSocial,
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
			      "SELECT"
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

	public function updateFooter($token,$rol_usuario_id,$id_formatoCampo,$observaciones,$cono_id,$varilla_id,$flexometro_id,$termometro_id,$tipo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
								formatoCampo
							SET
								observaciones ='1QQ',
								cono_id = 1QQ,
								varilla_id = 1QQ,
								flexometro_id = 1QQ,
								termometro_id = 1QQ,
								tipo ='1QQ'
							WHERE
								active=1 AND
								id_formatoCampo = 1QQ
					 "
					,array($observaciones,$cono_id,$varilla_id,$flexometro_id,$termometro_id,$tipo,$id_formatoCampo),"UPDATE"
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
			$dbS->squery("	UPDATE
								formatoCampo
							SET
								informeNo ='1QQ'
							WHERE
								active=1 AND
								id_formatoCampo = 1QQ
					 "
					,array($informeNo,$id_formatoCampo),"UPDATE"
			      	);
			$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito de actualizacion de header','error' => 0);	
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}		
		}
		return json_encode($arr);
	}

	public function completeFormato($token,$rol_usuario_id,$id_formatoCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
								formatoCampo
							SET
								status = 1
							WHERE
								active = 1 AND
								id_formatoCampo = 1QQ
					 "
					,array($id_formatoCampo),"UPDATE"
			      	);
			$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , verifica tus datos y vuelve a intentarlo','error' => 5);
			}		
		}
		return json_encode($arr);
	}

	




}
?>