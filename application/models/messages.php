<?php
class Messages extends CI_Model
{

	function insert($images, $sender, $state, $time, $message, $con_id)
	{
		$data = array(
			'images' => $images,
			'sender' => $sender,
			'state' => $state,
			'time' => $time,
			'message' => $message,
			'con_id' => $con_id
		);
		$result = $this->db->insert('tb_messages', $data);
		return $result;
	}

	function deleteByConId($con_id)
	{
		$data = array(
			'con_id' => $con_id,
		);
	
		$result = $this->db->delete('tb_messages', $data);

		return $result;
	}

	function getAll()
	{
		return $this->db->get('tb_messages')->result();
	}

	function getById($id)
	{
		$query = $this->db->get_where('tb_messages', array('id' => $id));
		return $query->row();
	}

	function getByConId($con_id)
	{
		$query = $this->db->get_where('tb_messages', array('con_id' => $con_id));
		return $query->result();
	}

	function deleteAll()
	{
		return $this->db->empty_table('tb_messages');
	}
}
