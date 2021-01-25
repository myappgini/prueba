<?php
if (!function_exists('getMemberInfo')) {
    include '../../lib.php';
}
include 'handlebars.php';

$cmd = Request::val('cmd', false);

header('Content-Type: application/json; charset=utf-8');

if ($cmd) {
    switch ($cmd) {
        case 'get-todo':
            $html = $handlebars->render('todo', []);
            echo $html;
            return;
            break;
        default:
            # code...
            break;
    }
}
