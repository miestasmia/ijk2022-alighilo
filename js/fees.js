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
};

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

	return {
		baseFees, otherFees, baseFee, totalFee
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
		formatMoney(fees.baseFees.housing);

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
}

function getProgramFee () {
	const bdate = $$('#kampo-naskightago').value;
	if (!bdate) { return null; }
	const age = moment('2022-08-20').diff(moment(bdate), 'y');

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
	}

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
	const housingType = $$('#kampo-loghado').value;
	if (!housingType) { return null; }
	return HOUSING_FEE[housingType];
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
