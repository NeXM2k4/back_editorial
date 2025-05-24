<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Definiendo las propiedades del modelo para tener el autocompletado
 * @property AuthModel $AuthModel
 */

class AuthController extends RestAPIController
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('ApiAuth');
		$this->load->model('AuthModel');
	}

	public function register()
	{
		$username = $this->input->post('sUsername');
		$password = $this->input->post('sPassword');

		$this->form_validation->set_rules('sUsername', 'Username', 'required');
		$this->form_validation->set_rules('sPassword', 'Password', 'required');

		if ($this->form_validation->run()) {
			$data = array(
				'username' => $username,
				'password' => sha1($password)
			);
			//Por el momento no agregaremos a la base de datos
			//$this->AuthModel->registerUser($data);
			$response = array(
				'ok' => true,
				'message' => 'Usuario creado correctamente',
				'data' => []
			);
			$this->response($response, 201);
		} else {
			$response = array(
				'ok' => false,
				'message' => 'Usuario o contraseña incorrectos',
				'data' => []
			);
			$this->response($response, 401);
		}
		exit;
	}

	public function login() {

	}
}
