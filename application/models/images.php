<?php
class Images extends CI_Model
{

	function insert($image_name, $image_path)
	{
		$data = array(
			'image_name' => $image_name,
			'image_path' => $image_path,
		);
		$this->db->insert('tb_images', $data);
		$insert_id = $this->db->insert_id();

   return  $insert_id;
	}

	function getAll()
	{
		return $this->db->get('tb_images')->result();
	}

	function getById($id)
	{
		$query = $this->db->get_where('tb_images', array('id' => $id));
		return $query->row();
	}

	function update($id, $image_name, $image_path)
	{
		$data = array(
			'image_name' => $image_name,
			'image_path' => $image_path
		);
		$this->db->where('id', $id);
		$result = $this->db->update('tb_images', $data);
		return $result;
	}
}
