<?php
class Conversation extends CI_Model
{

	function insert($time)
	{
		$data = array(
			'date' => $time,
		);
		$this->db->insert('tb_conversation', $data);
		return $this->db->insert_id();
	}

	function getAll()
	{
		return $this->db->get('tb_conversation')->result();
	}

	function getByDate($date)
	{
		$query = $this->db->get_where('tb_conversation', array('date' => $date));
		return $query->row()->id;
	}

	function update($id, $which, $value)
	{
		$this->db->where('id', $id);
		$result = $this->db->update($which, $value);
		return $result;
	}

	function deleteAll()
	{
		return $this->db->empty_table('tb_conversation');
	}
}
