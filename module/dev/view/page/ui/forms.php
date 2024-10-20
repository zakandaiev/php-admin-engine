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
              <input type="text" placeholder="Input">
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Textarea</h5>
            </div>
            <div class="box__body">
              <textarea placeholder="Textarea"></textarea>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Checkboxes</h5>
            </div>
            <div class="box__body">

              <label>
                <input type="checkbox">
                <span>Option</span>
              </label>

              <br>

              <label>
                <input type="checkbox" disabled>
                <span>Option disabled</span>
              </label>

              <br>

              <label class="mb-0 mr-3">
                <input type="checkbox">
                <span>Option 1</span>
              </label>

              <label class="mb-0 mr-3">
                <input type="checkbox">
                <span>Option 2</span>
              </label>

              <label class="mb-0 mr-3">
                <input type="checkbox">
                <span>Option 3</span>
              </label>

            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Radios</h5>
            </div>
            <div class="box__body">

              <label>
                <input type="radio" name="radio-example">
                <span>Option</span>
              </label>

              <br>

              <label>
                <input type="radio" name="radio-example" disabled>
                <span>Option disabled</span>
              </label>

              <br>

              <label class="mb-0 mr-3">
                <input type="radio" name="radio-example">
                <span>Option 1</span>
              </label>

              <label class="mb-0 mr-3">
                <input type="radio" name="radio-example">
                <span>Option 2</span>
              </label>

              <label class="mb-0 mr-3">
                <input type="radio" name="radio-example">
                <span>Option 3</span>
              </label>

            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Switches</h5>
            </div>
            <div class="box__body">

              <label class="switch">
                <input type="hidden" name="switch-default" value="false">
                <input type="checkbox" name="switch-default" value="true">
                <span class="switch__slider"></span>
                <span>Switch</span>
              </label>

              <br>

              <label class="switch">
                <input type="hidden" name="switch-disabled" value="false" disabled>
                <input type="checkbox" name="switch-disabled" value="true" disabled>
                <span class="switch__slider"></span>
                <span>Switch disabled</span>
              </label>

              <br>

              <label class="switch mb-0 mr-3">
                <input type="hidden" name="switch-1" value="false">
                <input type="checkbox" name="switch-1" value="true">
                <span class="switch__slider"></span>
                <span>Switch 1</span>
              </label>

              <label class="switch mb-0 mr-3">
                <input type="hidden" name="switch-2" value="false">
                <input type="checkbox" name="switch-2" value="true">
                <span class="switch__slider"></span>
                <span>Switch 2</span>
              </label>

              <label class="switch mb-0 mr-3">
                <input type="hidden" name="switch-3" value="false">
                <input type="checkbox" name="switch-3" value="true">
                <span class="switch__slider"></span>
                <span>Switch 3</span>
              </label>

            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Groups</h5>
            </div>
            <div class="box__body">

              <div class="input-group">
                <span>@</span>
                <input type="text" placeholder="Username">
              </div>

              <div class="input-group">
                <input type="text" placeholder="Email">
                <span>@example.com</span>
              </div>

              <div class="input-group">
                <span><i class="ti ti-calendar"></i></span>
                <input type="text" data-picker="date">
              </div>

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

              <div class="input-group">
                <button type="button" class="cursor-pointer"><i class="ti ti-minus"></i></button>
                <input type="number" placeholder="Amount">
                <button type="button" class="cursor-pointer"><i class="ti ti-plus"></i></button>
              </div>

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
              <select data-placeholder="Single">
                <option data-placeholder="true"></option>
                <option>One</option>
                <option>Two</option>
                <option>Three</option>
                <option disabled>Disabled</option>
              </select>

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
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Dates</h5>
            </div>
            <div class="box__body">

              <div class="row cols-xs-2 gap-xs">

                <div class="col">
                  <label>Date</label>
                  <input type="text" data-picker="date">
                </div>

                <div class="col">
                  <label>DateTime</label>
                  <input type="text" data-picker="datetime">
                </div>

                <div class="col">
                  <label>Multiple</label>
                  <input type="text" data-picker="date" data-multiple>
                </div>

                <div class="col">
                  <label>Range</label>
                  <input type="text" data-picker="date" data-range>
                </div>

                <div class="col">
                  <label>Month picker</label>
                  <input type="text" data-picker="month">
                </div>

                <div class="col">
                  <label>Time picker</label>
                  <input type="text" data-picker="time">
                </div>

              </div>

            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Input Masks</h5>
            </div>
            <div class="box__body">

              <div class="row cols-xs-2 gap-xs">

                <div class="col">
                  <label>Phone</label>
                  <input type="text" data-maska="+38 (###) ### ## ##">
                </div>

                <div class="col">
                  <label>HEX-color</label>
                  <input type="text" data-maska="!#HHHHHH" data-maska-tokens="H:[0-9a-fA-F]">
                </div>

                <div class="col">
                  <label>IP-address</label>
                  <input type="text" data-maska="#00.#00.#00.#00" data-maska-tokens="0:[0-9]:optional">
                </div>

                <div class="col">
                  <label>Money</label>
                  <input type="text" data-maska="0.99" data-maska-tokens="0:\d:multiple|9:\d:optional">
                </div>

              </div>

            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Сolor picker</h5>
            </div>
            <div class="box__body">
              <input type="color" placeholder="Input">
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Range slider</h5>
            </div>
            <div class="box__body">
              <input type="range" min="1" max="100" step="1" value="1">
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>

</div>

<?php Theme::template('ui-footer'); ?>
