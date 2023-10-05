@@include("../../node_modules/chart.js/dist/chart.umd.js")

if (typeof ENGINE !== 'undefined' && ENGINE.theme && ENGINE.theme.color) {
	Chart.defaults.backgroundColor = ENGINE.theme.color.box;
	Chart.defaults.borderColor = ENGINE.theme.color.border;
	Chart.defaults.color = ENGINE.theme.color.text_muted;
}

document.addEventListener('DOMContentLoaded', () => {

	document.querySelectorAll('.chart').forEach(chart => {
		let data = chart.textContent.trim();

		if (!isJson(data)) {
			return false;
		}

		data = JSON.parse(data);

		if (!data || !data.type || !data.data) {
			return false;
		}

		const instance = new Chart(chart, data);

		chart.instance = instance;

		function isJson(item) {
			let value = typeof item !== 'string' ? JSON.stringify(item) : item;

			try {
				value = JSON.parse(value);
			}
			catch (e) {
				return false;
			}

			return typeof value === 'object' && value !== null;
		}
	});

});
