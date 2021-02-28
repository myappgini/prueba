<?php

// Data functions (insert, update, delete, form) for table db_field_permission

// This script and data application were generated by AppGini 5.94
// Download AppGini for free from https://bigprof.com/appgini/download/

function db_field_permission_insert(&$error_message = '') {
	global $Translation;

	// mm: can member insert record?
	$arrPerm = getTablePermissions('db_field_permission');
	if(!$arrPerm['insert']) return false;

	$data = [
		'ID_field_permissions' => Request::val('ID_field_permissions', ''),
		'groupID' => Request::val('groupID', ''),
		'table_field' => Request::val('table_field', ''),
		'fieldstate' => Request::val('fieldstate', ''),
	];

	if($data['ID_field_permissions'] === '') {
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'ID field permission': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">' . $Translation['< back'] . '</a></div>';
		exit;
	}

	// hook: db_field_permission_before_insert
	if(function_exists('db_field_permission_before_insert')) {
		$args = [];
		if(!db_field_permission_before_insert($data, getMemberInfo(), $args)) {
			if(isset($args['error_message'])) $error_message = $args['error_message'];
			return false;
		}
	}

	$error = '';
	// set empty fields to NULL
	$data = array_map(function($v) { return ($v === '' ? NULL : $v); }, $data);
	insert('db_field_permission', backtick_keys_once($data), $error);
	if($error)
		die("{$error}<br><a href=\"#\" onclick=\"history.go(-1);\">{$Translation['< back']}</a>");

	$recID = $data['ID_field_permissions'];

	update_calc_fields('db_field_permission', $recID, calculated_fields()['db_field_permission']);

	// hook: db_field_permission_after_insert
	if(function_exists('db_field_permission_after_insert')) {
		$res = sql("SELECT * FROM `db_field_permission` WHERE `ID_field_permissions`='" . makeSafe($recID, false) . "' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)) {
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=[];
		if(!db_field_permission_after_insert($data, getMemberInfo(), $args)) { return $recID; }
	}

	// mm: save ownership data
	set_record_owner('db_field_permission', $recID, getLoggedMemberID());

	// if this record is a copy of another record, copy children if applicable
	if(!empty($_REQUEST['SelectedID'])) db_field_permission_copy_children($recID, $_REQUEST['SelectedID']);

	return $recID;
}

function db_field_permission_copy_children($destination_id, $source_id) {
	global $Translation;
	$requests = []; // array of curl handlers for launching insert requests
	$eo = ['silentErrors' => true];
	$safe_sid = makeSafe($source_id);

	// launch requests, asynchronously
	curl_batch($requests);
}

function db_field_permission_delete($selected_id, $AllowDeleteOfParents = false, $skipChecks = false) {
	// insure referential integrity ...
	global $Translation;
	$selected_id = makeSafe($selected_id);

	// mm: can member delete record?
	if(!check_record_permission('db_field_permission', $selected_id, 'delete')) {
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: db_field_permission_before_delete
	if(function_exists('db_field_permission_before_delete')) {
		$args = [];
		if(!db_field_permission_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'] . (
				!empty($args['error_message']) ?
					'<div class="text-bold">' . strip_tags($args['error_message']) . '</div>'
					: '' 
			);
	}

	sql("DELETE FROM `db_field_permission` WHERE `ID_field_permissions`='{$selected_id}'", $eo);

	// hook: db_field_permission_after_delete
	if(function_exists('db_field_permission_after_delete')) {
		$args = [];
		db_field_permission_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("DELETE FROM `membership_userrecords` WHERE `tableName`='db_field_permission' AND `pkValue`='{$selected_id}'", $eo);
}

function db_field_permission_update(&$selected_id, &$error_message = '') {
	global $Translation;

	// mm: can member edit record?
	if(!check_record_permission('db_field_permission', $selected_id, 'edit')) return false;

	$data = [
		'ID_field_permissions' => Request::val('ID_field_permissions', ''),
		'groupID' => Request::val('groupID', ''),
		'table_field' => Request::val('table_field', ''),
		'fieldstate' => Request::val('fieldstate', ''),
	];

	if($data['ID_field_permissions'] === '') {
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'ID field permission': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">' . $Translation['< back'] . '</a></div>';
		exit;
	}
	// get existing values
	$old_data = getRecord('db_field_permission', $selected_id);
	if(is_array($old_data)) {
		$old_data = array_map('makeSafe', $old_data);
		$old_data['selectedID'] = makeSafe($selected_id);
	}

	$data['selectedID'] = makeSafe($selected_id);

	// hook: db_field_permission_before_update
	if(function_exists('db_field_permission_before_update')) {
		$args = ['old_data' => $old_data];
		if(!db_field_permission_before_update($data, getMemberInfo(), $args)) {
			if(isset($args['error_message'])) $error_message = $args['error_message'];
			return false;
		}
	}

	$set = $data; unset($set['selectedID']);
	foreach ($set as $field => $value) {
		$set[$field] = ($value !== '' && $value !== NULL) ? $value : NULL;
	}

	if(!update(
		'db_field_permission', 
		backtick_keys_once($set), 
		['`ID_field_permissions`' => $selected_id], 
		$error_message
	)) {
		echo $error_message;
		echo '<a href="db_field_permission_view.php?SelectedID=' . urlencode($selected_id) . "\">{$Translation['< back']}</a>";
		exit;
	}

	$data['selectedID'] = $data['ID_field_permissions'];
	$newID = $data['ID_field_permissions'];

	$eo = ['silentErrors' => true];

	update_calc_fields('db_field_permission', $data['selectedID'], calculated_fields()['db_field_permission']);

	// hook: db_field_permission_after_update
	if(function_exists('db_field_permission_after_update')) {
		$res = sql("SELECT * FROM `db_field_permission` WHERE `ID_field_permissions`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)) $data = array_map('makeSafe', $row);

		$data['selectedID'] = $data['ID_field_permissions'];
		$args = ['old_data' => $old_data];
		if(!db_field_permission_after_update($data, getMemberInfo(), $args)) return;
	}

	// mm: update ownership data
	sql("UPDATE `membership_userrecords` SET `dateUpdated`='" . time() . "', `pkValue`='{$data['ID_field_permissions']}' WHERE `tableName`='db_field_permission' AND `pkValue`='" . makeSafe($selected_id) . "'", $eo);

	// if PK value changed, update $selected_id
	$selected_id = $newID;
}

function db_field_permission_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = '') {
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm = getTablePermissions('db_field_permission');
	if(!$arrPerm['insert'] && $selected_id=='') { return ''; }
	$AllowInsert = ($arrPerm['insert'] ? true : false);
	// print preview?
	$dvprint = false;
	if($selected_id && $_REQUEST['dvprint_x'] != '') {
		$dvprint = true;
	}

	$filterer_groupID = thisOr($_REQUEST['filterer_groupID'], '');
	$filterer_table_field = thisOr($_REQUEST['filterer_table_field'], '');

	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: groupID
	$combo_groupID = new DataCombo;
	// combobox: table_field
	$combo_table_field = new DataCombo;

	if($selected_id) {
		// mm: check member permissions
		if(!$arrPerm['view']) return '';

		// mm: who is the owner?
		$ownerGroupID = sqlValue("SELECT `groupID` FROM `membership_userrecords` WHERE `tableName`='db_field_permission' AND `pkValue`='" . makeSafe($selected_id) . "'");
		$ownerMemberID = sqlValue("SELECT LCASE(`memberID`) FROM `membership_userrecords` WHERE `tableName`='db_field_permission' AND `pkValue`='" . makeSafe($selected_id) . "'");

		if($arrPerm['view'] == 1 && getLoggedMemberID() != $ownerMemberID) return '';
		if($arrPerm['view'] == 2 && getLoggedGroupID() != $ownerGroupID) return '';

		// can edit?
		$AllowUpdate = 0;
		if(($arrPerm['edit'] == 1 && $ownerMemberID == getLoggedMemberID()) || ($arrPerm['edit'] == 2 && $ownerGroupID == getLoggedGroupID()) || $arrPerm['edit'] == 3) {
			$AllowUpdate = 1;
		}

		$res = sql("SELECT * FROM `db_field_permission` WHERE `ID_field_permissions`='" . makeSafe($selected_id) . "'", $eo);
		if(!($row = db_fetch_array($res))) {
			return error_message($Translation['No records found'], 'db_field_permission_view.php', false);
		}
		$combo_groupID->SelectedData = $row['groupID'];
		$combo_table_field->SelectedData = $row['table_field'];
		$urow = $row; /* unsanitized data */
		$hc = new CI_Input(datalist_db_encoding);
		$row = $hc->xss_clean($row); /* sanitize data */
	} else {
		$combo_groupID->SelectedData = $filterer_groupID;
		$combo_table_field->SelectedData = $filterer_table_field;
	}
	$combo_groupID->HTML = '<span id="groupID-container' . $rnd1 . '"></span><input type="hidden" name="groupID" id="groupID' . $rnd1 . '" value="' . html_attr($combo_groupID->SelectedData) . '">';
	$combo_groupID->MatchText = '<span id="groupID-container-readonly' . $rnd1 . '"></span><input type="hidden" name="groupID" id="groupID' . $rnd1 . '" value="' . html_attr($combo_groupID->SelectedData) . '">';
	$combo_table_field->HTML = '<span id="table_field-container' . $rnd1 . '"></span><input type="hidden" name="table_field" id="table_field' . $rnd1 . '" value="' . html_attr($combo_table_field->SelectedData) . '">';
	$combo_table_field->MatchText = '<span id="table_field-container-readonly' . $rnd1 . '"></span><input type="hidden" name="table_field" id="table_field' . $rnd1 . '" value="' . html_attr($combo_table_field->SelectedData) . '">';

	ob_start();
	?>

	<script>
		// initial lookup values
		AppGini.current_groupID__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['groupID'] : $filterer_groupID); ?>"};
		AppGini.current_table_field__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['table_field'] : $filterer_table_field); ?>"};

		jQuery(function() {
			setTimeout(function() {
				if(typeof(groupID_reload__RAND__) == 'function') groupID_reload__RAND__();
				if(typeof(table_field_reload__RAND__) == 'function') table_field_reload__RAND__();
			}, 50); /* we need to slightly delay client-side execution of the above code to allow AppGini.ajaxCache to work */
		});
		function groupID_reload__RAND__() {
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint) { ?>

			$j("#groupID-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c) {
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_groupID__RAND__.value, t: 'db_field_permission', f: 'groupID' },
						success: function(resp) {
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="groupID"]').val(resp.results[0].id);
							$j('[id=groupID-container-readonly__RAND__]').html('<span id="groupID-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=view_mebership_groups_view_parent]').hide(); } else { $j('.btn[id=view_mebership_groups_view_parent]').show(); }


							if(typeof(groupID_update_autofills__RAND__) == 'function') groupID_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term) { return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 5,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page) { return { s: term, p: page, t: 'db_field_permission', f: 'groupID' }; },
					results: function(resp, page) { return resp; }
				},
				escapeMarkup: function(str) { return str; }
			}).on('change', function(e) {
				AppGini.current_groupID__RAND__.value = e.added.id;
				AppGini.current_groupID__RAND__.text = e.added.text;
				$j('[name="groupID"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=view_mebership_groups_view_parent]').hide(); } else { $j('.btn[id=view_mebership_groups_view_parent]').show(); }


				if(typeof(groupID_update_autofills__RAND__) == 'function') groupID_update_autofills__RAND__();
			});

			if(!$j("#groupID-container__RAND__").length) {
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_groupID__RAND__.value, t: 'db_field_permission', f: 'groupID' },
					success: function(resp) {
						$j('[name="groupID"]').val(resp.results[0].id);
						$j('[id=groupID-container-readonly__RAND__]').html('<span id="groupID-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=view_mebership_groups_view_parent]').hide(); } else { $j('.btn[id=view_mebership_groups_view_parent]').show(); }

						if(typeof(groupID_update_autofills__RAND__) == 'function') groupID_update_autofills__RAND__();
					}
				});
			}

		<?php } else { ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_groupID__RAND__.value, t: 'db_field_permission', f: 'groupID' },
				success: function(resp) {
					$j('[id=groupID-container__RAND__], [id=groupID-container-readonly__RAND__]').html('<span id="groupID-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=view_mebership_groups_view_parent]').hide(); } else { $j('.btn[id=view_mebership_groups_view_parent]').show(); }

					if(typeof(groupID_update_autofills__RAND__) == 'function') groupID_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
		function table_field_reload__RAND__() {
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint) { ?>

			$j("#table_field-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c) {
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_table_field__RAND__.value, t: 'db_field_permission', f: 'table_field' },
						success: function(resp) {
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="table_field"]').val(resp.results[0].id);
							$j('[id=table_field-container-readonly__RAND__]').html('<span id="table_field-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=tmp_tables_fields_view_parent]').hide(); } else { $j('.btn[id=tmp_tables_fields_view_parent]').show(); }


							if(typeof(table_field_update_autofills__RAND__) == 'function') table_field_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term) { return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 5,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page) { return { s: term, p: page, t: 'db_field_permission', f: 'table_field' }; },
					results: function(resp, page) { return resp; }
				},
				escapeMarkup: function(str) { return str; }
			}).on('change', function(e) {
				AppGini.current_table_field__RAND__.value = e.added.id;
				AppGini.current_table_field__RAND__.text = e.added.text;
				$j('[name="table_field"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=tmp_tables_fields_view_parent]').hide(); } else { $j('.btn[id=tmp_tables_fields_view_parent]').show(); }


				if(typeof(table_field_update_autofills__RAND__) == 'function') table_field_update_autofills__RAND__();
			});

			if(!$j("#table_field-container__RAND__").length) {
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_table_field__RAND__.value, t: 'db_field_permission', f: 'table_field' },
					success: function(resp) {
						$j('[name="table_field"]').val(resp.results[0].id);
						$j('[id=table_field-container-readonly__RAND__]').html('<span id="table_field-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=tmp_tables_fields_view_parent]').hide(); } else { $j('.btn[id=tmp_tables_fields_view_parent]').show(); }

						if(typeof(table_field_update_autofills__RAND__) == 'function') table_field_update_autofills__RAND__();
					}
				});
			}

		<?php } else { ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_table_field__RAND__.value, t: 'db_field_permission', f: 'table_field' },
				success: function(resp) {
					$j('[id=table_field-container__RAND__], [id=table_field-container-readonly__RAND__]').html('<span id="table_field-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=tmp_tables_fields_view_parent]').hide(); } else { $j('.btn[id=tmp_tables_fields_view_parent]').show(); }

					if(typeof(table_field_update_autofills__RAND__) == 'function') table_field_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
	</script>
	<?php

	$lookups = str_replace('__RAND__', $rnd1, ob_get_contents());
	ob_end_clean();


	// code for template based detail view forms

	// open the detail view template
	if($dvprint) {
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/db_field_permission_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	} else {
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/db_field_permission_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Db field permission details', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($AllowInsert) {
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return db_field_permission_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return db_field_permission_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
	} else {
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '', $templateCode);
	}

	// 'Back' button action
	if($_REQUEST['Embedded']) {
		$backAction = 'AppGini.closeParentModal(); return false;';
	} else {
		$backAction = '$j(\'form\').eq(0).attr(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;';
	}

	if($selected_id) {
		if(!$_REQUEST['Embedded']) $templateCode = str_replace('<%%DVPRINT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="dvprint" name="dvprint_x" value="1" onclick="$j(\'form\').eq(0).prop(\'novalidate\', true); document.myform.reset(); return true;" title="' . html_attr($Translation['Print Preview']) . '"><i class="glyphicon glyphicon-print"></i> ' . $Translation['Print Preview'] . '</button>', $templateCode);
		if($AllowUpdate) {
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return db_field_permission_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
		} else {
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		}
		if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3) { // allow delete?
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '<button type="submit" class="btn btn-danger" id="delete" name="delete_x" value="1" onclick="return confirm(\'' . $Translation['are you sure?'] . '\');" title="' . html_attr($Translation['Delete']) . '"><i class="glyphicon glyphicon-trash"></i> ' . $Translation['Delete'] . '</button>', $templateCode);
		} else {
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		}
		$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>', $templateCode);
	} else {
		$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		$templateCode = str_replace('<%%DESELECT_BUTTON%%>', ($ShowCancel ? '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>' : ''), $templateCode);
	}

	// set records to read only if user can't insert new records and can't edit current record
	if(($selected_id && !$AllowUpdate && !$AllowInsert) || (!$selected_id && !$AllowInsert)) {
		$jsReadOnly .= "\tjQuery('#ID_field_permissions').replaceWith('<div class=\"form-control-static\" id=\"ID_field_permissions\">' + (jQuery('#ID_field_permissions').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#groupID').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#groupID_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#table_field').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#table_field_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#fieldstate').replaceWith('<div class=\"form-control-static\" id=\"fieldstate\">' + (jQuery('#fieldstate').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	} elseif($AllowInsert) {
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
			$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos
	$templateCode = str_replace('<%%COMBO(groupID)%%>', $combo_groupID->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(groupID)%%>', $combo_groupID->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(groupID)%%>', urlencode($combo_groupID->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(table_field)%%>', $combo_table_field->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(table_field)%%>', $combo_table_field->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(table_field)%%>', urlencode($combo_table_field->MatchText), $templateCode);

	/* lookup fields array: 'lookup field name' => array('parent table name', 'lookup field caption') */
	$lookup_fields = array('groupID' => array('view_mebership_groups', 'GroupID'), 'table_field' => array('tmp_tables_fields', 'Table field'), );
	foreach($lookup_fields as $luf => $ptfc) {
		$pt_perm = getTablePermissions($ptfc[0]);

		// process foreign key links
		if($pt_perm['view'] || $pt_perm['edit']) {
			$templateCode = str_replace("<%%PLINK({$luf})%%>", '<button type="button" class="btn btn-default view_parent hspacer-md" id="' . $ptfc[0] . '_view_parent" title="' . html_attr($Translation['View'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-eye-open"></i></button>', $templateCode);
		}

		// if user has insert permission to parent table of a lookup field, put an add new button
		if($pt_perm['insert'] && !$_REQUEST['Embedded']) {
			$templateCode = str_replace("<%%ADDNEW({$ptfc[0]})%%>", '<button type="button" class="btn btn-success add_new_parent hspacer-md" id="' . $ptfc[0] . '_add_new" title="' . html_attr($Translation['Add New'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-plus-sign"></i></button>', $templateCode);
		}
	}

	// process images
	$templateCode = str_replace('<%%UPLOADFILE(ID_field_permissions)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(groupID)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(table_field)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(fieldstate)%%>', '', $templateCode);

	// process values
	if($selected_id) {
		if( $dvprint) $templateCode = str_replace('<%%VALUE(ID_field_permissions)%%>', safe_html($urow['ID_field_permissions']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(ID_field_permissions)%%>', html_attr($row['ID_field_permissions']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(ID_field_permissions)%%>', urlencode($urow['ID_field_permissions']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(groupID)%%>', safe_html($urow['groupID']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(groupID)%%>', html_attr($row['groupID']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(groupID)%%>', urlencode($urow['groupID']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(table_field)%%>', safe_html($urow['table_field']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(table_field)%%>', html_attr($row['table_field']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(table_field)%%>', urlencode($urow['table_field']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(fieldstate)%%>', safe_html($urow['fieldstate']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(fieldstate)%%>', html_attr($row['fieldstate']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(fieldstate)%%>', urlencode($urow['fieldstate']), $templateCode);
	} else {
		$templateCode = str_replace('<%%VALUE(ID_field_permissions)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(ID_field_permissions)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(groupID)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(groupID)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(table_field)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(table_field)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(fieldstate)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(fieldstate)%%>', urlencode(''), $templateCode);
	}

	// process translations
	$templateCode = parseTemplate($templateCode);

	// clear scrap
	$templateCode = str_replace('<%%', '<!-- ', $templateCode);
	$templateCode = str_replace('%%>', ' -->', $templateCode);

	// hide links to inaccessible tables
	if($_REQUEST['dvprint_x'] == '') {
		$templateCode .= "\n\n<script>\$j(function() {\n";
		$arrTables = getTableList();
		foreach($arrTables as $name => $caption) {
			$templateCode .= "\t\$j('#{$name}_link').removeClass('hidden');\n";
			$templateCode .= "\t\$j('#xs_{$name}_link').removeClass('hidden');\n";
		}

		$templateCode .= $jsReadOnly;
		$templateCode .= $jsEditable;

		if(!$selected_id) {
		}

		$templateCode.="\n});</script>\n";
	}

	// ajaxed auto-fill fields
	$templateCode .= '<script>';
	$templateCode .= '$j(function() {';


	$templateCode.="});";
	$templateCode.="</script>";
	$templateCode .= $lookups;

	// handle enforced parent values for read-only lookup fields

	// don't include blank images in lightbox gallery
	$templateCode = preg_replace('/blank.gif" data-lightbox=".*?"/', 'blank.gif"', $templateCode);

	// don't display empty email links
	$templateCode=preg_replace('/<a .*?href="mailto:".*?<\/a>/', '', $templateCode);

	/* default field values */
	$rdata = $jdata = get_defaults('db_field_permission');
	if($selected_id) {
		$jdata = get_joined_record('db_field_permission', $selected_id);
		if($jdata === false) $jdata = get_defaults('db_field_permission');
		$rdata = $row;
	}
	$templateCode .= loadView('db_field_permission-ajax-cache', array('rdata' => $rdata, 'jdata' => $jdata));

	// hook: db_field_permission_dv
	if(function_exists('db_field_permission_dv')) {
		$args=[];
		db_field_permission_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}