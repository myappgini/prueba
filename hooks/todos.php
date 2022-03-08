<?php
	// For help on using hooks, please refer to https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks

	function todos_init(&$options, $memberInfo, &$args) {

		return TRUE;
	}

	function todos_header($contentType, $memberInfo, &$args) {
		$header='';

		switch($contentType) {
			case 'tableview':
				$header='';
				break;

			case 'detailview':
				$header='';
				break;

			case 'tableview+detailview':
				$header='';
				break;

			case 'print-tableview':
				$header='';
				break;

			case 'print-detailview':
				$header='';
				break;

			case 'filters':
				$header='';
				break;
		}

		return $header;
	}

	function todos_footer($contentType, $memberInfo, &$args) {
		$footer='';

		switch($contentType) {
			case 'tableview':
				$footer='';
				break;

			case 'detailview':
				$footer='';
				break;

			case 'tableview+detailview':
				$footer='';
				break;

			case 'print-tableview':
				$footer='';
				break;

			case 'print-detailview':
				$footer='';
				break;

			case 'filters':
				$footer='';
				break;
		}

		return $footer;
	}

	function todos_before_insert(&$data, $memberInfo, &$args) {

		include_once('field_permission/script.php');
	$notChanges = FieldsPermissions::update_fields_permission(pathinfo(__FILE__, PATHINFO_FILENAME), $memberInfo, $data);
	return  $notChanges;
	//return  TRUE;
	}

	function todos_after_insert($data, $memberInfo, &$args) {

		return TRUE;
	}

	function todos_before_update(&$data, $memberInfo, &$args) {

		include_once('field_permission/script.php');
	$notChanges = FieldsPermissions::update_fields_permission(pathinfo(__FILE__, PATHINFO_FILENAME), $memberInfo, $data);
	return  $notChanges;
	//return  TRUE;
	}

	function todos_after_update($data, $memberInfo, &$args) {

		return TRUE;
	}

	function todos_before_delete($selectedID, &$skipChecks, $memberInfo, &$args) {

		return TRUE;
	}

	function todos_after_delete($selectedID, $memberInfo, &$args) {

	}

	function todos_dv($selectedID, $memberInfo, &$html, &$args) {

		include_once('field_permission/script.php');
		$html .= FieldsPermissions::dv_field_permissions(pathinfo(__FILE__, PATHINFO_FILENAME), $memberInfo, $selectedID);
	}

	function todos_csv($query, $memberInfo, &$args) {

		return $query;
	}
	function todos_batch_actions(&$args) {

		return [];
	}
