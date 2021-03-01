<?php
	// For help on using hooks, please refer to https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks

	function products_init(&$options, $memberInfo, &$args) {

		/* Inserted by Audit Log for AppGini on 2021-01-22 04:58:38 */
		$_SESSION ['tablenam'] = $options->TableName; $_SESSION ['tableID'] = $options->PrimaryKey; $tableID = $_SESSION ['tableID'];
		/* End of Audit Log for AppGini code */


		//$options->AllowFilters = 0;

		return TRUE;
	}

	function products_header($contentType, $memberInfo, &$args) {
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

	function products_footer($contentType, $memberInfo, &$args) {
		$footer='';


		switch($contentType) {
			case 'tableview':
				$footer='';
				$footer = $extraJS_field_permission;

				break;

			case 'detailview':
				$footer='';
				$footer = $extraJS_field_permission;

				break;

			case 'tableview+detailview':
				$footer='';
				$footer = $extraJS_field_permission;

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

	function products_before_insert(&$data, $memberInfo, &$args) {

		return TRUE;
	}

	function products_after_insert($data, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-02-14 08:42:10 */
		table_after_change($_SESSION, $memberInfo, $data, 'INSERTION');
		/* End of Audit Log for AppGini code */


		return TRUE;
	}

	function products_before_update(&$data, $memberInfo, &$args) {

		$notChanges=TRUE;
		include ('field_permission/script.php');//simple matriz de configuracón
		if (isset($setting_permissions['products'])){
			$fields_permission = array_column($setting_permissions['products'],"tn");
			$fields_table = get_table_fields('products'); //obtienen todos los nombre de campor de la tabla
			foreach ($fields_table as $fn => $value) {
				//verifica si unos de los campos está en la matriz de configuracion
				if (in_array($fn,$fields_permission)){
					//busca en los grupos bloqueados
					$groups_diabled = $setting_permissions['products'][$fn]['groups_disabled'] ;
					$current_group = $memberInfo["group"];
					if (in_array($current_group,$groups_diabled)){
						$old_val = sqlValue("SELECT {$fn} FROM products WHERE id = '{$data['selectedID']}' ");
						//compara el campo actual con el campo encontrado si son distintos termina y cancela UPDATE
						$notChanges = $old_val === $data[$fn];
						if (!$notChanges) break;
					}
				}
			}
		}
		return  $notChanges;
		//return  TRUE;
	}

	function products_after_update($data, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-02-14 08:42:10 */
		table_after_change($_SESSION, $memberInfo, $data, 'UPDATE');
		/* End of Audit Log for AppGini code */

		return TRUE;
	}

	function products_before_delete($selectedID, &$skipChecks, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-02-14 08:42:10 */
		table_before_change($_SESSION, $selectedID);
		/* End of Audit Log for AppGini code */


		return TRUE;
	}

	function products_after_delete($selectedID, $memberInfo, &$args) {
		/* Inserted by Audit Log for AppGini on 2021-02-16 03:18:47 */
		table_after_change($_SESSION, $memberInfo, $selectedID, 'DELETION');
		/* End of Audit Log for AppGini code */

	}

	function products_dv($selectedID, $memberInfo, &$html, &$args) {
		include ('field_permission/script.php');
		if (isset($setting_permissions['products'])){

			$fields_permission = array_column($setting_permissions['products'],"fn");
			$fields_table = get_table_fields('products');
			foreach ($fields_table as $fn => $value) {
				if (in_array($fn,$fields_permission)){
					$groups_diabled = $setting_permissions['products'][$fn]['groups_disabled'] ;
					$current_group = $memberInfo["group"];
					if (in_array($current_group,$groups_diabled)){
						$bloqued[]="#{$fn}";
					}
				}
			}
			$bloqued = implode($bloqued,", ");
			ob_start;
			?>
				<script>
					$j(function () {
						$j('<?php echo $bloqued; ?>').attr('readonly','true');
					})
				</script>
			<?php
			$h=ob_get_contents();
			ob_end_clean(); 
			$html .= $h;
		}
	}

	function products_csv($query, $memberInfo, &$args) {

		return $query;
	}
	function products_batch_actions(&$args) {

		return [];
	}
