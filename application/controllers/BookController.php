<?php

/**
 * Definiendo las propiedades del modelo para tener el autocompletado
 * @property BookModel $BookModel
 */
class BookController extends CI_RestAPIController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('BookModel');
	}

	public function index()
	{
		$page = isset($_GET['page']) ? $_GET['page'] : 1;

		if (!is_numeric($page) || $page < 1) {
			$page = 1;
		} else {
			$page = (int)$page;
		}

		$books = $this->BookModel->get_books($page);
		$this->response([
			"ok" => true,
			"message" => "Lista de tomos obtenida",
			"data" => $books
		], 200);
	}
}
