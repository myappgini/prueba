<?php
// 
// Author: Alejandro Landini
// previewImages.php 7/4/18
// update 10/9/20
//get functions
include ('functions-ajax.php');

//load handlebars php library
require 'handlebars-php/src/Handlebars/Autoloader.php';
Handlebars\Autoloader::register();

use Handlebars\Handlebars;
use Handlebars\Loader\FilesystemLoader;

$boostrap = "ver3";


$cmd        = isset($_POST['cmd'])    ? $_POST['cmd']    : '';

$source     = isset($_POST['source']) ? $_POST['source'] : '';
$json       = isset($_POST['json'])   ? $_POST['json']   : [];
$indice     = isset($_POST['indice']) ? $_POST['indice'] : ''; //id
$title      = isset($_POST['title'])  ? $_POST['title']  : '';
$current    = isset($_POST['current']) ? $_POST['current'] : '';

$invalid_characters = array("$", "%", "#", "<", ">", "|", "\"", "\n");
$title      = makeSafe_preview(str_replace($invalid_characters, "", $title));

$name       = isset($_POST['name'])   ? $_POST['name']   : '';
$largo      = isset($_POST['largo'])  ? $_POST['largo']  : '';
$tableName  = isset($_POST['tableName'])  ? $_POST['tableName']  : '';

$currDir = dirname(__FILE__);











