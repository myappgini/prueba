<?php
// This script and data application were generated by AppGini 5.98
// Download AppGini for free from https://bigprof.com/appgini/download/

	include_once(__DIR__ . '/lib.php');
	@include_once(__DIR__ . '/hooks/salary.php');
	include_once(__DIR__ . '/salary_dml.php');

	// mm: can the current member access this page?
	$perm = getTablePermissions('salary');
	if(!$perm['access']) {
		echo error_message($Translation['tableAccessDenied']);
		exit;
	}

	$x = new DataList;
	$x->TableName = 'salary';

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = [
		"`salary`.`id`" => "id",
		"IF(    CHAR_LENGTH(`contacto1`.`name`) || CHAR_LENGTH(`contacto1`.`user`), CONCAT_WS('',   `contacto1`.`name`, ' - ', `contacto1`.`user`), '') /* Contacto */" => "contacto",
		"`salary`.`monto`" => "monto",
		"`salary`.`mes`" => "mes",
		"IF(    CHAR_LENGTH(`contacto1`.`name`), CONCAT_WS('',   `contacto1`.`name`), '') /* Nombre */" => "nombre",
		"IF(    CHAR_LENGTH(`contacto1`.`rango`), CONCAT_WS('',   `contacto1`.`rango`), '') /* Rango */" => "rango",
		"IF(    CHAR_LENGTH(if(`contacto1`.`date`,date_format(`contacto1`.`date`,'%d/%m/%Y'),'')), CONCAT_WS('',   if(`contacto1`.`date`,date_format(`contacto1`.`date`,'%d/%m/%Y'),'')), '') /* Proxima fecha de pago */" => "date",
	];
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = [
		1 => '`salary`.`id`',
		2 => 2,
		3 => 3,
		4 => 4,
		5 => '`contacto1`.`name`',
		6 => '`contacto1`.`rango`',
		7 => 'date_format(`contacto1`.`date`,\'%d/%m/%Y\')',
	];

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = [
		"`salary`.`id`" => "id",
		"IF(    CHAR_LENGTH(`contacto1`.`name`) || CHAR_LENGTH(`contacto1`.`user`), CONCAT_WS('',   `contacto1`.`name`, ' - ', `contacto1`.`user`), '') /* Contacto */" => "contacto",
		"`salary`.`monto`" => "monto",
		"`salary`.`mes`" => "mes",
		"IF(    CHAR_LENGTH(`contacto1`.`name`), CONCAT_WS('',   `contacto1`.`name`), '') /* Nombre */" => "nombre",
		"IF(    CHAR_LENGTH(`contacto1`.`rango`), CONCAT_WS('',   `contacto1`.`rango`), '') /* Rango */" => "rango",
		"IF(    CHAR_LENGTH(if(`contacto1`.`date`,date_format(`contacto1`.`date`,'%d/%m/%Y'),'')), CONCAT_WS('',   if(`contacto1`.`date`,date_format(`contacto1`.`date`,'%d/%m/%Y'),'')), '') /* Proxima fecha de pago */" => "date",
	];
	// Fields that can be filtered
	$x->QueryFieldsFilters = [
		"`salary`.`id`" => "ID",
		"IF(    CHAR_LENGTH(`contacto1`.`name`) || CHAR_LENGTH(`contacto1`.`user`), CONCAT_WS('',   `contacto1`.`name`, ' - ', `contacto1`.`user`), '') /* Contacto */" => "Contacto",
		"`salary`.`monto`" => "Monto",
		"`salary`.`mes`" => "Mes",
		"IF(    CHAR_LENGTH(`contacto1`.`name`), CONCAT_WS('',   `contacto1`.`name`), '') /* Nombre */" => "Nombre",
		"IF(    CHAR_LENGTH(`contacto1`.`rango`), CONCAT_WS('',   `contacto1`.`rango`), '') /* Rango */" => "Rango",
		"IF(    CHAR_LENGTH(if(`contacto1`.`date`,date_format(`contacto1`.`date`,'%d/%m/%Y'),'')), CONCAT_WS('',   if(`contacto1`.`date`,date_format(`contacto1`.`date`,'%d/%m/%Y'),'')), '') /* Proxima fecha de pago */" => "Proxima fecha de pago",
	];

	// Fields that can be quick searched
	$x->QueryFieldsQS = [
		"`salary`.`id`" => "id",
		"IF(    CHAR_LENGTH(`contacto1`.`name`) || CHAR_LENGTH(`contacto1`.`user`), CONCAT_WS('',   `contacto1`.`name`, ' - ', `contacto1`.`user`), '') /* Contacto */" => "contacto",
		"`salary`.`monto`" => "monto",
		"`salary`.`mes`" => "mes",
		"IF(    CHAR_LENGTH(`contacto1`.`name`), CONCAT_WS('',   `contacto1`.`name`), '') /* Nombre */" => "nombre",
		"IF(    CHAR_LENGTH(`contacto1`.`rango`), CONCAT_WS('',   `contacto1`.`rango`), '') /* Rango */" => "rango",
		"IF(    CHAR_LENGTH(if(`contacto1`.`date`,date_format(`contacto1`.`date`,'%d/%m/%Y'),'')), CONCAT_WS('',   if(`contacto1`.`date`,date_format(`contacto1`.`date`,'%d/%m/%Y'),'')), '') /* Proxima fecha de pago */" => "date",
	];

	// Lookup fields that can be used as filterers
	$x->filterers = ['contacto' => 'Contacto', ];

	$x->QueryFrom = "`salary` LEFT JOIN `contacto` as contacto1 ON `contacto1`.`id`=`salary`.`contacto` ";
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
	$x->ScriptFileName = 'salary_view.php';
	$x->RedirectAfterInsert = 'salary_view.php?SelectedID=#ID#';
	$x->TableTitle = 'Salary';
	$x->TableIcon = 'table.gif';
	$x->PrimaryKey = '`salary`.`id`';

	$x->ColWidth = [150, 150, 150, 150, 150, 150, ];
	$x->ColCaption = ['Contacto', 'Monto', 'Mes', 'Nombre', 'Rango', 'Proxima fecha de pago', ];
	$x->ColFieldName = ['contacto', 'monto', 'mes', 'nombre', 'rango', 'date', ];
	$x->ColNumber  = [2, 3, 4, 5, 6, 7, ];

	// template paths below are based on the app main directory
	$x->Template = 'templates/salary_templateTV.html';
	$x->SelectedTemplate = 'templates/salary_templateTVS.html';
	$x->TemplateDV = 'templates/salary_templateDV.html';
	$x->TemplateDVP = 'templates/salary_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HasCalculatedFields = false;
	$x->AllowConsoleLog = false;
	$x->AllowDVNavigation = true;

	// hook: salary_init
	$render = true;
	if(function_exists('salary_init')) {
		$args = [];
		$render = salary_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: salary_header
	$headerCode = '';
	if(function_exists('salary_header')) {
		$args = [];
		$headerCode = salary_header($x->ContentType, getMemberInfo(), $args);
	}

	if(!$headerCode) {
		include_once(__DIR__ . '/header.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/header.php');
		echo str_replace('<%%HEADER%%>', ob_get_clean(), $headerCode);
	}

	echo $x->HTML;

	// hook: salary_footer
	$footerCode = '';
	if(function_exists('salary_footer')) {
		$args = [];
		$footerCode = salary_footer($x->ContentType, getMemberInfo(), $args);
	}

	if(!$footerCode) {
		include_once(__DIR__ . '/footer.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/footer.php');
		echo str_replace('<%%FOOTER%%>', ob_get_clean(), $footerCode);
	}
