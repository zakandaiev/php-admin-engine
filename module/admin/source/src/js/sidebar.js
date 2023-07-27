const sidebar = document.querySelector('.sidebar');

if (sidebar) {
	// SIDEBAR STATE ON PAGE INIT
	sidebar.style.transition = 'none';
	setTimeout(() => sidebar.style.transition = '', 100);

	if (window.innerWidth >= 992) {
		sidebar.classList.remove('sidebar_hidden');
	}
	else {
		sidebar.classList.add('sidebar_hidden');
	}

	// SIDEBAR COLLAPSE
	document.addEventListener('click', event => {
		const trigger = event.target.closest('.sidebar__collapse > .sidebar__item');
		const collapse = event.target.closest('.sidebar__collapse');

		if (!trigger || !collapse) {
			return false;
		}

		const menu = collapse.querySelector('.sidebar__collapse-menu');

		if (!menu) {
			return false;
		}

		event.preventDefault();

		const menu_height = menu.scrollHeight;
		if (collapse.classList.contains('active')) {
			menu.style.height = '0px';
			collapse.classList.remove('active');
		}
		else {
			menu.style.height = `${menu_height}px`;
			collapse.classList.add('active');
		}
	});

	// SIDEBAR COLLAPSE ON PAGE INIT
	document.querySelectorAll('.sidebar__collapse.active').forEach(collapse => {
		const menu = collapse.querySelector('.sidebar__collapse-menu');

		if (!menu) {
			return false;
		}

		const menu_height = menu.scrollHeight;
		menu.style.transition = 'none';
		setTimeout(() => menu.style.transition = '', 100);
		menu.style.height = `${menu_height}px`;
	});

	// SIDEBAR STAGE TRIGGER
	document.addEventListener('click', event => {
		const sidebar_toggler = event.target.closest('[data-sidebar-toggle]');

		if(!sidebar_toggler) {
			return false;
		}

		event.preventDefault();

		sidebar.classList.toggle('sidebar_hidden');
	});

	// SIDEBAR STATE LISTENER
	window.addEventListener('resize', event => {
		const width = event.currentTarget.innerWidth;

		if (width >= 992) {
			sidebar.classList.remove('sidebar_hidden');
		}
		else {
			sidebar.classList.add('sidebar_hidden');
		}
	});
}
