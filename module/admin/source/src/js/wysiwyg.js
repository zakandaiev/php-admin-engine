@@include("../../node_modules/quill/dist/quill.min.js")

document.addEventListener('DOMContentLoaded', () => {

document.querySelectorAll('textarea[data-wysiwyg]').forEach(textarea => {
	const wysiwyg = document.createElement('div');
	wysiwyg.classList.add('wysiwyg');

	const quill = document.createElement('div');
	quill.innerHTML = textarea.value;

	wysiwyg.appendChild(quill);

	textarea.after(wysiwyg);

	wysiwyg.appendChild(textarea);

	const quill_icons = Quill.import('ui/icons');
	quill_icons['expand'] = `<i class="icon icon-arrows-maximize"></i><i class="icon icon-arrows-minimize"></i>`;

	const editor = new Quill(quill, {
		modules: {
			toolbar: {
					container: [
						[{ header: [false, 3, 2] }],
						['bold', 'italic', 'underline', 'strike'],
						[{'align': []}, {'list': 'ordered'}, {'list': 'bullet'}],
						[{'color': []}, {'background': []}],
						['link', 'image', 'video', 'blockquote', 'code'],
						[{'indent': '-1'}, {'indent': '+1'}],
						[{'script': 'sub'}, {'script': 'super'}],
						['clean'], ['expand']
					],
					handlers: {
						'image': () => handleImage(),
						'expand': () => handleExpand()
					}
			}
		},
		placeholder: textarea.placeholder || '',
		readOnly: textarea.disabled ? true : false,
		theme: 'snow'
	});

	// POPULATE
	// editor.setContents(JSON.parse(textarea.value).ops);

	// UPDATE TEXTAREA VALUE
	editor.on('editor-change', event => {
		// textarea.value = JSON.stringify(editor.getContents());
		textarea.value = editor.root.innerHTML;
	});

	// EXPAND
	function handleExpand() {
		const expand = wysiwyg.querySelector('.ql-expand');

		function maximize() {
			wysiwyg.classList.add('wysiwyg_fullscreen');
			if (expand) expand.classList.add('active');
		}

		function minimize() {
			wysiwyg.classList.remove('wysiwyg_fullscreen');
			if (expand) expand.classList.remove('active');
		}

		wysiwyg.classList.contains('wysiwyg_fullscreen') ?  minimize() : maximize();
	}

	// IMAGE UPLOAD
	// const Image = editor.import('formats/image');
	// Image.className = 'image-fluid';
	// editor.register(Image, true);

	// function handleImage() {
	// 	const input = document.createElement('input');
	// 	input.setAttribute('type', 'file');
	// 	input.setAttribute('accept', 'image/*');
	// 	input.click();

	// 	input.onchange = () => {
	// 		const file = input.files[0];

	// 		if(file) {
	// 			let formData = new FormData();
	// 			formData.append('file', file);
	// 			formData.append(SETTING.csrf.key, SETTING.csrf.token);

	// 			editor.enable(false);

	// 			fetch(BASE_URL + '/upload/', {method: 'POST', body: formData})
	// 			.then(response => response.text())
	// 			.then(data => {
	// 				const selection = editor.getSelection().index;
	// 				const image_url = BASE_URL + '/' + data;

	// 				editor.insertEmbed(selection, 'image', image_url);
	// 				editor.setSelection(selection + 1);
	// 			})
	// 			.catch(error => {
	// 				if (toast instanceof Function) {
	// 					toast('error', error);
	// 				}
	// 				else {
	// 					console.log(error);
	// 				}
	// 			})
	// 			.finally(() => {
	// 				editor.enable(true);
	// 			});
	// 		}
	// 	};
	// }

	wysiwyg.instance = editor;
	textarea.instance = editor;
	textarea.setAttribute('readonly', true);
	textarea.classList.add('hidden');
});

});
