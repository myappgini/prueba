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
    'tn' => 'landini_todo',
    'fn' => 'todos',
    'ix' => Request::val('ix', false),
    'mi' => getMemberInfo(Request::val('mi', false)),
    'id' => getLoggedMemberID(),
    'tk' => Request::val('task', false),
    'nt' => Request::val('newtext', false),
    'ok' => Request::val('complete', false),
    'us' => Request::val('user', false), //user to send task
    'pr' => Request::val('preserve', false)=== "true" ? true : false, //preserve task in my list
    'du' => Request::val('due',false), //due task
];

header('Content-Type: application/json; charset=utf-8');

if ($cmd) {
    $tasks = get_data($data);
    switch ($cmd) {
        case 'option-todo':
            $html = $handlebars->render('dropdown_menu', []);
            echo $html;
            break;
        case 'get-todo':
            $tasks['list_delete'] = false;
            $html = $handlebars->render('todos', $tasks);
            echo $html;
            break;
        case 'get-deleted':
            $tasks['list_delete'] = true;
            $html = $handlebars->render('todos', $tasks);
            echo $html;
            break;
        case 'removed-deleted':
            unset($tasks['deleted_tasks']);
            $res = update_data($data, $tasks);
            $tasks['list_delete'] = false;
            echo $handlebars->render('todos', $tasks);
            break;
        case 'remove-task':
            unset($tasks['deleted_tasks'][$data['ix']]);
            $res = update_data($data, $tasks);
            echo 'removed: '. $res;
            break;
        case 'delete-task':
            echo 'deleted: '. delete_task($data, $tasks);
            break;
        case 'recover-task':
            $uid = uniqid();
            $tasks['deleted_tasks'][$data['ix']]['deleted']=false;
            $tasks['deleted_tasks'][$data['ix']]['recovered_deleted']=date('Y-m-d H:i:s');
            $tasks['deleted_tasks'][$data['ix']]['details'][]=["message"=>"Recovered","date"=>date('Y-m-d H:i:s')];

            $tasks['tasks'][$uid]=$tasks['deleted_tasks'][$data['ix']];
            $tasks['tasks'][$uid]['uid']=$uid;
            unset($tasks['deleted_tasks'][$data['ix']]);
            $res = update_data($data, $tasks);
            echo 'recovered: '. $res;
            break;
        case 'edit-task':
            if (!$data['nt']) {
                echo "{error:'something wrong in edit task'}";
                break;
            }
            if ($tasks['tasks'][$data['ix']]['task']===$data['nt']) {
                echo "{error:'no task changed'}";
                break;
            }
            $tasks['tasks'][$data['ix']]['task']=$data['nt'];
            $tasks['tasks'][$data['ix']]['details'][]=["message"=>"Task change to:{$data['nt']}","date"=>date('Y-m-d H:i:s')];
            $res = update_data($data, $tasks);
            echo 'edited: '. $res;
            break;
        case 'edit-description':
            if (!$data['nt']) {
                echo "{error:'something wrong in edit description'}";
                break;
            }
            if ($tasks['tasks'][$data['ix']]['description']===$data['nt']) {
                echo "{error:'no descrition changed'}";
                break;
            }
            $tasks['tasks'][$data['ix']]['description']=$data['nt'];
            $tasks['tasks'][$data['ix']]['details'][]=["message"=>"Description change to:{$data['nt']}","date"=>date('Y-m-d H:i:s')];
            $res = update_data($data, $tasks);
            echo 'edited: '. $res;
            break;
        case 'check-task':
            $ok = $data['ok'] === "true" ? true : false;
            $tasks['tasks'][$data['ix']]['complete']=$ok;
            $tasks['tasks'][$data['ix']]['details'][]=["message"=> $ok ? "Task marked as completed" : "Task marked as uncompleted" ,"date"=>date('Y-m-d H:i:s')];
            $res = update_data($data, $tasks);
            echo 'edited: '. $res;
            break;
        case 'set-due':
            $tasks['tasks'][$data['ix']]['due']= $data['du'];
            $tasks['tasks'][$data['ix']]['details'][]=["message"=> "Set due to tas: ".$data['du'] ,"date"=>date('Y-m-d H:i:s')];
            $res = update_data($data, $tasks);
            echo 'edited: '. $res;
            break;
        case 'get-values':
            $res['length'] = is_null($tasks['length']) ? 0 : $tasks['length'];
            $res['deleted'] = is_null($tasks['deleted']) ? 0 : $tasks['deleted'];
            $res['listed'] = is_null($tasks['listed']) ? 0 : $tasks['listed'];
            $res['completed'] = is_null($tasks['completed']) ? 0 : $tasks['completed'];
            echo json_encode($res);
            break;
        case 'add-task':
            if (!$data['tk']) {
                echo "{error:'something wrong'}";
                break;
            }
            $task = add_data($data);
            $html = $handlebars->render('task', $task);
            echo $html;
            break;
        case 'task-detail':
            $task = $tasks['tasks'][$data['ix']];
            $task += detail_options();
            $html = $handlebars->render('detail', $task);
            echo $html;
            break;
        case 'send-task-user':
            if (!$data['us'] || $data['us'] === $data['id']) {
                echo "{error:'select a correct user'}";
                break;
            }
            $uid = uniqid();
            $task = $tasks['tasks'][$data['ix']];
            $tasks['tasks'][$data['ix']]['details'][]=["send_to"=>$data['us'],"date"=>date('Y-m-d H:i:s')];
            $res = ' edited: '. update_data($data, $tasks);

            if (!$data['pr']) {
                $res .= ' deleted: '. delete_task($data, $tasks);
            }
            
            $newdata = $data;
            $newdata['id']=$data['us'];
            $task['uid']=$uid;

            $user_tasks = get_data($newdata);
            $user_tasks['tasks'][$uid]=$task;
            $user_tasks['tasks'][$uid]['from']=$data['id'];
            $user_tasks['tasks'][$uid]['from_date']=date('Y-m-d H:i:s');
            $user_tasks['tasks'][$uid]['details'][]=["message"=>"task from {$data['us']}","date"=>date('Y-m-d H:i:s')];

            $res .= ' sending: '. update_data($newdata, $user_tasks);
            echo $res;
            break;
        default:
            echo "{error:'something wrong!!!'}";
            break;
    }
    return;
}

