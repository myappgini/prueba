<?php
	$rdata = array_map('to_utf8', array_map('safe_html', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('safe_html', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function() {
		var tn = 'db_field_permission';

		/* data for selected record, or defaults if none is selected */
		var data = {
			groupID: <?php echo json_encode(['id' => $rdata['groupID'], 'value' => $rdata['groupID'], 'text' => $jdata['groupID']]); ?>,
			table_field: <?php echo json_encode(['id' => $rdata['table_field'], 'value' => $rdata['table_field'], 'text' => $jdata['table_field']]); ?>
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for groupID */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'groupID' && d.id == data.groupID.id)
				return { results: [ data.groupID ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for table_field */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'table_field' && d.id == data.table_field.id)
				return { results: [ data.table_field ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

