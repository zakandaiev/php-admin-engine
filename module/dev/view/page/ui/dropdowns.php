<?php Theme::template('ui-header', ['title' => 'Dropdowns']); ?>

<div class="mt-2">
  <div class="row fill gap-xs cols-xs-1 cols-md-2">
    <div class="col">
      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Dropdown Styles</h4>
        </div>

        <div class="box__body text-center">
          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Basic</button>

            <div class="dropdown__menu">
              <a class="dropdown__item" href="#">Action</a>
              <a class="dropdown__item" href="#">Another action</a>
              <a class="dropdown__item" href="#">Something else here</a>
            </div>
          </div>

          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Basic keep open</button>

            <div class="dropdown__menu" data-keep-open>
              <a class="dropdown__item" href="#">Action</a>
              <a class="dropdown__item" href="#">Another action</a>
              <a class="dropdown__item" href="#">Something else here</a>
            </div>
          </div>

          <br>

          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Active link</button>

            <div class="dropdown__menu">
              <a class="dropdown__item" href="#">Regular link</a>
              <a class="dropdown__item active" href="#">Active link</a>
              <a class="dropdown__item" href="#">Another link</a>
            </div>
          </div>

          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Disabled link</button>

            <div class="dropdown__menu">
              <a class="dropdown__item" href="#">Regular link</a>
              <a class="dropdown__item disabled" href="#">Disabled link</a>
              <a class="dropdown__item" href="#">Another link</a>
            </div>
          </div>

          <br>

          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Header</button>

            <div class="dropdown__menu">
              <p class="dropdown__header">Header text</p>
              <a class="dropdown__item" href="#">Action</a>
              <a class="dropdown__item" href="#">Another action</a>
              <a class="dropdown__item" href="#">Something else here</a>
            </div>
          </div>

          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Divider</button>

            <div class="dropdown__menu">
              <a class="dropdown__item" href="#">Action</a>
              <a class="dropdown__item" href="#">Another action</a>
              <a class="dropdown__item" href="#">Something else here</a>

              <div class="dropdown__divider"></div>

              <a class="dropdown__item" href="#">Separated link</a>
            </div>
          </div>

          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Text</button>

            <div class="dropdown__menu">
              <p class="dropdown__text">
                Some example text that's free-flowing within the dropdown menu.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Dropdown Placements</h4>
        </div>

        <div class="box__body text-center">
          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Top Left</button>

            <div class="dropdown__menu" data-position="top-left">
              <a class="dropdown__item" href="#">Action</a>
              <a class="dropdown__item" href="#">Another action</a>
              <a class="dropdown__item" href="#">Something else here</a>
            </div>
          </div>

          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Top Center</button>

            <div class="dropdown__menu" data-position="top-center">
              <a class="dropdown__item" href="#">Action</a>
              <a class="dropdown__item" href="#">Another action</a>
              <a class="dropdown__item" href="#">Something else here</a>
            </div>
          </div>

          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Top Right</button>

            <div class="dropdown__menu" data-position="top-right">
              <a class="dropdown__item" href="#">Action</a>
              <a class="dropdown__item" href="#">Another action</a>
              <a class="dropdown__item" href="#">Something else here</a>
            </div>
          </div>

          <br>

          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Bottom Left</button>

            <div class="dropdown__menu" data-position="bottom-left">
              <a class="dropdown__item" href="#">Action</a>
              <a class="dropdown__item" href="#">Another action</a>
              <a class="dropdown__item" href="#">Something else here</a>
            </div>
          </div>

          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Bottom Center</button>

            <div class="dropdown__menu" data-position="bottom-center">
              <a class="dropdown__item" href="#">Action</a>
              <a class="dropdown__item" href="#">Another action</a>
              <a class="dropdown__item" href="#">Something else here</a>
            </div>
          </div>

          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Bottom Right</button>

            <div class="dropdown__menu" data-position="bottom-right">
              <a class="dropdown__item" href="#">Action</a>
              <a class="dropdown__item" href="#">Another action</a>
              <a class="dropdown__item" href="#">Something else here</a>
            </div>
          </div>

          <br>

          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Left Top</button>

            <div class="dropdown__menu" data-position="left-top">
              <a class="dropdown__item" href="#">Action</a>
              <a class="dropdown__item" href="#">Another action</a>
              <a class="dropdown__item" href="#">Something else here</a>
            </div>
          </div>

          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Left Center</button>

            <div class="dropdown__menu" data-position="left-center">
              <a class="dropdown__item" href="#">Action</a>
              <a class="dropdown__item" href="#">Another action</a>
              <a class="dropdown__item" href="#">Something else here</a>
            </div>
          </div>

          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Left Bottom</button>

            <div class="dropdown__menu" data-position="left-bottom">
              <a class="dropdown__item" href="#">Action</a>
              <a class="dropdown__item" href="#">Another action</a>
              <a class="dropdown__item" href="#">Something else here</a>
            </div>
          </div>

          <br>

          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Right Top</button>

            <div class="dropdown__menu" data-position="right-top">
              <a class="dropdown__item" href="#">Action</a>
              <a class="dropdown__item" href="#">Another action</a>
              <a class="dropdown__item" href="#">Something else here</a>
            </div>
          </div>

          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Right Center</button>

            <div class="dropdown__menu" data-position="right-center">
              <a class="dropdown__item" href="#">Action</a>
              <a class="dropdown__item" href="#">Another action</a>
              <a class="dropdown__item" href="#">Something else here</a>
            </div>
          </div>

          <div class="dropdown d-inline-block m-1">
            <button type="button" class="btn btn_secondary">Right Bottom</button>

            <div class="dropdown__menu" data-position="right-bottom">
              <a class="dropdown__item" href="#">Action</a>
              <a class="dropdown__item" href="#">Another action</a>
              <a class="dropdown__item" href="#">Something else here</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<?php Theme::template('ui-footer'); ?>
