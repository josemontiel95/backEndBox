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

class loteCorreos{
	private $id_formatoCampo;
	private $informeNo;
	private $ordenDeServicio_id;
	private $observaciones;
	private $mr;
	private $tipo;


	private $wc = '/1QQ/';


	public function getAllAdmin($token,$rol_usuario_id,$status){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			     	SELECT
						lc.id_loteCorreos,
						lc.creador_id,
						CONCAT(u.nombre,' ',u.apellido) AS encargado,
						IF(status = 0, 'Pendiente', 'Completado') AS estado,
						status
					FROM
						loteCorreos AS lc,
						usuario AS u
					WHERE
						lc.creador_id = u.id_usuario AND 
						lc.status = 1QQ AND
						laboratorio_id = 1QQ
					ORDER BY 
						lc.createdON
			      ",
			      array($status,$laboratorio_id),
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

	public function agregaFormatos($token,$rol_usuario_id,$lote,$formatosSeleccionados){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$id_usuario=$usuario->id_usuario;
		$dbS->beginTransaction();
		$formatosSeleccionados=json_decode($formatosSeleccionados);
		if($arr['error'] == 0){
			if($lote == -1){ 					// No hay lote, crerar uno nuevo
				$dbS->squery(
					"
					INSERT INTO 
						loteCorreos(
							creador_id
						)
					VALUES
						(
							1QQ
						)
					"
					,
					array($id_usuario)
					,
					"INSERT"
				);
				if(!$dbS->didQuerydied){ // Todo va bien, obtendremos id del lote y continuamos
					//Creamos el informe No.
					$lote=$dbS->lastInsertedID;
				}else{
					$dbS->rollbackTransaction();
					$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la consulta, verifica tus datos y vuelve a intentarlo','error' => 7);
					return json_encode($arr);
				}
			}
			foreach ($formatosSeleccionados as $value) {
				$dbS->squery(
					"
					INSERT INTO 
						correoDeLote(
							loteCorreos_id,
							formatoCampo_id
						)
					VALUES
						(
							1QQ,
							1QQ
						)
					"
					,
					array($lote,$value)
					,
					"INSERT"
				);
				if($dbS->didQuerydied){
					$dbS->rollbackTransaction();
					$arr = array('id_formatoCampo' => 'NULL','token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 7);
					return json_encode($arr);				
				}
			}
			$dbS->commitTransaction();
			$arr = array('lote' => $lote,'token' => $token,	'estatus' => 'Exito! ','error' => 0);
		}
		return json_encode($arr);
	}

	public function generateAllFormatosByLote($token,$rol_usuario_id,$lote){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			     	SELECT
						cl.formatoCampo_id AS formatoCampo_id,
						
					FROM
						correoDeLote AS cl
					WHERE
						cl.status = 0 AND
						cl.loteCorreos_id = 1QQ
					ORDER BY 
						fc.lastEditedON DESC	        
			      ",
			      array($lote),
			      "SELECT"
			      );
			if(!$dbS->didQuerydied && !($arr="empty")){
				foreach ($arr as $value) {
					
					$hora_de_creacion = getdate();
					$target_dir = "./../../../SystemData/FormatosFINALES/".$lote."/".$value['formatoCampo_id']."/";
					$dirDatabase = $var_system['apiRoot']."SystemData/FormatosFINALES/".$lote."/".$value['formatoCampo_id']."/"."ReporteDeConcreto"."(".$hora_de_creacion['hours']."-".$hora_de_creacion['minutes']."-".$hora_de_creacion['seconds'].")".".pdf";
					if (!file_exists($target_dir)) {
					    mkdir($target_dir, 0777, true);
					}
					$target_dir=$target_dir."ReporteDeConcreto"."(".$hora_de_creacion['hours']."-".$hora_de_creacion['minutes']."-".$hora_de_creacion['seconds'].")".".pdf";
					//Llamada a el generador de formatos
					$generador = new GeneradorFormatos();
					//Cachamos la excepcion
					try{
						$generador->generateCCH($token,$rol_usuario_id,$value['formatoCampo_id'],$target_dir);

						/*
						if($mailer->sendMailBasic($correo, $info['nombre'], $dirDatabase)==202){
							$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito Formato completado','error' => 0);	
						}else{
							$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar formato , no se pudo enviar el correo al cliente','error' => 6);
						}
						*/
					}catch(Exception $e){
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la generacion del formato:'.$e->getMessage(),'error' => 7);
						return json_encode($arr);
					}
				}
			}
		}
		return json_encode($arr);	
	}

	public function getAllFormatosByLote($token,$rol_usuario_id,$lote){
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
						cliente AS c,
						loteCorreos AS lc,
						correoDeLote AS cl
					WHERE
						fc.ordenDeTrabajo_id = ot.id_ordenDeTrabajo AND
						ot.obra_id = o.id_obra AND
						o.cliente_id = c.id_cliente AND
						lc.id_loteCorreos = cl.loteCorreos_id AND 
						cl.formatoCampo_id = fc.id_formatoCampo AND 
						lc.id_loteCorreos = 1QQ
					ORDER BY 
						fc.lastEditedON DESC	        

			      ",
			      array($lote),
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
	
	public function ping2($data){
		echo $data;
	}

}
?>