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

    public $trash_folder = 'delete';
    private $temp_array = [];

    public function __construct()
    {
        header('Content-Type: application/json; charset=utf-8');
        $this->temp_array = $this->get_array();
    }

    /**
     * Push data onto the array folder stack, agrega el dato y guarda el json en base de datos
     *
     * @param array $data dato para agregar a la colección o folder.
     *
     * @param array $folder carpeta dentro del array para guarda el dato.
     *
     * @return true is OK
     */
    public function add_data($data, $folder = false)
    {
        $set = $this->get_array();
        if ($folder) {
            $set[$folder][uniqid()] = $data;
        } else {
            $set[uniqid()] = $data;
        }

        return $this->set_array($set);
    }

    public function get_count($folder = false)
    {
        return count($this->get_array($folder));
    }

    public function del_item($folder = false)
    {
        // this code require new version db
        // $sql = "UPDATE {$tn} SET {$fn}=json_remove({$fn},'$.images[{$ix}]') WHERE {$where}";
        // return  sql($sql, $eo);
        $set = $this->get_array();

        if ($folder) {
            $trash = $set[$folder];
            unset($set[$folder][$this->info['ix']]);
        } else {
            $trash = $set;
            unset($set[$this->info['ix']]);
        }

        $unset = $this->set_array($set);
        $trash = $this->move_to_folder($trash[$this->info['ix']], $this->trash_folder);

        return $trash && $unset;
    }

    private function move_to_folder($data, $folder = false)
    {
        $set = $this->get_array($folder);
        $set[uniqid()] = $this->set_updated_info('movedTo', $data);
        $data = $this->add_data($set, $folder); // agrega el folder y guarda al json original
        return $data; //debiera regresar un true
    }

    public function set_value($key, $value, $folder = false)
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

    public function get_folders()
    {
        $set = $this->get_array();
        return array_keys($set);
    }

    public function get_array($folder = false)
    {
        $set = json_decode($this->get_json(), true);
        $folder && $set = $set[$folder];
        return $set;
    }
    /////////////////////////////////////////
    /**
     * SELECT en la base de datos SQL.
     *
     * @param array $set string para enviar a la base de datos con el set de SQL.
     *
     * @return string con el json o falso si hay error
     */
    private function get_json()
    {
        $sql = "SELECT {$this->info['fn']} FROM `{$this->info['tn']}` WHERE {$this->get_where()}";
        $res = sqlValue($sql);
        //TODO: error select control
        return stripslashes($res); //* add this function stripslashes to make it work on windows
    }

    /**
     * UPDATE en la base de datos SQL.
     *
     * @param array $set string para enviar a la base de datos con el set de SQL.
     *
     * @return true is OK
     */
    private function set_json($set)
    {
        $sql = "UPDATE `{$this->info['tn']}` SET {$set} WHERE 1=1 AND {$this->get_where()}";
        $eo = ['silentErrors' => true];
        $res = sql($sql, $eo);
        //TODO: error update control
        return $res;
    }

    /**
     * prepara el array para convertirlo en JSON y lo manda a guardar a la base de datos, le agrega información adicional
     *
     * @param array $set array para enviar a la base de datos.
     *
     * @return true is OK
     */
    private function set_array($set)
    {
        $set = array_merge($set, $this->info);
        return $this->set_json("`{$this->info['fn']}`='" . json_encode($set) . "'");
    }

    private function get_where()
    {
        if (!$this->info['tn'] && !$this->info['id'] && $this->info['fn']) die('data info not valid');
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
