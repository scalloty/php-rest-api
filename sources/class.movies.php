<?php

class Movies extends Actions {

	private $_get_propers   = array('id','title','duration');
	private $_input_propers = array('title','duration');
	private $_required_fields = array('title','duration');

	public function __construct() {
		parent::__construct(get_class($this),$this->_get_propers,$this->_input_propers);
	}

	public function inputValidate($post) {
		foreach ($this->_input_propers as $p) {
			if(in_array($p,$this->_required_fields) && (!isset($post[$p]) || empty($post[$p]))) {
				$this->response['status'] = 0;
				$this->response['result'] = "{$p} is required.";
				return FALSE;
			}
		}

		return TRUE;
	}
}