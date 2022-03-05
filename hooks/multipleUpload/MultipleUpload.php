<?php
class MultipleUpload
{
    protected $errors;

    public $extensions_img = ['jpg', 'jpeg', 'gif', 'png', 'tif', 'tiff'];
    public $extensions_mov = ['mov', 'avi', 'swf', 'asf', 'wmv', 'mpg', 'mpeg', 'mp4', 'flv'];
    public $extensions_docs = ['txt', 'doc', 'docx', 'pdf', 'zip'];
    public $extensions_audio = ['wav', 'mp3'];


    public function __construct($config = [])
    {
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        $this->errors = [];
        $this->extensions = array_merge(
            $this->extensions_docs,
            $this->extensions_img,
            $this->extensions_mov,
            $this->extensions_audio
        );
        $this->type = 'img';
        $this->minImageSize = 1200;
        $this->size = 'false';
        $this->tn = false;
        $this->fn = 'uploads';
        $this->id;
        $this->folder_base = 'images';
    }

    public function process_ajax_upload()
    {
        $resources_dir = dirname(__FILE__);
        $base_dir = realpath("$resources_dir/../..");

        $original = $base_dir . '/' . $this->folder . '/';

        try {
            //if file exceeded the filesize, no file will be sent
            if (!isset($_FILES['uploadedFile'])) {
                //throw new RuntimeException("No file sent you must upload a file not greater than $maxFileSize");
            }

            $file = pathinfo($_FILES['uploadedFile']['name']);
            $ext = $file['extension']; // get the extension of the file
            $filename = $file['filename'];

            $this->check_extension($ext);

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

            if (!in_array(strtolower($ext) , $this->extensions)) {
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
            if (!is_dir($original)) {
                if (!mkdir($original, 0777, true)) {
                    throw new RuntimeException(
                        'File was not uploaded. The folder can\'t create, check permissions. '
                    );
                }
            }
            
        } catch (RuntimeException $e) {
            $a = dirname(__FILE__);
            header('Content-Type: application/json');
            header(
                $_SERVER['SERVER_PROTOCOL'] .
                    'error; Content-Type: application/json',
                true,
                400
            );
            echo json_encode([
                'error' => $e->getMessage() . $a,
            ]);
            die;
        }

        //check existing projects' names
        $currentFiles = scandir($original);
        natsort($currentFiles);
        $currentFiles = array_reverse($currentFiles);

        $renameFlag = false;

        foreach ($currentFiles as $file_name_dir) {
            if (
                preg_match(
                    '/^' . $filename . '(-[0-9]+)?\.' . $ext . '$/i',
                    $file_name_dir
                )
            ) {
                $matches = [];
                if (!strcmp($_FILES['uploadedFile']['name'], $file_name_dir)) {
                    $newName = $filename . '-' . '1' . ".$ext";
                    $new_name = $filename . '-' . '1';
                    $renameFlag = true;
                } else {
                    //increment number at the end of the name ( sorted desc, first one is the largest number)
                    preg_match(
                        '/(-[0-9]+)\.' . $ext . '$/i',
                        $file_name_dir,
                        $matches
                    );
                    $number = preg_replace('/[^0-9]/', '', $matches[0]);
                    $newName =
                        $filename . '-' . (((int) $number) + 1) . ".$ext";
                    $new_name = $filename . '-' . (((int) $number) + 1);
                    $renameFlag = true;
                    break;
                }
            }
        }

        if ($renameFlag) {
            $oldName = $filename;
            $filename = $new_name;
        }

        if (!move_uploaded_file(
            $_FILES['uploadedFile']['tmp_name'],
            sprintf(
                $original . '/%s',
                $renameFlag ? $newName : $_FILES['uploadedFile']['name']
            )
        )) {
            throw new RuntimeException('Failed to move uploaded file.');
        } else {
            include 'json_class.php';
            $json = new ProcessJson;
            $json->info = ['tn' => $this->tn, 'id' => $this->id, 'fn' => $this->fn];

            $exit = false;
            if ($this->type === 'img' || $this->type === 'mov') {
                include '_resampledIMG.php';
                $exit = make_thumb(
                    $renameFlag ? $newName : $file['basename'],
                    $filename,
                    $ext,
                    $this
                );
            }
            //file uploaded successfully

            $data = [
                'defaultImage' => is_null($json->get_array()) ? "true" : "false",
                'isRenamed' => $renameFlag,
                'fileName' => $renameFlag ? $newName : $file['basename'],
                'extension' => $ext,
                'name' => $filename,
                'type' => $this->type,
                'folder' => $original,
                'folder_base' => $this->folder,
                'size' => $this->size,
                'userUpload' => $this->user_name(),
                'dateUpload' => date('d.m.y'),
                'timeUpload' => date('H:i:s'),
                'oldName' => $oldName ? $oldName : '',
                'title' => $filename,
                'thumbnail' => $exit,
                'pdfPage' => 1,
            ];

            $res = $json->add_data( $data);
            $data['success'] = $res;
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        return;
    }
    private function check_extension($ext)
    {
        //Check extension
        in_array($ext, $this->extensions_docs) && $this->type = 'doc';
        in_array($ext, $this->extensions_mov) && $this->type = 'mov';
        in_array($ext, $this->extensions_audio) && $this->type = 'audio';
    }
    private function user_name()
    {
        $mi = getMemberInfo();
        return $mi['username'];
    }
}

$currDir = dirname(__FILE__);
$base_dir = realpath("{$currDir}/../..");
if (!function_exists('makeSafe')) {
    include "$base_dir/lib.php";
}
if (isset($_GET['tn'])) {
    $mu = new MultipleUpload();
    $mu->tn = Request::val('tn');
    $mu->fn = Request::val('fn');
    $mu->id = Request::val('id');
    //define folder
    //$mu->folder = Request::val('f');
    $mu->folder = "{$mu->folder_base}/{$mu->tn}/{$mu->id}";
    //
    $mu->process_ajax_upload();
}
