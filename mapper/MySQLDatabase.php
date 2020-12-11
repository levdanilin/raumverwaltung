<?php
define ('DB_SERVER', 'localhost');
define ('DB_USER', 'lev');
define ('DB_PASSWORD', 'rk542.10');
define ('DB_DATABASE', 'raumverwaltungneu');

class MySQLDatabase {
	private static $instance;

	public static function getInstance(){
		if(!self::$instance){
			try{
				self::$instance  = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
				self::$instance->set_charset("utf8");
			}
			catch(Exception $e){
				echo self::$instance->connect_error;
			}
		}
		return self::$instance;
	}
}
?>
