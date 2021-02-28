<?php

/** Field Permissions (v1.2)
 * 2020-03-16, 2019-11-05
 * Olaf Nöhring
 * https://datenbank-projekt.de
 * 
 * This file contains functions to check for field permissions and react on unexpected values.
 * 
 * NOTE: YOU NEED TO USE AppGini Helper which can be obtained for small money from https://www.bizzworxx.de/en/appgini-helper/
 * 		 I DECIDED TO USE AppGini Helper AS IT IS A GREAT SCRIPT WHICH SUPPORTS ALL FIELD TYPES OF APPGINI.
 * 		 PLEASE TAKE A LOOK AND SUPPORT jsetzer WHO IS THE PROGRAMMER AND GREAT SUPPORTER OF ALL USERS IN THE APPGINI FORUM.
 */

//Write a log file
/*
if (!function_exists('write_log')) {
	include("hooks/write_log.php");
}
*/

/**
 * Function checks if user has limited permissions
 * @tablename What tablename needs to be checked
 * @memberID  For which user
 * @state     What should be checked? is_hidden | is_locked
 */

function check_field_permission($tablenam, $memberID, $state)
{
	$divider = '*';
	$list_fields = "";

	$groupID = getLoggedGroupID();

	$sql = "SELECT * FROM db_field_permission WHERE table_field LIKE '" . $tablenam . $divider . "%' AND fieldstate = '" . $state . "' AND groupID = " . $groupID . ";";

	$arr_table_field = array();
	$result = sql($sql, $eo);
	while ($row = db_fetch_assoc($result)) {
		$arr_table_field[] = str_replace($tablenam . $divider, "", $row['table_field']);
	}
	$list_fields = implode(",", $arr_table_field);

	return $list_fields;
}

/**
 * Function padds CSV list with additional characters
 * @field_list  CSV list
 * @additionalPadding Character to be used for padding
 */
function pad_field_permissions($field_list, $additionalPadding)
{
	$padded_field_list = "";
	$field_list = str_replace(',', $additionalPadding . ',' . $additionalPadding, $field_list);
	$field_list = $additionalPadding . $field_list . $additionalPadding;
	$padded_field_list = $field_list;
	return $padded_field_list;
}

/**
 * Lock record in use
 * future plan: make it possible to prevent editing when another user has requested this record
 * things to watch for: 
 * 		when did the other user request the same record (time)
 * 		once a user has clicked the save button the lock is removed again
 */
function record_is_locked_field_permissions($currentID)
{
}

/**
 * Function reads values of hidden and locked fields from database and fills those into the fields comming from the user.
 * This makes it impossible for the user to save other values for these fields than those that are already in the database.
 * There will be no more error, that a record could not be saved: Fields that do not have special field-permissions will be written back to the database as they come from the user.
 * @data	Array comming from AppGini
 * @memberInfo	Array comming from AppGini
 * @field_permission_tablenam		Name of the table the users tries to change a record in
 * @field_permission_tableID		Name of the primarykey field in the table the users tries to change a record in
 */
