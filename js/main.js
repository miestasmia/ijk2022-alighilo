// Add required stars
for (const el of $('main>form>div>[required]')) {
	const label = $$(`label[for=${el.id}]`);
	label.classList.add('label-required');
}

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

function getFee () {
	const programFee = getProgramFee();
	const signupFee = getSignupFee();
	const memberFee = $$('#kampo-membreco').checked ? 0 : 50;
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
