<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prueba extends CI_Controller {
	
	public function __construct() {
        parent::__construct();

        $this->load->database();  
    }
	 
	public function index()
	{
		$result = $this->db->get('autores')->result();
		
		foreach ($result as $row){
			
			echo $row->sNombreAutor;
		}
	}
}
