<?php
// This script and data application were generated by AppGini 5.94
// Download AppGini for free from https://bigprof.com/appgini/download/

	$currDir = dirname(__FILE__);
	include_once("{$currDir}/lib.php");
	@include_once("{$currDir}/hooks/products.php");
	include_once("{$currDir}/products_dml.php");

	// mm: can the current member access this page?
	$perm = getTablePermissions('products');
	if(!$perm['access']) {
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout(function() { window.location = "index.php?signOut=1"; }, 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = 'products';

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = [
		"`products`.`id`" => "id",
		"`products`.`name`" => "name",
		"`products`.`uploads`" => "uploads",
		"if(`products`.`due`,date_format(`products`.`due`,'%d/%m/%Y %h:%i %p'),'')" => "due",
	];
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = [
		1 => '`products`.`id`',
		2 => 2,
		3 => 3,
		4 => '`products`.`due`',
	];

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = [
		"`products`.`id`" => "id",
		"`products`.`name`" => "name",
		"`products`.`uploads`" => "uploads",
		"if(`products`.`due`,date_format(`products`.`due`,'%d/%m/%Y %h:%i %p'),'')" => "due",
	];
	// Fields that can be filtered
	$x->QueryFieldsFilters = [
		"`products`.`id`" => "ID",
		"`products`.`name`" => "Name",
		"`products`.`uploads`" => "Uploads",
		"`products`.`due`" => "Due",
	];

	// Fields that can be quick searched
	$x->QueryFieldsQS = [
		"`products`.`id`" => "id",
		"`products`.`name`" => "name",
		"`products`.`uploads`" => "uploads",
		"if(`products`.`due`,date_format(`products`.`due`,'%d/%m/%Y %h:%i %p'),'')" => "due",
	];

	// Lookup fields that can be used as filterers
	$x->filterers = [];

	$x->QueryFrom = "`products` ";
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
	$x->ScriptFileName = 'products_view.php';
	$x->RedirectAfterInsert = 'products_view.php?SelectedID=#ID#';
	$x->TableTitle = 'Products';
	$x->TableIcon = 'table.gif';
	$x->PrimaryKey = '`products`.`id`';

	$x->ColWidth = [150, 150, ];
	$x->ColCaption = ['Name', 'Due', ];
	$x->ColFieldName = ['name', 'due', ];
	$x->ColNumber  = [2, 4, ];

	// template paths below are based on the app main directory
	$x->Template = 'templates/products_templateTV.html';
	$x->SelectedTemplate = 'templates/products_templateTVS.html';
	$x->TemplateDV = 'templates/products_templateDV.html';
	$x->TemplateDVP = 'templates/products_templateDVP.html';

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
		$x->QueryWhere = "WHERE `products`.`id`=`membership_userrecords`.`pkValue` AND `membership_userrecords`.`tableName`='products' AND LCASE(`membership_userrecords`.`memberID`)='" . getLoggedMemberID() . "'";
	} elseif($perm['view'] == 2 || ($perm['view'] > 2 && $DisplayRecords == 'group' && !$_REQUEST['NoFilter_x'])) { // view group only
		$x->QueryFrom .= ', `membership_userrecords`';
		$x->QueryWhere = "WHERE `products`.`id`=`membership_userrecords`.`pkValue` AND `membership_userrecords`.`tableName`='products' AND `membership_userrecords`.`groupID`='" . getLoggedGroupID() . "'";
	} elseif($perm['view'] == 3) { // view all
		// no further action
	} elseif($perm['view'] == 0) { // view none
		$x->QueryFields = ['Not enough permissions' => 'NEP'];
		$x->QueryFrom = '`products`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: products_init
	$render = true;
	if(function_exists('products_init')) {
		$args = [];
		$render = products_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: products_header
	$headerCode = '';
	if(function_exists('products_header')) {
		$args = [];
		$headerCode = products_header($x->ContentType, getMemberInfo(), $args);
	}

	if(!$headerCode) {
		include_once("{$currDir}/header.php"); 
	} else {
		ob_start();
		include_once("{$currDir}/header.php");
		echo str_replace('<%%HEADER%%>', ob_get_clean(), $headerCode);
	}

	echo $x->HTML;

	// hook: products_footer
	$footerCode = '';
	if(function_exists('products_footer')) {
		$args = [];
		$footerCode = products_footer($x->ContentType, getMemberInfo(), $args);
	}

	if(!$footerCode) {
		include_once("{$currDir}/footer.php"); 
	} else {
		ob_start();
		include_once("{$currDir}/footer.php");
		echo str_replace('<%%FOOTER%%>', ob_get_clean(), $footerCode);
	}
