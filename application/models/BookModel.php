<?php

class BookModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url'); // Cargar el helper de URL
    }

    //Obtener TODOS los libros **posiblemente cambie en el futuro
    public function get_books_rows()
    {
        return $this->db->count_all("books");
    }

    function internalBooksList($limit, $offset)
    {
        $query = $this->db->get("books", $limit, $offset);
        return $query->result();
    }

    public function get_books($page = 1)
    {
        $total_rows = $this->get_books_rows();
        $limit = 10;

        $totalPage = ceil($total_rows / $limit);

        $offset = ($page - 1) * $limit;

        $books = $this->internalBooksList($limit, $offset);

        return [
            'books' => $books,
            'page' => $page,
            'limit' => $limit,
            'totalPages' => $totalPage
        ];
    }
}
