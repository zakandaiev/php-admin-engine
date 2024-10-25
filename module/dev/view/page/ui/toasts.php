<?php Theme::template('ui-header', ['title' => 'Notifications']); ?>

<div class="box">
  <div class="box__header">
    <h5 class="box__title">Toast notifications</h5>
  </div>

  <div class="box__body">
    <form id="notification-form" class="form row cols-xs-12 cols-md-3 gap-xs" data-native>
      <div class="form__column col">
        <div class="form__label">
          <label>Message</label>
        </div>

        <div class="form__input">
          <input type="text" name="message" placeholder="Message">
        </div>
      </div>

      <div class="form__column col">
        <div class="form__label">
          <label>Type</label>
        </div>

        <div class="form__input">
          <select name="type" required>
            <option value="default" selected>Default</option>
            <option value="success">Success</option>
            <option value="info">Info</option>
            <option value="warning">Warning</option>
            <option value="error">Error</option>
          </select>
        </div>
      </div>

      <div class="form__column col">
        <div class="form__label">
          <label>Duration</label>
        </div>

        <div class="form__input">
          <select name="duration">
            <option value="2500">2.5s</option>
            <option value="5000" selected>5s</option>
            <option value="7500">7.5s</option>
            <option value="10000">10s</option>
          </select>
        </div>
      </div>

      <div class="col-xs-12 form__submit" data-form-type="submit"><button type="submit" class="btn btn_primary">Show notification</button></div>
    </form>
  </div>
</div>

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

<?php Theme::template('ui-footer'); ?>
