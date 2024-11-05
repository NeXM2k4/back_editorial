<?php

class FileController {

    // Método para listar todos los archivos de un tomo específico
    public function index($volume_id) {
        $files = FileModel::get_files_by_volume($volume_id);

        return json_encode([
            "status" => "success",
            "message" => "Lista de archivos obtenida",
            "data" => $files
        ]);
    }

    // Método para mostrar los detalles de un archivo específico
    public function view($id) {
        $file = FileModel::get_file($id);

        if ($file) {
            return json_encode([
                "status" => "success",
                "message" => "Detalles del archivo obtenidos",
                "data" => $file
            ]);
        } else {
            return json_encode([
                "status" => "error",
                "message" => "Archivo no encontrado"
            ]);
        }
    }

    // Método para subir un archivo PDF y asociarlo a un tomo específico
    public function upload($volume_id) {
        // Verificar que se haya enviado un archivo
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            return json_encode([
                "status" => "error",
                "message" => "No se ha subido ningún archivo"
            ]);
        }

        // Validar que el archivo sea un PDF
        $fileInfo = $_FILES['file'];
        $fileType = mime_content_type($fileInfo['tmp_name']);
        if ($fileType !== 'application/pdf') {
            return json_encode([
                "status" => "error",
                "message" => "El archivo debe ser un PDF"
            ]);
        }

        // Limitar el tamaño del archivo (ejemplo: 5MB)
        $maxSize = 5 * 1024 * 1024;
        if ($fileInfo['size'] > $maxSize) {
            return json_encode([
                "status" => "error",
                "message" => "El archivo supera el tamaño máximo permitido de 5MB"
            ]);
        }

        // Verificar que el volume_id exista en la base de datos
        $volume = VolumeModel::get_volume($volume_id);
        if (!$volume) {
            return json_encode([
                "status" => "error",
                "message" => "El tomo especificado no existe"
            ]);
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
                "filename" => $fileInfo['name'],
                "path" => $filePath,
                "volume_id" => $volume_id
            ];
            $file = FileModel::save_file($data);

            return json_encode([
                "status" => "success",
                "message" => "Archivo subido exitosamente",
                "data" => $file
            ]);
        } else {
            return json_encode([
                "status" => "error",
                "message" => "Error al subir el archivo"
            ]);
        }
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
