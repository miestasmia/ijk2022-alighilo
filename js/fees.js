const FEES = [
	[ 17, [ 30, 15, 0 ] ],
	[ 26, [ 50, 35, 20 ] ],
	[ 35, [ 80, 65, 50 ] ],
	[ 999, [ 110, 95, 80 ] ],
];
const COUNTRY_GROUPS = [
	[ 'AT', 'BE', 'GB', 'DK', 'FR', 'DE', 'IE', 'LU', 'NL', 'NO', 'SE', 'CH' ],
	[
		// EU
		'AL','AD','AZ','BY','BA','BG','HR','CY','CZ','EE','FI','GE','GR','HU',
		'IS', 'IT','KZ','LV','LI','LT','MK','MT','MD','MC','ME','PL','PT','RO',
		'RU','SM','RS','SK', 'SI','ES','TR','UA','VA',

		'AU', 'JP', 'CA', 'NZ', 'KR', 'US',
	],
];

const SIGNUP_PERIODS = [
	[ moment('2022-01-09'), 0 ],
	[ moment('2022-04-24'), 10 ],
	[ moment('2022-07-03'), 20 ],
	[ moment('2023-01-01'), 30 ],
];

const HOUSING_FEE = {
	1: 250,
	2: 100,
	kelk: 50,
	amas: 35,
	tipio: 20,
	tendo: 20,
	ekster: 0,
};
const BEDSHEET_DISCOUNT = [
	'1', '2', 'kelk', 'amas'
];

const FOOD_FEES = {
	0: 2,
	1: 6.5,
	2: 3,
};

const FREE_T_SHIRT_DATE = moment('2021-11-09');

// Auto render fees
$('main>form input,main>form select').forEach(el =>
	el.addEventListener('change', renderFee)
);
$('main>form button').forEach(el =>
	el.addEventListener('click', renderFee)
);
renderFee();

function formatMoney (num) {
	return Intl.NumberFormat('de-DE', {
		style: 'currency',
		currency: 'EUR'
	}).format(num);
}

function getFees () {
	const baseFees = {
		program: getProgramFee(),
		signup: getSignupFee(),
		housing: getHousingFee(),
		food: getFoodFee(),
	};
	const otherFees = {
		member: $$('#kampo-membreco').checked ? 0 : 50,
		tShirt: getTShirtPrice(),
		bedsheet: (
			$$('#kampo-littuko').checked &&
			BEDSHEET_DISCOUNT.includes($$('#kampo-loghado').value)
		) ? -5 : null,
	};

	let baseFee = null;
	let totalFee = null;
	const hasNull = Object.values(baseFees).includes(null);
	if (!hasNull) {
		baseFee = Object.values(baseFees).reduce((a, b) => a + b, 0)
		totalFee = baseFee;
		totalFee += Object.values(otherFees)
			.filter(x => x !== null)
			.reduce((a, b) => a + b, 0);
	}

	let donation = 0;
	if ($$('#kampo-donaco').value) {
		donation = parseFloat($$('#kampo-donaco').value);
		donation = Math.max(donation, 0);
		donation = Math.min(donation, 10000);
		if (!hasNull) {
			totalFee += donation;
		}
	}

	return {
		baseFees, otherFees, baseFee, totalFee, donation
	};
}

function renderFee () {
	const fees = getFees();

	$$('#kotizo-programo').textContent = fees.baseFees.program === null ?
		'kalkulota' :
		formatMoney(fees.baseFees.program);

	$$('#kotizo-periodo').textContent = formatMoney(fees.baseFees.signup);

	$$('#kotizo-loghado').textContent = fees.baseFees.housing === null ?
		'kalkulota' :
		formatMoney(fees.baseFees.housing) + 
		(fees.otherFees.bedsheet === null ?
			'' :
			` (${formatMoney(fees.otherFees.bedsheet)}: littuka rabato)`);

	$$('#kotizo-manghoj').textContent = formatMoney(fees.baseFees.food);

	$$('#kotizo-chemizo').textContent = fees.otherFees.tShirt === null ?
		'' :
		`T-ĉemizo: ${formatMoney(fees.otherFees.tShirt)}`;

	$$('#kotizo-plena').textContent = fees.totalFee === null ?
		'kalkulota' :
		formatMoney(fees.totalFee);

	$$('#kotizo-nemembro').innerHTML = fees.otherFees.member === 0 ?
		'' :
		`Malrabato pro nemembreco: ${formatMoney(fees.otherFees.member)}<br>`;

	$$('#kotizo-donaco').innerHTML = fees.donation === 0 ?
		'' :
		`Donaco al la IJK: ${formatMoney(fees.donation)}<br>`;
}

function getStayDuration () {
	if ($$('#kampo-partopreno-plentempa').checked) { return 7; }

	const arrivalDate = $$('#kampo-alveno').value;
	const leaveDate = $$('#kampo-foriro').value;

	if (!arrivalDate || !leaveDate) {
		return null;
	}

	return moment(leaveDate).diff(arrivalDate, 'd');
}

function getProgramFee () {
	const stayDuration = getStayDuration();
	if (stayDuration === null) { return null; }

	const bdate = $$('#kampo-naskightago').value;
	if (!bdate) { return null; }
	const age = moment('2022-08-20', 'YYYY-MM-DD').diff(moment(bdate), 'y');
	if (isNaN(age)) { return null; }

	if (age <= 10) { return 0; }

	const country = $$('#kampo-loghlando').value;
	if (!country) { return null; }
	let countryGroup = 2;
	for (const i in COUNTRY_GROUPS) {
		const group = COUNTRY_GROUPS[i];
		if (!group.includes(country)) { continue; }
		countryGroup = parseInt(i, 10);
	}

	let fee;
	for (const [maxAge, ageFees] of FEES) {
		if (age > maxAge) { continue; }
		fee = ageFees[countryGroup];
		break;
	}

	if (stayDuration <= 2) { fee /= 2; }

	return fee;
}

function getSignupFee () {
	const now = moment();
	for (const [maxTime, timeFee] of SIGNUP_PERIODS) {
		if (now.isAfter(maxTime, 'day')) { continue; }
		return timeFee;
	}
}

function getHousingFee () {
	const stayDuration = getStayDuration();
	if (stayDuration === null) { return null; }

	const housingType = $$('#kampo-loghado').value;
	if (!housingType) { return null; }

	let fee = HOUSING_FEE[housingType];
	if (stayDuration <= 2) { fee /= 2; }
	return fee;
}

function getFoodFee () {
	let fee = 0;
	for (const row of $('#mangho-tabelo>tbody>tr')) {
		let i = -1;
		for (const col of $('td', row)) {
			i++;
			const checkbox = $$('input', col);
			if (!checkbox) { continue; }
			if (checkbox.checked) {
				fee += FOOD_FEES[i];
			}
		}
	}
	return fee;
}

function getTShirtPrice () {
	const wanted = $$('#kampo-chemizo').value !== 'ne';
	if (!wanted) { return null; }
	const now = moment();
	if (now.isAfter(FREE_T_SHIRT_DATE, 'day')) {
		return 7.5;
	}
	return 0;
}
