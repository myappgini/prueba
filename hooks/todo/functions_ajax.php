<?php
if (!function_exists('getMemberInfo')) {
    include '../../lib.php';
}
include '../landini_commons/landini_functions.php';
include 'handlebars.php';

$cmd = Request::val('cmd', false);
if (!$cmd) {
    die('bad command');
}

$data_selector = [
    'tn' => 'landini_todo',
    'fn' => 'todos',
    'ix' => Request::val('ix', false),
    'mi' => getMemberInfo(Request::val('mi', false)),
    'id' => getLoggedMemberID(),
    'tk' => Request::val('task', false),
];

header('Content-Type: application/json; charset=utf-8');

if ($cmd) {
    switch ($cmd) {
        case 'get-todo':
            $tasks = get_data($data_selector);
            $html = $handlebars->render('todo', $tasks);
            echo $html;
            return;
            break;
        case 'delete-task':
            $tasks = get_data($data_selector);
            $tasks['tasks'][$data_selector['ix']]['deleted']=true;
            $tasks['tasks'][$data_selector['ix']]['date_deleted']=date('d.m.y h:m:s');
            $res = update_data($data_selector,$tasks);
            echo 'deleted: '. $res;
            return;
            break;
        case 'add-task':
            if (!$data_selector['tk']) {
                echo "{error:'something worng'}";
                return;
                break;
            }
            $task = add_data($data_selector);
            $html = $handlebars->render('task', $task);
            echo $html;
            return;
            break;
        default:
            # code...
            break;
    }
}

function get_data(&$data)
{
    $res = getDataTable($data, true);
    $res = json_decode($res['todos'], true);
    return $res;
}
function add_data(&$data)
{
    $tasks = get_data($data);

    $task = [
        'task' => $data['tk'],
        'complete' => false,
        'added' => date('d.m.y h:m:s'),
        'due' => false,
        'edited' => [$data['tk']],
        'deleted' => false,
        'date_deleted' => false,
    ];

    $tasks['tasks'][uniqid()] = $task;

    $res = update_data($data,$tasks);

    return $task;
}

function update_data(&$data,$set){

    $where = whereConstruct($data);
    $eo = ['silentErrors' => true];
    //check if member exist
    $count = sqlValue("SELECT COUNT( * ) FROM `{$data['tn']}` WHERE {$where};");
    if ($count < 1) {
        //add member if not exist
        $res = sql(
            "INSERT INTO `{$data['tn']}`(`memberID`) VALUES ('{$data['id']}')",
            $eo
        );
        $errors[] = $eo;
    }
    $set = "`{$data['fn']}`='" . json_encode($set) . "'";
    $sql = "UPDATE `{$data['tn']}` SET {$set} WHERE {$where}";
    $res = sql($sql, $eo);
    $errors[] = $eo;

    $data['errors'] = $errors;

    return $res;
}
