<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Definiendo las propiedades del modelo para tener el autocompletado
 * @property VolumeModel $VolumeModel
 * @property FileModel $FileModel
 */
class FileController extends RestAPIController
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('FileModel');
		$this->load->model('VolumeModel');
	}

	// Metodo para devolver el archivo de un tomo específico
	public function index($volume_id)
	{
		$file = $this->FileModel->get_file($volume_id);

		if ($file == null) $response = array(
			"ok" => false,
			"message" => "Tomo no encontrado"
		);

		else $response = array(
			"ok" => true,
			"message" => "URL del archivo",
			"data" => $file
		);

		$this->response($response, $response['ok'] ? 200 : 400);
	}

	// Metodo para subir un archivo PDF y asociarlo a un tomo específico
	public function upload_pdf() {
		$config['upload_path']          = './uploads/';
		$config['allowed_types']        = 'pdf';
		$config['max_size']             = 5000;
		$config['file_name']            = uniqid() . '.pdf';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('archivo_pdf'))
		{
			$error = array('error' => $this->upload->display_errors());
			$this->response($error, 400);
		}
		else
		{
			$data = $this->upload->data();
			$this->response([
				"ok" => true,
				"message" => "Archivo subido exitosamente",
				"data" => $data['file_name']
			], 200);
		}
	}

//	public function upload($volume_id)
//	{
//		// Verificar que se haya enviado un archivo
//		if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
//			$this->response([
//				"ok" => false,
//				"message" => "No se ha subido ningún archivo"], 400);
//		}
//
//		// Validar que el archivo sea un PDF
//		$fileInfo = $_FILES['file'];
//		$fileType = mime_content_type($fileInfo['tmp_name']);
//		if (!$volume_id || $fileType !== 'application/pdf') {
//			$this->response([
//				"ok" => false,
//				"message" => "El archivo debe ser un PDF"
//			], 400);
//		}
//
//		// Limitar el tamaño del archivo (ejemplo: 5MB)
//		$maxSize = 5 * 1024 * 1024;
//		if ($fileInfo['size'] > $maxSize) {
//			$this->response([
//				"ok" => false,
//				"message" => "El archivo supera el tamaño máximo permitido de 5MB"
//			], 400);
//		}
//
//		#Verificar que el volume_id exista en la base de datos
//		$volume = $this->VolumeModel->get_volume($volume_id);
//		if (!$volume) {
//			$this->response([
//				"ok" => false,
//				"message" => "El tomo especificado no existe"
//			], 400);
//		}
//
//		// Definir la ruta de almacenamiento
//		$uploadDir = "/uploads/volumen_{$volume_id}/";
//		if (!is_dir($uploadDir)) {
//			mkdir($uploadDir, 0777, true);
//		}
//		$filePath = $uploadDir . basename($fileInfo['name']);
//
//		// Mover el archivo al directorio de destino
//		if (move_uploaded_file($fileInfo['tmp_name'], $filePath)) {
//			$data = [
//				"path" => $filePath,
//				"volume_id" => $volume_id
//			];
//			$file = $this->FileModel->save_file($data);
//			$this->response([
//				"ok" => true,
//				"message" => "Archivo subido exitosamente",
//				"data" => $file
//			], 201);
//		} else {
//			$this->response([
//				"ok" => false,
//				"message" => "Error al subir el archivo"
//			], 400);
//		}
//	}

	// Metodo para eliminar un archivo específico
	public function delete($id)
	{
		// Obtener la información del archivo
		$file = $this->FileModel->get_file($id);

		if ($file) {
			// Eliminar el archivo físico del servidor
			if (file_exists($file['path'])) {
				unlink($file['path']);
			}
			// Eliminar el registro de la base de datos
			$this->FileModel->delete_file($id);
			$this->response([
				"ok" => true,
				"message" => "Archivo eliminado exitosamente"
			], 200);
		} else {
			$this->response([
				"ok" => false,
				"message" => "Archivo no encontrado"
			], 400);
		}
	}
}
