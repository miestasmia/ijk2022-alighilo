<?php
	require('countries.php');

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
		'ueakodo' => '/^[a-z]{4}(-[a-z])?$|/i',
		'partopreno-plentempa' => true,
		'alveno' => function ($date) use ($isDate) {
			if ($_POST['partopreno-plentempa']) {
				return $date === '';
			}
			if (!$isDate($date)) { return false; }
			return $date < new DateTime('2022-08-20');
		},
		'foriro' => function ($date) use ($isDate) {
			if ($_POST['partopreno-plentempa']) {
				return $date === '';
			}
			if (!$isDate($date)) { return false; }
			return $date <= new DateTime('2022-08-28'); // plus unu pro horo
		},
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
			if (!is_numeric($val)) { return false; }
			$val = (float) $val;

			return $val >= 0 && $val <= 10000;
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
			(is_callable($validator) && !$validator($_POST[$key])) ||
			($validator === true && isset($_POST[$key]) && $_POST[$key] !== 'on')
		) {
			exit("'" . $key . "' estas erara");
		}
	}

	// Save to file
	$file = './alighoj.csv';
	if (!file_exists($file)) {
		file_put_contents($file, '');
	}

	$data = [
		'nomo' => $_POST['nomo'],
		'shildnomo' => $_POST['shildnomo'],
		'retposhtadreso' => $_POST['retposhtadreso'],
		'naskightago' => $_POST['naskightago'],
		'loghlando' => $_POST['loghlando'],
		'shildlando' => $_POST['shildlando'],
		'membreco' => $_POST['membreco'] === 'on',
		'ueakodo' => $_POST['ueakodo'],
		'alveno' => $_POST['partopreno-plentempa'] === 'on' ? '2022-08-20' : $_POST['alveno'],
		'foriro' => $_POST['partopreno-plentempa'] === 'on' ? '2022-08-27' : $_POST['foriro'],
		'loghado' => $_POST['loghado'],
		'kunloghado' => $_POST['kunloghado'],
		'kunloghanto' => $_POST['kunloghanto'],
		'dormo' => $_POST['dormo'],
		'littuko' => $_POST['littuko'] === 'on',
		'mangho' => $_POST['mangho'],
		'chemizo' => $_POST['chemizo'],
		'donaco' => $_POST['donaco'],
		'pagmaniero' => $_POST['pago'],
		'listo' => $_POST['listo'] === 'on',
		'fotoj' => $_POST['alveno'] === 'on',
	];

	$fp = fopen($file, 'a');
	fwrite($fp, json_encode($data) . "\n");
	fclose($fp);
?>
<!doctype html>
<html lang="eo">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
    <link rel="icon" href="icon-small.png" sizes="32x32">
    <link rel="icon" href="icon.png" sizes="192x192">
    <link rel="apple-touch-icon-precomposed" href="icon-apple.png">
    <title>Aliĝi al IJK 2022 en Nederlando</title>
    <meta property="og:site_name" content="IJK 2022">
    <meta name="description" content="La 78a IJK de TEJO okazos en De Roerdomp – Westelbeers, Nederlando, inter la 20-a kaj la 27-a de aŭgusto 2022. Ĝin organizos la Nederlanda Esperanto-Junularo.">
    <meta property="og:description" content="La 78a IJK de TEJO okazos en De Roerdomp – Westelbeers, Nederlando, inter la 20-a kaj la 27-a de aŭgusto 2022. Ĝin organizos la Nederlanda Esperanto-Junularo.">
  </head>
  <body>
  	<main>
	  	<h1>Dankon pro via aliĝo, <?php echo $_POST['nomo']; ?>!</h1>
	  	<p>
	  		Ni sendos al vi retmesaĝon ene de la venontaj kelkaj tagoj post la komenca ŝtormo de aliĝoj.
	  	</p>
	</main>
  </body>
 </html>