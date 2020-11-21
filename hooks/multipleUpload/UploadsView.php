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

$handlebars = registerHelpers($handlebars);

if ($cmd !== '') {
    if (!$where) {
        $where = whereConstruct($tn, $id);
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

function registerHelpers($handlebars)
{

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
        "when",
        function ($template, $context, $args, $source) {

            $m = explode(" ", $args);
            $keyname = $m[0];
            $when = $m[1];
            $compare = $m[2];
            $data = $context->get($keyname);

            switch ($when) {
                case 'eq':
                    if ($data == $compare) return $template->render($context);
                    break;

                default:
                    break;
            }
            return false; // $data.' ::: '.$when.':::'.$comapare.':::'.count($m);
        }
    );

    return $handlebars;
}
//SELECT json_extract(uploads,'$.images[0].defaultImage') from products where id = 2
//update products uploads set uploads=json_set(uploads,'$.images[0].defaultImage',false) where id = 2