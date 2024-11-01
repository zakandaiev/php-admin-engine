<?php Theme::template('ui-header', ['title' => 'Cards']); ?>

<div class="mt-2">
  <div class="row cols-xs-1 cols-md-3 gap-xs">

    <div class="col">
      <div class="box">
        <div class="box__image">
          <img src="https://dummyimage.com/600x400/ccc/fff" alt="Card image">
        </div>

        <div class="box__header">
          <h4 class="box__title">Card with image and links</h4>
          <h5 class="box__subtitle">Card subtitle</h5>
        </div>

        <div class="box__body">
          Some quick example text to build on the card title and make up the bulk of the card's content.
        </div>

        <div class="box__footer">
          <a href="#">Link</a>
          <a href="#">Another link</a>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__image">
          <img src="https://dummyimage.com/600x400/ccc/fff" alt="Card image">
        </div>

        <div class="box__header">
          <h4 class="box__title">Card with image and button</h4>
          <h5 class="box__subtitle">Card subtitle</h5>
        </div>

        <div class="box__body">
          Some quick example text to build on the card title and make up the bulk of the card's content.
        </div>

        <div class="box__footer">
          <a href="#" class="btn btn_primary">Go somewhere</a>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__image">
          <img src="https://dummyimage.com/600x400/ccc/fff" alt="Card image">
        </div>

        <div class="box__header">
          <h4 class="box__title">Card with image and list</h4>
          <h5 class="box__subtitle">Card subtitle</h5>
        </div>

        <div class="list-group">
          <span class="list-group__item">Cras justo odio</span>
          <span class="list-group__item">Dapibus ac facilisis in</span>
          <span class="list-group__item">Vestibulum at eros</span>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Card with links</h4>
        </div>

        <div class="box__body">
          Some quick example text to build on the card title and make up the bulk of the card's content.
        </div>

        <div class="box__footer">
          <a href="#">Link</a>
          <a href="#">Another link</a>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Card with button</h4>
        </div>

        <div class="box__body">
          Some quick example text to build on the card title and make up the bulk of the card's content.
        </div>

        <div class="box__footer">
          <a href="#" class="btn btn_primary">Go somewhere</a>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Card with list of links</h4>
        </div>

        <nav class="list-group">
          <a href="#" class="list-group__item">Link #1</a>
          <a href="#" class="list-group__item active">Link #2</a>
          <a href="#" class="list-group__item">Link #3</a>
        </nav>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__header box__header_actions">
          <div class="box__title m-0">
            <span>Card with actions</span>

            <div class="box__actions dropdown">
              <i class="ti ti-dots"></i>

              <div class="dropdown__menu" data-position="bottom-right">
                <a class="dropdown__item" href="#">Action</a>
                <a class="dropdown__item" href="#">Another action</a>
                <a class="dropdown__item" href="#">Something else here</a>
              </div>
            </div>
          </div>
        </div>

        <div class="box__body">
          Some quick example text to build on the card title and make up the bulk of the card's content.
        </div>
      </div>
    </div>

    <div class="col fill">
      <div class="row gap-xs cols-xs-1 cols-sm-2 cols-lg-3">

        <div class="col">
          <div class="box h-100">
            <div class="box__header box__header_icon">
              <h4 class="box__title">
                <span>Stats primary card</span>
                <span class="box__icon box__icon_primary">
                  <i class="ti ti-currency-dollar"></i>
                </span>
              </h4>
            </div>

            <div class="box__body">
              <h3 class="mt--2 mb-1">35</h3>
              <span class="label label_light label_primary">+0.75%</span>
              <span class="color-muted">since last month</span>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box h-100">
            <div class="box__header box__header_icon">
              <h4 class="box__title">
                <span>Stats secondary card</span>
                <span class="box__icon box__icon_secondary">
                  <i class="ti ti-currency-dollar"></i>
                </span>
              </h4>
            </div>

            <div class="box__body mt-auto">
              <h3 class="mt--2 mb-1">35</h3>
              <span class="label label_light label_secondary">-0.75%</span>
              <span class="color-muted">since last month</span>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box h-100">
            <div class="box__header box__header_icon">
              <h4 class="box__title">
                <span>Stats info card</span>
                <span class="box__icon box__icon_info">
                  <i class="ti ti-currency-dollar"></i>
                </span>
              </h4>
            </div>

            <div class="box__body mt-auto">
              <h3 class="mt--2 mb-1">35</h3>
              <span class="label label_light label_info">+0.25%</span>
              <span class="color-muted">since last month</span>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box h-100">
            <div class="box__header box__header_icon">
              <h4 class="box__title">
                <span>Stats success card</span>
                <span class="box__icon box__icon_success">
                  <i class="ti ti-currency-dollar"></i>
                </span>
              </h4>
            </div>

            <div class="box__body mt-auto">
              <h3 class="mt--2 mb-1">35</h3>
              <span class="label label_light label_success">+7.75%</span>
              <span class="color-muted">since last month</span>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box h-100">
            <div class="box__header box__header_icon">
              <h4 class="box__title">
                <span>Stats error card</span>
                <span class="box__icon box__icon_error">
                  <i class="ti ti-currency-dollar"></i>
                </span>
              </h4>
            </div>

            <div class="box__body mt-auto">
              <h3 class="mt--2 mb-1">35</h3>
              <span class="label label_light label_error">-7.75%</span>
              <span class="color-muted">since last month</span>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box h-100">
            <div class="box__header box__header_icon">
              <h4 class="box__title">
                <span>Stats warning card</span>
                <span class="box__icon box__icon_warning">
                  <i class="ti ti-currency-dollar"></i>
                </span>
              </h4>
            </div>

            <div class="box__body mt-auto">
              <h3 class="mt--2 mb-1">35</h3>
              <span class="label label_light label_warning">+1.25%</span>
              <span class="color-muted">since last month</span>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>

<?php Theme::template('ui-footer'); ?>
