import AirDatepicker from 'air-datepicker';

document.addEventListener('DOMContentLoaded', () => {
  // INPUT
  document.querySelectorAll('input[data-picker]').forEach((input) => {
    const type = input.getAttribute('data-picker') || 'date';
    const datesArray = input.value.length ? input.value.split(' - ') : [];

    const options = {
      selectedDates: datesArray,
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

    if (typeof Engine !== 'undefined' && Engine.translation && Engine.translation.datepicker) {
      options.locale = Engine.translation.datepicker;
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
      const selectedDates = input.instance && input.instance.selectedDates ? input.instance.selectedDates : [];
      const isRange = input.instance && input.instance.opts && input.instance.opts.range ? input.instance.opts.range : false;

      if (
        isFinished
        && JSON.stringify(initialDates) !== JSON.stringify(selectedDates)
        && ((isRange && selectedDates.length !== 1) || !isRange)
      ) {
        input.dispatchEvent(new CustomEvent('change', { bubbles: true }));
      }
    };

    const datepicker = new AirDatepicker(input, options);

    input.instance = datepicker;
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

    if (typeof Engine !== 'undefined' && Engine.translation && Engine.translation.datepicker) {
      options = {
        ...options,
        locale: Engine.translation.datepicker,
      };
    }

    const datepicker = new AirDatepicker(calendar, options);

    calendar.instance = datepicker;
  });
});
