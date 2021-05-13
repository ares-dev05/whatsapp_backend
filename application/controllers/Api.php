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
		$this->load->model('images');
		$this->load->model('conversation');
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
		$config['max_size']     	= '255240412';

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

	function image_insert()
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
			$id = $this->images->insert($result['file_name'], $result['file_path']);

			$result = array(
				'file_name' => $data['upload_data']['file_name'],
				'file_path' => "/assets/images/" . $data['upload_data']['file_name'],
				'file_id' => $id
			);
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}
	}

	function image_getAll()
	{
		$result = $this->images->getAll();
		if ($result) {
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		} else {
			$this->output->set_content_type('application/json')->set_output("database error!");
		}
	}

	function image_getById()
	{
		if ($this->input->post('id') == NULL) {
			$this->output->set_content_type('application/json')->set_output("unkown error!");
		} else {
			$result = $this->images->getById($this->input->post('id'));
			if ($result) {
				$this->output->set_content_type('application/json')->set_output(json_encode($result));
			} else {
				$this->output->set_content_type('application/json')->set_output("database error!");
			}
		}
	}

	function conversation_insert()
	{
		if ($this->input->post('time') == NULL) {
			$this->output->set_content_type('application/json')->set_output("error!");
		} else {
			$result = $this->conversation->insert($this->input->post('time'));
			if ($result) {
				$this->output->set_content_type('application/json')->set_output($result);
			} else {
				$this->output->set_content_type('application/json')->set_output("error!");
			}
		}
		
	}


	function conversation_getByTime()
	{
		if ($this->input->post('time') == NULL) {
			$this->output->set_content_type('application/json')->set_output("error!");
		} else {
			$con_id = $this->conversation->getByDate($this->input->post('time'));
			$result = $this->messages->deleteByConId($con_id);
			if ($result) {
				$this->output->set_content_type('application/json')->set_output(json_encode($con_id));
			} else {
				$this->output->set_content_type('application/json')->set_output("database error!");
			}
		}
	}

	function conversation_getAll()
	{
		$result = $this->conversation->getAll();
		if ($result) {
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		} else {
			$this->output->set_content_type('application/json')->set_output("database error!");
		}
	}


	function message_insert()
	{
		if ($this->input->post('sender') == NULL || $this->input->post('state') == NULL || $this->input->post('time') == NULL || $this->input->post('con_id') == NULL) {
			$this->output->set_content_type('application/json')->set_output("unkown error!");
		} else {
			$result = $this->messages->insert($this->input->post('images'), $this->input->post('sender'), $this->input->post('state'), $this->input->post('time'), $this->input->post('message'), $this->input->post('con_id') );
			if ($result) {
				$this->output->set_content_type('application/json')->set_output(json_encode($result));
			} else {
				$this->output->set_content_type('application/json')->set_output("database error!");
			}
		}
	}

	function message_deleteByConId()
	{
		if ($this->input->post('con_id') == NULL) {
			$this->output->set_content_type('application/json')->set_output("unkown error!");
		} else {
			$result = $this->messages->deleteByConId($this->input->post('con_id'));
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

	function message_getByDate()
	{
		if ($this->input->post('date') == NULL) {
			$this->output->set_content_type('application/json')->set_output("unkown error!");
		} else {
			$con_id = $this->conversation->getByDate($this->input->post('date'));
			
			$result = $this->messages->getByConId($con_id);
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
