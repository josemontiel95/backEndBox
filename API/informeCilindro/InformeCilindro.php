<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class InformeCilindro{
	/* Variables de utilería */
	private $wc = '/1QQ/';


	public function createPDFByID($token,$rol_usuario_id,$id_formatoCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			//Obtenemos la informacion del formato de campo
			$formatoC = new formatoCampo();	$regisC = new registrosCampo();
			$infoFormato = json_decode($formatoC->getInfoByID($token,$rol_usuario_id,$id_formatoCampo));
			$regisFormato = $regisC->getAllRegistrosByID($token,$rol_usuario_id,$id_formatoCampo);

			//Instanciamos un nuevo PDF
			$pdf = new InformeCilindros();
			$pdf->CreateNew($infoFormato,$regisFormato);



			$s= $dbS->qarrayA("
			      SELECT
			      	informeNo,
			        obra,
					localizacion,
					formatoCampo.observaciones,
					nombre,
					razonSocial,
					direccion,
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
			if($dbS->didQuerydied){
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}


	




}
?>