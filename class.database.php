<?php

class Database {
	
	private $_connection;
	private static $_instance; 
	private $_host     = "localhost";
	private $_username = "root";
	private $_password = "root";
	private $_database = "rest_api";

	private function __clone() {}

	private function __construct() {
		$this->_connection = new mysqli($this->_host, $this->_username, $this->_password, $this->_database);

		if ($this->_connection->connect_error)
		    die("Connection failed: " . $this->_connection->connect_error);
	}

	public static function getInstance() {
		if(!self::$_instance) self::$_instance = new self();
		return self::$_instance;
	}
	
	public function getConnection() {
		return $this->_connection;
	}
}