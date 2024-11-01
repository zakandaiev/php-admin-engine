import { create, registerPlugin } from 'filepond';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginMediaPreview from 'filepond-plugin-media-preview';
import FilePondPluginPdfPreview from 'filepond-plugin-pdf-preview';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';

registerPlugin(FilePondPluginFileValidateSize);
registerPlugin(FilePondPluginFileValidateType);
registerPlugin(FilePondPluginImagePreview);
registerPlugin(FilePondPluginMediaPreview);
registerPlugin(FilePondPluginPdfPreview);
registerPlugin(FilePondPluginImageExifOrientation);

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('input[type="file"]').forEach((input) => {
    const pond = create(input, {
      server: {
        load: async (uri, load, error) => {
          const url = window?.Engine?.site?.url;

          try {
            const response = await fetch(`${url}/${uri}`);
            const blob = await response.blob();
            load(blob);
          } catch (e) {
            error(e.message);
          }
        },
      },
      files: (() => {
        const files = [];

        if (!input.hasAttribute('data-value')) {
          return files;
        }

        const inputFilesRaw = input.getAttribute('data-value');
        if (!inputFilesRaw || !inputFilesRaw.length) {
          return files;
        }

        try {
          const inputFiles = input.multiple ? JSON.parse(inputFilesRaw) : [inputFilesRaw];
          inputFiles.forEach((file) => {
            files.push({
              source: file,
              options: {
                type: 'local',
              },
            });
          });
        } catch (error) {
          // do nothing
        }

        return files;
      })(),
      storeAsFile: true,
      instantUpload: false,
      allowProcess: false,
      allowRevert: false,
      allowReorder: true,
      dropOnPage: true,
      dropOnElement: false,
      // eslint-disable-next-line
      allowImagePreview: input.getAttribute('data-preview') == 'false' ? false : true,
      maxFileSize: input.hasAttribute('data-max-size') ? input.getAttribute('data-max-size') : null,
      maxTotalFileSize: input.hasAttribute('data-max-total-size') ? input.getAttribute('data-max-total-size') : null,
      maxFiles: input.hasAttribute('data-max-files') ? parseInt(input.getAttribute('data-max-files'), 10) : null,
      styleItemPanelAspectRatio: input.hasAttribute('data-aspect-ratio') ? parseFloat(input.getAttribute('data-aspect-ratio')) : 0.5625,
      credits: false,
    });

    if (window?.Engine?.translation?.filepond) {
      pond.setOptions(window.Engine.translation.filepond);
    }

    if (input.hasAttribute('data-placeholder')) {
      pond.setOptions({
        labelIdle: input.getAttribute('data-placeholder'),
      });
    }

    input.instance = pond;
  });

  document.addEventListener('FilePond:updatefiles', (e) => {
    const input = e.target.querySelector('[type="file"]');
    if (!input) {
      return false;
    }

    if (!input.validity.valid) {
      const column = input.closest('.form__column');
      if (column) {
        column.classList.remove('form__column_valid');
        column.classList.add('form__column_invalid');

        return true;
      }

      input.classList.remove('valid');
      input.classList.add('invalid');
    } else {
      const column = input.closest('.form__column');
      if (column) {
        column.classList.remove('form__column_invalid');
        column.classList.add('form__column_valid');

        return true;
      }
      input.classList.remove('invalid');
      input.classList.add('valid');
    }
  });
});
