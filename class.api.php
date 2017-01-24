<?php 

class Api {
	
	private $_req_method;
	private $_source;
	private $_id;
	private $_response = array('status'=>1);

	public function __construct() {
		$this->_req_method = $_SERVER["REQUEST_METHOD"];
		$this->setParams($_SERVER["REQUEST_URI"]);
	}

	public function run() {
		require_once('sources/class.'.$this->_source.'.php');
		$_source = new $this->{'_source'};

		switch($this->_req_method) {
		    case 'GET':
				$_source->get($this->_id);
				$this->_response = $_source->response;
				$this->sendResponse();
		        break;
		    case 'POST':
		    	if($_source->inputValidate($_POST)) $_source->post($_POST);
		    	$this->_response = $_source->response;
				$this->sendResponse();
		        break;
		    case 'PUT':
		        parse_str(file_get_contents("php://input"),$post);
		        if($_source->inputValidate($post)) $_source->put($this->_id,$post);
		    	$this->_response = $_source->response;
				$this->sendResponse();
		        break;
		    case 'DELETE':
		    	$_source->delete($this->_id);
		    	$this->_response = $_source->response;
				$this->sendResponse();
		        break;
		    default:
		        header("HTTP/1.0 405 Method Not Allowed");
		        break;
		}
	}

	private function setParams($ruri) {
		$params = explode('/', trim($ruri,'/'));
		if(count($params) == 3) {
			if(file_exists('sources/class.'.$params[1].'.php')) $this->_source = $params[1];
			else {
				$this->_response['status'] = 0;
				$this->_response['result'] = "Incorrect url path. Example: /api/news/7.";
				$this->sendResponse();
			}
			if(is_numeric($params[2])) $this->_id = $params[2];
			else {
				$this->_response['status'] = 0;
				$this->_response['result'] = "Please enter a number for id. Example: /api/news/7.";
				$this->sendResponse();
			}
		} elseif (count($params) == 2) {
			if(file_exists('sources/class.'.$params[1].'.php')) $this->_source = $params[1];
			else {
				$this->_response['status'] = 0;
				$this->_response['result'] = "Incorrect url path. Example: /api/news/7.";
				$this->sendResponse();
			}
		} else {
			$this->_response['status'] = 0;
			$this->_response['result'] = "Please enter the correct url path. Example: /api/news/7.";
			$this->sendResponse();
		}
	}

	private function sendResponse() {
		header('Content-Type: application/json');
		echo json_encode($this->_response);
		exit;
	}
}