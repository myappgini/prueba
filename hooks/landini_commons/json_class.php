<?php
if (!function_exists('getMemberInfo'))  include '../../lib.php';
include_once 'landini_class.php';
class ProcessJson
{
    private $info = [
        'tn' => false,
        'fn' => false,
        'id' => false,
        'ix' => false
    ];

    public $trash_folder = 'delete';
    private $temp_array = [];

    public function __construct($info = [])
    {
        $this->info = $info;
        $this->temp_array = $this->get_array();
        header('Content-Type: application/json; charset=utf-8');
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
        $data['uid'] = uniqid();
        if ($folder) {
            $this->temp_array[$folder][$data['uid']] = $data;
        } else {
            // $this->temp_array[$data['uid']] = $data;
            $this->temp_array = $data;
        }

        return $this->set_array();
    }

    public function get_count($folder = false)
    {
        $set = $folder ? $this->temp_array[$folder] : $this->temp_array;
        return count($set);
    }

    public function del_item($folder = false)
    {

        if ($folder) {
            $trash = $this->temp_array[$folder][$this->info['ix']];
            unset($this->temp_array[$folder][$this->info['ix']]);
        } else {
            $trash = $this->temp_array[$this->info['ix']];
            unset($this->temp_array[$this->info['ix']]);
        }
        $this->temp_array[$this->trash_folder][uniqid()] = $trash;

        return $this->set_array();
    }

    public function del_item_sql()
    {
        // this code require new version db
        // $sql = "UPDATE {$tn} SET {$fn}=json_remove({$fn},'$.images[{$ix}]') WHERE {$where}";
        // return  sql($sql, $eo);
        // $set = $this->temp_array;
    }

    public function set_value_sql()
    {
        // this code require new version db
        //$sql = "UPDATE {$tn} SET {$fn}=json_set({$fn},'$.images[{$ix}].{$key}','{$value}') WHERE {$where}";
        // $res = sqlValue($sql);
        // return $res;
    }

    public function set_value($key, $value, $folder = false)
    {
        if ($folder) {
            if (!is_array($value)) {
                $this->temp_array[$folder][$this->info['ix']][makeSafe($key)] = makeSafe($value);
            } else {
                $this->temp_array[$folder][$this->info['ix']][makeSafe($key)][] = $value;
            }
            $this->temp_array[$folder][$this->info['ix']] = $this->set_info('updated', $this->temp_array[$folder][$this->info['ix']]);
        } else {
            $this->temp_array[$this->info['ix']][makeSafe($key)] = makeSafe($value);
            $this->temp_array[$this->info['ix']] = $this->set_info('updated', $this->temp_array[$this->info['ix']]);
        }

        return $this->set_array();
    }

    public function get_folders()
    {
        $set = $this->temp_array;
        return array_keys($set);
    }

    /**
     * Obtiene el array de la base de datos.
     *
     * @param string $Folder se puede especificar una carpeta dentro del array para obtner los datos.
     *
     * @return array con el set de datos
     */
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
     * @return string con el json o falso si hay error
     */
    private function get_json()
    {
        //TODO: get json from another source
        $sql = "SELECT {$this->info['fn']} FROM `{$this->info['tn']}` WHERE {$this->get_where()}";
        //TODO: error select control
        $res = sqlValue($sql);
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
        $set = "`{$this->info['fn']}`='" . json_encode($set, JSON_UNESCAPED_UNICODE) . "'";
        $sql = "UPDATE `{$this->info['tn']}` SET {$set} WHERE {$this->get_where()}";
        $eo = ['silentErrors' => true];
        $res = sql($sql, $eo);
        //TODO: error update control
        return $res;
    }

    /**
     * prepara el array para convertirlo en JSON y lo manda a guardar, le agrega información adicional
     *
     * @param array $set array para enviar a guardar.
     *
     * @return true is OK
     */
    public function set_array($set = [])
    {
        if (empty($set)) $set = $this->temp_array;
        $set = array_merge($set, $this->info);
        return $this->set_json($set);
    }

    private function get_where()
    {
        return Landini::whereConstruct($this->info);
    }

    private function set_info($prefix, $array)
    {
        $data[$prefix . 'By'] = getLoggedMemberID();
        $data[$prefix . 'Date'] = date('d.m.y');
        $data[$prefix . 'Time'] = date('H:i:s');
        return array_merge($array, $data);
    }
}
