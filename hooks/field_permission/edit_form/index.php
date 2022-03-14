<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permissions form</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</head>

<body>
    <div class="container-fluid">
        <?php
        include "../../../lib.php";

        $table_fields = get_table_fields();
        echo '<ul class="list-group" >';
        foreach ($table_fields as $tn => $fields) {
            echo '<li class="list-group-item" >Table:--->' . $tn;
            echo '<ul class="list-group" >';
            foreach ($fields as $fn => $val) {
                echo '<li class="list-group-item" >Field:--->' . $fn;
                $val = array_merge($val, [
                    "groups_disabled" => [],
                    "users_disabled" => [],
                    "hidden" => true
                ]);
                echo '<ul class="list-group" >';
                foreach ($val as $k => $v) {

                    echo '<li class="list-group-item" >attr:--->' . $k . " => " . $v;
                    echo '</li>';
                }
                echo '<li class="list-group-item" >attr:---> groups';
                echo '<select id="box" class="form-select" multiple aria-label="multiple select example">';
                $res = sql("select groupID, name, description from membership_groups ", $eo);
                while ($row = db_fetch_row($res)) {
                    $groupMembersCount = sqlValue("select count(1) from membership_users where groupID='$row[0]'");
                    echo '<option value="' . $row[1] . '">' . $row[1] . ' (' . $groupMembersCount . ' user/s) </option>';
                }
                echo '</select>
                            <button class="btn btn-primary myBtn-'.$tn.$fn.'">
                Get values
                </button>';
                echo '</li>';
                echo "</ul>";
                echo '</li>';
            }
            echo "</ul>";
            echo '</li>';
        }
        echo "</ul>";

        ?>
    </div>
</body>
<script>
    setTimeout(() => {
        var btn = document.getElementsByClassName('myBtn-contactoid');
        btn.addEventListener('click',
            () => {
                box = document.getElementById("#box")
                console.log(box.value)
            }, false)

    }, 500);
</script>

</html>