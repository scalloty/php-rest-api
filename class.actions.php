<?php

class Actions {
	
	private $_table;
	private $_db;
	private $_get_columns;
	private $_set_columns;
	public $response = array('status'=>1);

	public function __construct($table,$get_columns,$set_columns) {
		$this->_db          = Database::getInstance()->getConnection();
		$this->_table       = strtolower($table);
		$this->_get_columns = implode(',',$get_columns);
		$this->_set_columns = $set_columns;
	}

	public function get($id=0) {
		$query = "SELECT {$this->_get_columns} FROM ".$this->_table;
		if($id) $query.= " WHERE id = ".$this->_db->real_escape_string($id);
		$result = $this->_db->query($query);
		while($row = $result->fetch_assoc()) $this->response['result'][] = $row;
	}

	public function post($post) {
		$sets = '';
		foreach ($this->_set_columns as $column)
			$sets .= $column." = '".$this->_db->real_escape_string($post[$column])."',";

		$sets = trim($sets,',');

		$query = "INSERT INTO {$this->_table} SET {$sets}";

		if($this->_db->query($query)) $this->response['result'] = "{$this->_table} added successfully.";
		else {
			$this->response['status'] = 0;
			$this->response['result'] = "{$this->_table} addition failed.";
		}
	}

	public function put($id=0,$post) {
		$sets = '';
		foreach ($this->_set_columns as $column)
			$sets .= $column." = '".$this->_db->real_escape_string($post[$column])."',";

		$sets = trim($sets,',');

		$query = "UPDATE {$this->_table} SET {$sets} WHERE id = ".$this->_db->real_escape_string($id);

		if($this->_db->query($query)) $this->response['result'] = "{$this->_table} update successfully.";
		else {
			$this->response['status'] = 0;
			$this->response['result'] = "{$this->_table} update failed.";
		}
	}

	public function delete($id=0) {
		$query = "DELETE FROM {$this->_table} WHERE id = ".$this->_db->real_escape_string($id);

		if($this->_db->query($query)) $this->response['result'] = "{$this->_table} deleted successfully.";
		else {
			$this->response['status'] = 0;
			$this->response['result'] = "{$this->_table} deleted failed.";
		}
	}
}