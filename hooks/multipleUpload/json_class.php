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
    ];

    public function __construct()
    {
        header('Content-Type: application/json; charset=utf-8');
    }

    public function add_data($data)
    {
        $set = $this->get_array();
        $set['images'][uniqid()] = $data;
        $set = array_merge($set, $this->info);
        $set['length'] = count($set['images']);
        return $this->set_array($set);
    }

    public function get_counts($data)
    {
        $set = $this->get_array();
        return count($set[$data]);
    }

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

    private function get_json()
    {
        $sql = "SELECT {$this->info['fn']} FROM `{$this->info['tn']}` WHERE 1=1 AND {$this->get_where()}";
        $res = sqlValue($sql);
        return stripslashes($res); //* add this function stripslashes to make it work on windows
    }

    private function set_json($set)
    {
        $sql = "UPDATE `{$this->info['tn']}` SET {$set} WHERE 1=1 AND {$this->get_where()}";
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
        if (!$this->info['tn'] && !$this->info['id'] && $this->info['fn']) die('data not valid');
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
