<?php
	// For help on using hooks, please refer to https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks

	function db_field_permission_init(&$options, $memberInfo, &$args) {

		return TRUE;
	}

	function db_field_permission_header($contentType, $memberInfo, &$args) {
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

	function db_field_permission_footer($contentType, $memberInfo, &$args) {
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

	function db_field_permission_before_insert(&$data, $memberInfo, &$args) {

		return TRUE;
	}

	function db_field_permission_after_insert($data, $memberInfo, &$args) {

		return TRUE;
	}

	function db_field_permission_before_update(&$data, $memberInfo, &$args) {

		return TRUE;
	}

	function db_field_permission_after_update($data, $memberInfo, &$args) {

		return TRUE;
	}

	function db_field_permission_before_delete($selectedID, &$skipChecks, $memberInfo, &$args) {

		return TRUE;
	}

	function db_field_permission_after_delete($selectedID, $memberInfo, &$args) {

	}

	function db_field_permission_dv($selectedID, $memberInfo, &$html, &$args) {

	}

	function db_field_permission_csv($query, $memberInfo, &$args) {

		return $query;
	}
	function db_field_permission_batch_actions(&$args) {

		return [];
	}
