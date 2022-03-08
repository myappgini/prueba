<?php
//Field Permissions hide / lock fields by usergroup and user
class FieldsPermissions
{
    static $permissions = [
        "products" => [
            "name" => [
                "groups_disabled" => ["users"],
                "users_disabled" => ["Ale"],
                "hidden" => true
            ],
            "due" => [
                "groups_disabled" => ["Admins"],
                "hidden" => true
            ],
        ],
        "contacto" => [
            "name" => [
                "groups_disabled" => ["Admins", "users"],
            ],
            "user" => [
                "groups_disabled" => ["users", "Admins"],
                "hidden" => false
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
    //             "groups_disabled" => ["usergroup",...],
    //             "hidden" => true
    //         ]
    //     ]
    // ];

    //this function returns a js code to block the client side fields
    //used in tablename_dv function
    static function dv_field_permissions($tn = false, $memberInfo)
    {
        $permissions = FieldsPermissions::$permissions[$tn];
        if (is_null($permissions)) return true;
        $fields_table = get_table_fields($tn); //AppGini internal function
        foreach ($fields_table as $fn => $val) {
            if (array_key_exists($fn,  $permissions)) {
                if (FieldsPermissions::check_permissions($permissions[$fn], $memberInfo)) {
                    $bloqued[] = "#{$fn}";
                    $permissions[$fn]['hidden'] && $hidden[] = ".form-group.{$tn}-{$fn}";
                }
            }
        }
        ob_start();
        ?>
        <script>
            $j(function() {
                $j('<?php echo implode(", ", $bloqued); ?>').attr('readonly', 'true');
                $j('<?php echo implode(", ", $hidden); ?>').hide();

            })
        </script>
        <?php
        return ob_get_clean();
    }
    //this function checks that the user does not try to force the value of the field. 
    //used in tablename_before_update function and tablename_before_insert 
    static function update_fields_permission($tn = false, $memberInfo, $data)
    {
        $permissions = FieldsPermissions::$permissions[$tn];
        if (is_null($permissions)) return true;
        $notChanges = true;
        $fields_table = get_table_fields($tn); //AppGini internal function
        //recorre los campos de la tabla
        foreach ($fields_table as $fn => $val) {
            //check if one of the fields is in the configuration array
            if (array_key_exists($fn,  $permissions)) {
                //search in blocked groups/users 
                if (FieldsPermissions::check_permissions($permissions[$fn], $memberInfo)) {
                    $where = FieldsPermissions::where_construct($tn, $data['selectedID']); // generate the where id depending on the ID field
                    // get the database value
                    $old_val = sqlValue("SELECT {$fn} FROM {$tn} WHERE  {$where} "); //AppGini internal function
                    // compare the current value with the found field if they are different terminate and cancel UPDATE/INSERT
                    $notChanges = $old_val == $data[$fn];
                    if (!$notChanges) break;
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
