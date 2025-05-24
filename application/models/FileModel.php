<?php

class FileModel extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_file($volume_id)
	{
		$this->db->where('nIdPublicacion', $volume_id);
		$result = $this->db->get('publicaciones_encabezado')->row();

		if ($result == null) return null;
		return $result->sURLRecurso;
	}

	public function save_file($data)
	{

		$url = array(
			'$sURLRecurso' => $data["path"]
		);

		$this->db->where('id', $data["volume_id"]);
		$this->db->update("publicaciones_encabezado", $url);

	}

	public function delete_file($id)
	{

	}

}
