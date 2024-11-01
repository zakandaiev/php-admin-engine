<?php Theme::template('ui-header', ['title' => 'Form validation']); ?>

<div class="mt-2">
  <div class="row fill gap-xs cols-xs-1">

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h5 class="box__title">Custom validation</h5>
        </div>

        <div class="box__body">
          <form class="form row gap-xs" data-validate>
            <div class="col-xs-12 col-md-4 form__column form__column_required" data-form-type="column" data-column-name="first-name">
              <div class="form__label">
                <label>First name</label>
              </div>

              <div class="form__input">
                <input name="first-name" type="text" required value="John" placeholder="First name">
              </div>

              <div class="form__error"></div>
            </div>

            <div class="col-xs-12 col-md-4 form__column form__column_required" data-form-type="column" data-column-name="last-name">
              <div class="form__label">
                <label>Last name</label>
              </div>

              <div class="form__input">
                <input name="last-name" type="text" required value="Doe" placeholder="Last name">
              </div>

              <div class="form__error"></div>
            </div>

            <div class="col-xs-12 col-md-4 form__column form__column_required" data-form-type="column" data-column-name="username">
              <div class="form__label">
                <label>Username</label>
              </div>

              <div class="form__input">
                <div class="input-group">
                  <span>@</span>
                  <input name="username" type="text" required placeholder="Username">
                </div>
              </div>

              <div class="form__error"></div>
            </div>

            <div class="col-xs-12 col-md-6 form__column form__column_required" data-form-type="column" data-column-name="city">
              <div class="form__label">
                <label>City</label>
              </div>

              <div class="form__input">
                <input name="city" type="text" required placeholder="City">
              </div>

              <div class="form__error"></div>
            </div>

            <div class="col-xs-12 col-md-3 form__column form__column_required" data-form-type="column" data-column-name="state">
              <div class="form__label">
                <label>State</label>
              </div>

              <div class="form__input">
                <select required data-placeholder="Choose...">
                  <option data-placeholder="true"></option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                </select>
              </div>

              <div class="form__error"></div>
            </div>

            <div class="col-xs-12 col-md-3 form__column form__column_required" data-form-type="column" data-column-name="zip">
              <div class="form__label">
                <label>Zip</label>
              </div>

              <div class="form__input">
                <input name="zip" type="text" required placeholder="Zip">
              </div>

              <div class="form__error"></div>
            </div>

            <div class="col-xs-12 form__column form__column_boolean" data-form-type="column" data-column-name="terms">
              <div class="form__input">
                <label>
                  <input type="checkbox" name="terms" value="true" required>
                  <span>Agree to terms and conditions</span>
                </label>
              </div>

              <div class="form__error"></div>
            </div>

            <div class="col-xs-12 form__submit" data-form-type="submit">
              <button type="submit" class="btn btn_primary">Submit</button>
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
          <form class="form row gap-xs">
            <div class="col-xs-12 col-md-4 form__column form__column_required" data-form-type="column" data-column-name="first-name">
              <div class="form__label">
                <label>First name</label>
              </div>

              <div class="form__input">
                <input name="first-name" type="text" required value="John" placeholder="First name">
              </div>

              <div class="form__error"></div>
            </div>

            <div class="col-xs-12 col-md-4 form__column form__column_required" data-form-type="column" data-column-name="last-name">
              <div class="form__label">
                <label>Last name</label>
              </div>

              <div class="form__input">
                <input name="last-name" type="text" required value="Doe" placeholder="Last name">
              </div>

              <div class="form__error"></div>
            </div>

            <div class="col-xs-12 col-md-4 form__column form__column_required" data-form-type="column" data-column-name="username">
              <div class="form__label">
                <label>Username</label>
              </div>

              <div class="form__input">
                <div class="input-group">
                  <span>@</span>
                  <input name="username" type="text" required placeholder="Username">
                </div>
              </div>

              <div class="form__error"></div>
            </div>

            <div class="col-xs-12 col-md-6 form__column form__column_required" data-form-type="column" data-column-name="city">
              <div class="form__label">
                <label>City</label>
              </div>

              <div class="form__input">
                <input name="city" type="text" required placeholder="City">
              </div>

              <div class="form__error"></div>
            </div>

            <div class="col-xs-12 col-md-3 form__column form__column_required" data-form-type="column" data-column-name="state">
              <div class="form__label">
                <label>State</label>
              </div>

              <div class="form__input">
                <select required data-placeholder="Choose...">
                  <option data-placeholder="true"></option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                </select>
              </div>

              <div class="form__error"></div>
            </div>

            <div class="col-xs-12 col-md-3 form__column form__column_required" data-form-type="column" data-column-name="zip">
              <div class="form__label">
                <label>Zip</label>
              </div>

              <div class="form__input">
                <input name="zip" type="text" required placeholder="Zip">
              </div>

              <div class="form__error"></div>
            </div>

            <div class="col-xs-12 form__column form__column_required" data-form-type="column" data-column-name="terms">
              <div class="form__input">
                <label>
                  <input type="checkbox" name="terms" value="true" required>
                  <span>Agree to terms and conditions</span>
                </label>
              </div>

              <div class="form__error"></div>
            </div>

            <div class="col-xs-12 form__submit" data-form-type="submit">
              <button type="submit" class="btn btn_primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>

<?php Theme::template('ui-footer'); ?>
