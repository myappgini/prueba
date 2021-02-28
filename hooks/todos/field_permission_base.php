<?php
/** Field Permissions (v1.12)
 * 2020-03-16; 2019-11-20; 2019-11-05
 * Olaf Nöhring
 * https://datenbank-projekt.de
 * 
 * This file contains the code that is used in the footer to make necessary adjustments of the look of the details form: lock or hide fields
 * 
 * NOTE: YOU NEED TO USE AppGini Helper which can be obtained for small money from https://www.bizzworxx.de/en/appgini-helper/
 * 		 I DECIDED TO USE AppGini Helper AS IT IS A GREAT SCRIPT WHICH SUPPORTS ALL FIELD TYPES OF APPGINI.
 * 		 PLEASE TAKE A LOOK AND SUPPORT jsetzer WHO IS THE PROGRAMMER AND GREAT SUPPORTER OF ALL USERS IN THE APPGINI FORUM.
 */

/*
 //For debugging / writing a log file
if (!function_exists('write_log')) {
	require("hooks/write_log.php");
}
*/

//###################################################################			
	//START Field-Permissions
	if (!function_exists('check_field_permission')) {
		include("hooks/field_permission_functions.php");	
	}

	$fields_is_locked = "";
	$fields_is_hidden = "";

	// check for locked fields only, if user has the ability to INSERT or EDIT the record
	$user_permissions = getTablePermissions($_SESSION['field_permission_tablenam']);
	if (($user_permissions['insert'] <> 0) || ($user_permissions['edit'] <> 0)){
		$fields_is_locked = check_field_permission($_SESSION['field_permission_tablenam'], getLoggedMemberID(), "locked");
	}

	// always check hidden fields
	$fields_is_hidden = check_field_permission($_SESSION['field_permission_tablenam'], getLoggedMemberID(), "hidden");

	$extraJS_Start = "\n<script>\n";
	$extraJS_End = "\n</script>\n";

// START AGHelper
	$extraJS_HideField="";	
	$extraJS_DisableField="";

	$extraJS_HiddenFieldAction = "";
	if ($fields_is_hidden <>""){
		$extraJS_HiddenFieldAction = 'new AppGiniFields([' . pad_field_permissions($fields_is_hidden,'"') . ']).hide();';
	}

	$extraJS_DisableFieldAction ="";	// 'new AppGiniFields([' . pad_field_permissions($fields_is_locked,'"') . ']).readonly(true);';
	
	$extraJS_DisableFieldTimer = "";
	if ($fields_is_locked <> "") {
	$extraJS_DisableFieldTimer = '
	var dv = new AppGiniDetailView();
	dv.ready(makeFieldReadonly);
	function makeFieldReadonly() {
	new AppGiniFields([' . pad_field_permissions($fields_is_locked,'"') . ']).readonly(true);
	}';
}
// END AGhelper

	// build complete JS to be included in case 'detailview' and case 'tableview+detailview' below
	// in the following switch, set 
	//		$footer = $extraJS_field_permission
	// to add the code to your html output

	$extraJS_field_permission = $extraJS_Start  . 
		$extraJS_DisableFieldAction  . "\n" .
		$extraJS_HiddenFieldAction . "\n" .
		$extraJS_DisableFieldTimer . "\n" .
		$extraJS_End;

if ($extraJS_field_permission == "<script>




	</script>"){
	$extraJS_field_permission ="";

}
	//END Field-Permissions
	//###################################################################	
?>