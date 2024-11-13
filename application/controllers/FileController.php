<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FileController extends CI_Controller{

    public function __construct(){

        parent::__construct();

        $this->load->model('FileModel');
		//$this->load->model('PublicacionesModel');
    }

    // Método para devolver el archivo de un tomo específico
    public function index($volume_id) {
        $file = $this->FileModel->get_file($volume_id);
	
		
		if ($file == null) $response = json_encode([
            "status" => "failed",
            "message" => "Tomo no encontrado"
        ]);

        else $response =  json_encode([
            "status" => "success",
            "message" => "URL del archivo",
            "data" => $file
        ]);
		
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($response));
    }

    // Método para subir un archivo PDF y asociarlo a un tomo específico
    public function upload($volume_id) {
        // Verificar que se haya enviado un archivo
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $this->output
			->set_content_type('application/json')
			->set_output(json_encode([
                "status" => "error",
                "message" => "No se ha subido ningún archivo"
            ]));
			
			return;
        }

        // Validar que el archivo sea un PDF
        $fileInfo = $_FILES['file'];
        $fileType = mime_content_type($fileInfo['tmp_name']);
        if (!$volume) {
			
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode([
                "status" => "error",
                "message" => "El archivo debe ser un PDF"
            ]));
			
			return;
        }

        // Limitar el tamaño del archivo (ejemplo: 5MB)
        $maxSize = 5 * 1024 * 1024;
        if ($fileInfo['size'] > $maxSize) {
            $this->output
			->set_content_type('application/json')
			->set_output(json_encode([
                "status" => "error",
                "message" => "El archivo supera el tamaño máximo permitido de 5MB"
            ]));
        }

        #Verificar que el volume_id exista en la base de datos
        $volume = $this->PublicacionesModel->get_volume($volume_id);
        if (!$volume) {
			
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode([
                "status" => "error",
                "message" => "El tomo especificado no existe"
            ]));
			
			return;
        }

        // Definir la ruta de almacenamiento
        $uploadDir = "/uploads/volumen_{$volume_id}/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $filePath = $uploadDir . basename($fileInfo['name']);

        // Mover el archivo al directorio de destino
        if (move_uploaded_file($fileInfo['tmp_name'], $filePath)) {
            // Guardar la información del archivo en la base de datos
            $data = [
                "path" => $filePath,
                "volume_id" => $volume_id
            ];
            $file = $this->FileModel->save_file($data);
			
			$response = [
                "status" => "success",
                "message" => "Archivo subido exitosamente",
                "data" => $file
            ];
        } else {
			$response = [
                "status" => "error",
                "message" => "Error al subir el archivo"
            ];
        }
		
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($response));
	}

    // Método para eliminar un archivo específico
    public function delete($id) {
        // Obtener la información del archivo
        $file = FileModel::get_file($id);

        if ($file) {
            // Eliminar el archivo físico del servidor
            if (file_exists($file['path'])) {
                unlink($file['path']);
            }

            // Eliminar el registro de la base de datos
            FileModel::delete_file($id);

            return json_encode([
                "status" => "success",
                "message" => "Archivo eliminado exitosamente"
            ]);
        } else {
            return json_encode([
                "status" => "error",
                "message" => "Archivo no encontrado"
            ]);
        }
    }
}
