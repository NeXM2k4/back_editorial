<?php

class VolumeController {
    
    // Método para listar todos los tomos de una colección específica
    public function index($collection_id) {
        $volumes = VolumeModel::get_volumes_by_collection($collection_id);
        
        return json_encode([
            "status" => "success",
            "message" => "Lista de tomos obtenida",
            "data" => $volumes
        ]);
    }

    // Método para mostrar los detalles de un tomo específico
    public function view($id) {
        $volume = VolumeModel::get_volume($id);
        
        if ($volume) {
            return json_encode([
                "status" => "success",
                "message" => "Detalles del tomo obtenidos",
                "data" => $volume
            ]);
        } else {
            return json_encode([
                "status" => "error",
                "message" => "Tomo no encontrado"
            ]);
        }
    }

    // Método para crear un nuevo tomo en una colección específica
    public function create($collection_id) {
        // Se espera que los datos lleguen en el cuerpo de la solicitud
        $data = json_decode(file_get_contents("php://input"), true);
        $data['collection_id'] = $collection_id;
        
        $volume = VolumeModel::create_volume($data);
        
        return json_encode([
            "status" => "success",
            "message" => "Tomo creado exitosamente",
            "data" => $volume
        ]);
    }

    // Método para actualizar la información de un tomo específico
    public function edit($id) {
        // Se espera que los datos lleguen en el cuerpo de la solicitud
        $data = json_decode(file_get_contents("php://input"), true);
        
        $volume = VolumeModel::update_volume($id, $data);
        
        if ($volume) {
            return json_encode([
                "status" => "success",
                "message" => "Tomo actualizado exitosamente",
                "data" => $volume
            ]);
        } else {
            return json_encode([
                "status" => "error",
                "message" => "Error al actualizar el tomo"
            ]);
        }
    }

    // Método para eliminar un tomo específico
    public function delete($id) {
        $deleted = VolumeModel::delete_volume($id);
        
        if ($deleted) {
            return json_encode([
                "status" => "success",
                "message" => "Tomo eliminado exitosamente"
            ]);
        } else {
            return json_encode([
                "status" => "error",
                "message" => "Error al eliminar el tomo"
            ]);
        }
    }
}
