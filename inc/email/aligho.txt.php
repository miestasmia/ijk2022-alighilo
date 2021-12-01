<?php
include_once('../util.php');
include_once('../fields.php');
include_once('../countries.php');

function renderAlighoEmailTxt ($data) {
	$dataArr = getDataArr($data);
	$dataMaxLen = max(array_map(function ($x) {
		return mb_strlen($x[0]);
	}, $dataArr));
	$dataStr = implode("\n", array_map(function ($x) use ($dataMaxLen) {
		$val = $x[1];
		if (is_bool($val)) {
			$val = $val ? 'Jes' : 'Ne';
		}
		$pad = str_repeat(' ', 2 + $dataMaxLen - mb_strlen($x[0]));
		return "{$x[0]}: $pad$val";
	}, $dataArr));

	$coal = 'coal';
	return <<<"EOT"
Kara {$coal($data['shildnomo'], $data['nomo'])},


Ni sukcese ricevis vian aliĝon al la 78-a Internacia Junulara Kongreso! Vi aliĝis kun la jenaj informoj:

{$dataStr}


Via kalkulita kotizo estas:

Programkotizo: {{kotizo-programo}}
Aliĝperiodo: {{kotizo-aligho}}
Loĝado: {{kotizo-loghado}}
Manĝoj: {{kotizo-manghoj}}
Malrabato pro nemembreco: {{kotizo-}}
Via plena kotizo: 

Se vi ankoraŭ ne faris, sendu vian antaŭpagon de almenaŭ 20€ baldaŭ al NL85 RABO 0147 9494 24, (BIC: RABONL2U), nome de Nederlandse Esperanto-Jongeren, Utrecht kun la priskribo "IJK 2022 [ŝildnomo]". Via aliĝo validas je ricevo de la minimuma antaŭpago.  Se vi volas pagi en alia maniero, se vi havas specialan peton, aŭ se vi havas demandon, ne hezitu skribi al ijk2022@tejo.org.

En la venontaj monatoj vi regule ricevos novaĵleteron de ni pri nia progreso por organizi por vi la plej bonan kongreson.

Ĝis revido en aŭgusto,
la organiza teamo de IJK 2022

EOT;
}
