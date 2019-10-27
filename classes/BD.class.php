<?php
include_once ("");
class BD{
	private static $conn;

	public function __construct(){}

	public static function conn(){
		if(is_null(self::$conn)){
			self::$conn = new PDO('pgsql:host='.HOST.';dbname='.DB.'', ''.USER.'', ''.PASS.'');
		}

        return self::$conn;
	}
}
?>