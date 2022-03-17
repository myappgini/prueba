<?php
	// For help on using hooks, please refer to https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks

	function contacto_init(&$options, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-02-16 03:49:59 */
		$_SESSION ['tablenam'] = $options->TableName; $_SESSION ['tableID'] = $options->PrimaryKey;
		/* End of Audit Log for AppGini code */



		$options->ColCaption = ['Name', 'User', 'Rango', 'Prox. Fecha Pago', ];

		
		return TRUE;
	}

	function contacto_header($contentType, $memberInfo, &$args) {
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

	function contacto_footer($contentType, $memberInfo, &$args) {
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

	function contacto_before_insert(&$data, $memberInfo, &$args) {



		include_once('field_permission/script.php');
	$notChanges = FieldsPermissions::update_fields_permission(pathinfo(__FILE__, PATHINFO_FILENAME), $memberInfo, $data);
	return  $notChanges;
	//return  TRUE;
	}

	function contacto_after_insert($data, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-02-16 03:45:54 */
		table_after_change($_SESSION, $memberInfo, $data, 'INSERTION');
		/* End of Audit Log for AppGini code */



		return TRUE;
	}

	function contacto_before_update(&$data, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-02-16 04:33:59 */
		table_before_change($_SESSION, $data['selectedID']);
		/* End of Audit Log for AppGini code */

		include_once('field_permission/script.php');
		$notChanges = FieldsPermissions::update_fields_permission(pathinfo(__FILE__, PATHINFO_FILENAME), $memberInfo, $data);
		return  $notChanges;
		//return  TRUE;
	}

	function contacto_after_update($data, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-02-16 03:45:54 */
		table_after_change($_SESSION, $memberInfo, $data, 'UPDATE');
		/* End of Audit Log for AppGini code */



		return TRUE;
	}

	function contacto_before_delete($selectedID, &$skipChecks, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-02-16 04:44:11 */
		table_before_change($_SESSION, $selectedID);
		/* End of Audit Log for AppGini code */

		return TRUE;
	}

	function contacto_after_delete($selectedID, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-02-16 03:45:54 */
		table_after_change($_SESSION, $memberInfo, $selectedID, 'DELETION');
		/* End of Audit Log for AppGini code */



	}

	function contacto_dv($selectedID, $memberInfo, &$html, &$args) {

		include_once('field_permission/script.php');
		$html .= FieldsPermissions::dv_field_permissions(pathinfo(__FILE__, PATHINFO_FILENAME), $memberInfo, $selectedID);
	}

	function contacto_csv($query, $memberInfo, &$args) {

		return $query;
	}
	function contacto_batch_actions(&$args) {

		return [];
	}
