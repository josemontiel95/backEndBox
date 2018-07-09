<?php
include_once("./../../configSystem.php");

class Usuario{
	
	/* Variables de BD*/
	private $id_usuario;
	private $nombre;
	private $apellido;
	private $email;
	private $fechaDeNac;
	private $foto;
	private $rol_usuario_id;
	private $contrasena;
	
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
		$arr = array('estatus' => 'Exito. Sesion cerrada','error' => 0);
		return json_encode($arr);
	}

	/*
		
	*/
//Devuelve el valor del toke, si esta activo, si esta muerta o si no esta activa
	public function getIDByTokenAndValidate($token){
		global $dbS;


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
	public function getAllUsuarios($token){
		global $dbS;
		if($this->getIDByTokenAndValidate($token)=="success"){
			$arr= $dbS->qAll("
			      SELECT 
			        id_usuario,
			        nombre,
			        apellido,
			        email,
			        fechaDeNac,
			        foto,
			        rol_usuario_id,
			        active
			      FROM 
			        usuario 
			      ",
			      array(),
			      "SELECT"
			      );
			return json_encode($arr);
		}else{
			$arr = array('estatus' => 'Exito. Sesion cerrada','error' => 0);
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
			        email,
			        fechaDeNac,
			        foto,
			        rol_usuario_id,
			        contrasena
			      FROM 
			        usuario 
			      WHERE 
			      	active=1 AND
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
			$this->fechaDeNac= $s['fechaDeNac'];
			$this->foto= $s['foto'];
			$this->rol_usuario_id= $s['rol_usuario_id'];
			$this->contrasena= $s['contrasena'];
			$this->email= $s['email'];
			return "success";
		}

	}

	public function insert($token,$nombre,$apellido,$email,$fechaDeNac,$rol_usuario_id,$constrasena){
		global $dbS;
		if($this->getIDByTokenAndValidate($token) == 'sucess'){
			if($rol_usuario_id==$this->rol_usuario_id){ //No es redundante?
				$contrasenaValida = echo hash('sha512', $constrasena);
				$dbS->squery("
						INSERT INTO
						usuario(nombre,apellido,email,fechaDeNac,rol_usuario_id,contrasena)

						VALUES
						('1QQ','1QQ','1QQ','1QQ',1QQ,'1QQ')
				",array($nombre,$apellido,$email,$fechaDeNac,$rol_usuario_id,$contrasenaValida),"INSERT");
				$arr = array('id_usuario' => $this->id_usuario, 'nombre' => $this->nombre, 'token' => $token,	'estatus' => 'Exito de insercion','error' => 0);
				return json_encode($arr);

			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este usuario no tiene el privilegio correcto','error' => 1);
				return json_encode($arr);
			}
		}
	}


	public function upDate($id_usuario,$token,$nombre,$apellido,$email,$fechaDeNac,$rol_usuario_id,$constrasena){
		global $dbS;
		if($this->getIDByTokenAndValidate($token) == 'sucess'){
			if($rol_usuario_id==$this->rol_usuario_id){
				$dbS->squery("	UPDATE
							usuario
						SET
							nombre = '1QQ',
							apellido = '1QQ',
							email = '1QQ',
							fechaDeNac = '1QQ',
							rol_usuario_id = 1QQ,
							contrasena = '1QQ'
						WHERE
							active=1 AND
							id_usuario = 1QQ
					 "
					,array($nombre,$apellido,$email,$fechaDeNac,$rol_usuario_id,$constrasena),"UPDATE"
			      	);
				$arr = array('id_usuario' => $this->id_usuario, 'nombre' => $this->nombre, 'token' => $token,	'estatus' => 'Exito de insercion','error' => 0);
				return json_encode($arr);
			

			}
		}
		else{
			$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Este usuario no tiene el privilegio correcto','error' => 1);
			return json_encode($arr);
		}
		
	}


	public function deactivate(){

	}
}
?>


