<?php Theme::header(); ?>

<?php Theme::template('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::template('navbar/top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <nav class="breadcrumb">
        <span class="breadcrumb__item"><a href="<?= Route::link('dashboard') ?>">Home</a></span>
        <span class="breadcrumb__item"><a href="<?= Route::link('ui-home') ?>">Dev UI</a></span>
        <span class="breadcrumb__item">Dropdowns</span>
      </nav>

      <h2 class="section__title">Dropdowns</h2>

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

                <div class="dropdown d-inline-block m-1" data-keep-open>
                  <button type="button" class="btn btn_secondary">Basic keep open</button>

                  <div class="dropdown__menu">
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
                <div class="dropdown d-inline-block m-1 dropdown_top-left">
                  <button type="button" class="btn btn_secondary">Top Left</button>

                  <div class="dropdown__menu">
                    <a class="dropdown__item" href="#">Action</a>
                    <a class="dropdown__item" href="#">Another action</a>
                    <a class="dropdown__item" href="#">Something else here</a>
                  </div>
                </div>

                <div class="dropdown d-inline-block m-1 dropdown_top-center">
                  <button type="button" class="btn btn_secondary">Top Center</button>

                  <div class="dropdown__menu">
                    <a class="dropdown__item" href="#">Action</a>
                    <a class="dropdown__item" href="#">Another action</a>
                    <a class="dropdown__item" href="#">Something else here</a>
                  </div>
                </div>

                <div class="dropdown d-inline-block m-1 dropdown_top-right">
                  <button type="button" class="btn btn_secondary">Top Right</button>

                  <div class="dropdown__menu">
                    <a class="dropdown__item" href="#">Action</a>
                    <a class="dropdown__item" href="#">Another action</a>
                    <a class="dropdown__item" href="#">Something else here</a>
                  </div>
                </div>

                <br>

                <div class="dropdown d-inline-block m-1 dropdown_left-top">
                  <button type="button" class="btn btn_secondary">Left Top</button>

                  <div class="dropdown__menu">
                    <a class="dropdown__item" href="#">Action</a>
                    <a class="dropdown__item" href="#">Another action</a>
                    <a class="dropdown__item" href="#">Something else here</a>
                  </div>
                </div>

                <div class="dropdown d-inline-block m-1 dropdown_left-center">
                  <button type="button" class="btn btn_secondary">Left Center</button>

                  <div class="dropdown__menu">
                    <a class="dropdown__item" href="#">Action</a>
                    <a class="dropdown__item" href="#">Another action</a>
                    <a class="dropdown__item" href="#">Something else here</a>
                  </div>
                </div>

                <div class="dropdown d-inline-block m-1 dropdown_left-bottom">
                  <button type="button" class="btn btn_secondary">Left Bottom</button>

                  <div class="dropdown__menu">
                    <a class="dropdown__item" href="#">Action</a>
                    <a class="dropdown__item" href="#">Another action</a>
                    <a class="dropdown__item" href="#">Something else here</a>
                  </div>
                </div>

                <br>

                <div class="dropdown d-inline-block m-1 dropdown_right-top">
                  <button type="button" class="btn btn_secondary">Right Top</button>

                  <div class="dropdown__menu">
                    <a class="dropdown__item" href="#">Action</a>
                    <a class="dropdown__item" href="#">Another action</a>
                    <a class="dropdown__item" href="#">Something else here</a>
                  </div>
                </div>

                <div class="dropdown d-inline-block m-1 dropdown_right-center">
                  <button type="button" class="btn btn_secondary">Right Center</button>

                  <div class="dropdown__menu">
                    <a class="dropdown__item" href="#">Action</a>
                    <a class="dropdown__item" href="#">Another action</a>
                    <a class="dropdown__item" href="#">Something else here</a>
                  </div>
                </div>

                <div class="dropdown d-inline-block m-1 dropdown_right-bottom">
                  <button type="button" class="btn btn_secondary">Right Bottom</button>

                  <div class="dropdown__menu">
                    <a class="dropdown__item" href="#">Action</a>
                    <a class="dropdown__item" href="#">Another action</a>
                    <a class="dropdown__item" href="#">Something else here</a>
                  </div>
                </div>

                <br>

                <div class="dropdown d-inline-block m-1 dropdown_bottom-left">
                  <button type="button" class="btn btn_secondary">Bottom Left</button>

                  <div class="dropdown__menu">
                    <a class="dropdown__item" href="#">Action</a>
                    <a class="dropdown__item" href="#">Another action</a>
                    <a class="dropdown__item" href="#">Something else here</a>
                  </div>
                </div>

                <div class="dropdown d-inline-block m-1 dropdown_bottom-center">
                  <button type="button" class="btn btn_secondary">Bottom Center</button>

                  <div class="dropdown__menu">
                    <a class="dropdown__item" href="#">Action</a>
                    <a class="dropdown__item" href="#">Another action</a>
                    <a class="dropdown__item" href="#">Something else here</a>
                  </div>
                </div>

                <div class="dropdown d-inline-block m-1 dropdown_bottom-right">
                  <button type="button" class="btn btn_secondary">Bottom Right</button>

                  <div class="dropdown__menu">
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
  </section>

  <?php Theme::template('navbar/bottom'); ?>

</main>

<?php Theme::footer(); ?>
