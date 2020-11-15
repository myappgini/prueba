<?php

include(dirname(__FILE__) . '/MultipleUpload.php');
$folder = '';
if (isset($_GET['f'])) $folder = $_GET['f'];
$mu = new MultipleUpload();
$mu->folder = $folder;
$mu->process_ajax_upload();
