document.querySelectorAll('textarea[data-wysiwyg]').forEach(textarea => {
	const wrapper = document.createElement('div');
	wrapper.classList.add('wysiwyg');

	textarea.after(wrapper);

	const editor = new EditorJS({
		holder: wrapper,
		logLevel: 'ERROR',
		autofocus: textarea.hasAttribute('data-focus') ? true : false,
		placeholder: textarea.placeholder || '',
		readOnly: false,
		tools: {
			header: {
				class: Header,
				shortcut: 'CMD+SHIFT+H',
				placeholder: 'Enter a header',
				levels: [2, 3, 4, 5, 6],
        defaultLevel: 2
			},
			quote: Quote,
			list: NestedList,
			embed: Embed,
			table: Table,
			delimiter: Delimiter,
			code: CodeTool,
			raw: RawTool,
			Marker: {
				class: Marker,
				shortcut: 'CMD+SHIFT+M',
			},
			inlineCode: {
				class: InlineCode,
				shortcut: 'CMD+SHIFT+C',
			},
		},
		onChange: async (api, event) => {
			const { blocks } = await api.saver.save();
			if (blocks.length) {
				textarea.value = JSON.stringify(blocks);
			}
			else {
				textarea.value = '';
			}
		}
	});

	wrapper.instance = editor;
	textarea.instance = editor;
	textarea.setAttribute('readonly', true);
	textarea.classList.add('hidden');
});
