<?php Theme::template('ui-header', ['title' => 'Form elements']); ?>

<div class="mt-2">
  <div class="row fill gap-xs cols-xs-1 cols-md-2">
    <div class="col">
      <div class="row fill gap-xs cols-xs-1">

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Input</h5>
            </div>

            <div class="box__body">
              <form class="form">
                <div class="form__column">
                  <div class="form__input">
                    <input type="text" placeholder="Input">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Textarea</h5>
            </div>

            <div class="box__body">
              <form class="form">
                <div class="form__column">
                  <div class="form__input">
                    <textarea placeholder="Textarea" rows="1"></textarea>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Checkboxes</h5>
            </div>

            <div class="box__body">
              <form class="form">
                <div class="form__column">
                  <div class="form__input">
                    <label>
                      <input type="checkbox">
                      <span>Option</span>
                    </label>
                  </div>
                </div>

                <div class="form__column">
                  <div class="form__input">
                    <label>
                      <input type="checkbox" disabled>
                      <span>Option disabled</span>
                    </label>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Radios</h5>
            </div>

            <div class="box__body">
              <form class="form">
                <div class="form__column">
                  <div class="form__input">
                    <label>
                      <input type="radio" name="radio-example">
                      <span>Option</span>
                    </label>
                  </div>
                </div>

                <div class="form__column">
                  <div class="form__input">
                    <label>
                      <input type="radio" name="radio-example" disabled>
                      <span>Option disabled</span>
                    </label>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Switches</h5>
            </div>

            <div class="box__body">
              <form class="form">
                <div class="form__column">
                  <div class="form__input">
                    <label class="switch">
                      <input type="hidden" name="switch-default" value="false">
                      <input type="checkbox" name="switch-default" value="true">
                      <span class="switch__slider"></span>
                      <span>Switch</span>
                    </label>
                  </div>
                </div>

                <div class="form__column">
                  <div class="form__input">
                    <label class="switch">
                      <input type="hidden" name="switch-disabled" value="false" disabled>
                      <input type="checkbox" name="switch-disabled" value="true" disabled>
                      <span class="switch__slider"></span>
                      <span>Switch disabled</span>
                    </label>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Groups</h5>
            </div>

            <div class="box__body">
              <form class="form">
                <div class="form__column">
                  <div class="form__input">
                    <div class="input-group">
                      <span>@</span>
                      <input type="text" placeholder="Username">
                    </div>
                  </div>
                </div>

                <div class="form__column">
                  <div class="form__input">
                    <div class="input-group">
                      <input type="text" placeholder="Email">
                      <span>@example.com</span>
                    </div>
                  </div>
                </div>

                <div class="form__column">
                  <div class="form__input">
                    <div class="input-group">
                      <span><i class="ti ti-calendar"></i></span>
                      <input type="text" data-picker="date" placeholder="Date">
                    </div>
                  </div>
                </div>

                <div class="form__column">
                  <div class="form__input">
                    <div class="input-group">
                      <span>www.</span>
                      <select data-placeholder="Select...">
                        <option data-placeholder="true">
                        <option>
                        <option>google.com</option>
                        <option>microsoft.com</option>
                        <option>bing.com</option>
                      </select>
                      <button type="button" class="cursor-pointer">Go!</button>
                    </div>
                  </div>
                </div>

                <div class="form__column">
                  <div class="form__input">
                    <div class="input-group">
                      <button type="button" class="cursor-pointer"><i class="ti ti-minus"></i></button>
                      <input type="number" placeholder="Amount">
                      <button type="button" class="cursor-pointer"><i class="ti ti-plus"></i></button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="col">
      <div class="row fill gap-xs cols-xs-1">

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Selects</h5>
            </div>

            <div class="box__body">
              <form class="form row cols-xs-2 gap-xs">
                <div class="form__column col">
                  <div class="form__label">
                    <label>Single</label>
                  </div>

                  <div class="form__input">
                    <select data-placeholder="Single">
                      <option data-placeholder="true"></option>
                      <option>One</option>
                      <option>Two</option>
                      <option>Three</option>
                      <option disabled>Disabled</option>
                    </select>
                  </div>
                </div>

                <div class="form__column col">
                  <div class="form__label">
                    <label>Multiple</label>
                  </div>

                  <div class="form__input">
                    <select multiple data-placeholder="Multiple">
                      <optgroup label="Dairy">
                        <option value="Milk">Milk</option>
                        <option value="Cheese">Cheese</option>
                        <option value="Butter">Butter</option>
                        <option value="Ice Cream">Ice Cream</option>
                      </optgroup>
                      <optgroup label="Meat">
                        <option value="Beef">Beef</option>
                        <option value="Ham">Ham</option>
                        <option value="Pork">Pork</option>
                        <option value="Sausage">Sausage</option>
                        <option value="Chicken">Chicken</option>
                        <option value="Turkey">Turkey</option>
                      </optgroup>
                      <optgroup label="Fruits">
                        <option value="Apple">Apple</option>
                        <option value="Banana">Banana</option>
                        <option value="Grape">Grape</option>
                        <option value="Orange">Orange</option>
                        <option value="Strawberry">Strawberry</option>
                        <option value="Blueberry">Blueberry</option>
                        <option value="Raspberry">Raspberry</option>
                      </optgroup>
                      <optgroup label="Vegetables">
                        <option value="Carrot">Carrot</option>
                        <option value="Tomato">Tomato</option>
                        <option value="Broccoli">Broccoli</option>
                        <option value="Celery">Celery</option>
                        <option value="Corn">Corn</option>
                        <option value="Pumpkin">Pumpkin</option>
                        <option value="Kale">Kale</option>
                        <option value="Spinach">Spinach</option>
                      </optgroup>
                    </select>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Dates</h5>
            </div>

            <div class="box__body">
              <form class="form row cols-xs-2 gap-xs">
                <div class="form__column col">
                  <div class="form__label">
                    <label>Date</label>
                  </div>

                  <div class="form__input">
                    <input type="text" data-picker="date" placeholder="Date">
                  </div>
                </div>

                <div class="form__column col">
                  <div class="form__label">
                    <label>DateTime</label>
                  </div>

                  <div class="form__input">
                    <input type="text" data-picker="datetime" placeholder="DateTime">
                  </div>
                </div>

                <div class="form__column col">
                  <div class="form__label">
                    <label>Multiple</label>
                  </div>

                  <div class="form__input">
                    <input type="text" data-picker="date" data-multiple placeholder="Multiple">
                  </div>
                </div>

                <div class="form__column col">
                  <div class="form__label">
                    <label>Range</label>
                  </div>

                  <div class="form__input">
                    <input type="text" data-picker="date" data-range placeholder="Range">
                  </div>
                </div>

                <div class="form__column col">
                  <div class="form__label">
                    <label>Month picker</label>
                  </div>

                  <div class="form__input">
                    <input type="text" data-picker="month" placeholder="Month picke">
                  </div>
                </div>

                <div class="form__column col">
                  <div class="form__label">
                    <label>Time picker</label>
                  </div>

                  <div class="form__input">
                    <input type="text" data-picker="time" placeholder="Time picker">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Masks</h5>
            </div>

            <div class="box__body">
              <form class="form row cols-xs-2 gap-xs">
                <div class="form__column col">
                  <div class="form__label">
                    <label>Phone</label>
                  </div>

                  <div class="form__input">
                    <input type="text" data-maska="+38 (0A#) ### ## ##" data-maska-tokens="A:[3,5-9]" placeholder="+38 (000) 000 00 00">
                  </div>
                </div>

                <div class="form__column col">
                  <div class="form__label">
                    <label>HEX-color</label>
                  </div>

                  <div class="form__input">
                    <input type="text" data-maska="!#HHHHHH" data-maska-tokens="H:[0-9a-fA-F]" placeholder="#123456">
                  </div>
                </div>

                <div class="form__column col">
                  <div class="form__label">
                    <label>IP-address</label>
                  </div>

                  <div class="form__input">
                    <input type="text" data-maska="#00.#00.#00.#00" data-maska-tokens="0:[0-9]:optional" placeholder="xxx.xxx.xxx.xxx">
                  </div>
                </div>

                <div class="form__column col">
                  <div class="form__label">
                    <label>Money</label>
                  </div>

                  <div class="form__input">
                    <input type="text" data-maska="0.99" data-maska-tokens="0:\d:multiple|9:\d:optional" placeholder="123.45">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title"></h5>
            </div>

            <div class="box__body">
              <form class="form">
                <div class="form__column">
                  <div class="form__label">
                    <label>Сolor picker</label>
                  </div>

                  <div class="form__input">
                    <input type="color" placeholder="Input">
                  </div>
                </div>

                <div class="form__column">
                  <div class="form__label">
                    <label>Range slider</label>
                  </div>

                  <div class="form__input">
                    <input type="range" min="1" max="100" step="1" value="1">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>

</div>

<?php Theme::template('ui-footer'); ?>
