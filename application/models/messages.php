<?php
class Messages extends CI_Model
{

	function insert($images, $sender, $state, $time)
	{
		$data = array(
			'images' => $images,
			'sender' => $sender,
			'state' => $state,
			'time' => $time
		);
		$result = $this->db->insert('tb_messages', $data);
		return $result;
	}

	function getData()
	{
		return $this->db->get('tb_messages')->result();
	}

	function getById($id)
	{
		$query = $this->db->get_where('tb_messages', array('id' => $id));
		return $query->row();
	}

	function update($id, $which, $value)
	{
		$this->db->where('id', $id);
		$result = $this->db->update($which, $value);
		return $result;
	}

	function deleteAll()
	{
		return $this->db->empty_table('tb_messages');
	}
}
