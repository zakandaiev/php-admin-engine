@@include("../../node_modules/air-datepicker/air-datepicker.js")

document.addEventListener('DOMContentLoaded', () => {

	// INPUT
	document.querySelectorAll('input[data-picker]').forEach(input => {
		const type = input.getAttribute('data-picker') || 'date';
		const dates_array = input.value.length ? input.value.split(' - ') : [];

		let options = {
			selectedDates: dates_array,
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

		options.onSelect = ({ date, datepicker }) => {
			if (datepicker.opts.range && date.length === 2) {
				datepicker.hide();
			}
			else if (!datepicker.opts.multipleDates && !datepicker.opts.range && !type.includes('time')) {
				datepicker.hide();
			}
		};

		options.onHide = (isFinished) => {
			const initial_dates = dates_array.map(d => new Date(d));
			const selected_dates = input.instance && input.instance.selectedDates ? input.instance.selectedDates : [];
			const is_range = input.instance && input.instance.opts && input.instance.opts.range ? input.instance.opts.range : false;

			if (
				isFinished
				&& JSON.stringify(initial_dates) !== JSON.stringify(selected_dates)
				&& (is_range && selected_dates.length !== 1 || !is_range)
			) {
				input.dispatchEvent(new CustomEvent('change', { bubbles: true }));
			}
		};

		const datepicker = new AirDatepicker(input, options);

		input.instance = datepicker;
		input.setAttribute('readonly', true);
	});

	// STATIC
	document.querySelectorAll('[data-calendar]').forEach(calendar => {
		const type = calendar.getAttribute('data-calendar') || 'date';
		const dates = calendar.textContent.trim() || '';
		const dates_array = dates.length ? dates.split(' - ') : [];

		calendar.textContent = '';

		let options = {
			inline: true,
			selectedDates: dates_array,
			multipleDates: dates_array.length > 2 ? true : false,
			range: dates_array.length === 2 ? true : false,
			buttons: [],
			timepicker: false
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

		const datepicker = new AirDatepicker(calendar, options);

		calendar.instance = datepicker;
	});

});
