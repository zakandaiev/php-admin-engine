@@include("../../node_modules/@fancyapps/ui/dist/fancybox/fancybox.umd.js")

document.addEventListener('DOMContentLoaded', () => {
	let options = {};

	if (typeof ENGINE !== 'undefined' && ENGINE.translation && ENGINE.translation.fancybox) {
		options = {
			...options,
			l10n: ENGINE.translation.fancybox
		}
	}

	Fancybox.bind('[data-fancybox]', options);
});
