<?php
// This script and data application were generated by AppGini 5.96
// Download AppGini for free from https://bigprof.com/appgini/download/

	$currDir = dirname(__FILE__);
	include_once("$currDir/lib.php");

	handle_maintenance();

	header('Content-type: text/javascript; charset=' . datalist_db_encoding);

	$table_perms = getTablePermissions('salary');
	if(!$table_perms['access']) die('// Access denied!');

	$mfk = $_GET['mfk'];
	$id = makeSafe($_GET['id']);
	$rnd1 = intval($_GET['rnd1']); if(!$rnd1) $rnd1 = '';

	if(!$mfk) {
		die('// No js code available!');
	}

	switch($mfk) {

		case 'contacto':
			if(!$id) {
				?>
				$j('#nombre<?php echo $rnd1; ?>').html('&nbsp;');
				$j('#rango<?php echo $rnd1; ?>').html('&nbsp;');
				$j('#date<?php echo $rnd1; ?>').html('&nbsp;');
				<?php
				break;
			}
			$res = sql("SELECT `contacto`.`id` as 'id', `contacto`.`name` as 'name', `contacto`.`user` as 'user', `contacto`.`rango` as 'rango', if(`contacto`.`date`,date_format(`contacto`.`date`,'%d/%m/%Y'),'') as 'date' FROM `contacto`  WHERE `contacto`.`id`='{$id}' limit 1", $eo);
			$row = db_fetch_assoc($res);
			?>
			$j('#nombre<?php echo $rnd1; ?>').html('<?php echo addslashes(str_replace(array("\r", "\n"), '', nl2br($row['name']))); ?>&nbsp;');
			$j('#rango<?php echo $rnd1; ?>').html('<?php echo addslashes(str_replace(array("\r", "\n"), '', nl2br($row['rango']))); ?>&nbsp;');
			$j('#date<?php echo $rnd1; ?>').html('<?php echo addslashes(str_replace(array("\r", "\n"), '', nl2br($row['date']))); ?>&nbsp;');
			<?php
			break;


	}

?>