<?php Theme::template('ui-header', ['title' => 'Form validation']); ?>

<div class="mt-2">
  <div class="row fill gap-xs cols-xs-1">

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h5 class="box__title">Custom validation</h5>
        </div>
        <div class="box__body">
          <form class="row gap-xs" data-validate>
            <div class="col-xs-12 col-md-4">
              <label>First name</label>
              <input type="text" value="Mark" placeholder="First name" required>
            </div>
            <div class="col-xs-12 col-md-4">
              <label>Last name</label>
              <input type="text" value="Otto" placeholder="Last name" required>
            </div>
            <div class="col-xs-12 col-md-4">
              <label>Username</label>
              <div class="input-group">
                <span>@</span>
                <input type="text" placeholder="Username" required>
              </div>
            </div>
            <div class="col-xs-12 col-md-6">
              <label>City</label>
              <input type="text" placeholder="City" required>
            </div>
            <div class="col-xs-12 col-md-3">
              <label>State</label>
              <select required>
                <option data-placeholder="true">Choose...</option>
                <option value="1">1</option>
                <option value="2">2</option>
              </select>
            </div>
            <div class="col-xs-12 col-md-3">
              <label>Zip</label>
              <input type="text" placeholder="Zip" required>
            </div>
            <div class="col-xs-12">
              <label>
                <input type="checkbox" required>
                <span>Agree to terms and conditions</span>
              </label>
            </div>
            <div class="col-xs-12">
              <button class="btn btn_primary" type="submit">Submit form</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h5 class="box__title">Browser default validation</h5>
        </div>
        <div class="box__body">
          <form class="row gap-xs">
            <div class="col-xs-12 col-md-4">
              <label>First name</label>
              <input type="text" value="Mark" placeholder="First name" required>
            </div>
            <div class="col-xs-12 col-md-4">
              <label>Last name</label>
              <input type="text" value="Otto" placeholder="Last name" required>
            </div>
            <div class="col-xs-12 col-md-4">
              <label>Username</label>
              <div class="input-group">
                <span>@</span>
                <input type="text" placeholder="Username" required>
              </div>
            </div>
            <div class="col-xs-12 col-md-6">
              <label>City</label>
              <input type="text" placeholder="City" required>
            </div>
            <div class="col-xs-12 col-md-3">
              <label>State</label>
              <select required>
                <option data-placeholder="true">Choose...</option>
                <option value="1">1</option>
                <option value="2">2</option>
              </select>
            </div>
            <div class="col-xs-12 col-md-3">
              <label>Zip</label>
              <input type="text" placeholder="Zip" required>
            </div>
            <div class="col-xs-12">
              <label>
                <input type="checkbox" required>
                <span>Agree to terms and conditions</span>
              </label>
            </div>
            <div class="col-xs-12">
              <button class="btn btn_primary" type="submit">Submit form</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>

<?php Theme::template('ui-footer'); ?>
