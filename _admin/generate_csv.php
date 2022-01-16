<?php

$keys = [
	'dato',
	'nomo',
	'shildnomo',
	'retposhtadreso',
	'naskightago',
	'loghlando',
	'shildlando',
	'membreco',
	'ueakodo',
	'alveno',
	'foriro',
	'loghado',
	'kunloghado',
	'kunloghanto',
	'dormo',
	'littuko',
	'mangho',
	'chemizo',
	'donaco',
	'pagmaniero',
	'listo',
	'fotoj',
	'novulo',
	'dieto',
	'vizo',
	'vizo-nacieco',
	'vizo-pasportnumero',
	'vizo-dato-ek',
	'vizo-dato-ghis',
	'vizo-adreso',
	'volontulo',
	'kontribuo',
	'komentoj',
];

foreach (range(20, 27) as $day) {
	for ($j = 0; $j < 3; $j++) {
		if (
			($day == 20 && $j != 2) ||
			($day == 27 && $j != 0)
		) { continue; }
		$keys[] = "mangho-$day-$j";
	}
}

echo '#';
foreach ($keys as $key) {
	echo "\t" . $key;
}
echo "\n";

$fh = fopen('../alighoj.csv', 'r');
$i = 1;

while (($line = fgets($fh)) !== false) {
	$data = json_decode($line, true);

	echo $i++;
	foreach ($keys as $key) {
		echo "\t" . ($data[$key] ?? '');
	}
	echo "\n";
}

fclose($fh);
