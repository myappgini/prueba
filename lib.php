<?php
// This script and data application were generated by AppGini 5.94
// Download AppGini for free from https://bigprof.com/appgini/download/


error_reporting(E_ERROR /*| E_WARNING*/ | E_PARSE);

if(!defined('datalist_db_encoding')) define('datalist_db_encoding', 'UTF-8');
if(!defined('maxSortBy')) define('maxSortBy', 4);
if(!defined('empty_lookup_value')) define('empty_lookup_value', '{empty_value}');

if(function_exists('date_default_timezone_set')) @date_default_timezone_set('America/Argentina/Buenos_Aires');

$GLOBALS['filter_operators'] = array(
	'equal-to' => '<=>',
	'not-equal-to' => '!=',
	'greater-than' => '>',
	'greater-than-or-equal-to' => '>=',
	'less-than' => '<',
	'less-than-or-equal-to' => '<=',
	'like' => 'like',
	'not-like' => 'not like',
	'is-empty' => 'isEmpty',
	'is-not-empty' => 'isNotEmpty'
);

$currDir = dirname(__FILE__);
// if hooks/__bootstrap.php is found, it's included before any other files
// to define the very first functions loaded by app. Useful for controlling
// session behavior, ... etc.
@include_once("$currDir/hooks/__bootstrap.php");

include_once("$currDir/settings-manager.php");
detect_config();
migrate_config();

include_once("$currDir/defaultLang.php");
include_once("$currDir/language.php");
$Translation = array_merge($TranslationEn, $Translation);

include_once("$currDir/db.php");
include_once("$currDir/ci_input.php");
include_once("$currDir/datalist.php");
include_once("$currDir/incCommon.php");
include_once("$currDir/admin/incFunctions.php");

checkAppRequirements();

	// detecting classes not included above
	@spl_autoload_register(function($class) {
		$app_dir = dirname(__FILE__);
		@include_once("{$app_dir}/resources/lib/{$class}.php");
	});

	ob_start();


	/* trim $_POST, $_GET, $_REQUEST */
	if(count($_POST)) $_POST = array_trim($_POST);
	if(count($_GET)) $_GET = array_trim($_GET);
	if(count($_REQUEST)) $_REQUEST = array_trim($_REQUEST);

	initSession();

	// check if membership system exists
	setupMembership();

	// silently apply db changes, if any
	@include_once("$currDir/updateDB.php");

	// include global hook functions
	@include_once("$currDir/hooks/__global.php");

	// do we have a login request?
	logInMember();

	// convert expanded sorting variables, if provided, to SortField and SortDirection
	$postedOrderBy = [];
	for($i = 0; $i < maxSortBy; $i++) {
		if(isset($_REQUEST["OrderByField$i"])) {
			$sd = ($_REQUEST["OrderDir$i"] == 'desc' ? 'desc' : 'asc');
			if($sfi = intval($_REQUEST["OrderByField$i"])) {
				$postedOrderBy[] = array($sfi => $sd);
			}
		}
	}
	if(count($postedOrderBy)) {
		$_REQUEST['SortField'] = '';
		$_REQUEST['SortDirection'] = '';
		foreach($postedOrderBy as $obi) {
			$sfi = ''; $sd = '';
			foreach($obi as $sfi => $sd);
			$_REQUEST['SortField'] .= "$sfi $sd,";
		}
		$_REQUEST['SortField'] = substr($_REQUEST['SortField'], 0, -2 - strlen($sd));
		$_REQUEST['SortDirection'] = $sd;
	} elseif($_REQUEST['apply_sorting']) {
		/* no sorting and came from filters page .. so clear sorting */
		$_REQUEST['SortField'] = $_REQUEST['SortDirection'] = '';
	}

	// include nav menu links
	@include_once("$currDir/hooks/links-navmenu.php");
