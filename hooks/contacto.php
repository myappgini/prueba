<?php
	// For help on using hooks, please refer to https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks

	function contacto_init(&$options, $memberInfo, &$args) {



		$options->ColCaption = ['Name', 'User', 'Rango', 'Prox. Fecha Pago', ];


		
					$_SESSION ['tablenam'] = $options->TableName; $_SESSION ['tableID'] = $options->PrimaryKey; $tableID = $_SESSION ['tableID'];


		

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

		return TRUE;
	}

	function contacto_after_insert($data, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-02-14 08:42:10 */
		table_after_change($_SESSION, $memberInfo, $data, 'INSERTION');
		/* End of Audit Log for AppGini code */


		return TRUE;
	}

	function contacto_before_update(&$data, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-02-14 08:42:10 */
		table_before_change($_SESSION, $data['selectedID']);
		/* End of Audit Log for AppGini code */


		return TRUE;
	}

	function contacto_after_update($data, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-02-14 08:42:10 */
		table_after_change($_SESSION, $memberInfo, $data, 'UPDATE');
		/* End of Audit Log for AppGini code */


		return TRUE;
	}

	function contacto_before_delete($selectedID, &$skipChecks, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-02-14 08:42:10 */
		table_before_change($_SESSION, $selectedID);
		/* End of Audit Log for AppGini code */


		return TRUE;
	}

	function contacto_after_delete($selectedID, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-02-14 08:42:10 */
		table_after_change($_SESSION, $memberInfo, $data, 'DELETION');
		/* End of Audit Log for AppGini code */


	}

	function contacto_dv($selectedID, $memberInfo, &$html, &$args) {

	}

	function contacto_csv($query, $memberInfo, &$args) {

		return $query;
	}
	function contacto_batch_actions(&$args) {

		return [];
	}
