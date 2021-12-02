<?php
require_once('../inc/email/aligho.txt.php');
require_once('../inc/sendemail.php');

if (!isset($_GET['konfirmo'])) {
	die('Bonvolu aldoni ?konfirmo fine de la URL por konfirmi ke vi vere volas (denove) dissendi konfirman retpoŝtadreson al ĉiuj aliĝintoj.');
}

$numEmail = 0;
$fh = fopen('../alighoj.csv', 'r');
while (($line = fgets($fh)) !== false) {
	$data = json_decode($line, true);
	sendEmail([
		'from' => [
			'email' => 'ijk2022@tejo.org',
			'name' => 'La IJK 2022-teamo'
		],
		'personalizations' => [
			[
				'to' => [
					[
						'email' => $data['retposhtadreso'],
						'name' => coal($data['shildnomo'], $data['nomo'])
					]
				],
				'subject' => 'Vi sukcese aliĝis al IJK2022'
			]
		],
		'content' => [
			[
				'type' => 'text/plain',
				'value' => renderAlighoEmailTxt($data)
			]
		]
	]);
	$numEmail++;
	usleep(200000); // 0.2 seconds
}
fclose($fh);
die("Sendis $numEmail retmesaĝojn.");
