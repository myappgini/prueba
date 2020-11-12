<?php
	$rdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function() {
		var tn = 'salary';

		/* data for selected record, or defaults if none is selected */
		var data = {
			contacto: <?php echo json_encode(array('id' => $rdata['contacto'], 'value' => $rdata['contacto'], 'text' => $jdata['contacto'])); ?>,
			nombre: <?php echo json_encode($jdata['nombre']); ?>,
			rango: <?php echo json_encode($jdata['rango']); ?>,
			date: <?php echo json_encode($jdata['date']); ?>
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for contacto */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'contacto' && d.id == data.contacto.id)
				return { results: [ data.contacto ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for contacto autofills */
		cache.addCheck(function(u, d) {
			if(u != tn + '_autofill.php') return false;

			for(var rnd in d) if(rnd.match(/^rnd/)) break;

			if(d.mfk == 'contacto' && d.id == data.contacto.id) {
				$j('#nombre' + d[rnd]).html(data.nombre);
				$j('#rango' + d[rnd]).html(data.rango);
				$j('#date' + d[rnd]).html(data.date);
				return true;
			}

			return false;
		});

		cache.start();
	});
</script>

