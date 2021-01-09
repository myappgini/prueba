<?php
// 
// Author: Alejandro Landini
// from previewImages.php 7/4/18
// update 10/9/20

if (!function_exists('getMemberInfo')) {
    die('{ "error": "Invalid way to access." }');
}
require 'handlebars-php/src/Handlebars/Autoloader.php';
//load handlebars php library
Handlebars\Autoloader::register();

use Handlebars\Handlebars;
use Handlebars\Loader\FilesystemLoader;

//$boostrap = "bs3";

$currDir = dirname(__FILE__);

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
                case '!eq':
                    if ($data !== $compare) return $template->render($context);
                default:
                    break;
            }
            return false; // $data.' ::: '.$when.':::'.$comapare.':::'.count($m);
        }
    );

    $handlebars->addHelper(
        "admin",
        function ($template, $context, $args, $source) {
            $mi = getMemberInfo();
            return $mi['admin'] ? $template->render($context) : false;
        }
    );

    return $handlebars;
}
