<?php Theme::header(); ?>

<div class="row gap-xs">
  <div class="col-xs-12 col-md-8 order-xs-2 order-md-1">

    <div class="section section_offset-bottom">
      <h1>[Work in progress!] Route guide</h1>
    </div>

    <section class="section section_offset border-top">
      <h2 class="section__title">Overview</h2>

      <div class="section__body">
        <p>This is an example template for test page with some examples.</p>
      </div>
    </section>

    <section class="section section_offset border-top">
      <h2 class="section__title">Template data inject</h2>

      <div class="section__body">
        <h4 class="font-regular">$dataFromControllerString:</h4>
        <div class="box"><?php debug($dataFromControllerString); ?></div>

        <h4 class="font-regular">$dataFromControllerRouteOptions:</h4>
        <div class="box"><?php debug($dataFromControllerRouteOptions); ?></div>

        <h4 class="font-regular">Or get whole data from View class - viewGetData():</h4>
        <div class="box"><?php debug(viewGetData()); ?></div>

        <h4 class="font-regular">Or get data about current route - getRouteProperty():</h4>
        <div class="box"><?php debug(getRouteProperty()); ?></div>
      </div>
    </section>

    <section class="section section_offset border-top">
      <h2 class="section__title">Language interpolation</h2>

      <div class="section__body">
        <h4 class="font-regular">t('test_var', 'dummy'):</h4>
        <div class="box"><?= t('test_var', 'dummy') ?></div>

        <h4 class="font-regular">t('test_named_vars', ['keyOne' => 'dummyOne', 'keyTwo' => 'dummyTwo']):</h4>
        <div class="box"><?= t('test_named_vars', ['keyOne' => 'dummyOne', 'keyTwo' => 'dummyTwo']) ?></div>

        <h4 class="font-regular">t('test_pluralization', 1):</h4>
        <div class="box"><?= t('test_pluralization', 1) ?></div>

        <h4 class="font-regular">t('test_pluralization', 5):</h4>
        <div class="box"><?= t('test_pluralization', 5) ?></div>
      </div>
    </section>

  </div>

  <div class="col-xs-12 col-md-3 offset-md-1 align-self-start position-sticky order-xs-1 order-md-2 bg-body">
    <div class="section__navigation"></div>
  </div>
</div>

<?php Theme::footer(); ?>
