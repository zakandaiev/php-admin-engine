<div class="header__item ml-2 dropdown dropdown_bottom-right">
	<img src="https://demo.zakandaiev.com/upload/demo/no-avatar.jpg" class="w-100 h-100 fit-cover radius" alt="test">

	<div class="dropdown__menu">
		<a href="<?= site('url_language') ?>/admin/profile" class="dropdown__item d-flex align-items-center gap-2"><i class="icon icon-user"></i> <?= __('admin.navbar.profile') ?></a>
		<a href="<?= site('url_language') ?>/admin/profile/edit" class="dropdown__item d-flex align-items-center gap-2"><i class="icon icon-settings"></i> <?= __('admin.navbar.settings') ?></a>
		<div class="dropdown__divider"></div>
		<a href="<?= site('url_language') ?>/admin/logout" class="dropdown__item d-flex align-items-center gap-2"><i class="icon icon-logout"></i> <?= __('admin.navbar.logout') ?></a>
	</div>
</div>
