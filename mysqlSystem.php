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
	private $connection;
	
	/* Variables de utilería */
	private $wc = '/1QQ/';

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
			$q = $this->query;
		echo '<p>-'.$q.'-</p>';
		$this->resultSet = mysqli_query($this->connection,$q);
	}
	
	public function fetch($rS = "eempty"){
		if($rS == "eempty")
			$rS = $this->resultSet;
		if (mysqli_num_rows($rS)!=0) {
			return mysqli_fetch_array($rS);
		}
		else{
			return "empty";
		}
	}
	public function fetchA($rS = "eempty"){
		if($rS == "eempty")
			$rS = $this->resultSet;
		if (mysqli_num_rows($rS)!=0) {
			return mysqli_fetch_array($rS, MYSQLI_ASSOC);
		}
		else{
			return "empty";
		}
	}

	public function fetchAll($rS = "eempty"){
		if($rS == "eempty"){
			$rS = $this->resultSet;
		}
		if (mysqli_num_rows($rS)==0) {
			$rows="empty";
			return $rows;
		}
		else{
			while($row = mysqli_fetch_array($rS)){
				$rows[] = $row;
			}
			return $rows;
		}
	}

	/*
		Funciones que podemos llamar
	*/
	
	public function qarray($q = "eempty", $arr = array()){
		$this->squery($q,$arr);
		return $this->fetch();
	}
	
	public function qarrayA($q = "eempty", $arr = array()){
		$this->squery($q,$arr);
		return $this->fetchA();
	}

	public function qAll($q = "eempty", $arr = array()){
		$this->squery($q,$arr);
		return $this->fetchAll();
	}
	
	public function qvalue($q = "eempty", $arr = array()){
		return $this->qarray($q,$arr)[0];
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
		$str = preg_replace('/</',"&lt;",$str);
		$str = preg_replace('/>/',"&gt;",$str);
		return mysqli_real_escape_string($this->connection,$str);
	}
	
	public function secure_string($str,$arr){
		foreach($arr as $a){
			$aa = $this->secure($a);
			$str = preg_replace($this->wc,$aa,$str,1);
		}
		return $str;
	}
	
	public function squery($q = "eempty", $arr = array()){
		if(count($arr) == 0)
			$this->query($q);
		else
			$this->query($this->secure_string($q,$arr));
	}
}
?>