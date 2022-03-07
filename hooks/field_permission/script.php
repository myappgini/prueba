<?php

class FieldsPermissions
{
    static $permissions = [
        "products" => [
            "name" => [
                "groups_disabled" => ["users"],
                "users_disabled" => ["ale"]
            ],
            "sss" => [
                "groups_disabled" => ["users"],
            ],
        ],
        "Contatcts" => [
            "name" => [
                "groups_disabled" => ["admin", "users"],
            ],
            "last_name" => [
                "groups_disabled" => ["users"],
            ],

        ],
    ];

    // format
    // static $permissions = [
    //     "tablename" => [
    //         "fieldname" => [
    //             "groups_disabled" => ["usergroup",...],
    //             "users_disabled" => ["usernamer",...]
    //         ],
    //         "other fieldname" => [
    //             "groups_disabled" => ["usergroup",...]
    //         ]
    //     ]
    // ];


    //esta función regresa un código js para bloquear los campos del lado del cliente
    //se utiliza en tablename_dv function
    static function dv_field_permissions($tn = false, $memberInfo)
    {
        $permissions = FieldsPermissions::$permissions;
        if (isset($permissions[$tn])) {
            $fields_table = get_table_fields($tn); //AppGini internal function
            foreach ($fields_table as $fn => $val) {
                if (array_key_exists($fn,  $permissions[$tn])) {
                    if (FieldsPermissions::check_permissions($permissions[$tn][$fn], $memberInfo)) {
                        $bloqued[] = "#{$fn}";
                    }
                }
            }
            ob_start();
?>
            <script>
                $j(function() {
                    $j('<?php echo implode(", ", $bloqued); ?>').attr('readonly', 'true');
                })
            </script>
<?php
            $h = ob_get_clean();
        }
        return $h;
    }
    //esta función verifica que no haya cambiado a la fuerza el valor del campo.
    //se utiliza en tablename_before_update function
    static function update_fields_permission($tn = false, $memberInfo, $data)
    {
        $notChanges = true;
        $permissions = FieldsPermissions::$permissions;
        if (isset($permissions[$tn])) {

            $fields_table = get_table_fields($tn); //AppGini internal function
            foreach ($fields_table as $fn => $val) {
                //verifica si unos de los campos está en la matriz de configuracion
                if (array_key_exists($fn,  $permissions[$tn])) {
                    //busca en los grupos bloqueados
                    if (FieldsPermissions::check_permissions($permissions[$tn][$fn], $memberInfo)) {
                        $where = FieldsPermissions::where_construct($tn, $data['selectedID']); //genera el where id dependiendo del campo ID
                        // get the database value
                        $old_val = sqlValue("SELECT {$fn} FROM {$tn} WHERE  {$where} "); //AppGini internal function
                        //compara el campo actual con el campo encontrado si son distintos termina y cancela UPDATE
                        $notChanges = $old_val == $data[$fn];
                        if (!$notChanges) {
                            break;
                        }
                    }
                }
            }
        }
        return  $notChanges;
    }

    private function check_permissions($data, $memberInfo)
    {
        return in_array($memberInfo["group"], $data['groups_disabled']) || in_array($memberInfo["username"], $data['users_disabled']);
    }

    private function where_construct($tn, $id)
    {
        $key = getPKFieldName($tn); //AppGini internal function
        return $key ? "`{$key}`='{$id}'" : $key;
    }
}
