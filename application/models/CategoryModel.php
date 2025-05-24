<?php

class CategoryModel extends CI_Model
{
	//TODO: Debe de agregarse un tipo de paginacion si es necesario para optimizar
	//Eso puede hacerse usando la libreria 'pagination'
	//$this->load->library('pagination
	public function get_all_categories()
	{
		return array(
			'name' => 'test'
		);
	}

	public function get_category($id)
	{
		return "Categoria con el id: " . $id;
	}

	public function create_category($data)
	{
		return "Categoria creada" . $data;
	}

	public function update_category($id, $data)
	{
		return "Categoria actualizada con id " . $id;
	}

	public function delete_category($id)
	{
		return "Categoria eliminada con id " . $id;
	}

}
