<div class="header__item ml-2 dropdown dropdown_bottom-right">
  <img src="<?= resolveUrl(Asset::url(), 'img', 'no-avatar.jpg') ?>" class="w-100 h-100 fit-cover radius-xs" alt="test">

  <div class="dropdown__menu">
    <a href="<?= Route::link('profile') ?>" class="dropdown__item d-flex align-items-center gap-2"><i class="ti ti-user"></i> <?= t('profile.dropdown.profile') ?></a>

    <a href="<?= Route::link('profile-edit') ?>" class="dropdown__item d-flex align-items-center gap-2"><i class="ti ti-settings"></i> <?= t('profile.dropdown.settings') ?></a>

    <div class="dropdown__divider"></div>

    <a href="<?= Route::link('logout') ?>" class="dropdown__item d-flex align-items-center gap-2"><i class="ti ti-logout"></i> <?= t('profile.dropdown.logout') ?></a>
  </div>
</div>
