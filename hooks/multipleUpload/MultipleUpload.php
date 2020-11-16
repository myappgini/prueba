<?php
class MultipleUpload
{
	protected $title, $name, $logo, $errors, $extensions;

	public function __construct($config = array())
	{
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		$this->errors = array();
		$this->extensions_img  = '|jpg|jpeg|gif|png|tif';
		$this->extensions_mov  = '|mov|avi|swf|asf|wmv|mpg|mpeg|mp4|flv';
		$this->extensions_docs = '|txt|doc|docx|pdf|zip';
		$this->extensions_audio = '|wav|mp3';
		$this->type = 'img';
		$this->original = '';
		$this->minImageSize = 1200;
	}

	public function process_ajax_upload()
	{
		$resources_dir = dirname(__FILE__);
		$base_dir = realpath("{$resources_dir}/../..");
		include_once("$base_dir/lib.php");

		$extensiones_docs = $this->extensions_docs;
		$extensiones_mov = $this->extensions_mov;
		$extensiones_img = $this->extensions_img;
		$extensiones_audio = $this->extensions_audio;
		$this->size = 'false';
		$extensiones = $extensiones_img . $extensiones_docs . $extensiones_mov . $extensiones_audio;

		$mi = getMemberInfo();

		$folder_base = $base_dir . "/" . $this->folder;
		$original = $folder_base . "/" . $this->original;

		try {

			//if file exceeded the filesize, no file will be sent
			if (!isset($_FILES['uploadedFile'])) {
				//throw new RuntimeException("No file sent you must upload a file not greater than $maxFileSize");
			}

			$file = pathinfo($_FILES['uploadedFile']['name']);
			$ext = strtolower($file['extension']); // get the extension of the file	
			$filename = $file['filename'];

			// Undefined | Multiple Files | $_FILES Corruption Attack
			// If this request falls under any of them, treat it invalid.

			// Check $_FILES['uploadedFile']['error'] value.
			switch ($_FILES['uploadedFile']['error']) {
				case UPLOAD_ERR_OK:
					break;
				case UPLOAD_ERR_NO_FILE:
					throw new RuntimeException('No file sent.');
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					throw new RuntimeException('You must upload a file not greater than $maxFileSize"');
				default:
					throw new RuntimeException('Unknown errors.');
			}

			//Check extention
			if (strpos($extensiones_docs, $ext)) {
				//it is a doc
				$this->type = 'doc';
			}

			if (strpos($extensiones_mov, $ext)) {
				//it is movie
				$this->type = 'mov';
			}
			if (strpos($extensiones_audio, $ext)) {
				//it is movie
				$this->type = 'audio';
			}

			$extensiones = explode('|', $extensiones);
			if (!in_array(strtolower($ext), $extensiones)) {
				throw new RuntimeException('You must upload a (' . implode("|", $extensiones) . ') file');
			}

			// $_FILES['uploadedFile']['name'] validation
			if (!preg_match('/^[A-Za-z0-9-_]/', $_FILES['uploadedFile']['name'])) {
				throw new RuntimeException('File was not uploaded. The file can only contain "A-Z", "a-z", "0-9", "_" and "-".');
			}

			// check folder if exist and create
			if (!is_dir($original)) {
				if (!mkdir($original, 0777, true)) {
					throw new RuntimeException('File was not uploaded. The folder can\'t create, check permissions. ');
				}
			}
		} catch (RuntimeException $e) {
			$a = dirname(__FILE__);
			header('Content-Type: application/json');
			header($_SERVER['SERVER_PROTOCOL'] . 'error; Content-Type: application/json', true, 400);
			echo json_encode(array(
				"error" => $e->getMessage() . $a
			));
		}

		//check existing projects' names 
		$currentFiles = scandir($original);
		natsort($currentFiles);
		$currentFiles = array_reverse($currentFiles);

		$renameFlag = false;

		foreach ($currentFiles as $file_name_dir) {
			if (preg_match('/^' . $filename . '(-[0-9]+)?\.' . $ext . '$/i', $file_name_dir)) {
				$matches = array();
				if (!strcmp($_FILES['uploadedFile']['name'], $file_name_dir)) {
					$newName = $filename . "-" . "1" . ".$ext";
					$new_name = $filename . "-" . "1";
					$renameFlag = true;
				} else {
					//increment number at the end of the name ( sorted desc, first one is the largest number)
					preg_match('/(-[0-9]+)\.' . $ext . '$/i', $file_name_dir, $matches);
					$number = preg_replace("/[^0-9]/", '', $matches[0]);
					$newName = $filename . "-" . (((int)$number) + 1) . ".$ext";
					$new_name = $filename . "-" . (((int)$number) + 1);
					$renameFlag = true;
					break;
				}
			} 
		}

		if ($renameFlag) {
			$oldName = $filename;
			$filename = $new_name;
		}

		if (!move_uploaded_file($_FILES['uploadedFile']['tmp_name'], sprintf($original . '/%s', ($renameFlag ? $newName : $_FILES['uploadedFile']['name'])))) {
			throw new RuntimeException('Failed to move uploaded file.');
		} else {
			$exit = false;
			if ($this->type === 'img' || strtolower($ext) === 'pdf' || $this->type === 'mov') {
				//add thumbsnail
				include '_resampledIMG.php';
				$exit = make_thumb($renameFlag ? $newName : $file['basename'], $filename, $ext, $this);
				//agregar a la tabla de files
			}
			//file uploaded successfully	
			$tn = Request::val('tn');
			$fn = Request::val('fn');
			$id = Request::val('id');
			
			header('Content-Type: application/json; charset=utf-8');
			// * defaultImage => se cambia a true si es el primer elemento en la funcion add_json
			$data = array(
				"defaultImage"  => FALSE, 
				"isRenamed"     => $renameFlag,
				"fileName"      => $renameFlag ? $newName : $file['basename'],
				"extension"     => $ext,
				"name"          => $filename,
				"type"          => $this->type,
				"hd_image"      => $exit,
				"folder"        => $original,
				"folder_base"   => $this->folder,
				"size"          => $this->size,
				"userUpload"    => $mi['username'],
				"dateUpload"	=> date("d.m.y"),
				"timeUpload"	=> date("H:i:s"),
				"oldName"		=> $oldName ? $oldName : "",
				"tn"			=> $tn,
				"fn"			=> $fn,
				"id"			=> $id
			);
			
			// * guardar registro..
			if (!function_exists('add_json')) {
				include("functions-ajax.php");
			}
			
			$res = add_json($tn, $id, $fn, $data);
			$data['success']=$res;
			echo json_encode($data);
		}
		return;
	}
}
