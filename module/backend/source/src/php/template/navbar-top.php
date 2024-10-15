<header class="header">
  <div class="container-fluid">
    <div class="header__wrapper">

      <button type="button" class="header__burger" data-sidebar-toggle>
        <i class="ti ti-menu-2"></i>
      </button>

      <nav class="header__nav">
        <button type="button" class="header__item" data-theme-toggle>
          <i class="ti ti-moon"></i>
          <i class="ti ti-sun"></i>
        </button>

        <?php Theme::template('widget/notification-dropdown'); ?>
        <?php Theme::template('widget/lang-dropdown'); ?>
        <?php Theme::template('widget/profile-dropdown'); ?>
      </nav>

    </div>
  </div>
</header>
