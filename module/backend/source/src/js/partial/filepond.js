import { create, registerPlugin } from 'filepond';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFilePoster from 'filepond-plugin-file-poster';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginMediaPreview from 'filepond-plugin-media-preview';
import FilePondPluginPdfPreview from 'filepond-plugin-pdf-preview';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';

registerPlugin(
  FilePondPluginFileValidateSize,
  FilePondPluginFileValidateType,
  FilePondPluginFilePoster,
  FilePondPluginImagePreview,
  FilePondPluginMediaPreview,
  FilePondPluginPdfPreview,
  FilePondPluginImageExifOrientation,
);

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('input[type="file"]').forEach((input) => {
    const pond = create(input, {
      server: { load: '/' },
      storeAsFile: true,
      instantUpload: false,
      allowProcess: false,
      allowRevert: false,
      allowReorder: true,
      dropOnPage: true,
      dropOnElement: false,
      files: initFiles(input),
      // eslint-disable-next-line
      allowImagePreview: input.getAttribute('data-preview') == 'false' ? false : true,
      maxFileSize: input.hasAttribute('data-max-size') ? input.getAttribute('data-max-size') : null,
      maxTotalFileSize: input.hasAttribute('data-max-total-size') ? input.getAttribute('data-max-total-size') : null,
      maxFiles: input.hasAttribute('data-max-files') ? parseInt(input.getAttribute('data-max-files'), 10) : null,
      styleItemPanelAspectRatio: input.hasAttribute('data-aspect-ratio') ? parseFloat(input.getAttribute('data-aspect-ratio')) : 0.5625,
      credits: false,
    });

    if (typeof Engine !== 'undefined' && Engine.translation && Engine.translation.filepond) {
      pond.setOptions(Engine.translation.filepond);
    }

    if (input.hasAttribute('data-placeholder')) {
      pond.setOptions({
        labelIdle: input.getAttribute('data-placeholder'),
      });
    }

    input.instance = pond;
  });

  function initFiles(input) {
    const files = [];

    if (!input.hasAttribute('data-value')) {
      return files;
    }

    let inputFiles = input.getAttribute('data-value');

    if (!inputFiles || inputFiles === '[]') {
      return files;
    }

    inputFiles = JSON.parse(inputFiles);

    inputFiles.forEach((file) => {
      const fileObj = {
        source: file.value,
        options: {
          type: 'local',
          metadata: {},
        },
      };

      // eslint-disable-next-line
      if (input.getAttribute('data-preview') == 'false' ? false : true) {
        fileObj.options.metadata.poster = file.poster;
      }

      files.push(fileObj);
    });

    return files;
  }
});
