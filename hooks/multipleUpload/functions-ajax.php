<?php
if (!function_exists('getMemberInfo')) {
    include("../../lib.php");
}
$cmd = Request::val('cmd');
$tn = Request::val('tn');
$fn = Request::val('fn');
$where = Request::val('where');

if ($cmd !== '') {
    switch ($cmd) {
        case 'get_json':
            $rslt = get_json($tn, $fn, $where);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($rslt);
            break;
        case 'put_json':
            $rslt = put_json($tn, $set, $where);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($rslt);
            break;
    }
    return;
}

/*
* * get_json function
    $tn = table name
    $fn = field name
    $where
*/
function get_json($tn, $fn, $where)
{
    $sql = "SELECT {$fn} FROM `{$tn}` WHERE 1=1 AND {$where}";
    $res = sqlValue($sql);
    return $res;
}

function put_json($tn, $set, $where)
{
    $sql = "UPDATE `{$tn}` SET {$set} WHERE 1=1 AND {$where}";
    $res = sqlValue($sql);
    return $res;
}
