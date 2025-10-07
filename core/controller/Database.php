<?php
class Database {
	public static $db;
	public static $con;
	function __construct(){
                $this->user="kwgqxiie_elconitoinvuser";$this->pass="G9ZI*-*dYg2520";$this->host="75.102.22.115";$this->ddbb="kwgqxiie_elconitoinv";
	}

	function connect(){
		$con = new mysqli($this->host,$this->user,$this->pass,$this->ddbb);
		$con->query("set sql_mode='';");
		return $con;
	}

	public static function getCon(){
		if(self::$con==null && self::$db==null){
			self::$db = new Database();
			self::$con = self::$db->connect();
		}
		return self::$con;
	}
	
}
?>