function check_BE_field_permissions(&$data, $memberInfo, $field_permission_tablenam, $field_permission_tableID)
{

	$myReturnValue = TRUE;								//set default value

	$clean_selectedID = makeSafe($data['selectedID']);	// which record are we testing?

	$fields_is_locked = "";
	$fields_is_hidden = "";

	$fields_is_locked = check_field_permission($field_permission_tablenam, getLoggedMemberID(), "locked");
	$fields_is_hidden = check_field_permission($field_permission_tablenam, getLoggedMemberID(), "hidden");

	if ($fields_is_locked <> "") {
		$fields_locked = explode(",", $fields_is_locked);
	}
	if ($fields_is_hidden <> "") {
		$fields_hidden = explode(",", $fields_is_hidden);
	}

	/*
	// Backup Code .. for now as of 2020-03-13
	$fields_is_special_permission = "";
	if (($fields_is_locked <> "") && ($fields_is_hidden <> "")) {
		$fields_is_special_permission = array_merge($fields_locked, $fields_hidden);
	} else {
		if ($fields_is_locked <> "") {
			$fields_is_special_permission = $fields_locked;
		} else {
			if ($fields_is_hidden <> "") {
				$fields_is_special_permission = $fields_hidden;
			}
		}
	}



	if ($fields_is_special_permission <> "") {

		$sql_fieldlist = implode(",", $fields_is_special_permission);

		if ($sql_fieldlist <> ",") {		// if at least one field in this table has a special permission - otherwiese: all ok and continue

			$sql_padded_fieldlist = pad_field_permissions($sql_fieldlist, '`');

			$sql = "SELECT $sql_padded_fieldlist FROM `$field_permission_tablenam` WHERE $field_permission_tableID = " . $clean_selectedID;

			$result_fieldlist = sql($sql, $eo);
			$row_result_fieldlist = db_fetch_assoc($result_fieldlist);

			$fields_cheating = array();		// fields in which unexpected values occur
			foreach ($fields_is_special_permission as $key => $value) {
				$clean_data_fieldvalue_new = makeSafe($data[$value]);
				$result = $row_result_fieldlist[$value];

				if ($result !== $clean_data_fieldvalue_new) {
					$fields_cheating[] = $value;
				}
			}

			if (count($fields_cheating) <> 0) {
				//this session variable is some extra: If you want to use custom errors, please check my solution here https://forums.appgini.com/phpbb/viewtopic.php?f=7&t=1740&p=10906#p10906
				$_SESSION['custom_err_msg'] = 'Edits rejected due to assumed cheating. The record can not be saved. Problems at: ' . implode(", ", $fields_cheating) . '';

				$myReturnValue = FALSE;		// some unexpected values, so we deny adding/updating this record
			}
		}
	}
	*/


	$fields_is_special_permission = "";
	if (($fields_is_locked <> "") && ($fields_is_hidden <> "")) {
		$fields_is_special_permission = array_merge($fields_locked, $fields_hidden);
	} else {
		if ($fields_is_locked <> "") {
			$fields_is_special_permission = $fields_locked;
		} else {
			if ($fields_is_hidden <> "") {
				$fields_is_special_permission = $fields_hidden;
			}
		}
	}


	if ($fields_is_special_permission <> "") {

		$sql_fieldlist = implode(",", $fields_is_special_permission);

		if ($sql_fieldlist <> ",") {		// if at least one field in this table has a special permission - otherwiese: all ok and continue

			$sql_padded_fieldlist = pad_field_permissions($sql_fieldlist, '`');

			$sql = "SELECT $sql_padded_fieldlist FROM `$field_permission_tablenam` WHERE $field_permission_tableID = '" . $clean_selectedID . "'";

			$result_fieldlist = sql($sql, $eo);
			$row_result_fieldlist = db_fetch_assoc($result_fieldlist);

			$fields_cheating = array();		// fields in which unexpected values occur
			foreach ($fields_is_special_permission as $key => $value) {
				$clean_data_fieldvalue_new = makeSafe($data[$value]);
				$result = $row_result_fieldlist[$value];

				if ($result !== $clean_data_fieldvalue_new) {
					$fields_cheating[] = $value;
				}

				//write value FROM database TO current value, so no cheating can be done at all
				$data[$value]  = $row_result_fieldlist[$value];
			}

			if (count($fields_cheating) <> 0) {
				//this session variable is some extra: If you want to use custom errors, please check my solution here https://forums.appgini.com/phpbb/viewtopic.php?f=7&t=1740&p=10906#p10906
				//$_SESSION['custom_err_msg'] = 'Edits rejected due to assumed cheating. The record can not be saved. Problems at: ' . implode(", ", $fields_cheating) . '';
				//$myReturnValue = FALSE;		// some unexpected values, so we deny adding/updating this record

				// error not shown as record can be saved
				$_SESSION['custom_err_msg'] = 'Unallowed edits rejected were rejected ' . implode(", ", $fields_cheating) . ', allowed changes accepted.';
				$myReturnValue = TRUE;		// some unexpected values, so we deny adding/updating this record
			}
		}
	}


	return $myReturnValue;
}


/** 
 * Get the database table field name from either label of table view field OR caption 
 * This function is needed as AG does not provide a reliable value of either the label or the database-field-name
 * @search_term - searchterm we need the database field name for. this might be either the label of the column in table view OR the database-field-name.
 * @fields_hidden - list of visible fields (in table view), holds the label of the column in table view
 * @fields_hidden_Name - list of visible fields (in table view), holds the real database-field-name
 */
function getRealDBFieldname($search_term, $fields_hidden, $fields_hidden_Name)
{
	$myReturnValue = 0;
	$position_in_array = -1;

	//search in db-field-names first
	$position_in_array = array_search($search_term, $fields_hidden);
	// DEBUG
	// write_log("search term : $search_term");
	// write_log("fields_hidden ...");
	// array_walk($fields_hidden,"showArrayContents");
	// write_log("fields_hidden_Name ...");
	// array_walk($fields_hidden_Name,"showArrayContents");
	
	if ($position_in_array > -1) {
		$myReturnValue = $search_term;
	} else {
		//search in labels from tableview 2nd
		$position_in_array = array_search($search_term, $fields_hidden_Name);
		if ($position_in_array > -1) {
			$myReturnValue = $fields_hidden[$position_in_array];
		} else {
			$myReturnValue = -2;
		}
	}
	return $myReturnValue;
}

?>