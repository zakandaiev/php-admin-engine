@@include("../../node_modules/filepond/dist/filepond.min.js")
@@include("../../node_modules/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js")
@@include("../../node_modules/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js")
@@include("../../node_modules/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.min.js")
@@include("../../node_modules/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js")
@@include("../../node_modules/filepond-plugin-media-preview/dist/filepond-plugin-media-preview.min.js")
@@include("../../node_modules/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.js")
@@include("../../node_modules/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js")

FilePond.registerPlugin(
	FilePondPluginFileValidateSize,
	FilePondPluginFileValidateType,
	FilePondPluginFilePoster,
	FilePondPluginImagePreview,
	FilePondPluginMediaPreview,
	FilePondPluginPdfPreview,
	FilePondPluginImageExifOrientation
);

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('input[type="file"]').forEach(input => {
		const pond = FilePond.create(input, {
			server: {load: '/'},
			storeAsFile: true,
			instantUpload: false,
			allowProcess: false,
			allowRevert: false,
			allowReorder: true,
			dropOnPage: true,
			dropOnElement: false,
			files: initFiles(input),
			allowImagePreview: input.getAttribute('data-preview') == 'false' ? false : true,
			maxFileSize: input.hasAttribute('data-max-size') ? input.getAttribute('data-max-size') : null,
			maxTotalFileSize: input.hasAttribute('data-max-total-size') ? input.getAttribute('data-max-total-size') : null,
			maxFiles: input.hasAttribute('data-max-files') ? parseInt(input.getAttribute('data-max-files')) : null,
			styleItemPanelAspectRatio: input.hasAttribute('data-aspect-ratio') ? parseFloat(input.getAttribute('data-aspect-ratio')) : 0.5625,
			credits: false
		});

		if(input.hasAttribute('data-placeholder')) {
			pond.setOptions({
				labelIdle: input.getAttribute('data-placeholder')
			});
		}

		if (typeof ENGINE !== 'undefined' && ENGINE.translation && ENGINE.translation.filepond) {
			pond.setOptions(ENGINE.translation.filepond);
		}

		input.instance = pond;
	});

	function initFiles(input) {
		let files = [];

		if(!input.hasAttribute('data-value')) {
			return files;
		}

		let input_files = input.getAttribute('data-value');

		if(!input_files || input_files == '[]') {
			return files;
		}

		input_files = JSON.parse(input_files);

		input_files.forEach(file => {
			let file_obj = {
				source: file.value,
				options: {
					type: 'local',
					metadata: {}
				}
			};

			if(input.getAttribute('data-preview') == 'false' ? false : true) {
				file_obj.options.metadata.poster = file.poster;
			}

			files.push(file_obj);
		});

		return files;
	}
});
