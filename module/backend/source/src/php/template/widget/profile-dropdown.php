<div class="header__item ml-2 dropdown dropdown_bottom-right">
  <img src="<?= pathResolveUrl(Asset::url(), 'img', 'no-avatar.jpg') ?>" class="w-100 h-100 fit-cover radius-xs" alt="test">

  <div class="dropdown__menu">
    <a href="<?= routeLink('profile') ?>" class="dropdown__item d-flex align-items-center gap-2"><i class="ti ti-user"></i> <?= t('profile.dropdown.profile') ?></a>

    <a href="<?= routeLink('profile-edit') ?>" class="dropdown__item d-flex align-items-center gap-2"><i class="ti ti-settings"></i> <?= t('profile.dropdown.settings') ?></a>

    <div class="dropdown__divider"></div>

    <a href="<?= routeLink('logout') ?>" class="dropdown__item d-flex align-items-center gap-2"><i class="ti ti-logout"></i> <?= t('profile.dropdown.logout') ?></a>
  </div>
</div>
