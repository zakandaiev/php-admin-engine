<?php Theme::template('ui-header', ['title' => 'Grid']); ?>

<div class="box">
  <div class="box__body">
    <h5>Example #1: grow and auto width columns controlled with <span class="label">.fill</span> class</h5>

    <div class="row fill mt-2">
      <div class="col">
        <div class="box border text-center">
          <div class="box__body">Grow column</div>
        </div>
      </div>

      <div class="col col-auto">
        <div class="box border text-center">
          <div class="box__body">Auto column</div>
        </div>
      </div>

      <div class="col">
        <div class="box border text-center">
          <div class="box__body">Grow column</div>
        </div>
      </div>
    </div>

    <br>

    <h5>Example #2: equal-width columns defined in <span class="label">.row</span> with <span class="label">.cols-{breakpoint}-{size}</span> syntax</h5>

    <div class="row cols-xs-2 cols-md-4 mt-2">
      <?php foreach (range(1, 6) as $idx): ?>
        <div class="col">
          <div class="box border text-center">
            <div class="box__body"><?= $idx ?> column</div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <br>

    <h5>Example #3: control column width in each <span class="label">.col</span> with <span class="label">.col-{breakpoint}-{size}</span> syntax</h5>

    <div class="row mt-2">
      <div class="col-xs-12 col-md-3">
        <div class="box border text-center">
          <div class="box__body">1 column</div>
        </div>
      </div>

      <div class="col-xs-12 col-md-6">
        <div class="box border text-center">
          <div class="box__body">2 column</div>
        </div>
      </div>

      <div class="col-xs-12 col-md-3">
        <div class="box border text-center">
          <div class="box__body">3 column</div>
        </div>
      </div>

      <div class="col-xs-12">
        <div class="box border text-center">
          <div class="box__body">4 column</div>
        </div>
      </div>
    </div>

    <br>

    <h5>Example #4: offset columns with <span class="label">.offset-{breakpoint}-{size}</span> syntax</h5>

    <div class="row mt-2">
      <div class="col-xs-12 col-md-3">
        <div class="box border text-center">
          <div class="box__body">1 column</div>
        </div>
      </div>

      <div class="col-xs-12 col-md-3 offset-md-3">
        <div class="box border text-center">
          <div class="box__body">2 column</div>
        </div>
      </div>

      <div class="col-xs-12 col-md-3 offset-md-3">
        <div class="box border text-center">
          <div class="box__body">3 column</div>
        </div>
      </div>

      <div class="col-xs-12 col-md-3 offset-md-3">
        <div class="box border text-center">
          <div class="box__body">4 column</div>
        </div>
      </div>
    </div>

    <br>

    <h5>Example #5: row gaps controlled with <span class="label">.gap-{breakpoint}</span> for XY axes, <span class="label">.gap-{breakpoint}-x</span> for X axis or <span class="label">.gap-{breakpoint}-y</span> for Y axis syntax defined in <span class="label">.row</span> class</h5>

    <div class="row fill gap-xs cols-xs-2 cols-md-4 mt-2">
      <?php foreach (range(1, 6) as $idx): ?>
        <div class="col">
          <div class="box border text-center">
            <div class="box__body"><?= $idx ?> column</div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php Theme::template('ui-footer'); ?>
