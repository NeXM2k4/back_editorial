<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Collections extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cargar el modelo
        $this->load->model('CollectionModel');
    }

    // Método index - Obtiene todas las colecciones
    public function index() {
        $collections = $this->CollectionModel->get_all_collections();
        echo json_encode([
            "status" => "success",
            "message" => "Lista de colecciones obtenida exitosamente",
            "data" => $collections
        ]);
    }

    // Método view - Obtiene los detalles de una colección por ID
    public function view($id) {
        $collection = $this->CollectionModel->get_collection($id);
        if ($collection) {
            echo json_encode([
                "status" => "success",
                "message" => "Detalles de la colección obtenidos",
                "data" => $collection
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Colección no encontrada"
            ]);
        }
    }

    // Método create - Crea una nueva colección
    public function create() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (isset($data['name']) && isset($data['description'])) {
            $new_collection_id = $this->CollectionModel->create_collection($data);
            if ($new_collection_id) {
                $new_collection = $this->CollectionModel->get_collection($new_collection_id);
                echo json_encode([
                    "status" => "success",
                    "message" => "Colección creada exitosamente",
                    "data" => $new_collection
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Error al crear la colección"
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Datos insuficientes para crear la colección"
            ]);
        }
    }

    // Método edit - Actualiza una colección por ID
    public function edit($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['name']) && isset($data['description'])) {
            $updated = $this->CollectionModel->update_collection($id, $data);
            if ($updated) {
                $updated_collection = $this->CollectionModel->get_collection($id);
                echo json_encode([
                    "status" => "success",
                    "message" => "Colección actualizada exitosamente",
                    "data" => $updated_collection
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Error al actualizar la colección"
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Datos insuficientes para actualizar la colección"
            ]);
        }
    }

    // Método delete - Elimina una colección por ID
    public function delete($id) {
        $deleted = $this->CollectionModel->delete_collection($id);
        if ($deleted) {
            echo json_encode([
                "status" => "success",
                "message" => "Colección eliminada exitosamente"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error al eliminar la colección"
            ]);
        }
    }
}

