<?php
// 
// Author: Alejandro Landini
// previewImages.php 7/4/18
// update 10/9/20
//get functions
include('functions-ajax.php');

//load handlebars php library
require 'handlebars-php/src/Handlebars/Autoloader.php';
Handlebars\Autoloader::register();

use Handlebars\Handlebars;
use Handlebars\Loader\FilesystemLoader;

$boostrap = "ver3";

$currDir = dirname(__FILE__);

$title = Request::val('title');
$id = Request::val('id');
$tn = Request::val('tn');
$fn = Request::val('fn');
$key = Request::val('key');
$cmd = Request::val('cmd');
$where = Request::val('where');

# Set the partials files
$partialsDir = __DIR__ . "/templates";
$partialsLoader = new FilesystemLoader(
    $partialsDir,
    [
        "extension" => "hbs"
    ]
);

# We'll use $handlebars throughout this the examples, assuming the will be all set this way
$handlebars = new Handlebars([
    "loader" => $partialsLoader,
    "partials_loader" => $partialsLoader
]);

registerHelpers($handlebars);

if ($cmd !== '') {
    if (!$where){
        $where = whereConstruct($tn,$id);
    }
    $j = get_json($tn, $fn, $where);
    $j = json_decode($j, true);
    $j['id'] = $id;

    switch ($cmd) {
        case 'full':
            # Will render the model to the template
            $html =  $handlebars->render("dv_bs3", $j);
            echo $html;
            
        break;
        case 'form':
            
            # Will render the model to the template
            $html =  $handlebars->render("gallery_bs3", $j);
            echo $html;
            
            break;
        default:
            # code...
            break;
    }
}



// /srv/www/htdocs/prueba/hooks/multipleUpload/UploadsView.php:58:
// array (size=13)
//   'response-type' => string 'success' (length=7)
//   'defaultImage' => boolean false
//   'isRenamed' => boolean false
//   'fileName' => string 'file-sample_150kB(1).pdf' (length=24)
//   'extension' => string 'pdf' (length=3)
//   'name' => string 'file-sample_150kB(1)' (length=20)
//   'type' => string 'doc' (length=3)
//   'hd_image' => boolean false
//   'folder' => string '/srv/www/htdocs/prueba/images/products/1/' (length=41)
//   'folder_base' => string 'images/products/1' (length=17)
//   'size' => string 'false' (length=5)
//   'userUpload' => string 'admin' (length=5)
//   'aproveUpload' => boolean true

function registerHelpers(&$handlebars){
    $handlebars->addHelper(
        "filemtime",
        function ($template, $context, $args, $source) {
            $data = ($context->get($args));
            $file = $data['folder'] . $data['name'] . '_th.jpg';
            if (file_exists($file)) {
                return filemtime($file);
            }
            return 0;
        }
    );
    $handlebars->addHelper(
        "compare",
        function ($template, $context, $args, $source) {
            $args = explode(" ", $args);
            // var_dump($args);
            // var_dump($context);
            // var_dump($source);
            // var_dump($template);
            switch ($args[1]) {
                case 'eq':
                    if ($args[0] == $args[2]) return $context;
                    break;

                default:
                    # code...
                    break;
            }
        }
    );

}