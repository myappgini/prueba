<?php
// This script and data application were generated by AppGini 5.98
// Download AppGini for free from https://bigprof.com/appgini/download/

	include_once(__DIR__ . '/lib.php');

	handle_maintenance();

	header('Content-type: text/javascript; charset=' . datalist_db_encoding);

	$table_perms = getTablePermissions('salary');
	if(!$table_perms['access']) die('// Access denied!');

	$mfk = Request::val('mfk');
	$id = makeSafe(Request::val('id'));
	$rnd1 = intval(Request::val('rnd1')); if(!$rnd1) $rnd1 = '';

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
			$j('#nombre<?php echo $rnd1; ?>').html('<?php echo addslashes(str_replace(["\r", "\n"], '', safe_html($row['name']))); ?>&nbsp;');
			$j('#rango<?php echo $rnd1; ?>').html('<?php echo addslashes(str_replace(["\r", "\n"], '', safe_html($row['rango']))); ?>&nbsp;');
			$j('#date<?php echo $rnd1; ?>').html('<?php echo addslashes(str_replace(["\r", "\n"], '', safe_html($row['date']))); ?>&nbsp;');
			<?php
			break;


	}

?>