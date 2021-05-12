<?php
class ScreenShots extends CI_Model
{

	function insert($image_name, $image_path)
	{
		$data = array(
			'image_name' => $image_name,
			'image_path' => $image_path,
		);
		$this->db->insert('tb_screenshots', $data);
		$insert_id = $this->db->insert_id();

   return  $insert_id;
	}

	function getAll()
	{
		return $this->db->get('tb_screenshots')->result();
	}

	function getById($id)
	{
		$query = $this->db->get_where('tb_screenshots', array('id' => $id));
		return $query->row();
	}

	function update($id, $image_name, $image_path)
	{
		$data = array(
			'image_name' => $image_name,
			'image_path' => $image_path
		);
		$this->db->where('id', $id);
		$result = $this->db->update('tb_screenshots', $data);
		return $result;
	}
}
