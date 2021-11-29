<?php
include_once('countries.php');

$fields = [
	'dato' => 'Dato/horo',
	'nomo' => 'Plena nomo',
	'shildnomo' => 'Ŝildnomo',
	'retposhtadreso' => 'Retpoŝtadreso',
	'naskightago' => 'Naskiĝtago',
	'loghlando' => 'Loĝlando',
	'shildlando' => 'Ŝildlando',
	'membreco' => 'IM de TEJO',
	'ueakodo' => 'UEA-kodo',
	'alveno' => 'Alventago',
	'foriro' => 'Forirtago',
	'loghado' => 'Loĝado',
	'kunloghado' => 'Kunloĝado',
	'kunloghanto' => 'Kunloĝanto',
	'dormo' => 'Ekdormo',
	'littuko' => 'Kunportos proprajn littukojn',
	'mangho' => 'Manĝo',
	'chemizo' => 'T-ĉemizo',
	'donaco' => 'Donaco',
	'pagmaniero' => 'Pagmaniero',
	'listo' => 'Apero en listo',
	'fotoj' => 'Apero en fotoj',
	'novulo' => 'Novulo',
	'dieto' => 'Dietaj bezonoj',
	'vizo' => 'Bezonas vizon',
	'vizo-nacieco' => 'Nacieco (vizo)',
	'vizo-pasportnumero' => 'Pasportnumero (vizo)',
	'vizo-dato-ek' => 'Ekdato (vizo)',
	'vizo-dato-ghis' => 'Ĝisdato (vizo)',
	'vizo-adreso' => 'Adreso (vizo)',
	'volontulo' => 'Volas volontuli',
	'kontribuo' => 'Kontribuo',
	'komentoj' => 'Komentoj',
];

function getDataArr ($data) {
	global $fields, $countries;

	$country = null;
	foreach ($countries as $countryArr) {
		if ($countryArr[0] === $data['loghlando']) {
			$country = $countryArr[1];
			break;
		}
	}

	$dataArr = [
		'dato',
		'nomo',
		'shildnomo'
	];

	if ($data['shildnomo']) {
		$dataArr[] = 'shildnomo';
	}

	array_push($dataArr,
		'retposhtadreso',
		'naskightago',
		[ $fields['loghlando'], $country ]
	);

	if ($data['shildlando']) {
		$dataArr[] = 'shildlando';
	}

	array_push($dataArr,
		'membreco'
	);

	if ($data['membreco']) {
		$dataArr[] = 'ueakodo';
	}

	array_push($dataArr,
		'alveno',
		'foriro',
		'loghado',
		'kunloghado',
		'kunloghanto',
		'dormo',
		'littuko',
		'mangho',
		'chemizo'
	);

	$donaco = (float) $data['donaco'];
	if ($donaco > 0) {
		$dataArr[] = [
			$fields['donaco'],
			number_format($donaco, 2, ',', '.') . '€'
		];
	}

	array_push($dataArr,
		'pagmaniero',
		'listo',
		'fotoj',
		'novulo',
		'dieto',
		'vizo'
	);

	if ($data['vizo']) {
		array_push($dataArr,
			'vizo-nacieco',
			'vizo-pasportnumero',
			'vizo-dato-ek',
			'vizo-dato-ghis',
			'vizo-adreso'
		);
	}

	array_push($dataArr,
		'volontulo',
		'kontribuo',
		'komentoj'
	);

	$dataArr2 = [];
	foreach ($dataArr as $row) {
		if (is_string($row)) {
			$newRow = [ $fields[$row], $data[$row] ];
		} else {
			$newRow = $row;
		}
		$dataArr2[] = $newRow;
	}

	return $dataArr2;
}
