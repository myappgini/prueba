<?php
if (!function_exists('getMemberInfo')) {
    include(dirname(__FILE__)."/../../lib.php");
}

$mi = getMemberInfo();
$admin_config = config('adminConfig');
$guest = $admin_config['anonymousMember'];

if ($guest == $mi['username']) {
    die();
}

$libname="widgets";

?>
<link rel="stylesheet" href="hooks/<?php echo $libname; ?>/css/<?php echo $libname; ?>.css">
<script src="hooks/<?php echo $libname; ?>/js/<?php echo $libname; ?>.js"></script>