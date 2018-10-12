<?php 
	include_once("./../../formatoCampo/formatoCampo.php");
	include_once("./../../formatoCampo/registrosCampo.php");
	include_once("./../../disenoFormatos/InformeCilindros.php");
	include_once("./../../disenoFormatos/InformeVigas.php");
	include_once("./../../disenoFormatos/InformeRevenimiento.php");
	include_once("./../../disenoFormatos/Revenimiento.php");
	include_once("./../../disenoFormatos/InformeCubos.php");
	include_once("./../../usuario/Usuario.php");
	include_once("./../../FPDF/fpdf.php");
	include_once("./../../disenoFormatos/CCH.php");
	include_once("./../../formatoRegistroRev/FormatoRegistroRev.php");

	class GeneradorFormatos{
		function generateInformeCampo($token,$rol_usuario_id,$id_formatoCampo,$target_dir){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if(0 == 0){
				$formato = new FormatoCampo();	$infoFormato = json_decode($formato->getInfoByID($token,$rol_usuario_id,$id_formatoCampo),true);
				switch ($infoFormato['tipo_especimen']) {
					case 'CUBO':
						$pdf  = new InformeCubos('L','mm','Letter');
						$pdf->AddPage();
						$pdf->calculateSize();
						$regisFormato = $this->getRegCuboByFCCH($token,$rol_usuario_id,$id_formatoCampo,$pdf->arrayCampos);
						echo json_encode($regisFormato);
						/*
						$pdf = new InformeCubos();	$pdf->CreateNew($infoFormato,$regisFormato,$target_dir);*/
						break;
					case 'CILINDRO':
						$pdf = new InformeCilindros('L','mm','Letter');
						$pdf->AddPage();
						$pdf->calculateSize();
						$regisFormato = $this->getRegCilindroByFCCH($token,$rol_usuario_id,$id_formatoCampo,$pdf->arrayCampos);
						unset($pdf);
						$pdf = new InformeCilindros();	$pdf->CreateNew($infoFormato,$regisFormato,$target_dir);
						break;
					case 'VIGAS':
						$pdf  = new InformeVigas('L','mm','Letter');
						$pdf->AddPage();
						$pdf->calculateSize();
						$infoFormato = $this->getInfoViga($token,$rol_usuario_id,$id_formatoCampo);
						$regisFormato = $this->getRegVigaByFCCH($token,$rol_usuario_id,$id_formatoCampo,$pdf->arrayCampos);
						unset($pdf);
						$pdf  = new InformeVigas();
						$pdf->CreateNew($infoFormato,$regisFormato,$target_dir);	
						break;


				
				}
			}
		}


		function vistaPrevia($token,$rol_usuario_id,$tipo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				switch ($tipo) {
					case 'CUBO':
						$pdf = new InformeCubos();
						$pdf->demo();
						break;
					case 'VIGAS':
						$pdf = new InformeVigas();
						$pdf->demo();
						break;
					case 'CILINDRO':
						$pdf = new InformeCilindros();
						$pdf->demo();
						break;
					case 'CCH':
						$pdf = new CCH();
						$pdf->demo();
						break;
				}
			}else{
				return json_encode($arr);
			}
			
		}

		function getMaxString($sizeString,$tam){
			//Instanciamos el objeto para crear el pdf
			$pdf = new fpdf();
			//Configuramos el tamaño y fuente de la letra
			$pdf->SetFont('Arial','',$sizeString);
			//Declaramos el estado inicial de la cadena
			$string = 'W';	
			//Calculamos el tamaño inicial de la cadena
			$tam_string = $pdf->GetStringWidth($string);
			if($tam_string > $tam){
				return array('estatus' => 'No se pudo calcular, el tamaño de la celda es insuficiente para ese tamaño de letra.','error' => '1');
			}
			else{
				$count = 1;
				//Realizamos un ciclo iterativo para aumentar la cadena hasta que no queda en el tamaño de la celda y devolver el total de caracteres que puede almacenar la celda
				while ($tam_string < $tam) {
					$string .= 'W';
					$tam_string = $pdf->GetStringWidth($string);
					$count++;
				}
				return $count;
			}
		}

		function truncaCadena($arrayFont,$string,$tam){
			$pdf = new fpdf();
			$pdf->AddPage();
			/*
				Solucionar error, no se puede abrir una ruta en la libreria

			if($arrayFont['style'] == null){
				$pdf->SetFont($arrayFont['family'],'',$arrayFont['size']);
			}
			else{
				$pdf->SetFont($arrayFont['family'],$arrayFont['style'],$arrayFont['size']);
			}*/
			$pdf->SetFont('Arial','',7);
			
			$tam_string = $pdf->GetStringWidth($string); 
			while($tam_string>$tam){
				$string = substr($string,0,(strlen($string))-1);
				$tam_string =  $pdf->GetStringWidth($string)+2;
			}
			return $string;
		}

		function truncaCadenaArray($arrayFont,$arrayString,$arrayCell){
			$arrayAll = array();
			foreach ($arrayString as $row) {
				$i = 0;
				$array = array();
				foreach ($row as $r) {
					array_push($array,$this->truncaCadena($arrayFont,$r,$arrayCell[$i]));
					$i++;
				}
				array_push($arrayAll,$array);
			}
			return $arrayAll;

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

		function generateInformeRevenimiento($token,$rol_usuario_id,$id_formatoRegistroRev,$target_dir){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			$info = $this->getInfoRev($token,$rol_usuario_id,$id_formatoRegistroRev);
			$registros = $this->getRegRev($token,$rol_usuario_id,$id_formatoRegistroRev);
			$pdf = new InformeRevenimiento();	
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

		/*
			Es este o es el otro
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
		*/

		
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
					concretera,
					remisionNo,
					horaSalida,
					horaLlegada
			      FROM 
			      	registrosRev,
			      	concretera
			      WHERE 
			      	concretera_id = id_concretera AND 
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
			else{
				echo json_encode($arr);
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
		
		function getRegCuboByFCCH($token,$rol_usuario_id,$id_formatoCampo,$arrayCampos){
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
									ensayo_def_pi
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
						$arrayFont = array("family" => "Arial", "style" => "null", "size" => 7);
						//$this->SetFont('Arial','',$tam_font_head);
						//$pdf->SetFont($arrayFont['family'],$arrayFont['style'],$arrayFont['size']);

						//Extraermos el ultimo valor del array
						$tam_localizacion = array_pop($arrayCampos);
						$array_aux = $this->truncaCadenaArray($arrayFont,$s,$arrayCampos);

						//Obtenemos el valor que nos faltaba
						$valueLocalizacion = $dbS->qarrayA("
												    	SELECT
												    		localizacion
														FROM
															ensayoCubo,
															registrosCampo,
															formatoCampo,
															(
																SELECT
																	ensayo_def_pi
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

						$string = $this->truncaCadena($arrayFont,$valueLocalizacion['localizacion'],($tam_localizacion * 6));
						$array_aux2 = array("localizacion"=>$string);

						return (array_merge($array_aux,$array_aux2));
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getHerramientaByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;

		}

		function getRegCilindroByFCCH($token,$rol_usuario_id,$id_formatoCampo,$arrayCampos){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qAll("
				    	SELECT
				    		falla,
				    		ROUND((((carga/((( ((d1+d2)/2) * ((d1+d2)/2))*var_system.ensayo_def_pi)/4))/fprima)*100),3)  AS porcentResis,
				    		fprima,
				    		ROUND((carga/((( ((d1+d2)/2) * ((d1+d2)/2))*var_system.ensayo_def_pi)/4)),3) AS kg,
				    		ROUND(((carga/((( ((d1+d2)/2) * ((d1+d2)/2))*var_system.ensayo_def_pi)/4))/var_system.ensayo_def_MPa),3)  AS mpa,
				    		carga,
				    		ROUND(((carga*var_system.ensayo_def_kN)/var_system.ensayo_def_divisorKn),3) AS kn,
				    		ROUND(((( ((d1+d2)/2) * ((d1+d2)/2))*var_system.ensayo_def_pi)/4),3) AS area,
				    		ROUND (h1+h2)/2 AS altura,
				    		ROUND (d1+d2)/2 AS diametro,
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
							peso,
							revObra,
							claveEspecimen,
							ensayoCilindro.fecha AS fechaEnsaye
						FROM
							ensayoCilindro,
							registrosCampo,
							formatoCampo,
							(
								SELECT
									ensayo_def_pi,
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
						$arrayFont = array("family" => "Arial", "style" => "null", "size" => 7);
						//$this->SetFont('Arial','',$tam_font_head);
						//$pdf->SetFont($arrayFont['family'],$arrayFont['style'],$arrayFont['size']);

						//Extraermos el ultimo valor del array
						$tam_localizacion = array_pop($arrayCampos);
						$array_aux = $this->truncaCadenaArray($arrayFont,$s,$arrayCampos);

						//Obtenemos el valor que nos faltaba
						$valueLocalizacion = $dbS->qarrayA("
												    	SELECT
															localizacion
														FROM
															ensayoCilindro,
															registrosCampo,
															formatoCampo
														WHERE
															id_registrosCampo = ensayoCilindro.registrosCampo_id AND
															id_formatoCampo = ensayoCilindro.formatoCampo_id AND 
															ensayoCilindro.formatoCampo_id = 1QQ
												      ",
												      array($id_formatoCampo),
												      "SELECT"
												      );

						$string = $this->truncaCadena($arrayFont,$valueLocalizacion['localizacion'],($tam_localizacion * 6));
						$array_aux2 = array("localizacion"=>$string);

						return (array_merge($array_aux,$array_aux2));
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getHerramientaByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;

		}

		function getRegVigaByFCCH($token,$rol_usuario_id,$id_formatoCampo,$arrayCampos){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qAll("
				    	SELECT
							claveEspecimen,
							registrosCampo.fecha,
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
							CASE
								WHEN lijado IS NULL OR lijado = 'N/A' THEN 'CUERO'  
								WHEN cuero IS NULL OR cuero = 'N/A' THEN 'LIJADO'
								ELSE 'ERROR'
							END AS puntosApoyo,
							condiciones,
							(ancho1 + ancho2)/2 AS anchoPromedio,
							(per1 + per2)/2 AS perPromedio,
							disApoyo,
							carga,
							( ((ancho1 + ancho2)/2) * ( ((per1 + per2)/2) * ((per1 + per2)/2) ) ) AS modRuptura,
							( ((ancho1 + ancho2)/2) * ( ((per1 + per2)/2) * ((per1 + per2)/2) ) )/ensayo_def_MPa AS modRuptura2,
							defectos
						FROM
							ensayoViga,
							registrosCampo,
							formatoCampo,
							(
								SELECT
									ensayo_def_pi,
									ensayo_def_kN,
									ensayo_def_MPa,
									ensayo_def_divisorKn
								FROM
									systemstatus
								ORDER BY id_systemstatus DESC LIMIT 1
							)AS var_system
						WHERE
							id_registrosCampo = ensayoViga.registrosCampo_id AND
							id_formatoCampo = ensayoViga.formatoCampo_id AND 
							ensayoViga.formatoCampo_id = 1QQ 
				      ",
				      array($id_formatoCampo),
				      "SELECT"
				      );
				
				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('No existen registro relacionados con el id_formatoCampo'=>$id_formatoCampo,'error' => 5);
					}
					else{
						$arrayFont = array("family" => "Arial", "style" => "null", "size" => 7);
						//$this->SetFont('Arial','',$tam_font_head);
						//$pdf->SetFont($arrayFont['family'],$arrayFont['style'],$arrayFont['size']);
						return ($this->truncaCadenaArray($arrayFont,$s,$arrayCampos));
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getHerramientaByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;

		}

		function getInfoCilindro($token,$rol_usuario_id,$id_formatoCampo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qarrayA("
			     				SELECT
									informeNo,
									obra.obra,
									obra.localizacion,
									cliente.nombre,
									CONCAT(calle,' ',noExt,' ',noInt,', ',col,', ',municipio,', ',estado) AS direccion,
									formatoCampo.observaciones
								FROM
									formatoCampo,
									ordenDeTrabajo,
									obra,
									cliente
								WHERE
									cliente.id_cliente = obra.cliente_id AND
									obra.id_obra = ordenDeTrabajo.obra_id AND
									ordenDeTrabajo.id_ordenDeTrabajo = formatoCampo.ordenDeTrabajo_id AND
									formatoCampo.id_formatoCampo = 1QQ
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

		function getInfoViga($token,$rol_usuario_id,$id_formatoCampo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qarrayA("
			     				SELECT
									informeNo,
									obra.obra,
									obra.localizacion AS obraLocalizacion,
									cliente.razonSocial,
									CONCAT(calle,' ',noExt,' ',noInt,', ',col,', ',municipio,', ',estado) AS direccion,
									formatoCampo.observaciones,
									registrosCampo.localizacion,
									formatoCampo.tipoConcreto
								FROM
									formatoCampo,
									ordenDeTrabajo,
									obra,
									cliente,
									registrosCampo
								WHERE
									cliente.id_cliente = obra.cliente_id AND
									obra.id_obra = ordenDeTrabajo.obra_id AND
									ordenDeTrabajo.id_ordenDeTrabajo = formatoCampo.ordenDeTrabajo_id AND
									registrosCampo.formatoCampo_id = formatoCampo.id_formatoCampo AND
									formatoCampo.id_formatoCampo = 1QQ
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

		/*
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
		*/
	}
	

?>