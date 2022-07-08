<?php
require_once(__DIR__ . '/../util.php');
require_once(__DIR__ . '/../fields.php');
require_once(__DIR__ . '/../countries.php');
require_once(__DIR__ . '/../fees.php');

function renderAlighoEmailTxt ($data) {
	$dataArr = getDataArr($data);

	$dataMaxLen = max(array_map(function ($x) {
		return mb_strlen($x[0]);
	}, $dataArr));
	$dataStrArr = array_map(function ($x) use ($dataMaxLen) {
		$val = $x[1];
		if (is_bool($val)) {
			$val = $val ? 'Jes' : 'Ne';
		}
		$pad = str_repeat(' ', 2 + $dataMaxLen - mb_strlen($x[0]));
		return "{$x[0]}: $pad$val";
	}, $dataArr);

	$dataStrLen = max(array_map(function ($line) {
		return mb_strlen($line);
	}, $dataStrArr));
	array_walk($dataStrArr, function (&$line) use ($dataStrLen) {
		$line = '| ' . $line . str_repeat(' ', $dataStrLen - mb_strlen($line) + 1) . '|';
	});

	$dataStrLine = str_repeat('-', $dataStrLen + 2);
	$dataStr = implode("\n", $dataStrArr);

	$fees = getFees($data);

	$nemembrecoStr = '';
	if ($fees['otherFees']['member']) {
		$nemembrecoStr = "\nMalrabato pro nemembreco: " . format_eur($fees['otherFees']['member']);
	}

	$pagmanieroStr = [
		'banko' => 'Se vi ankoraŭ ne faris, sendu vian antaŭpagon de almenaŭ 20€ baldaŭ al NL85 RABO 0147 9494 24, (BIC: RABONL2U), nome de Nederlandse Esperanto-Jongeren, Utrecht kun la priskribo "IJK 2022 [ŝildnomo]".',
		'karto' => 'Por pagi per kreditkarto iru al la paĝo https://uea.org/alighoj/spagilo. Kiel pago-celon elektu \'Pago por IJK\', kaj en la akompanaj notoj skribu "IJK2022 [ŝildnomo]".',
		'paypal' => 'Se vi ankoraŭ ne faris, sendu vian antaŭpagon de almenaŭ 20€ per PayPal al financoj@co.uea.org kun la priskribo "IJK2022 [ŝildnomo]". Atentu se vi sendas monon en alia valuto ol eŭroj: Ĉar PayPal prenas kotizon pro konverto de valutoj, indas aldoni 10% al la pago por esti certa ke almenaŭ 20€ atingas nin.',
		'organizanto' => 'Se vi ankoraŭ ne faris, trovu organizanton kaj donu al ri vian antaŭpagon de almenaŭ 20€.'
	][$data['pagmaniero']];

	$totalFeeFormatted = format_eur($fees['totalFee']);
	$totalFeeLine = str_repeat('=', mb_strlen($totalFeeFormatted));

	$coal = 'coal';
	$format_eur = 'format_eur';
	return <<<"EOT"
Kara {$coal($data['shildnomo'], $data['nomo'])},

Ni sukcese ricevis vian aliĝon al la 78-a Internacia Junulara Kongreso! Vi aliĝis kun la jenaj informoj:


/$dataStrLine\\
$dataStr
\\$dataStrLine/


Bonvolu kontroli ĉu ĉiuj informoj ĝuste aperas. Se estas eraro en viaj informoj, aŭ se vi ŝatus ŝanĝi iun informon, bonvolu skribi al ijk2022@tejo.org.

Via kalkulita kotizo estas:

Programkotizo: {$format_eur($fees['baseFees']['program'])}
Aliĝperiodo: {$format_eur($fees['baseFees']['signup'])}
Loĝado: {$format_eur($fees['baseFees']['housing'])}
Manĝoj: {$format_eur($fees['baseFees']['food'])}$nemembrecoStr
Via plena kotizo: $totalFeeFormatted
                  $totalFeeLine

$pagmanieroStr Via aliĝo validas je ricevo de la minimuma antaŭpago.  Se vi volas pagi en alia maniero, se vi havas specialan peton, aŭ se vi havas demandon, ne hezitu skribi al ijk2022@tejo.org.

En la venontaj monatoj vi regule ricevos novaĵleteron de ni pri nia progreso por organizi por vi la plej bonan kongreson.

Ĝis revido en aŭgusto,
la organiza teamo de IJK 2022

EOT;
}
