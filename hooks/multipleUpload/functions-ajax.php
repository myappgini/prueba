<?php
if (!function_exists('getMemberInfo')) {
    include("../../lib.php");
}
include('UploadsView.php');

$cmd = Request::val('cmd');
$tn = Request::val('tn');
$fn = Request::val('fn');
$id = Request::val('id');
$ix = Request::val('ix');
$lastix = Request::val('lastix');

header('Content-Type: application/json; charset=utf-8');

if ($cmd !== '') {
    $where = whereConstruct($tn, $id);
    if (!$where) {
        $rslt['error'] = "NOT where found";
    } else {

        switch ($cmd) {
            case 'get_json':
                $rslt = get_json($tn, $fn, $where);
                break;
            case 'put_json':
                $rslt = put_json($tn, $set, $where);
                break;
            case 'del_json':
                //changue this if you want to preserve file on server
                $DELETE_FILE = true;
                if ($DELETE_FILE) {
                    //TODO: delete file
                    $j = get_json($tn, $fn, $where);
                    $j = json_decode($j, true);
                    $b = $j['images'][$ix];
                    $rslt['file'] = unlink($b['folder'] . $b['fileName']);
                    if ($b['thumbnail']) {
                        $rslt = unlink($b['folder'] . $b['name'] . "_th.jpg");
                    }
                }
                $rslt['json'] = del_json($tn, $fn, $ix, $where);

                break;
            case 'set-default':
                if ($lastix != "") {
                    $res = upd_json($tn, $fn, $lastix, 'defaultImage', 'false', $where);
                }
                $res = upd_json($tn, $fn, $ix, 'defaultImage', 'true', $where);
                $rslt['res'] = $res;
                break;
            case "set-pdf-page":
                $page = Request::val('page');
                if ($page) {
                    $res = upd_json($tn, $fn, $ix, 'pdfPage', $page, $where);
                    $rslt['res'] = $res;
                } else {
                    $rslt['error'] = "must indicate a page";
                }
                break;
            case "set-title":
                $newTitle = Request::val('newtitle');
                $res = upd_json($tn, $fn, $ix, 'title', $newTitle, $where);
                $rslt['res'] = $res . " - " . $newTitle;
                break;
            case 'full':
                # Will render the model to the template
                $j = get_json($tn, $fn, $where);
                $j = json_decode($j, true);
                $html =  $handlebars->render("dv", $j);
                echo $html;
                return;
                break;
            case 'gallery':
                # Will render the model to the template
                $j = get_json($tn, $fn, $where);
                $j = json_decode($j, true);
                $j['gallery'] = true;
                $html =  $handlebars->render("gallery", $j);
                echo $html;
                return;
                break;
            case 'uploading':
                //calling from uploading option
                return;
                break;
            default:
                $rslt['error'] = "OPPS, what you need to do?";
                break;
        }
    }
    echo json_encode($rslt);
    return;
} else {
    $rslt['error'] = "OPPS, what you need?";
    echo json_encode($rslt);
    return;
}

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
    $res = sqlValue($sql);
    return $res;
}

function del_json($tn, $fn, $ix, $where)
{
    $sql = "UPDATE {$tn} SET {$fn}=json_remove({$fn},'$.images[{$ix}]') WHERE {$where}";
    $res = sqlValue($sql);
    return $res;
}

function upd_json($tn, $fn, $ix, $key, $value, $where)
{
    $sql = "UPDATE {$tn} SET {$fn}=json_set({$fn},'$.images[{$ix}].{$key}','{$value}') WHERE {$where}";
    $res = sqlValue($sql);
    return $res;
}

function add_json($tn, $id, $fn, $data)
{
    $where = whereConstruct($tn, $id);
    $res = get_json($tn, $fn, $where);
    $set = json_decode($res, true);
    if (is_null($set)) $data['defaultImage'] = true;
    $set['images'][] = $data;
    $set['id'] = $id;
    $set['tn'] = $tn;
    $set['fn'] = $fn;
    $set['length'] = count($set['images']);
    $set =  "$fn='" . json_encode($set) . "'";
    $res = put_json($tn, $set, $where);
    return $res;
}

function whereConstruct($tn, $id)
{
    $key = getPKFieldName($tn);
    $key = $key ? "`{$key}`='{$id}'" : $key;
    return $key;
}
