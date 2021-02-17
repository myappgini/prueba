<?php
if (!function_exists('getMemberInfo')) {
    include '../../lib.php';
}
include 'landini_commons/landini_functions.php';
include 'handlebars.php';

$cmd = Request::val('cmd', false);
if (!$cmd) {
    die('bad command');
}

$data = [
    'ix' => Request::val('ix', false),
    'mi' => getMemberInfo(Request::val('mi', false)),
    'lm' => getLoggedMemberID(),
    'data' => Request::val('data',false),
];

header('Content-Type: application/json; charset=utf-8');

if ($cmd) {
    switch ($cmd) {
        case 'option-todo':
            $html = $handlebars->render('dropdown_menu', []);
            $res=["html"=>$html];
            break;
        default:
            $res= ["error"=>'something wrong!!!'];
            break;
    }
    echo json_encode($res);
    return;
}