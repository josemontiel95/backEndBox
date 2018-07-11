<?php 

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

	public function insert($obra,$prefijo,$fechaDeCreacion,$descripcion,$cliente_id,$concretera,$tipo){
		global $dbS;
		$dbS->squery("
						INSERT INTO
						obra(obra,prefijo,fechaDeCreacion,descripcion,cliente_id,concretera,tipo)

						VALUES
						('1QQ','1QQ','1QQ','1QQ',1QQ,'1QQ','1QQ')
				",array($obra,$prefijo,$fechaDeCreacion,$descripcion,$cliente_id,$concretera,$tipo),"INSERT");
		$arr = array('id_obra' => 'No disponible, esto NO es un error', 'obra' => $obra, 'estatus' => 'Exito en insercion', 'error' => 0);
		return json_encode($arr);
	}

	public function upDate($id_obra,$obra,$prefijo,$fechaDeCreacion,$descripcion,$cliente_id,$concretera,$tipo){
		global $dbS;
		$dbS->squery("	UPDATE
							obra
						SET
							obra ='1QQ',
							prefijo = '1QQ', 
							fechaDeCreacion = '1QQ',
							descripcion ='1QQ',
							cliente_id = '1QQ', 
							concretera = '1QQ',
							tipo = '1QQ'
						WHERE
							active=1 AND
							id_obra = 1QQ
					 "
					,array($obra,$prefijo,$fechaDeCreacion,$descripcion,$cliente_id,$concretera,$tipo,$id_obra),"UPDATE"
			      	);
		$arr = array('id_obra' => $id_obra, 'obra' => $obra,'estatus' => 'Exito de actualizacion','error' => 0);
		return json_encode($arr);

	}








}

?>