<?php
	require('countries.php');

	header('Content-Type: text/plain');
	var_dump($_POST);

	// Validation
	$isDate = function ($date) {
		return new DateTime($date) != null;
	};

	$validators = [
		'nomo' => '/^[^\r\n]{1,100}$/',
		'shildnomo' => '/^[^\r\n]{0,30}$/',
		'retposhtadreso' => '/^.{1,200}$/',
		'naskightago' => $isDate,
		'loghlando' => function ($country) use ($countries) {
			foreach ($countries as $existingCountry) {
				if ($existingCountry[0] === $country) { return true; }
			}
			return false;
		},
		'shildlando' => '/^[^\r\n]{0,20}$/',
		'membreco' => true,
		'ueakodo' => '/^[a-z]{4}(-[a-z])?$/i',
		'partopreno-plentempa' => true,
		'alveno' => $isDate,
		'foriro' => $isDate,
		'loghado' => function ($val) {
			return in_array($val, [
				'1', '2', 'kelk', 'amas', 'tipio', 'tendo'
			]);
		},
		'kunloghado' => function ($val) {
			return in_array($val, [
				'viroj', 'virinoj', 'familioj', 'glat', 'negravas'
			]);
		},
		'kunloghanto' => '/^[^\r\n]{0,200}$/',
		'dormo' => function ($val) {
			return in_array($val, [
				'frue', 'malfrue', 'malfruege'
			]);
		},
		'littuko' => true,
		'mangho' => function ($val) {
			return in_array($val, [
				'vegetare', 'vegane', 'kunviande'
			]);
		},

		'chemizo' => function ($val) {
			return in_array($val, [
				'ne', 'S', 'M', 'L', 'XL', 'XXL'
			]);
		},
		'donaco' => function ($val) {
			$val = (float) $val;

			if ($val !== (string) $val) {
				return false;
			}

			return $val > 0 && $val <= 10000;
		},
		'pago' => function ($val) {
			return in_array($val, [
				'banko', 'karto', 'paypal', 'organizanto'
			]);
		},
		'listo' => true,
		'fotoj' => true,
	];
	foreach (range(20, 27) as $day) {
		for ($j = 0; $j < 3; $j++) {
			if (
              ($day == 20 && $j != 2) ||
              ($day == 27 && $j != 0)
            ) { continue; }
			$validators["mangho-$day-$j"] = true;
		}
	}

	foreach ($validators as $key => $validator) {
		if (
			(is_string($validator) && !preg_match($validator, $_POST[$key])) ||
			(is_callable($validators) && !$validator($_POST[$key])) ||
			($validators === true && isset($_POST[$key]) && $_POST[$key] !== 'on')
		) {
			exit("'" . $key . "' estas erara");
		}
	}

	// Save to file
	$file = './alighoj.csv';
	if (!file_exists($file)) {
		file_put_contents($file, '');
	}

	$data = json_encode($_POST);

	$fp = fopen($file, 'a');
	fwrite($fp, $data . "\n");
	fclose($fp);
