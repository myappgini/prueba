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
				`table_field` VARCHAR(40) NULL,
				`fieldstate` VARCHAR(40) NULL
			) CHARSET utf8",
			$silent, [
				"ALTER TABLE `table4` RENAME `db_field_permission`",
				"UPDATE `membership_userrecords` SET `tableName`='db_field_permission' WHERE `tableName`='table4'",
				"UPDATE `membership_userpermissions` SET `tableName`='db_field_permission' WHERE `tableName`='table4'",
				"UPDATE `membership_grouppermissions` SET `tableName`='db_field_permission' WHERE `tableName`='table4'",
				"ALTER TABLE db_field_permission ADD `field1` VARCHAR(40)",
				"ALTER TABLE `db_field_permission` CHANGE `field1` `ID_field_permission` VARCHAR(40) NULL ",
				"ALTER TABLE `db_field_permission` CHANGE `ID_field_permission` `ID_field_permissions` VARCHAR(40) NULL ",
				"ALTER TABLE db_field_permission ADD `field2` VARCHAR(40)",
				"ALTER TABLE `db_field_permission` CHANGE `field2` `groupID` VARCHAR(40) NULL ",
				"ALTER TABLE db_field_permission ADD `field3` VARCHAR(40)",
				"ALTER TABLE `db_field_permission` CHANGE `field3` `table_field` VARCHAR(40) NULL ",
				"ALTER TABLE db_field_permission ADD `field4` VARCHAR(40)",
				"ALTER TABLE `db_field_permission` CHANGE `field4` `fieldstate` VARCHAR(40) NULL ",
				"ALTER TABLE `db_field_permission` CHANGE `ID_field_permissions` `ID_field_permissions` VARCHAR(40) NOT NULL ",
				"ALTER TABLE `db_field_permission` ADD PRIMARY KEY (`ID_field_permissions`)",
			]
		);
		setupIndexes('db_field_permission', ['groupID','table_field',]);

		setupTable(
			'tmp_tables_fields', " 
			CREATE TABLE IF NOT EXISTS `tmp_tables_fields` ( 
				`table_filed` VARCHAR(40) NOT NULL,
				PRIMARY KEY (`table_filed`)
			) CHARSET utf8",
			$silent, [
				"ALTER TABLE `table5` RENAME `tmp_tables_fields`",
				"UPDATE `membership_userrecords` SET `tableName`='tmp_tables_fields' WHERE `tableName`='table5'",
				"UPDATE `membership_userpermissions` SET `tableName`='tmp_tables_fields' WHERE `tableName`='table5'",
				"UPDATE `membership_grouppermissions` SET `tableName`='tmp_tables_fields' WHERE `tableName`='table5'",
				"ALTER TABLE tmp_tables_fields ADD `field1` VARCHAR(40)",
				"ALTER TABLE `tmp_tables_fields` CHANGE `field1` `table_filed` VARCHAR(40) NULL ",
				"ALTER TABLE `tmp_tables_fields` CHANGE `table_filed` `table_filed` VARCHAR(40) NOT NULL ",
				"ALTER TABLE `tmp_tables_fields` ADD PRIMARY KEY (`table_filed`)",
			]
		);

		setupTable(
			'view_mebership_groups', " 
			CREATE TABLE IF NOT EXISTS `view_mebership_groups` ( 
				`groupID` VARCHAR(40) NOT NULL,
				PRIMARY KEY (`groupID`),
				`name` VARCHAR(40) NULL,
				`description` TEXT NULL,
				`allowSignup` VARCHAR(40) NULL,
				`needsApproval` VARCHAR(40) NULL
			) CHARSET utf8",
			$silent, [
				"ALTER TABLE `table6` RENAME `view_mebership_groups`",
				"UPDATE `membership_userrecords` SET `tableName`='view_mebership_groups' WHERE `tableName`='table6'",
				"UPDATE `membership_userpermissions` SET `tableName`='view_mebership_groups' WHERE `tableName`='table6'",
				"UPDATE `membership_grouppermissions` SET `tableName`='view_mebership_groups' WHERE `tableName`='table6'",
				"ALTER TABLE view_mebership_groups ADD `field1` VARCHAR(40)",
				"ALTER TABLE `view_mebership_groups` CHANGE `field1` `groupID` VARCHAR(40) NULL ",
				"ALTER TABLE view_mebership_groups ADD `field2` VARCHAR(40)",
				"ALTER TABLE `view_mebership_groups` CHANGE `field2` `name` VARCHAR(40) NULL ",
				"ALTER TABLE view_mebership_groups ADD `field3` VARCHAR(40)",
				"ALTER TABLE `view_mebership_groups` CHANGE `field3` `description` VARCHAR(40) NULL ",
				"ALTER TABLE `view_mebership_groups` CHANGE `comments` `comments` TEXT NULL ",
				"ALTER TABLE view_mebership_groups ADD `field4` VARCHAR(40)",
				"ALTER TABLE `view_mebership_groups` CHANGE `field4` `allowSingUp` VARCHAR(40) NULL ",
				"ALTER TABLE `view_mebership_groups` CHANGE `allowSingUp` `allowSignup` VARCHAR(40) NULL ",
				"ALTER TABLE view_mebership_groups ADD `field5` VARCHAR(40)",
				"ALTER TABLE `view_mebership_groups` CHANGE `field5` `needsApprlbals` VARCHAR(40) NULL ",
				"ALTER TABLE `view_mebership_groups` CHANGE `needsApprlbals` `needsApprobals` VARCHAR(40) NULL ",
				"ALTER TABLE `view_mebership_groups` CHANGE `needsApprobals` `needsApprovals` VARCHAR(40) NULL ",
				"ALTER TABLE `view_mebership_groups` CHANGE `needsApprovals` `needsApproval` VARCHAR(40) NULL ",
				"ALTER TABLE `view_mebership_groups` CHANGE `groupID` `groupID` VARCHAR(40) NOT NULL ",
				"ALTER TABLE `view_mebership_groups` ADD PRIMARY KEY (`groupID`)",
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
