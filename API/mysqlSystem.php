<?php
class MySQLSystem{
	
	/* Variables de BDD */
	private $dbname = "boxLacocs";
	private $dbuser = "lacocsAdmin";
	private $dbpssw = "Julio2018%";
	private $dbsrvr = "160.153.46.5";
	
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
		if (mysqli_connect_errno())
		  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
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
}
?>