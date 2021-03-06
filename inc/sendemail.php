<?php
require_once(__DIR__ . '/settings.php');

function sendEmail ($params) {
	// Generate curl request
	$session = curl_init('https://api.sendgrid.com/v3/mail/send');
	// Tell curl to use HTTP POST
	curl_setopt ($session, CURLOPT_POST, true);
	// Tell curl that this is the body of the POST
	curl_setopt ($session, CURLOPT_POSTFIELDS, json_encode($params));
	// Tell curl not to return headers, but do return the response
	curl_setopt($session, CURLOPT_HEADER, false);
	// Tell PHP not to use SSLv3 (instead opting for TLS)
	curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($session, CURLOPT_HTTPHEADER, [
		'Authorization: Bearer ' . SENDGRID_API_KEY,
		'Content-Type: application/json'
	]);

	// obtain response
	$response = curl_exec($session);
	curl_close($session);

	// print everything out
	return $response;
}
