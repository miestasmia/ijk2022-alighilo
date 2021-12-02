<?php
require_once(__DIR__ . '/countries.php');
require_once(__DIR__ . '/util.php');
require_once(__DIR__ . '/fees.php');

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

	$fieldLoghado = [
		'1' => 'Luksa unuopa ĉambro',
		'2' => 'Luksa duopa ĉambro',
		'kelk' => 'Lukseta ĉambro',
		'amas' => 'Amasĉambro',
		'tipio' => 'Tipio ekstera por 8 personoj',
		'tendo' => 'Propra tendo ekstere',
		'ekster' => 'Mem prizorgata loko ekter la kongresejo'
	];

	$fieldKunloghado = [
		'viroj' => 'kun viroj',
		'virinoj' => 'kun virinoj',
		'familioj' => 'kun familioj',
		'glat' => 'GLAT-amika',
		'negravas' => 'Ne gravas'
	];

	$mealsData = getMeals($data);
	$manghoj = [
		0 => [],
		1 => [],
		2 => [],
	];
	foreach ($mealsData as $day => $mealsToday) {
		foreach ($mealsToday as $mealType => $havingMeal) {
			if (!$havingMeal) { continue; }
			$manghoj[$mealType][] = $day;
		}
	}
	$manghojStr = array_map(function ($days) {
		return implode(', ', $days);
	}, $manghoj);

	array_push($dataArr,
		'alveno',
		'foriro',
		[ $fields['loghado'], $fieldLoghado[$data['loghado']] ],
		[ $fields['kunloghado'], $fieldKunloghado[$data['kunloghado']] ],
		'kunloghanto',
		'dormo',
		'littuko',
		'mangho',
		[ 'Matenmanĝoj', $manghojStr[0] ],
		[ 'Tagmanĝoj', $manghojStr[1] ],
		[ 'Vespermanĝoj', $manghojStr[2] ],
		'chemizo'
	);

	$donaco = (float) $data['donaco'];
	if ($donaco > 0) {
		$dataArr[] = [
			$fields['donaco'],
			format_eur($donaco)
		];
	}

	$fieldPagmaniero = [
		'banko' => 'Bankkonto de la IJK',
		'karto' => 'Kreditkarto',
		'paypal' => 'PayPal',
		'organizanto' => 'Al organizanto'
	];

	array_push($dataArr,
		[ $fields['pagmaniero'], $fieldPagmaniero[$data['pagmaniero']] ],
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
