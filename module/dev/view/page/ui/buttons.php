<?php Theme::template('ui-header', ['title' => 'Buttons']); ?>

<div class="mt-2">
  <div class="row cols-xs-1 cols-md-2 gap-xs">

    <div class="col">
      <div class="box h-100">
        <div class="box__header">
          <h4 class="box__title">Basic Buttons</h4>
          <h5 class="box__subtitle">Basic buttons styles</h5>
        </div>

        <div class="box__body text-center">
          <div>
            <?php foreach (getButtonColors() as $color): ?>
              <button class="btn btn_<?= $color ?>"><?= ucfirst($color) ?></button>
            <?php endforeach; ?>
          </div>

          <div class="mt-6">
            <?php foreach (getButtonColors() as $color): ?>
              <button class="btn btn_<?= $color ?> btn_disabled" disabled><?= ucfirst($color) ?></button>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box h-100">
        <div class="box__header">
          <h4 class="box__title">Outline Buttons</h4>
          <h5 class="box__subtitle">Outline buttons styles</h5>
        </div>

        <div class="box__body text-center">
          <div>
            <?php foreach (getButtonColors() as $color): ?>
              <button class="btn btn_outline btn_<?= $color ?>"><?= ucfirst($color) ?></button>
            <?php endforeach; ?>
          </div>

          <div class="mt-6">
            <?php foreach (getButtonColors() as $color): ?>
              <button class="btn btn_outline btn_<?= $color ?> btn_disabled" disabled><?= ucfirst($color) ?></button>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box h-100">
        <div class="box__header">
          <h4 class="box__title">Icon Buttons</h4>
          <h5 class="box__subtitle">Buttons with icons</h5>
        </div>

        <div class="box__body text-center">
          <div>
            <?php foreach (getButtonColors() as $color): ?>
              <button class="btn btn_<?= $color ?>"><i class="ti <?= getButtonIcon($color) ?>"></i> <?= ucfirst($color) ?></button>
            <?php endforeach; ?>
          </div>

          <div class="mt-6">
            <?php foreach (getButtonColors() as $color): ?>
              <button class="btn btn_icon btn_<?= $color ?>"><i class="ti <?= getButtonIcon($color) ?>"></i></button>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box h-100">
        <div class="box__header">
          <h4 class="box__title">Social Buttons</h4>
          <h5 class="box__subtitle">Social buttons with icons</h5>
        </div>

        <div class="box__body text-center">
          <div>
            <?php foreach (getButtonSocials() as $color): ?>
              <button class="btn btn_<?= $color ?>"><i class="ti ti-brand-<?= $color ?>"></i> <?= ucfirst($color) ?></button>
            <?php endforeach; ?>
          </div>

          <div class="mt-6">
            <?php foreach (getButtonSocials() as $color): ?>
              <button class="btn btn_icon btn_<?= $color ?>"><i class="ti ti-brand-<?= $color ?>"></i></button>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box h-100">
        <div class="box__header">
          <h4 class="box__title">Button Sizes</h4>
          <h5 class="box__subtitle">Different button sizes</h5>
        </div>

        <div class="box__body text-center">
          <div>
            <button class="btn btn_sm btn_primary">Small</button>
            <button class="btn btn_primary">Medium</button>
            <button class="btn btn_lg btn_primary">Large</button>
          </div>

          <div class="mt-6">
            <button class="btn btn_sm btn_primary"><i class="ti ti-world"></i> Small</button>
            <button class="btn btn_primary"><i class="ti ti-world"></i> Medium</button>
            <button class="btn btn_lg btn_primary"><i class="ti ti-world"></i> Large</button>
          </div>

          <div class="mt-6">
            <button class="btn btn_sm btn_icon btn_primary"><i class="ti ti-world"></i></button>
            <button class="btn btn_icon btn_primary"><i class="ti ti-world"></i></button>
            <button class="btn btn_lg btn_icon btn_primary"><i class="ti ti-world"></i></button>
          </div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box h-100">
        <div class="box__header">
          <h4 class="box__title">Button Dropdown (TODO)</h4>
          <h5 class="box__subtitle">Dropdowns with buttons</h5>
        </div>

        <div class="box__body text-center">
          <div>
            <?php foreach (getButtonColors() as $color): ?>
              <button class="btn btn_<?= $color ?>"><?= ucfirst($color) ?> <i class="ti ti-chevron-down"></i></button>
            <?php endforeach; ?>
          </div>

          <div class="mt-6">
            <?php foreach (getButtonColors() as $color): ?>
              <button class="btn btn_sm btn_<?= $color ?>"><?= ucfirst($color) ?> <i class="ti ti-chevron-down"></i></button>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box h-100">
        <div class="box__header">
          <h4 class="box__title">Button Group</h4>
          <h5 class="box__subtitle">Button Group styles</h5>
        </div>

        <div class="box__body">
          <p>Horizontal button group</p>
          <div class="btn-group mb-3">
            <button class="btn btn_lg btn_secondary">Button</button>
            <button class="btn btn_lg btn_secondary">Button</button>
            <button class="btn btn_lg btn_secondary">Button</button>
          </div>
          <br>
          <div class="btn-group mb-3">
            <button class="btn btn_secondary">Button</button>
            <button class="btn btn_secondary">Button</button>
            <button class="btn btn_secondary">Button</button>
          </div>
          <br>
          <div class="btn-group mb-3">
            <button class="btn btn_sm btn_secondary">Button</button>
            <button class="btn btn_sm btn_secondary">Button</button>
            <button class="btn btn_sm btn_secondary">Button</button>
          </div>
          <p>Horizontal button group full width</p>
          <div class="btn-group btn-group_fit mb-6">
            <button class="btn btn_secondary">Button</button>
            <button class="btn btn_secondary">Button</button>
            <button class="btn btn_secondary">Button</button>
          </div>
          <p>Vertical button group</p>
          <div class="btn-group btn-group_vertical mb-6">
            <button class="btn btn_info">Button</button>
            <button class="btn btn_info">Button</button>
            <button class="btn btn_info">Button</button>
          </div>
          <div class="btn-group btn-group_vertical mb-6">
            <button class="btn btn_success">Button</button>
            <button class="btn btn_success">Button</button>
            <button class="btn btn_success">Button</button>
          </div>
          <div class="btn-group btn-group_vertical mb-6">
            <button class="btn btn_warning">Button</button>
            <button class="btn btn_warning">Button</button>
            <button class="btn btn_warning">Button</button>
          </div>
          <div class="btn-group btn-group_vertical mb-6">
            <button class="btn btn_error">Button</button>
            <button class="btn btn_error">Button</button>
            <button class="btn btn_error">Button</button>
          </div>
          <p>Vertical button group full width</p>
          <div class="btn-group btn-group_vertical btn-group_fit">
            <button class="btn btn_primary">Button</button>
            <button class="btn btn_primary">Button</button>
            <button class="btn btn_primary">Button</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<?php Theme::template('ui-footer'); ?>
