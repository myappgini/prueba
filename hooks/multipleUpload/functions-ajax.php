<?php
if (!function_exists('getMemberInfo')) {
    include '../../lib.php';
}
include 'UploadsView.php';

$cmd = Request::val('cmd');

$info = [
    'tn' => Request::val('tn'),
    'fn' => Request::val('fn'),
    'id' => Request::val('id'),
    'ix' => Request::val('ix'),
    'lastix' => Request::val('lastix'),
];

header('Content-Type: application/json; charset=utf-8');

if ($cmd !== '') {
    $info['where'] = whereConstruct($info);
    if (!$info['where']) {
        $rslt['error'] = 'NOT where found';
    } else {
        switch ($cmd) {
            case 'del_json':
                //changue this if you want to preserve file on server
                $DELETE_FILE = true;
                if ($DELETE_FILE) {
                    //TODO: delete file
                    $j = get_array($info);
                    $b = $j['images'][$info['ix']];
                    $rslt['file'] = unlink($b['folder'] . $b['fileName']);
                    if ($b['thumbnail']) {
                        $rslt = unlink(
                            $b['folder'] . $b['name'] . '_th.' . $b['extension']
                        );
                    }
                }
                $rslt['json'] = del_json($info);

                break;
            case 'set-default':
                if ($lastix != '') {
                    $res = upd_json($info, 'defaultImage', 'false');
                }
                $res = upd_json($info, 'defaultImage', 'true');
                $rslt['res'] = $res;
                $rslt['setIx'] = $info['ix'];

                break;
            case 'set-pdf-page':
                $page = Request::val('page');
                if ($page) {
                    $res = upd_json($info, 'pdfPage', $page);
                    $rslt['res'] = $res;
                } else {
                    $rslt['error'] = 'must indicate a page';
                }
                break;
            case 'set-title':
                $newTitle = Request::val('newtitle');
                $res = upd_json($info, 'title', $newTitle);
                $rslt['res'] = $res . ' - ' . $newTitle;
                break;
            case 'full':
                # Will render the model to the template
                $j = get_json($info);
                $j = json_decode($j, true);
                $html = $handlebars->render('dv', $j);
                echo $html;
                return;
                break;
            case 'gallery':
                # Will render the model to the template
                $j = get_json($info);
                $j = json_decode($j, true);
                $j['gallery'] = true;
                $html = $handlebars->render('gallery', $j);
                echo $html;
                return;
                break;
            case 'uploading':
                //calling from uploading option
                return;
                break;
            default:
                $rslt['error'] = 'OPPS, what you need to do?';
                break;
        }
    }
    echo json_encode($rslt);
    return;
} else {
    $rslt['error'] = 'OPPS, what you need?';
    echo json_encode($rslt);
    return;
}

function get_json($info)
{
    $sql = "SELECT {$info['fn']} FROM `{$info['tn']}` WHERE 1=1 AND {$info['where']}";
    $res = sqlValue($sql);
    return stripslashes($res); //* add this function stripslashes to make it work on windows
}

function get_array($info)
{
    $res = get_json($info);
    return json_decode($res, true);
}

function put_json($info, $set)
{
    $sql = "UPDATE `{$info['tn']}` SET {$set} WHERE 1=1 AND {$info['where']}";
    $eo = ['silentErrors' => true];
    $res = sql($sql, $eo);
    return $res;
}

function put_array($info, $set)
{
    $set = "`{$info['fn']}`='" . json_encode($set) . "'";
    return put_json($info, $set);
}

function del_json($info)
{
    // this code require new version db
    // $sql = "UPDATE {$tn} SET {$fn}=json_remove({$fn},'$.images[{$ix}]') WHERE {$where}";
    // return  sql($sql, $eo);
    $set = get_array($info);
    $delete = $set['images'][$info['ix']];
    $delete = setInfo('delete', $delete);
    $set['delete'][] = $delete;
    array_splice($set['images'], $info['ix'], 1);
    $set['length'] = count($set['images']);
    return put_array($info, $set);
}

function upd_json($info, $key, $value)
{
    // this code require new version db
    //$sql = "UPDATE {$tn} SET {$fn}=json_set({$fn},'$.images[{$ix}].{$key}','{$value}') WHERE {$where}";
    // $res = sqlValue($sql);
    // return $res;
    $set = get_array($info);
    $data = $set['images'][$info['ix']];
    $data[$key] = makeSafe($value);
    $data = setInfo('updated', $data);
    $set['images'][$info['ix']] = $data;
    return put_array($info, $set);
}

function add_json($info, $data)
{
    $info['where'] = whereConstruct($info);
    $set = get_array($info);
    if (is_null($set)) {
        $data['defaultImage'] = 'true';
    }
    $set['images'][] = $data;
    $set = array_merge($set, $info,array("where"=>""));
    $set['length'] = count($set['images']);
    return put_array($info, $set);
}

function whereConstruct($info)
{
    $key = getPKFieldName($info['tn']);
    return $key ? "`{$key}`='{$info['id']}'" : $key;
}

function setInfo($prefix, $array)
{
    $data[$prefix . 'By'] = getLoggedMemberID();
    $data[$prefix . 'Date'] = date('d.m.y');
    $data[$prefix . 'Time'] = date('H:i:s');
    return array_merge($array, $data);
}
