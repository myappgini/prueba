<?php
	// check this file's MD5 to make sure it wasn't called before
	$prevMD5 = @file_get_contents(dirname(__FILE__) . '/setup.md5');
	$thisMD5 = md5(@file_get_contents(dirname(__FILE__) . '/updateDB.php'));

	// check if setup already run
	if($thisMD5 != $prevMD5) {
		// $silent is set if this file is included from setup.php
		if(!isset($silent)) $silent = true;

		// set up tables
		setupTable(
			'contacto', " 
			CREATE TABLE IF NOT EXISTS `contacto` ( 
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (`id`),
				`name` VARCHAR(40) NULL,
				`user` VARCHAR(40) NULL,
				`rango` VARCHAR(40) NULL,
				`date` DATE NULL
			) CHARSET utf8",
			$silent
		);

		setupTable(
			'salary', " 
			CREATE TABLE IF NOT EXISTS `salary` ( 
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (`id`),
				`contacto` INT UNSIGNED NULL,
				`monto` VARCHAR(40) NULL,
				`mes` VARCHAR(40) NULL,
				`nombre` INT UNSIGNED NULL,
				`rango` INT UNSIGNED NULL,
				`date` INT UNSIGNED NULL DEFAULT '1'
			) CHARSET utf8",
			$silent
		);
		setupIndexes('salary', ['contacto',]);

		setupTable(
			'products', " 
			CREATE TABLE IF NOT EXISTS `products` ( 
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (`id`),
				`name` VARCHAR(40) NULL,
				`uploads` TEXT NULL,
				`due` DATETIME NULL
			) CHARSET utf8",
			$silent
		);

		setupTable(
			'db_field_permission', " 
			CREATE TABLE IF NOT EXISTS `db_field_permission` ( 
				`ID_field_permissions` VARCHAR(40) NOT NULL,
				PRIMARY KEY (`ID_field_permissions`),
				`groupID` VARCHAR(40) NULL,
				`table_field` VARCHAR(200) NULL,
				`fieldstate` VARCHAR(50) NOT NULL
			) CHARSET utf8",
			$silent, [
				" ALTER TABLE `db_field_permission` CHANGE `fieldstate` `fieldstate` VARCHAR(40) NOT NULL ",
				" ALTER TABLE `db_field_permission` CHANGE `fieldstate` `fieldstate` VARCHAR(50) NOT NULL ",
			]
		);
		setupIndexes('db_field_permission', ['groupID','table_field',]);

		setupTable(
			'tmp_tables_fields', " 
			CREATE TABLE IF NOT EXISTS `tmp_tables_fields` ( 
				`table_filed` VARCHAR(200) NOT NULL,
				PRIMARY KEY (`table_filed`)
			) CHARSET utf8",
			$silent, [
				" ALTER TABLE `tmp_tables_fields` CHANGE `table_filed` `table_filed` VARCHAR(200) NOT NULL ",
			]
		);

		setupTable(
			'view_membership_groups', " 
			CREATE TABLE IF NOT EXISTS `view_membership_groups` ( 
				`groupID` VARCHAR(40) NOT NULL,
				PRIMARY KEY (`groupID`),
				`name` VARCHAR(50) NULL,
				`description` TEXT NULL,
				`allowSignup` VARCHAR(50) NULL,
				`needsApproval` VARCHAR(50) NULL
			) CHARSET utf8",
			$silent, [
				"ALTER TABLE `view_mebership_groups` RENAME `view_membership_groups`",
				"UPDATE `membership_userrecords` SET `tableName`='view_membership_groups' WHERE `tableName`='view_mebership_groups'",
				"UPDATE `membership_userpermissions` SET `tableName`='view_membership_groups' WHERE `tableName`='view_mebership_groups'",
				"UPDATE `membership_grouppermissions` SET `tableName`='view_membership_groups' WHERE `tableName`='view_mebership_groups'",
				" ALTER TABLE `view_membership_groups` CHANGE `name` `name` VARCHAR(50) NULL ",
				" ALTER TABLE `view_membership_groups` CHANGE `allowSignup` `allowSignup` VARCHAR(50) NULL ",
				" ALTER TABLE `view_membership_groups` CHANGE `needsApproval` `needsApproval` VARCHAR(50) NULL ",
			]
		);



		// save MD5
		@file_put_contents(dirname(__FILE__) . '/setup.md5', $thisMD5);
	}


	function setupIndexes($tableName, $arrFields) {
		if(!is_array($arrFields) || !count($arrFields)) return false;

		foreach($arrFields as $fieldName) {
			if(!$res = @db_query("SHOW COLUMNS FROM `$tableName` like '$fieldName'")) continue;
			if(!$row = @db_fetch_assoc($res)) continue;
			if($row['Key']) continue;

			@db_query("ALTER TABLE `$tableName` ADD INDEX `$fieldName` (`$fieldName`)");
		}
	}


	function setupTable($tableName, $createSQL = '', $silent = true, $arrAlter = '') {
		global $Translation;
		$oldTableName = '';
		ob_start();

		echo '<div style="padding: 5px; border-bottom:solid 1px silver; font-family: verdana, arial; font-size: 10px;">';

		// is there a table rename query?
		if(is_array($arrAlter)) {
			$matches = [];
			if(preg_match("/ALTER TABLE `(.*)` RENAME `$tableName`/i", $arrAlter[0], $matches)) {
				$oldTableName = $matches[1];
			}
		}

		if($res = @db_query("SELECT COUNT(1) FROM `$tableName`")) { // table already exists
			if($row = @db_fetch_array($res)) {
				echo str_replace(['<TableName>', '<NumRecords>'], [$tableName, $row[0]], $Translation['table exists']);
				if(is_array($arrAlter)) {
					echo '<br>';
					foreach($arrAlter as $alter) {
						if($alter != '') {
							echo "$alter ... ";
							if(!@db_query($alter)) {
								echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
								echo '<div class="text-danger">' . $Translation['mysql said'] . ' ' . db_error(db_link()) . '</div>';
							} else {
								echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
							}
						}
					}
				} else {
					echo $Translation['table uptodate'];
				}
			} else {
				echo str_replace('<TableName>', $tableName, $Translation['couldnt count']);
			}
		} else { // given tableName doesn't exist

			if($oldTableName != '') { // if we have a table rename query
				if($ro = @db_query("SELECT COUNT(1) FROM `$oldTableName`")) { // if old table exists, rename it.
					$renameQuery = array_shift($arrAlter); // get and remove rename query

					echo "$renameQuery ... ";
					if(!@db_query($renameQuery)) {
						echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
						echo '<div class="text-danger">' . $Translation['mysql said'] . ' ' . db_error(db_link()) . '</div>';
					} else {
						echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
					}

					if(is_array($arrAlter)) setupTable($tableName, $createSQL, false, $arrAlter); // execute Alter queries on renamed table ...
				} else { // if old tableName doesn't exist (nor the new one since we're here), then just create the table.
					setupTable($tableName, $createSQL, false); // no Alter queries passed ...
				}
			} else { // tableName doesn't exist and no rename, so just create the table
				echo str_replace("<TableName>", $tableName, $Translation["creating table"]);
				if(!@db_query($createSQL)) {
					echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
					echo '<div class="text-danger">' . $Translation['mysql said'] . db_error(db_link()) . '</div>';
				} else {
					echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
				}
			}
		}

		echo '</div>';

		$out = ob_get_clean();
		if(!$silent) echo $out;
	}
