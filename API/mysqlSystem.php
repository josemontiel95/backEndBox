<?php
class MySQLSystem{
	
	/* Variables de BDD */
	private $dbname = "lacocs";
	private $dbuser = "lacocsadmin";
	private $dbpssw = "Septiembre2018#";
	private $dbsrvr = "mysqlcluster14.registeredsite.com";
	
	/* Variables de buffer */
	private $resultSet;
	private $query;
	public $lastInsertedID;
	public $lastInsertedLogID;
	public $didQuerydied;
	private $connection;
	
	/* Variables de utilería */
	private $wc = '/1QQ/';
	private $queryType;

	public function MySQLSystem(){
		$this->connection =
		mysqli_connect($this->dbsrvr,$this->dbuser,$this->dbpssw,$this->dbname);

		// Check connection
		if (mysqli_connect_errno()){
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();

		}else{
			$this->setTimeZone();
		}
	}

	public function setTimeZone(){

		$query='SELECT MONTH(CURDATE()) AS month';

		$result = mysqli_query($this->connection,$query);
		if (mysqli_num_rows($result)!=0) { //Aqui solo se tiene un valor
			$values= mysqli_fetch_array($result, MYSQLI_ASSOC); //Obtiene una fila de resultados como un array asociativo, nunerico o ambos.
		}
		if($values['month']>10 || $values['month']<4){
			$query="set time_zone = '-06:00'";
			$result = mysqli_query($this->connection,$query);
		}else{
			$query="set time_zone = '-05:00'";
			$result = mysqli_query($this->connection,$query);
		}
		$queryTypeTemp=$this->queryType;
		$this->queryType="SetTimeZone";
		$this->logQuery('SELECT MONTH(CURDATE()) AS month');
		$this->queryType=$queryTypeTemp;
	}


	/** 
	Instrucciones de consulta
	*/
	

	// Esta funcion es la unica que hace como tal una query hacia la base de datos
	public function query($q = "eempty"){
		if($q == "eempty")
			$q = $this->query; //this->query no esta vacia?
		//echo '<p>-'.$q.'-</p>';
		$this->logQuery($q); //Registra el tipo de query que se hace en la tabla 

		$this->resultSet = mysqli_query($this->connection,$q); //Devuelve el valor de la consulta, false en caso de error
		$this->lastInsertedID=mysqli_insert_id($this->connection);
		if($this->resultSet==false){
			$this->didQuerydied=true;
			$query='
				UPDATE log SET status="FAILED"  WHERE id_log='.$this->lastInsertedLogID
			;//Aqui ingresa la query al registro de querys
			//echo '<p>LOG-'.$query.'-</p>';
			$mailer = new Mailer();
			$mailer->sendMailErrorDB($q,$this->queryType);
			mysqli_query($this->connection,$query);

		}else{
			$this->didQuerydied=false;
			$query='
				UPDATE log SET status="PASSED" WHERE id_log='.$this->lastInsertedLogID
			;//Aqui ingresa la query al registro de querys
			//echo '<p>LOG-'.$query.'-</p>';
			mysqli_query($this->connection,$query);
		}
		
	}
	public function logQuery($q){
		$query='
			INSERT INTO log(query, queryType) VALUES("'.$q.'", "'.$this->queryType.'") 
		';//Aqui ingresa la query al registro de querys
		//echo '<p>LOG-'.$query.'-</p>';
		mysqli_query($this->connection,$query);
		$this->lastInsertedLogID=mysqli_insert_id($this->connection);
	}

	//===========
	public function fetch($rS = "eempty"){
		if($rS == "eempty")
			$rS = $this->resultSet;
		if (mysqli_num_rows($rS)!=0) {
			return mysqli_fetch_array($rS);//Obtiene una fila de resultados como un array asociativo, nunerico o ambos.
		}
		else{
			return "empty";
		}
	}
	public function fetchA($rS = "eempty"){
		if($rS == "eempty")
			$rS = $this->resultSet;
		if (mysqli_num_rows($rS)!=0) { //Aqui solo se tiene un valor
			return mysqli_fetch_array($rS, MYSQLI_ASSOC); //Obtiene una fila de resultados como un array asociativo, nunerico o ambos.
		}
		else{
			return "empty";
		}
	}

	public function fetchAll($rS = "eempty"){
		if($rS == "eempty"){
			$rS = $this->resultSet; //Aqui contiene el valor de la consulta
		}
		if (mysqli_num_rows($rS)==0) {
			$rows="empty";
			return $rows;
		}
		else{
			while($row = mysqli_fetch_array($rS, MYSQLI_ASSOC)){ //Existe un arreglo con varios registros que coinciden con el id que se quiere saber, en un campo seleccionado
				$rows[] = $row;
			}
			return $rows;
		}
	}

	/*
		Funciones que podemos llamar
	*/
	
	public function qarray($q = "eempty", $arr = array(), $queryType="NS"){
		$this->queryType= $queryType;
		$this->squery($q,$arr, $queryType);
		return $this->fetch();
	}
	

	/*
		Devuelve un array Asociatvo con los campos de la tabla usuario
	*/
	public function qarrayA($q = "eempty", $arr = array(), $queryType="NS"){
		$this->queryType= $queryType;
		$this->squery($q,$arr,$queryType);
		return $this->fetchA();
	}

	public function qAll($q = "eempty", $arr = array(), $queryType="NS"){
		$this->queryType= $queryType;
		$this->squery($q,$arr, $queryType);
		return $this->fetchAll(); //Devuelve los valores de la consulta en caso de exito
	}
	
