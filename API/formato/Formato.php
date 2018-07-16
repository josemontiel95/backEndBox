<?php 
class formato(){
	private $id_formato;
	private $formato;
	private $titulo;
	private $noCamposHeader;
	private $noCamposTecnico;
	private $noCamposMuestras;
	private $noCamposFooter;
	private $noFirmas;


	public function insert($formato,$titulo,$noCamposHeader,$noCamposTecnico,$noCamposMuestras,$noCamposFooter,$noFirmas){
		global $dbS;
		$dbS->squery("
						INSERT INTO
						formato(formato,titulo,noCamposHeader,noCamposTecnico,noCamposMuestras,noCamposFooter,noFirmas)

						VALUES
						('1QQ','1QQ','1QQ','1QQ',1QQ,'1QQ','1QQ')
				",array($formato,$titulo,$noCamposHeader,$noCamposTecnico,$noCamposMuestras,$noCamposFooter,$noFirmas),"INSERT");
		$arr = array('id_formato' => 'No disponible, esto NO es un error', 'formato' => $formato, 'estatus' => 'Exito en insercion', 'error' => 0);
		return json_encode($arr);

	}


	public function upDate($id_formato,$formato,$titulo,$noCamposHeader,$noCamposTecnico,$noCamposMuestras,$noCamposFooter,$noFirmas){
		global $dbS;
		$dbS->squery("	UPDATE
							formato
						SET
							formato ='1QQ',
							titulo = '1QQ', 
							noCamposHeader = '1QQ',
							noCamposTecnico ='1QQ',
							noCamposMuestras = '1QQ', 
							noCamposFooter = '1QQ',
							noFirmas = '1QQ'
						WHERE
							active=1 AND
							id_formato = 1QQ
					 "
					,array($formato,$titulo,$noCamposHeader,$noCamposTecnico,$noCamposMuestras,$noCamposFooter,$noFirmas,$id_formato),"UPDATE"
			      	);
		$arr = array('id_formato' => $id_formato, 'formato' => $formato,'estatus' => 'Exito de actualizacion','error' => 0);
		return json_encode($arr);
	}


}


?>