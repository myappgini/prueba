<?php
/** Field Permissions (v1.2)
 * 2020-03-16
 * Olaf Nöhring
 * https://datenbank-projekt.de
 * 
 * This file needs to be included in the _init function of each table where you want to use teh field-permissions extension
 * 
 * NOTE: YOU NEED TO USE AppGini Helper which can be obtained for small money from https://www.bizzworxx.de/en/appgini-helper/
 * 		 I DECIDED TO USE AppGini Helper AS IT IS A GREAT SCRIPT WHICH SUPPORTS ALL FIELD TYPES OF APPGINI.
 * 		 PLEASE TAKE A LOOK AND SUPPORT jsetzer WHO IS THE PROGRAMMER AND GREAT SUPPORTER OF ALL USERS IN THE APPGINI FORUM.
 */

	//##################################################################
	// START Field-Permissions
	//##################################################################
	$_SESSION['field_permission_tablenam'] = $options->TableName;	//Field-Permissions - for frontend check (and backend check)
	$_SESSION['field_permission_tableID'] = $options->PrimaryKey;	//Field-Permissions - backend check

	//------------------------------------------------------------
	// START Change of displayed fields in tableview
	// src: https://bigprof.com/appgini/tips-and-tutorials/modifying-sql-query-table-view-using-hooks

	// Define what should be shown in the hidden column instead of the real value
	// to display only the first four characters the field use this:
	$hidden_text = '▒▒▒▒▒▒';

	// This is the prefix (and postfix) of the hidden field label in the Filters of the table
	$unavailable_text_pre = '[-Unavailable: ';
	$unavailable_text_post = '-]';

	// -------------------------------------
	// ------ NO CHANGES NEEDED BELOW ------ 
	// -------------------------------------

	// pad tablename.fieldname combination for SQL
	$hidden_text = '\'' . $hidden_text . '\'';

	//Table and field will come from FIELD_PERMISSIONS table later
	$table_name = $_SESSION['field_permission_tablenam'];
	include ("field_permission_functions.php");
	$fields_is_hidden = check_field_permission($_SESSION['field_permission_tablenam'], getLoggedMemberID(), "hidden");
	//write_log("hidden field list: " . $fields_is_hidden);

	if ($fields_is_hidden <> "") {

		// START read base values that might be changed below. needed to be repeated for every field!
		$old_options = $options->QueryFieldsTV;
		$old_optionsCSV = $options->QueryFieldsCSV;
		$old_optionsFilter = $options->QueryFieldsFilters;
		$old_optionsQuickSearch = $options->QueryFieldsQS;

		$old_optionsColWidth = $options->ColWidth;
		$old_optionsColCaption = $options->ColCaption;
		$old_optionsColFieldName = $options->ColFieldName;
		$old_optionsColNumber = $options->ColNumber;
		// END read base values that might be changed below

		// START Count fields in table view
		$fields_count = count($old_optionsColFieldName);
		// END Count fields in table view

		$fields_hidden = "";
		$fields_hidden = explode(",", $fields_is_hidden);

		// START get captions from fieldnames for FILTER and QUIUCKSEARCH 		
		// Why? build an array to use a similar approach as for the real captions (View and CSV export)
		unset($fields_hidden_Name);
		foreach ($fields_hidden as $key => $value) {
			$position_in_array = array_search($value, $old_optionsColFieldName);	//at which position is $value (which contains the database field name found in the list)
			$fields_hidden_Name[] = $old_optionsColCaption[$position_in_array];		//fill the lablel of the column in table view into the new array which stands at the same array position (but a different array) as the actual database-table-column-name. array $fields_hidden_Name is needed to search in FILTER
		}
		// END get captions from fieldnames for FILTER and QUIUCKSEARCH 		

		// START general table view	
		// use: ColFieldName	
		$counter = 0;
		foreach ($old_options as $field => $caption) {

			// this $replacement_key_text is needed to generate a unique key for the array (and each field in the table)
			$replacement_key_text = 'IF (`' . $table_name . '`.`' . $caption . '` IS NULL, ' . $hidden_text . ', ' . $hidden_text . ')';

			if (in_array($caption, $fields_hidden)) {
				$new_options[$replacement_key_text] = $caption;		// Content of column
			} else {
				$new_options[$field] = $caption;						// Content of column
			}

			$options->QueryFieldsTV = $new_options;
			$counter++;
		}
		// END general table view

		// START CSV table view
		//use: ColFieldName		
		foreach ($old_optionsCSV as $field => $caption) {
			$replacement_key_text = 'IF (`' . $table_name . '`.`' . $caption . '` IS NULL, ' . $hidden_text . ', ' . $hidden_text . ')';
			if (in_array($caption, $fields_hidden)) {
				$new_optionsCSV[$replacement_key_text] = $caption;
			} else {
				$new_optionsCSV[$field] = $caption;
			}
			$options->QueryFieldsCSV = $new_optionsCSV;
		}
		// END CSV table view

		// START FILTER table view
		//used: ColCaption		
		foreach ($old_optionsFilter as $field => $caption) {
			$replacement_key_text = 'IF (`' . $table_name . '`.`' . $caption . '` IS NULL, ' . $hidden_text . ', ' . $hidden_text . ')';
			if (in_array($caption, $fields_hidden_Name)) {		//attention: here the array $fields_hidden_Name (which contains LABELS form the table view is searched, NOT $fields_hidden which has been used for general table view and csv export)
				$new_optionsFilter[$replacement_key_text] = $unavailable_text_pre . $caption . $unavailable_text_post;
			} else {
				$new_optionsFilter[$field] = $caption;
			}
			$options->QueryFieldsFilters = $new_optionsFilter;
		}
		// END FILTER table view

		// START QuickSearch table view
		//used: ColCaption OR ColFieldName (!!!! why this AG ???)
		foreach ($old_optionsQuickSearch as $field => $caption) {
			$caption = getRealDBFieldname($caption, $old_optionsColFieldName, $old_optionsColCaption);
			$replacement_key_text = 'IF (`' . $table_name . '`.`' . $caption . '` IS NULL, ' . $hidden_text . ', ' . $hidden_text . ')';

			if ((in_array($caption, $fields_hidden)) || (in_array($caption, $fields_hidden_Name))) {
				$new_optionsQuickSearch[$replacement_key_text] = $caption;
			} else {
				$new_optionsQuickSearch[$field] = $caption;
			}
			$options->QueryFieldsQS = $new_optionsQuickSearch;
		}
		// END QuickSearch table view
	}

	// END Change of displayed fields in tableview
	//------------------------------------------------------------
	// END Field Permissions
	//##################################################################
?>	