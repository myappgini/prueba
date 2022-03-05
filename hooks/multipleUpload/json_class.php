<?php
if (!function_exists('getMemberInfo')) {
    include '../../lib.php';
}

class ProcessJson
{
    public $info = [
        'tn' => false,
        'fn' => false,
        'id' => false,
        'ix' => false,
        'lastix' => false,
        'where' => false,
    ];

    //changue this if you want to preserve file on server
    public $delete_file = true;

    public function __construct()
    {
        header('Content-Type: application/json; charset=utf-8');
    }

    public function add_data($data)
    {
        $this->info['where'] = $this->get_where();
        $set = $this->get_array();
        if (is_null($set)) {
            $data['defaultImage'] = 'true';
        }
        $set['images'][uniqid()] = $data;
        $set = array_merge($set, $this->info, array("where" => ""));
        $set['length'] = count($set['images']);
        return $this->set_array($set);
    }

    public function get_view($view)
    {
        include_once 'hbs_views.php';
        header('Content-Type: text/html; charset=utf-8');
        $j = json_decode($this->get_json(), true);
        $j['view'] = $view;
        return $handlebars->render($view, $j);
    }

    /**
     * Get all json data in array .
     *
     * @return array
     */
    public function get_array()
    {
        return json_decode($this->get_json(), true);
    }

    public function del_item()
    {
        // this code require new version db
        // $sql = "UPDATE {$tn} SET {$fn}=json_remove({$fn},'$.images[{$ix}]') WHERE {$where}";
        // return  sql($sql, $eo);
        $set = $this->get_array();
        $delete = $set['images'][$this->info['ix']];
        $delete = $this->set_updated_info('delete', $delete);
        $set['delete'][] = $delete;
        unset($set['images'][$this->info['ix']]);
        $set['length'] = count($set['images']);
        return $this->set_array($set);
    }

    public function set_value($key, $value)
    {
        // this code require new version db
        //$sql = "UPDATE {$tn} SET {$fn}=json_set({$fn},'$.images[{$ix}].{$key}','{$value}') WHERE {$where}";
        // $res = sqlValue($sql);
        // return $res;
        $set = $this->get_array();
        $data = $set['images'][$this->info['ix']];
        $data[$key] = makeSafe($value);
        $data = $this->set_updated_info('updated', $data);
        $set['images'][$this->info['ix']] = $data;
        return $this->set_array($set);
    }
    /////////////////////////////////////////

    /**
     * Get all json text from database.
     *
     * @return string
     */
    private function get_json()
    {
        $this->info['where'] = $this->get_where();
        if (!$this->info['tn']) die('data not valid');
        $sql = "SELECT {$this->info['fn']} FROM `{$this->info['tn']}` WHERE 1=1 AND {$this->info['where']}";
        $res = sqlValue($sql);
        return stripslashes($res); //* add this function stripslashes to make it work on windows
    }

    private function set_json($set)
    {
        $this->info['where'] = $this->get_where();
        if (!$this->info['tn']) die('data not valid');
        $sql = "UPDATE `{$this->info['tn']}` SET {$set} WHERE 1=1 AND {$this->info['where']}";
        $eo = ['silentErrors' => true];
        $res = sql($sql, $eo);
        return $res;
    }

    private function set_array($set)
    {
        return $this->set_json("`{$this->info['fn']}`='" . json_encode($set) . "'");
    }

    private function get_where()
    {
        $key = getPKFieldName($this->info['tn']);
        return $key ? "`{$key}`='{$this->info['id']}'" : $key;
    }

    private function set_updated_info($prefix, $array)
    {
        $data[$prefix . 'By'] = getLoggedMemberID();
        $data[$prefix . 'Date'] = date('d.m.y');
        $data[$prefix . 'Time'] = date('H:i:s');
        return array_merge($array, $data);
    }
}
