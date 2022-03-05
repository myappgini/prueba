<?php
// This script and data application were generated by AppGini 5.98
// Download AppGini for free from https://bigprof.com/appgini/download/

	include_once(__DIR__ . '/lib.php');
	@include_once(__DIR__ . '/hooks/db_field_permission.php');
	include_once(__DIR__ . '/db_field_permission_dml.php');

	// mm: can the current member access this page?
	$perm = getTablePermissions('db_field_permission');
	if(!$perm['access']) {
		echo error_message($Translation['tableAccessDenied']);
		exit;
	}

	$x = new DataList;
	$x->TableName = 'db_field_permission';

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = [
		"`db_field_permission`.`ID_field_permissions`" => "ID_field_permissions",
		"IF(    CHAR_LENGTH(`view_membership_groups1`.`name`), CONCAT_WS('',   `view_membership_groups1`.`name`), '') /* GroupID */" => "groupID",
		"IF(    CHAR_LENGTH(`tmp_tables_fields1`.`table_filed`), CONCAT_WS('',   `tmp_tables_fields1`.`table_filed`), '') /* Table field */" => "table_field",
		"`db_field_permission`.`fieldstate`" => "fieldstate",
	];
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = [
		1 => 1,
		2 => '`view_membership_groups1`.`name`',
		3 => '`tmp_tables_fields1`.`table_filed`',
		4 => 4,
	];

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = [
		"`db_field_permission`.`ID_field_permissions`" => "ID_field_permissions",
		"IF(    CHAR_LENGTH(`view_membership_groups1`.`name`), CONCAT_WS('',   `view_membership_groups1`.`name`), '') /* GroupID */" => "groupID",
		"IF(    CHAR_LENGTH(`tmp_tables_fields1`.`table_filed`), CONCAT_WS('',   `tmp_tables_fields1`.`table_filed`), '') /* Table field */" => "table_field",
		"`db_field_permission`.`fieldstate`" => "fieldstate",
	];
	// Fields that can be filtered
	$x->QueryFieldsFilters = [
		"`db_field_permission`.`ID_field_permissions`" => "ID field permission",
		"IF(    CHAR_LENGTH(`view_membership_groups1`.`name`), CONCAT_WS('',   `view_membership_groups1`.`name`), '') /* GroupID */" => "GroupID",
		"IF(    CHAR_LENGTH(`tmp_tables_fields1`.`table_filed`), CONCAT_WS('',   `tmp_tables_fields1`.`table_filed`), '') /* Table field */" => "Table field",
		"`db_field_permission`.`fieldstate`" => "Fieldstate",
	];

	// Fields that can be quick searched
	$x->QueryFieldsQS = [
		"`db_field_permission`.`ID_field_permissions`" => "ID_field_permissions",
		"IF(    CHAR_LENGTH(`view_membership_groups1`.`name`), CONCAT_WS('',   `view_membership_groups1`.`name`), '') /* GroupID */" => "groupID",
		"IF(    CHAR_LENGTH(`tmp_tables_fields1`.`table_filed`), CONCAT_WS('',   `tmp_tables_fields1`.`table_filed`), '') /* Table field */" => "table_field",
		"`db_field_permission`.`fieldstate`" => "fieldstate",
	];

	// Lookup fields that can be used as filterers
	$x->filterers = ['groupID' => 'GroupID', 'table_field' => 'Table field', ];

	$x->QueryFrom = "`db_field_permission` LEFT JOIN `view_membership_groups` as view_membership_groups1 ON `view_membership_groups1`.`groupID`=`db_field_permission`.`groupID` LEFT JOIN `tmp_tables_fields` as tmp_tables_fields1 ON `tmp_tables_fields1`.`table_filed`=`db_field_permission`.`table_field` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->AllowSelection = 1;
	$x->HideTableView = ($perm['view'] == 0 ? 1 : 0);
	$x->AllowDelete = $perm['delete'];
	$x->AllowMassDelete = (getLoggedAdmin() !== false);
	$x->AllowInsert = $perm['insert'];
	$x->AllowUpdate = $perm['edit'];
	$x->SeparateDV = 1;
	$x->AllowDeleteOfParents = 0;
	$x->AllowFilters = 1;
	$x->AllowSavingFilters = (getLoggedAdmin() !== false);
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 1;
	$x->AllowPrintingDV = 1;
	$x->AllowCSV = 1;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation['quick search'];
	$x->ScriptFileName = 'db_field_permission_view.php';
	$x->RedirectAfterInsert = 'db_field_permission_view.php?SelectedID=#ID#';
	$x->TableTitle = 'Db field permissions';
	$x->TableIcon = 'table.gif';
	$x->PrimaryKey = '`db_field_permission`.`ID_field_permissions`';

	$x->ColWidth = [150, 150, 150, 150, ];
	$x->ColCaption = ['ID field permission', 'GroupID', 'Table field', 'Fieldstate', ];
	$x->ColFieldName = ['ID_field_permissions', 'groupID', 'table_field', 'fieldstate', ];
	$x->ColNumber  = [1, 2, 3, 4, ];

	// template paths below are based on the app main directory
	$x->Template = 'templates/db_field_permission_templateTV.html';
	$x->SelectedTemplate = 'templates/db_field_permission_templateTVS.html';
	$x->TemplateDV = 'templates/db_field_permission_templateDV.html';
	$x->TemplateDVP = 'templates/db_field_permission_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HasCalculatedFields = false;
	$x->AllowConsoleLog = false;
	$x->AllowDVNavigation = true;

	// hook: db_field_permission_init
	$render = true;
	if(function_exists('db_field_permission_init')) {
		$args = [];
		$render = db_field_permission_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: db_field_permission_header
	$headerCode = '';
	if(function_exists('db_field_permission_header')) {
		$args = [];
		$headerCode = db_field_permission_header($x->ContentType, getMemberInfo(), $args);
	}

	if(!$headerCode) {
		include_once(__DIR__ . '/header.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/header.php');
		echo str_replace('<%%HEADER%%>', ob_get_clean(), $headerCode);
	}

	echo $x->HTML;

	// hook: db_field_permission_footer
	$footerCode = '';
	if(function_exists('db_field_permission_footer')) {
		$args = [];
		$footerCode = db_field_permission_footer($x->ContentType, getMemberInfo(), $args);
	}

	if(!$footerCode) {
		include_once(__DIR__ . '/footer.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/footer.php');
		echo str_replace('<%%FOOTER%%>', ob_get_clean(), $footerCode);
	}