	public function qvalue($q = "eempty", $arr = array(), $queryType="NS"){
		$this->queryType= $queryType;
		return $this->qarray($q,$arr,$queryType)[0];
	}
	
	public function rows($rS = "eempty"){
		if($rS == "eempty")
			$rS = $this->resultSet;
		return mysqli_num_rows($rS);
	}
	/*
	Prevenir inyeccion SQL
	*/
	public function secure($str){
		$str = preg_replace('/</',"&lt;",$str); // Que valores cambia?----Los valores asignados cuando se ingresaron los datos.
		$str = preg_replace('/>/',"&gt;",$str); //
		return mysqli_real_escape_string($this->connection,$str); //Crea una cadena SQL legal que se puede usar en una sentencia SQL
	}
	
	public function secure_string($str,$arr){
		foreach($arr as $a){
			$aa = $this->secure($a);
			$str = preg_replace($this->wc,$aa,$str,1); //Str contiene la cadena para acceder a SQL
		}
		return $str; // Devuelve una sentencia legal para realizar el query
	}
	
	public function squery($q = "eempty", $arr = array(), $queryType="NS"){
		$this->queryType= $queryType;
		if(count($arr) == 0)
			$this->query($q);
		else
			$this->query($this->secure_string($q,$arr));
	}

	public function beginTransaction(){
		$qt=$this->queryType;
		$this->queryType="beginTransaction";
		$this->query('BEGIN');
		$this->queryType=$qt;
	}
	public function commitTransaction(){
		$qt=$this->queryType;
		$this->queryType="commitTransaction";
		$this->query('COMMIT');
		$this->queryType=$qt;
	}
	public function rollbackTransaction(){
		$qt=$this->queryType;
		$this->queryType="rollbackTransaction";
		$this->query('ROLLBACK');
		$this->queryType=$qt;
	}
	/*

						-----------------APORTACIONES BRYAN---------------

	*/

	public function transquery($q = "eempty",$arrThings = array(),$destino,$queryType="NS"){
		$this->beginTransaction();	//SE INSERTA COMO SI SE TRATARA DE LA TERMINAL, EL INICIO DE LA TRANSACCION
		foreach ($arrThings as $a){
				//Revisar que no exista un registro con este par en especial (herramienta-orden de Trabajo) activo.
				$cuantos=$this->qvalue("
					SELECT 
						COUNT(*) 
					FROM 
						herramienta_ordenDeTrabajo 
					WHERE 
						active = 1 AND
						herramienta_id = 1QQ AND 
						ordenDeTrabajo_id = 1QQ
					",
					array($a, $destino),
					"SELECT -- Herra_ordenDeTra :: mysqlSystem :: transquery"
				);
				if($cuantos==0){ // En caso de que ya este relacionada 
					$array_aux = array($a,$destino); //ARRAY AUXILIAR PARA PODER EJECUTAR LA "squery"
					$this->squery($q,$array_aux,$queryType);
					if($this->didQuerydied){//POR CADA ITERACION SE REVISA SI NO HA MUERTO LA QUERY
						break;
					}
				}else{
					$this->rollbackTransaction();
					$this->queryType="rollbackFLAG";
					$this->logQuery("ROLLBACK");
					$this->didQuerydied = true;
					return ($a);
				}
				
		}
		if (!$this->didQuerydied) {
			$this->commitTransaction();
			return (0);
		}
		else{
			$this->rollbackTransaction(); //EJECUTAMOS EL ROLL BACK PARA VOLVER AL ESTADO DE LA TABLA ANTES DE REALIZAR CAMBIOS
			$this->squery($q,$array_aux,$queryType);	
			$this->queryType="rollbackFLAG";
			$this->logQuery("ROLLBACK");
			$this->didQuerydied = true;
			return ($a);
		}
	}

	public function transqueryTecnicos($q = "eempty",$arrThings = array(),$destino,$queryType="NS"){
		$this->beginTransaction();	//SE INSERTA COMO SI SE TRATARA DE LA TERMINAL, EL INICIO DE LA TRANSACCION
		foreach ($arrThings as $a){
				//Revisar que no exista un registro con este par en especial (herramienta-orden de Trabajo) activo.
				$cuantos=$this->qvalue("
					SELECT 
						COUNT(*) 
					FROM 
						tecnicos_ordenDeTrabajo 
					WHERE 
						active = 1 AND
						tecnico_id = 1QQ AND 
						ordenDeTrabajo_id = 1QQ
					",
					array($a, $destino),
					"SELECT -- Tecnicos:ODT :: mysqlSystem :: transqueryTecnicos"
				);
				if($cuantos==0){ // En caso de que ya este relacionada 
					$array_aux = array($a,$destino); //ARRAY AUXILIAR PARA PODER EJECUTAR LA "squery"
					$this->squery($q,$array_aux,$queryType);
					if($this->didQuerydied){//POR CADA ITERACION SE REVISA SI NO HA MUERTO LA QUERY
						break;
					}
				}else{
					$this->rollbackTransaction();
					$this->queryType="rollbackFLAG";
					$this->logQuery("ROLLBACK");
					$this->didQuerydied = true;
					return ($a);
				}
				
		}
		if (!$this->didQuerydied) {
			$this->commitTransaction();
			return (0);
		}
		else{
			$this->rollbackTransaction(); //EJECUTAMOS EL ROLL BACK PARA VOLVER AL ESTADO DE LA TABLA ANTES DE REALIZAR CAMBIOS
			$this->squery($q,$array_aux,$queryType);	
			$this->queryType="rollbackFLAG";
			$this->logQuery("ROLLBACK");
			$this->didQuerydied = true;
			return ($a);
		}
	}

}
?>