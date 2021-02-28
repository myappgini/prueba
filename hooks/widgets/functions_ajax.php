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
    'data' => Request::val('data', false),
];

header('Content-Type: application/json; charset=utf-8');

if ($cmd) {
    $options = $data['data'];
    $html = $handlebars->render($cmd, $options);
    $res=["html"=>$html];
    // switch ($cmd) {
    //     case 'info-box':
    //         $res=['render'=>$cmd];
    //         break;
    //     default:
    //         $res= ["error"=>'something wrong!!!'];
    //         break;
    // }
    echo json_encode($res);
}

include ('def_hbs.php');



return;
