<?php

class AuthModel extends CI_Model
{
	function registerUser($data) {
		return $this->db->insert('usuarios', $data);
	}

	//Devolver los valores de la row en caso de encontrar un usuario con $data
	function checkLogin($data) {
		$this->db->where($data);
		$query = $this->db->get('usuarios');
		return $query->num_rows() == 1 ? $query->row() : false;
	}

}
