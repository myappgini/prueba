<?php
//Field Permissions Code
if (!function_exists('fill_tmp_tables_fields')) {
include("hooks/permissions/field_permission_tmp.php");
}

	// For help on using hooks, please refer to https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks

	function db_field_permission_init(&$options, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-03-04 10:40:51 */
		$_SESSION ['tablenam'] = $options->TableName; $_SESSION ['tableID'] = $options->PrimaryKey;
		/* End of Audit Log for AppGini code */

		fill_tmp_tables_fields();

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
		/* Inserted by Audit Log for AppGini on 2021-03-04 10:40:51 */
		table_after_change($_SESSION, $memberInfo, $data, 'INSERTION');
		/* End of Audit Log for AppGini code */


		return TRUE;
	}

	function db_field_permission_before_update(&$data, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-03-04 10:40:51 */
		table_before_change($_SESSION, $data['selectedID']);
		/* End of Audit Log for AppGini code */


		return TRUE;
	}

	function db_field_permission_after_update($data, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-03-04 10:40:51 */
		table_after_change($_SESSION, $memberInfo, $data, 'UPDATE');
		/* End of Audit Log for AppGini code */


		return TRUE;
	}

	function db_field_permission_before_delete($selectedID, &$skipChecks, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-03-04 10:40:51 */
		table_before_change($_SESSION, $selectedID);
		/* End of Audit Log for AppGini code */


		return TRUE;
	}

	function db_field_permission_after_delete($selectedID, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-03-04 10:40:51 */
		table_after_change($_SESSION, $memberInfo, $selectedID, 'DELETION');
		/* End of Audit Log for AppGini code */


	}

	function db_field_permission_dv($selectedID, $memberInfo, &$html, &$args) {

	}

	function db_field_permission_csv($query, $memberInfo, &$args) {

		return $query;
	}
	function db_field_permission_batch_actions(&$args) {

		return [];
	}
