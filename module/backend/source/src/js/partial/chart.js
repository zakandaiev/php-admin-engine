import Chart from 'chart.js/auto';

if (window?.Engine?.theme?.color) {
  Chart.defaults.backgroundColor = window.Engine.theme.color.box;
  Chart.defaults.borderColor = window.Engine.theme.color.border;
  Chart.defaults.color = window.Engine.theme.color.textMuted;
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.chart').forEach((chart) => {
    let data = chart.textContent.trim();

    if (!isJson(data)) {
      return false;
    }

    data = JSON.parse(data);

    if (!data || !data.type || !data.data) {
      return false;
    }

    const instance = new Chart(chart, data);

    chart.chart = instance;

    function isJson(item) {
      let value = typeof item !== 'string' ? JSON.stringify(item) : item;

      try {
        value = JSON.parse(value);
      } catch (e) {
        return false;
      }

      return typeof value === 'object' && value !== null;
    }
  });
});
