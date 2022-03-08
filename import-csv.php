<?php
	define('PREPEND_PATH', '');
	include_once(__DIR__ . '/lib.php');

	// accept a record as an assoc array, return transformed row ready to insert to table
	$transformFunctions = [
		'contacto' => function($data, $options = []) {
			if(isset($data['date'])) $data['date'] = guessMySQLDateTime($data['date']);

			return $data;
		},
		'salary' => function($data, $options = []) {
			if(isset($data['contacto'])) $data['contacto'] = pkGivenLookupText($data['contacto'], 'salary', 'contacto');
			if(isset($data['nombre'])) $data['nombre'] = thisOr($data['contacto'], pkGivenLookupText($data['nombre'], 'salary', 'nombre'));
			if(isset($data['rango'])) $data['rango'] = thisOr($data['contacto'], pkGivenLookupText($data['rango'], 'salary', 'rango'));
			if(isset($data['date'])) $data['date'] = thisOr($data['contacto'], pkGivenLookupText($data['date'], 'salary', 'date'));

			return $data;
		},
		'products' => function($data, $options = []) {
			if(isset($data['due'])) $data['due'] = guessMySQLDateTime($data['due']);

			return $data;
		},
		'db_field_permission' => function($data, $options = []) {
			if(isset($data['groupID'])) $data['groupID'] = pkGivenLookupText($data['groupID'], 'db_field_permission', 'groupID');
			if(isset($data['table_field'])) $data['table_field'] = pkGivenLookupText($data['table_field'], 'db_field_permission', 'table_field');

			return $data;
		},
		'tmp_tables_fields' => function($data, $options = []) {

			return $data;
		},
		'view_membership_groups' => function($data, $options = []) {

			return $data;
		},
		'todos' => function($data, $options = []) {
			if(isset($data['dateInit'])) $data['dateInit'] = guessMySQLDateTime($data['dateInit']);
			if(isset($data['dateEnd'])) $data['dateEnd'] = guessMySQLDateTime($data['dateEnd']);
			if(isset($data['product'])) $data['product'] = pkGivenLookupText($data['product'], 'todos', 'product');

			return $data;
		},
	];

	// accept a record as an assoc array, return a boolean indicating whether to import or skip record
	$filterFunctions = [
		'contacto' => function($data, $options = []) { return true; },
		'salary' => function($data, $options = []) { return true; },
		'products' => function($data, $options = []) { return true; },
		'db_field_permission' => function($data, $options = []) { return true; },
		'tmp_tables_fields' => function($data, $options = []) { return true; },
		'view_membership_groups' => function($data, $options = []) { return true; },
		'todos' => function($data, $options = []) { return true; },
	];

	/*
	Hook file for overwriting/amending $transformFunctions and $filterFunctions:
	hooks/import-csv.php
	If found, it's included below

	The way this works is by either completely overwriting any of the above 2 arrays,
	or, more commonly, overwriting a single function, for example:
		$transformFunctions['tablename'] = function($data, $options = []) {
			// new definition here
			// then you must return transformed data
			return $data;
		};

	Another scenario is transforming a specific field and leaving other fields to the default
	transformation. One possible way of doing this is to store the original transformation function
	in GLOBALS array, calling it inside the custom transformation function, then modifying the
	specific field:
		$GLOBALS['originalTransformationFunction'] = $transformFunctions['tablename'];
		$transformFunctions['tablename'] = function($data, $options = []) {
			$data = call_user_func_array($GLOBALS['originalTransformationFunction'], [$data, $options]);
			$data['fieldname'] = 'transformed value';
			return $data;
		};
	*/

	@include(__DIR__ . '/hooks/import-csv.php');

	$ui = new CSVImportUI($transformFunctions, $filterFunctions);
