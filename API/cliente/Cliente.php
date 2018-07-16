<?php 
class Cliente(){
	private $id_cliente;
	private $rfc;
	private $razonSocial;
	private $email;
	private $telefono;
	private $nombreContacto;
	private $telefonoDeContacto;

	/* Variables de utilería */
	private $wc = '/1QQ/';

	public function insert($rfc,$razonSocial,$email,$telefono,$nombreContacto,$telefonoDeContacto){
		global $dbS;
		$dbS->squery("
						INSERT INTO
						cliente(rfc,razonSocial,email,telefono,nombreContacto,telefonoDeContacto)

						VALUES
						('1QQ','1QQ','1QQ','1QQ',1QQ,'1QQ')
				",array($rfc,$razonSocial,$email,$telefono,$nombreContacto,$telefonoDeContacto),"INSERT");
		$arr = array('id_cliente' => 'No disponible, esto NO es un error', 'razonSocial' => $razonSocial, 'estatus' => 'Exito en insercion', 'error' => 0);
		return json_encode($arr);
	}

	public function upDate($id_cliente,$rfc,$razonSocial,$email,$telefono,$nombreContacto,$telefonoDeContacto){
		global $dbS;
		$dbS->squery("	UPDATE
							cliente
						SET
							rfc ='1QQ',
							razonSocial = '1QQ', 
							email = '1QQ',
							telefono ='1QQ',
							nombreContacto = '1QQ', 
							telefonoDeContacto = '1QQ',
						WHERE
							active=1 AND
							id_cliente = 1QQ
					 "
					,array($rfc,$razonSocial,$email,$telefono,$nombreContacto,$telefonoDeContacto,$id_cliente),"UPDATE"
			      	);
		$arr = array('id_cliente' => $id_cliente, 'razonSocial' => $razonSocial,'estatus' => 'Exito de actualizacion','error' => 0);
		return json_encode($arr);
	}




}
?>