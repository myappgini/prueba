<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php

    include "../../../lib.php";

    $table_fields = get_table_fields();
    foreach ($table_fields as $tn => $fields) {
        echo "<ul>";
        echo "<li>Table:--->".$tn ."</li>" ;
        foreach ($fields as $fn => $val){
            echo "<ul>";
                echo "<li>field:--->".$fn ."</li>" ;
                $val=array_merge($val,[
                    "groups_disabled" => [],
                    "users_disabled" => [],
                    "hidden" => true
                ]);
                print_r($val);
                echo "<br>";
            echo "</ul>";
        }
        echo "</ul>";
    }

    ?>
</body>
</html>