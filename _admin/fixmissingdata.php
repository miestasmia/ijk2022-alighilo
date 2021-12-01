<?php
$defaultSignup = [
	'dato' => (new DateTime())->format('Y-m-d\TH:i:s'),
	'nomo' => 'Sennoma!',
	'shildnomo' => '',
	'retposhtadreso' => '',
	'naskightago' => '',
	'loghlando' => 'NL',
	'shildlando' => '',
	'membreco' => false,
	'ueakodo' => '',
	'alveno' => '2020-08-20',
	'foriro' => '2020-08-27',
	'loghado' => 'amas',
	'kunloghado' => 'negravas',
	'kunloghanto' => '',
	'dormo' => 'malfruege',
	'littuko' => false,
	'mangho' => 'vegetare',
	'chemizo' => 'ne',
	'donaco' => 0,
	'pagmaniero' => 'banko',
	'listo' => true,
	'fotoj' => true,
	'novulo' => false,
	'dieto' => '',
	'vizo' => false,
	'vizo-nacieco' => null,
	'vizo-pasportnumero' => '',
	'vizo-dato-ek' => '',
	'vizo-dato-ghis' => '',
	'vizo-adreso' => '',
	'volontulo' => false,
	'kontribuo' => '',
	'komentoj' => '',
];

foreach (range(20, 27) as $day) {
	for ($j = 0; $j < 3; $j++) {
        if (
            ($day == 20 && $j != 2) ||
            ($day == 27 && $j != 0)
        ) { continue; }
        $defaultSignup["mangho-$day-$j"] = true;
    }
}

$fh_read = fopen('../alighoj.csv', 'r');
$fh_write = fopen('../alighoj.csv.tmp', 'w');
$lineStartPos = 0;
while (($line = fgets($fh_read)) !== false) {
	$signup = json_decode($line, true);
	$fixedSignup = array_merge($defaultSignup, $signup);
	fwrite($fh_write, json_encode($fixedSignup) . "\n");
}
fclose($fh_read);
fclose($fh_write);
rename('../alighoj.csv.tmp', '../alighoj.csv');
