<?php
if (!function_exists('getMemberInfo')) {
    include("../../lib.php");
}
$cmd = Request::val('cmd');
$tn = Request::val('tn');
$fn = Request::val('fn');
$id = Request::val('id');
$ix = Request::val('ix');
$lastix = Request::val('lastix');
////$where = Request::val('where');

header('Content-Type: application/json; charset=utf-8');
if ($cmd !== '') {
    $where = whereConstruct($tn,$id);
    if (!$where){
        $rslt['error']= "NOT where found";
        echo json_encode($rslt);
        return;
    } 
    switch ($cmd) {
        case 'get_json':
            $rslt = get_json($tn, $fn, $where);
            echo json_encode($rslt);
            break;
        case 'put_json':
            $rslt = put_json($tn, $set, $where);
            echo json_encode($rslt);
        break;
        case 'set-default':
            if ($lastix != ""){
                $sql ="UPDATE {$tn} SET {$fn}=json_set({$fn},'$.images[{$lastix}].defaultImage',false) WHERE {$where}";
                $res = sqlValue($sql);
            }
            $sql ="UPDATE {$tn} SET {$fn}=json_set({$fn},'$.images[{$ix}].defaultImage',true) WHERE {$where}";
            $res = sqlValue($sql);
            $rslt ['res'] = $res;
            echo json_encode($rslt);
            break;
        case "set-pdf-page":
            $page = Request::val('page');
            if ($page){
                $sql ="UPDATE {$tn} SET {$fn}=json_set({$fn},'$.images[{$ix}].pdfPage',$page) WHERE {$where}";
                $res = sqlValue($sql);
                $rslt ['res']= $res;
            }else{
                $rslt['error']= "must indicate a page";
            }
            echo json_encode($rslt);
            break;
    }
    return;
}else{
    $rslt['error']= "OPPS, what you need?";
    echo json_encode($rslt);
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
    //* add this function stripslashes to make it work on windows
    return stripslashes($res);
}

function put_json($tn, $set, $where)
{
    $sql = "UPDATE `{$tn}` SET {$set} WHERE 1=1 AND {$where}";
    $res = sql($sql, $e);
    return $res;
}

function add_json($tn, $id, $fn, $data)
{
    $where = whereConstruct($tn,$id);
    $res = get_json($tn, $fn, $where);
    $set = json_decode($res, true);
    if (is_null($set)) $data['defaultImage'] = true;
    $set['images'][] = $data;
    $set['id']=$id;
    $set['tn']=$tn;
    $set['fn']=$fn;
    $set['length'] = count($set['images']);
    $set =  "$fn='" . json_encode($set) . "'";
    $res = put_json($tn, $set, $where);
    return $res;
}

function whereConstruct($tn,$id){
    $key = getPKFieldName($tn);
    $key = $key ? "`{$key}`='{$id}'" : $key;
    return $key;
}