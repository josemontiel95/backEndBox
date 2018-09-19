<?php 
	include_once("./../../formatoCampo/formatoCampo.php");
	include_once("./../../formatoCampo/registrosCampo.php");
	include_once("./../../disenoFormatos/InformeCilindros.php");
	include_once("./../../disenoFormatos/Revenimiento.php");
	include_once("./../../disenoFormatos/InformeCubos.php");
	include_once("./../../usuario/Usuario.php");
	include_once("./../../disenoFormatos/CCH.php");
	include_once("./../../formatoRegistroRev/FormatoRegistroRev.php");

	class GeneradorFormatos{
		function generateInformeCampo($token,$rol_usuario_id,$id_formatoCampo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if(0 == 0){
				$formato = new FormatoCampo();	$infoFormato = json_decode($formato->getInfoByID($token,$rol_usuario_id,$id_formatoCampo),true);
				switch ($infoFormato['tipo_especimen']) {
					case 'CUBO':
						$regisFormato = $this->getRegCuboByFCCH($token,$rol_usuario_id,$id_formatoCampo);
						$pdf = new InformeCubos();	$pdf->CreateNew($infoFormato,$regisFormato);
						break;
					case 'CILINDRO':
						$regisFormato = $this->getRegCuboByFCCH($token,$rol_usuario_id,$id_formatoCampo);
						$pdf = new InformeCilindros();	$pdf->CreateNew($infoFormato,$regisFormato);
						break;

				
				}
			}
		}

		function generateRevenimiento($token,$rol_usuario_id,$id_formatoRegistroRev,$target_dir){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			$info = $this->getInfoRev($token,$rol_usuario_id,$id_formatoRegistroRev);
			$registros = $this->getRegRev($token,$rol_usuario_id,$id_formatoRegistroRev);
			$pdf = new Revenimiento();	
			$pdf->CreateNew($info,$registros,$target_dir);
		}
		/*
		function generateRevenimiento($token,$rol_usuario_id,$id_formatoRegistroRev){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			$info = $this->getInfoRev($token,$rol_usuario_id,$id_formatoRegistroRev);
			$registros = $this->getRegRev($token,$rol_usuario_id,$id_formatoRegistroRev);
			$pdf = new Revenimiento();	$pdf->CreateNew($info,$registros);
			echo json_encode($info);
		}*/

		function generateEnsayoCubos($token,$rol_usuario_id,$id_ensayoCubo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$formato = new FormatoCampo();	
				$infoFormato = json_decode($formato->getInfoByID($token,$rol_usuario_id,$id_formatoCampo),true);
				$regisFormato = $this->getRegCCH($token,$rol_usuario_id,$id_formatoCampo);
				$pdf = new CCH();	
				$pdf->CreateNew($infoFormato,$regisFormato,$target_dir);
			}
		}

		function getInfoEnsayoCubo($token,$rol_usuario_id,$id_ensayoCubo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qarrayA("
				    	SELECT
				    		registrosCampo.fecha AS fechaColado,
				    		informeNo,
				    		claveEspecimen,
							l1,
							l2,
							carga,
							(l1*l2)AS area,
							carga/(l1*l2) AS resistencia,	
							ensayoCubo.fecha AS fechaEnsayo,						
							CASE
								WHEN MOD(diasEnsaye,4) = 1 THEN prueba1  
								WHEN MOD(diasEnsaye,4) = 1 THEN prueba1
								WHEN MOD(diasEnsaye,4) = 2 THEN prueba2  
								WHEN MOD(diasEnsaye,4) = 2 THEN prueba2
								WHEN MOD(diasEnsaye,4) = 3 THEN prueba3  
								WHEN MOD(diasEnsaye,4) = 3 THEN prueba3
								WHEN MOD(diasEnsaye,4) = 0 THEN prueba4  
								WHEN MOD(diasEnsaye,4) = 0 THEN prueba4
								ELSE 'Error, Contacta a soporte'
							END AS diasEnsayeFinal
						FROM 
							ensayoCubo,registrosCampo,formatoCampo
						WHERE
							id_formatoCampo = ensayoCubo.formatoCampo_id AND
							id_registrosCampo = ensayoCubo.registrosCampo_id AND
							id_ensayoCubo = 1QQ
				      ",
				      array($id_ensayoCubo),
				      "SELECT"
				      );
				
				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('No existen registro relacionados con el id_ensayoCubo'=>$id_ensayoCubo,'error' => 5);
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

		function getInfoRev($token,$rol_usuario_id,$id_formatoRegistroRev){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qarrayA("
			      SELECT
			      	regNo,
			        obra,
					formatoRegistroRev.localizacion AS localizacionRev,
					formatoRegistroRev.observaciones,
					formatoRegistroRev.status,
					nombre,
					razonSocial,
					CONCAT(calle,' ',noExt,' ',noInt,', ',col,', ',municipio,', ',estado) AS direccion,
					obra.localizacion,
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
						$arr = array('No existen registro relacionados con el id_formatoRegistroRev'=>$id_formatoRegistroRev,'error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getRegCCH, verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;
		}

		function getRegRev($token,$rol_usuario_id,$id_formatoRegistroRev){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qAll("
			      SELECT
			      	fecha,
					revProyecto,	
					revObtenido,
					tamAgregado,
					idenConcreto,
					volumen,
					horaDeterminacion,
					unidad,
					concretera_id,
					remisionNo,
					horaSalida,
					horaLlegada
			      FROM 
			      	registrosRev
			      WHERE 
			      	registrosRev.active = 1 AND
			      	formatoRegistroRev_id = 1QQ
			      ",
			      array($id_formatoRegistroRev),
			      "SELECT"
			      );
				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('No existen registro relacionados con el id_formatoRegistroRev'=>$id_formatoRegistroRev,'error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getRegCCH, verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;

		}


		function generateCCH($token,$rol_usuario_id,$id_formatoCampo,$target_dir){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$formato = new FormatoCampo();	
				$infoFormato = json_decode($formato->getInfoByID($token,$rol_usuario_id,$id_formatoCampo),true);
				$regisFormato = $this->getRegCCH($token,$rol_usuario_id,$id_formatoCampo);
				$pdf = new CCH();	
				$pdf->CreateNew($infoFormato,$regisFormato,$target_dir);
			}
		}
		
		/*
		function generateCCH($token,$rol_usuario_id,$id_formatoCampo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$formato = new FormatoCampo();	
				$infoFormato = json_decode($formato->getInfoByID($token,$rol_usuario_id,$id_formatoCampo),true);
				$regisFormato = $this->getRegCCH($token,$rol_usuario_id,$id_formatoCampo);
				$pdf = new CCH();	
				$pdf->CreateNew($infoFormato,$regisFormato);
			}
		}
	*/
		function getRegCCH($token,$rol_usuario_id,$id_formatoCampo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qAll("
				    	SELECT
				    		claveEspecimen,
				    		fecha,
				    		fprima,
							revProyecto,
							revObra,
							tamAgregado,
							volumen,
							tipoConcreto,
							unidad,
							horaMuestreo,
							tempMuestreo,
							tempRecoleccion,
							localizacion
						FROM
							registrosCampo,
							formatoCampo
						WHERE
							id_formatoCampo = formatoCampo_id AND 
							formatoCampo_id = 1QQ
				      ",
				      array($id_formatoCampo),
				      "SELECT"
				      );
				
				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('No existen registro relacionados con el id_formatoCampo'=>$id_formatoCampo,'error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getRegCCH, verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;

		}

	
		function getRegCuboByFCCH($token,$rol_usuario_id,$id_formatoCampo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qAll("
				    	SELECT
				    		ROUND((((carga/(l1*l2))/fprima)*100),3)  AS porcentResis,
				    		fprima,
				    		ROUND((carga/(l1*l2)),3) AS kg,
				    		ROUND(((carga/(l1*l2))/var_system.ensayo_def_MPa),3)  AS mpa,
				    		carga,
				    		ROUND(((carga*var_system.ensayo_def_kN)/var_system.ensayo_def_divisorKn),3) AS kn,
				    		ROUND((l1*l2),3) AS area,
							l2,
							l1,
							CASE
								WHEN MOD(diasEnsaye,4) = 1 THEN prueba1  
								WHEN MOD(diasEnsaye,4) = 1 THEN prueba1
								WHEN MOD(diasEnsaye,4) = 2 THEN prueba2  
								WHEN MOD(diasEnsaye,4) = 2 THEN prueba2
								WHEN MOD(diasEnsaye,4) = 3 THEN prueba3  
								WHEN MOD(diasEnsaye,4) = 3 THEN prueba3
								WHEN MOD(diasEnsaye,4) = 0 THEN prueba4  
								WHEN MOD(diasEnsaye,4) = 0 THEN prueba4
								ELSE 'Error, Contacta a soporte'
							END AS diasEnsaye,
							revObra,
							claveEspecimen,
							ensayoCubo.fecha AS fechaEnsaye	
						FROM
							ensayoCubo,
							registrosCampo,
							formatoCampo,
							(
								SELECT
									ensayo_def_kN,
									ensayo_def_MPa,
									ensayo_def_divisorKn
								FROM
									systemstatus
								ORDER BY id_systemstatus DESC LIMIT 1
							)AS var_system
						WHERE
							id_registrosCampo = ensayoCubo.registrosCampo_id AND
							id_formatoCampo = ensayoCubo.formatoCampo_id AND 
							ensayoCubo.formatoCampo_id = 1QQ
				      ",
				      array($id_formatoCampo),
				      "SELECT"
				      );
				
				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('No existen registro relacionados con el id_formatoCampo'=>$id_formatoCampo,'error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getHerramientaByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;

		}

		function getRegCilindroByFCCH($token,$rol_usuario_id,$id_formatoCampo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qAll("
				    	SELECT
				    		ensayoCilindro.fecha AS fechaEnsaye,
							claveEspecimen,
							revObra,
							carga,
							(d1+d2)/2 AS diametro,
							(h1/h2)/2 AS altura,
				    		ROUND((((carga/(l1*l2))/fprima)*100),3)  AS porcentResis,
				    		fprima,
				    		ROUND((carga/(l1*l2)),3) AS kg,
				    		ROUND(((carga/(l1*l2))/var_system.ensayo_def_MPa),3)  AS mpa,
				    		ROUND(((carga*var_system.ensayo_def_kN)/var_system.ensayo_def_divisorKn),3) AS kn,
				    		ROUND((l1*l2),3) AS area,
							l2,
							l1,
							CASE
								WHEN MOD(diasEnsaye,4) = 1 THEN prueba1  
								WHEN MOD(diasEnsaye,4) = 1 THEN prueba1
								WHEN MOD(diasEnsaye,4) = 2 THEN prueba2  
								WHEN MOD(diasEnsaye,4) = 2 THEN prueba2
								WHEN MOD(diasEnsaye,4) = 3 THEN prueba3  
								WHEN MOD(diasEnsaye,4) = 3 THEN prueba3
								WHEN MOD(diasEnsaye,4) = 0 THEN prueba4  
								WHEN MOD(diasEnsaye,4) = 0 THEN prueba4
								ELSE 'Error, Contacta a soporte'
							END AS diasEnsaye
						FROM
							ensayoCilindro,
							registrosCampo,
							formatoCampo,
							(
								SELECT
									ensayo_def_kN,
									ensayo_def_MPa,
									ensayo_def_divisorKn
								FROM
									systemstatus
								ORDER BY id_systemstatus DESC LIMIT 1
							)AS var_system
						WHERE
							id_registrosCampo = ensayoCilindro.registrosCampo_id AND
							id_formatoCampo = ensayoCilindro.formatoCampo_id AND 
							ensayoCilindro.formatoCampo_id = 1QQ
				      ",
				      array($id_formatoCampo),
				      "SELECT"
				      );
				
				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('No existen registro relacionados con el id_formatoCampo'=>$id_formatoCampo,'error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getHerramientaByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;

		}
	}
	

?>