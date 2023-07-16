document.querySelectorAll('input[data-picker]').forEach(input => {
	const type = input.getAttribute('data-picker') || 'date';

	let options = {
		selectedDates: input.value ? [input.value] : [],
		multipleDates: input.hasAttribute('data-multiple') ? true : false,
		multipleDatesSeparator: ' - ',
		range: input.hasAttribute('data-range') ? true : false,
		buttons: type.includes('date') ? ['clear'] : [],
		timepicker: type.includes('time') ? true : false,
		onlyTimepicker: type === 'time' ? true : false,
		minDate: input.hasAttribute('data-min') ? input.getAttribute('data-min') : '',
		maxDate: input.hasAttribute('data-max') ? input.getAttribute('data-max') : '',
		minHours: input.hasAttribute('data-min-hour') ? input.getAttribute('data-min-hour') : 0,
		maxHours: input.hasAttribute('data-max-hour') ? input.getAttribute('data-max-hour') : 23,
		minMinutes: input.hasAttribute('data-min-minute') ? input.getAttribute('data-min-minute') : 0,
		maxMinutes: input.hasAttribute('data-max-minute') ? input.getAttribute('data-max-minute') : 59,
		hoursStep: input.hasAttribute('data-hour-step') ? input.getAttribute('data-hour-step') : 1,
		minutesStep: input.hasAttribute('data-minute-step') ? input.getAttribute('data-minute-step') : 1
	}

	if (type === 'month') {
		options = {
			...options,
			view: 'months',
			minView: 'months',
			dateFormat: 'MMMM yyyy'
		}
	}

	if (typeof ENGINE !== 'undefined' && ENGINE.translation && ENGINE.translation.datepicker) {
		options = {
			...options,
			locale: ENGINE.translation.datepicker
		}
	}

	const datepicker = new AirDatepicker(input, options);

	input.instance = datepicker;
	input.setAttribute('readonly', true);
});