<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::block('navbar/top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <nav class="breadcrumb">
        <span class="breadcrumb__item"><a href="<?= Route::link('dashboard') ?>">Home</a></span>
        <span class="breadcrumb__item"><a href="<?= Route::link('ui-home') ?>">Dev UI</a></span>
        <span class="breadcrumb__item">Notifications</span>
      </nav>

      <h2 class="section__title">Notifications</h2>

      <div class="box">
        <div class="box__header">
          <h5 class="box__title">Toast notifications</h5>
        </div>
        <div class="box__body">
          <form id="notification-form" class="row gap-xs" data-native>
            <div class="col-xs-12 col-md-4">
              <label>Message</label>
              <input type="text" name="message" placeholder="Message">
            </div>
            <div class="col-xs-12 col-md-4">
              <label>Type</label>
              <select name="type" required>
                <option value="default" selected>Default</option>
                <option value="success">Success</option>
                <option value="info">Info</option>
                <option value="warning">Warning</option>
                <option value="error">Error</option>
              </select>
            </div>
            <div class="col-xs-12 col-md-4">
              <label>Type</label>
              <select name="duration">
                <option value="2500">2.5s</option>
                <option value="5000" selected>5s</option>
                <option value="7500">7.5s</option>
                <option value="10000">10s</option>
              </select>
            </div>
            <div class="col-xs-12">
              <button class="btn btn_primary" type="submit">Show notification</button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </section>

  <?php Theme::block('navbar/bottom'); ?>

</main>

<?php Theme::footer(); ?>

<script defer>
  const notificationForm = document.getElementById('notification-form');

  notificationForm.addEventListener('submit', event => {
    event.preventDefault();

    const message = notificationForm.querySelector('[name="message"]').value || 'Lorem ipsum dolor sit amet consectetur adipisicing elit.';
    const type = notificationForm.querySelector('[name="type"]').value;
    const duration = notificationForm.querySelector('[name="duration"]').value;

    window.toast(message, type, duration);
  });
</script>
