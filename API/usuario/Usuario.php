<?php
include_once("./../../configSystem.php");
//-----------------------------Incluir las demas clases 
class Usuario{
	
	/* Variables de BD*/
	private $id_usuario;
	private $nombre;
	private $apellido;
	private $laboratorio_id;
	private $laboratorio;
	private $nss;
	private $email;
	private $fechaDeNac;
	private $foto;
	private $rol_usuario_id;
	private $rol;
	private $createdON;
	private $lastEditedON;
	private $contrasena;
	private $active;
	
	/* Variables de utilería */
	private $wc = '/1QQ/';
	public function Usuario(){
	}
	/*
	public function Usuario($nombre,$apellido,$email,$fechaDeNac,$foto,$rol_usuario_id,$contrasena){
		$this->nombre= $nombre;
		$this->apellido= $apellido;
		$this->email= $email;
		$this->fechaDeNac= $fechaDeNac;
		$this->foto= $foto;
		$this->rol_usuario_id= $rol_usuario_id;
		$this->contrasena= $contrasena;
	}
	*/
	public function login($email, $contrasena){
		if($this->getByEmail($email)=="success"){
			$contrasenaSHA= hash('sha512', $contrasena);
			//echo $contrasenaSHA."<br>";
			//echo $this->contrasena;
			if($this->contrasena==$contrasenaSHA){
				$this->deactivateAllSesions();
				$arr = array('id_usuario' => $this->id_usuario, 'nombre' => $this->nombre, 'token' => $this->setToken(),'estatus' => 'exito','error' => 0);
				return json_encode($arr);
			}else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Pasword incorrecto','error' => 1);
				return json_encode($arr);
			}
		}else{
			$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'El usuario no existe','error' => 2);
				return json_encode($arr);
		}
	}

	public function setToken(){
		global $dbS;
		$token= $this->getToken();
		$s= $dbS->squery("
			      INSERT INTO sesion (
			        usuario_id,
			        token,
			        active
			        )
			      VALUES (
			        1QQ,
			        '1QQ',
			        1
			        )
			      ",
			      array($this->id_usuario,$token),
			      "INSERT"
			      );
		return $token;
	}

	public function deactivateAllSesions(){
		global $dbS;
		$s= $dbS->squery("
			      UPDATE 
			      	sesion
			      SET 
			      	active=0
			      WHERE
			        usuario_id=1QQ
			      ",
			      array($this->id_usuario),
			      "UPDATE"
			      );
	}

	public function getToken(){
		$date=date("Y/m/d");
		$preToken=$date.$this->id_usuario;
		return encurl($preToken);
	}

	public function getByEmail($email){
		global $dbS;
		/*
			qarray: Realiza un query con un array Asociativo
					Verfica que no exista una inyeccion SQL en el campo $email
		*/
		$s= $dbS->qarrayA("
			      SELECT 
			        id_usuario,
			        nombre,
			        apellido,
			        email,
			        fechaDeNac,
			        foto,
			        rol_usuario_id,
			        contrasena
			      FROM 
			        usuario 
			      WHERE 
			      	active=1 AND
			        email = '1QQ'
			      ",
			      array($email),
			      "SELECT"
			      );
		if($s=="empty"){
			return "empty";
		}
		else{
			//s es el arreglo asociativo que obtivo los valores de la tabla y los guarda en variables locales para su posterior uso.
			$this->id_usuario= $s['id_usuario'];
			$this->nombre= $s['nombre'];
			$this->apellido=$s['apellido'];
			$this->fechaDeNac= $s['fechaDeNac'];
			$this->foto= $s['foto'];
			$this->rol_usuario_id= $s['rol_usuario_id'];
			$this->contrasena= $s['contrasena'];
			$this->email= $email;
			return "success";
		}

	}



	public function validateSesion($token, $rol_usuario_id){
		switch($this->getIDByTokenAndValidate($token)){
			case'success':
				if($rol_usuario_id==$this->rol_usuario_id){
					$arr = array('id_usuario' => $this->id_usuario, 'nombre' => $this->nombre, 'token' => $token,'estatus' => 'Exito. Sesion activa','error' => 0);
					return json_encode($arr);
				}
				else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este usuario no tiene el privilegio correcto','error' => 4);
					return json_encode($arr);
				}
			break;
			case'empty':
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'No existe token','error' => 1);
				return json_encode($arr);
			break;
			case'muerta':
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'El token ya expiro','error' => 2);
				return json_encode($arr);
			break;
			case'noActiva':
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'El token ya no es valido','error' => 3);
				return json_encode($arr);
			break;
		}
	}

	public function cerrarSesion($token){
		if($this->getIDByTokenAndValidate($token)=="success"){
			$this->deactivateAllSesions();
			$arr = array('estatus' => 'Exito. Sesion cerrada','error' => 0);
			return json_encode($arr);
		}
		else{
			$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'El token no existe o ya no es valido','error' => 1);
			return json_encode($arr);
		}
	}

	public function getIDByToken($token, $rol_usuario_id){
		global $dbS;
		if($this->getIDByTokenAndValidate($token) == 'success'){
			//Valida identidad y permisos
			if($rol_usuario_id==$this->rol_usuario_id){
			
				$arr = array('id_usuario' => $this->id_usuario,
							 'nombre' => $this->nombre, 
							 'apellido' => $this->apellido,
							 'laboratorio_id' => $this->laboratorio_id,
							 'laboratorio' => $this->laboratorio,
							 'nss' => $this->nss,
							 'rol' => $this->rol,
							 'email' => $this->email, 
							 'fechaDeNac' => $this->fechaDeNac, 
							 'foto' => $this->foto, 
							 'rol_usuario_id' => $this->rol_usuario_id, 
							 'token' => $token,	
							 'estatus' => 'Exito',
							 'error' => 0);
				return json_encode($arr);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este usuario no tiene el privilegio correcto','error' => 1);
				return json_encode($arr);
			}
		}
		else{
			$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este token expiro o no existe','error' => 2);
			return json_encode($arr);
		}
	}



	public function tokenUpDateLive($token){
		global $dbS;
		$resultado = $dbS->squery(" 
									UPDATE
										sesion
									SET
										consultasAlBack = consultasAlBack+1
									WHERE 
										token = '1QQ'

									",
									array($token),"UPDATE");
	}

	/*
		
	*/
