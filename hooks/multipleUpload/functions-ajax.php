<?php
if (!function_exists('getMemberInfo')) {
    include '../../lib.php';
}
include_once 'json_class.php';
include_once 'MultipleUpload.php';
$json = new ProcessJson;
$mu = new MultipleUpload();

$cmd = Request::val('cmd');

$data = [
    'tn' => Request::val('tn'), //table name
    'fn' => Request::val('fn'), //field name
    'id' => Request::val('id'), //index record
    'ix' => Request::val('ix'), //id item gallery
    'lastix' => Request::val('lastix'), //id last item gallery
];

$json->info = $data;
$mu->info=$data;
header('Content-Type: application/json; charset=utf-8');

if ($cmd !== '') {
    switch ($cmd) {
        case 'del-item':
            //changue this if you want to preserve file on server
            $DELETE_FILE = true;
            if ($DELETE_FILE) {
                //TODO: delete file
                $j = $json->get_array();
                $b = $j['images'][$data['ix']];
                $rslt['file'] = unlink($b['folder'] . $b['fileName']);
                if ($b['thumbnail']) {
                    $rslt = unlink(
                        $b['folder'] . $b['name'] . '_th.' . $b['extension']
                    );
                }
            }
            $rslt['json'] = $json->del_item();
            break;
        case 'set-default':
            if ($data['lastix'] != '') {
                $json->info['ix'] = $data['lastix'];
                $json->set_value('defaultImage', 'false');
            }
            $json->info['ix'] = $data['ix'];
            $json->set_value('defaultImage', 'true');
            $rslt['setIx'] = $data['ix'];
            break;
        case 'set-pdf-page':
            $page = Request::val('page');
            if ($page) {
                $res = $json->set_value('pdfPage', $page);
                $rslt['res'] = $res;
            } else {
                $rslt['error'] = 'must indicate a page';
            }
            break;
        case 'set-title':
            $newTitle = Request::val('newtitle');
            $res = $json->set_value('title', $newTitle) ? "changed to: " : "NOT changed to: ";
            $rslt['res'] = $res . $newTitle;
            break;
        case 'full':
            echo get_view('dv', $json->get_array());
            return;
            break;
        case 'gallery':
            echo get_view('gallery', $json->get_array());
            return;
            break;
        case 'uploading':
            //calling from uploading option
            //change folder base
            $mu->folder_base = "{$mu->folder_base}/{$mu->info['tn']}/{$mu->info['id']}";
            //
            $mu->process_upload();
            return;
            break;
        case 'get-frame':
            include 'uploadFrame.php';
            return;
        default:
            $rslt['error'] = 'OPPS, what you need to do?';
            break;
    }
    echo json_encode($rslt);
    return;
} else {
    $rslt['error'] = 'OPPS, what you need?';
    echo json_encode($rslt);
    return;
}

function get_view($view, $data)
{
    include_once 'hbs_views.php';
    header('Content-Type: text/html; charset=utf-8');
    $data['view'] = $view;
    return $handlebars->render($view, $data);
}
