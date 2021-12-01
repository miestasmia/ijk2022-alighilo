<?php
define('FEES', [
	[ 17, [ 30, 15, 0 ] ],
	[ 26, [ 50, 35, 20 ] ],
	[ 35, [ 80, 65, 50 ] ],
	[ 999, [ 110, 95, 80 ] ],
]);
define('COUNTRY_GROUPS', [
	[ 'AT', 'BE', 'GB', 'DK', 'FR', 'DE', 'IE', 'LU', 'NL', 'NO', 'SE', 'CH' ],
	[
		// EU
		'AL','AD','AZ','BY','BA','BG','HR','CY','CZ','EE','FI','GE','GR','HU',
		'IS', 'IT','KZ','LV','LI','LT','MK','MT','MD','MC','ME','PL','PT','RO',
		'RU','SM','RS','SK', 'SI','ES','TR','UA','VA',

		'AU', 'JP', 'CA', 'NZ', 'KR', 'US',
	],
]);

define('SIGNUP_PERIODS', [
	[ '2022-01-09', 0 ],
	[ '2022-04-24', 10 ],
	[ '2022-07-03', 20 ],
	[ '2023-01-01', 30 ],
]);

define('HOUSING_FEE', [
	'1' => 250,
	'2' => 100,
	'kelk' => 50,
	'amas' => 35,
	'tipio' => 20,
	'tendo' => 20,
	'ekster' => 0,
]);


define('BEDSHEET_DISCOUNT', [
	'1', '2', 'kelk', 'amas'
]);

define('FOOD_FEES', [
	0 => 2,
	1 => 6.5,
	2 => 3,
]);

define('FREE_T_SHIRT_DATE', '2021-11-09');

function getFees ($data) {
	$baseFees = [
		'program' => getProgramFee($data),
		'signup' => getSignupFee($data),
		'housing' => getHousingFee($data),
		'food' => getFoodFee($data),
	];
	$otherFees = [
		'member' => $data['membreco'] ? 0 : 50,
		'tShirt' => getTShirtPrice($data),
		'bedsheet' => (
			$data['littuko'] &&
			in_array($data['loghado'], BEDSHEET_DISCOUNT)
		) ? -5 : null,
	];

	$baseFee = array_sum($baseFees);
	$donation = $data['donaco'];
	$totalFee = $baseFee + $donation + array_sum($otherFees);

	return [
		'baseFees' => $baseFees,
		'otherFees' => $otherFees,
		'baseFee' => $baseFee,
		'totalFee' => $totalFee,
		'donation' => $donation
	];
}

function getStayDuration ($data) {
	$arrivalDate = new DateTime($data['alveno']);
	$leaveDate = new DateTime($data['foriro']);
	return (int)($arrivalDate->diff($leaveDate)->format('%r%a'));
}

function getProgramFee ($data) {
	$stayDuration = getStayDuration($data);

	$bdate = new DateTime($data['naskightago']);
	$age = (int)($bdate->diff(new DateTime('2022-08-20'))->format('%r%y'));
	if ($age <= 10) { return 0; }

	$country = $data['loghlando'];
	$countryGroup = 2;
	foreach (COUNTRY_GROUPS as $i => $group) {
		if (!in_array($country, $group)) { continue; }
		$countryGroup = $i;
	}

	$fee = null;
	foreach (FEES as [$maxAge, $ageFees]) {
		if ($age > $maxAge) { continue; }
		$fee = $ageFees[$countryGroup];
		break;
	}

	if ($stayDuration <= 2) { $fee /= 2; }

	return $fee;
}

function getSignupFee ($data) {
	$time = new DateTime($data['dato']);
	$time->setTime(0,0);
	foreach (SIGNUP_PERIODS as [$maxTime, $timeFee]) {
		if ($time > new DateTime($maxTime)) { continue; }
		return $timeFee;
	}
}

function getHousingFee ($data) {
	$stayDuration = getStayDuration($data);
	$housingType = $data['loghado'];

	$fee = HOUSING_FEE[$housingType];
	if ($stayDuration <= 2) { $fee /= 2; }
	return $fee;
}

function getMeals ($data) {
	$meals = [];
	foreach (range(20, 27) as $day) {
		$meals[$day] = [];

		for ($j = 0; $j < 3; $j++) {
            if (
                ($day == 20 && $j != 2) ||
                ($day == 27 && $j != 0)
            ) { continue; }
	        $meals[$day][$j] = $data["mangho-$day-$j"];
        }
	}
	return $meals;
}

function getFoodFee ($data) {
	$fee = 0;
	$meals = getMeals($data);
	
	foreach ($meals as $mealsToday) {
		foreach ($mealsToday as $i => $havingMeal) {
			if ($havingMeal) {
				$fee += FOOD_FEES[$i];
			}
		}
	}
	return $fee;
}

function getTShirtPrice ($data) {
	$wanted = $data['chemizo'] !== 'ne';
	if (!$wanted) { return null; }
	$date = new DateTime($data['dato']);
	$date->setTime(0, 0);
	if ($date > new DateTime(FREE_T_SHIRT_DATE)) {
		return 7.5;
	}
	return 0;
}
