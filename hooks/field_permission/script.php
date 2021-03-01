<?php
// $setting_permissions = [
//     "TableName"=>[
//         "FieldName1"=> [
//             "fn"=>"FieldName1",
//             "groups_disabled"=>["group1","group2"],
//         ],
//         "Fieldname2"=>[
//             "fn"=>"FieldName2",
//             "groups_disabled"=>["group2"],
//         ],

//         ],
//     ];


$setting_permissions = [
    "products"=>[
        "name"=> [
            "fn"=>"name",
            "groups_disabled"=>["dmins","users"],
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

    ];