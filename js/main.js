function getRange (start, stop, step = 1) {
  return Array(Math.ceil((stop - start) / step)).fill(start).map((x, y) => x + y * step);
}

// Add required stars
function updateRequiredStars () {
	for (const el of $('main>form [required]')) {
		const label = $$(`label[for=${el.id}]`);
		if (!label) { continue; }
		label.classList.add('label-required');
	}
}
updateRequiredStars();

function setVisible (el, visible = true) {
	if (visible) { el.classList.remove('hide'); }
	else { el.classList.add('hide'); }
}

// Food buttons
for (let i = 0; i < 3; i++) {
	$$('#mangho-butono-' + i).addEventListener('click', function () {
		const checkboxes = $(`#mangho-tabelo>tbody>tr>td:nth-child(${i + 2})>input`);
		const hasUnchecked = Array.from(checkboxes)
			.filter(el => !el.disabled)
			.map(el => el.checked)
			.includes(false);
		for (const checkbox of checkboxes) {
			if (checkbox.disabled) { continue; }
			checkbox.checked = hasUnchecked;
		}
		// Without this sometimes the calculation takes place before the checkboxes are updated
		renderFee();
	});
}

// Hide dependent fields
function toggleDateFields () {
	// Hide or show the date pickers
	const hidden = $$('#kampo-partopreno-plentempa').checked;
	const datePickerDiv = $$('#kamparo-partopreno');
	setVisible(datePickerDiv, !hidden);
	const inputs = $('input,select', datePickerDiv);
	inputs.forEach(el => {
		setBoolAttribute(el, 'required', !hidden);
	});
	updateRequiredStars();

	// Toggle checked food selections
	if (hidden) {
		$(`#mangho-tabelo>tbody>tr input`).forEach(el => {
			el.disabled = false;
			el.checked = true;
		});
	} else {
		$(`#mangho-tabelo>tbody>tr input`).forEach(el => {
			el.disabled = false;
		});

		const arrivalDate = $$('#kampo-alveno').value;
		const leaveDate = $$('#kampo-foriro').value;

		if (arrivalDate && leaveDate) {
			const firstDay = moment(arrivalDate).date();
			const lastDay = moment(leaveDate).date();

			if (firstDay > 20) {
				for (const n of getRange(20, firstDay)) {
					$(`#mangho-tabelo>tbody>tr[data-date="${n}"] input`).forEach(el => {
						el.disabled = true;
						el.checked = false;
					});
				}
			}

			if (lastDay < 27) {
				for (const n of getRange(lastDay + 1, 28)) {
					$(`#mangho-tabelo>tbody>tr[data-date="${n}"] input`).forEach(el => {
						el.disabled = true;
						el.checked = false;
					});
				}
			}
		}
	}
	renderFee();
}
$('#kampo-partopreno-plentempa,#kampo-alveno,#kampo-foriro').forEach(el => el.addEventListener('change', toggleDateFields));
toggleDateFields();

function toggleVisaFields () {
	const fieldGroup = $$('#kamparo-vizo');
	const visible = $$('#kampo-vizo').checked;
	setVisible(fieldGroup, visible);

	const inputs = $('input,select', fieldGroup);
	inputs.forEach(el => {
		setBoolAttribute(el, 'required', visible);
	});
}
toggleVisaFields();
$$('#kampo-vizo').addEventListener('change', toggleVisaFields);

function toggleMemberField () {
	setVisible($$('#kamparo-ueakodo'), $$('#kampo-membreco').checked);
}
toggleMemberField();
$$('#kampo-membreco').addEventListener('change', toggleMemberField);