function get_data(&$data)
{
    $res = getDataTable($data, true);
    return json_decode($res['todos'], true);
}
function add_data(&$data)
{
    $tasks = get_data($data);
    $uid = uniqid();
    $task = [
        'task' => $data['tk'],
        'complete' => false,
        'added' => date('Y-m-d H:i:s'),
        'due' => false,
        'details' => [["message"=>"New task: {$data['tk']}","date"=>date('Y-m-d H:i:s')]],
        'deleted' => false,
        'date_deleted' => false,
        'uid' => $uid,
    ];
    $tasks['tasks'][$uid] = $task;

    $res = update_data($data, $tasks);

    return $task;
}

function update_data(&$data, $set)
{
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
    $del = count($set['deleted_tasks']);
    $completed = array_value_recursive_count('complete', true, $set['tasks']);
    $elements=count($set['tasks']);
    $set['length']=$elements + $del;
    $set['deleted']=$del;
    $set['listed']=$elements;
    $set['completed']=$completed;
    $set = "`{$data['fn']}`='" . json_encode($set) . "'";
    $sql = "UPDATE `{$data['tn']}` SET {$set} WHERE {$where}";
    $res = sql($sql, $eo);
    $errors[] = $eo;

    $data['errors'] = $errors;

    return $res;
}
function array_value_recursive_count($key, $value, array $arr)
{
    $val = array();
    array_walk_recursive($arr, function ($v, $k) use ($key, &$val, $value) {
        if ($k === $key && $v === $value) {
            array_push($val, $v);
        }
    });
    return count($val) >= 1 ? count($val) : 0;
}

function delete_task($data, $tasks)
{
    $uid = uniqid();
    $tasks['tasks'][$data['ix']]['deleted']=true;
    $tasks['tasks'][$data['ix']]['date_deleted']=date('Y-m-d H:i:s');
    $tasks['tasks'][$data['ix']]['details'][]=["message"=>"Delete this task","date"=>date('Y-m-d H:i:s')];
    $tasks['deleted_tasks'][$uid]=$tasks['tasks'][$data['ix']];
    $tasks['deleted_tasks'][$uid]['uid']=$uid;
    unset($tasks['tasks'][$data['ix']]);
    $res = update_data($data, $tasks);
    return $res;
}

function detail_options()//detail modal windows options
{
    $options['modal_header']=[
        "headline"=>"To-Do Task Detail",
        "id"=>"modal-todo",
        "size"=>"",
        "dismiss"=>true,
    ];
    $options['modal_footer']=[
        "close_btn"=>[
            "enable"=>true,
            "text"=>"Close",
            "color"=>"default",
            "size"=>"xs",
            "class"=>"",
            "attr"=>"data-dismiss='modal'",
            "icon"=>[
                "enable"=>true,
                "icon"=>"glyphicon glyphicon-remove",
            ],
        ],
    ];
    //send task box options
    $options['send_box_options']=[
        "headline"=>"Send Task to user",
        "color"=>"success",
        "solid"=>false,
        "with-border"=>false,
        "class"=>"",
        "attr"=>"",
        "box-tool"=>[
            "enable"=>false,
            "collapsable"=>true,
            "removable"=>true,

        ],
    ];
    //send taks button options
    $options['send_options']=[
        "send_btn"=>[
            "enable"=>true,
            "text"=>"Send",
            "color"=>"primary",
            "size"=>"xs",
            "class"=>"send-taks-user pull-right",
            "attr"=>"data-cmd='send-task-user'",
            "icon"=>[
                "enable"=>true,
                "icon"=>"glyphicon glyphicon-send",
            ],
        ],
    
    ];
    //details task box options
    $options['due_box_options']=[
        "headline"=>"Due Task",
        "color"=>"warning",
        "solid"=>false,
        "with-border"=>false,
        "class"=>"",
        "attr"=>"",
        "box-tool"=>[
            "enable"=>false,
            "collapsable"=>true,
            "removable"=>false,
        ],
    ];
    //set due taks button options
    $options['due_options']=[
        "set_due_btn"=>[
            "enable"=>true,
            "text"=>"Set due",
            "color"=>"primary",
            "size"=>"xs",
            "class"=>"set-due pull-right",
            "attr"=>"data-cmd='set-due'",
            "icon"=>[
                "enable"=>true,
                "icon"=>"glyphicon glyphicon-time",
            ],
        ],
    
    ];
    return $options;
}
