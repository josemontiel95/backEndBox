<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class Obra{
	private $id_obra;
	private $obra;
	private $prefijo;
	private $fechaDeCreacion;
	private $descripcion;
	private $cliente_id;
	private $concretera;
	private $tipo;


	/* Variables de utilería */
	private $wc = '/1QQ/';

	public function insertAdmin($token,$rol_usuario_id,$obra,$prefijo,$fechaDeCreacion,$descripcion,$localizacion,$nombre_residente,$telefono_residente,$correo_residente,$cliente_id,$concretera_id,$tipo,$revenimiento,$incertidumbre,$cotizacion,$consecutivoProbeta,$consecutivoDocumentos){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						obra(laboratorio_id,obra,prefijo,fechaDeCreacion,descripcion,localizacion,nombre_residente,telefono_residente,correo_residente,cliente_id,concretera_id,tipo,revenimiento, incertidumbre, cotizacion,consecutivoProbeta,consecutivoDocumentos)

						VALUES
						('1QQ','1QQ','1QQ','1QQ','1QQ','1QQ','1QQ','1QQ','1QQ',1QQ,1QQ,1QQ,1QQ,1QQ,'1QQ','1QQ','1QQ')
				",array($laboratorio_id,$obra,$prefijo,$fechaDeCreacion,$descripcion,$localizacion,$nombre_residente,$telefono_residente,$correo_residente,$cliente_id,$concretera_id,$tipo,$revenimiento,$incertidumbre,$cotizacion,$consecutivoProbeta,$consecutivoDocumentos),"INSERT");
				$arr = array('id_obra' => 'No disponible, esto NO es un error', 'obra' => $obra, 'estatus' => 'Exito en insercion', 'error' => 0);
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	public function upDateAdmin($token,$rol_usuario_id,$id_obra,$obra,$prefijo,$fechaDeCreacion,$descripcion,$localizacion,$nombre_residente,$telefono_residente,$correo_residente,$cliente_id,$concretera_id,$tipo,$revenimiento,$incertidumbre,$cotizacion,$consecutivoProbeta,$consecutivoDocumentos){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							obra
						SET
							obra ='1QQ',
							prefijo = '1QQ', 
							fechaDeCreacion = '1QQ',
							descripcion ='1QQ',
							localizacion = '1QQ',
							nombre_residente = '1QQ',
							telefono_residente = '1QQ',
							correo_residente = '1QQ',
							cliente_id = 1QQ, 
							concretera_id = 1QQ,
							tipo = 1QQ,
							revenimiento='1QQ',
							incertidumbre='1QQ',
							cotizacion='1QQ',
							consecutivoProbeta='1QQ',
							consecutivoDocumentos='1QQ'
						WHERE
							active=1 AND
							id_obra = 1QQ
					 "
					,array($obra,$prefijo,$fechaDeCreacion,$descripcion,$localizacion,$nombre_residente,$telefono_residente,$correo_residente,$cliente_id,$concretera_id,$tipo,$revenimiento,$incertidumbre,$cotizacion,$consecutivoProbeta,$consecutivoDocumentos,$id_obra),"UPDATE"
			      	);
			$arr = array('id_obra' => $id_obra, 'obra' => $obra,'estatus' => 'Exito de actualizacion','error' => 0);
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}		
		}
		return json_encode($arr);

	}


	public function getAllAdmin($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      SELECT 
			        id_obra,
					obra,
					prefijo,
					fechaDeCreacion,
					descripcion,
					localizacion,
					nombre_residente,
					telefono_residente,
					correo_residente,
					id_cliente,
					nombre,
					IF(obra.tipo = 2,'Unitario','Iguala') AS tipo,
					obra.createdON,
					obra.lastEditedON, 
					id_concretera,
					concretera,
					revenimiento,
					incertidumbre,
					obra.laboratorio_id,
					cliente.active AS isClienteActive,
					concretera.active AS isConcreteraActive,
					IF(obra.active = 1,'Si','No') AS active
			      FROM 
			        cliente,obra,concretera
			      WHERE
			      	 cliente_id = id_cliente AND
			      	 concretera_id = id_concretera
			      ",
			      array(),
			      "SELECT"
			      );
			if(!$dbS->didQuerydied){
						if(count($arr) == 0)
							$arr = array('estatus' =>"No hay registros", 'error' => 5); //Pendiente
						
			}else
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en el query, verifica tus datos y vuelve a intentarlo','error' => 6);
		}
		return json_encode($arr);	
	}



	public function getForDroptdownAdmin($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorio_id=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      SELECT 
			        id_obra,
					obra
			      FROM 
			        obra
			      WHERE
			      	 active = 1 AND 
			      	 laboratorio_id= 1QQ
			      ORDER BY 
			      	obra	
			      ",
			      array($laboratorio_id),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
						if(count($arr) == 0)
							$arr = array('estatus' =>"No hay registros", 'error' => 5); //Pendiente
						
			}else
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en el query, verifica tus datos y vuelve a intentarlo','error' => 6);
		}
		return json_encode($arr);	
	}


	public function deactivate($token,$rol_usuario_id,$id_obra){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							obra
						SET
							active = 1QQ
						WHERE
							active=1 AND
							id_obra = 1QQ
					 "
					,array(0,$id_obra),"UPDATE"
			      	);
		//PENDIENTE por la herramienta_tipo_id para poderla imprimir tengo que cargar las variables de la base de datos?
			if(!$dbS->didQuerydied){
				$arr = array('id_obra' => $id_obra,'estatus' => 'Obra se desactivo','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la desactivacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}

		}
		return json_encode($arr);
	}

	public function activate($token,$rol_usuario_id,$id_obra){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							obra
						SET
							active = 1QQ
						WHERE
							active=0 AND
							id_obra = 1QQ
					 "
					,array(1,$id_obra),"UPDATE"
			      	);
			if(!$dbS->didQuerydied){
				$arr = array('id_obra' => $id_obra,'estatus' => 'Obra se activo','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la activacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}


	public function getByIDAdmin($token,$rol_usuario_id,$id_obra){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			      SELECT
			      	id_obra, 
			        obra,
					prefijo,
					fechaDeCreacion,
					descripcion,
					localizacion,
					nombre_residente,
					telefono_residente,
					correo_residente,
					tipo,
					id_concretera,
					concretera,
					cotizacion,
					consecutivoProbeta,
					id_cliente,
					obra.laboratorio_id AS laboratorio_id,
					nombre,
					cliente.active AS isClienteActive,
					concretera.active AS isConcreteraActive,
					revenimiento,
					incertidumbre,
					consecutivoDocumentos
			      FROM 
			      	cliente,obra,concretera
			      WHERE
			      	cliente_id = id_cliente AND
			      	concretera_id = id_concretera AND 
			      	id_obra = 1QQ
			      ",
			      array($id_obra),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('id_obra' => $id_obra,'estatus' => 'Error no se encontro ese id','error' => 5);
				}
				else{
					return json_encode($s);
				}
			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getObraByID , verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);
	}



}

?>