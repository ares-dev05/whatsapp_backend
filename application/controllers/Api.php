<?php
class Api extends CI_Controller
{
	function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == "OPTIONS") {
			die();
		}
		parent::__construct();

		$this->load->model('screenshots');
		$this->load->model('messages');
	}

	function index()
	{
		$this->load->view('v_upload');
	}

	function _config()
	{
		$config['upload_path']		= "./assets/images";
		$config['allowed_types']	= 'gif|jpg|jpeg|png';
		$config['encrypt_name'] 	= TRUE;
		$config['max_size']     	= '100000';

		$this->load->library('upload', $config);
	}

	function screen_insert()
	{
		$this->_config();
		if ($this->upload->do_upload("file") == FALSE) {
			$this->output->set_content_type('application/json')->set_output("file error!");
		} else {
			$data = array('upload_data' => $this->upload->data());
			$result = array(
				'file_name' => $data['upload_data']['file_name'],
				'file_path' => "/assets/images/" . $data['upload_data']['file_name']
			);
			$id = $this->screenshots->insert($result['file_name'], $result['file_path']);
			
			$result = array(
				'file_name' => $data['upload_data']['file_name'],
				'file_path' => "/assets/images/" . $data['upload_data']['file_name'],
				'file_id' => $id
			);
			$this->output->set_content_type('application/json')->set_output(json_encode($result));

		}
	}

	function screen_getAll()
	{
		$result = $this->screenshots->getAll();
		if ($result) {
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		} else {
			$this->output->set_content_type('application/json')->set_output("database error!");
		}
	}

	function screen_getById()
	{
		if ($this->input->post('id') == NULL) {
			$this->output->set_content_type('application/json')->set_output("unkown error!");
		} else {
			$result = $this->screenshots->getById($this->input->post('id'));
			if ($result) {
				$this->output->set_content_type('application/json')->set_output(json_encode($result));
			} else {
				$this->output->set_content_type('application/json')->set_output("database error!");
			}
		}
	}

	function screen_upload()
	{
		$this->_config();
		if ($this->upload->do_upload("file") == FALSE) {
			$this->output->set_content_type('application/json')->set_output("file error!");
		} else {
			$data = array('upload_data' => $this->upload->data());
			$result = array(
				'file_name' => $data['upload_data']['file_name'],
				'file_path' => "/assets/images/" . $data['upload_data']['file_name']
			);
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	function message_insert()
	{
		if ($this->input->post('images') == NULL || $this->input->post('sender') == NULL || $this->input->post('state') == NULL || $this->input->post('time') == NULL) {
			$this->output->set_content_type('application/json')->set_output("unkown error!");
		} else {
			$result = $this->messages->insert($this->input->post('images'), $this->input->post('sender'), $this->input->post('state'), $this->input->post('time'));
			if ($result) {
				$this->output->set_content_type('application/json')->set_output(json_encode($result));
			} else {
				$this->output->set_content_type('application/json')->set_output("database error!");
			}
		}
	}

	function message_getAll()
	{
		$result = $this->messages->getAll();
		if ($result) {
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		} else {
			$this->output->set_content_type('application/json')->set_output("database error!");
		}
	}

	function message_getById()
	{
		if ($this->input->post('id') == NULL) {
			$this->output->set_content_type('application/json')->set_output("unkown error!");
		} else {
			$result = $this->messages->getById($this->input->post('id'));
			if ($result) {
				$this->output->set_content_type('application/json')->set_output(json_encode($result));
			} else {
				$this->output->set_content_type('application/json')->set_output("database error!");
			}
		}
	}

	function message_deleteAll()
	{
		$result = $this->messages->deleteAll();
		if ($result) {
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		} else {
			$this->output->set_content_type('application/json')->set_output("database error!");
		}
	}
}
