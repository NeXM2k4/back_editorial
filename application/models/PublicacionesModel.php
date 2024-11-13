<?php

class PublicacionesModel extends CI_Model{
	
	public function __constructor(){
		
		parent::__construct();
		$this->load->database();
	}

    public function get_volumes_by_collection($collection_id){

        
    }

    public function get_volume($id){

		$this->db->where('id',$id);
		
		$response = $this->db->query("publicaciones_encabezado");
		
		if ($response == null) return false;
		else return true;
    }

    public function create_volume($data){



    }

    public function update_volume($id, $data){



    }

    public function delete_volume($id){


        
    }

}