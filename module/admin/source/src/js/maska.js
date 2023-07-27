@@include("../../node_modules/maska/dist/maska.umd.js")

document.addEventListener('DOMContentLoaded', () => {
	new Maska.MaskInput("[data-maska]");
});