//Devuelve el valor del toke, si esta activo, si esta muerta o si no esta activa
	public function getIDByTokenAndValidate($token){
		global $dbS;
		$this->tokenUpDateLive($token);
		$s= $dbS->qarrayA("
			      SELECT 
			        id_sesion,
					usuario_id,
					active
			      FROM 
			        sesion 
			      WHERE 
			        token = '1QQ'
			      ",
			      array($token),
			      "SELECT"
			      );
		//echo json_encode($s);
		if($s=="empty"){
			return "empty";
		}
		else{
			if($s['active']==1){	//Sesion activa 	Valida que la secion no haya expirado por mas de 10 minutos
				$u=$dbS->qvalue("
						SELECT 
							IF(
								DATE_SUB(NOW(), INTERVAL 10 MINUTE)<lastEditedON,1, 0) 
						FROM 
							sesion 
						WHERE 
							id_sesion=1QQ"
					,
					array($s['id_sesion']),
					"SELECT");
				//echo "<br>".$u;
				if($u==1){	//Sesion Valida en tiempo.
					$this->id_usuario=$s['usuario_id'];
					$this->getByID($this->id_usuario);
					return "success";
				}else{		//Sesion muerta.
					$this->id_usuario=$s['usuario_id'];
					$this->getByID($this->id_usuario);
					$this->deactivateAllSesions();
					return "muerta";
				}
			}else{				//Sesion inactiva
				return "noActiva";
			}
		}
	}
	public function getAllAdmin($token){
		global $dbS;
		if($this->getIDByTokenAndValidate($token)=="success"){
			$arr= $dbS->qAll("
			      SELECT 
			        id_usuario,
			        nombre,
			        apellido,
			        laboratorio_id,
			        laboratorio,
			        nss,
			        rol,
			        email,
			        fechaDeNac,
			        foto,
			        rol_usuario_id,
			        usuario.createdON,
					usuario.lastEditedON,
			        usuario.active
			      FROM 
			        usuario,rol_usuario,laboratorio
			      WHERE
			      	laboratorio_id = id_laboratorio AND
			      	rol_usuario_id = id_rol_usuario
			      ",
			      array(),
			      "SELECT"
			      );
			return json_encode($arr);
		}else{
			$arr = array('estatus' => 'Tu token ya no es valido','error' => 1);
			return json_encode($arr);
		}
	}

	/*
		SE obtienen todos los campso del usuario mediante el ID
	*/

	public function getByID($id_usuario){
		global $dbS;
		$s= $dbS->qarrayA("
			      SELECT 
			        id_usuario,
			        nombre,
			        apellido,
			        laboratorio_id,
			        laboratorio,
			        nss,
			        email,
			        fechaDeNac,
			        foto,
			        rol_usuario_id,
			        rol,
			        usuario.createdON,
					usuario.lastEditedON,
					usuario.active,
			        contrasena
			      FROM 
			        usuario,
			        rol_usuario,
			        laboratorio
			      WHERE 
			      	laboratorio_id = id_laboratorio AND
			      	rol_usuario_id = id_rol_usuario AND
			        id_usuario = 1QQ
			      ",
			      array($id_usuario),
			      "SELECT"
			      );
		if($s=="empty"){
			return "empty";
		}
		else{
			$this->id_usuario= $s['id_usuario'];
			$this->nombre= $s['nombre'];
			$this->apellido=$s['apellido'];
			$this->laboratorio_id=$s['laboratorio_id'];
			$this->email=$s['email'];
			$this->laboratorio=$s['laboratorio'];
			$this->nss=$s['nss'];
			$this->fechaDeNac= $s['fechaDeNac'];
			$this->foto= $s['foto'];
			$this->rol = $s['rol'];
			$this->rol_usuario_id= $s['rol_usuario_id'];

			$this->rol = $s['rol'];
			$this->rol_usuario_id= $s['rol_usuario_id'];

			$this->active= $s['active'];

			$this->createdON= $s['createdON'];
			$this->lastEditedON= $s['lastEditedON'];
			return "success";
		}

	}



	public function emailValidate($email){
		global $dbS;
		$query_resultado = $dbS->qarrayA("
						SELECT
							email
						FROM 
							usuario
						WHERE
							email = '1QQ'
						
						",array($email),"SELECT");				
		if($query_resultado == "empty")
			return true;
		else
			return false;
	}


	public function emailValidateUpDate($email,$id_usuario){
		global $dbS;
		$query_resultado = $dbS->qarrayA("
						SELECT
							id_usuario,
							email
						FROM 
							usuario
						WHERE
							email = '1QQ'
						
						",array($email),"SELECT");				
		if($query_resultado == "empty")
			return true;
		else
			if($query_resultado['id_usuario'] == $id_usuario)
				return true;
			else
				return false;

	}

	public function getForDroptdownAdmin($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      SELECT 
			        id_usuario,
					nombre,
					active
			      FROM 
			        usuario
			      ",
			      array(),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if(count($arr) == 0)
					$arr = array('estatus' =>"No hay registros", 'error' => 5); //Pendiente
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 6);	
			}
		}
		return json_encode($arr);
	}


	public function insertAdmin($token,$rol_usuario_id,$nombre,$apellido,$laboratorio_id,$nss,$email,$fechaDeNac,$rol_usuario_id_new,$constrasena){
		global $dbS;
		if($this->getIDByTokenAndValidate($token) == 'success'){
			if($rol_usuario_id==$this->rol_usuario_id){
				$email =  strtolower($email);
				if($this->emailValidate($email)){
					$contrasenaValida = hash('sha512', $constrasena);
					$dbS->squery("
							INSERT INTO
							usuario(nombre,apellido,laboratorio_id,nss,email,fechaDeNac,rol_usuario_id,contrasena)

							VALUES
							('1QQ','1QQ',1QQ,'1QQ','1QQ','1QQ',1QQ,'1QQ')
							",array($nombre,$apellido,$laboratorio_id,$nss,$email,$fechaDeNac,$rol_usuario_id_new,$contrasenaValida),"INSERT");				
					if(!$dbS->didQuerydied){
						$id=$dbS->lastInsertedID;
						$arr = array('id_usuario' => $id, 'nombre' => $nombre, 'token' => $token,	'estatus' => '¡Exito!, redireccionando...','error' => 0);
						return json_encode($arr);
					}else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 2);
						return json_encode($arr);
					}
				}
				else{
					$arr = array('estatus'=>'Ese correo ya existe','error' => 4);
					return json_encode($arr);
				}					
				
			}
			else{
				
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este usuario no tiene el privilegio correcto, este comportamiento sera registrado y se cerrara el sistema','error' => 1);
				return json_encode($arr);
			}
		}else{
			$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'El token no existe o ya no es valido','error' => 3);
				return json_encode($arr);
		}
	}


	public function upDateAdmin($token,$rol_usuario_id,$id_usuario,$nombre,$apellido,$laboratorio_id,$nss,$email,$fechaDeNac,$rol_usuario_id_new){
		global $dbS;
		if($this->getIDByTokenAndValidate($token) == 'success'){
			if($rol_usuario_id==$this->rol_usuario_id){
				$email =  strtolower($email);
				if($this->emailValidateUpDate($email,$id_usuario)){
					$dbS->squery("	UPDATE
								usuario
							SET
								nombre = '1QQ',
								apellido = '1QQ',
								laboratorio_id = 1QQ,
								nss = 1QQ,
								email = '1QQ',
								fechaDeNac = '1QQ',
								rol_usuario_id = 1QQ
							WHERE
								id_usuario = 1QQ
					 	"
						,array($nombre,$apellido,$laboratorio_id,$nss,$email,$fechaDeNac,$rol_usuario_id_new,$id_usuario),"UPDATE"
			      		);
					if(!$dbS->didQuerydied){
						$id=$dbS->lastInsertedID;
						$arr = array('id_usuario' => $id, 'nombre' => $nombre, 'token' => $token,	'estatus' => '¡Exito!, redireccionando...','error' => 0);
						return json_encode($arr);
					}else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion, verifica tus datos y vuelve a intentarlo','error' => 3);
						return json_encode($arr);
					}
				}
				else{
					$arr = array('estatus'=>'Ese correo ya existe','error' => 4);
					return json_encode($arr);
				}
							
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este usuario no tiene el privilegio correcto','error' => 1);
				return json_encode($arr);
			}
		}
		else{
			$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este token expiro o no existe','error' => 2);
			return json_encode($arr);
		}
		
		
	}


	public function upDateContrasena($token,$rol_usuario_id,$id_usuario,$constrasena){
		global $dbS;
		if($this->getIDByTokenAndValidate($token) == 'success'){
			if($rol_usuario_id==$this->rol_usuario_id){
				$contrasenaValida = hash('sha512', $constrasena);
				$dbS->squery("	UPDATE
							usuario
						SET
							contrasena = '1QQ'
						WHERE
							id_usuario = 1QQ
					 "
					,array($contrasenaValida,$id_usuario),"UPDATE"
			      	);
				if(!$dbS->didQuerydied){
					$arr = array('id_usuario' => $id_usuario,'token' => $token,	'estatus' => '¡Exito!, redireccionando...','error' => 0);
					return json_encode($arr);
				}else{
					$arr = array('id_usuario' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion, verifica tus datos y vuelve a intentarlo','error' => 3);
					return json_encode($arr);
				}
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este usuario no tiene el privilegio correcto','error' => 1);
				return json_encode($arr);
			}
		}
		else{
			$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este token expiro o no existe','error' => 2);
			return json_encode($arr);
		}

	}


	public function deactivate($token,$rol_usuario_id,$id_usuario){
		global $dbS;
		if($this->getIDByTokenAndValidate($token) == 'success'){
			if($rol_usuario_id==$this->rol_usuario_id){
				$dbS->squery("	UPDATE
							usuario
						SET
							active = '1QQ'
						WHERE
							active=1 AND
							id_usuario = 1QQ
					 "
					,array(0,$id_usuario),"UPDATE"
			      	);

				if(!$dbS->didQuerydied){
						$id=$dbS->lastInsertedID;
						$arr = array('id_usuario' => $id, 'nombre' => $nombre, 'token' => $token,	'estatus' => '¡Exito!, redireccionando...','error' => 0);
						return json_encode($arr);
				}else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la desactivacion, verifica tus datos y vuelve a intentarlo','error' => 2);
						return json_encode($arr);
				}
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este usuario no tiene el privilegio correcto','error' => 1);
				return json_encode($arr);
			}
		}
		else{
			$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este token expiro o no existe','error' => 2);
			return json_encode($arr);
		}

	}

	public function activate($token,$rol_usuario_id,$id_usuario){
		global $dbS;
		if($this->getIDByTokenAndValidate($token) == 'success'){
			if($rol_usuario_id==$this->rol_usuario_id){
				$dbS->squery("	UPDATE
							usuario
						SET
							active = '1QQ'
						WHERE
							active=0 AND
							id_usuario = 1QQ
					 "
					,array(1,$id_usuario),"UPDATE"
			      	);

				if(!$dbS->didQuerydied){
						$id=$dbS->lastInsertedID;
						$arr = array('id_usuario' => $id, 'nombre' => $nombre, 'token' => $token,	'estatus' => '¡Exito!, redireccionando...','error' => 0);
						return json_encode($arr);
				}else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la activacion, verifica tus datos y vuelve a intentarlo','error' => 2);
						return json_encode($arr);
				}
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este usuario no tiene el privilegio correcto','error' => 1);
				return json_encode($arr);
			}
		}
		else{
			$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este token expiro o no existe','error' => 2);
			return json_encode($arr);
		}

	}


	
	public function upLoadFoto($token,$rol_usuario_id,$id_usuario,$foto){
		global $dbS;
		if($this->getIDByTokenAndValidate($token) == 'success'){
			if($rol_usuario_id==$this->rol_usuario_id){
				$resultado = $dbS->squery("
						UPDATE
							usuario
						SET
							foto = '1QQ'
						WHERE
							id_usuario = 1QQ
						",
						array($foto,$id_usuario),"UPDATE"

					);
				if(!$dbS->didQuerydied){
						$id=$dbS->lastInsertedID;
						$arr = array('id_usuario' => $id, 'nombre' => $nombre, 'token' => $token,	'estatus' => '¡Exito!, redireccionando...','error' => 0);
						return json_encode($arr);
				}else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en subir la foto, verifica tus datos y vuelve a intentarlo','error' => 2);
						return json_encode($arr);
				}			
			}else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este usuario no tiene el privilegio correcto','error' => 1);
				return json_encode($arr);
			}
		}
		else{
			$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este token expiro o no existe','error' => 2);
			return json_encode($arr);
		}
	}

	public function getByIDAdmin($token,$rol_usuario_id,$id_usuario){
		global $dbS;
		if($this->getIDByTokenAndValidate($token) == 'success'){
			if($rol_usuario_id==$this->rol_usuario_id){
				$query_resultado = $this->getByID($id_usuario);
				if($query_resultado != "empty"){
						$id=$dbS->lastInsertedID;
						$arr = array(	'id_usuario' => $this->id_usuario,
							 			'nombre' => $this->nombre, 
							 			'apellido' => $this->apellido,
							 			'laboratorio_id' => $this->laboratorio_id,
							 			'laboratorio' => $this->laboratorio,
							 			'nss' => $this->nss,
							 			'rol' => $this->rol,
							 			'email' => $this->email, 
							 			'fechaDeNac' => $this->fechaDeNac,

							 			'createdON' => $this->createdON, 
							 			'lastEditedON' => $this->lastEditedON,

							 			'active' => $this->active,

							 			'foto' => $this->foto, 
							 			'rol_usuario_id' => $this->rol_usuario_id, 
							 			'token' => $token,	
							 			'estatus' => 'Exito',
							 			'error' => 0
							 		);
						return json_encode($arr);
				}else{
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la function getUserByID','error' => 3);
						return json_encode($arr);
				}
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este usuario no tiene el privilegio correcto','error' => 1);
				return json_encode($arr);
			}
		}
		else{
			$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este token expiro o no existe','error' => 2);
			return json_encode($arr);
		}
	}


}
?>


