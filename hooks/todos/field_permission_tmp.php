<?php
/** Field Permissions (v1.0)
 * 2019-11-05
 * Olaf Nöhring
 * https://datenbank-projekt.de
 * 
 * fill_tmp_tables_fields
 * This function reads all tables with all columns from the database and writes them to a tmp table to be used as drop down in field permissions definition.
 * This function needs to be called from the _init function in this module.
 * 
 * NOTE: YOU NEED TO USE AppGini Helper which can be obtained for small money from https://www.bizzworxx.de/en/appgini-helper/
 * 		 I DECIDED TO USE AppGini Helper AS IT IS A GREAT SCRIPT WHICH SUPPORTS ALL FIELD TYPES OF APPGINI.
 * 		 PLEASE TAKE A LOOK AND SUPPORT jsetzer WHO IS THE PROGRAMMER AND GREAT SUPPORTER OF ALL USERS IN THE APPGINI FORUM.
 */

function fill_tmp_tables_fields(){
	
	$divider = '*';

	//clear tmp_tables_fields
	$sql_d = "DELETE FROM tmp_tables_fields WHERE 1;";
	$result = sqlvalue($sql_d);

	$hooks_dir = dirname(__FILE__);
	require("$hooks_dir/../config.php");
	$db_names = "Tables_in_" . $dbDatabase;
	$sql_base = "INSERT INTO tmp_tables_fields SET table_field=@new_record@"; //, tablename=@tabnames@, fieldname=@colnames@";

	// GET ALL TABLES IN DATABASE
	$tabnames = array();
	$i = 0;
	$query_tablenames = sql("SHOW FULL TABLES WHERE table_type = 'BASE TABLE';", $eo);
	while ($result_tablenames = db_fetch_assoc($query_tablenames)) {
		$tabnames[$i] = $result_tablenames[$db_names];

		// GET ALL COLUMN NAMES IN THE TABLE
		$colnames = array();
		$j = 0;
		$query_fields = sql("SHOW COLUMNS FROM " . $tabnames[$i], $eo);

		while ($result_fields = db_fetch_assoc($query_fields)) {
			$colnames[$j] = $result_fields['Field'];
			//write new tablename*fieldname to temporary table to be used in lookup
			$new_record = $tabnames[$i] . $divider . $colnames[$j];
			//prepare INSERT SQL
			$sql_i = str_replace("@new_record@", '"' . $new_record . '"', $sql_base);
			$sql_i = $sql_i . ";";		//add closing ;
			$result_new_record = sqlvalue($sql_i);
			$j++;
		};
		$i++;
	};
}
?>	