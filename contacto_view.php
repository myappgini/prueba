<?php
// This script and data application were generated by AppGini 5.96
// Download AppGini for free from https://bigprof.com/appgini/download/

	$currDir = dirname(__FILE__);
	include_once("{$currDir}/lib.php");
	@include_once("{$currDir}/hooks/contacto.php");
	include_once("{$currDir}/contacto_dml.php");

	// mm: can the current member access this page?
	$perm = getTablePermissions('contacto');
	if(!$perm['access']) {
		echo error_message($Translation['tableAccessDenied']);
		exit;
	}

	$x = new DataList;
	$x->TableName = 'contacto';

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = [
		"`contacto`.`id`" => "id",
		"`contacto`.`name`" => "name",
		"`contacto`.`user`" => "user",
		"`contacto`.`rango`" => "rango",
		"if(`contacto`.`date`,date_format(`contacto`.`date`,'%d/%m/%Y'),'')" => "date",
	];
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = [
		1 => '`contacto`.`id`',
		2 => 2,
		3 => 3,
		4 => 4,
		5 => '`contacto`.`date`',
	];

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = [
		"`contacto`.`id`" => "id",
		"`contacto`.`name`" => "name",
		"`contacto`.`user`" => "user",
		"`contacto`.`rango`" => "rango",
		"if(`contacto`.`date`,date_format(`contacto`.`date`,'%d/%m/%Y'),'')" => "date",
	];
	// Fields that can be filtered
	$x->QueryFieldsFilters = [
		"`contacto`.`id`" => "ID",
		"`contacto`.`name`" => "Name",
		"`contacto`.`user`" => "User",
		"`contacto`.`rango`" => "Rango",
		"`contacto`.`date`" => "prox fecha de pago",
	];

	// Fields that can be quick searched
	$x->QueryFieldsQS = [
		"`contacto`.`id`" => "id",
		"`contacto`.`name`" => "name",
		"`contacto`.`user`" => "user",
		"`contacto`.`rango`" => "rango",
		"if(`contacto`.`date`,date_format(`contacto`.`date`,'%d/%m/%Y'),'')" => "date",
	];

	// Lookup fields that can be used as filterers
	$x->filterers = [];

	$x->QueryFrom = "`contacto` ";
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
	$x->ScriptFileName = 'contacto_view.php';
	$x->RedirectAfterInsert = 'contacto_view.php?SelectedID=#ID#';
	$x->TableTitle = 'Contacto';
	$x->TableIcon = 'table.gif';
	$x->PrimaryKey = '`contacto`.`id`';

	$x->ColWidth = [150, 150, 150, 150, ];
	$x->ColCaption = ['Name', 'User', 'Rango', 'prox fecha de pago', ];
	$x->ColFieldName = ['name', 'user', 'rango', 'date', ];
	$x->ColNumber  = [2, 3, 4, 5, ];

	// template paths below are based on the app main directory
	$x->Template = 'templates/contacto_templateTV.html';
	$x->SelectedTemplate = 'templates/contacto_templateTVS.html';
	$x->TemplateDV = 'templates/contacto_templateDV.html';
	$x->TemplateDVP = 'templates/contacto_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HasCalculatedFields = false;
	$x->AllowConsoleLog = false;
	$x->AllowDVNavigation = true;

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, ['user', 'group'])) { $DisplayRecords = 'all'; }
	if($perm['view'] == 1 || ($perm['view'] > 1 && $DisplayRecords == 'user' && !$_REQUEST['NoFilter_x'])) { // view owner only
		$x->QueryFrom .= ', `membership_userrecords`';
		$x->QueryWhere = "WHERE `contacto`.`id`=`membership_userrecords`.`pkValue` AND `membership_userrecords`.`tableName`='contacto' AND LCASE(`membership_userrecords`.`memberID`)='" . getLoggedMemberID() . "'";
	} elseif($perm['view'] == 2 || ($perm['view'] > 2 && $DisplayRecords == 'group' && !$_REQUEST['NoFilter_x'])) { // view group only
		$x->QueryFrom .= ', `membership_userrecords`';
		$x->QueryWhere = "WHERE `contacto`.`id`=`membership_userrecords`.`pkValue` AND `membership_userrecords`.`tableName`='contacto' AND `membership_userrecords`.`groupID`='" . getLoggedGroupID() . "'";
	} elseif($perm['view'] == 3) { // view all
		// no further action
	} elseif($perm['view'] == 0) { // view none
		$x->QueryFields = ['Not enough permissions' => 'NEP'];
		$x->QueryFrom = '`contacto`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: contacto_init
	$render = true;
	if(function_exists('contacto_init')) {
		$args = [];
		$render = contacto_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: contacto_header
	$headerCode = '';
	if(function_exists('contacto_header')) {
		$args = [];
		$headerCode = contacto_header($x->ContentType, getMemberInfo(), $args);
	}

	if(!$headerCode) {
		include_once("{$currDir}/header.php"); 
	} else {
		ob_start();
		include_once("{$currDir}/header.php");
		echo str_replace('<%%HEADER%%>', ob_get_clean(), $headerCode);
	}

	echo $x->HTML;

	// hook: contacto_footer
	$footerCode = '';
	if(function_exists('contacto_footer')) {
		$args = [];
		$footerCode = contacto_footer($x->ContentType, getMemberInfo(), $args);
	}

	if(!$footerCode) {
		include_once("{$currDir}/footer.php"); 
	} else {
		ob_start();
		include_once("{$currDir}/footer.php");
		echo str_replace('<%%FOOTER%%>', ob_get_clean(), $footerCode);
	}
