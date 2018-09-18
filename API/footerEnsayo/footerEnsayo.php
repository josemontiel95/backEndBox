<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class footerEnsayo{
	
	/* Variables de utilería */
	private $wc = '/1QQ/';

	public function initInsert($token,$rol_usuario_id,$tipo,$id_RegistroCCH){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode
		($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			//Cargamos las variables del sistema
			$dbS->beginTransaction();	//Iniciamos la transacción
			$arr = 	$dbS->qarrayA(
				"SELECT
						id_footerEnsayo,
						buscula_id,
						regVerFle_id,
						prensa_id,
						observaciones,
						tipo,
						createdON
					FROM
						footerEnsayo
					WHERE
						CURDATE() = DATE(createdON) AND
						tipo = '1QQ'
				",
				array($tipo),
				"SELECT"
			);
			if($arr == "empty"){
				$var_system = $dbS->qarrayA(
				"
					SELECT
						ensayo_def_buscula_id,
						ensayo_def_regVerFle_id,
						ensayo_def_prensa_id,
						ensayo_def_observaciones
					FROM
						systemstatus
					ORDER BY id_systemstatus DESC;
				",array(),"SELECT"
				);

				$dbS->squery("
						INSERT INTO
							footerEnsayo(buscula_id,regVerFle_id,prensa_id,tipo,observaciones,encargado_id)

						VALUES
							(1QQ,1QQ,1QQ,'1QQ','1QQ',1QQ)
				",array($var_system['ensayo_def_buscula_id'],$var_system['ensayo_def_regVerFle_id'],$var_system['ensayo_def_prensa_id'],$tipo,$var_system['observaciones'],$usuario->id_usuario),"INSERT");
				if(!$dbS->didQuerydied){
					$id=$dbS->lastInsertedID;
					switch($tipo){
						case"CILINDRO":
							$idRegGabsCil=$this->checkifRegCCHRegCILINDRO($id_RegistroCCH);
							if($idRegGabsCil==-1){
								$idRegGabsCil=$this->initEnsayoCilindro($id_RegistroCCH,$id);
								$arr = array('id_footerEnsayo' => $id, 'id_RegistroGabs' => $idRegGabsCil,'estatus' => '¡Exito en la inicializacion','error' => 0,'existe' => 0);
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
								$arr = array('id_footerEnsayo' => $id, 'id_RegistroGabs' => $idRegGabsCubo,'estatus' => '¡Exito en la inicializacion','error' => 0,'existe' => 0);
							}else if($idRegGabsCubo==-2){
								$dbS->rollbackTransaction();
								$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
							}else{
								$arr = array('id_footerEnsayo' => $id, 'id_RegistroGabs' => $idRegGabsCubo,'estatus' => '¡Exito en la inicializacion','error' => 0,'existe' => 0);
							}
						break;
						case"VIGA":
							$idRegGabsViga=$this->checkifRegCCHRegVIGA($id_RegistroCCH);
							if($idRegGabsViga==-1){
								$idRegGabsViga=$this->initEnsayoViga($id_RegistroCCH,$id);
								$arr = array('id_footerEnsayo' => $id, 'id_RegistroGabs' => $idRegGabsViga,'estatus' => '¡Exito en la inicializacion','error' => 0,'existe' => 0);
							}else if($idRegGabsViga==-2){
								$dbS->rollbackTransaction();
								$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
							}else{
								$arr = array('id_footerEnsayo' => $id, 'id_RegistroGabs' => $idRegGabsViga,'estatus' => '¡Exito en la inicializacion','error' => 0,'existe' => 0);
							}
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
				switch($tipo){
						case"CILINDRO":
							$idRegGabsCil=$this->checkifRegCCHRegCILINDRO($id_RegistroCCH);
							if($idRegGabsCil==-1){
								$idRegGabsCil=$this->initEnsayoCilindro($id_RegistroCCH,$arr['id_footerEnsayo']);
								$arr = array('id_footerEnsayo' => $arr['id_footerEnsayo'], 'id_RegistroGabs' => $idRegGabsCil,'estatus' => 'Ya se creo un footer el dia de hoy para el tipo, se inicializo un nuevo registro Gab de tipo:'.$tipo,'error'=>0,'existe' => 1);
							}else{ 
								if($idRegGabsCil==-2){
									$dbS->rollbackTransaction();
									$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
								}else{
									$arr = array('id_footerEnsayo' => $arr['id_footerEnsayo'], 'id_RegistroGabs' => $idRegGabsCil,'estatus' => 'Ya se creo un footer el dia de hoy para el tipo, ya existia un registro Gab de tipo:'.$tipo,'error'=>0,'existe' => 1);
								}
							}
						break;
						case"CUBO":
							$idRegGabsCubo=$this->checkifRegCCHRegCUBO($id_RegistroCCH);
							if($idRegGabsCubo==-1){
								$idRegGabsCubo=$this->initEnsayoCubo($id_RegistroCCH,$arr['id_footerEnsayo']);
								$arr = array('id_footerEnsayo' => $arr['id_footerEnsayo'], 'id_RegistroGabs' => $idRegGabsCubo,'estatus' => 'Ya se creo un footer el dia de hoy para el tipo, se inicializo un nuevo registro Gab de tipo:'.$tipo,'error'=>0,'existe' => 1);
							}else{ 
								if($idRegGabsCubo==-2){
									$dbS->rollbackTransaction();
									$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
								}else{
									$arr = array('id_footerEnsayo' => $arr['id_footerEnsayo'], 'id_RegistroGabs' => $idRegGabsCubo,'estatus' => 'Ya se creo un footer el dia de hoy para el tipo, ya existia un registro Gab de tipo:'.$tipo,'error'=>0,'existe' => 1);
								}
							}
						break;
						case"VIGA":
							$idRegGabsViga=$this->checkifRegCCHRegVIGA($id_RegistroCCH);
							if($idRegGabsViga==-1){
								$idRegGabsViga=$this->initEnsayoViga($id_RegistroCCH,$arr['id_footerEnsayo']);
								$arr = array('id_footerEnsayo' => $arr['id_footerEnsayo'], 'id_RegistroGabs' => $idRegGabsViga,'estatus' => 'Ya se creo un footer el dia de hoy para el tipo, se inicializo un nuevo registro Gab de tipo:'.$tipo,'error'=>0,'existe' => 1);
							}else{ 
								if($idRegGabsViga==-2){
									$dbS->rollbackTransaction();
									$arr = array('id_footerEnsayo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
								}else{
									$arr = array('id_footerEnsayo' => $arr['id_footerEnsayo'], 'id_RegistroGabs' => $idRegGabsViga,'estatus' => 'Ya se creo un footer el dia de hoy para el tipo, ya existia un registro Gab de tipo:'.$tipo,'error'=>0,'existe' => 1);
								}
							}
						break;
				}
				$dbS->commitTransaction();
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
			",array($id_RegistroCCH),"SELECT"
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
			",array($id_RegistroCCH),"SELECT"
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
			",array($id_RegistroCCH),"SELECT"
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
		      "SELECT"
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
				"UPDATE"
			);

			if(!$dbS->didQuerydied){
				$dbS->squery("
					INSERT INTO
						ensayoCilindro(registrosCampo_id,formatoCampo_id,footerEnsayo_id,peso,d1,d2,h1,h2,carga,falla)
					VALUES
						(1QQ,1QQ,1QQ,0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0)
					",array($id_RegistroCCH, $a['formatoCampo_id'],$id ),
					"INSERT"
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
		      "SELECT"
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
				"UPDATE"
			);

			if(!$dbS->didQuerydied){
				$dbS->squery("
					INSERT INTO
						ensayoCubo(registrosCampo_id,formatoCampo_id,footerEnsayo_id)
					VALUES
						(1QQ,1QQ,1QQ)
					",array($id_RegistroCCH, $a['formatoCampo_id'],$id ),
					"INSERT"
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
		$a= $dbS->qarrayA("
	      	SELECT
				formatoCampo_id
			FROM
				registrosCampo
			WHERE
				id_registrosCampo=1QQ
		      ",
		      array($id_RegistroCCH),
		      "SELECT"
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
				"UPDATE"
			);

			if(!$dbS->didQuerydied){
				$dbS->squery("
					INSERT INTO
						ensayoViga(registrosCampo_id,formatoCampo_id,footerEnsayo_id,fecha)
					VALUES
						(1QQ,1QQ,1QQ,CURDATE())
					",array($id_RegistroCCH, $a['formatoCampo_id'],$id ),
					"INSERT"
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

	//
	public function getAllFooterPendientes($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$s= $dbS->qAll("
		      	SELECT
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
						WHEN footerEnsayo.status = 0 AND DATE(footerEnsayo.createdON) = CURDATE() THEN 1
						WHEN footerEnsayo.status = 0 AND DATE(footerEnsayo.createdON) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN 2
						WHEN footerEnsayo.status = 0 AND DATE(footerEnsayo.createdON) = DATE_SUB(CURDATE(), INTERVAL 2 DAY) THEN 3
						ELSE 4
					END AS color,
					CASE
						WHEN DATE(footerEnsayo.createdON) = CURDATE() THEN 'Hoy'
						WHEN DATE(footerEnsayo.createdON) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN 'Ayer'
						ELSE DATE(footerEnsayo.createdON)
					END AS fecha
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
					footerEnsayo.status = 0 AND
					buscula_id = basculas.id_herramienta AND
					prensa_id = prensas.id_herramienta AND
					regVerFle_id = regVerFle.id_herramienta AND
					usuario.laboratorio_id = 1QQ
			      ",
			      array($laboratorio_id),
			      "SELECT"
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
	public function ping(){
		global $dbS;
		$laboratorio_id=1003;
		$arr =$dbS->qarrayA("
		      	SELECT
					footerEnsayo.id_footerEnsayo,
					buscula_id,
					basculas.placas AS buscula_placas,
					regVerFle_id,
					regVerFle.placas AS regVerFle_id_placas,		
					prensa_id,
					observaciones,
					prensas.placas AS prensa_placas,
					encargado_id,
					CONCAT(nombre,' ',apellido) AS nombre,
					CASE
						WHEN DATE(footerEnsayo.createdON) = CURDATE() THEN 'Hoy'
						WHEN DATE(footerEnsayo.createdON) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN 'Ayer'
						ELSE DATE(footerEnsayo.createdON)
					END AS fecha
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
				WHEREA
					basculas.id_footerEnsayo = footerEnsayo.id_footerEnsayo AND
					prensas.id_footerEnsayo = footerEnsayo.id_footerEnsayo AND
					regVerFle.id_footerEnsayo = footerEnsayo.id_footerEnsayo AND
					encargado_id = id_usuario AND
					footerEnsayo.active = 1 AND
					footerEnsayo.status = 0 AND
					buscula_id = basculas.id_herramienta AND
					prensa_id = prensas.id_herramienta AND
					regVerFle_id = regVerFle.id_herramienta AND
					usuario.laboratorio_id = 1QQ
			      ",
			      array($laboratorio_id),
			      "SELECT-footerEnsayo :: ping"
			      );
		return json_encode($arr);

	}

	public function getFooterByID($token,$rol_usuario_id,$id_footerEnsayo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
		      	SELECT
					id_footerEnsayo,
					buscula_id,
					basculas.placas AS buscula_placas,
					regVerFle_id,
					regVerFle.placas AS regVerFle_id_placas,		
					prensa_id,
					observaciones,
					prensas.placas AS prensa_placas,
					encargado_id,
					CONCAT(nombre,' ',apellido) AS nombre,
					DATE(footerEnsayo.createdON) AS fecha,
					footerEnsayo.status AS status
				FROM
					footerEnsayo,
					usuario,
					(
						SELECT
				  			id_herramienta,
				  			placas 
				  		FROM
				  			herramientas,footerEnsayo
				  		WHERE
				  			buscula_id = id_herramienta AND
				  			id_footerEnsayo = 1QQ 
					)AS basculas,
					(
				  		SELECT
				  			id_herramienta,
				  			placas
				  		FROM
				  			herramientas,footerEnsayo
				  		WHERE
				  			prensa_id = id_herramienta AND
				  			id_footerEnsayo = 1QQ
				  	)AS prensas,
				  	(
				  		SELECT
				  			id_herramienta,
				  			placas
				  		FROM
				  			herramientas,footerEnsayo
				  		WHERE
				  			regVerFle_id = id_herramienta AND
				  			id_footerEnsayo = 1QQ
				  	)AS regVerFle
				WHERE
					encargado_id = id_usuario AND
					footerEnsayo.active = 1 AND
					buscula_id = basculas.id_herramienta AND
					prensa_id = prensas.id_herramienta AND
					regVerFle_id = regVerFle.id_herramienta AND
					id_footerEnsayo = 1QQ
			      ",
			      array($id_footerEnsayo,$id_footerEnsayo,$id_footerEnsayo,$id_footerEnsayo),
			      "SELECT"
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