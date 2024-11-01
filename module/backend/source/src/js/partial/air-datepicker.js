import AirDatepicker from 'air-datepicker';

document.addEventListener('DOMContentLoaded', () => {
  function getDateFormatByType(type) {
    if (type === 'date') {
      return 'yyyy-MM-dd';
    }

    if (type === 'datetime') {
      return (date) => {
        if (Array.isArray(date)) {
          return date.map((d) => {
            d.setSeconds(new Date().getSeconds());

            return d.toISOString().split('.')[0].replace('T', ' ');
          });
        }

        date.setSeconds(new Date().getSeconds());

        return date.toISOString().split('.')[0].replace('T', ' ');
      };
    }

    if (type === 'month') {
      return 'yyyy-MM';
    }

    if (type === 'time') {
      return 'HH:mm';
    }

    return 'T';
  }
  // INPUT
  document.querySelectorAll('input[data-picker]').forEach((input) => {
    const type = input.getAttribute('data-picker') || 'date';
    const datesArray = input.value.length ? input.value.split(' - ') : [];

    const inputValue = document.createElement('input');
    inputValue.setAttribute('hidden', true);
    inputValue.setAttribute('name', input.name);

    input.removeAttribute('name');
    input.before(inputValue);

    const options = {
      selectedDates: datesArray,
      altField: inputValue,
      altFieldDateFormat: getDateFormatByType(type),
      multipleDates: input.hasAttribute('data-multiple') ? true : false,
      multipleDatesSeparator: ' - ',
      range: input.hasAttribute('data-range') ? true : false,
      timepicker: type.includes('time') ? true : false,
      onlyTimepicker: type === 'time' ? true : false,
      minDate: input.hasAttribute('data-min') ? input.getAttribute('data-min') : '',
      maxDate: input.hasAttribute('data-max') ? input.getAttribute('data-max') : '',
      minHours: input.hasAttribute('data-min-hour') ? input.getAttribute('data-min-hour') : 0,
      maxHours: input.hasAttribute('data-max-hour') ? input.getAttribute('data-max-hour') : 23,
      minMinutes: input.hasAttribute('data-min-minute') ? input.getAttribute('data-min-minute') : 0,
      maxMinutes: input.hasAttribute('data-max-minute') ? input.getAttribute('data-max-minute') : 59,
      hoursStep: input.hasAttribute('data-hour-step') ? input.getAttribute('data-hour-step') : 1,
      minutesStep: input.hasAttribute('data-minute-step') ? input.getAttribute('data-minute-step') : 1,
      position: input.getAttribute('data-position') || 'bottom left',
    };

    if (type.includes('date') && !input.required) {
      options.buttons = type.includes('date') && !input.required ? ['clear'] : [];
    }

    if (type === 'month') {
      options.view = 'months';
      options.minView = 'months';
      options.dateFormat = 'MMMM yyyy';
    }

    if (window?.Engine?.translation?.datepicker) {
      options.locale = window.Engine.translation.datepicker;
    }

    options.onSelect = ({ date, datepicker }) => {
      if (datepicker.opts.range && date.length === 2) {
        datepicker.hide();
      } else if (!datepicker.opts.multipleDates && !datepicker.opts.range && !type.includes('time')) {
        datepicker.hide();
      }
    };

    options.onHide = (isFinished) => {
      const initialDates = datesArray.map((d) => new Date(d));
      const selectedDates = input.datepicker && input.datepicker.selectedDates ? input.datepicker.selectedDates : [];
      const isRange = input.datepicker && input.datepicker.opts && input.datepicker.opts.range ? input.datepicker.opts.range : false;

      if (
        isFinished
        && JSON.stringify(initialDates) !== JSON.stringify(selectedDates)
        && ((isRange && selectedDates.length !== 1) || !isRange)
      ) {
        input.dispatchEvent(new CustomEvent('change', { bubbles: true }));
      }
    };

    const datepicker = new AirDatepicker(input, options);

    input.datepicker = datepicker;
    input.setAttribute('readonly', true);
  });

  // STATIC
  document.querySelectorAll('[data-calendar]').forEach((calendar) => {
    const type = calendar.getAttribute('data-calendar') || 'date';
    const dates = calendar.textContent.trim() || '';
    const datesArray = dates.length ? dates.split(' - ') : [];

    calendar.textContent = '';

    let options = {
      inline: true,
      selectedDates: datesArray,
      multipleDates: datesArray.length > 2 ? true : false,
      range: datesArray.length === 2 ? true : false,
      buttons: [],
      timepicker: false,
    };

    if (type === 'month') {
      options = {
        ...options,
        view: 'months',
        minView: 'months',
        dateFormat: 'MMMM yyyy',
      };
    }

    if (window?.Engine?.translation?.datepicker) {
      options = {
        ...options,
        locale: window.Engine.translation.datepicker,
      };
    }

    const datepicker = new AirDatepicker(calendar, options);

    calendar.datepicker = datepicker;
  });
});
