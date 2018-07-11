<?php 

class Herramienta(){
	private $id_herramienta;
	private $herramienta_tipo_id;
	private $fechaDeCompra;
	private $condicion;

	/* Variables de utilería */
	private $wc = '/1QQ/';


	public function insert($herramienta_tipo_id,$fechaDeCompra,$condicion){
		global $dbS;
		//Como mostrar el tipo de herramienta en el arreglo?
		$dbS->squery("
						INSERT INTO
						herramientas(herramienta_tipo_id,fechaDeCompra,condicion)

						VALUES
						('1QQ','1QQ','1QQ')
				",array($herramienta_tipo_id,$fechaDeCompra,$condicion),"INSERT");
		$arr = array('id_herramienta' => 'No disponible, esto NO es un error', 'herramienta_tipo_id' => $herramienta_tipo_id, 'estatus' => 'Exito en insercion', 'error' => 0);
		return json_encode($arr);
	}

	public function upDate($id_herramienta,$herramienta_tipo_id,$fechaDeCompra,$condicion){
		global $dbS;
		$dbS->squery("	UPDATE
							herramientas
						SET
							herramienta_tipo_id ='1QQ',
							fechaDeCompra = '1QQ', 
							condicion = '1QQ'
						WHERE
							active=1 AND
							id_herramienta = 1QQ
					 "
					,array($herramienta_tipo_id,$fechaDeCompra,$condicion,$id_herramienta),"UPDATE"
			      	);
		$arr = array('id_herramienta' => $id_herramienta, 'herramienta_tipo_id' => $herramienta_tipo_id,'estatus' => 'Exito de actualizacion','error' => 0);
		return json_encode($arr);
	}

	public function deactivate($id_herramienta){
		global $dbS;
		$dbS->squery("	UPDATE
							herramientas
						SET
							active = 1QQ
						WHERE
							active=1 AND
							id_herramienta = 1QQ
					 "
					,array(0,$id_herramienta),"UPDATE"
			      	);
		//PENDIENTE por la herramienta_tipo_id para poderla imprimir tengo que cargar las variables de la base de datos?
		$arr = array('id_herramienta' => $id_herramienta, 'herramienta_tipo_id' => $herramienta_tipo_id,'estatus' => 'Herramienta se desactivo','error' => 0);
		return json_encode($arr);
	}







}





?>