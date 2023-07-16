const BASE_URL = window.location.protocol + '//' + window.location.host;
const PATH_URL = window.location.pathname;
const FULL_URL = window.location.href;
const GET_PARAM = (key) => {
	return new URL(FULL_URL).searchParams.get(key);
};

@@include('partial/watermark.js')

// UTILS
@@include('util/fade-out.js')
@@include('util/sleep.js')
@@include('util/smooth-scroll.js')
@@include('util/replace-broken-image.js')

document.addEventListener('DOMContentLoaded', () => {
	// PARTIALS
	@@include('partial/accrodion.js')
	@@include('partial/dropdown.js')
	@@include('partial/format-tel-link.js')
	@@include('partial/external-link-norefer.js')
	@@include('partial/protect-image.js')
	@@include('partial/responsive-table.js')
	@@include('partial/popover.js')
	@@include('partial/tab.js')
	@@include('partial/textarea.js')
	@@include('partial/tooltip.js')

	// PLUGINS
	@@include("plugin/air-datepicker.js")
	@@include("plugin/maska.js")
	@@include("plugin/slimselect.js")
	@@include("plugin/wysiwyg.js")
});
