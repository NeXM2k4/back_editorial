<?php

class FileModel extends CI_Model{
	
	public function __construct (){
		
		parent::__construct();
		$this->load->database();
	}
	
	

    public function get_file($volume_id){

		
		$this->db->where('nIdPublicacion', $volume_id);
		$result = $this->db->get('publicaciones_encabezado')->row();
	
		return $result->sURLRecurso;
    }

    public function save_file($data){
		
		$this->db->$data["path"]
    }

    public function delete_file($id){


        
    }

}