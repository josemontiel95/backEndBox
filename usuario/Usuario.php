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
	private $contraseña;
	
	/* Variables de utilería */
	private $wc = '/1QQ/';
	public function Usuario(){
	}
	/*
	public function Usuario($nombre,$apellido,$email,$fechaDeNac,$foto,$rol_usuario_id,$contraseña){
		$this->nombre= $nombre;
		$this->apellido= $apellido;
		$this->email= $email;
		$this->fechaDeNac= $fechaDeNac;
		$this->foto= $foto;
		$this->rol_usuario_id= $rol_usuario_id;
		$this->contraseña= $contraseña;
	}
	*/
	public function login($email, $contraseña){
		$respuesta=$this->getByEmail($email);
		if($respuesta=="success"){
			$contraseñaSHA= hash('sha512', $contraseña);
			if($this->contraseña==$contraseñaSHA){
				$arr = array('id_usuario' => $this->id_usuario, 'nombre' => $this->nombre, 'token' => setToken());
				return json_encode($arr);
			}else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'error por constrasela','error' => 0);
				return json_encode($arr);
			}
		}else{
			$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'error por usuario','error' => -1);
				return json_encode($arr);
		}
	}

	public function setToken(){
		$token= getToken();
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
			      array($this->id_usuario,$token)
			      );
		return token();
	}

	public function getToken(){
		$date=date("Y/m/d");
		$preToken=$date.$id_usuario;
		return encurl($preToken);
	}

	public function getByEmail($email){
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
			        contraseña
			      FROM 
			        usuario 
			      WHERE 
			        email = '1QQ'
			      ",
			      array($email)
			      );
		if($s=="empty"){
			echo "hola";
			return "empty";
		}
		else{
			$this->id_usuario= $s['id_usuario'];
			$this->nombre= $s['nombre'];
			$this->apellido=$s['apellido'];
			$this->fechaDeNac= $s['fechaDeNac'];
			$this->foto= $s['foto'];
			$this->rol_usuario_id= $s['rol_usuario_id'];
			$this->contraseña= $s['contraseña'];
			$this->email= $email;
			echo $s['id_usuario'];
			return "success";
		}

	}
}
?>