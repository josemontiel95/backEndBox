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
	/*
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

	*/

	public function insertJefeBrigada($token,$rol_usuario_id,$regNo,$ordenDeTrabajo_id,$localizacion,$cono_id,$varilla_id,$flexometro_id,$longitud,$latitud){
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
					direccion,
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


	/*
	



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
					direccion
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

	
	*/
}
?>