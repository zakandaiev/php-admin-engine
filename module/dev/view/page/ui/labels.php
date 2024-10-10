<?php Theme::template('ui-header', ['title' => 'Labels']); ?>

<div class="box">
  <div class="box__body">
    <div>
      <?php foreach (getButtonColors() as $color): ?>
        <span class="label label_<?= $color ?>"><?= ucfirst($color) ?></span>
      <?php endforeach; ?>
    </div>

    <div class="mt-3">
      <?php foreach (getButtonAccentColors() as $color): ?>
        <span class="label label_light label_<?= $color ?>"><?= ucfirst($color) ?></span>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php Theme::template('ui-footer'); ?>
