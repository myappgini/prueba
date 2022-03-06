<?php
class MultipleUpload
{
    protected $errors;

    public $extensions_img = ['jpg', 'jpeg', 'gif', 'png', 'tif', 'tiff'];
    public $extensions_mov = ['mov', 'avi', 'swf', 'asf', 'wmv', 'mpg', 'mpeg', 'mp4', 'flv'];
    public $extensions_docs = ['txt', 'doc', 'docx', 'pdf', 'zip'];
    public $extensions_audio = ['wav', 'mp3'];
    public $folder_base = 'images';

    private $current_file_dir = false;
    private $root_dir = false;
    private $type = 'img';
    private $uploaded_file = [];
    private $upload_dir = false;

    public $info = [
        'tn' => false,
        'fn' => 'uploads',
        'id' => false,
        'ix' => false,
        'lastix' => false,
    ];


    public function __construct($config = [])
    {
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        $this->current_file_dir = dirname(__FILE__);
        $this->root_dir = dirname(dirname($this->current_file_dir));
        $this->errors = [];
        $this->extensions = array_merge(
            $this->extensions_docs,
            $this->extensions_img,
            $this->extensions_mov,
            $this->extensions_audio
        );
        $this->minImageSize = 1200;
        $this->size = 'false';
    }

    public function process_upload()
    {
        $this->upload_dir = $this->root_dir . '/' . $this->folder_base . '/';

        try {
            //if file exceeded the filesize, no file will be sent
            if (!isset($_FILES['uploadedFile'])) {
                throw new RuntimeException("No file sent you must upload a file whit autorized size");
            }

            $this->uploaded_file = pathinfo($_FILES['uploadedFile']['name']);

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
                    throw new RuntimeException(
                        'You must upload a file not greater than $maxFileSize"'
                    );
                default:
                    throw new RuntimeException('Unknown errors.');
            }

            //check autorized extension files
            if (!in_array(strtolower($this->uploaded_file['extension']), $this->extensions)) {
                throw new RuntimeException(
                    'You must upload a (' . implode(",", $this->extensions) . ') file'
                );
            }

            // $_FILES['uploadedFile']['name'] validation
            if (
                !preg_match('/^[A-Za-z0-9-_]/', $_FILES['uploadedFile']['name'])
            ) {
                throw new RuntimeException(
                    'File was not uploaded. The file can only contain "A-Z", "a-z", "0-9", "_" and "-".'
                );
            }

            // check folder if exist and create
            if (!is_dir($this->upload_dir)) {
                if (!mkdir($this->upload_dir, 0777, true)) {
                    throw new RuntimeException(
                        'File was not uploaded. The folder can\'t create, check permissions. :' . $this->upload_dir
                    );
                }
            }
        } catch (RuntimeException $e) {
            header('Content-Type: application/json');
            header(
                $_SERVER['SERVER_PROTOCOL'] .
                    'error; Content-Type: application/json',
                true,
                400
            );
            echo json_encode(['error' => $e->getMessage()]);
            die;
        }

        $this->set_type();

        $renameFlag = $this->check_exist_file();

        if (!move_uploaded_file(
            $_FILES['uploadedFile']['tmp_name'],
            sprintf(
                $this->upload_dir . '/%s', $this->uploaded_file['basename']
            )
        )) {
            throw new RuntimeException('Failed to move uploaded file.');
            die;
            ////////////////////////////////////////
        } else {
            include 'json_class.php';
            $json = new ProcessJson;
            $json->info = $this->info;

            $exit = false;
            if ($this->type === 'img' || $this->type === 'mov') {
                include '_resampledIMG.php';
                $exit = make_thumb(
                    $this->uploaded_file['basename'],
                    $this->uploaded_file['filename'],
                    $this->uploaded_file['extension'],
                    $this->upload_dir,
                    $this->type
                );
            }
            //file uploaded successfully

            $data = [
                'defaultImage' => is_null($json->get_array()) ? "true" : "false",
                'isRenamed' => $renameFlag,
                'fileName' => $this->uploaded_file['basename'],
                'extension' => $this->uploaded_file['extension'],
                'name' => $this->uploaded_file['filename'],
                'type' => $this->type,
                'folder' => $this->upload_dir,
                'folder_base' => $this->folder_base,
                'size' => $this->size,
                'userUpload' => $this->user_name(),
                'dateUpload' => date('d.m.y'),
                'timeUpload' => date('H:i:s'),
                'oldName' => $this->oldName,
                'title' => $this->uploaded_file['filename'],
                'thumbnail' => $exit,
                'pdfPage' => 1,
            ];

            $res = $json->add_data($data);
            $data['success'] = $res;
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        return;
    }
    private function set_type()
    {
        //Check extension
        in_array($this->uploaded_file['extension'], $this->extensions_docs) && $this->type = 'doc';
        in_array($this->uploaded_file['extension'], $this->extensions_mov) && $this->type = 'mov';
        in_array($this->uploaded_file['extension'], $this->extensions_audio) && $this->type = 'audio';
    }
    private function user_name()
    {
        $mi = getMemberInfo();
        return $mi['username'];
    }
    private function check_exist_file()//and rename
    {
        //check existing names
        $currentFiles = scandir($this->upload_dir);
        natsort($currentFiles);
        $currentFiles = array_reverse($currentFiles);

        $renameFlag = false;

        foreach ($currentFiles as $file_name_dir) {
            if (
                preg_match(
                    '/^' . $this->uploaded_file['filename'] . '(-[0-9]+)?\.' . $this->uploaded_file['extension'] . '$/i',
                    $file_name_dir
                )
            ) {
                $matches = [];
                $this->oldName = $file_name_dir;
                $renameFlag = true;
                if (!strcmp($_FILES['uploadedFile']['name'], $file_name_dir)) {
                    // la primera vez
                    $this->uploaded_file['basename'] = $this->uploaded_file['filename'] . '-' . '1' . "." . $this->uploaded_file['extension'];
                    $this->uploaded_file['filename']  = $this->uploaded_file['filename'] . '-' . '1';
                } else {
                    //increment number at the end of the name 
                    //( sorted desc, first one is the largest number)
                    preg_match(
                        '/(-[0-9]+)\.' . $this->uploaded_file['extension'] . '$/i',
                        $file_name_dir,
                        $matches
                    );
                    $number = preg_replace('/[^0-9]/', '', $matches[0]);
                    $this->uploaded_file['basename'] = $this->uploaded_file['filename'] . '-' . (((int) $number) + 1) . "." . $this->uploaded_file['extension'];
                    $this->uploaded_file['filename'] = $this->uploaded_file['filename'] . '-' . (((int) $number) + 1);
                    break;
                }
            }
        }

        return $renameFlag;
    }
}

$base_dir = realpath(dirname(__FILE__)."/../..");
if (!function_exists('makeSafe')) {
    include "$base_dir/lib.php";
}
if (isset($_GET['tn'])) {
    $mu = new MultipleUpload();
    $mu->info['tn'] = Request::val('tn');
    $mu->info['fn'] = Request::val('fn');
    $mu->info['id'] = Request::val('id');

    //change folder base
    $mu->folder_base = "{$mu->folder_base}/{$mu->info['tn']}/{$mu->info['id']}";
    //
    $mu->process_upload();
}
