<header class="header">
  <div class="container-fluid">
    <div class="header__wrapper">

      <button type="button" class="header__burger" data-sidebar-toggle>
        <i class="ti ti-menu-2"></i>
      </button>

      <nav class="header__nav">
        <?php Theme::template('widget/navbar-theme'); ?>
        <?php Theme::template('widget/navbar-notification'); ?>
        <?php Theme::template('widget/navbar-lang'); ?>
        <?php Theme::template('widget/navbar-profile'); ?>
      </nav>

    </div>
  </div>
</header>
