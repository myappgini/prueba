<?php
// This script and data application were generated by AppGini 5.98
// Download AppGini for free from https://bigprof.com/appgini/download/

	include_once(__DIR__ . '/lib.php');
	@include_once(__DIR__ . '/hooks/products.php');
	include_once(__DIR__ . '/products_dml.php');

	// mm: can the current member access this page?
	$perm = getTablePermissions('products');
	if(!$perm['access']) {
		echo error_message($Translation['tableAccessDenied']);
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
		include_once(__DIR__ . '/header.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/header.php');
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
		include_once(__DIR__ . '/footer.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/footer.php');
		echo str_replace('<%%FOOTER%%>', ob_get_clean(), $footerCode);
	}
