import Quill from 'quill';
import { request } from '@/js/util/request';
import toast from '@/js/util/toast';

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('textarea[data-wysiwyg]').forEach((textarea) => {
    const wysiwyg = document.createElement('div');
    wysiwyg.classList.add('wysiwyg');

    const quill = document.createElement('div');
    quill.innerHTML = textarea.value;

    wysiwyg.appendChild(quill);

    textarea.after(wysiwyg);

    quill.before(textarea);

    const quillIcons = Quill.import('ui/icons');
    quillIcons.expand = '<i class="ti ti-arrows-maximize"></i><i class="ti ti-arrows-minimize"></i>';

    const editor = new Quill(quill, {
      modules: {
        toolbar: {
          container: [
            [{ header: [false, 3, 2] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ align: [] }, { list: 'ordered' }, { list: 'bullet' }],
            [{ color: [] }, { background: [] }],
            ['link', 'image', 'video', 'blockquote', 'code'],
            [{ indent: '-1' }, { indent: '+1' }],
            [{ script: 'sub' }, { script: 'super' }],
            ['clean'], ['expand'],
          ],
          handlers: {
            image: () => handleImage(),
            expand: () => handleExpand(),
          },
        },
      },
      placeholder: textarea.placeholder || '',
      readOnly: textarea.disabled ? true : false,
      theme: 'snow',
    });

    // POPULATE OLD
    // editor.setContents(JSON.parse(textarea.value).ops);

    // UPDATE TEXTAREA VALUE
    editor.on('editor-change', () => {
      // SET OLD
      // textarea.value = JSON.stringify(editor.getContents());
      textarea.value = editor.root.innerHTML?.trim();

      if (textarea.value === '<p><br></p>' || textarea.value === '<p></p>' || textarea.value === '<br>') {
        textarea.value = '';
      }

      textarea.dispatchEvent(new CustomEvent('change', { bubbles: true }));
    });

    // EXPAND
    editor.maximize = () => {
      wysiwyg.classList.add('wysiwyg_fullscreen');

      const expand = wysiwyg.querySelector('.ql-expand');
      if (expand) {
        expand.classList.add('active');
      }
    };
    editor.minimize = () => {
      wysiwyg.classList.remove('wysiwyg_fullscreen');

      const expand = wysiwyg.querySelector('.ql-expand');
      if (expand) {
        expand.classList.remove('active');
      }
    };

    function handleExpand() {
      if (wysiwyg.classList.contains('wysiwyg_fullscreen')) {
        editor.minimize();
      } else {
        editor.maximize();
      }
    }

    // TODO upload loader
    function handleImage() {
      const input = document.createElement('input');
      input.setAttribute('type', 'file');
      input.setAttribute('accept', 'image/*');
      input.click();

      input.onchange = async () => {
        const img = input.files[0];

        if (!img) {
          return false;
        }

        const formData = new FormData();
        formData.append('image', img);
        formData.append(window?.Engine?.csrf?.key, window?.Engine?.csrf?.token);

        editor.enable(false);

        const data = await request(
          `${window?.Engine?.site?.url}/backend/upload/wysiwyg`,
          {
            headers: {},
            method: 'POST',
            body: formData,
          },
          window?.Engine?.api?.timeoutMs,
          window?.Engine?.api?.delayMs,
        );

        if (data.code === 200 && data.status === 'success') {
          const selection = editor.getSelection().index;
          const imageUrl = `${window?.Engine?.site?.url}/${data.data}`;

          editor.insertEmbed(selection, 'image', imageUrl);
          editor.setSelection(selection + 1);
        } else {
          toast(data.message, data.status);
        }

        editor.enable(true);
      };
    }

    wysiwyg.wysiwyg = editor;
    textarea.wysiwyg = editor;
  });

  // MINIMIZE BY ESC
  document.addEventListener('keydown', (event) => {
    let isEscape = false;

    if ('key' in event) {
      isEscape = (event.key === 'Escape' || event.key === 'Esc');
    } else {
      isEscape = (event.keyCode === 27);
    }

    if (!isEscape) {
      return false;
    }

    document.querySelectorAll('.wysiwyg_fullscreen').forEach((el) => el.wysiwyg.minimize());
  });
});
