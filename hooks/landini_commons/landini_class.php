<?php
if (!function_exists('getMemberInfo')) include '../../lib.php';

class Landini {
    
    /**
     * This hook function is called when get a row from a table.
     * @param array $settings Array varibles
     * @return array db_fetch from data result
     */
    public static function getDataTable(&$settings, $values = false)
    {
        [
            'tn' => $table_name,
            'id' => $id,
            'debug' => $debug,
            'error' => $error,
        ] = $settings; // destructuring input array

        !$table_name ? ($error[] = 'need table name') : false;
        $id ? ($where = ' AND ' . self::whereConstruct($settings)) : ($where = '');
    
        $id && $values ? ($where = ' WHERE '. self::whereConstruct($settings)) : false;
    
        $table_from = !$values ? get_sql_from($table_name) : $table_name; //AppGini internal function
        $table_fields = !$values ? get_sql_fields($table_name) : ' * '; //AppGini internal function
    
        $sql = "SELECT {$table_fields} FROM {$table_from}" . $where;
    
        $debug && ($error[] = "<br> $sql <br>");
        $eo = ['silentErrors' => true];
        $res = sql($sql, $eo); //AppGini internal function
        $error[] = $eo;
    
        $settings['error'] = $error;
        return db_fetch_assoc($res); //AppGini internal function
    }
    
    public static function whereConstruct($settings)
    {
        ['tn' => $tn, 'id' => $id] = $settings;
        if (!$tn && !$id ) die('data settings info not valid');
        $key = getPKFieldName($tn); //AppGini internal function
        return $key ? "`{$key}`='{$id}'" : $key;
    }
}



// example use 3
// $settings = [
//     'tn' => 'products',
//     'id' => 2,
//     'fn' => false,
//     'debug' => true,
//     'error' => [],
// ];

// $data = getDataTable($settings);
// echo '"$data"';
// var_dump($data);
// echo '"$settings"';
// var_dump($settings);
