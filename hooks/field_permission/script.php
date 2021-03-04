<?php
function settings_fields_permissions(){

    return( [
        "products"=>[
            "name"=> [
                "fn"=>"name",
                "groups_disabled"=>["Admins","users"],
            ],
            "sss"=>[
                "fn"=>"sss",
                "groups_disabled"=>["users"],
            ],
    
            ],
        "Contatcts"=>[
            "name"=>[
                "fn"=>"name",
                "groups_disabled"=>["admin","users"],
            ],
            "hola"=>[
                "fn"=>"hola",
                "groups_disabled"=>["users"],
            ],
    
            ],
    
        ]);
}

    function dv_field_permissions($tn=false, $memberInfo)
    {
        $h="";
        $setting_permissions = settings_fields_permissions();
        if (isset($setting_permissions[$tn])) {
            $fields_permission = array_column($setting_permissions[$tn], "fn");
            $fields_table = get_table_fields($tn);
            foreach ($fields_table as $fn => $value) {
                if (in_array($fn, $fields_permission)) {
                    $groups_diabled = $setting_permissions[$tn][$fn]['groups_disabled'] ;
                    $current_group = $memberInfo["group"];
                    if (in_array($current_group, $groups_diabled)) {
                        $bloqued[]="#{$fn}";
                    }
                }
            }
            $bloqued = implode($bloqued, ", ");
            ob_start; ?>
				<script>
					$j(function () {
						$j('<?php echo $bloqued; ?>').attr('readonly','true');
					})
				</script>
			<?php
            $h=ob_get_contents();
            ob_end_clean();
        }
        return $h;
    }

    function update_fields_permission($tn=false,$memberInfo)
    {
        $setting_permissions = settings_fields_permissions();
        $notChanges=true;
        if (isset($setting_permissions[$tn])) {
            $fields_permission = array_column($setting_permissions[$tn], "fn");
            $fields_table = get_table_fields($tn); //obtienen todos los nombre de campor de la tabla
            foreach ($fields_table as $fn => $value) {
                //verifica si unos de los campos está en la matriz de configuracion
                if (in_array($fn, $fields_permission)) {
                    //busca en los grupos bloqueados
                    $groups_diabled = $setting_permissions[$tn][$fn]['groups_disabled'] ;
                    $current_group = $memberInfo["group"];
                    if (in_array($current_group, $groups_diabled)) {
                        $where = where_construct($tn, $data['selectedID']);//genera el where id dependiendo del campo ID

                        $old_val = sqlValue("SELECT {$fn} FROM {$tn} WHERE  {$where} ");
                        //compara el campo actual con el campo encontrado si son distintos termina y cancela UPDATE
                        $notChanges = $old_val === $data[$fn];
                        if (!$notChanges) {
                            break;
                        }
                    }
                }
            }
        }
        return  $notChanges;
    }

    function where_construct($tn, $id)
    {
        $key = getPKFieldName($tn); //AppGini internal function
        return $key ? "`{$key}`='{$id}'" : $key;
    }
