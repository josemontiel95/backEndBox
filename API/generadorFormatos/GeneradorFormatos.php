<?php
	include_once("./../../configSystem.php"); 
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
	include_once("./../../disenoFormatos/EnsayoCuboPDF.php");
	include_once("./../../disenoFormatos/EnsayoCilindroPDF.php");
	include_once("./../../disenoFormatos/EnsayoVigaPDF.php");

	class GeneradorFormatos{
		function generateInformeCampo($token,$rol_usuario_id,$id_formatoCampo,$target_dir){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
					$formato = new FormatoCampo();	
					$infoFormato = json_decode($formato->getInfoByID($token,$rol_usuario_id,$id_formatoCampo),true);
					if($infoFormato == "empty")
						echo "No hay resultados del id solicitado";
				switch ($infoFormato['tipo_especimen']) {
					case 'CUBO':
						//Obtenemos la informacion de quien esta realizando el pdf
						$infoU = $this->getInfoUserFinal($token,$rol_usuario_id,$id_formatoCampo);
						if(!(array_key_exists('error', $infoU))){
							$infoFormato = $this->getInfoCuboByFCCH($token,$rol_usuario_id,$id_formatoCampo);
							if(!(array_key_exists('error', $infoFormato))){
								$regisFormato = $this->getRegCuboByFCCH($token,$rol_usuario_id,$id_formatoCampo);
								if(!(array_key_exists('error', $regisFormato))){
									$pdf = new InformeCubos();	
									$pdf->CreateNew($infoFormato,$regisFormato,$infoU,$target_dir);
								}
								else{
									return json_encode($regisFormato);
								}
							}else{
								return json_encode($infoFormato);
							}	
						}else{
							return json_encode($infoU);
						}
						break;
					case 'CILINDRO':
						//Obtenemos la informacion de quien esta realizando el pdf
						$infoU = $this->getInfoUserFinal($token,$rol_usuario_id,$id_formatoCampo);
						if(!(array_key_exists('error', $infoU))){
							$infoFormato = $this->getInfoCiliByFCCH($token,$rol_usuario_id,$id_formatoCampo);
							if(!(array_key_exists('error', $infoFormato))){
								$regisFormato = $this->getRegCilindroByFCCH($token,$rol_usuario_id,$id_formatoCampo);
								if(!(array_key_exists('error', $regisFormato))){
									$pdf = new InformeCilindros();	
									$pdf->CreateNew($infoFormato,$regisFormato,$infoU,$target_dir);
								}
								else{
									return json_encode($regisFormato);
								}

							}
							else{
								return json_encode($infoFormato);
							}
						}else{
							return json_encode($infoU);
						}
						break;
					case 'VIGAS':
						//Obtenemos la informacion de quien esta realizando el pdf
						$infoU = $this->getInfoUserFinal($token,$rol_usuario_id,$id_formatoCampo);
						if(!(array_key_exists('error', $infoU))){
							$infoFormato = $this->getInfoViga($token,$rol_usuario_id,$id_formatoCampo);
							if(!(array_key_exists('error', $infoFormato))){
								$regisFormato = $this->getRegVigaByFCCH($token,$rol_usuario_id,$id_formatoCampo);
								if(!(array_key_exists('error', $regisFormato))){
									$pdf  = new InformeVigas();
									return $pdf->CreateNew($infoFormato,$regisFormato,$infoU,$target_dir);	
								}
								else{
									return json_encode($regisFormato);
								}
							}else{
								return json_encode($infoFormato);
							}
						}else{
							return json_encode($infoU);
						}
						break;
				}
			}
			else{
				return json_encode($arr);
			}

		}

		function getInfoUser($token,$rol_usuario_id){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				//Obtenemos el id del usuario que solicita
				$id_usuario = substr(decurl($token),10);
				
				$s= $dbS->qarrayA(
					"	SELECT
							laboratorio_id,						
							CONCAT(nombre,' ',apellido) AS nombreRealizo,
							firma AS firmaRealizo
						FROM
							usuario
						WHERE
							usuario.id_usuario = 1QQ
				      ",
				      array($id_usuario),				      
				      "SELECT -- GeneradorFormatos :: getInfoUser : 1"
				      );

				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('id_usuario' => $id_usuario,'estatus' => 'Error no se encontro información suficiente en  ese id','error' => 5);
					}else{
						return $s;
					}
				}else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getInfoByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;
		}



		/*
			FUNCION QUE EXTRAE EL USUARIO Y AL JEFE DE LABORATORIO QUE CREO LA ORDEN DE TRABAJO (ESTE CASO SIRVE PARA LOS FORMATOS DE GABINO)


		*/
		function getInfoUserCubos($token,$rol_usuario_id,$id_footerEnsayo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				//Obtenemos el id del usuario que solicita
				$id_usuario = substr(decurl($token),10);
				
				$s= $dbS->qarrayA(
					"  SELECT
							usuarioRealizo.nombreRealizo,	
							usuarioRealizo.firma AS firmaRealizo,
							CONCAT(nombre,' ',apellido) AS nombreLaboratorista,
							usuario.firma AS firmaLaboratorista
						FROM
							ordenDeTrabajo,
							formatoCampo,
							ensayoCubo,
							usuario,
							(
								SELECT						
									CONCAT(nombre,' ',apellido) AS nombreRealizo,
									firma
								FROM
									usuario
								WHERE
									usuario.id_usuario = 1QQ
							) AS usuarioRealizo
						WHERE
							ordenDeTrabajo.jefa_lab_id = usuario.id_usuario AND 
							formatoCampo.ordenDeTrabajo_id = ordenDeTrabajo.id_ordenDeTrabajo AND
							ensayoCubo.formatoCampo_id = formatoCampo.id_formatoCampo AND
							ensayoCubo.footerEnsayo_id = 1QQ
				      ",
				      array($id_usuario,$id_footerEnsayo),				      
				      "SELECT -- GeneradorFormatos :: getInfoUser : 1"
				      );

				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('id_usuario' => $id_usuario,'id_footerEnsayo' => $id_footerEnsayo,'estatus' => 'Error no se encontro información suficiente en esos id','error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getInfoByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;
		}

		function getInfoUserCilindros($token,$rol_usuario_id,$id_footerEnsayo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				//Obtenemos el id del usuario que solicita
				$id_usuario = substr(decurl($token),10);
				
				$s= $dbS->qarrayA(
					"   SELECT
							usuarioRealizo.nombreRealizo,	
							usuarioRealizo.firma AS firmaRealizo,
							CONCAT(nombre,' ',apellido) AS nombreLaboratorista,
							usuario.firma AS firmaLaboratorista
						FROM
							ordenDeTrabajo,
							formatoCampo,
							ensayoCilindro,
							usuario,
							(
								SELECT						
									CONCAT(nombre,' ',apellido) AS nombreRealizo,
									firma
								FROM
									usuario
								WHERE
									usuario.id_usuario = 1QQ
							) AS usuarioRealizo
						WHERE
							ordenDeTrabajo.jefa_lab_id = usuario.id_usuario AND 
							formatoCampo.ordenDeTrabajo_id = ordenDeTrabajo.id_ordenDeTrabajo AND
							ensayoCilindro.formatoCampo_id = formatoCampo.id_formatoCampo AND
							ensayoCilindro.footerEnsayo_id = 1QQ
				      ",
				      array($id_usuario,$id_footerEnsayo),				      
				      "SELECT -- GeneradorFormatos :: getInfoUser : 1"
				      );

				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('id_usuario' => $id_usuario,'id_footerEnsayo' => $id_footerEnsayo,'estatus' => 'Error no se encontro información suficiente en esos id','error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getInfoByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;
		}

		function getInfoUserVigas($token,$rol_usuario_id,$id_footerEnsayo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				//Obtenemos el id del usuario que solicita
				$id_usuario = substr(decurl($token),10);
				
				$s= $dbS->qarrayA(
					"   SELECT
							usuarioRealizo.nombreRealizo,	
							usuarioRealizo.firma AS firmaRealizo,
							CONCAT(nombre,' ',apellido) AS nombreLaboratorista,
							usuario.firma AS firmaLaboratorista
						FROM
							ordenDeTrabajo,
							formatoCampo,
							ensayoViga,
							usuario,
							(
								SELECT						
									CONCAT(nombre,' ',apellido) AS nombreRealizo,
									firma
								FROM
									usuario
								WHERE
									usuario.id_usuario = 1QQ
							) AS usuarioRealizo
						WHERE
							ordenDeTrabajo.jefa_lab_id = usuario.id_usuario AND 
							formatoCampo.ordenDeTrabajo_id = ordenDeTrabajo.id_ordenDeTrabajo AND
							ensayoViga.formatoCampo_id = formatoCampo.id_formatoCampo AND
							ensayoViga.footerEnsayo_id = 1QQ
				      ",
				      array($id_usuario,$id_footerEnsayo),				      
				      "SELECT -- GeneradorFormatos :: getInfoUser : 1"
				      );

				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('id_usuario' => $id_usuario,'id_footerEnsayo' => $id_footerEnsayo,'estatus' => 'Error no se encontro información suficiente en esos id','error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getInfoByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;
		}
				
		/*
				FUNCION QUE EXTRAE AL USUARIO QUE REALIZO Y AL JEFE DE LABORATORIO ENCARGADO DEL RESPECTIVO LABORATORIO


					ESPERANDO MODIFICACION PARA OBTENER LA INFORMACION 


		function getInfoUser($token,$rol_usuario_id){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				//Obtenemos el id del usuario que solicita
				$id_usuario = substr(decurl($token),10);
				
				$s= $dbS->qarrayA("
				      	SELECT
							usuarioRealizo.nombreRealizo,
							usuarioRealizo.firma AS firmaRealizo,
							CONCAT(nombre,' ',apellido) AS nombreLaboratorista,
							usuario.firma AS firmaLaboratorista
						FROM
							usuario,
							laboratorio,
							(
								SELECT
									laboratorio_id,						
									CONCAT(nombre,' ',apellido) AS nombreRealizo,
									firma
								FROM
									usuario
								WHERE
									usuario.id_usuario = 1QQ
							) AS usuarioRealizo
						WHERE
							usuarioRealizo.laboratorio_id = laboratorio.id_laboratorio AND
							usuario.id_usuario = laboratorio.encargado_id
				      ",
				      array($id_usuario),				      
				      "SELECT -- GeneradorFormatos :: getInfoUser : 1"
				      );

				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('id_usuario' => $id_usuario,'estatus' => 'Error no se encontro información suficiente en  ese id','error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getInfoByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;
		}
		*/

		/*
		function getInfoUserFinal($token,$rol_usuario_id,$id_formatoCampo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				//Obtenemos el id del usuario que solicita
				$id_usuario = substr(decurl($token),10);
				
				$s= $dbS->qarrayA("
				      	SELECT
							nombreLaboratorista,
							firmaLaboratorista,
							nombreG,
							firmaG
						FROM
							laboratorio,
							(
								SELECT
									CONCAT(nombre,' ',apellido) AS nombreLaboratorista,
									firma AS firmaLaboratorista,
									ordenDeTrabajo.laboratorio_id AS id_laboratorio
								FROM
									ordenDeTrabajo,
									formatoCampo,
									usuario
								WHERE
									ordenDeTrabajo.jefa_lab_id = usuario.id_usuario AND
									formatoCampo.ordenDeTrabajo_id =  ordenDeTrabajo.id_ordenDeTrabajo AND
									formatoCampo.id_formatoCampo = 1107
							)AS laboratorista
						WHERE
							laboratorio.id_laboratorio = laboratorista.id_laboratorio
				      ",
				      array($id_formatoCampo),				      
				      "SELECT -- GeneradorFormatos :: getInfoUserFinal : 1"
				      );

				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Error no se encontro información suficiente en  ese id','error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getInfoByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;
		}

		*/

		function getInfoUserFinal($token,$rol_usuario_id){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				//Obtenemos el id del usuario que solicita
				$id_usuario = substr(decurl($token),10);
				
				$s= $dbS->qarrayA(
					"  	SELECT
							nombreLaboratorista,
							firmaLaboratorista,
							nombreG,
							firmaG
						FROM
							laboratorio,
							(
								SELECT
									laboratorio_id,						
									CONCAT(nombre,' ',apellido) AS nombreLaboratorista,
									firma AS firmaLaboratorista
								FROM
									usuario
								WHERE
									usuario.id_usuario = 1QQ
							) AS laboratorista
						WHERE
							laboratorio.id_laboratorio = laboratorista.laboratorio_id
				      ",
				      array($id_usuario),				      
				      "SELECT -- GeneradorFormatos :: getInfoUserFinal : 1"
				      );

				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('id' => 'Null','estatus' => 'Error no se encontro información suficiente de los usuarios que firman,','error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getInfoUserFinal , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;
		}

		
		

		function getInfoCuboByFCCH($token,$rol_usuario_id,$id_formatoCampo){
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
						razonSocial,
						CONCAT(calle,' ',noExt,' ',noInt,', ',col,', ',municipio,', ',estado) AS direccion,
						incertidumbreCubo
				      FROM 
				        ordenDeTrabajo,cliente,obra,formatoCampo
				      WHERE 
				      	obra_id = id_obra AND
				      	cliente_id = id_cliente AND
						ordenDeTrabajo.id_ordenDeTrabajo = formatoCampo.ordenDeTrabajo_id AND
				      	formatoCampo.id_formatoCampo = 1QQ
				      ",
				      array($id_formatoCampo),
				      "SELECT -- GeneradorFormatos :: getInfoCuboByFCCH : 1"
				      );

				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Error no se encontro información suficiente en  ese id','error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getInfoByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;
		}

		function getInfoCiliByFCCH($token,$rol_usuario_id,$id_formatoCampo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qarrayA(
					" SELECT
				      	informeNo,
				        obra,
						localizacion,
						formatoCampo.observaciones,
						nombre,
						razonSocial,
						CONCAT(calle,' ',noExt,' ',noInt,', ',col,', ',municipio,', ',estado) AS direccion,
						incertidumbreCilindro
				      FROM 
				        ordenDeTrabajo,cliente,obra,formatoCampo
				      WHERE 
				      	obra_id = id_obra AND
				      	cliente_id = id_cliente AND
						ordenDeTrabajo.id_ordenDeTrabajo = formatoCampo.ordenDeTrabajo_id AND
				      	formatoCampo.id_formatoCampo = 1QQ
				      ",
				      array($id_formatoCampo),
				      "SELECT -- GeneradorFormatos :: getInfoCiliByFCCH : 1"
				      );

				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Error no se encontro información suficiente en  ese id','error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getInfoByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;
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
					case 'REVENIMIENTO':
						$pdf = new Revenimiento();
						$pdf->demo();
						break;
					case 'INFO_REVENIMIENTO':
						$pdf = new InformeRevenimiento();
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
			if($arr['error'] == 0){
				$infoU = $this->getInfoUser($token,$rol_usuario_id);
				if(!(array_key_exists('error', $infoU))){
					$info = $this->getInfoRev($token,$rol_usuario_id,$id_formatoRegistroRev);
					if(!(array_key_exists('error', $info))){
						$registros = $this->getRegRev($token,$rol_usuario_id,$id_formatoRegistroRev);
						if(!(array_key_exists('error', $registros))){
							$pdf = new Revenimiento();	
							return $pdf->CreateNew($info,$registros,$infoU,$target_dir);
						}
						else{
							return json_encode($registros);
						}
					}
					else{
						return json_encode($info);
					}
				}else{
					return json_encode($infoU);
				}				
			}
			else{
				return json_encode($arr);
			}
		}

		function getInfoRev($token,$rol_usuario_id,$id_formatoRegistroRev){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qarrayA(
					"SELECT
				      	regNo,
				      	obra.incertidumbre,
				        obra,
				        obra.localizacion AS locObra,
				        nombre,
						razonSocial,
						CONCAT(calle,' ',noExt,' ',noInt,', ',col,', ',municipio,', ',estado) AS direccion,

						formatoRegistroRev.localizacion AS locRev,

						formatoRegistroRev.observaciones,
						
						CONO,

						VARILLA,

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
				      "SELECT -- GeneradorFormatos :: getInfoRev : 1 "
				      );

				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Error no se encontro información suficiente en  ese id','error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getInfoByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;
		}

		function generateInformeRevenimiento($token,$rol_usuario_id,$id_formatoRegistroRev,$target_dir){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$infoU = $this->getInfoUserFinal($token,$rol_usuario_id);
				if(!(array_key_exists('error', $infoU))){
					$info = $this->getInfoRev($token,$rol_usuario_id,$id_formatoRegistroRev);
					if(!(array_key_exists('error', $info))){
						$registros = $this->getRegRev($token,$rol_usuario_id,$id_formatoRegistroRev);
						if(!(array_key_exists('error', $registros))){
							$pdf = new InformeRevenimiento();	
							$pdf->CreateNew($info,$registros,$infoU,$target_dir);
						}else{
							return json_encode($registros);
						}
					}else{
						return json_encode($info);
					}
				}else{
					return json_encode($infoU);
				}
			}
			else{
				return json_encode($arr);
			}			
		}

		function generateEnsayoVigas($token,$rol_usuario_id,$id_footerEnsayo,$target_dir){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				//Obtenemos la informacion de quien esta realizando el pdf
				$infoU = $this->getInfoUserVigas($token,$rol_usuario_id,$id_footerEnsayo);
				if(!(array_key_exists('error', $infoU))){
					$info = $this->getInfoEnsayoVigas($token,$rol_usuario_id,$id_footerEnsayo);
					if(!(array_key_exists('error', $info))){
						$registros = $this->getRegEnsayoVigas($token,$rol_usuario_id,$id_footerEnsayo);
						if(!(array_key_exists('error', $registros))){
							$pdf = new EnsayoVigaPDF();	
							return $pdf->CreateNew($info,$registros,$infoU,$target_dir);
						}else{
							return json_encode($registros);
						}
					}else{
						return json_encode($info);
					}
				}else{
					return json_encode($infoU);
				}
			}
			else{
				return json_encode($arr);
			}


		}

		function getInfoEnsayoVigas($token,$rol_usuario_id,$id_footerEnsayo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qarrayA(
					"SELECT
				      		cliente.razonSocial,
							obra.obra,
							obra.localizacion AS obraLocalizacion,
							registrosCampo.localizacion AS eleColado,
							formatoCampo.tipoConcreto,
							formatoCampo.informeNo,
							registrosCampo.fprima,
							regVerFle.placas AS regVerFle_id_placas,		
							prensas.placas AS prensa_placas
						FROM
							formatoCampo,
							ordenDeTrabajo,
							obra,
							cliente,
							registrosCampo,
							ensayoViga,
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
							cliente.id_cliente = obra.cliente_id AND
							obra.id_obra = ordenDeTrabajo.obra_id AND
							ordenDeTrabajo.id_ordenDeTrabajo = formatoCampo.ordenDeTrabajo_id AND
							registrosCampo.formatoCampo_id = formatoCampo.id_formatoCampo AND
							ensayoViga.registrosCampo_id = registrosCampo.id_registrosCampo AND
							ensayoViga.formatoCampo_id = formatoCampo.id_formatoCampo AND
							ensayoViga.footerEnsayo_id = 1QQ

				      ",
				      array($id_footerEnsayo,$id_footerEnsayo,$id_footerEnsayo,$id_footerEnsayo),
				      "SELECT  --GeneradorFormatos :: getInfoEnsayoVigas : 1"
				      );

				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('id_footerEnsayo' => $id_footerEnsayo,'estatus' => 'Error no se encontro información suficiente en  ese id','error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getInfoByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;
		}

		function getRegEnsayoVigas($token,$rol_usuario_id,$id_footerEnsayo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qAll(
					"SELECT
			      		claveEspecimen,
			      		registrosCampo.fecha AS fechaColado,
			      		ensayoViga.fecha AS fechaEnsayo,
			      		CASE
							WHEN MOD(diasEnsaye,3) = 1 THEN prueba1  
							WHEN MOD(diasEnsaye,3) = 2 THEN prueba2  
							WHEN MOD(diasEnsaye,3) = 0 THEN prueba3  
							ELSE 'Error, Contacta a soporte'
						END AS diasEnsaye,
						condiciones,
						lijado,
						cuero,	
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(ancho1, 1), 1), CHAR), ',', '  '), '.', ',') AS ancho1,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(ancho2, 1), 1), CHAR), ',', '  '), '.', ',') AS ancho2,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(per1, 1), 1), CHAR), ',', '  '), '.', ',') AS per1,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(per2, 1), 1), CHAR), ',', '  '), '.', ',') AS per2,
						
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(l1, 0), 0), CHAR), ',', '  '), '.', ',') AS l1,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(l2, 0), 0), CHAR), ',', '  '), '.', ',') AS l2,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(l3, 0), 0), CHAR), ',', '  '), '.', ',') AS l3,

						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(((l1+l2+l3)/3), 0), 0), CHAR), ',', '  '), '.', ',') AS prom,
						
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(disApoyo, 1), 1), CHAR), ',', '  '), '.', ',') AS disApoyo,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(disCarga, 1), 1), CHAR), ',', '  '), '.', ',') AS disCarga,

						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(carga, 0), 0), CHAR), ',', '  '), '.', ',') AS carga,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(mr, 1), 1), CHAR), ',', '  '), '.', ',') AS modRuptura,
						defectos,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(velAplicacionExp, 2), 2), CHAR), ',', '  '), '.', ',') AS velAplicacionExp,
						CONCAT(nombre,' ',apellido) AS realizo
					FROM 
						usuario,
						footerEnsayo,
						formatoCampo,
						ensayoViga,
						registrosCampo
					WHERE
						footerEnsayo.encargado_id = usuario.id_usuario AND
						formatoCampo.id_formatoCampo = registrosCampo.formatoCampo_id AND
						id_formatoCampo = ensayoViga.formatoCampo_id AND
						id_registrosCampo = ensayoViga.registrosCampo_id AND
						ensayoViga.footerEnsayo_id = footerEnsayo.id_footerEnsayo AND
						footerEnsayo.id_footerEnsayo = 1QQ
			      ",
			      array($id_footerEnsayo),
			      "SELECT -- GeneradorFormatos :: getRegEnsayoVigas : 1 "
			      );

				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('No existen registro relacionados con el id_footerEnsayo'=>$id_footerEnsayo,'error' => 5);
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

		function generateEnsayoCilindros($token,$rol_usuario_id,$id_footerEnsayo,$target_dir){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				//Obtenemos la informacion de quien esta realizando el pdf
				$infoU = $this->getInfoUserCilindros($token,$rol_usuario_id,$id_footerEnsayo);
				if(!(array_key_exists('error', $infoU))){
					$info = $this->getInfoEnsayoCilindros($token,$rol_usuario_id,$id_footerEnsayo);
					if(!(array_key_exists('error', $info))){
						$registros = $this->getRegEnsayoCilindros($token,$rol_usuario_id,$id_footerEnsayo);
						if(!(array_key_exists('error', $registros))){
							$pdf = new EnsayoCilindroPDF();	
							return $pdf->CreateNew($info,$registros,$infoU,$target_dir);
						}else{
							return json_encode($registros);
						}
					}else{
						return json_encode($info);
					}
				}else{
					return json_encode($infoU);
				}
			}
			else{
				return json_encode($arr);
			}
		}
		

		function generateEnsayoCubos($token,$rol_usuario_id,$id_footerEnsayo,$target_dir){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				//Obtenemos la informacion de quien esta realizando el pdf
				$infoU = $this->getInfoUserCubos($token,$rol_usuario_id,$id_footerEnsayo);
				if(!(array_key_exists('error', $infoU))){
					$info = $this->getInfoEnsayoCubo($token,$rol_usuario_id,$id_footerEnsayo);
					if(!(array_key_exists('error', $info))){
						$registros = $this->getRegEnsayoCubo($token,$rol_usuario_id,$id_footerEnsayo);
						if(!(array_key_exists('error', $registros))){
							$pdf = new EnsayoCuboPDF();	
							return $pdf->CreateNew($info,$registros,$infoU,$target_dir);
						}else{
							return json_encode($registros);
						}
					}else{
						return json_encode($info);
					}
				}else{
					return json_encode($infoU);
				}
			}
			else{
				return json_encode($arr);
			}
		}

		function getInfoEnsayoCilindros($token,$rol_usuario_id,$id_footerEnsayo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qarrayA(
					"   SELECT
							ensayoCilindro.footerEnsayo_id,
							ensayoCilindro.fecha AS fechaEnsayo,
							basculas.placas AS buscula_placas,
							regVerFle.placas AS regVerFle_id_placas,		
							prensas.placas AS prensa_placas,
							observaciones,
							encargado_id,
							CONCAT(nombre,' ',apellido) AS nombre
						FROM
							ensayoCilindro,
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
							ensayoCilindro.footerEnsayo_id = footerEnsayo.id_footerEnsayo AND
							ensayoCilindro.footerEnsayo_id = 1QQ
				      ",
				      array($id_footerEnsayo,$id_footerEnsayo,$id_footerEnsayo,$id_footerEnsayo),
				      "SELECT  --GeneradorFormatos :: getInfoEnsayoCilindros : 1"
				      );

				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('id_footerEnsayo' => $id_footerEnsayo,'estatus' => 'Error no se encontro información suficiente en  ese id','error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getInfoByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;
		}

		function getRegEnsayoCilindros($token,$rol_usuario_id,$id_footerEnsayo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qAll(
					"SELECT
						registrosCampo.fecha AS fechaColado,
						informeNo,
						claveEspecimen,
						peso,
						CASE
						WHEN MOD(diasEnsaye,4) = 1 THEN prueba1
						WHEN MOD(diasEnsaye,4) = 2 THEN prueba2
						WHEN MOD(diasEnsaye,4) = 3 THEN prueba3
						WHEN MOD(diasEnsaye,4) = 0 THEN prueba4
						ELSE 'Error, Contacta a soporte'
						END AS diasEnsayeFinal,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(d1, 1), 1), CHAR), ',', '  '), '.', ',') AS d1,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(d2, 1), 1), CHAR), ',', '  '), '.', ',') AS d2,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(h1, 1), 1), CHAR), ',', '  '), '.', ',') AS h1,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(h2, 1), 1), CHAR), ',', '  '), '.', ',') AS h2,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(carga, 0), 0), CHAR), ',', '  '), '.', ',') AS carga,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(((( ((d1+d2)/2) * ((d1+d2)/2))*var_system.ensayo_def_pi)/4), 1), 1), CHAR), ',', '  '), '.', ',') AS area,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND((((carga/((( ((d1+d2)/2) * ((d1+d2)/2))*var_system.ensayo_def_pi)/4))/fprima)*100), 0), 0), CHAR), ',', '  '), '.', ',') AS porcentResis,
						ROUND(velAplicacionExp,1),
						IF(falla = 0,'-',REPLACE(REPLACE(CONVERT(FORMAT(ROUND(falla, 0), 0), CHAR), ',', '  '), '.', ',')) AS falla
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
						id_formatoCampo = ensayoCilindro.formatoCampo_id AND
						id_registrosCampo = ensayoCilindro.registrosCampo_id AND
						ensayoCilindro.footerEnsayo_id = 1QQ
			      ",
			      array($id_footerEnsayo),
			      "SELECT -- GeneradorFormatos :: getRegEnsayoCilindros : 1 "
			      );
				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('No existen registro relacionados con el id_footerEnsayo'=>$id_footerEnsayo,'error' => 5);
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



		function getInfoEnsayoCubo($token,$rol_usuario_id,$id_footerEnsayo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qarrayA("
				      	SELECT
							ensayoCubo.footerEnsayo_id,
							ensayoCubo.fecha AS fechaEnsayo,
							basculas.placas AS buscula_placas,
							regVerFle.placas AS regVerFle_id_placas,		
							prensas.placas AS prensa_placas,
							observaciones,
							encargado_id,
							CONCAT(nombre,' ',apellido) AS nombre
						FROM
							ensayoCubo,
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
							ensayoCubo.footerEnsayo_id = footerEnsayo.id_footerEnsayo AND
							ensayoCubo.footerEnsayo_id = 1QQ
				      ",
				      array($id_footerEnsayo,$id_footerEnsayo,$id_footerEnsayo,$id_footerEnsayo),
				      "SELECT  --GeneradorFormatos :: getInfoEnsayoCubo : 1"
				      );

				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('id_footerEnsayo' => $id_footerEnsayo,'estatus' => 'Error no se encontro información suficiente en  ese id','error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getInfoByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;
		}

		function getRegEnsayoCubo($token,$rol_usuario_id,$id_footerEnsayo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qAll("
			      	SELECT
							registrosCampo.fecha AS fechaColado,
							informeNo,
							claveEspecimen,
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
						END AS diasEnsayeFinal,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(l1, 1), 1), CHAR), ',', '  '), '.', ',') AS l1,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(l2, 1), 1), CHAR), ',', '  '), '.', ',') AS l2,	
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND(carga, 0), 0), CHAR), ',', '  '), '.', ',') AS carga,
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND((l1*l2), 1), 1), CHAR), ',', '  '), '.', ',') AS area,	
						ROUND(velAplicacionExp,1),
						REPLACE(REPLACE(CONVERT(FORMAT(ROUND((carga/(l1*l2)), 0), 0), CHAR), ',', '  '), '.', ',') AS kg
					FROM 
						ensayoCubo,
						registrosCampo,
						formatoCampo
					WHERE
						id_formatoCampo = ensayoCubo.formatoCampo_id AND
						id_registrosCampo = ensayoCubo.registrosCampo_id AND
						ensayoCubo.footerEnsayo_id = 1QQ
			      ",
			      array($id_footerEnsayo),
			      "SELECT -- GeneradorFormatos :: getRegEnsayoCubo : 1 "
			      );
				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('No existen registro relacionados con el id_footerEnsayo'=>$id_footerEnsayo,'error' => 5);
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
					ROUND(volumen,1) AS volumen,
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
			      "SELECT -- GeneradorFormatos :: getRegRev : 1 "
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
				$infoU = $this->getInfoUser($token,$rol_usuario_id);
				if(!(array_key_exists('error', $infoU))){
					$infoFormato = $this->getInfoCCH($token,$rol_usuario_id,$id_formatoCampo);
					if(!(array_key_exists('error',$infoFormato))){
						$regisFormato = $this->getRegCCH($token,$rol_usuario_id,$id_formatoCampo);
						if(!(array_key_exists('error', $regisFormato))){
							$pdf = new CCH();
							return $pdf->CreateNew($infoFormato,$regisFormato,$infoU,$target_dir);
						}
						else{
							return json_encode($regisFormato);
						}
					}else{
						return json_encode($infoFormato);
					}
				}else{

					return json_encode($infoU);
				}				
			}
			else{
				return json_encode($arr);
			}
		}
		
		function getInfoCCH($token,$rol_usuario_id,$id_formatoCampo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qarrayA(
					" SELECT
				      	informeNo,
				        obra,
						localizacion,
						formatoCampo.observaciones,
						nombre,
						razonSocial,
						CONCAT(calle,' ',noExt,' ',noInt,', ',col,', ',municipio,', ',estado) AS direccion,

						formatoCampo.tipo AS tipo_especimen,
						
						CONO,
						
						VARILLA,
			
						FLEXOMETRO,
			
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
				      "SELECT -- GeneradorFormatos :: getInfoCCH : 1 "
				      );

				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Error no se encontro información suficiente en  ese id','error' => 5);
					}
					else{
						return $s;
					}
				}
				else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getInfoByID , verifica tus datos y vuelve a intentarlo','error' => 6);
				}
			}
			return $arr;
		}

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
							grupo,
							localizacion
						FROM
							registrosCampo,
							formatoCampo
						WHERE
							id_formatoCampo = formatoCampo_id AND 
							formatoCampo_id = 1QQ
				      ",
				      array($id_formatoCampo),
				      "SELECT -- GeneradorFormatos :: getRegCCH : 1 "
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
				$s= $dbS->qAll(
					"SELECT
				    		ensayoCubo.fecha AS fechaEnsaye,
				    		claveEspecimen,
				    		revObra,
				    		CASE
								WHEN MOD(diasEnsaye,4) = 1 THEN prueba1
								WHEN MOD(diasEnsaye,4) = 2 THEN prueba2
								WHEN MOD(diasEnsaye,4) = 3 THEN prueba3
								WHEN MOD(diasEnsaye,4) = 0 THEN prueba4
								ELSE 'Error, Contacta a soporte'
							END AS diasEnsaye,
							l1,
							l2,
							ROUND((l1*l2),3) AS area,
							ROUND(((carga*var_system.ensayo_def_kN)/var_system.ensayo_def_divisorKn),3) AS kn,
							carga,
							ROUND(((carga/(l1*l2))/var_system.ensayo_def_MPa),3)  AS mpa,
							ROUND((carga/(l1*l2)),3) AS kg,
							fprima,
				    		ROUND((((carga/(l1*l2))/fprima)*100),3)  AS porcentResis,
							grupo,
							registrosCampo.localizacion
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
							ensayoCubo.status <> 0 AND
							ensayoCubo.formatoCampo_id = 1QQ
				      ",
				      array($id_formatoCampo),
				      "SELECT -- GeneradorFormatos :: getRegCuboByFCCH : 1 "
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
				$s= $dbS->qAll(
					"	SELECT
				    		ensayoCilindro.fecha AS fechaEnsaye,
				    		claveEspecimen,
				    		revObra,
				    		peso,
				    		CASE
								WHEN MOD(diasEnsaye,4) = 1 THEN prueba1  
								WHEN MOD(diasEnsaye,4) = 2 THEN prueba2  
								WHEN MOD(diasEnsaye,4) = 3 THEN prueba3  
								WHEN MOD(diasEnsaye,4) = 0 THEN prueba4  
								ELSE 'Error, Contacta a soporte'
							END AS diasEnsaye,
							ROUND (d1+d2)/2 AS diametro,
							ROUND (h1+h2)/2 AS altura,
							ROUND(((( ((d1+d2)/2) * ((d1+d2)/2))*var_system.ensayo_def_pi)/4),3) AS area,
				    		ROUND(((carga*var_system.ensayo_def_kN)/var_system.ensayo_def_divisorKn),3) AS kn,
				    		carga,
				    		ROUND(((carga/((( ((d1+d2)/2) * ((d1+d2)/2))*var_system.ensayo_def_pi)/4))/var_system.ensayo_def_MPa),3)  AS mpa,
				    		ROUND((carga/((( ((d1+d2)/2) * ((d1+d2)/2))*var_system.ensayo_def_pi)/4)),3) AS kg,
				    		fprima,
				    		ROUND((((carga/((( ((d1+d2)/2) * ((d1+d2)/2))*var_system.ensayo_def_pi)/4))/fprima)*100),3)  AS porcentResis,
				    		falla,
							grupo,
							registrosCampo.localizacion
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
							ensayoCilindro.status <> 0 AND
							id_registrosCampo = ensayoCilindro.registrosCampo_id AND
							id_formatoCampo = ensayoCilindro.formatoCampo_id AND 
							ensayoCilindro.formatoCampo_id = 1QQ
				      ",
				      array($id_formatoCampo),
				      "SELECT -- GeneradorFormatos :: getRegCilindroByFCCH : 1 "
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

		function getRegVigaByFCCH($token,$rol_usuario_id,$id_formatoCampo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				$s= $dbS->qAll(
					"	SELECT
							claveEspecimen,
							registrosCampo.fecha,
							CASE
								WHEN MOD(diasEnsaye,3) = 1 THEN prueba1  
								WHEN MOD(diasEnsaye,3) = 2 THEN prueba2  
								WHEN MOD(diasEnsaye,3) = 0 THEN prueba3  
								ELSE 'Error, Contacta a soporte'
							END AS diasEnsaye,
							CASE
								WHEN lijado = 'SI' THEN 'LIJADO'  
								WHEN cuero = 'SI' THEN 'CUERO'
								ELSE 'ERROR'
							END AS puntosApoyo,
							condiciones,
							REPLACE(REPLACE(CONVERT(FORMAT(ROUND((ancho1 + ancho2)/2, 1), 1), CHAR), ',', '  '), '.', ',') AS anchoPromedio,
							REPLACE(REPLACE(CONVERT(FORMAT(ROUND((per1 + per2)/2, 1), 1), CHAR), ',', '  '), '.', ',') AS perPromedio,
							ROUND(disApoyo,1) AS disApoyo,
							REPLACE(REPLACE(CONVERT(FORMAT(ROUND(carga, 0), 0), CHAR), ',', '  '), '.', ',') AS carga,
							REPLACE(REPLACE(CONVERT(FORMAT(ROUND(mr, 1), 1), CHAR), ',', '  '), '.', ',') AS modRuptura,
							REPLACE(REPLACE(CONVERT(FORMAT(ROUND((mr/ensayo_def_MPa), 1), 1), CHAR), ',', '  '), '.', ',') AS modRuptura2,
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
							ensayoViga.status <> 0 AND
							id_registrosCampo = ensayoViga.registrosCampo_id AND
							id_formatoCampo = ensayoViga.formatoCampo_id AND 
							ensayoViga.formatoCampo_id = 1QQ 
				      ",
				      array($id_formatoCampo),
				      "SELECT -- GeneradorFormatos :: getRegVigaByFCCH : 1 "
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
			      "SELECT -- GeneradorFormatos :: getInfoCilindro : 1 "
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
									incertidumbreVigas,
									ensayoViga.fecha,
									registrosCampo.localizacion,
									formatoCampo.tipoConcreto,
									registrosCampo.fprima
								FROM
									formatoCampo,
									ordenDeTrabajo,
									obra,
									cliente,
									registrosCampo,
									ensayoViga
								WHERE
									cliente.id_cliente = obra.cliente_id AND
									obra.id_obra = ordenDeTrabajo.obra_id AND
									ordenDeTrabajo.id_ordenDeTrabajo = formatoCampo.ordenDeTrabajo_id AND
									registrosCampo.formatoCampo_id = formatoCampo.id_formatoCampo AND
									ensayoViga.formatoCampo_id = formatoCampo.id_formatoCampo AND
									formatoCampo.id_formatoCampo = 1QQ
			      ",
			      array($id_formatoCampo),
			      "SELECT -- GeneradorFormatos :: getInfoViga : 1 "
			      );

				
				if(!$dbS->didQuerydied){
					if($s=="empty"){
						$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Error no se encontro información suficiente en  ese id','error' => 5);
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


		


	}
	

?>